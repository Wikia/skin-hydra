<?php
$script = str_replace(__DIR__.'/', '', __FILE__);
$urlParts = explode($script.'?', $_SERVER['REQUEST_URI']);
if (empty($urlParts[1])) {
	http_response_code(404);
	exit;
}
$url = str_replace(['/cdn2', '/cdn3'], ['http://cdn2.cpmstar.com', 'http://cdn3.cpmstar.com'], $urlParts[1]);
if (filter_var($url, FILTER_VALIDATE_URL)) {
	define("CA_COOKIE_SETUP", true);

	$IP = dirname(dirname(dirname(__DIR__)));
	putenv("MW_INSTALL_PATH=".$IP);
	require($IP.'/includes/WebStart.php');

	$contents = Http::get($url);
	if ($contents !== false) {
		$extension = substr($url, strrpos($url, '.') + 1);
		header('Content-Type: image/'.$extension);
		echo $contents;
		exit;
	}
}
http_response_code(404);
