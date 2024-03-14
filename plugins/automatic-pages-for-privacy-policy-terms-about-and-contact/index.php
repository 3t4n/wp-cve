<?php
/*
 * Plugin Name:		Automatic pages for Privacy Policy, Terms, About, Contact us
 * Description:		Just install the plugin and it creates 1 parent page "info" and 4 sub-pages "Privacy Policy", "Terms and conditions", "Contact us", About us". Then automatically creates a menu-links for them and appends it in footer. You can set that on either Multisite-Wide or per single site.
 * Text Domain:		automatic-text-generator-for-important-pages
 * Domain Path:		/languages
 * Version:		1.42
 * WordPress URI:	https://wordpress.org/plugins/automatic-text-generator-for-important-pages/
 * Plugin URI:		https://puvox.software/software/wordpress-plugins/?plugin=automatic-text-generator-for-important-pages
 * Contributors: 	puvoxsoftware,ttodua
 * Author:		Puvox.software
 * Author URI:		https://puvox.software/
 * Donate Link:		https://paypal.me/Puvox
 * License:		GPL-3.0
 * License URI:		https://www.gnu.org/licenses/gpl-3.0.html
 
 * @copyright:		Puvox.software
*/


namespace AutomaticTextGeneratorForImportantPages
{
  if (!defined('ABSPATH')) exit;
  require_once( __DIR__."/library.php" );
  require_once( __DIR__."/library_wp.php" );
  
  class PluginClass extends \Puvox\wp_plugin
  {

	public function declare_settings()
	{
		$this->initial_static_options	=
		[
			'has_pro_version'        => 0, 
            'show_opts'              => true, 
            'show_rating_message'    => true, 
            'show_donation_footer'   => true, 
            'show_donation_popup'    => true, 
            'menu_pages'             => [
                'first' =>[
                    'title'           => 'Automatic info pages', 
                    'default_managed' => 'network',            // network | singlesite
                    'required_role'   => 'install_plugins',
                    'level'           => 'submenu', 
                    'page_title'      => 'Automatic pages for Privacy Policy, Terms, About, Contact us',
                    'tabs'            => [],
                ],
            ]
		];
		
		$this->initial_user_options		= 
		[	
			'use_parent_type'	=>'with_parent_page', 	//"with_parent_page", "without_parent_page", "clear_content"
			'use_nofollow'		=>true,
			'use_caching'		=>1440,
			'menu_in_footer'	=>true,
			'organization_name'	=>sanitize_text_field($_SERVER['HTTP_HOST']), 
			'domain_name'		=>sanitize_text_field($_SERVER['HTTP_HOST']),
			'last_update_time'	=>date('Y-m-d'),
			'page_titles'		=>$this->page_titles, 
			'random_string'		=>$this->helpers->randomString(),
			//
			'show_privacy_note'	=>true,
			'privacy_message'	=>'This website stores data such as cookies to enable necessary site functionality, including analytics, targeting, and personalization. By remaining on this website you indicate your consent <a href="'.$this->linkToPrivacyVariable.'" target="_blank">Cookie Policy</a>',
			'privacy_agree_button_chars'=>'x',
		]; 
	}

	public $linkToPrivacyVariable= '%LINK_TO_PRIVACY%';
	
	public $shortcode_name1= 'atgfip';
	public $shortcode_name2= 'atgfip_example_contact_form';
	public $shortcode_name3= 'atgfip_menu'; 
	
	private $basepage_info	= [ 'title'=>"Info", 'slug'=>"info"];
	private $page_titles	= ["privacy-policy"=>"Privacy Policy", "terms"=> "Terms Of Use", "about"=>"About Us", "contact"=>"Contact"];
	
	private function url($slug='privacy-policy') { 
		$page = get_page_by_path($this->baseSlug.'/'.$slug); 
		if ($page){
			return get_permalink($page);
		}
		return home_url();
	}
	
