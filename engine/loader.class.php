<?php
    require_once("login.class.php");
    class loader
    {
    
        public $posts;
        public $gets;
    
        public function __construct($getRequests, $postRequests)
        {
            $this->gets = $getRequests;
            $this->posts = $postRequests;
        
            if($this->gets['action'] === "login")
            {
                $login = new login();
                $result = $login->checkUserPass($postRequests['username'], $postRequests['pass']);
                if($result)
                {
                    echo json_encode(array("loggedin" => true));
                }
                else
                {
                    echo json_encode(array("loggedin" => false));
                }
            }
            elseif($this->gets['action'] === "logout")
            {
                login::logout();
            }
        }
    }

?>
