<?php
    require_once("db.class.php");
    class login
    {
        public $db;
        
        public function __construct()
        {
            $this->db = new db();
        }
        
        public function checkUserPass($user, $pass)
        {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
            $stmt->execute(array($user, sha1($pass)));
            $rows = $stmt->rowCount();
            if ($rows === 1)
            {
                return true;
            }
            return false;
        }
    } 
?>
