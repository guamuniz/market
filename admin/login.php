<?php
require_once __DIR__.'/../includes/bootstrap.php';
if (!empty($_SESSION['admin_id'])) { header('Location: dashboard.php'); exit; }
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 csrf_check();
 $email=trim($_POST['email']??'');$password=(string)($_POST['password']??'');
 $stmt=db()->prepare('SELECT id,name,password_hash FROM admins WHERE email=? LIMIT 1');$stmt->execute([$email]);$admin=$stmt->fetch();
 if($admin&&password_verify($password,$admin['password_hash'])){
  session_regenerate_id(true);$_SESSION['admin_id']=$admin['id'];$_SESSION['admin_name']=$admin['name'];
  db()->prepare('UPDATE admins SET last_login=NOW() WHERE id=?')->execute([$admin['id']]);
  header('Location: dashboard.php');exit;
 } else {$error='E-mail ou senha inválidos.';}
}
$pageTitle='Login administrativo';
require __DIR__.'/../includes/header.php';
?>
<div class="row justify-content-center py-5"><div class="col-md-6 col-lg-4"><div class="panel p-4">
<div class="text-center mb-4"><img src="https://thebestdigital.xyz/10repositorios/LOGO-XYZ-WHITE.png" height="40" alt="XYZ"><h1 class="h4 mt-3">Administração</h1><p class="small-muted">Acesso restrito</p></div>
<?php if(isset($_GET['created'])):?><div class="alert alert-success">Administrador criado. Faça login.</div><?php endif;?>
<?php if($error):?><div class="alert alert-danger"><?=e($error)?></div><?php endif;?>
<form method="post"><input type="hidden" name="csrf" value="<?=e(csrf_token())?>">
<div class="mb-3"><label class="form-label">E-mail</label><input class="form-control" type="email" name="email" required></div>
<div class="mb-3"><label class="form-label">Senha</label><input class="form-control" type="password" name="password" required></div>
<button class="btn btn-primary w-100">Entrar <i class="bi bi-arrow-right"></i></button></form>
</div></div></div>
<?php require __DIR__.'/../includes/footer.php'; ?>
