<?php
class AuthController extends Controller {
    public function login(): void {
        if (Auth::check()) $this->redirect('index.php?url=dashboard');
        if ($_SERVER['REQUEST_METHOD']==='POST') { $this->verifyCsrf(); $u=User::findByEmail(trim($_POST['email']??'')); if($u && password_verify($_POST['password']??'',$u['password'])){Auth::login($u);$this->redirect('index.php?url=dashboard');} $this->flash('error','Correo o contraseña incorrectos.'); }
        $this->render('auth/login',['csrf'=>$this->csrf()]);
    }
    public function register(): void {
        if ($_SERVER['REQUEST_METHOD']==='POST') { $this->verifyCsrf(); $name=trim($_POST['name']??'');$email=trim($_POST['email']??'');$pass=$_POST['password']??''; if(!$name||!filter_var($email,FILTER_VALIDATE_EMAIL)||strlen($pass)<8){$this->flash('error','Completa los campos y usa una contraseña de mínimo 8 caracteres.');} elseif(User::findByEmail($email)){$this->flash('error','Ese correo ya está registrado.');} else {User::create($name,$email,$pass);$this->flash('success','Cuenta creada. Ahora puedes iniciar sesión.');$this->redirect('index.php?url=login');} }
        $this->render('auth/register',['csrf'=>$this->csrf()]);
    }
    public function forgot(): void {
        $devLink=null;
        if($_SERVER['REQUEST_METHOD']==='POST'){ $this->verifyCsrf(); $u=User::findByEmail(trim($_POST['email']??'')); if($u){$token=bin2hex(random_bytes(32));$st=Database::get()->prepare('INSERT INTO password_resets(user_id,token,expires_at) VALUES(?,?,DATE_ADD(NOW(),INTERVAL 30 MINUTE))');$st->execute([$u['id'],$token]);$devLink='index.php?url=reset&token='.$token;} $this->flash('success','Si el correo existe, se generó un enlace de recuperación.'); }
        $this->render('auth/forgot',['csrf'=>$this->csrf(),'devLink'=>$devLink]);
    }
    public function reset(): void {
        $token=$_GET['token']??$_POST['token']??''; $st=Database::get()->prepare('SELECT * FROM password_resets WHERE token=? AND used=0 AND expires_at>NOW() LIMIT 1');$st->execute([$token]);$r=$st->fetch(); if(!$r){$this->flash('error','El enlace no es válido o ya expiró.');$this->redirect('index.php?url=forgot');}
        if($_SERVER['REQUEST_METHOD']==='POST'){ $this->verifyCsrf();$p=$_POST['password']??'';if(strlen($p)<8){$this->flash('error','La contraseña debe tener al menos 8 caracteres.');}else{User::setPassword((int)$r['user_id'],$p);$x=Database::get()->prepare('UPDATE password_resets SET used=1 WHERE id=?');$x->execute([$r['id']]);$this->flash('success','Contraseña actualizada.');$this->redirect('index.php?url=login');}}
        $this->render('auth/reset',['csrf'=>$this->csrf(),'token'=>$token]);
    }
    public function logout(): void { Auth::logout(); $this->redirect('index.php?url=login'); }
}
