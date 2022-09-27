<?php

    /*
    * This file is part of WIMM project created as part of Belvo API test.
    *
    * @author (c) Diogo Carvalho <dgo.eng@gmail.com>
    * @version 1.0.0
    *
    * Class to manage the database and all requests related to users
    *
    *
    *
    *
    */
    
require_once("BelvoAPIClass.php");

class DatabaseClass  
{  
    private $host = ""; // your host name  
    private $username = ""; // your user name  
    private $password = ""; // your password  
    private $dbname = ""; // your database name  
    private $db;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() { 

        $this->host = $_ENV['DB_HOST'];
        $this->username = $_ENV['DB_USER'];
        $this->password = $_ENV['DB_PASSWD'];
        $this->dbname = $_ENV['DB_NAME'];
        
        $this->db = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->username, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8") );
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    }  

    // this method used to execute mysql query      
    /**
     * query_executed
     *
     * @param  mixed $sql
     * @return void
     */
    protected function query_executed($sql) {  
        $c = $this->conn->query($sql);  
        return $c;  
    } 
    
    /**
     * get_rows
     *
     * @param  mixed $fields
     * @param  mixed $id
     * @param  mixed $tablename
     * @return void
     */
    public function get_rows($fields, $id = NULL, $tablename = NULL) {  
        $cn = !empty($id) ? " WHERE $id " : " ";  
        $fields = !empty($fields) ? $fields : " * ";  
        $sql = "SELECT $fields FROM $tablename $cn";  
        $results = $this-> query_executed($sql);  
        $rows = $this-> get_fetch_data($results);  
        return $rows;  
    }
    
    /**
     * get_user_by_email
     *
     * @param  mixed $email
     * @return void
     */
    public function get_user_by_email(string $email) { 
      try {
          // prepare sql and bind parameters
          $stmt = $this->db->prepare("SELECT * FROM user_tb WHERE user_email = :email");
          $stmt->bindValue(':email', $email);
          $stmt->execute();

          $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          return $result;
                
      } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
    }
    
    /**
     * get_user_by_id
     *
     * @param  mixed $user_id
     * @return void
     */
    public function get_user_by_id(string $user_id) { 
      try {
          // prepare sql and bind parameters
          $stmt = $this->db->prepare("SELECT * FROM user_tb WHERE user_id = :user_id");
          $stmt->bindValue(':user_id', $user_id);
          $stmt->execute();

          $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          return $result;
                  
      } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
    }    
    
