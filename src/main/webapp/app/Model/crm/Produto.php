<?php

namespace crm;

/**
 * @author Jonathan Vieira
 * @copyright  Jonnis Corp
 * @version 1.2.0
 * @since 1.0.0
 *
 * Classe que trata as ações para o sistema.
 */
class Produto
{
	private $id;
	private $sku;
	private $nome;
	private $descricao;
	private $valor;

	/**
	 * @author Jonathan Vieira
	 * @copyright  Jonnis Corp
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * Construtor de classe.
	 */
	public function __construct($id, $sku, $nome, $descricao, $valor)
	{
		$this->id = $id;
		$this->sku = $sku;
		$this->nome = $nome;
		$this->valor = $valor;
		$this->descricao = $descricao;
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