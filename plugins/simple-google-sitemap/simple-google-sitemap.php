<?php
/* ************************************************************************ */
/* This file is (c) by Peter Liebetrau                                      */
/* simple-google-sitemap.php                                                */
/* ************************************************************************ */


/*
 Plugin Name: Simple Google Sitemap 
 Plugin URI: http://suche.pytalhost.de/2008/12/13/wordpress-plugin-simple-google-sitemap-v10
 Description: This plugin will generate a sitemaps.org compatible sitemap of your WordPress blog which is supported by Ask.com, Google, MSN Search and YAHOO. 
 Version: 1.6
 Author: Peter Liebetrau
 Author URI: http://suche.pytalhost.de/
*/

class SimpleGoogleSitemap{
	var $version = '1.6';
	
	// Pathes and URLs
	var $siteurl = '';
	
	var $xslurl = '';
	var $sitemappath = '';
	var $sitemap_url = '';
	var $pingurls = array();
	
	// Errors, Notes, Hints, etc. goes here
	var $msg = '';

	// Options
	var $changefreq = array(
		'homepage' => 'daily',
		'post' => 'weekly',
		'page' => 'weekly'
	);
	var $priority = array(
		'homepage' => '1.0',
		'post' => '0.8',
		'page' => '0.8'
	);

	function SimpleGoogleSitemap(){
		$this->__construct();
	}
	function __construct(){
		$this->siteurl = get_bloginfo('wpurl').'/';
		$this->xslurl = $this->siteurl.'wp-content/plugins/simple-google-sitemap/sitemap.xsl';
		$this->sitemappath = dirname(dirname(dirname(dirname(__FILE__)))).'/sitemap.xml';
		$this->sitemap_url = $this->siteurl.'sitemap.xml';
		
		$sitemap_url = urlencode($this->sitemap_url);
		
		$this->pingurls[] = array(
			'service' => 'ASK.COM',
			'url' => 'http://submissions.ask.com/ping?sitemap='.$sitemap_url,
			'snippet' => 'Your Sitemap has been successfully received and added to our Sitemap queue.'
		);
		$this->pingurls[] = array(
			'service' => 'GOOGLE',
			'url' => 'http://www.google.com/webmasters/sitemaps/ping?sitemap='.$sitemap_url,
			'snippet' => 'Your Sitemap has been successfully added to our list of Sitemaps to crawl.'
		);
		$this->pingurls[] = array(
			'service' => 'MSN',
			'url' => 'http://webmaster.live.com/ping.aspx?siteMap='.$sitemap_url,
			'snippet' => 'Thanks for submitting your sitemap.'
		);
		$this->pingurls[] = array(
			'service' => 'YAHOO',
			'url' => 'http://search.yahooapis.com/SiteExplorerService/V1/ping?sitemap='.$sitemap_url,
			'snippet' => 'Update notification has successfully submitted.'
		);

		$this->getoptions();

		add_action('admin_menu', array(&$this, 'RegisterAdminPage'));

		add_action('delete_post', array(&$this, 'create'),9999,1);
		add_action('publish_post', array(&$this, 'create'),9999,1);
		add_action('publish_page', array(&$this, 'create'),9999,1);
		
		// 
		load_textdomain('simplesitemap', dirname(__FILE__).'/language/'.get_locale().'.mo');
	}

	function RegisterAdminPage() {
		add_options_page('Simple Google Sitemap', 'Simple Sitemap', 10, basename(__FILE__), array(&$this,'options'));	
	}
	
