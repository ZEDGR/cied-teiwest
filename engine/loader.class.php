<?php
    require_once("login.class.php");
    class Loader
    {
    
        public $posts;
        public $gets;
    
        public function __construct($getRequests, $postRequests)
        {
            $this->gets = $getRequests;
            $this->posts = $postRequests;
        
            if($this->gets['action'] === "login")
            {
                $login = new Login();
                $result = $login->checkUserPass($postRequests['username'], $postRequests['pass']);
                echo json_encode(array("loggedin" => $result));
            }
            elseif($this->gets['action'] === "checklogin")
            {
                $session = new Session();
                echo json_encode(array("loggedin" => $session->isLoggedin()));
            }
            elseif($this->gets['action'] === "logout")
            {
                login::logout();
            }
        }
    }

?>
