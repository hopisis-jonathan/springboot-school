<?php
session_start();
set_time_limit(240);
ini_set('max_execution_time', 300);
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);

require 'vendor/Slim/Slim.php';
require 'vendor/autoload.php';
require 'Model/autoload.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim(array('templates.path' => './'));

$app->get('/produtos',function() use ($app){
	$app->render("./Http/crm/produtos/listar.php");
});

$app->post('/produto',function() use ($app){
	$app->render("./Http/crm/produtos/manter.php");
});

$app->delete('/produto/:id',function($id) use ($app){
	$app->render("./Http/crm/produtos/excluir.php", ["id" => $id]);
});

$app->get('/crm/vendas',function() use ($app){
	$app->render("./Http/crm/vendas/listar.php");
});

$app->delete('/crm/vendas/:id',function($id) use ($app){
	$app->render("./Http/crm/vendas/excluir.php", ["id" => $id]);
});

$app->post('/vender/abrir',function() use ($app){
	$app->render("./Http/crm/vendas/vender.php");
});

$app->delete('crm/venda/:id',function($id) use ($app){
	$app->render("./Http/crm/vendas/excluir.php", ["id" => $id]);
});

$app->put('/vender/processar/:id',function($id) use ($app){
	$app->render("./Http/crm/vendas/processar.php", ["id" => $id]);
});

$app->post('/vender/itens',function() use ($app){
	$app->render("./Http/crm/vendas/item.add.php");
});

$app->delete('/vender/itens/:item/:venda',function($item, $venda) use ($app){
	$app->render("./Http/crm/vendas/item.del.php", ["item" => $item, "venda" => $venda]);
});

$app->get('/vender/itens/:id',function($id) use ($app){
	$app->render("./Http/crm/vendas/item.list.php", ["venda" => $id]);
});

$app->run();  
?>