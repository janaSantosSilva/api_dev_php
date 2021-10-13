<?php
    
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/config/environment/db.php');

    class Database {

        protected function __construct(){}
    
        private function __clone(){}
        
        protected function __destruct() {
    
            foreach ($this as $key => $value) {
                unset($this->$key);
            }
    
        }

        private static $dbtype = DATABASE_TYPE;
        private static $host = DATABASE_HOST;
        private static $port = DATABASE_PORT;    
        private static $user = DATABASE_USER;    
        private static $password = DATABASE_PASSWORD;
        private static $db = DATABASE_NAME;
    
        private function getDBType() {
            return self::$dbtype;
        }
    
        private function getHost() {
            return self::$host;
        }
    
        private function getPort() {
            return self::$port;
        }
    
        private function getUser() {
            return self::$user;
        }
    
        private function getPassword() {
            return self::$password;
        }
    
        private function getDB() {
            return self::$db;
        }
    
        protected function connect(){
    
            $options = [
                PDO::ATTR_PERSISTENT    => true,
                PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
            ];
    
            try {
    
                $this->conexao = new PDO($this->getDBType().":host=".$this->getHost().";port=".$this->getPort().";dbname=".$this->getDB(), $this->getUser(), $this->getPassword(), $options);
                
            } catch (PDOException $ex) {
                die($ex->getMessage());
            }
             
            return $this->conexao;
    
        }
         
        protected function disconnect() {
            $this->conexao = null;
        }

    }