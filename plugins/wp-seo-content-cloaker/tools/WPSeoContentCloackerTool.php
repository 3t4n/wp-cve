<?php

if(!class_exists('WPSeoContentCloackerTool')){
	// Utilitaires
	class WPSeoContentCloackerTool {
		
		var $seoCrawlerUserAgentList;

		public function __construct(){

		}

		// Vérification Google bot
		public function isGoogleBot(){
			$remoteAddr =  $_SERVER['REMOTE_ADDR'];
			$host = gethostbyaddr($remoteAddr);
			$ipFromHost = gethostbyname($host);
			if($remoteAddr != $ipFromHost){ return false; }
			$isGoogleBot = false;
			$googleHosts = $this->getGoogleHosts();
			foreach($googleHosts as $dns){
			if(preg_match("#".$dns."#isU", $host)){
					$isGoogleBot = true;
					break;
				}
			}
			return $isGoogleBot;
		}

		public function getGoogleHosts(){
			return array(
				'google.com',
				'googlebot.com'
			);
		}

		// Vérification des bots SEO
		public function isSeoCrawler(){
			$userAgent = $_SERVER['HTTP_USER_AGENT'];
			$seoCrawlers = $this->getSeoCrawlersUserAgentList();
			$isSeoCrawler = false;
			foreach($seoCrawlers as $seoCrawler){
				if(preg_match("#".$seoCrawler."#isU", $userAgent)){
					$isSeoCrawler = true;
					break;
				}
			}
		}

		public function getSeoCrawlersUserAgentList(){
			return array(
					"AhrefsBot",
					"MJ12bot",
					"Yandex",
					"YandexBot",
					"UbiCrawler",
					"DOC",
					"Zao",
					"sitecheck.internetseer.com",
					"Zealbot",
					"MSIECrawler",
					"SiteSnagger",
					"WebStripper",
					"WebCopier",
					"Fetch",
					"Offline Explorer",
					"Teleport",
					"TeleportPro",
					"WebZIP",
					"linko",
					"HTTrack",
					"Microsoft.URL.Control",
					"Xenu",
					"larbin",
					"libwww",
					"ZyBORG",
					"Download Ninja",
					"rogerbot",
					"spbot",
					"Sosospider",
					"BacklinkCrawler",
					"ia_archiver",
					"Ezooms",
					"Gigabot",
					"findlinks",
					"SurveyBot",
					"SEOENGBot",
					"BPImageWalker",
					"bdbrandprotect",
					"LinkWalker",
					"Updownerbot",
					"HTTrack",
					"SemrushBot",
					"SemrushBot-SA"
				);
		}
	}
}