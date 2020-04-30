<?php

use crm\Venda as Venda;
use crm\VendaDAO as VendaDAO;
use crm\Item as Item;


$content = array();
$usuario = NULL;

try{
    
    VendaDAO::excluir($id);

    $content['status'] = 'OK';

}catch(\Exception $e){
    $content["errorID"] = $e->getCode();
    $content["errorMSG"] =  $e->getFile()."(".$e->getLine().")".$e->getMessage();
    $content["status"] = "CODEERR";
}
header('Content-Type: application/json;charset=utf-8');

echo json_encode($content);
?>