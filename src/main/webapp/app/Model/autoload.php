<?php
define("HOME", __DIR__."/");


date_default_timezone_set('America/Sao_Paulo');

spl_autoload_register(function ($class) {
	$nome = str_replace("\\", "/" , $class . '.php');

	if( file_exists( HOME . $nome ) ){
		include_once( HOME . $nome );	
	}
});
