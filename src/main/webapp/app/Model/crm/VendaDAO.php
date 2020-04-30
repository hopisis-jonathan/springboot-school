<?php
namespace crm;

use bd\Cluster as Cluster;
use \PDO as PDO;
use crm\Venda as Venda;
use crm\Item as Item;

/**
 * @author Jonathan Vieira
 * @copyright  AR2J
 * @version 1.0.0
 * @since 1.0.0
 *
 * DATA OBJECT EMPRESA
 * Classe que recupera, atualiza e adiciona vendas na base e transforma em objetos.
 */
 
 abstract class VendaDAO{
	
	/***
	 Função estatica que recupera a venda.
	 @acesso public 
	 @param Int $id 
	 @return object 
	 
	 */ 

	public static function carregar($id){
		$conn = new Cluster();
		$result = $conn->prepare("select crm_venda.* from crm_venda where id = ? ");
		$result->bindParam(1, $id, PDO::PARAM_STR); 
		$result->execute();
		$result->setFetchMode(PDO::FETCH_OBJ);
		$dados = $result->fetch();	
		$venda = new Venda( $dados->id, $dados->data, NULL, $dados->total, $dados->status);

		
		return $venda;	
	}
	
	public static function listar($sql, $json){
		$conn = new Cluster();
		$sqlComplementar = "";
		$result = $conn->prepare("select crm_venda.* from crm_venda where 1=1 ".$sql." order by data");
		$result->execute();
		$result->setFetchMode(PDO::FETCH_OBJ);
		$itens = $result->fetchAll();
		$vendas = array();
		$i = 0;	
		foreach($itens as $dados){
			$venda = new Venda( $dados->id, $dados->data, NULL, $dados->total, $dados->status);

			if($json){
				$vendas[$i++] = $venda->getJsonData();
			}else{
				$vendas[$i++] = $venda;
			}
		}	
		
		return $vendas;	
	}

public static function inserir($venda){
		$conn = new Cluster();
		
		$result = $conn->prepare("INSERT INTO crm_venda (data, status) 
		 VALUES ( ?, ? )");
		$result->bindValue(1, $venda->data, PDO::PARAM_STR); 
		$result->bindValue(2, Venda::$_STATUS_ABERTO, PDO::PARAM_STR); 
		$result->execute();
		
		//echo $result->debugDumpParams();
		
		return  $conn->lastInsertId();	
}

public static function atualizarCampo($campo, $valor, $id){
	$conn = new Cluster();
	
	$result = $conn->prepare("UPDATE  crm_venda SET ".$campo."=? WHERE id = ?");
	$result->bindValue(1, $valor, PDO::PARAM_STR); 
	$result->bindValue(2, $id, PDO::PARAM_INT); 
	$result->execute();

}

public static function excluir($venda){
	$conn = new Cluster();

	$result = $conn->prepare("DELETE FROM crm_venda_item WHERE id_venda = ?");
	$result->bindValue(1, $venda, PDO::PARAM_INT); 
	$result->execute();

	$result = $conn->prepare("DELETE FROM crm_venda WHERE id = ?");
	$result->bindValue(1, $venda, PDO::PARAM_INT); 
	$result->execute();
}


/* PROCEDIMENTOS PARA INSERÇÃO DE ITEM DE VENDAS */

public static function inserirItem($item){
	$conn = new Cluster();
	
	$result = $conn->prepare("INSERT INTO crm_venda_item (id_produto, id_venda, qtde, total, status) 
	 VALUES ( ?, ?, ?, ?, ? )");
	$result->bindValue(1, $item->produto, PDO::PARAM_STR); 
	$result->bindValue(2, $item->venda, PDO::PARAM_STR); 
	$result->bindValue(3, $item->qtde, PDO::PARAM_STR); 
	$result->bindValue(4, $item->valorTotal, PDO::PARAM_STR); 
	$result->bindValue(5, $item->status, PDO::PARAM_STR);  
	$result->execute();
	
	//echo $result->debugDumpParams();
	
	return  $conn->lastInsertId();	
}

public static function listarItens($venda, $sql, $json){
	
	$conn = new Cluster();
	$sqlComplementar = "";
	$result = $conn->prepare("select crm_venda_item.* from crm_venda_item where id_venda = ? ".$sql." order by id_item");
	$result->bindValue(1, $venda, PDO::PARAM_STR); 
	$result->execute();
	$result->setFetchMode(PDO::FETCH_OBJ);
	$itens = $result->fetchAll();
	$vendas = array();
	$i = 0;	
	foreach($itens as $dados){
		$produto = ProdutoDAO::carregar($dados->id_produto);
		$item = new Item($dados->id_item, $dados->id_venda, $produto, $dados->qtde, $dados->total, $dados->status);

		if($json){
			$vendas[$i++] = $item->getJsonData();
		}else{
			$vendas[$i++] = $item;
		}
	}	
	
	return $vendas;	
}

public static function listarAdicionais($venda, $sql, $json){
	
	$conn = new Cluster();
	$sqlComplementar = "";
	$result = $conn->prepare("select crm_venda_item.* from crm_venda_item where id_venda = ? ".$sql." order by id_item");
	$result->bindValue(1, $venda, PDO::PARAM_STR); 
	$result->execute();
	$result->setFetchMode(PDO::FETCH_OBJ);
	$itens = $result->fetchAll();
	$vendas = array();
	$i = 0;	
	foreach($itens as $dados){
		$produto = ProdutoDAO::carregar($dados->id_produto);
		$subitens = explode(";", $produto->configuracao);
		foreach($subitens as $sub){
			$subItem = explode(":",$sub);
			if($subItem[0] == "IT"){
				$produto = ProdutoDAO::carregar($subItem[1]);
				$item = new Item($dados->id_item, $dados->id_venda, $produto, $dados->qtde, ($dados->qtde*$produto->valor), Venda::$_STATUS_ADICIONAL);
				if($json){
					$vendas[$i++] = $item->getJsonData();
				}else{
					$vendas[$i++] = $item;
				}
			}
		}
	}	
	
	return $vendas;	
}

public static function atualizarTotalVenda($venda){
	
	$conn = new Cluster();
	$sqlComplementar = "";
	$result = $conn->prepare("select sum(total) as total from crm_venda_item where id_venda = ? ");
	$result->bindValue(1, $venda, PDO::PARAM_STR); 
	$result->execute();
	$result->setFetchMode(PDO::FETCH_OBJ);
	$dados = $result->fetch();
	
	$valorTotal = $dados->total;
	$adicionais = VendaDAO::listarAdicionais($venda, "", false);
	foreach($adicionais as $item){
		$valorTotal += $item->valorTotal;	
	}
	
	$result = $conn->prepare("UPDATE crm_venda SET total=? WHERE id = ?");
	$result->bindValue(1, $valorTotal, PDO::PARAM_STR); 
	$result->bindValue(2, $venda, PDO::PARAM_INT); 
	$result->execute();

	return $dados->total;
}

public static function delItem($venda){
	$conn = new Cluster();

	$result = $conn->prepare("DELETE FROM crm_venda_item WHERE id_item = ?");
	$result->bindValue(1, $venda, PDO::PARAM_INT); 
	$result->execute();
}

public static function atualizarStatusItens($venda, $status){
	$conn = new Cluster();
	
	$result = $conn->prepare("UPDATE  crm_venda_item SET status=? WHERE id_venda = ?");
	$result->bindValue(1, $status, PDO::PARAM_STR); 
	$result->bindValue(2, $venda->id, PDO::PARAM_INT); 
	$result->execute();
	
}


}
?>