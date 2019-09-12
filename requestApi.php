<?php

    class GetResponseFromUrl {

        private $url;

        private $method;

        private $queryParams;

        private $response;
        
        private $responseCode;

        private $headers = "";

        public function __construct(String $url, String $method, Array $queryParams = array(), String $headers = ""){
            $this->url = $url;
            $this->method = $method;
            if(is_array($queryParams))$this->queryParams = $queryParams;
        }

        public function setParam($name = false, $value = false){
            if($name && $value){
                $this->queryParams[$name] = $value;
            }
        }

        public function setParams(Array $params){
            foreach($params as $key=>$value){
                $this->queryParams[$key] = $value;
            }
        }

        public function setHeaders(String $headers){
            $this->headers = $headers;
        }
        
        public function getUrl(){
            return $this->url;
        }

        public function getMethod(){
            return $this->method;
        }

        public function getParams(){
            return $this->queryParams;
        }

        public function getHeaders(){
            return $this->headers;
        }

        public function getRsponse(){
            return $this->response;
        }

        public function getRsponseCode(){
            return $this->responseCode;
        }

        public function getRequest() {
            if($this->url != "" && $this->method != ""){
                switch(strtolower($this->method)){
                    case "post":
                        self::post();
                        break;
                    case "get":
                        self::get();
                        break;
                    default:
                        self::get();
                }
            }
            return ($this->responseCode == 200) ? $this->response : false;
        }

        private function post() {
            if(count($this->queryParams)>0)$data=http_build_query($this->queryParams);
            $handle=curl_init();
            curl_setopt($handle, CURLOPT_URL, $this->url);
            if($this->headers != "")curl_setopt($handle, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($handle, CURLOPT_POST, true);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            if(isset($data))curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
            $response=curl_exec($handle);
            $code=curl_getinfo($handle, CURLINFO_HTTP_CODE);
            $this->responseCode = $code;
            $this->response = $response;
        }

        private function get() {
            $data=http_build_query($this->queryParams);
            $handle=curl_init();
            curl_setopt($handle, CURLOPT_URL, $this->url."?".$data);
            if($this->headers != "")curl_setopt($handle, CURLOPT_HTTPHEADER, $this->headers);
            curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
            $response=curl_exec($handle);
            $code=curl_getinfo($handle, CURLINFO_HTTP_CODE);
            $this->responseCode = $code;
            $this->response = $response;
        }
    }