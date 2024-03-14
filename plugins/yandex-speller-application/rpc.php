<?php
/*  Copyright 2009  Dmitry Ponomarev (email : ponomarev.dev@gmail.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Set RPC response headers
header('Content-Type: text/plain');
header('Content-Encoding: UTF-8');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$raw = "" . file_get_contents("php://input");

// No input data
if (!$raw) {
	die('{"result":null,"id":null,"error":{"errstr":"Could not get raw post data.","errfile":"","errline":null,"errcontext":"","level":"FATAL"}}');
}

$url = parse_url('http://speller.yandex.net/services/tinyspell');

// Setup request 
$req = "POST " . $url["path"] . " HTTP/1.0\r\n";
$req .= "Connection: close\r\n";
$req .= "Host: " . $url["host"] . "\r\n";
$req .= "Content-Type: application/json\r\n";
$req .= "Content-Length: " . strlen($raw) . "\r\n";
$req .= "\r\n" . $raw;

if (!isset($url['port']) || !$url['port']) {
	$url['port'] = 80;
}

$errno = $errstr = "";

$socket = fsockopen($url['host'], intval($url['port']), $errno, $errstr, 30);
if ($socket) {
	// Send request headers
	fputs($socket, $req);

	// Read response headers and data
	$resp = "";
	while (!feof($socket)) { 
		$resp .= fgets($socket, 4096);
	}

	fclose($socket);

	// Split response header/data
	$resp = explode("\r\n\r\n", $resp);
	print $resp[1]; // Output body
}
?>