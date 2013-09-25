<?php
	require_once("session.class.php");
	class AdminPage
	{

		function getName()
		{
			echo json_encode(array("name" => $_SESSION['user']));
		}
		
		function fileUpload()
		{
		    
		}
	}

?>
