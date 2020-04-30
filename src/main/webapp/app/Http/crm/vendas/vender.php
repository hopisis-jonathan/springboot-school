<?php

use crm\Venda as Venda;
use crm\VendaDAO as VendaDAO;

$content = array();
$usuario = NULL;

try{

    $_POST = json_decode(file_get_contents('php://input'), true);
    
	$vendaForm = json_decode($_POST['venda']);
	$venda = new Venda(0, $vendaForm->data, NULL, 0, Venda::$_STATUS_ABERTO);
	$id = VendaDAO::inserir($venda);
	$venda->id = $id;
	
	$content['venda'] = $venda->getJsonData();	
	$content['status'] = 'OK';

}catch(\Exception $e){
    $content["errorID"] = $e->getCode();
    $content["errorMSG"] =  $e->getFile()."(".$e->getLine().")".$e->getMessage();
    $content["status"] = "CODEERR";
}
header('Content-Type: application/json;charset=utf-8');

echo json_encode($content);
?>