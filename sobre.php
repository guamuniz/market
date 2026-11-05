<?php
require_once __DIR__.'/includes/bootstrap.php';
track_event('page_view','sobre');
$pageTitle='Sobre o XYZ Market';
require __DIR__.'/includes/header.php';
?>
<div class="panel p-4 p-lg-5">
 <img src="https://thebestdigital.xyz/10repositorios/LOGO-XYZ-WHITE.png" alt="XYZ" height="42" class="mb-4">
 <h1 class="h2 section-title">XYZ Market</h1>
 <p class="text-secondary">Uma plataforma gratuita de acompanhamento de indicadores e movimentos do mercado financeiro.</p>
 <h2 class="h5 mt-4">Como interpretar o radar</h2>
 <p class="text-secondary">Uma queda expressiva pode decorrer de fundamentos, eventos corporativos, liquidez, volatilidade ou movimentos gerais do mercado. O XYZ Market organiza dados e destaca variações para apoiar a investigação do usuário.</p>
 <div class="alert alert-dark border-secondary-subtle mb-0">O conteúdo é exclusivamente informativo e educacional. Não é recomendação, oferta ou solicitação de compra ou venda de ativos. Consulte fontes oficiais e um profissional habilitado antes de tomar decisões.</div>
</div>
<?php require __DIR__.'/includes/footer.php'; ?>
