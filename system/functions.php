<?php

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
     $url = "https://";   
else  
     $url = "http://";   
// Append the host(domain name, ip) to the URL.   
$url.= $_SERVER['HTTP_HOST'];   

// Append the requested resource location to the URL   
$url.= $_SERVER['REQUEST_URI'];

function show_404($message = "Page not found.")
{
	require_once VIEW . "/includes/header.php";
	require_once VIEW . "/errors/404.php";
	require_once VIEW . "/includes/footer.php";
	exit;
}

function add_route($route, $controller, $method)
{
	global $url;

	$params = [];
	$arguments = [];

	// Separate by /
	// Loop through all /
	// if ([0] == ":")
	// 	Get index of current URL
	// 	Get value from current URL
	// 	Replace in new_route

	$new_url = explode("/", $url);
	$new_route = explode("/", URL . $route);
	for ($a = 0; $a < count($new_route); $a++)
	{
		if (isset($new_route[$a][0]) && $new_route[$a][0] == ":")
		{
			for ($b = 0; $b < count($new_url); $b++)
			{
				if ($b == $a)
				{
					array_push($params, $new_route[$a]);
					array_push($arguments, $new_url[$b]);
					$new_route[$a] = $new_url[$b];
				}
			}
		}
	}

	$new_route = implode("/", $new_route);

	// if ($route == "/verify-email/:email")
	// {
	// 	print_r([$new_route, $url, $arguments, $params]);
	// 	exit;
	// }

	// $new_route = explode("/", $route);
	// foreach ($new_route as $key => $value)
	// {
	// 	if (isset($value[0]) && $value[0] == ":")
	// 	{
	// 		$value = ltrim($value, ":");

	// 		array_push($params, $value);
	// 		array_push($arguments, $key);

	// 		unset($new_route[$key]);
	// 	}
	// }
	// $new_route = implode("/", $new_route);
	// if (count($arguments) > 0)
	// {
	// 	$arguments_join = implode("/", $arguments);
	// 	$new_route = URL . $new_route . "/" . $arguments_join;
	// }
	// else
	// {
	// 	$new_route = URL . $new_route;
	// }

	// if ($route == "/verify-email/:email")
	// {
	// 	print_r([$new_route, $url]);
	// 	exit;
	// }

	if ($new_route == $url)
	{
		require_once("controllers/" . $controller . ".php");
		$controller = new $controller();

		if(isset($arguments[4]))
			$controller->{$method}($arguments[0], $arguments[1], $arguments[2], $arguments[3], $arguments[4]);
		else if(isset($arguments[3]))
			$controller->{$method}($arguments[0], $arguments[1], $arguments[2], $arguments[3]);
		else if(isset($arguments[2]))
			$controller->{$method}($arguments[0], $arguments[1], $arguments[2]);
		else if(isset($arguments[1]))
			$controller->{$method}($arguments[0], $arguments[1]);
		else if(isset($arguments[0]))
			$controller->{$method}($arguments[0]);
		else
			$controller->{$method}();
		exit;
	}
}