<?php
require_once __DIR__.'/includes/bootstrap.php';
track_event('page_view','radar');
$pageTitle='Radar de quedas';
require __DIR__.'/includes/header.php';
?>
<div class="mb-4"><div class="small-muted mb-2">MONITORAMENTO</div><h1 class="section-title h2">Radar de quedas</h1><p class="text-secondary">Identifique ativos com variações negativas relevantes e investigue o contexto.</p></div>
<div class="panel p-3 p-lg-4">
 <div class="row g-2 mb-3"><div class="col-md-4"><input id="filterSearch" class="form-control" placeholder="Buscar ativo ou símbolo"></div><div class="col-md-3"><select id="filterMarket" class="form-select"><option value="">Todos os mercados</option><option value="BR">Brasil</option><option value="US">Estados Unidos</option><option value="CRYPTO">Cripto</option><option value="FX">Forex</option></select></div><div class="col-md-3"><select id="filterDrop" class="form-select"><option value="-3">Queda maior que 3%</option><option value="-1">Queda maior que 1%</option><option value="-5">Queda maior que 5%</option><option value="0">Todas as variações negativas</option></select></div><div class="col-md-2"><button id="applyFilters" class="btn btn-primary w-100">Filtrar</button></div></div>
 <div class="table-responsive"><table class="table"><thead><tr><th>Ativo</th><th>Mercado</th><th>Preço</th><th>Variação</th><th>Leitura inicial</th></tr></thead><tbody id="radarRows"><tr><td colspan="5">Carregando...</td></tr></tbody></table></div>
</div>
<?php require __DIR__.'/includes/footer.php'; ?>