    /**
     * update_user_link_id
     *
     * @param  mixed $user_id
     * @param  mixed $user_link_id
     * @return void
     */
    public function update_user_link_id(string $user_id, string $user_link_id) {
      try {
          // prepare sql and bind parameters
          $stmt = $this->db->prepare("UPDATE user_tb SET user_link_id = :user_link_id WHERE user_id = :user_id");
          $stmt->bindValue(':user_link_id', $user_link_id);
          $stmt->bindValue(':user_id', $user_id);
          
          return $stmt->execute();
                  
      } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
      }
    }
    
    /**
     * update_accounts
     *
     * @param  mixed $array
     * @param  mixed $user_id
     * @return void
     */
    public function update_accounts($array, string $user_id) {      
      try {

        $this->db->beginTransaction();

        $stmt = $this->db->prepare("DELETE FROM accounts_tb WHERE user_id = :userId");
        $stmt->bindValue(':userId', $user_id);
        $stmt->execute();            

        foreach( $array as $row) {
          
          $stmt = $this->db->prepare("INSERT INTO accounts_tb (account_name, institution_name, currency, category, balance_type, last_accessed_at, user_id) " . 
          " VALUES (:account_name, :institution_name, :currency, :category, :balance_type, :last_accessed_at, :user_id)");

          $stmt->bindValue(':account_name', $row->name);
          $stmt->bindValue(':institution_name', $row->institution->name);
          $stmt->bindValue(':currency', $row->currency);
          $stmt->bindValue(':category', $row->category);
          $stmt->bindValue(':balance_type', $row->balance_type);
          $stmt->bindValue(':last_accessed_at', $row->last_accessed_at);
          $stmt->bindValue(':user_id', $user_id);
          $stmt->execute();

        }

        $this->db->commit();
        return true;
      
      } catch(PDOException $e) {
        $this->db->rollBack();
        echo "Error: " . $e->getMessage();
        return false;
      }
    }
    
    /**
     * update_balances
     *
     * @param  mixed $array
     * @param  mixed $user_id
     * @return void
     */
    public function update_balances($array, string $user_id) {
      
      try {
        $this->db->beginTransaction();

        $stmt = $this->db->prepare("DELETE FROM balances_tb WHERE user_id = :userId");
        $stmt->bindValue(':userId', $user_id);
        $stmt->execute();            

        foreach( $array as $row) {

          $stmt = $this->db->prepare("INSERT INTO balances_tb (account_name, account_type, institution_name, " . 
                                      "account_agency, account_number, value_date, current_balance, collected_at, user_id, currency) " . 
                                      " VALUES (:account_name, :account_type, :institution_name, :account_agency, :account_number, :value_date, :current_balance, ". 
                                      " :collected_at, :user_id, :currency)");

          $stmt->bindValue(':account_name', $row->account->name);
          $stmt->bindValue(':account_type', $row->account->type);
          $stmt->bindValue(':institution_name', $row->account->institution->name);
          $stmt->bindValue(':account_agency', $row->account->agency);
          $stmt->bindValue(':account_number', $row->account->number);
          $stmt->bindValue(':value_date', $row->value_date);

          $stmt->bindValue(':current_balance', $row->current_balance, PDO::PARAM_STR);
          $stmt->bindValue(':collected_at', $row->collected_at);
          $stmt->bindValue(':user_id', $user_id);
          $stmt->bindValue(':currency', $row->account->currency);
          $stmt->execute();
          
        }

        $this->db->commit();
        return true;
      
      } catch(PDOException $e) {
        $this->db->rollBack();
        echo "Error: " . $e->getMessage();
        return false;
      }
    }
    
    /**
     * update_alldata
     *
     * @param  mixed $belvoAPI
     * @param  mixed $link
     * @param  mixed $user_id
     * @return void
     */
    public function update_alldata($belvoAPI, string $link, string $user_id) {

      $array = $belvoAPI->getListAccount($link);
      $this->update_accounts($array, $user_id);
      
      $array = $belvoAPI->getListIncomes($link);
      $this->update_balances($array, $user_id);
      
      unset($belvoAPI);
    }

    
    /**
     * getAllBalance
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getAllBalance(string $user_id) {
      try {
        // prepare sql and bind parameters

        $sql = "select DATE_FORMAT(bal.value_date,'%d/%m/%Y') value_date, bal.current_balance from balances_tb bal " .
                "where bal.user_id = :user_id ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $result;       
                
      } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
    
    }
    
    /**
     * getCurrentBalance
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getCurrentBalance(string $user_id) {
      
      try {
        // prepare sql and bind parameters

        $sql = "select bal.currency, sum(bal.current_balance) as current_balance " . 
                "from balances_tb bal join ( " .
                "  select account_agency, account_number, user_id, max(value_date) value_date " .
                "  from balances_tb " .
                "  group by account_agency, account_number, user_id " .
                ") gp on (bal.account_agency = gp.account_agency " . 
                "    and bal.account_number = gp.account_number " . 
                "        and bal.value_date = gp.value_date " .
                "        and bal.user_id = gp.user_id) " .
                "where bal.user_id = :user_id " . 
                "group by bal.currency";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        return $result;       
                
      } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
    } 

    
    
    /**
     * getSummaryAccounts
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getSummaryAccounts(string $user_id) {
      
      try {
        // prepare sql and bind parameters
        $stmt = $this->db->prepare("SELECT Category, count(1) as total FROM accounts_tb WHERE user_id = :user_id GROUP BY Category");
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
                
      } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
    } 
    
    /**
     * getTotalAccounts
     *
     * @param  mixed $user_id
     * @return void
     */
    public function getTotalAccounts(string $user_id) {
      
      try {
        // prepare sql and bind parameters
        $stmt = $this->db->prepare("SELECT count(1) as total FROM accounts_tb WHERE user_id = :user_id");
        $stmt->bindValue(':user_id', $user_id);
        $stmt->execute();

        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        if (empty($result)) {
          return 0;
        } else {
          return $result[0]['total'];
        }
        
                
      } catch(PDOException $e) {
        echo "Error: " . $e->getMessage();
      }
    } 
    
    /**
     * insert_new_user
     *
     * @param  mixed $email
     * @param  mixed $name
     * @param  mixed $fl_google
     * @param  mixed $password
     * @return void
     */
    public function insert_new_user(string $email, string $name, string $fl_google, string $password = NULL ) {
        try {
            // prepare sql and bind parameters
            $stmt = $this->db->prepare("INSERT INTO user_tb (user_email, user_name, user_password, user_fl_google) " . 
                                       " VALUES (:email, :name, :password, :fl_google)");

            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':name', $name);
            $stmt->bindValue(':password', $password);
            $stmt->bindValue(':fl_google', $fl_google);
            $stmt->execute();
            
            return true;

          } catch(PDOException $e) {
            //echo "Error: " . $e->getMessage();
            return false;
          }
    }

    protected function get_fetch_data($r) {  
        $array = array();  
        while ($rows = mysqli_fetch_assoc($r)) {  
            $array[] = $rows;  
        }  
        return $array;  
    }  
}  
?>