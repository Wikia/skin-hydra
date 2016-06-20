<?php
$script = str_replace(__DIR__.'/', '', __FILE__);
$urlParts = explode($script.'?', $_SERVER['REQUEST_URI']);
if (empty($urlParts[1])) {
	http_response_code(404);
	exit;
}
$url = str_replace(['/cdn2', '/cdn3'], ['http://cdn2.cmpstar.com', 'cdn3.cpmstar.com'], $urlParts[1]);
if (filter_var($url, FILTER_VALIDATE_URL)) {
	define("CA_COOKIE_SETUP", true);

	putenv("MW_INSTALL_PATH=".dirname(dirname(__DIR__)));
	require(dirname(dirname(__DIR__)).'/includes/WebStart.php');

	$contents = Http::get($url);
	var_dump($contents);
}
http_response_code(404);
