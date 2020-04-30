<?php
namespace crm;

use bd\Cluster as Cluster;
use \PDO as PDO;
use crm\Produto as Produto;

/**
 * @author Jonathan Vieira
 * @copyright  Jonnis Corp
 * @version 1.0.0
 * @since 1.0.0
 *
 * DATA OBJECT EMPRESA
 * Classe que recupera, atualiza e adiciona produtos na base e transforma em objetos.
 */
 
 abstract class ProdutoDAO{
	
	/***
	 Função estatica que recupera a produto.
	 @acesso public 
	 @param Int $id 
	 @return object 
	 
	 */ 

	public static function carregar($id){
		$conn = new Cluster();
		$result = $conn->prepare("select crm_produto.* from crm_produto where id = ? ");
		$result->bindParam(1, $id, PDO::PARAM_STR); 
		$result->execute();
		$result->setFetchMode(PDO::FETCH_OBJ);
		$dados = $result->fetch();	
		$produto = new Produto( $dados->id, $dados->sku, $dados->nome, $dados->descricao, $dados->valor);
		
		return $produto;	
	}
	
	public static function listar($sql, $json){
		$conn = new Cluster();
		$sqlComplementar = "";
		$result = $conn->prepare("select crm_produto.* from crm_produto where 1=1 ".$sql." order by nome");
		$result->execute();
		$result->setFetchMode(PDO::FETCH_OBJ);
		$itens = $result->fetchAll();
		$produtos = array();
		$i = 0;	
		foreach($itens as $dados){
			$produto = new Produto( $dados->id, $dados->sku, $dados->nome, $dados->descricao, $dados->valor);

			if($json){
				$produtos[$i++] = $produto->getJsonData();
			}else{
				$produtos[$i++] = $produto;
			}
		}	
		
		return $produtos;	
	}

public static function inserir($produto){
		$conn = new Cluster();
		
		$result = $conn->prepare("INSERT INTO crm_produto (id, sku, nome, descricao, valor) 
		 VALUES ( ?, ?, ?, ?, ?)");
		$result->bindValue(1, $produto->id, PDO::PARAM_INT); 
		$result->bindValue(2, $produto->sku, PDO::PARAM_STR); 
		$result->bindValue(3, $produto->nome, PDO::PARAM_STR); 
		$result->bindValue(4, $produto->descricao, PDO::PARAM_STR); 
		$result->bindValue(5, $produto->valor, PDO::PARAM_STR); 
		$result->execute();
		
		//echo $result->debugDumpParams();
		
		return  $conn->lastInsertId();	
}

public static function atualizar($produto){
		$conn = new Cluster();
		
		$result = $conn->prepare("UPDATE  crm_produto SET sku=?, nome=?, descricao=?, valor=? WHERE id = ?");
		$result->bindValue(1, $produto->sku, PDO::PARAM_STR); 
		$result->bindValue(2, $produto->nome, PDO::PARAM_STR); 
		$result->bindValue(3, $produto->descricao, PDO::PARAM_STR); 
		$result->bindValue(4, $produto->valor, PDO::PARAM_STR); 
		$result->bindValue(5, $produto->id, PDO::PARAM_INT); 
		$result->execute();
		
		//echo $result->debugDumpParams();
		
		return  $produto->id;	
}

public static function excluir($id){
	$conn = new Cluster();
	
	$result = $conn->prepare("DELETE FROM crm_produto WHERE id = ?");
	$result->bindValue(1, $id, PDO::PARAM_STR); 
	$result->execute();
	
}

public static function atualizarCampo($campo, $valor, $usuario, $id){
	$conn = new Cluster();
	
	$result = $conn->prepare("UPDATE crm_produto SET ".$campo."=?, dt_alt=?, usuario_alt=? WHERE id = ?");
	$result->bindValue(1, $valor, PDO::PARAM_STR); 
	$result->bindValue(2, date("Y-m-d H:i:s"), PDO::PARAM_STR); 
	$result->bindValue(3, $usuario, PDO::PARAM_INT); 
	$result->bindValue(4, $id, PDO::PARAM_INT); 
	$result->execute();
	
}


}
?>