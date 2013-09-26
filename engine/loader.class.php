<?php
    require_once("login.class.php");
    require_once("admin.class.php");
    class Loader
    {
    
        public $post;
        public $get;
    
        public function __construct($getRequests, $postRequests, $files)
        {
            $this->get = $getRequests;
            $this->post = $postRequests;
        
            if($this->get['action'] === "login")
            {
                $login = new Login();
                $result = $login->checkUserPass($postRequests['username'], $postRequests['pass']);
                echo json_encode(array("loggedin" => $result));
            }
            elseif($this->get['action'] === "checklogin")
            {
                $session = new Session();
                echo json_encode(array("loggedin" => $session->isLoggedin()));
            }
            elseif($this->get['action'] === "fileupload")
            {
                $adminPage = new AdminPage();
                echo $adminPage->upload($files);
            }
            elseif($this->get['action'] === "logout")
            {
                login::logout();
            }
        }
    }

?>
