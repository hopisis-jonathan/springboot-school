<?php

namespace crm;

use crm\Item as Item;
use crm\Produto as Produto;
use crm\ProdutoDAO as ProdutoDAO;


/**
 * @author Jonathan Vieira
 * @copyright  Jonnis Corp
 * @version 1.2.0
 * @since 1.0.0
 *
 * Classe que trata as ações para o sistema.
 */
class Venda
{
	private $id;
	private $data;
	private $total;
	private $itens;
	private $status;

	public static $_STATUS_NOVO = 1;
	public static $_STATUS_ABERTO = 10;
	public static $_STATUS_AGUARDANDO = 15;
	public static $_STATUS_VENDIDO = 20;
	public static $_STATUS_ADICIONAL = 21;
	public static $_STATUS_CANCELADA = 25;
	public static $_STATUS_SUSPENSO = 30;
	public static $_STATUS_INATIVO = 50;

	/**
	 * @author Jonathan Vieira
	 * @copyright  Jonnis Corp
	 * @version 1.0.0
	 * @since 1.0.0
	 *
	 * Construtor de classe.
	 */
	public function __construct($id, $data, $itens, $total, $status)
	{
		$this->id = $id;
		$this->data = $data;
		$this->itens = $itens;
		$this->total = $total;
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
	

	public function getJsonData(){
		$var = get_object_vars($this);
		foreach ($var as &$value) {
			if (is_object($value) && method_exists($value,'getJsonData')) {
				$value = $value->getJsonData();
			}
		}
		return $var;
	}	

	public function __toString()
    {
        try 
        {
            return (string) $this->id.", Valor Venda: ".$this->total;
        } 
        catch (Exception $exception) 
        {
            return '';
        }
    }
	

}