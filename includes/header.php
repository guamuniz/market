<?php
$pageTitle = $pageTitle ?? 'XYZ Market';
?>
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($pageTitle) ?> | XYZ Market</title>
<link rel="icon" href="https://thebestdigital.xyz/10repositorios/LOGO-XYZ-WHITE.png">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark border-bottom border-secondary-subtle sticky-top">
<div class="container-xl">
<a class="navbar-brand d-flex align-items-center gap-2" href="/index.php">
<img src="https://thebestdigital.xyz/10repositorios/LOGO-XYZ-WHITE.png" alt="XYZ" height="34">
<span class="fw-bold">MARKET</span>
</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="mainNav">
<div class="navbar-nav ms-auto">
<a class="nav-link" href="/index.php">Mercado</a>
<a class="nav-link" href="/radar.php">Radar de quedas</a>
<a class="nav-link" href="/sobre.php">Sobre</a>
</div>
</div>
</div>
</nav>
<main class="container-xl py-4">
