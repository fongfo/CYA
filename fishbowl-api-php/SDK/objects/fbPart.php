<?php
/**
 * @package : FishPhone
 * @author : dnewsom <dave.newsom@fishbowlinventory.com>
 * @version : 1.0
 * @date : 2010-05-15
 *
 * Part object
 */

class FBPart {
	private $data = array();
	
	public function __construct($part) {
    	foreach($part AS $key=>$value) {
    		if (!is_array($value)) {
    			$this->$key = $value;
    		} else {
    			if ($key == "UOM") {
    				$this->$key = $value['Name'];
    			} elseif ($key == "WeightUOM" || $key == "SizeUOM") {
    				$this->$key = $value['UOM']['Name'];
    			} elseif ($key == "VendorPartNums") {
    				if (count($value)) {
    					$vpns = array();
    					foreach ($value AS $junk=>$object) {
    						for ($i=0; $i < count($object); $i++) {
    							$vpn = (array) $object[$i];
	    						$vpns[$vpn['Number']] = $vpn['Number'];
    						}
    					}
    					$this->$key = $vpns;
    				}
    			} elseif ($key == "PartTrackingList") {
						$tracking = array();
					if (isset($value['PartTracking']['0'])) {
						$item = array();
						for ($i=0; $i < count($value['PartTracking']); $i++) {
				    		$item = (array) $value['PartTracking'][$i];
				    		$tracking[$item['Name']] = $item;
				    	}
				    	$this->__set('Tracking', $tracking);
					} else {
						$tracking[$value['PartTracking']['Name']] = $value['PartTracking'];
						$this->__set('Tracking', $tracking);
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