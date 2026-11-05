<?php
require_once __DIR__.'/../includes/bootstrap.php';
require_once __DIR__.'/../includes/market.php';
try {
    $items = [];
    foreach (market_assets() as $asset) {
        $q = quote_asset($asset);
        $q['market'] = $asset['market'];
        $q['kind'] = $asset['kind'];
        $q['reading'] = 'Sem classificação';
        if (is_numeric($q['change'])) {
            if ((float)$q['change'] <= -5) $q['reading'] = 'Queda acentuada';
            elseif ((float)$q['change'] <= -3) $q['reading'] = 'Queda relevante';
            elseif ((float)$q['change'] < 0) $q['reading'] = 'Variação negativa';
            elseif ((float)$q['change'] >= 3) $q['reading'] = 'Alta relevante';
            elseif ((float)$q['change'] > 0) $q['reading'] = 'Variação positiva';
            else $q['reading'] = 'Estável';
        }
        $items[] = $q;
    }
    json_response(['ok'=>true,'items'=>$items,'updated_at'=>date(DATE_ATOM)]);
} catch (Throwable $e) {
    json_response(['ok'=>false,'message'=>'Não foi possível consultar o mercado. Verifique banco de dados e configuração.'],500);
}
