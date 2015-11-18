<?php
namespace core\view;

use core\logic\Bridge;
use core\logic\JSON_handler;

class ViewGateway {

	private $tovars;
	private $bridge;
	private $unpacker;
	private $pointer;

	function __construct() {
		$this->tovars = false;
		$this->bridge = new Bridge();
		$this->unpacker = new JSON_handler();
		$this->pointer = 0;
	}

	function loadTovarsOfCategory($category) {
		$this->tovars = $this->bridge->getTovars($category);
	}	

	function simpleHTML($class = false) {
		if (!$this->tovars) return false;
		$class = ($class === false) ? '' : ' class = "'.$class.'"';

		$diffCharacteristics = array();
		$diffPrices = array();
		$diffAttributes = array();

		$unpacked = $this->unpacker->unpack($this->tovars);

		foreach ($unpacked as $value) {

			foreach ($value->characteristics as $char) {
				if (array_search(
					$char->title, 
					$diffCharacteristics
				) === false) {
					array_push(
						$diffCharacteristics, 
						$char->title
					);
				}
			}

			foreach ($value->prices as $price) {
				if (array_search(
					$price->type, 
					$diffPrices
				) === false) {
					array_push(
						$diffPrices, 
						$price->type
					);
				}
			}

			foreach ($value->attributes as $attr) {
				if (array_search(
					$attr->title, 
					$diffAttributes
				) === false) {
					array_push(
						$diffAttributes, 
						$attr->title
					);
				}
			}
		}
		
		foreach ($unpacked as $tovar) {
			echo '<div'.$class.'>';		
			echo '#'.$tovar->idx.' '.$tovar->title;
			
			foreach ($diffCharacteristics as $value) {
				foreach ($tovar->characteristics as $char) {
					if ($value == $char->title) {
						echo '<div'.$class.'>';
						echo 'Char: '.$char->value.'</div>';
						break;
					}
				}
			}
			
			foreach ($diffAttributes as $value) {
				foreach ($tovar->attributes as $attr) {
					if ($value == $attr->title) {
						echo '<div'.$class.'>';
						echo 'Attr: '.$attr->value.'</div>';
						break;
					}
				}
			}
		
			foreach ($diffPrices as $value) {
				foreach ($tovar->prices as $price) {
					if ($value == $price->type) {
						echo '<div'.$class.'>';
						echo 'Price: '.$price->value.'</div>';
						break;
					}
				}
			}
		}

		return true;
	}

	function toHTML() {
		if (!$this->tovars) return false;

		$diffCharacteristics = array();
		$diffPrices = array();
		$diffAttributes = array();

		$unpacked = $this->unpacker->unpack($this->tovars);
		# var_dump($unpacked);

		foreach ($unpacked as $value) {

			foreach ($value->characteristics as $char) {
				if (array_search(
					$char->title, 
					$diffCharacteristics
				) === false) {
					array_push(
						$diffCharacteristics, 
						$char->title
					);
				}
			}

			foreach ($value->prices as $price) {
				if (array_search(
					$price->type, 
					$diffPrices
				) === false) {
					array_push(
						$diffPrices, 
						$price->type
					);
				}
			}

			foreach ($value->attributes as $attr) {
				if (array_search(
					$attr->title, 
					$diffAttributes
				) === false) {
					array_push(
						$diffAttributes, 
						$attr->title
					);
				}
			}

		}
		
		echo '<table><tbody><tr><td class="thd" onclick="sort(this)" title="Нажмите на заголовок, чтобы отсортировать колонку"># товару</td><td class="thd" onclick="sort(this)" title="Нажмите на заголовок, чтобы отсортировать колонку">Назва товару</td>';
		foreach ($diffCharacteristics as $value)
			echo '<td class="thd" onclick="sort(this)" title="Нажмите на заголовок, чтобы отсортировать колонку">'.$value.'</td>';
		foreach ($diffAttributes as $value)
			echo '<td class="thd" onclick="sort(this)" title="Нажмите на заголовок, чтобы отсортировать колонку">'.$value.'</td>';
		foreach ($diffPrices as $value)
			echo '<td class="thd" onclick="sort(this)" title="Нажмите на заголовок, чтобы отсортировать колонку">'.$value.'</td>';
		echo '</tr>';
		
		foreach ($unpacked as $tovar) {
			echo '<tr>';
			
			echo '<td>'.$tovar->idx.'</td>';
			echo '<td>'.$tovar->title.'</td>';
			
			foreach ($diffCharacteristics as $value) {
				$flag = false;
				foreach ($tovar->characteristics as $char) {
					if ($value == $char->title) {
						echo '<td>'.$char->value.'</td>';
						$flag = true;
						break;
					}
				}
				if (!$flag) {
					echo '<td></td>';
				}
			}
			
			foreach ($diffAttributes as $value) {
				$flag = false;
				foreach ($tovar->attributes as $attr) {
					if ($value == $attr->title) {
						echo '<td>'.$attr->value.'</td>';
						$flag = true;
						break;
					}
				}
				if (!$flag) {
					echo '<td></td>';
				}
			}
		
			foreach ($diffPrices as $value) {
				$flag = false;
				foreach ($tovar->prices as $price) {
					if ($value == $price->type) {
						echo '<td>'.$price->value.'</td>';
						$flag = true;
						break;
					}
				}
				if (!$flag) {
					echo '<td></td>';
				}
			}
			
			
			echo '</tr>';
		}
		echo '</tbody></table>';
		return true;
	}
}
?>
