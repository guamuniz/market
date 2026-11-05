<?php
function market_assets(): array {
    return [
        ['symbol'=>'^BVSP','name'=>'Ibovespa','market'=>'BR','provider'=>'brapi','kind'=>'index'],
        ['symbol'=>'PETR4','name'=>'Petrobras PN','market'=>'BR','provider'=>'brapi','kind'=>'stock'],
        ['symbol'=>'VALE3','name'=>'Vale ON','market'=>'BR','provider'=>'brapi','kind'=>'stock'],
        ['symbol'=>'ITUB4','name'=>'Itaú Unibanco PN','market'=>'BR','provider'=>'brapi','kind'=>'stock'],
        ['symbol'=>'MGLU3','name'=>'Magazine Luiza ON','market'=>'BR','provider'=>'brapi','kind'=>'stock'],
        ['symbol'=>'AAPL','name'=>'Apple','market'=>'US','provider'=>'twelve','kind'=>'stock'],
        ['symbol'=>'MSFT','name'=>'Microsoft','market'=>'US','provider'=>'twelve','kind'=>'stock'],
        ['symbol'=>'NVDA','name'=>'NVIDIA','market'=>'US','provider'=>'twelve','kind'=>'stock'],
        ['symbol'=>'SPY','name'=>'SPDR S&P 500 ETF','market'=>'US','provider'=>'twelve','kind'=>'etf'],
        ['symbol'=>'QQQ','name'=>'Invesco Nasdaq-100 ETF','market'=>'US','provider'=>'twelve','kind'=>'etf'],
        ['symbol'=>'DIA','name'=>'SPDR Dow Jones ETF','market'=>'US','provider'=>'twelve','kind'=>'etf'],
        ['symbol'=>'BTC/USD','name'=>'Bitcoin','market'=>'CRYPTO','provider'=>'twelve','kind'=>'crypto'],
        ['symbol'=>'ETH/USD','name'=>'Ethereum','market'=>'CRYPTO','provider'=>'twelve','kind'=>'crypto'],
        ['symbol'=>'EUR/USD','name'=>'Euro / Dólar','market'=>'FX','provider'=>'twelve','kind'=>'forex'],
    ];
}

