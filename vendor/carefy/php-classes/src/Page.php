<?php

namespace Carefy;

use \Rain\Tpl;

class Page
{
	private $tpl;
	private $options = [];
	private $defaults = [
			"header"=>true,
			"footer"=>true,
			"data"=>[]
	];

	public function __construct($opts = array(),$tpl_dir = "/views/")
	{
		$this->options = array_merge($this->defaults,$opts);
		
		$config = array(

			"tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir, #$_SERVER["DOCUMENT_ROOT"] tras o diretório raiz do servidor
			"cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
			"debug"         => false // set to false to improve the speed
		);

		Tpl::configure( $config );

		$this->tpl = new Tpl();

		$this->setData($this->options["data"]);

         //desenha o cabeçalho se a opção header for igual true
		 if($this->options["header"] === true)
		 {	
			if(empty($this->options['name'])==false) $this->tpl->assign('name',$this->options['name']);
			
			$this->tpl->draw("header");
		 } 

	}

    //metodo para atribuição de variaveis que vão aparecer no template
	private function setData($data=array())
	{
		foreach ($data as $key => $value) 
		{
			$this->tpl->assign($key,$value);
		}
	}

    //desenha o meio da tela
	public function setTpl($name,$data = array(),$returnHTML = false)
	{
		$this->setData($data);

		return $this->tpl->draw($name,$returnHTML);

	}

    //desenha o roda-pé se a opção footer for igual true
	public function __destruct()
	{
		if($this->options["footer"] === true) $this->tpl->draw("footer");



	}
}

?>
