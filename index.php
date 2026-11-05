<?php
require_once __DIR__.'/includes/bootstrap.php';
require_once __DIR__.'/includes/market.php';
track_event('page_view','dashboard');
$pageTitle='Mercado em tempo real';
require __DIR__.'/includes/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
 <div><div class="small-muted mb-2"><i class="bi bi-activity me-1"></i> INTELIGÊNCIA DE MERCADO</div><h1 class="section-title h2 mb-1">Visão geral do mercado</h1><p class="text-secondary mb-0">Acompanhe índices, ativos e movimentos relevantes.</p></div>
 <div class="text-secondary small">Atualização conforme disponibilidade das fontes · <span id="lastUpdate">carregando...</span></div>
</div>
<div class="row g-3 mb-4">
 <div class="col-6 col-lg-3"><div class="metric-card"><div class="metric-label">Ativos no radar</div><div class="metric-value" id="assetCount">—</div><div class="small-muted">Brasil e exterior</div></div></div>
 <div class="col-6 col-lg-3"><div class="metric-card"><div class="metric-label">Quedas relevantes</div><div class="metric-value negative" id="dropCount">—</div><div class="small-muted">Variação abaixo de -3%</div></div></div>
 <div class="col-6 col-lg-3"><div class="metric-card"><div class="metric-label">Em alta</div><div class="metric-value positive" id="upCount">—</div><div class="small-muted">Variação positiva</div></div></div>
 <div class="col-6 col-lg-3"><div class="metric-card"><div class="metric-label">Mercados</div><div class="metric-value">04</div><div class="small-muted">B3 · EUA · FX · Cripto</div></div></div>
</div>
<div class="row g-3">
 <div class="col-lg-8">
  <div class="panel p-3 p-lg-4">
   <div class="d-flex justify-content-between align-items-center mb-3"><div><h2 class="h5 mb-1">Ativos monitorados</h2><div class="small-muted">Cotações e variações disponíveis</div></div><span class="badge badge-soft">RADAR XYZ</span></div>
   <div class="table-responsive"><table class="table table-hover mb-0"><thead><tr><th>Ativo</th><th>Mercado</th><th>Preço</th><th>Variação</th><th>Status</th></tr></thead><tbody id="marketRows"><tr><td colspan="5" class="text-secondary py-4">Carregando dados...</td></tr></tbody></table></div>
  </div>
 </div>
 <div class="col-lg-4">
  <div class="panel p-3 p-lg-4 h-100">
   <h2 class="h5 mb-1">Distribuição de movimentos</h2><p class="small-muted">Ativos com cotação válida nesta consulta</p>
   <div class="chart-wrap"><canvas id="movementChart"></canvas></div>
   <div class="small-muted mt-3">A classificação é baseada na variação percentual retornada pelo provedor.</div>
  </div>
 </div>
</div>
<div class="panel p-3 p-lg-4 mt-3">
 <div class="d-flex flex-wrap justify-content-between gap-2 align-items-center"><div><h2 class="h5 mb-1">Radar de quedas</h2><p class="small-muted mb-0">Movimentos negativos que merecem investigação, não sinais automáticos de compra.</p></div><a class="btn btn-outline-light btn-sm" href="radar.php">Ver radar completo <i class="bi bi-arrow-right"></i></a></div>
 <div id="dropCards" class="row g-3 mt-1"><div class="col-12 text-secondary small">Aguardando cotações...</div></div>
</div>
<div class="alert alert-dark border-secondary-subtle mt-3 small mb-0"><i class="bi bi-info-circle me-2"></i>Dados de mercado podem ter atraso e dependem dos planos, cobertura e licenças dos provedores. Este painel é informativo e não constitui recomendação de investimento.</div>
<?php require __DIR__.'/includes/footer.php'; ?>
