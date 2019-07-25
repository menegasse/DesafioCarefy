<?php

namespace Carefy;
use \Carefy\Model\User;

class Patient extends User
{
    //verifica se o paciente já esta cadastrado no sistema
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
}

?>