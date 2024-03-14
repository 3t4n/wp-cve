<?php
$environments = array();
$environments["develop"] 	=	array(
	"name"		=>	"Sandbox Test",
	"api_url"	=>	"https://api.uat.payflex.co.za",
	"auth_url"  =>  "https://payflex.eu.auth0.com/oauth/token",
	"web_url"	=>	"https://api.uat.payflex.co.za",
	"auth_audience" => "https://auth-dev.payflex.co.za",
);

$environments["production"] =	array(
	"name"		=>	"Production",
	"api_url"	=>	"https://api.payflex.co.za",
	"auth_url"  =>  "https://payflex-live.eu.auth0.com/oauth/token",
	"web_url"	=>	"https://api.payflex.co.za",
	"auth_audience" => "https://auth-production.payflex.co.za",
);