	public function __construct_my()
	{
		$this->baseSlug 		= $this->basepage_info['slug'];
		$this->basePath			= $this->baseSlug .'/';
		$this->pages_baselink	= ( $this->network_managed_is_selected ? network_site_url() : home_url() ) . '/?'. $this->baseSlug .'=' ;
		$this->shortcodes		= [
			$this->shortcode_name1 =>[
				'description'=>__('These shortcodes are used to be replaced with actual contents. You won\'t need to manually implement them', 'automatic-text-generator-for-important-pages'),
				'atts'=>[ 
					['page',		'',  	__('Either <code>info</code>,<code>privacy</code>,<code>terms</code>,<code>about</code>,<code>contact</code>,', 'automatic-text-generator-for-important-pages') ],
				]
			] ,
			$this->shortcode_name2 =>[
				'description'=>__('Very simple contact form', 'automatic-text-generator-for-important-pages'),
				'atts'=>[ 
					['recipient',		get_site_option('admin_email'),  	__('[OPTIONAL] By default, it\'s set to deliver submited forms to the admin email. However, you can change the recipient', 'automatic-text-generator-for-important-pages') ]
				]
			] ,
			$this->shortcode_name3 =>[
				'description'=>__('Outputs links to those pages', 'automatic-text-generator-for-important-pages'),
				'atts'=>[ 
					['cache',		'1440', __('[OPTIONAL] how many minutes the menu should be cached', 'automatic-text-generator-for-important-pages') ],
					['exlude',		'',  	__('[OPTIONAL] you can put comma-separated list of slugs to exlude from menu (i.e. "contact") ', 'automatic-text-generator-for-important-pages') ]
				]
			] 
		];

		//add_action('plugins_loaded', [$this,'output_page_contents']);

		// navigation menus
		$this->nav_menus_init();
		
		// show cookie notice
		if($this->opts['show_privacy_note'])
		{
			$this->cookieNoticeSlug = 'atgfip_cookieconsent_status';
			if ( ! isset( $_COOKIE[$this->cookieNoticeSlug] ) )
			{
				if ( !is_admin() )
				{
					add_action('wp_head',  [$this, "ShowPopupNotice_head"] );
					add_action('wp_footer',  [$this, "ShowPopupNotice_footer"] );
				}
			}
		}
	}

	// ============================================================================================================== //
	// ============================================================================================================== //


	// shortcodes
	public function atgfip($atts, $contens=false)
	{
		$args = $this->helpers->shortcode_atts($this->shortcode_name1, $this->shortcodes[$this->shortcode_name1]['atts'], $atts);
		$out = '';

		if( $args['page']=="info"){

			$out .= '<div class="info-childs">';
			$existing_page = get_page_by_path($this->basepage_info['slug']);
			$query['child_of']	= $existing_page->ID;
			$query['depth']		= 0;
			$query['echo']		= 0;
			$query['title_li']	= "";
			$out .= wp_list_pages($query);
			$out .='</div>';
		}
		elseif (in_array($args['page'], array_keys($this->page_titles)))
		{
			$out .= '<div class="infopage">';
			$out .= apply_filters('the_content', $this->get_default_globaltext($args['page']) );
			$out .='</div>';
		}
		return $out ;
	}
 

	// get default texts

	public function get_default_filetext($slug)
	{
		$file= apply_filters('atgfip_default_text', __DIR__.'/default-texts/'.basename(sanitize_key($slug)).'.php', $slug);
		$out = file_exists($file) ? file_get_contents($file) : __("text not found", 'automatic-text-generator-for-important-pages');
		return $out;
	}

	public function get_default_text($slug){
		$out  = '<div class="atgfip_text">'; 
		$out .= str_replace(
			['__LAST_UPDATE_TIME_AUTOMATIC__', '__ORGANIZATION_NAME_AUTOMATIC__', '__DOMAIN_NAME_AUTOMATIC__'], 
			[ $this->opts['last_update_time'], $this->opts['organization_name'], $this->opts['domain_name'] ], 
			$this->get_default_filetext($slug) );
		$out .= '</div>';
		return apply_filters('atgfip_parsed_text', $out, $slug, $this->opts['last_update_time']);
	}	

