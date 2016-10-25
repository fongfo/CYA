<?php

    require_once("fbErrorCodes.class.php");
    require_once("fishbowlAPI.class.php");
    define('FISHBOWL_INI', "fishbowl.ini");
    /**
     * Product Class for Fishbowl Integration
     * @author Clement Yu
     */
    class Product {
        
        protected $ini_array;
        protected $fbApi;
        
        function __construct() {
            $this->ini_array = parse_ini_file(FISHBOWL_INI);
            define('APP_KEY', $this->ini_array['APP_KEY']);
            define('APP_NAME', $this->ini_array['APP_NAME']);
            define('APP_DESCRIPTION', $this->ini_array['APP_DESCRIPTION']);
            
            $this->fbApi = new FishbowlAPI($this->ini_array['HOST'], $this->ini_array['PORT']);

            $this->fbApi->Login($this->ini_array['USER'], $this->ini_array['PASSWORD']);
            if (!$this->fbApi->checkAccessRights("Customer", "View")) {
                throw new Exception('You do not have access to use that function.');
            }

            if ($this->fbApi->statusCode != 1000) {
                throw new Exception('Error connecting to Fishbowl!');
            }
        }
        
        public function GetProducts() {
            $products = array();
            $queryStr = "Select Num, Description, Height, Len, Width, Weight, Price"
                    ."  from Product"
                    ."  where kitflag != 1";//
            $this->tryExecuteQuery($queryStr);              
            
            $res = $this->fbApi->result;
            $rowArray = $res['FbiMsgsRs']['ExecuteQueryRs']['Rows']['Row'];
            $products = $this->extractRowArray($rowArray);
            
            return $products;
        }
        
        public function GetProductParts() {
            $parts = array();
            $queryStr = "Select Num, Description, Height, Len, Width, Weight, StdCost from Part"; //
            $this->tryExecuteQuery($queryStr);              
            
            $res = $this->fbApi->result;
            $rowArray = $res['FbiMsgsRs']['ExecuteQueryRs']['Rows']['Row'];
            $parts = $this->extractRowArray($rowArray);
            
            return $parts;
        }        
        
        public function GetProductById($id) {
            $products = array();
            $queryStr = "Select Id, Partid, Num, Description from Product WHERE Id=".$id;
            $this->tryExecuteQuery($queryStr);              
            
            $res = $this->fbApi->result;
            $rowArray = $res['FbiMsgsRs']['ExecuteQueryRs']['Rows']['Row'];
            $products = $this->extractRowArray($rowArray);
            
            return $products;            
        }
        
        public function GetProductBySku($sku) {
            $products = array();
            $queryStr = "Select Id, Partid, Num, Description from Product WHERE Num='".$sku."'";
            $this->tryExecuteQuery($queryStr);              
            
            $res = $this->fbApi->result;
            $rowArray = $res['FbiMsgsRs']['ExecuteQueryRs']['Rows']['Row'];
            $products = $this->extractRowArray($rowArray);
            
            return $products;              
        }
        
        private function extractRowArray($rows) {
            $result = array();
            $rowCount = 0;
            $header = array();
            foreach( $rows as $row ) {
                $row = str_replace('"', '', $row);
                if( $rowCount++ < 1 ) {
                    $header = explode(",", $row);
                } else {
                    $cols = explode(",", $row);
                    $resRow = array();
                    foreach( $cols as $cid => $col ) {
                        $resRow[ $header[$cid] ] = $col;
                    }
                    $result[] = $resRow;
                }
            }
            return $result;
        }
        
        private function tryExecuteQuery($queryStr) {
            $this->fbApi->executeQuery("Query", $queryStr);
            if ($this->fbApi->statusCode != 1000) {
		// Display error messages if it's not blank
                $errMessage = "Exception: There is an error with query [".$queryStr."]";
		if (!empty($this->fbApi->statusMsg)) {
                    $errMessage .= ", Message: ". $this->fbApi->statusMsg;
		}
                throw new Exception($errMessage);
            }  
        }
    }
    
?>