	function options(){
		if(isset($_POST['SimpleGoogleSitemapSaveOptions'])){
			$this->changefreq = $_POST['changefreq'];
			$this->priority = $_POST['priority'];
			$this->setoptions();
			$this->msg = __('Options Saved.', 'simplesitemap');
		}
		if(isset($_POST['SimpleGoogleSitemapCreate'])){
			$this->create();
		}
		
		function setselected($var, $value){
			$r = $var == $value ? ' selected="selected"' : '';
			return $r;
		}
		
		echo '
			<style type="text/css">
			select.sgmoptions option{
				margin-right:4px;
			}
			</style>
		';
		echo '
			<div class="wrap">
				<h2>Simple Google Sitemap V'.$this->version.'</h2>
		';
		if($this->msg != ''){
			echo '
				<div style="border:2px solid #cc0000; padding:8px; font-weight:bold; margin:10px;">'.$this->msg.'</div>
			';
		}
		echo '
				<h3>'.__('Options', 'simplesitemap').'</h3>
				<form method="post" action="">
					<h4>'.__('Change Frequency', 'simplesitemap').'</h4>
					<p>'.__('This option sets the period in which changes are expected for the item.', 'simplesitemap').'</p>
					<div>
						<select class="sgmoptions" name="changefreq[homepage]" id="changefreq_homepage">
							<option value="always"'.setselected($this->changefreq['homepage'], 'always').'>'.__('always', 'simplesitemap').'</option>
							<option value="hourly"'.setselected($this->changefreq['homepage'], 'hourly').'>'.__('hourly', 'simplesitemap').'</option>
							<option value="daily"'.setselected($this->changefreq['homepage'], 'daily').'>'.__('daily', 'simplesitemap').'</option>
							<option value="weekly"'.setselected($this->changefreq['homepage'], 'weekly').'>'.__('weekly', 'simplesitemap').'</option>
							<option value="monthly"'.setselected($this->changefreq['homepage'], 'monthly').'>'.__('monthly', 'simplesitemap').'</option>
							<option value="yearly"'.setselected($this->changefreq['homepage'], 'yearly').'>'.__('yearly', 'simplesitemap').'</option>
							<option value="never"'.setselected($this->changefreq['homepage'], 'never').'>'.__('never', 'simplesitemap').'</option>
						</select>
						<label for="changefreq_homepage">'.__('Homepage', 'simplesitemap').'</label>
					</div>
					<div>
						<select class="sgmoptions" name="changefreq[post]" id="changefreq_post">
							<option value="always"'.setselected($this->changefreq['post'], 'always').'>'.__('always', 'simplesitemap').'</option>
							<option value="hourly"'.setselected($this->changefreq['post'], 'hourly').'>'.__('hourly', 'simplesitemap').'</option>
							<option value="daily"'.setselected($this->changefreq['post'], 'daily').'>'.__('daily', 'simplesitemap').'</option>
							<option value="weekly"'.setselected($this->changefreq['post'], 'weekly').'>'.__('weekly', 'simplesitemap').'</option>
							<option value="monthly"'.setselected($this->changefreq['post'], 'monthly').'>'.__('monthly', 'simplesitemap').'</option>
							<option value="yearly"'.setselected($this->changefreq['post'], 'yearly').'>'.__('yearly', 'simplesitemap').'</option>
							<option value="never"'.setselected($this->changefreq['post'], 'never').'>'.__('never', 'simplesitemap').'</option>
						</select>
						<label for="changefreq_post">'.__('Articles', 'simplesitemap').'</label>
					</div>
					<div>
						<select class="sgmoptions" name="changefreq[page]" id="changefreq_page">
							<option value="always"'.setselected($this->changefreq['page'], 'always').'>'.__('always', 'simplesitemap').'</option>
							<option value="hourly"'.setselected($this->changefreq['page'], 'hourly').'>'.__('hourly', 'simplesitemap').'</option>
							<option value="daily"'.setselected($this->changefreq['page'], 'daily').'>'.__('daily', 'simplesitemap').'</option>
							<option value="weekly"'.setselected($this->changefreq['page'], 'weekly').'>'.__('weekly', 'simplesitemap').'</option>
							<option value="monthly"'.setselected($this->changefreq['page'], 'monthly').'>'.__('monthly', 'simplesitemap').'</option>
							<option value="yearly"'.setselected($this->changefreq['page'], 'yearly').'>'.__('yearly', 'simplesitemap').'</option>
							<option value="never"'.setselected($this->changefreq['page'], 'never').'>'.__('never', 'simplesitemap').'</option>
						</select>
						<label for="changefreq_page">'.__('Pages', 'simplesitemap').'</label>
					</div>
					<h4>'.__('Priority', 'simplesitemap').'</h4>
					<p>'.__('This option sets the priority for the item.', 'simplesitemap').'</p>
					<div>
						<select class="sgmoptions" name="priority[homepage]" id="priority_homepage">
							<option value="1.0"'.setselected($this->priority['homepage'], '1.0').'>'.__('1.0', 'simplesitemap').'</option>
							<option value="0.9"'.setselected($this->priority['homepage'], '0.9').'>'.__('0.9', 'simplesitemap').'</option>
							<option value="0.8"'.setselected($this->priority['homepage'], '0.8').'>'.__('0.8', 'simplesitemap').'</option>
							<option value="0.7"'.setselected($this->priority['homepage'], '0.7').'>'.__('0.7', 'simplesitemap').'</option>
							<option value="0.6"'.setselected($this->priority['homepage'], '0.6').'>'.__('0.6', 'simplesitemap').'</option>
							<option value="0.5"'.setselected($this->priority['homepage'], '0.5').'>'.__('0.5', 'simplesitemap').'</option>
							<option value="0.4"'.setselected($this->priority['homepage'], '0.4').'>'.__('0.4', 'simplesitemap').'</option>
							<option value="0.3"'.setselected($this->priority['homepage'], '0.3').'>'.__('0.3', 'simplesitemap').'</option>
							<option value="0.2"'.setselected($this->priority['homepage'], '0.2').'>'.__('0.2', 'simplesitemap').'</option>
							<option value="0.1"'.setselected($this->priority['homepage'], '0.1').'>'.__('0.1', 'simplesitemap').'</option>
						</select>
						<label for="priority_homepage">'.__('Homepage', 'simplesitemap').'</label>
					</div>
					<div>
						<select class="sgmoptions" name="priority[post]" id="priority_post">
							<option value="1.0"'.setselected($this->priority['post'], '1.0').'>'.__('1.0', 'simplesitemap').'</option>
							<option value="0.9"'.setselected($this->priority['post'], '0.9').'>'.__('0.9', 'simplesitemap').'</option>
							<option value="0.8"'.setselected($this->priority['post'], '0.8').'>'.__('0.8', 'simplesitemap').'</option>
							<option value="0.7"'.setselected($this->priority['post'], '0.7').'>'.__('0.7', 'simplesitemap').'</option>
							<option value="0.6"'.setselected($this->priority['post'], '0.6').'>'.__('0.6', 'simplesitemap').'</option>
							<option value="0.5"'.setselected($this->priority['post'], '0.5').'>'.__('0.5', 'simplesitemap').'</option>
							<option value="0.4"'.setselected($this->priority['post'], '0.4').'>'.__('0.4', 'simplesitemap').'</option>
							<option value="0.3"'.setselected($this->priority['post'], '0.3').'>'.__('0.3', 'simplesitemap').'</option>
							<option value="0.2"'.setselected($this->priority['post'], '0.2').'>'.__('0.2', 'simplesitemap').'</option>
							<option value="0.1"'.setselected($this->priority['post'], '0.1').'>'.__('0.1', 'simplesitemap').'</option>
						</select>
						<label for="priority_post">'.__('Articles', 'simplesitemap').'</label>
					</div>
					<div>
						<select class="sgmoptions" name="priority[page]" id="priority_page">
							<option value="1.0"'.setselected($this->priority['page'], '1.0').'>'.__('1.0', 'simplesitemap').'</option>
							<option value="0.9"'.setselected($this->priority['page'], '0.9').'>'.__('0.9', 'simplesitemap').'</option>
							<option value="0.8"'.setselected($this->priority['page'], '0.8').'>'.__('0.8', 'simplesitemap').'</option>
							<option value="0.7"'.setselected($this->priority['page'], '0.7').'>'.__('0.7', 'simplesitemap').'</option>
							<option value="0.6"'.setselected($this->priority['page'], '0.6').'>'.__('0.6', 'simplesitemap').'</option>
							<option value="0.5"'.setselected($this->priority['page'], '0.5').'>'.__('0.5', 'simplesitemap').'</option>
							<option value="0.4"'.setselected($this->priority['page'], '0.4').'>'.__('0.4', 'simplesitemap').'</option>
							<option value="0.3"'.setselected($this->priority['page'], '0.3').'>'.__('0.3', 'simplesitemap').'</option>
							<option value="0.2"'.setselected($this->priority['page'], '0.2').'>'.__('0.2', 'simplesitemap').'</option>
							<option value="0.1"'.setselected($this->priority['page'], '0.1').'>'.__('0.1', 'simplesitemap').'</option>
						</select>
						<label for="priority_page">'.__('Pages', 'simplesitemap').'</label>
					</div>
					<div class="submit">
						<input type="submit" name="SimpleGoogleSitemapSaveOptions" value="'.__('Save Options', 'simplesitemap').'" />
					</div>
				</form>
			</div>
		';
		echo '
				<h3>'.__('Create Sitemap manually', 'simplesitemap').'</h3>
				<form method="post" action="">
					<div class="submit">
						<input type="submit" name="SimpleGoogleSitemapCreate" value="'.__('Create Sitemap', 'simplesitemap').'" />
					</div>
				</form>
			</div>
		';

	}
	
