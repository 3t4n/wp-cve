<?php // Last Update 17 sep 2013

defined( 'ABSPATH' ) or die( 'Cheatin\' uh?' );

global $sppsc_vulnerables;
$sppsc_vulnerables = array(
	'solvemedia' =>	array(
						'Name' => 'Solve Media CAPTCHA',
						'Version' => '1.1.0', 'Patch' => '1.1.1',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'Exploit-DB' => 'http://www.exploit-db.com/exploits/24364' ), 
					),
	'devformatter' => 	array(
						'Name' => 'Developer Formatter',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'Exploit-DB' => 'http://www.exploit-db.com/exploits/24294' ), 
					),
	'ripe-hd-player' => 	array(
						'Name' => 'WordPress Ripe HD FLV Player',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'SQL injection' ),
						'Links' => array( 'Exploit-DB' => 'http://www.exploit-db.com/exploits/24229' ), 
					),
							
	'advanced-custom-fields' => 	array(
						'Name' => 'Advanced Custom Fields',
						'Version' => '3.5.1', 'Patch' => '3.5.2',
						'Flaws' => array( 'Remote File Inclusion' ),
						'Links' => array( 'Exploit-DB' => 'http://www.exploit-db.com/exploits/23856' ), 
					),
	'google-document-embedder' => 	array(
						'Name' => 'Google Document Embedder',
						'Version' => '2.4.6', 'Patch' => '2.5',
						'Flaws' => array( 'Arbitrary File Disclosure' ),
						'Links' => array( 'Exploit-DB' => 'http://www.exploit-db.com/exploits/23970' ), 
					),
	'wp-property' => 	array(
						'Name' => 'WP-Property',
						'Version' => '1.35.0', 'Patch' => '1.35.1',
						'Flaws' => array( 'PHP Upload' ),
						'Links' => array( 'Exploit-DB' => 'http://www.exploit-db.com/exploits/23651' ), 
					),
	'portable-phpmyadmin' => 	array(
						'Name' => 'Portable phpMyAdmin',
						'Version' => '1.3', 'Patch' => '1.3.1',
						'Flaws' => array( 'Authentication Bypass' ),
						'Links' => array( 'Exploit-DB' => 'http://www.exploit-db.com/exploits/23356' ), 
					),
	'w3-total-cache' => 	array(
						'Name' => 'W3 Total Cache',
						'Version' => '0.9.2.8', 'Patch' => '0.9.2.9',
						'Flaws' => array( 'PHP Execution' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/osvdb/OSVDB-92652' ), 
					),
	'player' => 	array(
						'Name' => 'Spider Video Player',
						'Version' => '2.1', 'Patch' => '',
						'Flaws' => array( 'SQL injection' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121250/WordPress-Spider-Video-Player-2.1-SQL-Injection.html' ), 
					),
	'spiffy' => 	array(
						'Name' => 'Spiffy XSPF Player',
						'Version' => '0.1', 'Patch' => '',
						'Flaws' => array( 'SQL injection' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121204/WordPress-Spiffy-XSPF-Player-0.1-SQL-Injection.html' ), 
					),
	'trafficanalyzer' => 	array(
						'Name' => 'Traffic Analyzer',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121167/WordPress-Traffic-Analyzer-Cross-Site-Scripting.html' ), 
					),
	'wp-funeral-press' => 	array(
						'Name' => 'WP FuneralPress',
						'Version' => '1.1.6', 'Patch' => '1.1.7',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121030/WordPress-FuneralPress-1.1.6-Cross-Site-Scripting.html' ), 
					),
	'podpress' => 	array(
						'Name' => 'Podpress',
						'Version' => '8.8.10.13', 'Patch' => '8.8.10.17',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://www.exploit-db.com/exploits/' ), 
					),
	'mathjax-latex' => 	array(
						'Name' => 'MathJax-LaTeX',
						'Version' => '1.1', 'Patch' => '1.2.1',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120931/WordPress-Mathjax-Latex-1.1-Cross-Site-Request-Forgery.html' ), 
					),
	'wp-banners-lite' => 	array(
						'Name' => 'WP Banners Lite',
						'Version' => '1.40', 'Patch' => '',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120928/WP-Banners-Lite-1.40-Cross-Site-Scripting.html' ), 
					),
	'finalist' => 	array(
						'Name' => 'Finalist',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'SQL Injetion' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120951/WordPress-Finalist-SQL-Injection.html' ), 
					),
	'events-manager' => 	array(
						'Name' => 'Events Manager',
						'Version' => '5.3.8', 'Patch' => '5.3.9',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120688/WordPress-Events-Manager-5.3.3-Cross-Site-Scripting.html' ), 
					),
	'simply-poll' => 	array(
						'Name' => 'Simply Poll',
						'Version' => '1.4.1', 'Patch' => '',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120833/WordPress-Simply-Poll-1.4.1-CSRF-XSS.html' ), 
					),
	'levelfourstorefront' => 	array(
						'Name' => 'Level Four Storefront',
						'Version' => '8.1.14', 'Patch' => '8.1.15',
						'Flaws' => array( 'SQL Injection' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120950/WordPress-Level-Four-Storefront-SQL-Injection.html' ), 
					),
	'faqs-manager' => 	array(
						'Name' => 'FAQs Manager',
						'Version' => '1.0', 'Patch' => '',
						'Flaws' => array( 'XSS', 'CSRF', 'SQL Injection' ),
						'Links' => array( 'Packet Storm 1' => 'http://packetstormsecurity.com/files/120910/WordPress-IndiaNIC-FAQS-Manager-1.0-XSS-CSRF.html', 'Packet Storm 2' => 'http://packetstormsecurity.com/files/120911/WordPress-IndiaNIC-FAQS-Manager-1.0-SQL-Injection.html', ), 
					),
					
	'count-per-day' => 	array(
						'Name' => 'Count per Day',
						'Version' => '3.2.5', 'Patch' => '',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120870/WordPress-Count-Per-Day-3.2.5-XSS.html' ), 
					),
	'occasions' => 	array(
						'Name' => 'Occasions',
						'Version' => '1.0.4', 'Patch' => '',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120871/WordPress-Occasions-1.0.4-Cross-Site-Request-Forgery.html' ), 
					),
	'leaguemanager' => 	array(
						'Name' => 'LeagueManager',
						'Version' => '3.8', 'Patch' => '3.8.1',
						'Flaws' => array( 'SQL Injection' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120817/WordPress-LeagueManager-3.8-SQL-Injection.html' ), 
					),
	'terillion-reviews' => 	array(
						'Name' => 'Terillion Reviews',
						'Version' => '1.0', 'Patch' => '1.1',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120730/WordPress-Terillion-Reviews-Cross-Site-Scripting.html' ), 
					),
	'wordpress-plugin-comment-rating' => 	array(
						'Name' => 'WordPress Plugin Comment Rating',
						'Version' => '2.9.32', 'Patch' => '',
						'Flaws' => array( 'SQL Injection', 'Vote ByPass' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120569/WordPress-Comment-Rating-2.9.32-SQL-Injection-Bypass.html' ), 
					),
	'pretty-link' => 	array(
						'Name' => 'Pretty Link Lite',
						'Version' => '1.6.3', 'Patch' => '1.6.4',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120433/WordPress-Pretty-Link-1.6.3-Cross-Site-Scripting.html' ), 
					),
	'marekkis-watermark' => 	array(
						'Name' => 'Marekkis Watermark-Plugin',
						'Version' => '0.9.1', 'Patch' => '0.9.2',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120378/WordPress-Marekkis-Watermark-Cross-Site-Scripting.html' ), 
					),
	'responsive-logo-slideshow' => 	array(
						'Name' => 'Responsive Logo Slideshow',
						'Version' => '1.2', 'Patch' => '',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120379/WordPress-Responsive-Logo-Slideshow-Cross-Site-Scripting.html' ), 
					),
	'audio-player' => 	array(
						'Name' => 'Audio Player',
						'Version' => '2.0.4.1', 'Patch' => '2.0.4.3',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120129/WordPress-Audio-Player-SWF-Cross-Site-Scripting.html' ), 
					),
	'commentluv' => 	array(
						'Name' => 'CommentLuv',
						'Version' => '2.92.3', 'Patch' => '2.92.4',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120090/WordPress-CommentLuv-2.92.3-Cross-Site-Scripting.html' ), 
					),
	'wysija-newsletters' => 	array(
						'Name' => 'Wysija Newsletters',
						'Version' => '2.2', 'Patch' => '2.2.1',
						'Flaws' => array( 'SQL Injection' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/120089/WordPress-Wysija-Newsletters-2.2-SQL-Injection.html' ), 
					),
	'wp-table-reloaded' => 	array(
						'Name' => 'WP-Table Reloaded',
						'Version' => '1.9.3', 'Patch' => '1.9.4',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/119968/WordPress-WP-Table-Reloaded-Cross-Site-Scripting.html' ), 
					),
	'solvemedia' => 	array(
						'Name' => 'Solve Media CAPTCHA',
						'Version' => '1.1.0', 'Patch' => '1.1.1',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/119834/WordPress-SolveMedia-1.1.0-Cross-Site-Request-Forgery.html' ), 
					),
	'floating-tweets' => 	array(
						'Name' => 'Floating Tweets',
						'Version' => '1.0.1', 'Patch' => '',
						'Flaws' => array( 'XSS', 'Directory Traversal' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/119499/WordPress-Floating-Tweets-1.0.1-XSS-Directory-Traversal.html' ), 
					),
	'gallery-plugin' => 	array(
						'Name' => 'Gallery',
						'Version' => '3.8.3', 'Patch' => '3.8.9',
						'Flaws' => array( 'Arbitrary File Read' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/119458/WordPress-Gallery-3.8.3-Arbitrary-File-Read.html' ), 
					),
	'google-document-embedder' => 	array(
						'Name' => 'Google Doc Embedder',
						'Version' => '2.4.6', 'Patch' => '2.5.6',
						'Flaws' => array( 'Arbitrary File Disclosure' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/119329/WordPress-Google-Document-Embedder-Arbitrary-File-Disclosure.html' ), 
					),
	'google-sitemap-generator' => 	array(
						'Name' => 'Google XML Sitemaps',
						'Version' => '3.2.8', 'Patch' => '3.2.9',
						'Flaws' => array( 'PHP Code Injection' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/119357/XML-Sitemap-Generator-3.2.8-Code-Injection.html' ), 
					),
	'spam-free-wordpress' => 	array(
						'Name' => 'Spam Free WordPress',
						'Version' => '1.9.2', 'Patch' => '',
						'Flaws' => array( 'Filter ByPass' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/119274/WordPress-Spam-Free-1.9.2-Filter-Bypass.html' ), 
					),
	'advanced-custom-fields' => 	array(
						'Name' => 'Advanced Custom Fields',
						'Version' => '3.5.1', 'Patch' => '3.5.2',
						'Flaws' => array( 'Remote File Inclusion' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/119221/WordPress-Advanced-Custom-Fields-Remote-File-Inclusion.html' ), 
					),	
	'advanced-custom-fields' => 	array(
						'Name' => 'Advanced Custom Fields',
						'Version' => '3.8.5.1', 'Patch' => '4.0.0.0',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'Packet Storm' => '' ), 
					),
	'xerte-online' => 	array(
						'Name' => 'Xerte Online',
						'Version' => '0.32', 'Patch' => '0.36',
						'Flaws' => array( 'Shell Upload' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/119220/WordPress-Xerte-Online-0.32-Shell-Upload.html' ), 
					),
	'uploader' => 	array(
						'Name' => 'Uploader',
						'Version' => '1.0.4', 'Patch' => '',
						'Flaws' => array( 'Shell Upload' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/119219/WordPress-Uploader-1.0.4-Shell-Upload.html' ), 
					),
	'reflex-gallery' => 	array(
						'Name' => 'ReFlex Gallery',
						'Version' => '1.3', 'Patch' => '',
						'Flaws' => array( 'Shell Upload' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/119218/WordPress-ReFlex-Gallery-1.3-Shell-Upload.html' ), 
					),
	'wordfence' => 	array(
						'Name' => 'WordFence',
						'Version' => '3.8.1', 'Patch' => '3.8.3',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122993/WordPress-Wordfence-3.8.1-Cross-Site-Scripting.html' ), 
					),
	'encrypted-blog' => 	array(
						'Name' => 'Encrypted Blog',
						'Version' => '0.0.6.2', 'Patch' => '',
						'Flaws' => array( 'Open Redirect' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122992/WordPress-Encrypted-Blog-0.0.6.2-XSS-Open-Redirect.html' ), 
					),
	'wp-simple-login-registration-plugin' => 	array(
						'Name' => 'WP Simple Login Registration Plugin',
						'Version' => '1.0.1', 'Patch' => '1.0.3',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122963/WordPress-Simple-Login-Registration-1.0.1-Cross-Site-Scripting.html' ), 
					),
	'post-gallery' => 	array(
						'Name' => 'WP Post Gallery',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122957/WordPress-Post-Gallery-Cross-Site-Scripting.html' ), 
					),
	'videowhisper-live-streaming-integration' => 	array(
						'Name' => 'Videowhisper Live Streaming Integration',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122943/WordPress-Video-Whisper-Cross-Site-Scripting.html' ), 
					),
	'backwpup' => 	array(
						'Name' => 'BackWPup Free - WordPress Backup Plugin',
						'Version' => '3.0.12', 'Patch' => '3.0.13',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122916/WordPress-BackWPup-3.0.12-Cross-Site-Scripting.html' ), 
					),
	'thinkit-wp-contact-form' => 	array(
						'Name' => 'ThinkIT WP Contact Form',
						'Version' => '0.1', 'Patch' => '',
						'Flaws' => array( 'CSRF', 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122898/WordPress-ThinkIT-0.1-CSRF-Cross-Site-Scripting.html' ), 
					),
	'hms-testimonials' => 	array(
						'Name' => 'HMS Testimonials',
						'Version' => '2.0.10', 'Patch' => '2.0.11',
						'Flaws' => array( 'CSRF', 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122761/WordPress-HMS-Testimonials-2.0.10-XSS-CSRF.html' ), 
					),
	'usernoise' => 	array(
						'Name' => 'Usernoise modal feedback / contact form',
						'Version' => '3.7.8', 'Patch' => '3.7.9',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122701/WordPress-Usernoise-3.7.8-Cross-Site-Scripting.html' ), 
					),
	'booking' => 	array(
						'Name' => 'Booking Calendar',
						'Version' => '4.1.4', 'Patch' => '4.1.6',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122691/Booking-Calendar-4.1.4-Cross-Site-Request-Forgery.html' ), 
					),
	'comment-extra-field' => 	array(
						'Name' => 'Comment Extra Field',
						'Version' => '1.7', 'Patch' => '',
						'Flaws' => array( 'CSRF', 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122625/WordPress-Comment-Extra-Fields-1.7-CSRF-XSS.html' ), 
					),
	'better-wp-security' => 	array(
						'Name' => 'Better WP Security',
						'Version' => '3.5.5', 'Patch' => '3.5.6',
						'Flaws' => array( 'XSS', 'Command Execution' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122615/Bit51-Better-WP-Security-Plugin-XSS-Command-Execution.html' ), 
					),
	'duplicator' => 	array(
						'Name' => 'Duplicator',
						'Version' => '0.4.4', 'Patch' => '0.4.5',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122535/WordPress-Duplicator-0.4.4-Cross-Site-Scripting.html' ), 
					),
	'flagem' => 	array(
						'Name' => 'FlagEm',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122505/WordPress-FlagEm-Cross-Site-Scripting.html' ), 
					),
	'woocommerce' => 	array(
						'Name' => 'WooCommerce - excelling eCommerce',
						'Version' => '2.0.12', 'Patch' => '2.0.13',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122465/WordPress-WooCommerce-2.0.12-Cross-Site-Scripting.html' ), 
					),
	'spicy-blogroll' => 	array(
						'Name' => 'Spicy Blogroll',
						'Version' => '1.0.0', 'Patch' => '',
						'Flaws' => array( 'File Inclusion' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122396/WordPress-Spicy-Blogroll-Local-File-Inclusion.html' ), 
					),
	'js-restaurant' => 	array(
						'Name' => 'JS Restaurant',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'SQL injection' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122316/WordPress-JS-Restaurant-SQL-Injection.html' ), 
					),
	'searchnsave' => 	array(
						'Name' => 'SearchNSave',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122304/WordPress-Search-N-Save-XSS-Path-Disclosure.html' ), 
					),
	'booking-system' => 	array(
						'Name' => 'Booking System (Booking Calendar)',
						'Version' => '1.1', 'Patch' => '1.2',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122289/WordPress-Booking-System-Cross-Site-Scripting.html' ), 
					),
	'category-grid-view-gallery' => 	array(
						'Name' => 'Category Grid View Gallery',
						'Version' => '2.3.1', 'Patch' => '2.3.3',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122259/WordPress-Category-Grid-View-Gallery-XSS.html' ), 
					),
	'xorbin-digital-flash-clock' => 	array(
							'Name' => 'Xorbin Digital Flash Clock',
							'Version' => '1.0', 'Patch' => '',
							'Flaws' => array( 'XSS' ),
							'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122223/Xorbin-Digital-Flash-Clock-1.0-For-WordPress-XSS.html' ), 
						),
	'xorbin-analog-flash-clock' => 	array(
							'Name' => 'Xorbin Analog Flash Clock',
							'Version' => '1.0', 'Patch' => '',
							'Flaws' => array( 'XSS' ),
							'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122222/Xorbin-Analog-Flash-Clock-1.0-For-WordPress-XSS.html' ), 
						),
	'wp-private-messages' => 	array(
						'Name' => 'WP Private Messages',
						'Version' => '1.0.1', 'Patch' => '',
						'Flaws' => array( 'SQL injection' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122220/WordPress-WP-Private-Messages-SQL-Injection.html' ), 
					),
	'ultimate-auction' => 	array(
						'Name' => 'WordPress Auction Plugin',
						'Version' => '1.0', 'Patch' => '1.0.1',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122052/Ultimate-WordPress-Auction-1.0-Cross-Site-Request-Forgery.html' ), 
					),
	'nextgen-gallery' => 	array(
						'Name' => 'NextGEN Gallery',
						'Version' => '1.9.12', 'Patch' => '2.0.21',
						'Flaws' => array( 'Arbitrary File Upload' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/122021/NextGEN-Gallery-1.9.12-Shell-Upload.html' ), 
					),
	'wp-sendsms' => 	array(
						'Name' => 'WP-SendSMS',
						'Version' => '1.0', 'Patch' => '',
						'Flaws' => array( 'CSRF', 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121977/WordPress-WP-SendSMS-1.0-CSRF-XSS.html' ), 
					),
	'antivirus' => 	array(
						'Name' => 'AntiVirus',
						'Version' => '1.3.4', 'Patch' => '',
						'Flaws' => array( 'Security ByPass' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121833/AntiVirus-For-WordPress-1.0-Path-Disclosure-Bypass.html' ), 
					),
	'user-role-editor' => 	array(
						'Name' => 'User Role Editor',
						'Version' => '3.12', 'Patch' => '3.14',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121785/WordPress-User-Role-Editor-3.12-Cross-Site-Request-Forgery.html' ), 
					),
	'spider-event-calendar' => 	array(
						'Name' => 'WordPress Event Calendar',
						'Version' => '1.3.0', 'Patch' => '1.3.6',
						'Flaws' => array( 'XSS', 'SQL injection' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121709/Spider-Event-Calendar-1.3.0-Cross-Site-Scripting-Path-Disclosure-SQL-Injection.html' ), 
					),
	'catalog' => 	array(
						'Name' => 'WordPress Catalog',
						'Version' => '1.4.6', 'Patch' => '1.5.2',
						'Flaws' => array( 'XSS', 'SQL injection' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121708/Spider-Catalog-1.4.6-Cross-Site-Scripting-Path-Disclosure-SQL-Injection.html' ), 
					),
	'wp-filemanager' => 	array(
						'Name' => 'wp-FileManager',
						'Version' => '1.3.0', 'Patch' => '1.4.0',
						'Flaws' => array( 'Arbitrary File Download' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121637/WordPress-wp-FileManager-File-Download.html' ), 
					),
	'newsletter' => 	array(
						'Name' => 'Newsletter',
						'Version' => '3.2.6', 'Patch' => '3.2.7',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121634/Wordpress-Newsletter-3.2.6-Cross-Site-Scripting.html' ), 
					),
	'video-embed-thumbnail-generator' => 	array(
						'Name' => 'Video Embed & Thumbnail Generator',
						'Version' => '4.0.3', 'Patch' => '4.1',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121625/WordPress-Video-JS-Cross-Site-Scripting.html' ), 
					),
	'search-and-share' => 	array(
						'Name' => 'Search & Share',
						'Version' => '0.9.3', 'Patch' => '',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121595/WordPress-Search-And-Share-0.9.3-Cross-Site-Scripting.html' ), 
					),
	'securimage-wp' => 	array(
						'Name' => 'Securimage-WP',
						'Version' => '3.2.4', 'Patch' => '3.5.1',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121588/WordPress-Securimage-3.2.4-Cross-Site-Scripting.html' ), 
					),
	'advanced-xml-reader' => 	array(
						'Name' => 'Advanced XML Reader',
						'Version' => '0.3.4', 'Patch' => '',
						'Flaws' => array( 'XXE' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/121492/WordPress-Advanced-XML-Reader-0.3.4-XXE-Injection.html' ), 
					),
	'indianic-testimonial' => 	array(
						'Name' => 'Indianic Testimonial',
						'Version' => '2.2', 'Patch' => '',
						'Flaws' => array( 'CSRF', 'XSS', 'SQL injection' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/123036/wptestimonial-xssxsrfsql.txt' ), 
					),
	'nextgen-smooth-gallery' => 	array(
						'Name' => 'Nextgen Smooth Gallery',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/123074/wpngsg-xss.txt' ), 
					),
	'cms-tree-page-view' => 	array(
						'Name' => 'CMS Tree Page View',
						'Version' => '1.2.4', 'Patch' => '1.2.5',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'tubepress' => 	array(
						'Name' => 'TubePress',
						'Version' => '2.2.9', 'Patch' => '2.4',
						'Flaws' => array( 'CSRF', 'XSS' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'wsi' => 	array(
						'Name' => 'WP Splash Image',
						'Version' => '1.3.4', 'Patch' => '1.4.0',
						'Flaws' => array( 'CSRF', 'XSS' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'websitedefender-wordpress-security' => 	array(
						'Name' => 'WebsiteDefender WordPress Security',
						'Version' => '0.5', 'Patch' => '0.9',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'p3-profiler' => 	array(
						'Name' => 'P3 (Plugin Performance Profiler)',
						'Version' => '1.0.2', 'Patch' => '1.0.5',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'wordpress-sentinel' => 	array(
						'Name' => 'WordPress Sentinel',
						'Version' => '1.0.0', 'Patch' => '1.0.1',
						'Flaws' => array( 'CSRF', 'XSS', 'SQL injection' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'simple-tags' => 	array(
						'Name' => 'Simple Tags',
						'Version' => '2.1.1', 'Patch' => '2.1.2',
						'Flaws' => array( 'CSRF', 'XSS' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'ckeditor-for-wordpress' => 	array(
						'Name' => 'CKEditor For WordPress',
						'Version' => '3.6.2', 'Patch' => '3.6.2.1',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'lightbox-2' => 	array(
						'Name' => 'Lightbox 2',
						'Version' => '2.9.2', 'Patch' => '',
						'Flaws' => array( 'CSRF', 'XSS' ),
						'Links' => array( 'NVD' => 'http://web.nvd.nist.gov/view/vuln/detail?vulnId=CVE-2008-2490' ), 
					),
	'wp-htaccess-control' => 	array(
						'Name' => 'WP htaccess Control',
						'Version' => '2.4', 'Patch' => '2.5.8',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'wp-plugin-security-check' => 	array(
						'Name' => 'WP Plugin Security Check',
						'Version' => '0.3', 'Patch' => '0.4',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'database-browser' => 	array(
						'Name' => 'Database Browser',
						'Version' => '1.0', 'Patch' => '1.1',
						'Flaws' => array( 'CSRF', 'XSS', 'SQL injection' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'wp-activity' => 	array(
						'Name' => 'WP-Activity',
						'Version' => '0.9', 'Patch' => '0.9.1',
						'Flaws' => array( 'CSRF', 'XSS' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'wptouch' => 	array(
						'Name' => 'WPtouch Mobile Plugin',
						'Version' => '1.9.31', 'Patch' => '1.9.32',
						'Flaws' => array( 'CSRF', 'XSS' ),
						'Links' => array( 'Hight-Tech Bridge' => 'https://www.htbridge.com/advisory/HTB22698' ), 
					),
	'yourls-wordpress-to-twitter' => 	array(
						'Name' => 'YOURLS: WordPress to Twitter',
						'Version' => '1.4.5', 'Patch' => '1.5.1',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'wordpress-flash-uploader' => 	array(
						'Name' => 'WordPress Flash Uploader',
						'Version' => '2.11.1', 'Patch' => '2.13',
						'Flaws' => array( 'CSRF', 'Information Disclosure' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'faster-image-insert' => 	array(
						'Name' => 'Faster Image Insert',
						'Version' => '2.4.0', 'Patch' => '2.4.1',
						'Flaws' => array( 'CSRF', 'XSS' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'stream-video-player' => 	array(
						'Name' => 'Stream Video Player',
						'Version' => '1.2', 'Patch' => '1.3.1',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'link-to-post' => 	array(
						'Name' => 'Link to Post',
						'Version' => '1.0', 'Patch' => '1.0.1',
						'Flaws' => array( 'XSS', 'SQL Injection' ),
						'Links' => array( 'SecuPress' => '' ), 
					),
	'thanks-you-counter-button' => 	array(
						'Name' => 'Thank You Counter Button',
						'Version' => '1.8.2', 'Patch' => '1.8.3',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://packetstormsecurity.com/files/117612/sa50977.txt' ), 
					),
	'cart66-lite' => 	array(
						'Name' => 'Cart66 Lite :: WordPress Ecommerce',
						'Version' => '1.5.1.14', 'Patch' => '1.5.1.17',
						'Flaws' => array( 'XSS', 'CSRF' ),
						'Links' => array( 'Packet Storm' => 'http://dl.packetstormsecurity.net/1310-exploits/wpcart66-xssxsrf.txt' ), 
					),
	'quick-contact-form' => 	array(
						'Name' => 'Quick Contact Form',
						'Version' => '6.0', 'Patch' => '6.3',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Packet Storm' => 'http://dl.packetstormsecurity.net/1310-exploits/wpquickcontact-xss.txt' ), 
					),
	'dexs-pm-system' => 	array(
						'Name' => 'Dexs PM System',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'CSRF' ),
						'Links' => array( 'Packet Storm' => 'http://dl.packetstormsecurity.net/1310-exploits/wpdexspmsystem-xss.txt' ), 
					),
	'wp-realty' => 	array(
						'Name' => 'WP Realty',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'SQL injection' ),
						'Links' => array( 'Packet Storm' => 'http://dl.packetstormsecurity.net/1310-exploits/wprealty-sql.txt' ), 
					),
	'formcraft' => 	array(
						'Name' => 'FormCraft',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'SQL injection' ),
						'Links' => array( 'Packet Storm' => 'http://dl.packetstormsecurity.net/1312-exploits/wpformcraft-sql.txt' ), 
					),
	'dzs-videogallery' => 	array(
						'Name' => 'DZS Videogallery',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'Remote File Disclosure' ),
						'Links' => array( 'Packet Storm' => 'http://dl.packetstormsecurity.net/1312-exploits/wpdzsvg-disclose.txt' ), 
					),
	'page-flip-image-gallery' => 	array(
						'Name' => 'Page Flip Image Gallery',
						'Version' => '*', 'Patch' => '',
						'Flaws' => array( 'Shell Upload' ),
						'Links' => array( 'Packet Storm' => 'http://dl.packetstormsecurity.net/1312-exploits/wppageflipig-shell.txt' ), 
					),
	'download-manager' => 	array(
						'Name' => 'WordPress Download Manager',
						'Version' => '2.5.8', 'Patch' => '2.5.9',
						'Flaws' => array( 'XSS' ),
						'Links' => array( 'Exploit DB' => 'http://www.exploit-db.com/exploits/30105/' ), 
					),
	// '' => 	array(
						// 'Name' => '',
						// 'Version' => '', 'Patch' => '',
						// 'Flaws' => array( '' ),
						// 'Links' => array( 'Packet Storm' => '' ), 
					// ),

				);