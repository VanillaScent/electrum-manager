<?php

namespace ElectrumManager\RPC;

use Exception;

class RPCCLient {

    /**
     * Logger object
    *
    * @var Logger
    */
    private $logger;

    /**
     * The server URL
    *
    * @var string
    */
    private $url = "http://127.0.0.1:7778";

    /**
     * The request id
    *
    * @var integer
    */
    private $id;

    /**
     * If true, notifications are performed instead of requests
    *
    * @var boolean
    */
    private $notification = false;

    /**
     * Username for http auth
     * @var string
     */
    private $username = "user";

    /**
     * Password for http auth
     * @var string
     */
    private $password = "user";

    /**
     * Takes the connection parameters
     *
     * @param string $url
     * @param boolean $debug
     */



        /**
         * Execute JSONRPC Request
         *
         * @param       $method
         * @param array $params
         *
         * @return mixed
         * @throws BadRequestException
         * @throws ElectrumResponseException
         */
        public function execute($method, $params = [])
        {
            // Create request payload
            $response = $this->__call($method, $params);
            // Retrieve electrum api response
            // Check if an error occured
            if(isset($response['error'])) {
                            // ### Set message
                            $msg = implode('|', $response['error']);
                            throw new Exception("An error occured while executing the RPC command, $msg");
            }
            return $response['result'];
        }

    /**
     * Performs a jsonRPC request and gets the results as an array
     *
     * @param string $method
     * @param array $params
     * @return array
     */
    public function __call($method,$params) {
        // prepares the request
        $request = array(
                'id'    => 0,
                'json_rpc'      => "2.0",
                'method' => $method,
                'params' => $params,
        );
        $request = json_encode($request);

        // execute
        $opts = array ('http' => array (
                            'method'  => 'POST',
                            'header'  => 'Content-type: application/json\r\n',
                            'content' => $request
        ));

        $opts['http']['header'] .= 'Authorization:Basic '.base64_encode( $this->username . ':' . $this->password).'\r\n';

        $context  = stream_context_create($opts);
        if ($fp = fopen("http://' . $this->username . ':' . $this->password . '@127.0.0.1:7777", 'rb', false, $context)) {
            #       print_r($_GLOBALS);exit;
            $response = '';
            $response = stream_get_contents($fp);

            $response = json_decode($response, true);
        } else {
            throw new Exception('Unable to connect to '.$this->url);
        }

        return $response;
    }


    /**
     * Set authparams
     * @param string $username
     * @param string $password
     */
     public function setAuthParams($username, $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    function find ($string, $array = array ())
    {
        foreach ($array as $key => $value) {
                unset ($array[$key]);
                if (strpos($value, $string) !== false) {
                        $array[$key] = $value;
                }
        }
        return $array;
    }


}