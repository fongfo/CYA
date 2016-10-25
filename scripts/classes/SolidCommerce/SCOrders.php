<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



define('SOLID_INI', "SOLID.ini");

class order{
    
    protected $ini_array;
    
    public function getOrder($data){
        
        $this->ini_array = parse_ini_file(SOLID_INI);

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

        $wsdl = $this->ini_array['WSDL'];

        $client = new SoapClient($wsdl,['soap_version' => $this->ini_array['SOAP_VERSION']]);

        
        try {
        $result = $client ->__soapCall('SearchOrdersV5',array($data));
        return $result;
        
        }catch (SOAPFault $e) {
            print $e;
        }
    }

        public function updateOrderStatus($data){
                $this->ini_array = parse_ini_file(SOLID_INI);

        $wsdl = $this->ini_array['WSDL'];

        $client = new SoapClient($wsdl,['soap_version' => $this->ini_array['SOAP_VERSION']]);

        
        try {
        $result = $client ->__soapCall('UpdateOrderStatus',array($data));
        return $result;
        
        }catch (SOAPFault $e) {
            print $e;
        }
    }
    
    
    public function objToXml($data){
        


        

        $res = $data->GetOrderResult->any;
        $return = simplexml_load_string($res);
        return $return;
        //print_r($return);
        

        
        
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
    
    public function searchObjToXml($data){
           
        $res = $data->SearchOrdersV5Result->any;
        $return = simplexml_load_string($res);
        return $return;
        //print_r($return);

              
    }
    
}