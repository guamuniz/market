<?php
require_once __DIR__.'/includes/bootstrap.php';
$count = (int)db()->query('SELECT COUNT(*) FROM admins')->fetchColumn();
$message = '';
if ($count > 0) { exit('Administrador já configurado. Por segurança, remova setup.php do servidor. <a href="admin/login.php">Ir ao login</a>'); }
if ($_SERVER['REQUEST_METHOD']==='POST') {
    csrf_check();
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = (string)($_POST['password'] ?? '');
    if (!$email || strlen($password)<12) $message='Informe um e-mail válido e senha com pelo menos 12 caracteres.';
    else {
        $stmt=db()->prepare('INSERT INTO admins (name,email,password_hash,created_at) VALUES (?,?,?,NOW())');
        $stmt->execute([trim($_POST['name'] ?? 'Administrador'),$email,password_hash($password,PASSWORD_DEFAULT)]);
        header('Location: admin/login.php?created=1'); exit;
    }
}
$pageTitle='Configuração inicial';
require __DIR__.'/includes/header.php';
?>
<div class="row justify-content-center"><div class="col-md-7 col-lg-5"><div class="panel p-4">
<h1 class="h4 mb-2">Criar administrador</h1><p class="text-secondary small">Crie a conta inicial para acessar o painel privado.</p>
<?php if($message): ?><div class="alert alert-danger"><?=e($message)?></div><?php endif; ?>
<form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>">
<div class="mb-3"><label class="form-label">Nome</label><input class="form-control" name="name" required></div>
<div class="mb-3"><label class="form-label">E-mail</label><input class="form-control" type="email" name="email" required></div>
<div class="mb-3"><label class="form-label">Senha (mínimo 12 caracteres)</label><input class="form-control" type="password" name="password" minlength="12" required></div>
<button class="btn btn-primary w-100">Criar administrador</button></form></div></div></div>
<?php require __DIR__.'/includes/footer.php'; ?>