	function getoptions(){
		$options = get_option('simple_google_sitemap');
		if($options !== false){
			$this->changefreq = $options['changefreq'];
			$this->priority = $options['priority'];
		}else{
			$this->setoptions();
		}
	}
	function setoptions(){
		$options = array(
			'changefreq' => $this->changefreq,
			'priority' => $this->priority
		);
		update_option('simple_google_sitemap', $options);
	}
		
	
	function create(){
		global $wpdb;
		// All articles and pages
		$sql = 'SELECT ID, post_modified_gmt, post_name, post_type FROM '.$wpdb->posts.' WHERE post_password="" AND post_status="publish" AND (post_type="post" OR post_type="page") ORDER BY post_modified_gmt DESC';
		$result = $wpdb->get_results($sql);
		$sites = '';
		foreach($result as $row){
			$loc = get_permalink($row->ID); //$this->siteurl.$row->post_name.'/';
			$lastmod = str_replace(' ', 'T', $row->post_modified_gmt).'+00:00';
			
			if($sites == ''){			
				$sites .= "\t<url>\n";
				$sites .= "\t\t<loc>".$this->siteurl."</loc>\n";
				$sites .= "\t\t<lastmod>".$lastmod."</lastmod>\n";
				$sites .= "\t\t<changefreq>".$this->changefreq['homepage']."</changefreq>\n";
				$sites .= "\t\t<priority>".$this->priority['homepage']."</priority>\n";
				$sites .= "\t</url>\n";		
			}

			$sites .= "\t<url>\n";
			$sites .= "\t\t<loc>".$loc."</loc>\n";
			$sites .= "\t\t<lastmod>".$lastmod."</lastmod>\n";
			$sites .= "\t\t<changefreq>".$this->changefreq[$row->post_type]."</changefreq>\n";
			$sites .= "\t\t<priority>".$this->priority[$row->post_type]."</priority>\n";
			$sites .= "\t</url>\n";
		}

		$sitemapxml = '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="'.$this->xslurl.'"?>
<!-- generator="wordpress/2.7" -->
<!-- sitemap-generator-url="http://suche.pytalhost.de" simple-google-sitemap-version="'.$this->version.'" -->
<!-- generated-on="'.date('d. F Y').'" -->
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.$sites.'</urlset>'."\n";

		// Store sitemap file
		if(function_exists('file_put_contents')){
			file_put_contents($this->sitemappath, $sitemapxml);
		}else{
			$fh = fopen($this->sitemappath, 'w');
			if($fh){
				if(fwrite($fh, $sitemapxml) === false){
					$this->msg = __('ERROR: Could not write sitemap file', 'simplesitemap');
					return;
				}
				fclose($fh);
			}else{
				$this->msg = __('ERROR: Could not open sitemap file to write', 'simplesitemap');
				return;
			}
		}
		$this->msg = sprintf(__('New Sitemap with %d entries created.', 'simplesitemap'), count($result)+1);

		$this->msg .= '<ul style="margin-top:10px;">';
		foreach($this->pingurls as $engine){
			$httpresult = (array)wp_remote_get($engine['url']);
			if(strpos($httpresult['body'], $engine['snippet']) !== false){
				$this->msg .= '<li>'.sprintf(__('%s was pinged at: ', 'simplesitemap'), $engine['service']).'<a href="'.$engine['url'].'">'.$engine['url'].'</a></li>';
			}else{
				$this->msg .= '<li><span style="color:#cc0000">'.sprintf(__('Oops .. %s ping failed at: ', 'simplesitemap').'</span>', $engine['service']).'<a href="'.$engine['url'].'">'.$engine['url'].'</a></li>';			
			}
		}
		$this->msg .= '<ul>';
	}
}
$SimpleGoogleSitemap = new SimpleGoogleSitemap(); 

?>