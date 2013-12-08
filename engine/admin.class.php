<?php
	require_once("db.class.php");
	require_once("session.class.php");

	class AdminPage
	{

		private $session;

		public function __construct()
		{
			$this->session = new Session();
		}

		public function getName()
		{
			echo json_encode(array("name" => $_SESSION['user']));
		}

		public function upload($file)
		{
			$filename = '';
			if (!$this->session->isLoggedin())
			{
				return json_encode(array('uploaded' => false, 'reason' => 'You must login!'));
			}
			elseif (isset($file['foodmenu']) || isset($file['buschedule']))
			{
				if (isset($file['foodmenu']))
				{
					$filename = "foodmenu";
				}
				elseif (isset($file['buschedule']))
				{
					$filename = "buschedule";
				}
				else
				{
					return json_encode(array('uploaded' => false, 'reason' => 'Neither foodmenu nor buschedule specified'));
				}

				$path = "files/" . $filename;

				if (file_exists($path . ".jpg"))
				{
					unlink($path . ".jpg");
				}
				if (file_exists($path . ".png"))
				{
					unlink($path . ".png");
				}
			}
			else
			{
				return json_encode(array('uploaded' => false, 'reason' => 'Unknown filename'));
			}
			$allowedExts = array("png", "jpg");
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

		public function post($get, $input)
		{
			if ($get['postaction'] === "show")
			{
				return $this->showPosts();
			}
			elseif ($get['postaction'] === "edit")
			{
				return $this->editPost($input);
			}
			elseif ($get['postaction'] === "create")
			{
				return $this->createPost($input);
			}
			elseif ($get['postaction'] === "delete")
			{
				return $this->deletePost($input);
			}

			return json_encode(array('item' => "post", 'status' => "unknown post action"));
		}

		private function createPost($input)
		{
			$db = new db();
			$sql = "INSERT INTO studposts (title, postdate, post, uid) VALUES(?, CURRENT_TIMESTAMP, ?, {$_SESSION['uid']})";
			$stmt = $db->prepare($sql);
			$res = $stmt->execute(array($input['title'], $input['post']));
			return json_encode(array('item' => "post", 'status' => "OK", 'created' => $res));
		}

		private function editPost($input)
		{
			$db = new db();
			$sql = "UPDATE studposts SET title = ?, postdate = CURRENT_TIMESTAMP, post = ?, uid = {$_SESSION['uid']} WHERE id = ?";
			$stmt = $db->prepare($sql);
			$res = $stmt->execute(array($input['title'], $input['post'], $input['id']));
			return json_encode(array('item' => "post", 'status' => "OK", 'updated' => $res));
		}

		private function deletePost($input)
		{
			$db = new db();
			$sql = "DELETE FROM studposts WHERE id = ?";
			$stmt = $db->prepare($sql);
			$res = $stmt->execute(array($input['id']));
			return json_encode(array('item' => "post", 'status' => "OK", 'deleted' => $res));
		}

		private function showPosts()
		{
			$db = new db();
			$response = array();
			$sql = "SELECT studposts.id as id, title, postdate, post, username FROM studposts JOIN users ON users.id=studposts.uid ORDER BY postdate DESC;";
			foreach($db->query($sql) as $row)
			{
				$tempArray = array();
				$tempArray['id'] = $row['id'];
				$tempArray['title'] = $row['title'];
				$tempArray['postDate'] = $row['postdate'];
				$tempArray['post'] = $row['post'];
				$tempArray['author'] = $row['username'];
				array_push($response, $tempArray);
			}

			return json_encode(array('item' => "posts", 'status' => "OK", 'posts' => $response));

		}
	}

?>
