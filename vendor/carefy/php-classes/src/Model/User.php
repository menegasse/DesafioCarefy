<?php

namespace Carefy\Model;

use \Carefy\DB\Sql;
use \Carefy\Model;
use \Carefy\Email;

class User extends Model
{

	const SESSION = "User";
	const  SECRET  =  "CarefyPhp7_Secret" ;
	const  SECRET_IV  =  "CarefyPhp7_Secret_IV" ;

	//realiza o login do usuário no sistema 
	public function login($login,$password)
	{
		$sql = new Sql();

        
		$results = $sql->select("SELECT * FROM users WHERE name = :LOGIN",array(
			":LOGIN"=>$login
		));

		//verifica se a registro cadastrado no banco 
		if(count($results)!==0 and (password_verify($password, $results[0]["password"]) === true))
		{

			print_r(var_dump($results[0]));
			$user = new User();

			$user->setData($results[0]);
			$_SESSION['name'] = $user->getname();
			$_SESSION[User::SESSION] = $user->getValues(); # cria a sessão do usuário

			return $user;
			
		}
		else
		{
			header("Location: /login/error/");
			exit;
		}

	}

	//verifica se o usuário está logado no sistema (sessão valida)
	public static function verifyLogin($inadmin = true)
	{
		if(!isset($_SESSION[User::SESSION]) #verifica se a sessão do usuário existe
		|| 
		!$_SESSION[User::SESSION] #verifica se a sessão do usuário está definida (não perdeu valor)
		||
		!(int)$_SESSION[User::SESSION]["id"]>0 # verifica se a sessão do usuário é dele
		)
		{
			header("Location: /");
			exit;
		}
	}

	public static function logout()
	{
	  $_SESSION[User::SESSION] = NULL;
	}

	//verifica se já existe uma usuário com o nome de login no sistema
	public function verifyUserExist($value=array())
	{
		$sql = new Sql();
		$nome = $value['name'];
		if(empty($nome)==false)
		{
			$result = $sql->select("SELECT * FROM users WHERE name = :name",array(
				":name"=>$value['name']
			));

			$resultemail = $sql->select("SELECT * FROM users WHERE email = :email",array(
				":email"=>$value['email']
			));
		}
		else
		{
			header("Location:  /user/create/error/");
			exit;
		}
		if(count($result)!==0 or ($value["password"]!==$value["confpassword"]) or (count($resultemail)!==0))
		{
			header("Location:  /user/create/error/");
			exit;
		}

	}

	public static function verifyPatientExist($name)
	{
		$sql = new Sql();
		if(empty($name)==false)
		{
			$result = $sql->select("SELECT * FROM patients WHERE name = :name",array(
				":name"=>$name
			));
		}
		else
		{
			header("Location:  /pacientes/create/error/");
			exit;
		}
		if(count($result)!==0)
		{
			header("Location:  /pacientes/create/error/");
			exit;
		}

    }
    
    //lista todos os paciente do banco
	public static function listAll()
	{
		$sql = new Sql();

		return $sql->select("SELECT * FROM patients ORDER BY name;");


	}

	//cadastra um paciente no banco
	public function savePatients($values)
	{
		$sql = new Sql();

		$sql->query("INSERT INTO patients(name,hospital,user_id,enabled) VALUES(:name,:hospital,:id,:enabled);",array(
			":name"=>$values["name"],
			":id"=>(int)$_SESSION[User::SESSION]["id"],
			":hospital"=>$values["hospital"],
			":enabled"=>$values["enabled"]
		));
	}

	//cadastra um usuário no banco
	public function saveUser($values)
	{
		$sql = new Sql();

		$sql->query("INSERT INTO users(name,email,password) VALUES(:name,:email,:password);",array(
			":name"=>$values["name"],
			":email"=>$values["email"],
			":password"=>password_hash($values["password"],PASSWORD_DEFAULT)
		));
	}

	//retorna um paciente do banco, pelo seu ID
	public function get($id)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM patients p WHERE p.id = :id;",array(
				"id"=>$id
		));

		$this->setData($results[0]);
	}

	public function getUser($id)
	{
		$sql = new Sql();

		$results = $sql->select("SELECT * FROM users WHERE id = :id;",array(
				"id"=>$id
		));

		$this->setData($results[0]);
	}

	//atualiza um registro na tabela paciente no banco
	public function update()
	{
		$sql = new Sql();

		$sql->query("UPDATE patients SET name = :name, hospital = :hospital, enabled = :enabled WHERE id = :id;",array(
			":id"=>$this->getid(),
			":name"=>$this->getname(),
			":hospital"=>$this->gethospital(),
			":enabled"=>$this->getenabled()
		));
	}

	public function updateUser()
	{
		$sql = new Sql();

		$sql->query("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id;",array(
			":id"=>$this->getid(),
			":name"=>$this->getname(),
			":email"=>$this->getemail(),
			":password"=>$this->getpassword()
		));
	}

	//deleta um registro na tabela paciente no banco
	public function delete()
	{
		$sql = new Sql();

		$sql->query("DELETE FROM patients WHERE id = :id",array(
			":id"=>$this->getid()
		));
	}

	//verifica se o email do usuário existe
	public static function getForgot($email)
	{
		$sql = new Sql();

		$result = $sql->select("SELECT * FROM users WHERE email = :email;",array(
			":email"=>$email
		));
		
		if(count($result)===0)
		{
			header("Location:  /user/forgot/");
			exit;
		}
		else
		{
			$data = $result[0];
			//o ideal seria criar uma tabela para guardar os registros de quem pedio para torcar a senha

			$code = openssl_encrypt($data['id'], 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));
			$code = base64_encode($code);

			$link = "http://www.desafiocarefy.com.br/user/forgot/reset?code=$code";

			$mailer = new Email($data["email"],$data["name"],"Redefinir senha do Desafio Carefy","forgot",
			array(
				"name"=>$data["name"],
				"link"=>$link
			));

			$mailer->send();

			return $data;
		}
	}

	public static function validForgotDecrypt($code)
	{
		$code = base64_decode($code);
		$id = openssl_decrypt($code, 'AES-128-CBC', pack("a16", User::SECRET), 0, pack("a16", User::SECRET_IV));
		return $id;
	}

}

?>