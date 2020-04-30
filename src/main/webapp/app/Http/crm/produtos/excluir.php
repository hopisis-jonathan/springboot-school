<?php

use crm\Produto as Produto;
use crm\ProdutoDAO as ProdutoDAO;

$content = array();

try{

	ProdutoDAO::excluir($id);
	$content['status'] = 'OK';
	
}catch(\Exception $e){
    $content["errorID"] = $e->getCode();
    $content["errorMSG"] =  $e->getFile()."(".$e->getLine().")".$e->getMessage();
    $content["status"] = "CODEERR";
}
header('Content-Type: application/json;charset=utf-8');

echo json_encode($content);
?>