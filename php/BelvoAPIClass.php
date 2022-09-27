
<?php

  /** 
    * Belvo API
    * 
    * This file is part of WIMM project created as part of Belvo API test.
    *
    * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
    * @version 1.0.0
    *
    * Class to manage all belvo API endpoints.
    *
    */

class BelvoAPI{
        
        private string $session_id = '';
        private string $session_paswd = '';
        private string $api_url = '';

        /**
         * __construct
         *
         * @param  string $session_id
         * @param  string $session_paswd
         * @param  string $api_url
         * @return mixed json The same Json structure returned from the belvo endpoint
         */
        public function __construct(string $session_id, string $session_paswd, string $api_url) {
            $this->session_id = $session_id;
            $this->session_paswd = $session_paswd;
            $this->api_url = $api_url;
        }

        //
                
        /**
         * deleteLink 
         * Call the belvo API endpoint /api/link using DELETE to remove a void link
         * A Link is a set of credentials associated to an end-user's access to an institution.
         * 
         * @param string $link_id A Link is a set of credentials associated to an end-user's access to an institution.
         * @return mixed json The same Json structure returned from the belvo endpoint
         */
        public function deleteLink(string $link_id) {
            $request = new HTTP_Request2();
            
            try {
                $request->setUrl($this->api_url . "/api/links/$link_id");
                $request->setMethod(HTTP_Request2::METHOD_DELETE);    
                $request->setAuth($this->session_id, $this->session_paswd);
        
                $response = $request->send();
                if ($response->getStatus() == 200) {
                    $json = json_decode($response->getBody());
                    return json_encode($json);
                }
                else {
                    $result = ['status' => $response->getStatus() , 'Message' => $response->getReasonPhrase()];
                    return json_encode($result);     
                }
            }
            catch(HTTP_Request2_Exception $e) {
                $result = ['status' => '500', 'Message' => $e->getMessage()];
                return  json_encode($result); 
            }
        }        
        
        /**
         * getAccount
         * Call the belvo API endpoint /api/accounts using GET. 
         * An account is the representation of a bank account inside a financial or gig institution.
         * 
         * @param string $link_id A Link is a set of credentials associated to an end-user's access to an institution.
         * @return mixed json The same Json structure returned from the belvo endpoint
         */
        public function getAccount(string $link_id) {
            $request = new HTTP_Request2();
            $jsonResult =  new ArrayObject();
            $result =  new ArrayObject();
            
            try {
                $i = 0;
                $next = NULL;

                do {
                    $i += 1;
                    $request->setUrl($this->api_url . "/api/accounts/?page=$i&page_size=1000&link=$link_id");
                    $request->setMethod(HTTP_Request2::METHOD_GET);    
                    $request->setAuth($this->session_id, $this->session_paswd);
        
                    $response = $request->send();
                    if ($response->getStatus() == 200) {
                        $json = json_decode($response->getBody());
                        $jsonResult->append($json->results);
                    }
                    else {
                        $result = ['status' => $response->getStatus() , 'Message' => $response->getReasonPhrase()];
                        return json_encode($result);                        
                    }
                } while (!is_null($json->next));
                
                for ($i = 0; $i < count($jsonResult); $i++) {
                    $result->append($jsonResult[$i]);
                }

                return json_encode($result[0]);
            }
            catch(HTTP_Request2_Exception $e) {
                $result = ['status' => '500', 'Message' => $e->getMessage()];
                return  json_encode($result); 
            }
        }
                
        /**
         * getListAccount
         * Call the method getAccount and return a list of objects. 
         * An account is the representation of a bank account inside a financial or gig institution.
         * 
         * @param string $link_id A Link is a set of credentials associated to an end-user's access to an institution.
         * @return mixed List of Accounts Objects
         */
        public function getListAccount(string $link_id) {
            
            try {
                $result = json_decode($this->getAccount($link_id));
                
                return $result;
            }
            catch(HTTP_Request2_Exception $e) {
                $result = ['status' => '500', 'Message' => $e->getMessage()];
                return  json_encode($result); 

            }
        }
        
        /**
         * getListIncomes
         * Call the method getIncomes and return a list of objects. 
         * A balance represents the amount of funds available in a checking or savings account over a period of time.
         *
         * @param string $link_id A Link is a set of credentials associated to an end-user's access to an institution.
         * @return mixed List of Balances Objects
         */
        public function getListIncomes(string $link_id) {
            
            try {
                $result = json_decode($this->getIncomes($link_id));
                
                return $result;
            }
            catch(HTTP_Request2_Exception $e) {
                $result = ['status' => '500', 'Message' => $e->getMessage()];
                return  json_encode($result); 

            }
        }        
        
