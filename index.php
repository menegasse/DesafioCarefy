<?php 

session_start();

#Rotas das páginas

require_once("vendor/autoload.php");
require_once("vendor/slim/slim/.htaccess");

use \Slim\Slim;
use \Carefy\Page;
use \Carefy\PageUser;
use \Carefy\Model\User;

$app = new Slim();

$app->config('debug', true);

//tela incial do sistema (login)
$app->get('/', function() {
    
	$page = new Page([
		"header"=>false,
		"footer"=>false
	]);

	$page->setTpl("index");

});



// rota de lgoin do usuário
$app->post('/login', function() {
    
	User::login($_POST["login"],$_POST["password"]);

	header("Location: /pacientes");
	exit;
});

//tela de erro no login
$app->get("/login/error/",function(){

	$page = new Page([
		"header"=>false,
		"footer"=>false
	],"/views/");

	$page->setTpl("loginerror");

});

//tela de pasciente
$app->get('/pacientes', function() {
	
	User::verifyLogin();

	$patients = User::listAll();

	$page = new Page([
		"header"=>true,
		"footer"=>true,
		"name"=>$_SESSION['name']
	],"/views/patients/");

	$page->setTpl("patients",array(
		"patients" => $patients
	));

});

//Cadastrar dados do paciente
$app->get('/pacientes/create', function() {
    
	$page = new Page([
		"header"=>true,
		"footer"=>true,
		"name"=>$_SESSION['name']
	],"/views/patients/");

	$page->setTpl("patients-create");

});

//tela de erro ao cadastrar novo paciente
$app->get('/pacientes/create/error/', function() {
    
	$page = new Page([
		"header"=>true,
		"footer"=>true,
		"name"=>$_SESSION['name']
	],"/views/patients/");

	$page->setTpl("patients-create-error");

});


//rota para salvar o paciente no banco
$app->post('/paciente/create',function(){

	User::verifyLogin();

	User::verifyPatientExist($_POST['name']);

	$user = new User();

	$_POST["enabled"] = (isset($_POST["enabled"]))?1:0;

	$user-> savePatients($_POST);

	header("Location:  /pacientes");
	exit;
});

// rota de logout do sistema
$app->get('/logout',function(){

	User::logout();

	header("Location:  /");
	exit;
});


//rota para deletar um paciente do sistema
$app->get("/pacientes/:iduser/delete",function($id){
	
	User::verifyLogin();

	$user = new User();

	$user->get((int)$id);

	$user->delete();

	header("Location:  /pacientes");
	exit;
});

//tela de update de pacientes
$app->get('/pacientes/edite/:id',function($id){    //passasse o id do usuario na rota como boas praticas para acessar aquele usuário em especifico 

	User::verifyLogin();

	$user = new User();

	$user->get((int)$id);

	$page = new Page([
		"header"=>true,
		"footer"=>true,
		"name"=>$_SESSION['name']
	],"/views/patients/");

	$page->setTpl("patients-update",array(
		"patients"=>$user->getValues()
	));
});


//rota para salvar a edisão do paciente no banco
$app->post("/pacientes/edite/:id",function($id){
	User::verifyLogin();

	$user = new User();

	$_POST["enabled"] = (isset($_POST["enabled"]))?1:0;

	$user->get((int)$id);

	$user->setData($_POST);

	$user->update();

	header("Location:  /pacientes");
	exit;
});

//tela de cadastro de usuário
$app->get("/user/create/",function(){

	$page = new Page([
		"header"=>true,
		"footer"=>true
	],"/views/user/");

	$page->setTpl("user-create");
});

//tela de erro no cadastro de usuário
$app->get("/user/create/error/",function(){

	$page = new Page([
		"header"=>true,
		"footer"=>true
	],"/views/user/");

	$page->setTpl("user-create-error");
});

//rota de cadastro de usuário no sistema
$app->post("/user/create/",function(){

	$user = new User();

	$user->verifyUserExist($_POST);

	$user->saveUser($_POST);
	
	header("Location:  /");
	exit;
});

//tela de esqueceu a senha 
$app->get("/user/forgot/",function()
{
	$page = new Page([
		"header"=>false,
		"footer"=>false
	],"/views/user/forgot/");

	$page->setTpl("forgot");

});

//rota para verificar e mandar o email do usuário
$app->post("/user/forgot/",function()
{
	$email = User::getForgot($_POST["email"]);

	header("Location:  /user/forgot/sent");
	exit;
});

//rota da tela de email enviado
$app->get("/user/forgot/sent",function()
{
	$page = new Page([
		"header"=>false,
		"footer"=>false
	],"/views/user/forgot/");

	$page->setTpl("forgot-sent");
});

//rota de tela da nova senha
$app->get("/user/forgot/reset",function(){
	
	 $user = new User();

	 $code = User::validForgotDecrypt($_GET['code']);

	 $user->getUser($code);

	$page = new Page([
		"header"=>false,
		"footer"=>false
	],"/views/user/forgot/");

	$page->setTpl("forgot-reset",array(
		"name"=>$user->getname(),
		"code"=>$user->getid()
	));
});

$app->post("/user/forgot/reset/",function(){
	
	$user = new User();

	$user->getUser($_POST['code']);

	$user->setpassword(password_hash($_POST['password'],PASSWORD_DEFAULT));

	$user->updateUser();

	header("Location: /user/forgot/reset-succes/");
	exit;
});


$app->get("/user/forgot/reset-succes/",function()
{
	$page = new Page([
		"header"=>false,
		"footer"=>false
	],"/views/user/forgot/");

	$page->setTpl("forgot-reset-success");
});

$app->run();

 ?>
