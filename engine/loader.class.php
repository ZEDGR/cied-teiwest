<?php
	require_once("login.class.php");
	require_once("admin.class.php");
	require_once("api.class.php");

	class Loader
	{

		public function __construct($get, $post, $files)
		{
			if ($get['action'] === "api")
			{
				$api = new API($get);
				echo $api->response();
			}
			elseif ($get['action'] === "post")
			{
				new Session();
				$adminPage = new AdminPage();
				echo $adminPage->post($get, $post);
			}
			elseif ($get['action'] === "login")
			{
				$login = new Login();
				$result = $login->checkUserPass($post['username'], $post['pass']);
				echo json_encode(array("loggedin" => $result));
			}
			elseif ($get['action'] === "checklogin")
			{
				$session = new Session();
				echo json_encode(array("loggedin" => $session->isLoggedin()));
			}
			elseif ($get['action'] === "fileupload")
			{
				$adminPage = new AdminPage();
				echo $adminPage->upload($files);
			}
			elseif ($get['action'] === "logout")
			{
				login::logout();
			}
		}
	}

?>
