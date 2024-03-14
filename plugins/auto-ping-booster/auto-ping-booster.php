<?php
/*
Plugin Name: Auto Ping Booster
Plugin URI: "http://seoleaders.club/
Description: Auto Ping Booster ping your website updates to top search engines and rss directories. Contact its developer <a href="http://www.samee.us">Samee Ullah Feroz</a>
Version: 1.2
Author: Samee Ullah Feroz
Author URI: "http://www.samee.us
*/

add_action('simple_edit_form', 'ping_booster');
function ping_booster()  {
	$ping = array("http://www.blogsearch.google.com/ping/RPC2",
					"http://rpc.pingomatic.com",
					"http://www.blogsearch.google.ae/ping/RPC2",
					"http://www.blogsearch.google.at/ping/RPC2",
					"http://www.blogsearch.google.be/ping/RPC2",
					"http://www.blogsearch.google.bg/ping/RPC2",
					"http://www.blogsearch.google.ca/ping/RPC2",
					"http://www.blogsearch.google.ch/ping/RPC2",
					"http://www.blogsearch.google.cl/ping/RPC2",
					"http://www.blogsearch.google.co.cr/ping/RPC2",
					"http://www.blogsearch.google.co.hu/ping/RPC2",
					"http://www.blogsearch.google.co.id/ping/RPC2",
					"http://www.blogsearch.google.co.il/ping/RPC2",
					"http://www.blogsearch.google.co.in/ping/RPC2",
					"http://www.blogsearch.google.co.jp/ping/RPC2",
					"http://www.blogsearch.google.co.ma/ping/RPC2",
					"http://www.blogsearch.google.co.nz/ping/RPC2",
					"http://www.blogsearch.google.co.th/ping/RPC2",
					"http://www.blogsearch.google.co.uk/ping/RPC2",
					"http://www.blogsearch.google.co.ve/ping/RPC2",
					"http://www.blogsearch.google.co.za/ping/RPC2",
					"http://www.blogsearch.google.com.ar/ping/RPC2",
					"http://www.blogsearch.google.com.au/ping/RPC2",
					"http://www.blogsearch.google.com.br/ping/RPC2",
					"http://www.blogsearch.google.com.co/ping/RPC2",
					"http://www.blogsearch.google.com.do/ping/RPC2",
					"http://www.blogsearch.google.com.mx/ping/RPC2",
					"http://www.blogsearch.google.com.my/ping/RPC2",
					"http://www.blogsearch.google.com.pe/ping/RPC2",
					"http://www.blogsearch.google.com.sa/ping/RPC2",
					"http://www.blogsearch.google.com.sg/ping/RPC2",
					"http://www.blogsearch.google.com.tr/ping/RPC2",
					"http://www.blogsearch.google.com.tw/ping/RPC2",
					"http://www.blogsearch.google.com.ua/ping/RPC2",
					"http://www.blogsearch.google.com.uy/ping/RPC2",
					"http://www.blogsearch.google.com.vn/ping/RPC2",
					"http://www.blogsearch.google.de/ping/RPC2",
					"http://www.blogsearch.google.es/ping/RPC2",
					"http://www.blogsearch.google.fi/ping/RPC2",
					"http://www.blogsearch.google.fr/ping/RPC2",
					"http://www.blogsearch.google.gr/ping/RPC2",
					"http://www.blogsearch.google.hr/ping/RPC2",
					"http://www.blogsearch.google.ie/ping/RPC2",
					"http://www.blogsearch.google.it/ping/RPC2",
					"http://www.blogsearch.google.jp/ping/RPC2",
					"http://www.blogsearch.google.lt/ping/RPC2",
					"http://www.blogsearch.google.nl/ping/RPC2",
					"http://www.blogsearch.google.pl/ping/RPC2",
					"http://www.blogsearch.google.pt/ping/RPC2",
					"http://www.blogsearch.google.ro/ping/RPC2",
					"http://www.blogsearch.google.ru/ping/RPC2",
					"http://www.blogsearch.google.se/ping/RPC2",
					"http://www.blogsearch.google.sk/ping/RPC2",
					"http://www.blogsearch.google.us/ping/RPC2",
					"http://ping.blogs.yandex.ru/RPC2",
					"http://rpc.twingly.com",
					"http://api.feedster.com/ping",
					"http://api.moreover.com/RPC2",
					"http://api.moreover.com/ping",
					"http://bblog.com/ping.php",
					"http://www.blogdigger.com/RPC2",
					"http://www.blogpeople.net/servlet/weblogUpdates",
					"http://ping.bloggers.jp/rpc/",
					"http://blogsearch.google.com/ping/RPC2",
					"http://www.blogshares.com/rpc.php",
					"http://www.blogsnow.com/ping",
					"http://www.blogstreet.com/xrbin/xmlrpc.cgi",
					"http://bulkfeeds.net/rpc",
					"http://www.newsisfree.com/xmlrpctest.php",
					"http://ping.blo.gs/",
					"http://ping.feedburner.com",
					"http://ping.syndic8.com/xmlrpc.php",
					"http://ping.weblogalot.com/rpc.php",
					"http://rpc.blogrolling.com/pinger/",
					"http://rpc.technorati.com/rpc/ping",
					"http://rpc.weblogs.com/RPC2",
					"http://www.feedsubmitter.com",
					"http://blo.gs/ping.php",
					"http://www.pingerati.net",
					"http://www.pingmyblog.com",
					"http://geourl.org/ping",
					"http://ipings.com",
					"http://topicexchange.com/RPC2",
					"http://www.weblogalot.com/ping",
					"http://xping.pubsub.com/ping",);
	foreach($ping as $pinger)
	{
		echo '<script>document.post.trackback_url.defaultValue="' . $pinger . '";</script>';
	}
}

?>