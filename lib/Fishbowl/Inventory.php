<?php

    require_once("fbErrorCodes.class.php");
    require_once("fishbowlAPI.class.php");
    define('FISHBOWL_INI', "fishbowl.ini");
    /**
     * Inventory Class for Fishbowl Integration
     * @author Clement Yu
     */
    class Inventory {
        
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

        public function GetInventory() {
            $inventory = array();
            $queryStr = "SELECT P.NUM, "
                ."  COALESCE( SUM(i.QTYONHAND - i.QTYALLOCATEDSO + i.QTYDROPSHIP), 0 ) AS AVAILABLE "
                ."  FROM PRODUCT P "
                ."    JOIN PART PA ON PA.ID=P.PARTID "
                ."    LEFT JOIN QTYINVENTORY I on I.PARTID = P.PARTID "
                ."  WHERE PA.ACTIVEFLAG=1 "
                ."  GROUP BY P.NUM";
            /*$queryStr = "SELECT p.NUM, SUM(i.QTYONHAND - i.QTYALLOCATEDSO) AS AVAILABLE, p.PRICE"
                ." FROM Product p "
                ." LEFT JOIN Part pa on p.PartId = pa.Id"
                ." LEFT JOIN QTYINVENTORY i on i.PartId = pa.Id"
                ." GROUP BY p.NUM";
             */
            $this->tryExecuteQuery($queryStr);              
            
            $res = $this->fbApi->result;
            $rowArray = $res['FbiMsgsRs']['ExecuteQueryRs']['Rows']['Row'];
            $inventory = $this->extractRowArray($rowArray);
            
            return $inventory;
        }   
        
        public function GetInventoryById($id) {
            $inventory = array();
            $queryStr = "SELECT i.PartID, p.Num, i.QtyOnHand "
		. " FROM qtyinventory i"
		. " JOIN Part p on i.PartID = p.Id"
                . " WHERE i.PartID=".$id;
            $this->tryExecuteQuery($queryStr);              
            
            $res = $this->fbApi->result;
            $rowArray = $res['FbiMsgsRs']['ExecuteQueryRs']['Rows']['Row'];
            $inventory = $this->extractRowArray($rowArray);
            
            return $inventory;
        }   

        public function GetInventoryBySku($sku) {
            $products = array();
            $queryStr = "SELECT DISTINCT(p.Num), p.Description, i.QtyOnHand "
		. " FROM qtyinventory i"
		. " JOIN Part p on i.PartID = p.Id"
                . " WHERE p.Num='".$sku."'";
            $this->tryExecuteQuery($queryStr);              
            
            $res = $this->fbApi->result;
            $rowArray = $res['FbiMsgsRs']['ExecuteQueryRs']['Rows']['Row'];
            $products = $this->extractRowArray($rowArray);
            
            return $products;              
        }   
        
        public function GetBatchInventoryBySku($skuStr) {
            $products = array();
            $queryStr = "SELECT DISTINCT(p.Num), p.Description, i.QtyOnHand "
		. " FROM qtyinventory i"
		. " JOIN Part p on i.PartID = p.Id"
                . " WHERE p.Num IN (".$skuStr.")";
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
