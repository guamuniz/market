<?php
require_once __DIR__.'/../includes/bootstrap.php';require_admin();
$pdo=db();
$total=(int)$pdo->query('SELECT COUNT(*) FROM analytics_events')->fetchColumn();
$today=(int)$pdo->query('SELECT COUNT(*) FROM analytics_events WHERE DATE(created_at)=CURDATE()')->fetchColumn();
$visitors=(int)$pdo->query('SELECT COUNT(DISTINCT visitor_hash) FROM analytics_events WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)')->fetchColumn();
$interactions=(int)$pdo->query("SELECT COUNT(*) FROM analytics_events WHERE event_name <> 'page_view'")->fetchColumn();
$pages=$pdo->query('SELECT page_path, COUNT(*) total FROM analytics_events WHERE event_name="page_view" GROUP BY page_path ORDER BY total DESC LIMIT 8')->fetchAll();
$events=$pdo->query('SELECT event_name, COUNT(*) total FROM analytics_events GROUP BY event_name ORDER BY total DESC')->fetchAll();
$pageTitle='Analytics';
require __DIR__.'/../includes/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4"><div><div class="small-muted">PAINEL ADMINISTRATIVO</div><h1 class="h2 section-title">Analytics</h1></div><a href="logout.php" class="btn btn-outline-light btn-sm">Sair <i class="bi bi-box-arrow-right"></i></a></div>
<div class="row g-3 mb-4">
<div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-label">Views totais</div><div class="metric-value"><?=number_format($total,0,',','.')?></div></div></div>
<div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-label">Views hoje</div><div class="metric-value"><?=number_format($today,0,',','.')?></div></div></div>
<div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-label">Visitantes únicos*</div><div class="metric-value"><?=number_format($visitors,0,',','.')?></div></div></div>
<div class="col-6 col-xl-3"><div class="metric-card"><div class="metric-label">Interações</div><div class="metric-value"><?=number_format($interactions,0,',','.')?></div></div></div>
</div>
<div class="row g-3"><div class="col-lg-7"><div class="panel p-3 p-lg-4"><h2 class="h5">Páginas mais acessadas</h2><div class="table-responsive"><table class="table"><thead><tr><th>Página</th><th>Visualizações</th></tr></thead><tbody><?php foreach($pages as $p):?><tr><td><?=e($p['page_path'])?></td><td><?=number_format($p['total'],0,',','.')?></td></tr><?php endforeach;?><?php if(!$pages):?><tr><td colspan="2" class="text-secondary">Sem dados ainda.</td></tr><?php endif;?></tbody></table></div></div></div>
<div class="col-lg-5"><div class="panel p-3 p-lg-4"><h2 class="h5">Eventos registrados</h2><?php foreach($events as $ev):?><div class="d-flex justify-content-between border-bottom border-secondary-subtle py-2"><span><?=e($ev['event_name'])?></span><strong><?=number_format($ev['total'],0,',','.')?></strong></div><?php endforeach;?><?php if(!$events):?><p class="text-secondary">Sem eventos ainda.</p><?php endif;?></div></div></div>
<p class="small-muted mt-3">* Visitantes únicos estimados por hash diário de IP + navegador; não representa identificação pessoal. Implemente aviso de privacidade e retenção adequada.</p>
<?php require __DIR__.'/../includes/footer.php'; ?>
