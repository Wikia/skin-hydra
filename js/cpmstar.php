<?php
$urlParts = explode($_SERVER['SCRIPT_FILENAME'], $_SERVER['REQUEST_URI']);
if (empty($urlParts[1])) {
	http_response_code(404);
	exit;
}
$url = str_replace(['cd2', 'cdn3'], ['cdn2.cmpstar.com', 'cdn3.cpmstar.com'], $urlParts[1]);
var_dump($url);
