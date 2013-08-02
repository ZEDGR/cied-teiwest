<?php
    class db extends PDO 
    {
        private $host = "localhost";
        private $dbname = "tesyd_app";
        private $user = "tesyd_app";
        private $pass = "gbapp";
        
        public function __construct()
        {
            try
            {
                parent::__construct("mysql:host=$this->host;dbname=$this->dbname;charset=utf8;", $this->user, $this->pass);
            }
            catch(PDOexception $e)
            {
                error_log($e->getMessage());
            }
        }
    }
?>
