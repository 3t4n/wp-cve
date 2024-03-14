<?php
/*
Plugin Name: issuuPress
Plugin URI: https://pixeline.be
Description: Displays your Issuu catalog of PDF files in your wordpress posts/pages using a shortcode.
Version: 1.3.2
Author: Alexandre Plennevaux
Author URI: https://pixeline.be
Plugin template by Piers //soderlind.no/archives/2010/03/04/wordpress-plugin-template/
*/

if (!class_exists('ap_issuupress')) {
	class ap_issuupress {
		/**
		 * @var string The options string name for this plugin
		 */
		protected $pluginVersion;
		protected $pluginId;
		protected $pluginPath;
		protected $pluginUrl;

		var $optionsName = 'ap_issuupress_options';
		var $apiKey;
		var $apiSecret;
		var $result_access;
		var $filterByTag;
		var $cacheFolder;
		var $cacheDuration;
		var $issuuCacheFile;
		var $issue_orderby_options;
		var $options = array();
		var $localizationDomain = "ap_issuupress";

		var $url = '';

		var $urlpath = '';

		//Class Functions
		/**
		 * PHP 4 Compatible Constructor
		 */
		function ap_issuupress(){$this->__construct();}

		/**
		 * PHP 5 Constructor
		 */
		function __construct(){
			//Language Setup
			$locale = get_locale();
			$mo = plugin_dir_path(__FILE__) . 'languages/' . $this->localizationDomain . '-' . $locale . '.mo';
			load_textdomain($this->localizationDomain, $mo);

			//"Constants" setup

			$this->url = plugins_url(basename(__FILE__), __FILE__);
			$this->urlpath = plugins_url('', __FILE__);
			//*/
			$this->pluginPath   =  dirname(__FILE__);
			$this->pluginUrl   =  WP_PLUGIN_URL . '/'.basename($this->pluginPath);
			$this->pluginVersion= '1.1.0';
			$this->pluginId = 'issuupress';
			$this->cacheFolder= plugin_dir_path(__FILE__).'cache';
			$this->issuuCacheFile = $this->cacheFolder . '/issuu.json';
			// Issuu gives the following parameters to control the order
			// see: //developers.issuu.com/api/issuu.document.list.html#responseparameters
			$this->issue_orderby_options = array(
				'username' => 'Owner of document',
				'name' => 'Name of document',
				'documentId' => 'Unique identifier of the document',
				'title' => 'Title of the document',
				'access' => '"public" or "private"',
				'state' => 'The state of the document',
				'category' => 'Category to which the content belongs',
				'type' => 'Physical format of publications',
				'orgDocType' => 'Format of original file',
				'orgDocName' => 'The original filename of the uploaded document',
				'origin' => 'The source of the document',
				'language' => 'Language Code for the document',
				'pageCount' => 'The number of pages in the document',
				'publishDate' => 'Timestamp for when this document was published',
				'description' => 'Description of the content',
				'tags' => 'Keywords describing the document',
				'folders' => 'The folders containing this document'
			);
			//Initialize the options
			$this->getOptions();



			//Admin menu
			add_action("admin_menu", array(&$this,"admin_menu_link"));
			// On Settings page load
			//add_action("settings_page_issuupress", array(&$this,"check_issuu_setup"));

			add_shortcode('issuupress', array($this, 'shortcode'));

			add_filter('the_posts', array(&$this,'scripts_and_styles'));
		}


		function listDocs($forceCache=false){

			require_once('issuuAPI.php');

			$args = array(
				'apiKey'=>$this->apiKey,
				'apiSecret'=>$this->apiSecret,
				'cacheDuration'=>$this->cacheDuration,
				'forceCache'=>$forceCache,
				'result_order' => $this->result_order,
				'result_orderby'=> $this->result_orderby,
				'result_access'=>$this->result_access
			);

			$issuuAPI = new issuuAPI($args);
			return $issuuAPI->getListing();
		}


		/**
		 * @desc Checks for issues in the Setup
		 * @return array
		 */
		function check_issuu_setup(){
			// Is cache file writable ?
			$system_error_class = 'updated';
			if(is_file($this->issuuCacheFile))
			{
				chmod($this->issuuCacheFile, 0777);
				$last_modified_cache =date("d F Y H:i:s", filemtime($this->issuuCacheFile));
			}
			if(!is_writable($this->issuuCacheFile)){
				chmod($this->issuuCacheFile, '0777');
				$system_error_class = 'error';
				$check_message = _("The issuupress cache file is not writable. You may need to manually Chmod it 0777.");
				$check_message.= _(' You will find it here:');
				$check_message .= '<code>'.$this->issuuCacheFile.'</code>';

				$check_message = '<p>'.$check_message.'</p>';
			}else{
				$check_message = '<p>Good, your cache file is writable and was last modified on <strong>'.$last_modified_cache.'</strong>.</p>';
				$check_message .= '<small><code style="font-size:90%">'.$this->issuuCacheFile.'</code> <a href="'.$this->pluginUrl.'/cache/issuu.json" target="_blank">view</a></small>';
			}

			$debug_info = ini_get_all('core',false);

			echo '<div id="message" class="'.$system_error_class.'" style="padding:.5em 1em"><h4>System check</h4>'.$check_message;
			echo '<fieldset><legend>Debug info:</legend>';
			echo '<textarea style="width:100%; min-height:10em;font-size:90%">'.print_r($debug_info,true).'</textarea>';
			echo '</div>';
		}
		/**
		 * @desc Retrieves the plugin options from the database.
		 * @return array
		 */
		function getOptions() {
			$theOptions = array(
				'ap_issuupress_apikey'=> '',
				'ap_issuupress_apisecret' => '',
				'ap_issuupress_cacheDuration'=>86400,
				'no_pdf_message'=>'No PDF available, sorry!',
				'result_orderby' => 'publishDate',
				'result_order'=>'desc',
				'result_access'=>'public'
			);
			$storedOptions = get_option($this->optionsName);
			if(!is_array($storedOptions)){
				// this happens on first installation.
				$storedOptions = $theOptions;
			}

			if (is_array($storedOptions) && count($storedOptions)!=count($theOptions)) {
				// Update the options upon plugin updating. Useful if new options have been introduced.
				$storedOptions=  array_merge($theOptions,$storedOptions);
				update_option($this->optionsName,$storedOptions);
			}
			$this->options = $storedOptions;
			$this->apiKey = $this->options['ap_issuupress_apikey'];
			$this->apiSecret = $this->options['ap_issuupress_apisecret'];
			$this->cacheDuration = $this->options['ap_issuupress_cacheDuration'];
			$this->no_pdf_message = $this->options['no_pdf_message'];
			$this->result_order = $this->options['result_order'];
			$this->result_orderby = $this->options['result_orderby'];		
			$this->result_access = $this->options['result_access'];		
		}


		function shortcode($atts){
			ob_start();
			if(!is_admin()){

				extract(shortcode_atts(array('folder'=>'', 'viewer'=>'mini','vmode'=>'','titlebar'=>'false','img'=>'false','height'=>'240', 'bgcolor'=>'FFFFFF','ctitle'=>'Pick a PDF file to read'), $atts));

				$this->filterByTag = $tag;

				$docs = $this->listDocs(false);

				if(is_array($docs) && isset($docs['error'])){
					echo '<div class="error">'._("Issuu could not be reached, sorry");
					if(current_user_can('edit_theme_options')){
						echo '<br><br>'._("Admin: you may need to manually chmod 777 the issuupress cache file: ").'<code>'.$issuuAPI->issuuCacheFile.'</code>';
					}
					echo '</div>';
				} else{
					if(isset($_GET['documentId']) && $_GET['documentId'] != '') {
						$docId = $_GET['documentId'];
						$docTitle= $_GET['issuutitle'];
					}else{

						if(count($docs->_content)>0){
							if($this->filterByTag !=''){
								foreach($docs->_content as $d){
									if(in_array($this->filterByTag, $d->document->tags)){
										$docId =  $d->document->documentId;
										$docTitle =  $d->document->title;
										break;
									}
								}
							}else{
								$docId = $docs->_content[0]->document->documentId;
								$docTitle = $docs->_content[0]->document->title;
							}
						}

					}
					$output = '<div id="issuupress">';

					if(count($docs->_content)>0){

						// display viewer, send it options in array

						if($viewer!=='no'){

							$output .= $this->issuuViewer(array('documentId'=> $docId, 'folder'=>$folder, 'viewer'=>$viewer, 'title'=>$docTitle, 'height'=>$height, 'bgcolor'=>$bgcolor, 'titlebar'=>$titlebar, 'vmode'=>$vmode ));
						}


						// loop through the issuus files and display them.
						$output .= '<h3>'.$ctitle.'</h3>';
						$output .='<ol class="issuu-list">';
						$count = 0;

						foreach($docs->_content as $d){

							$isInTags = (isset($d->document->tags) && is_array($d->document->tags) && in_array($this->filterByTag, $d->document->tags));
							$wantItAll = (trim($this->filterByTag)==='');

							if($isInTags || $wantItAll){
								//$output.=  "want it all = $wantItAll & isInTags=$isInTags";
								//$output .= "tags =" .print_r($d->document->tags,true);

								$count++;
								$issuu_link = 'https://issuu.com/'.$d->document->username.'/docs/'.$d->document->name.'#download';
								$dId = $d->document->documentId;
								$doc_link = esc_url(add_query_arg( 'documentId', $dId, get_permalink() ));
								$doc_link = esc_url(add_query_arg( 'issuutitle', $d->document->title, $doc_link));
								$doc_link.='#issuupress';
								$selected = ($dId == $docId) ? 'class="issuu-selected"':'';
								$link_target = '';
								if($viewer==='no'){
									$doc_link = $issuu_link;
									$link_target= 'target="_blank"';
								}
								if($img !='false'){
									$output .= '<li '.$selected.'><a class="issuu-view" href="'.$doc_link.'" '.$link_target.'><img src="//image.issuu.com/'.$dId.'/jpg/page_1_thumb_medium.jpg" width="'.$img.'">'.$d->document->title.'</a><small>'.$this->formatIssuuDate($d->document->publishDate).'</small></li>';
								}else
								{
									$output.= '<li '.$selected.'><a class="issuu-view" href="'.$doc_link.'" '.$link_target.'>'.$d->document->title.'<small>'.$this->formatIssuuDate($d->document->publishDate).'</small></a> </li>';

								}
							}
						}
						$output.= ($count<1)? '<p class="issuupress-no-pdf-message">'.$this->filterByTag.' '.$this->no_pdf_message.'</p>': '';


						$output.='</ol>
			</div>';

						echo $output;
					}
					else{
						// No Documents in the json file.
						echo '<div id="issuupress">'._("No document found.").'</div>';

					}
				}



			}
			$output_string = ob_get_contents();

			ob_end_clean();

			return $output_string;

		}
		private function formatIssuuDate($date){
			return date('d M Y',strtotime($date));
		}

		private function issuuViewer($args){
			$options['documentId']= $args['documentId'];
			$options['bgcolor']=$args['bgcolor'];
			$options['mode']= $args['viewer']; // 'mini', 'Presentation' or 'window'
			$options['height']=$args['height'];
			$options['title']= $args['title'];
			$options['titlebar']= $args['titlebar'];
			$options['vmode']= ($args['vmode']=='single') ? 'singlePage':'';
			$output= '<h3>'.$options['title'].'</h3>
			<div id="issuuViewer">
				<object style="width:100%;height:'.$options['height'].'px" >
				<param name="movie" value="//static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf?mode='.$options['mode'].'&amp;backgroundColor=%23'.$options['bgcolor'].'&amp;viewMode='.$options['vmode'].'&amp;embedBackground=%23'.$options['bgcolor'].'&amp;titleBarEnabled='.$options['titlebar'].'&amp;documentId='.$options['documentId'].'" />
				<param name="allowfullscreen" value="true"/>
				<param name="menu" value="false"/>
				<param name="wmode" value="transparent"/>
				<embed src="//static.issuu.com/webembed/viewers/style1/v2/IssuuReader.swf" type="application/x-shockwave-flash" allowfullscreen="true" menu="false" wmode="transparent" style="width:100%;height:'.$options['height'].'px" flashvars="mode='.$options['mode'].'&amp;backgroundColor=%23'.$options['bgcolor'].'&amp;viewMode='.$options['vmode'].'&amp;embedBackground=%23'.$options['bgcolor'].'&amp;documentId='.$options['documentId'].'&amp;titleBarEnabled='.$options['titlebar'].'" />
				</object>
				</div>';


			return $output;

		}


		// ADD JS and CSS IN FRONTEND WHEN RELEVANT

		function scripts_and_styles($posts){
			if (empty($posts)) return $posts;
			$shortcode_found = false;

			foreach ($posts as $post) {
				if (stripos($post->post_content, '[issuupress') !== false) {
					$shortcode_found = true; // bingo!
					break;
				}
			}

			if ($shortcode_found) {
				// enqueue here
				if(!is_admin()){
					$pth_plugin_url = plugin_dir_url(__FILE__);
					wp_enqueue_style('pixeline_issuupress', $this->pluginUrl.'/'.$this->pluginId.'-frontend.css');

				}
			}

			return $posts;
		}


		/*

		ADMIN STUFF HEREBELOW

		*/



		/**
		 * Saves the admin options to the database.
		 */
		function saveAdminOptions(){
			return update_option($this->optionsName, $this->options);
		}

		/**
		 * @desc Adds the options subpanel
		 */
		function admin_menu_link() {
			add_options_page('issuuPress', 'issuuPress', 'activate_plugins', basename(__FILE__), array(&$this,'admin_options_page'));
			add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'filter_plugin_actions'), 10, 2 );
		}

		/**
		 * @desc Adds the Settings link to the plugin activate/deactivate page
		 */
		function filter_plugin_actions($links, $file) {
			$settings_link = '<a href="options-general.php?page=' . basename(__FILE__) . '">' . __('Settings') . '</a>';
			array_unshift( $links, $settings_link ); // before other links

			return $links;
		}

		/**
		 * Adds settings/options page
		 */
		function admin_options_page() {

			if($_POST['ap_issuupress_save']){
				if (! wp_verify_nonce($_POST['_wpnonce'], 'ap_issuupress-update-options') ) die('Whoops! There was a problem with the data you posted. Please go back and try again.');
				$this->options['ap_issuupress_apikey'] = sanitize_text_field($_POST['ap_issuupress_apikey']);
				$this->options['ap_issuupress_apisecret'] =sanitize_text_field( $_POST['ap_issuupress_apisecret']);
				$this->options['no_pdf_message'] = sanitize_text_field($_POST['no_pdf_message']);
				$this->options['ap_issuupress_cacheDuration'] = (int)$_POST['ap_issuupress_cacheDuration'];
				$this->options['result_orderby'] = sanitize_text_field($_POST['result_orderby']);
				$this->options['result_order'] = sanitize_text_field($_POST['result_order']);
				$this->options['result_access'] = sanitize_text_field($_POST['result_access']);
				
				if($_POST['ap_issuupress_refresh_now']==='1'){

					// Delete cache file first.
					if (file_exists($this->issuuCacheFile)) {
						unlink($this->issuuCacheFile);
					}

					$docs = $this->listDocs(true);
					if(is_array($docs) && isset($docs['error'])){
						$refresh_mess = '<div class="updated"><p>'._('Error! Could not refresh the cache file : '). $docs['error']. '</p><p>(your cache file is here: '.$this->issuuCacheFile.')</p></div>' ;
					} else{
						$refresh_mess = '<div class="updated"><p>'._('Success! Cache refreshed.').'</p></div>';
					}

				}
				$this->saveAdminOptions();
				echo (empty($refresh_mess)) ? '<div class="updated"><p>'._('Success! Your changes were sucessfully saved.').'</p></div>': $refresh_mess;
			}
?>
			<div class="wrap">
			<h1><?php _e('IssuuPress Settings', $this->localizationDomain);?></h1>
			<p><?php _e('by <a href="//www.pixeline.be" target="_blank" class="external">pixeline</a>', $this->localizationDomain); ?></p>
			<p style="font-weight:bold;"><?php _e('If you like this plugin, please <a href="//wordpress.org/extend/plugins/issuupress/" target="_blank">give it a good rating</a> on the Wordpress Plugins repository, and if you make any money out of it, <a title="Paypal donation page" target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=J9X5B6JUVPBHN&lc=US&item_name=pixeline%20%2d%20Wordpress%20plugin&currency_code=EUR&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHostedGuest">send a few coins over to me</a>!', $this->localizationDomain); ?></p>

			<h2 style="border-top:1px solid #999;padding-top:1em;"><?php _e('Settings', $this->localizationDomain);?></h2>
<p><a href="options-general.php?page=issuupress.php&debug"><?php _e('Show Debug information', $this->localizationDomain); ?></a></p>
			<?php if(isset($_GET['debug'])){ $this->check_issuu_setup();}?>

			<?php
			if(!is_file($this->issuuCacheFile)){
				touch($this->issuuCacheFile);
			}?>

			<p><?php _e('In order to fetch the list of your documents from your Issuu account, you need to provide your API credentials. Get them <a href="//issuu.com/services/api/" target="_blank">here</a>.', $this->localizationDomain); ?>
			</p>
			<form method="post" id="ap_issuupress_options">
			<?php wp_nonce_field('ap_issuupress-update-options'); ?>
				<table width="100%" cellspacing="2" cellpadding="5" class="form-table">
					<tr valign="top">
						<th width="33%" scope="row"><?php _e('Your Issuu Api key:', $this->localizationDomain); ?></th>
						<td>
							<input name="ap_issuupress_apikey" type="text" id="ap_issuupress_apikey" size="45" value="<?php echo $this->options['ap_issuupress_apikey'] ;?>"/>

						</td>
					</tr>
					<tr valign="top">
						<th width="33%" scope="row"><?php _e('Your Issuu Api secret:', $this->localizationDomain); ?></th>
						<td>
							<input name="ap_issuupress_apisecret" type="text" id="ap_issuupress_apisecret" size="45" value="<?php echo $this->options['ap_issuupress_apisecret'] ;?>"/>

						</td>
					</tr>

					<tr valign="top">
						<th width="33%" scope="row"><?php _e('Message to display when no PDF file is returned:', $this->localizationDomain); ?></th>
						<td>
							<input name="no_pdf_message" type="text" id="no_pdf_message" size="45" value="<?php echo $this->options['no_pdf_message'] ;?>"/>

						</td>
					</tr>

					<tr valign="top">
						<th width="33%" scope="row"><?php _e('Refresh cache every (in seconds):', $this->localizationDomain); ?></th>
						<td>
							<input name="ap_issuupress_cacheDuration" type="text" id="ap_issuupress_cacheDuration" size="12" value="<?php echo $this->options['ap_issuupress_cacheDuration'] ;?>"/>
							<br><small><?php _e('Tip: 1 day = 86400 sec. , 1 hour = 3600 sec.', $this->localizationDomain); ?></small>
						</td>
					</tr>


					<tr valign="top">
						<th width="33%" scope="row"><?php _e('Display order by:', $this->localizationDomain); ?>
						<br>
							<small style="font-weight: 100">Default: <code>publishDate</code></small></th>
						<td>
							<select onchange="document.getElementById('ap_issuupress_refresh_now').checked = true;" name="result_orderby" id="result_orderby">
								<option value=""><?php _e('Select:', $this->localizationDomain); ?></option>
								<?php
			foreach ($this->issue_orderby_options as $k=>$v){

				$selected = ($this->options['result_orderby'] == $k) ? 'selected': '';
				echo '<option value="'.$k.'" '.$selected.'>'.$k.' : '.$v.'</option>';

			}
?>
							</select>

						</td>
					</tr>

					<tr valign="top">
						<th width="33%" scope="row"><?php _e('Order:', $this->localizationDomain); ?>
						<br>
							<small style="font-weight: 100">Default: <code>DESC</code></small></th>
						<td>
<label><input type="radio" name="result_order" <?php echo ($this->options['result_order']==='desc')? 'checked':'';?> value="desc" onchange="document.getElementById('ap_issuupress_refresh_now').checked = true;"><?php _e('DESC (descending order)', $this->localizationDomain); ?></label>
<label><input type="radio" name="result_order" <?php echo ($this->options['result_order']==='asc')? 'checked':'';?> value="asc" onchange="document.getElementById('ap_issuupress_refresh_now').checked = true;"><?php _e('ASC (ascending order)', $this->localizationDomain); ?></label>

						</td>
					</tr>



					<tr valign="top">
						<th width="33%" scope="row"><?php _e('Which documents would you like to display?', $this->localizationDomain); ?>
						<br>
							<small style="font-weight: 100">Default: <code>public only</code></small></th>
						<td>
<label><input type="radio" name="result_access" <?php echo ($this->options['result_access']==='both')? 'checked':'';?> value="both" onchange="document.getElementById('ap_issuupress_refresh_now').checked = true;"><?php _e('Both public and private documents', $this->localizationDomain); ?></label>
<label><input type="radio" name="result_access" <?php echo ($this->options['result_access']==='private')? 'checked':'';?> value="private" onchange="document.getElementById('ap_issuupress_refresh_now').checked = true;"><?php _e('Only private documents', $this->localizationDomain); ?></label>
<label><input type="radio" name="result_access" <?php echo ($this->options['result_access']==='public')? 'checked':'';?> value="public" onchange="document.getElementById('ap_issuupress_refresh_now').checked = true;"><?php _e('Only public documents', $this->localizationDomain); ?></label>

						</td>
					</tr>

<tr valign="top">
						<th width="33%" scope="row"><?php _e('Refresh the cache now? ', $this->localizationDomain); ?></th>
						<td>
							<label>
							<input name="ap_issuupress_refresh_now" type="checkbox" id="ap_issuupress_refresh_now" value="1"/>
							<br><small><?php _e('Check this option to download a fresh copy of your Issuu catalog.', $this->localizationDomain); ?></small></label>
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" name="ap_issuupress_save" class="button-primary" value="<?php _e('Save Changes', $this->localizationDomain); ?>" />
				</p>
			</form>
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_donations" />
<input type="hidden" name="business" value="J9X5B6JUVPBHN" />
<input type="hidden" name="lc" value="US" />
<input type="hidden" name="item_name" value="pixeline - Wordpress plugin: Issuupress" />
<input type="hidden" name="currency_code" value="EUR" />
<input type="hidden" name="bn" value="PP-DonationsBF:btn_donate_SM.gif:NonHostedGuest" />
<input type="image" name="submit" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" alt="PayPal - The safer, easier way to pay online!" />
<img src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" alt="" width="1" height="1" border="0" /></form>
<div class="issuupress-block">
<h2 style="border-top:1px solid #999;padding-top:1em;">Usage: Shortcode</h2>
<h3>Example</h3>
<code>[issuupress viewer="mini" titlebar="false" vmode="" ctitle="Pick a PDF file to read" height="240" bgcolor="FFFFFF"]</code>
<h3>Options</h3>
<ul>
<!-- 	<li><strong>tag=""</strong> :  If you want, you can restrict the list to only pdf with the provided tag. <em>Default: "".</em></li> -->
	<li><strong>viewer="mini"</strong> : Possible values: "no","mini","presentation" or "window". <em>Default: "mini".</em></li>
	<li><strong>titlebar="false"</strong> : Displays the PDF's titlebar. Possible values: "true", "false". <em>Default: "false".</em></li>
	<li><strong>vmode=""</strong> : Displays pages next to each other, or underneath each other ("single"). Possible values: "single", "". <em>Default: "".</em></li>
	<li><strong>ctitle=""</strong> : Title to print on top of the list of pdf files. <em>Default: "Pick a PDF file to read"</em></li>
	<li><strong>height="240"</strong> : Controls the viewer 's height dimension. In pixels. <em>Default: "240".</em></li>
	<li><strong>bgcolor="FFFFFF"</strong> : Controls the viewer background color. In hexadecimal. <em>Default :"FFFFFF".</em></li>
	<li><strong>img="120"</strong> : Set this to a number will display the thumbnail of each pdf at the provided width (ex: img="120" will display the thumbnail at the width of 120px). <em>Default :"false".</em></li>
</ul>

</div>
			<?php
		}
	} //End Class
} //End if class exists statement



if (isset($_GET['ap_issuupress_javascript'])) {
	//embed javascript
	header("content-type: application/x-javascript");
	echo<<<ENDJS
/**
* @desc issuuPress
* @author Alexandre Plennevaux - //www.pixeline.be
*/

jQuery(document).ready(function(){
	// add your jquery code here


	//validate plugin option form
  	jQuery("#ap_issuupress_options").validate({
		rules: {
			ap_issuupress_apikey: {
				required: true
			},
			ap_issuupress_cacheDuration:{
			required: true,
			min: 60,
			number: true
			}
		},
		messages: {
			ap_issuupress_apikey: {
				// the ap_issuupress_lang object is define using wp_localize_script() in function ap_issuupress_script()
				required: ap_issuupress_lang.required,
				number: ap_issuupress_lang.number,
				min: ap_issuupress_lang.min
			}
		}
	});
});

ENDJS;

} else {
	if (class_exists('ap_issuupress')) {
		$ap_issuupress_var = new ap_issuupress();
	}
}