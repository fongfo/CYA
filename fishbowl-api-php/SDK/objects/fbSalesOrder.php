<?php
/**
 * @package : FishPhone
 * @author : dnewsom <dave.newsom@fishbowlinventory.com>
 * @version : 1.0
 * @date : 2010-05-18
 *
 * Sales Order object
 */

class FBSalesOrder_t {
	private $data = array();
	
	public function __construct($part) {
    	foreach($part AS $key=>$value) {
    		if (!is_array($value)) {
    			$this->$key = $value;
    		} else {
    			if ($key == "SalesOrderItem") {
					$items = array();
					if (isset($value['0'])) {
						$item = array();
						for ($i=0; $i < count($value); $i++) {
				    		$item = (array) $value[$i];
				    		$items[$item['ID']] = $item;
				    	}
				    	$this->__set('SalesOrderItems', $items);
					} else {
						$items[$value['ID']] = $value;
						$this->__set('SalesOrderItems', $items);
					}
    			} else {
    				if (count($value)) {
    					$this->$key = $value;
    				}
    			}
    		}
    	}
	}

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function __get($name) {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

    public function __isset($name) {
        return isset($this->data[$name]);
    }

    public function __unset($name) {
        unset($this->data[$name]);
    }
}

?>