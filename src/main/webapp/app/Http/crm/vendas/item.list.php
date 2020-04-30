<?php

use crm\Venda as Venda;
use crm\VendaDAO as VendaDAO;

$content = array();
$usuario = NULL;

try{


            $sql = "";
            $resultado = VendaDAO::listarItens($venda, $sql, true);

			$content['itens'] = $resultado;
            $content['status'] = 'OK';
            
}catch(\Exception $e){
    $content["errorID"] = $e->getCode();
    $content["errorMSG"] =  $e->getFile()."(".$e->getLine().")".$e->getMessage();
    $content["status"] = "CODEERR";
}
header('Content-Type: application/json;charset=utf-8');

echo json_encode($content);
?>