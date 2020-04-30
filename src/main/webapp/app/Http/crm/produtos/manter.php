<?php

use crm\Produto as Produto;
use crm\ProdutoDAO as ProdutoDAO;

$content = array();

try{

    $_POST = json_decode(file_get_contents('php://input'), true);
    
	$produtoForm = json_decode($_POST['produto']);
	$produto = new Produto($produtoForm->id, $produtoForm->sku, $produtoForm->nome, $produtoForm->descricao, $produtoForm->valor);

	if($produto->id > 0){
		ProdutoDAO::atualizar($produto);
	}else{
		$id = ProdutoDAO::inserir($produto);
		$produto->id = $id;
	}
	$content['produto'] = $produto->getJsonData();	
	$content['status'] = 'OK';

	
}catch(\Exception $e){
    $content["errorID"] = $e->getCode();
    $content["errorMSG"] =  $e->getFile()."(".$e->getLine().")".$e->getMessage();
    $content["status"] = "CODEERR";
}
header('Content-Type: application/json;charset=utf-8');

echo json_encode($content);
?>