        /**
         * getTransactions
         * Call the belvo API endpoint /api/transactions using GET.
         * A transaction contains the detailed information of each movement inside an account. For example, a purchase at a store or a restaurant.
         * 
         * @param string $link_id A Link is a set of credentials associated to an end-user's access to an institution.
         * @return mixed json The same Json structure returned from the belvo endpoint
         */
        public function getTransactions(string $link_id) {
            $request = new HTTP_Request2();
            $jsonResult =  new ArrayObject();
            $result =  new ArrayObject();

            try {
                $i = 0;
                $next = NULL;

                do {
                    $i += 1;
                    $request->setUrl($this->api_url . "/api/transactions/?page=$i&page_size=1000&link=$link_id");
                    $request->setMethod(HTTP_Request2::METHOD_GET);    
                    $request->setAuth($this->session_id, $this->session_paswd);
        
                    $response = $request->send();
                    if ($response->getStatus() == 200) {
                        $json = json_decode($response->getBody());
                        $jsonResult->append($json->results);

                    }
                    else {
                        $result = ['status' => $response->getStatus() , 'Message' => $response->getReasonPhrase()];
                        return json_encode($result);   
                    }
                } while (!is_null($json->next));
                
                for ($i = 0; $i < count($jsonResult); $i++) {
                    $result->append($jsonResult[$i]);
                }

                return json_encode($result[0]);
            }
            catch(HTTP_Request2_Exception $e) {
                $result = ['status' => '500', 'Message' => $e->getMessage()];
                return  json_encode($result); 

            }
        }     
                
        /**
         * getOwners
         * Call the belvo API endpoint /api/owners using GET.
         * An owner represents the person who has access to a Link and is the owner of all the accounts inside the Link.
         * 
         * @param string $link_id A Link is a set of credentials associated to an end-user's access to an institution.
         * @return mixed json The same Json structure returned from the belvo endpoint
         */
        public function getOwners(string $link_id) {
            $request = new HTTP_Request2();
            $jsonResult =  new ArrayObject();
            $result =  new ArrayObject();

            try {
                $i = 0;
                $next = NULL;

                do {
                    $i += 1;
                    $request->setUrl($this->api_url . "/api/owners/?page=$i&page_size=1000&link=$link_id");
                    $request->setMethod(HTTP_Request2::METHOD_GET);    
                    $request->setAuth($this->session_id, $this->session_paswd);
        
                    $response = $request->send();
                    if ($response->getStatus() == 200) {
                        $json = json_decode($response->getBody());                        
                        
                        /* Masking DOC NUMBER */
                        $document_number = $json->results[0]->document_id->document_number;
                        $document_number = substr($document_number, 0, 3) . str_repeat('*', strlen($document_number) - 7) . substr($document_number, -4);
                        
                        $json->results[0]->document_id->document_number = $document_number;
                        
                        $results = $json->results;
                        $jsonResult->append($json->results);

                    }
                    else {
                        $result = ['status' => $response->getStatus() , 'Message' => $response->getReasonPhrase()];
                        return json_encode($result);   
                    }
                } while (!is_null($json->next));

                for ($i = 0; $i < count($jsonResult); $i++) {
                    $result->append($jsonResult[$i]);
                }

                return json_encode($result[0]);
            }
            catch(HTTP_Request2_Exception $e) {
                $result = ['status' => '500', 'Message' => $e->getMessage()];
                return  json_encode($result); 

            }
        }            
                
        /**
         * getIncomes
         * Call the belvo API endpoint /api/balances using GET. 
         * A balance represents the amount of funds available in a checking or savings account over a period of time.
         * 
         * @param string $link_id A Link is a set of credentials associated to an end-user's access to an institution.
         * @return mixed json The same Json structure returned from the belvo endpoint
         */
        public function getIncomes(string $link_id) {
            $request = new HTTP_Request2();
            $jsonResult =  new ArrayObject();
            $result =  new ArrayObject();

            try {
                $i = 0;
                $next = NULL;

                do {
                    $i += 1;
                    $request->setUrl($this->api_url . "/api/balances/?page=$i&page_size=1000&link=$link_id");
                    $request->setMethod(HTTP_Request2::METHOD_GET);    
                    $request->setAuth($this->session_id, $this->session_paswd);
        
                    $response = $request->send();

                    if ($response->getStatus() == 200) {
                        $json = json_decode($response->getBody());
                        $jsonResult->append($json->results);                        
                    }
                    else {
                        $result = ['status' => $response->getStatus() , 'Message' => $response->getReasonPhrase()];
                        return json_encode($result);   
                    }
                    
                } while (isset($json) && !is_null($json->next));

                if (isset($json) && isset($json->results)) {
                    for ($i = 0; $i < count($jsonResult); $i++) {
                        $result->append($jsonResult[$i]);
                    }    

                    return json_encode($result[0]);
                }

                return json_encode('');
            }
            catch(HTTP_Request2_Exception $e) {
                $result = ['status' => '500', 'Message' => $e->getMessage()];
                return  json_encode($result); 

            }
        }            

    }
?>