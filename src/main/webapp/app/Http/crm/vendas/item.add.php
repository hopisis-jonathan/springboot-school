<?php

use crm\Item as Item;
use crm\Venda as Venda;
use crm\VendaDAO as VendaDAO;

$content = array();
$usuario = NULL;

try{

    $_POST = json_decode(file_get_contents('php://input'), true);
    
	
	$itemForm = json_decode($_POST['produto']);
	$venda = ($_POST['venda']);
	$item = new Item(NULL, $venda, $itemForm->id, $itemForm->qtde, $itemForm->qtde*$itemForm->valor, Item::$_STATUS_ABERTO);
	VendaDAO::inserirItem($item);
	VendaDAO::atualizarTotalVenda($venda);
	
	$content['status'] = 'OK';

}catch(\Exception $e){
    $content["errorID"] = $e->getCode();
    $content["errorMSG"] =  $e->getFile()."(".$e->getLine().")".$e->getMessage();
    $content["status"] = "CODEERR";
}
header('Content-Type: application/json;charset=utf-8');

echo json_encode($content);
?>