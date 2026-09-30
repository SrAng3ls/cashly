<?php
class AdminController extends Controller {
    public function index(): void {Auth::requireAdmin();$this->render('admin/index',['users'=>User::all(),'csrf'=>$this->csrf()]);}
    public function save(): void {Auth::requireAdmin();$this->verifyCsrf();$id=(int)($_POST['id']??0);$name=trim($_POST['name']);$email=trim($_POST['email']);$role=$_POST['role']==='admin'?'admin':'user';if($id){User::update($id,$name,$email,$role);$this->flash('success','Cliente actualizado.');}else{User::create($name,$email,$_POST['password']??'Cliente123*',$role);$this->flash('success','Cliente creado.');}$this->redirect('index.php?url=admin');}
    public function delete(): void {Auth::requireAdmin();$this->verifyCsrf();$id=(int)$_POST['id'];if($id!==Auth::user()['id'])User::delete($id);$this->flash('success','Cliente eliminado.');$this->redirect('index.php?url=admin');}
}
