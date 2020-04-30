<?php

namespace crm;

/**
 * @author Jonathan Vieira
 * @copyright  Jonnis Corp
 * @version 1.2.0
 * @since 1.0.0
 *
 * Classe que trata as ações para o Itens de Venda.
 */
class Item
{
	private $id;
	private $produto;
	private $venda;
	private $qtde;
	private $valorTotal;
	private $status;
	private $usuario;

	public static $_STATUS_NOVO = 1;
	public static $_STATUS_ABERTO = 10;
	public static $_STATUS_VENDIDO = 20;
	public static $_STATUS_SUSPENSO = 30;

	/**
	 * @author Jonathan Vieira
	 * @copyright  Jonnis Corp
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * Construtor de classe.
	 */
	public function __construct($id, $venda, $produto, $qtde, $valorTotal, $status)
	{
		$this->id = $id;
		$this->venda = $venda;
		$this->produto = $produto;
		$this->qtde = $qtde;
		$this->valorTotal = $valorTotal;
		$this->status = $status;
	}

	/**
	 * @author Jonathan Vieira
	 * @copyright  Jonnis Corp
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * Metodo para setar os valores conforme os atributos da classe.
	 * @param void $chave
	 * @param void $valor
	 */
	public function __set( $chave, $valor ){
		$this->{$chave} = $valor;
	}
	
	/**
	 * @author Jonathan Vieira
	 * @copyright  Jonnis Corp
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * Metodo para recuperar os valores conforme os atributos da classe.
	 * @param void $chave
	 */
	public function &__get( $chave ){
		return $this->{$chave};
	}
	
	/**
	 * @author Jonathan Vieira
	 * @copyright  Jonnis Corp
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * Metodo que prepara objeto para JSON.
	 */
	public function getJsonData(){
		$var = get_object_vars($this);
		foreach ($var as &$value) {
			if (is_object($value) && method_exists($value,'getJsonData')) {
				$value = $value->getJsonData();
			}
		}
		return $var;
	}	
	

}