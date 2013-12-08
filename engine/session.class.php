<?php
	class Session
	{

		public function __construct()
		{
			if(session_id() === "")
			{
				session_start();
			}
		}

		public function setAdmin($id, $user, $pass)
		{
			$_SESSION['uid'] = $id;
			$_SESSION['user'] = $user;
			$_SESSION['pass'] = $pass;
		}

		public function isLoggedin()
		{
			if(isset($_SESSION['user']))
			{
				return true;
			}

			return false;
		}

		public function destroy()
		{
			$_SESSION = array();
			session_destroy();
		}
	}

?>