	public function get_default_globaltext($slug, $filtered=false)
	{
		$text = get_site_option('atgfip_text_'.$slug,  $this->get_default_text($slug) ); 
		return $filtered ? apply_filters("the_content", $text) : $text;
	}


	// create them on request
	public function create_pages_initial()
	{
		//$use_create_in_subpages_method =false;
		if ( $this->network_managed_is_selected) 
		{
			switch_to_blog(1);
			$this->create_pages();
			restore_current_blog();
			
			foreach (get_sites() as $blog)
			{
				switch_to_blog($blog->blog_id);
				$this->create_menus($blog);
				restore_current_blog();
			}  
		}
		//if($this->mainSite())
		else
		{
			$this->create_pages();
			$this->create_menus(); 
		}
	}
 

	

	#region ============================  MENUS ============================
	public function nav_menus_init()
	{
	//	register_nav_menus(array(
	//		'atgfip_menu_location1'   =>'Menu items for "Auto-Pages"'
	//	)); 
		if ($this->opts['menu_in_footer']) {
			add_action('wp_head', [$this,'show_footer_styles']); 		
			add_action('wp_footer', [$this,'show_footer_menu']); 		
		}
		
	}
	
	public function create_menus($blog=false)
	{
		// === create menu ===
		if ( $this->opts['menu_in_footer'])
		{
			if ( $this->get_option('`menu_already_created', false) ) return;
			$this->update_option('`menu_already_created', true);
			$menu_name='atgfip_menu';
			$menu = wp_get_nav_menu_object($menu_name);
			if( ! $menu )	$menu_id = wp_create_nav_menu($menu_name); 
			else			$menu_id = $menu->term_id; 

			foreach($this->page_titles as $slug=>$title)
			{
				wp_update_nav_menu_item($menu_id, 0, [
					'menu-item-title' =>  __($title),
					'menu-item-url' => get_permalink( get_page_by_path( $this->basePath . $slug ) ), 
					'menu-item-status' => 'publish'
				]);
			}
		}

	}
  
	public function show_footer_styles()
	{
		$out = '<style> .atgfip_menu {display:flex; flex-direction:row; justify-content: center; background: #e7e7e7; margin:0; z-index:1; } .atgfip_menu * { z-index:1; } .atgfip_menu li {list-style:none; margin:0 2px; border: 1px solid #cacaca; border-width:0 1px 0 0; padding: 2px 5px; } .atgfip_menu li:last-child { border:0px; } .atgfip_menu a { background: #e7e7e705; border-radius: 6px; } </style>';
		echo $out;
	}
	
	public function menu_output($args=false)
	{
		$out = '';

		$key= $this->helpers->prefix_.'temp_menu_'.md5( json_encode($args) )  ;
		if ( !$this->opts['use_caching'] || ($out = $this->get_transient_CHOSEN("`$key")) === false )
		{
				if ($this->network_managed_is_selected) switch_to_blog(1); //main site
			$out .= wp_nav_menu( ['theme_location'=>'',  'menu'=> 'atgfip_menu', 'menu_class'=>'atgfip_menu _standard', 'echo'=>0]);
				if ($this->network_managed_is_selected) restore_current_blog();
			$this->update_transient_CHOSEN("`$key", $out, $this->opts['use_caching'] );
		}
		return '<div id="atgfip_menu_parent">'.$out.'</div>';
	}

		
	public function atgfip_menu($atts, $contens=false)
	{
		$args = $this->helpers->shortcode_atts( $this->shortcode_name3, $this->shortcodes[$this->shortcode_name3]['atts'], $atts);
		//
		return $this->menu_output($args);
	}

	public function show_footer_menu()
	{
		$out = do_shortcode('[atgfip_menu]');
		echo $out;
	}
	#endregion // MENUS //









