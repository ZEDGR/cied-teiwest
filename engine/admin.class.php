<?php
	require_once("session.class.php");
	class Adminpage
	{

		function getPage()
		{
			echo json_encode(array("data" => "<a href='#'>Logout</a>"));
		}

		function getName()
		{
			echo json_encode(array("name" => $_SESSION['user']));
		}
	}

?>