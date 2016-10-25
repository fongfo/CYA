<?php

//require_once("SOLID.ini");
define('SOLID_INI', "SOLID.ini");

class order{
    
    protected $ini_array;
    
    public function getOrder($data){
        
        $this->ini_array = parse_ini_file(SOLID_INI);
        //header($this->ini_array['ORDER_HEADER']);
        $wsdl = $this->ini_array['WSDL'];

        $client = new SoapClient($wsdl,['soap_version' => $this->ini_array['SOAP_VERSION']]);

        
        try {
        $result = $client ->__soapCall('GetOrder',array($data));
        return $result;
        
        }catch (SOAPFault $e) {
            print $e;
        }
    }
    
    public function searchOrder($data){
        
        $this->ini_array = parse_ini_file(SOLID_INI);
        //header($this->ini_array['ORDER_HEADER']);
        $wsdl = $this->ini_array['WSDL'];

        $client = new SoapClient($wsdl,['soap_version' => $this->ini_array['SOAP_VERSION']]);

        
        try {
        $result = $client ->__soapCall('SearchOrdersV5',array($data));
        return $result;
        
        }catch (SOAPFault $e) {
            print $e;
        }
    }
    //protected $fbApi;

    
    /*public static function xmlToEncode($data){        

        $xml = $attr = "";
        foreach($data as $key => $value) {
                if(is_numeric($key)) {
                        $attr = " id='{$key}'";
                        $key = "item";
                }
                $xml .= "<{$key}{$attr}>";
                $xml .= is_array($value) ? self::xmlToEncode($value) : $value;
                $xml .= "</{$key}>\n";
        }   
        return $xml;
	
    }*/
    
    public function objToXml($data){
        


        

        $res = $data->GetOrderResult->any;
        $return = simplexml_load_string($res);
        return $return;
        //print_r($return);
        
        //$D=$return->getElementByTagName('DateTime')[0];
       // echo $D;
        
        
    }
    
    
    public function searchObjToXml($data){
        


        

        $res = $data->SearchOrdersV5Result->any;
        $return = simplexml_load_string($res);
        return $return;
        //print_r($return);
        
        //$D=$return->getElementByTagName('DateTime')[0];
       // echo $D;
        
        
    }
    public static function xmlEncode($data) {
		

		header("Content-Type:text/xml");
		$xml = "<?xml version='1.0' encoding='UTF-8'?>\n";
		$xml .= "<root>\n";

		$xml .= self::xmlToEncode($data);

		$xml .= "</root>";
		echo $xml;
	}

	public static function xmlToEncode($data) {

		$xml = "";
		foreach($data as $key => $value) {
			
			$xml .= "<{$key}>";
			$xml .= is_array($value) ? self::xmlToEncode($value) : $value;
			$xml .= "</{$key}>\n";
		}
		return $xml;
	}
        
        
        public function xmlToArr($xml, $root = true)
	{

		if(!$xml->children())
		{
			return (string)$xml;
		}
		$array = array();
		foreach($xml->children() as $element => $node)
		{
			$totalElement = count($xml->{$element});
			if(!isset($array[$element]))
			{
				$array[$element] = "";
			}
			// Has attributes
			if($attributes = $node->attributes())
			{
				$data = array('attributes' => array(), 'value' => (count($node) > 0) ? $this->xmlToArr($node, false) : (string)$node);
				foreach($attributes as $attr => $value)
				{
					$data['attributes'][$attr] = (string)$value;
				}
				if($totalElement > 1)
				{
					$array[$element][] = $data;
				}
				else
				{
					$array[$element] = $data;
				}
				// Just a value
			}
			else
			{
				if($totalElement > 1)
				{
					$array[$element][] = $this->xmlToArr($node, false);
				}
				else
				{
					$array[$element] = $this->xmlToArr($node, false);
				}
			}
		}
		if($root)
		{
			return array($xml->getName() => $array);
		}
		else
		{
			return $array;
		}

	}
        
        
        
        public function toValue($data){ 
            foreach($data as $key =>$value);{
                if (is_array($key)){
                    this::toValue($key);
                }
                echo $value;

            }
        }
}
?>