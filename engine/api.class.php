<?php
	class api
	{
		private $request;

		public function __construct($req)
		{
			$this->request = $req;
			/*header("Access-Control-Allow-Orgin: *");
        		header("Access-Control-Allow-Methods: *");*/
        		header("Content-Type: application/json");
		}

		public function response()
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