	#region ============================ PAGES ============================
	public function create_pages($blog=false)
	{
		if ( $this->get_option('`pages_already_created', false) ) return;
		$this->update_option('`pages_already_created', true);

		// INFO (parent)
		$info_slug	 = $this->baseSlug;
		$info_content= 'Welcome to Info-Page. This page doesn\'t contain much information itself. You may find more information on these pages: [atgfip page="'.$info_slug.'"]'; 
		$existing_page = get_page_by_path($info_slug); 
		if(!$existing_page)
		{
			$my_post = 
			[
				'post_title'	=> $this->basepage_info['title'],
				'post_content'	=> $info_content,
				'post_name'		=> $info_slug,
				'post_status'	=> 'publish',
				'post_type'		=> 'page'
			];

			$infopage_id = wp_insert_post( $my_post );
		}
		else
		{
			$infopage_id = $existing_page->ID;
			if ( stripos($existing_page->post_content, $info_content) === false ){
				// probably add the content in the bottom of the existing content?
			}
		}

		// Children
		foreach ($this->page_titles as $slug=>$title) 
		{
			$page = get_page_by_path($info_slug.'/'.$slug);

			if(!$page)
			{
				$my_post =
				[
					'post_title'	=> $title,
					'post_content'	=> '[atgfip page="'.$slug.'"]',
					'post_name'		=> $slug,
					'post_status'	=> 'publish',
					'post_type'		=> 'page',
					'post_parent'	=> $infopage_id
				];

				$current_id = wp_insert_post( $my_post );
			}
			else{
				echo '<h3 style="color:red;">'. $slug. ' already existed</h3>';
			}
		}
		return true;
	} 
	#endregion //PAGES//

	





















