<?php
    require_once("db.class.php");
    require_once("session.class.php");
    class Login
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
                $session = new Session();
                $session->setAdmin($user, $pass);
                return true;
            }
            return false;
        }

        public function logout()
        {
            $session = new Session();
            $session->destroy();
            echo json_encode(array("logout" => true));
        }
    } 
?>
