<?php
	require_once("session.class.php");
	class AdminPage
	{

		private $session;

		function __construct()
		{
			$this->session = new Session();
		}

		function getName()
		{
			echo json_encode(array("name" => $_SESSION['user']));
		}
		
		function upload($file)
		{
			$filename = '';
			if (!$this->session->isLoggedin())
			{
				return json_encode(array('uploaded' => false, 'reason' => 'You must login!'));
			}
			elseif(isset($file['foodmenu']) || isset($file['buschedule']))
			{
				$filename = isset($file['foodmenu']) ? 'foodmenu' : 'buschedule';
			}
			else
			{
				return json_encode(array('uploaded' => false, 'reason' => 'Unknown filename'));
			}
			$allowedExts = array("png", "jpg", "jpeg");
			$ext = explode(".", $file[$filename]['name']);
			$ext = end($ext);
			if (in_array($ext, $allowedExts) && file_exists("files/"))
			{
				$result = move_uploaded_file($file[$filename]['tmp_name'], "files/" . $filename . "." . $ext);
				$reason = $result == true ? 'none' : "You don't have permissions to write the folder 'files'.";
				return json_encode(array('uploaded' => $result, 'reason' => $reason));
			}
		    return json_encode(array('uploaded' => false, 'reason' => 'Not allowed file extension or folder "files" in the server does not exist!'));
		}
	}

?>