function quote_asset(array $asset): array {
    global $config;
    $symbol = $asset['symbol'];
    $cacheSeconds = max(60, (int)($config['market']['cache_seconds'] ?? 300));

    $cached = db()->prepare(
        'SELECT payload FROM market_cache
         WHERE cache_key=? AND fetched_at >= DATE_SUB(NOW(), INTERVAL ? SECOND)'
    );
    $cached->execute([$symbol, $cacheSeconds]);
    if ($row = $cached->fetch()) {
        $data = json_decode($row['payload'], true);
        if (is_array($data)) {
            $data['cached'] = true;
            return $data;
        }
    }

    $result = null;
    $providerError = null;
    $token = trim((string)($config['market']['brapi_token'] ?? ''));

    if ($asset['provider'] === 'brapi') {
        $headers = $token !== '' ? ['Authorization: Bearer ' . $token] : [];

        if ($symbol === '^BVSP') {
            // Ibovespa: rota documentada específica, com campos diretamente em results[0].
            $json = http_get_json('https://brapi.dev/api/quote/%5EBVSP', $headers);
            $item = $json['results'][0] ?? null;
            if (is_array($item) && isset($item['regularMarketPrice'])) {
                $result = [
                    'symbol' => $symbol,
                    'name' => $asset['name'],
                    'price' => is_numeric($item['regularMarketPrice']) ? (float)$item['regularMarketPrice'] : null,
                    'change' => isset($item['regularMarketChangePercent']) && is_numeric($item['regularMarketChangePercent']) ? (float)$item['regularMarketChangePercent'] : null,
                    'currency' => $item['currency'] ?? 'BRL',
                    'updated_at' => $item['regularMarketTime'] ?? ($json['requestedAt'] ?? date(DATE_ATOM)),
                    'source' => 'brapi',
                    'demo' => false,
                    'cached' => false
                ];
            }
        } else {
            $url = 'https://brapi.dev/api/v2/stocks/quote?symbols=' . rawurlencode($symbol);
            $json = http_get_json($url, $headers);
            $item = $json['results'][0]['data'] ?? null;
            if (is_array($item) && isset($item['regularMarketPrice'])) {
                $result = [
                    'symbol' => $symbol,
                    'name' => $item['shortName'] ?? $asset['name'],
                    'price' => is_numeric($item['regularMarketPrice']) ? (float)$item['regularMarketPrice'] : null,
                    'change' => isset($item['regularMarketChangePercent']) && is_numeric($item['regularMarketChangePercent']) ? (float)$item['regularMarketChangePercent'] : null,
                    'currency' => $item['currency'] ?? 'BRL',
                    'updated_at' => $item['regularMarketTime'] ?? ($json['requestedAt'] ?? date(DATE_ATOM)),
                    'source' => 'brapi',
                    'demo' => false,
                    'cached' => false
                ];
            }
        }
    } elseif ($asset['provider'] === 'twelve') {

        $key = trim((string)($config['market']['twelve_data_key'] ?? ''));

        if ($key !== '') {

            // Primeira tentativa: endpoint de cotação
            $url = 'https://api.twelvedata.com/quote?symbol='
                . rawurlencode($symbol)
                . '&apikey=' . rawurlencode($key);

            $json = http_get_json($url);

            if (isset($json['close']) && is_numeric($json['close'])) {

                $previous = isset($json['previous_close'])
                    && is_numeric($json['previous_close'])
                    ? (float)$json['previous_close']
                    : null;

                $price = (float)$json['close'];

                $change = ($previous !== null && $previous != 0)
                    ? (($price - $previous) / $previous) * 100
                    : (
                        isset($json['percent_change'])
                        && is_numeric($json['percent_change'])
                        ? (float)$json['percent_change']
                        : null
                    );

                $result = [
                    'symbol' => $symbol,
                    'name' => $asset['name'],
                    'price' => $price,
                    'change' => $change,
                    'currency' => $json['currency'] ?? (
                        $asset['kind'] === 'forex'
                        ? 'USD'
                        : 'USD'
                    ),
                    'updated_at' => $json['datetime'] ?? date(DATE_ATOM),
                    'source' => 'Twelve Data',
                    'demo' => false,
                    'cached' => false
                ];

            } else {

                // Guarda a mensagem original da API para diagnóstico
                $providerError = $json['message']
                    ?? $json['error']
                    ?? (
                        isset($json['code'])
                        ? 'Código API: ' . $json['code']
                        : 'Cotação indisponível'
                    );

                // Segunda tentativa específica para moedas
                if ($asset['kind'] === 'forex') {

                    $url = 'https://api.twelvedata.com/time_series'
                        . '?symbol=' . rawurlencode($symbol)
                        . '&interval=1day'
                        . '&outputsize=2'
                        . '&apikey=' . rawurlencode($key);

                    $series = http_get_json($url);

                    if (
                        isset($series['values'][0]['close'])
                        && is_numeric($series['values'][0]['close'])
                    ) {

                        $price = (float)$series['values'][0]['close'];

                        $previous = isset($series['values'][1]['close'])
                            && is_numeric($series['values'][1]['close'])
                            ? (float)$series['values'][1]['close']
                            : null;

                        $change = ($previous !== null && $previous != 0)
                            ? (($price - $previous) / $previous) * 100
                            : null;

                        $result = [
                            'symbol' => $symbol,
                            'name' => $asset['name'],
                            'price' => $price,
                            'change' => $change,
                            'currency' => 'USD',
                            'updated_at' => $series['values'][0]['datetime'] ?? date(DATE_ATOM),
                            'source' => 'Twelve Data - Série diária',
                            'demo' => false,
                            'cached' => false
                        ];

                    } else {

                        $providerError = $series['message']
                            ?? $series['error']
                            ?? $providerError;
                    }
                }
            }

        } else {
            $providerError = 'Configure a chave da Twelve Data';
        }
    }

    if (!$result) {
        $result = [
            'symbol' => $symbol, 'name' => $asset['name'], 'price' => null, 'change' => null,
            'currency' => $asset['market'] === 'BR' ? 'BRL' : 'USD',
            'updated_at' => null,
            'source' => $asset['provider'] === 'brapi'
            ? (($symbol === '^BVSP' && $token === '')
                ? 'Configure o token brapi'
                : 'Aguardando brapi')
            : ($providerError ?: 'Aguardando Twelve Data'),
            'demo' => true, 'cached' => false
        ];
    }

    $save = db()->prepare(
        'INSERT INTO market_cache (cache_key,payload,fetched_at) VALUES (?,?,NOW())
         ON DUPLICATE KEY UPDATE payload=VALUES(payload), fetched_at=NOW()'
    );
    $save->execute([$symbol, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
    return $result;
}
