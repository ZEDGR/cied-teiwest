<?php
	require_once("db.class.php");

	class API
	{

		private $request;

		public function __construct($req)
		{
			$this->request = $req;
			header("Access-Control-Allow-Origin: *");
			//header("Access-Control-Allow-Methods: *");
			header("Content-Type: application/json; charset=utf-8");
		}

		public function response()
		{
			if ($this->request['item'] === "news")
			{
				return $this->news();
			}
			elseif ($this->request['item'] === "foodmenu" || $this->request['item'] === "buschedule")
			{
				return $this->image();
			}
			else
			{
				return json_encode(array("item" => "not implemented"));
			}
		}

		private function news()
		{
			if ($this->request['from'] === "cied")
			{
				$feedXML = file_get_contents("http://www.tesyd.teimes.gr/www/index.php?format=feed&type=rss");
				$num = isset($this->request['num']) ? $this->request['num'] : 5;
				$num = $num > 10 ? 10 : $num;
				$feed = new SimpleXMLElement($feedXML);
				$response = array('item' => "news", 
						'from' => $this->request['from'],
						'num' => $num, 
						'announces' => array());
				for ($i = 0; $i < $num; $i++)
				{
					$temp = array();
					$temp['title'] = (string)$feed->channel->item[$i]->title;
					$temp['description'] = strip_tags((string)$feed->channel->item[$i]->description);
					$temp['author'] = (string)$feed->channel->item[$i]->author;
					$temp['pubDate'] = substr((string)$feed->channel->item[$i]->pubDate, 0, 25);
					$response['announces'][$i+1] = $temp;
				}
				return json_encode($response);
			}
			elseif ($this->request['from'] === "studcied")
			{
				$db = new db();
				$sql = "SELECT title, postdate, post, username as author FROM studposts JOIN users ORDER BY postdate DESC";
				if (isset($this->request['num']))
				{
					$num = (int)$this->request['num'];
					$sql .= " LIMIT :limit";
					$stmt = $db->prepare($sql);
					$stmt->bindParam(':limit', $num, PDO::PARAM_INT);
					$stmt->execute();
					return json_encode(array('item' => "news", 
								'from' => "studcied",
								'num' => $stmt->rowCount(), 
								'announces' => $stmt->fetchAll(PDO::FETCH_ASSOC)));
				}
				$stmt = $db->prepare($sql);
				$stmt->execute();
				return json_encode(array('item' => "news",
							'from' => "studcied",
							'num' => $stmt->rowCount(), 
							'announces' => $stmt->fetchAll(PDO::FETCH_ASSOC)));
			}

			return json_encode(array('item' => "news", 'data' => ""));

		}

		private function image()
		{
			$response = array();
			$extra = "files/";
			$host = $_SERVER['HTTP_HOST'];
			$uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
			if ($this->request['item'] === "foodmenu")
			{
				$extra .= $this->request['item'];
				$response['item'] = $this->request['item'];

				if (file_exists($extra . ".jpg"))
				{
					$response['image'] = $host . $uri . "/" . $extra . ".jpg";
				}
				elseif (file_exists($path . ".png"))
				{
					$response['image'] = $host . $uri . "/" . $extra . ".png";
				}
			}
			elseif ($this->request['item'] === "buschedule")
			{
				$extra .= $this->request['item'];
				$response['item'] = $this->request['item'];

				if (file_exists($extra . ".jpg"))
				{
					$response['image'] = $host . $uri . "/" . $extra . ".jpg";
				}
				elseif (file_exists($extra . ".png"))
				{
					$response['image'] =  $host . $uri . "/" . $extra . ".png";
				}
			}
			return json_encode($response);
		}

	}
?>
