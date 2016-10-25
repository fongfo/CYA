<?php
    
    //require_once dirname(__FILE__) . '/';
    class UPS {

        private $config;

        function __construct() {
            $this->config = parse_ini_file( dirname(__file__) . "/upsConfig.ini");
        }

        public function call($parameters)
        {
            ob_start();
            $curl_request = curl_init();
            curl_setopt($curl_request, CURLOPT_URL, $this->config['LOCATION']);
            curl_setopt($curl_request, CURLOPT_POST, 1);
            curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($curl_request, CURLOPT_HEADER, 1);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt($curl_request, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            //curl_setopt($curl_request, CURLOPT_USERPWD, $this->config['UPS_USER'].":".$this->config['UPS_PASSWORD']);

            $jsonEncodedData = json_encode($parameters);

            $post = array(
                 //"method" => $method,
                 "input_type" => "JSON",
                 "response_type" => "JSON",
                 "rest_data" => $jsonEncodedData
            );

            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $jsonEncodedData);
            $result = curl_exec($curl_request);
            curl_close($curl_request);

            $result = explode("\r\n\r\n", $result, 2);
            $response = json_decode($result[1]);
            ob_end_flush();

            return $response;
        }

        function getUser() {
            return $this->config['LOCATION'];
        }

        function login() {
            $login_parameters = array(
                 "user_auth" => array(
                      "user_name" => $this->config['UPS_USER'],
                      "password" => $this->config['UPS_PASSWORD'],
                      "version" => "1"
                 ),
                 "application_name" => "RestTest",
                 "name_value_list" => array(),
            );

            return $this->call("login", $login_parameters);
        }

        function setParameters( $sessionId, $moduleName ) {
            $get_entry_list_parameters = array(
                'session' => $sessionId,
                'module_name' => 'Leads',
                'query' => "",
                'order_by' => "",
                'offset' => '0',
                'link_name_to_fields_array' => array(
                ),
                'max_results' => '2',
                'deleted' => '0',
                'Favorites' => false,
            );
            return $get_entry_list_parameters;

        }

        function getLeads() {
            $loginRes = $this->login();
            $parameters = $this->setParameters($loginRes->id, 'Leads');
            $leads = $this->call("get_entry_list", $parameters);
            return $leads;

        }

        function getContacts() {
            $loginRes = $this->login();
            $parameters = $this->setParameters($loginRes->id, 'Contacts');
            $contacts = $this->call("get_entry_list", $parameters);
            return $contacts;

        }

    }

?>