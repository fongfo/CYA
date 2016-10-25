<?php

    class TrackRequest {
        private $request;
        
        public function __construct() {
            $this->request = array();
        }
        
        public function setRequest( $context ) {
            $this->request["Request"] = array( 
                "RequestOption" => "1",
                "TransactionReference" => array( 
                    "CustomerContext" => $context
                )
            );
        }
        
        public function setInquiryNumber( $number ) {
            $this->request["InquiryNumber"] = $number;
        }
        
        public function getRequest() {
            return $this->request;
        }        
        
    }
    

?>