	//=======================================================

	
	public function atgfip_example_contact_form($atts, $contens=false)
	{
		$args			= $this->helpers->shortcode_atts( $this->shortcode_name2, $this->shortcodes[$this->shortcode_name2]['atts'], $atts);
		$out			= '';
		//
		$domain 		= $_SERVER['HTTP_HOST'];
		$rand 			= md5( __DIR__ . date('d') . substr(NONCE_SALT, -7) . sanitize_key($domain).$this->opts['random_string']  ); 
		$antispam_key	= md5( $rand . 'key');
		$antispam_value	= md5( $rand . 'value');

		// pre-filled values
		$vars = !empty( $_POST ) ? array_map('sanitize_text_field', $_POST) : ['atgfip_email'=>'sample@example.com', 'atgfip_subject'=>'',   'atgfip_text'=>'',  ];
		
		$error='';
		if ( ! empty( $vars[$antispam_key] ) )
		{
			if ( empty($vars['atgfip_email']) ||  empty($vars['atgfip_subject']) || empty($vars['atgfip_text']) )
			{
				$error =$this->phrase("Error: Please fill the required fields");
			}
			else if (!wp_verify_nonce($_POST['atgfip_nonce'],'atgfip_formnonce'))
			{
				$error =$this->phrase("Error: AntiSpam check was not selected");
			}
			else
			{
				$recipient	= !empty($args['recipient']) ? $args['recipient'] : get_site_option('admin_email');
				$subject	= '('. $domain . ') '.sanitize_text_field($vars['atgfip_subject']);
					$from		= sanitize_email($vars['atgfip_email']);
				$message	="FROM: ". $from. "\r\n\r\n Message:\r\n" . $vars['atgfip_text'];
				$headers	='MIME-Version: 1.0' . "\r\n" . 'Content-type: text/html; charset=UTF-8' . "\r\n" . 'From: sender@' .$domain ."\r\n".'Reply-To: '.$from. "\r\n" . "X-Mailer: PHP/" . phpversion(); 
				$send_result = wp_mail( $recipient, $subject, $message, $headers);
				$error = $send_result ? $this->phrase('Email sent successfully') : $this->phrase('Error: server was unable to send email')  ;
				if ($send_result) $_POST = null; 
			}
			echo '<div class="atgfip_notification '.(stripos($error,'error')!==false ? 'red' : 'green').'"><h1>'.$error.'</h1></div>';
		}	

		if ( !isset($send_result) || !$send_result )
		{
			$out .= 
			'<div id="atgfip_contac_DIV">
				<style type="text/css">
					.atgfip_notification.red{ color:red; }
					.atgfip_notification.green{ color:green; }
					.atgfip_form {display:flex; flex-direction:column; }
					.atgfip_form > div{display:flex; flex-direction:column; align-items: baseline; }
					.atgfip_form input[type="text"]{ width:80%; }
					.atgfip_form .atgfip_antispam{ flex-direction: row; }
					.atgfip_form .atgfip_antispam > span{ margin:0 5px 0; }
					#atgfip_submit{cursor:pointer;}
					.atgfip_textarea{display:block; width:100%; height:200px;}
					.brdr{ border-radius:5px; border:1px solid; padding:3px; margin:5px; background-color:#E6E6E6; }
				</style> 
				<form class="atgfip_form" action="" method="POST">
					<div>'. $this->phrase("Your email") .' <input class="cfinputt brdr" name="atgfip_email" value="'.$vars['atgfip_email'].'" placeholder="" type="text" /></div>
					<div>'. $this->phrase("Subject")  .' <input class="cfinputt brdr" name="atgfip_subject"   value="'.$vars['atgfip_subject'].'" placeholder="" type="text" /></div>
					<div><textarea class="atgfip_textarea brdr" name="atgfip_text"/>'.$vars['atgfip_text'].'</textarea></div>
					<div class="atgfip_antispam"> <span>'. $this->phrase("Anti-Spam check").'</span> <input class="cfinputt confrm" type="checkbox" name="'.$antispam_key.'" value="'. $antispam_value .'" /></div>
					'. wp_nonce_field('atgfip_formnonce', 'atgfip_nonce', true, false) .'
					<div><input class="cfinputt brdr submt" type="submit" value="'.$this->phrase("Send").'" id="atgfip_submit"  /></div>
				</form>
			</div>';
		}
		return $out;
	}


	public function mainSite(){
		return !is_multisite() || ( $this->network_managed_is_selected && is_network_admin() );
	}






	public function ShowPopupNotice_head(){
		?>
		<style>
		#atgfip_cookie_notice { position:fixed; bottom:0; width:100%; background:#000000e0; z-index:888888888; padding:10px; text-align:center; color:wheat; opacity:1; }
		#atgfip_cookie_notice a{ color:#6565ff; }
		#atgfip_cookie_notice .close_button_1{ background: #ffffff; border-radius: 50%; cursor: pointer; display: inline-block; position: relative; top: -5px; font-family: sans-serif; margin-left: 20px; color: black; font-weight: bold; padding: 3px; line-height: 1em; } 
		#atgfip_cookie_notice .atgfip-fade-in { animation: atgfip_fadeIn ease 2s; -webkit-animation: atgfip_fadeIn ease 2s; -moz-animation: atgfip_fadeIn ease 2s; -o-animation: atgfip_fadeIn ease 2s; -ms-animation: atgfip_fadeIn ease 2s; } @keyframes fadeIn { 0% {opacity:0;} 100% {opacity:1;} } @-moz-keyframes atgfip_fadeIn { 0% {opacity:0;} 100% {opacity:1;} } @-webkit-keyframes atgfip_fadeIn { 0% {opacity:0;} 100% {opacity:1;} } @-o-keyframes atgfip_fadeIn { 0% {opacity:0;} 100% {opacity:1;} } @-ms-keyframes atgfip_fadeIn { 0% {opacity:0;} 100% {opacity:1;} } 
		</style>
		<?php
	}
	

	public function ShowPopupNotice_footer(){
		?>
		<script>
		(function (){
			let cookiename	= '<?php echo $this->cookieNoticeSlug;?>';
			//if( document.cookie.indexOf(CAM_cookiename+'=') <= -1)
			{
				<?php $url =  str_replace($this->linkToPrivacyVariable, $this->url('privacy-policy'), $this->opts["privacy_message"]); ?>
				let message = `<?php echo $this->opts["privacy_message"];?>`;
				let closeButton= `<?php echo ( $this->opts["privacy_agree_button_chars"] ?: 'x');?>`;
				let doc='<div id="atgfip_cookie_notice" class="atgfip-fade-in">'+message+' <span class="close_button_1" onclick="document.querySelector(\'#atgfip_cookie_notice\').remove();">'+closeButton+'</span></div>';
				document.body.insertAdjacentHTML('afterbegin', doc);
				document.cookie = cookiename + "=y; expires=Sun, 18 Dec 2072 20:47:11 UTC; path=/";
			}
		})();
		</script>
		<?php
	}








	public function opts_page_output()
	{  
		$this->settings_page_part("start", 'first');
		?> 

		<style>
		zzz.mainForm { text-align:center;  }
		.mainForm table {  margin: 0 auto; }
		.applies_to { font-style:italic; color:green;}
		.texts-titles{display:flex; flex-direction:column;}
		.eachBlock{ padding: 10px; background: #e7e7e7; width: 80%; max-width: 800px; margin: 10px auto; }
		.titleNotice { font-size:1.6em; color:red; }
		.myplugin table td {min-width:50px;    border-bottom: 2px solid #ececec;}
		.myplugin .contents_rows {display:none;}
		</style> 

		<?php if ($this->active_tab=="Options") 
		{
			$initial_created = $this->get_option_CHOSEN('`initial_pages_created', false);
			//if form updated
			if( $this->checkSubmission() ) { 
				$allowed = [ 'a' => [ 'href' => array(), 'title' =>[] ], 'br' =>[], 'em' => [], 'strong' =>[] ];
				
				$this->opts['menu_in_footer']	= $this->postOptionIsset('menu_in_footer');
				$this->opts['use_nofollow']  	= $this->postOptionIsset('use_nofollow');
				$this->opts['use_caching']  	= $this->postOptionNumber('use_caching');
				$this->opts['show_privacy_note']= $this->postOptionIsset('show_privacy_note');
				$this->opts['privacy_message']	= wp_kses($this->postOptionValue('privacy_message'), $allowed);
				$this->opts['privacy_agree_button_chars']=$this->postOptionText('privacy_agree_button_chars');
				//$this->opts['last_update_time'] = sanitize_title($_POST[ $this->slug ]['last_update_time']);
				//$this->opts['page_titles'] = array_map('sanitize_title', $_POST[ $this->slug ]['page_titles'] );
				//foreach ($this->opts['page_titles'] as $slug=>$title) {
				//	$this->update_option_CHOSEN('atgfip_globaltext_'.$slug, wp_kses_stripslashes($_POST['globaltext_'.$slug]));
				//}
				
				$this->update_opts(); 
				if (!$initial_created ){
					$this->create_pages_initial();
					$initial_created = $this->update_option_CHOSEN('`initial_pages_created', true);
				}
			}

			$global_mode = $this->network_managed_is_selected && is_network_admin() || !is_multisite();
			?>

			<form class="mainForm" method="post" action="">
			<table>
			<tr class="hierarchy_set">
				<td>
					<b><?php _e("Created pages :");?></b>
				</td>
				<td> 
					<p> <?php $slug= $this->basepage_info["slug"]; _e("Under the root-page (called <code>$slug</code>) the following sub-pages will be created (of course, you can change them later):");  echo '<code>'.implode(",", $this->page_titles).'</code>';?></p> 
					<span class="applies_to"><?php echo __("Note: ").( $global_mode ? __('You have chosen <code>GLOBAL MODE</code> for this plugin. So, those pages will be created under <code>PRIMARY SITE</code> and used for <code>ALL SUB-SITES</code>') :  ( is_multisite() ? __('The plugin was set as <code>SUB-SITE MODE</code> for this plugin. So, those pages will be created under this current <code>CURRENT SUB-SITE</code> only') : '' ) ) ;?></span>
				</td>
			</tr>
			<tr>
				<td>
					<b style="color:green;"><?php _e( 'Show menu in footer', 'automatic-text-generator-for-important-pages'); ?></b>
				</td>
				<td> 
					<p><input type="checkbox" name="<?php echo $this->slug;?>[menu_in_footer]" value="1" <?php checked($this->opts['menu_in_footer'], true);?> /></p> <?php _e(' (otherwise you can manually append the newly created "custom menu" in any widget or using shortcode)',  'automatic-text-generator-for-important-pages'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<b><?php _e( 'Use "no-index" for Terms & Privacy pages', 'automatic-text-generator-for-important-pages'); ?></b>
				</td>
				<td> 
					<p><input type="checkbox" name="<?php echo $this->slug;?>[use_nofollow]" value="1" <?php checked($this->opts['use_nofollow'], true);?> /></p>  <?php _e('If you use other SEO plugins, like YOAST, then disable this option, and set within YOAST as you desire.',  'automatic-text-generator-for-important-pages'); ?>
				</td>
			</tr>
			<tr>
				<td>
					<b><?php _e( 'Use caching for menu', 'automatic-text-generator-for-important-pages'); ?></b>
				</td>
				<td> 
					<p><input type="numeric" name="<?php echo $this->slug;?>[use_caching]" value="<?php echo $this->opts['use_caching'];?>"  /></p>  <?php _e('If you use other caching plugins, set this option to <code>0</code>. Otherwise using cache is good, at least 1 day (1440 minutes).',  'automatic-text-generator-for-important-pages'); ?>
				</td>
			</tr> 
			</table> 
			
			<br/>
			<br/>
			<h2><?php _e( 'Privacy Policy', 'automatic-text-generator-for-important-pages'); ?></h2>
			
			<table>
			<tr>
				<td>
					<b><?php _e( 'Show privacy warning message on first-time visitors', 'automatic-text-generator-for-important-pages'); ?></b>
				</td>
				<td> 
					<input type="checkbox" name="<?php echo $this->slug;?>[show_privacy_note]" value="1"  <?php checked($this->opts['show_privacy_note'], true);?>  />  <?php _e('Even though I recommend other advanced "Privacy Policy & GDPR" plugins, this might also help you temporarilty to show just very basic  "privacy policy" notice to first-time visitors on your site.',  'automatic-text-generator-for-important-pages'); ?> 
				</td>
			</tr>
			<tr>
				<td>
					<b><?php _e( 'Message', 'automatic-text-generator-for-important-pages'); ?></b>
				</td>
				<td> 
					<input type="text" name="<?php echo $this->slug;?>[privacy_message]" class="large-text" value="<?php echo htmlentities($this->opts['privacy_message']);?>" />
					<p><?php $slug=$this->linkToPrivacyVariable; _e("You can use <code>$slug</code> to get automatic link to Privacy-Policy generated by this plugin");?></p>
				</td>
			</tr>
			<tr>
				<td>
					<b><?php _e( '<code>Agree/Close</code> button letters', 'automatic-text-generator-for-important-pages'); ?></b>
				</td>
				<td> 
					<p><input type="text" name="<?php echo $this->slug;?>[privacy_agree_button_chars]" value="<?php echo $this->opts['privacy_agree_button_chars'];?>" /></p>
				</td>
			</tr> 
			</table> 
			<input type="hidden" name="update_stg1" value="1" />
			<?php 
			$this->nonceSubmit("Submit"); 
			?>
			</form>
		<?php 
		}  
		
		$this->settings_page_part("end", '');
		
	} 





  } // End Of Class

  $GLOBALS[__NAMESPACE__] = new PluginClass();

} // End Of NameSpace

/*
	termsandcondiitionssample.com
	wp auto terms
	we make websites - privacy policy
	Website usage terms and conditions – sample template ( practicalecommerce.com/6-Key-Terms-and-Conditions-for-Ecommerce-Merchants )
*/
?>