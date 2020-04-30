<?php

use crm\Venda as Venda;
use crm\VendaDAO as VendaDAO;
use crm\Item as Item;


$content = array();
$usuario = NULL;

try{

    $venda = VendaDAO::carregar($id);
    $venda->itens = VendaDAO::listarItens($venda->id, "", false);
    $venda->status = Venda::$_STATUS_VENDIDO;

    VendaDAO::atualizarCampo("status", $venda->status, $venda->id);
    VendaDAO::atualizarStatusItens($venda, $venda->status);    
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