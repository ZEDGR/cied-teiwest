<?php
	class Session
	{
		function __construct()
		{
			if(session_id() === "")
			{
				session_start();
			}

		}

		function setAdmin($user, $pass)
		{
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;
		}

		function isLoggedin()
		{
			if(isset($_SESSION['user']))
			{
				return true;
			}

			return false;
		}

		function destroy()
		{
			$_SESSION = array();
			session_destroy();
		}
	}

?>
