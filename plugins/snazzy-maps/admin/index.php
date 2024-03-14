<?php
namespace SnazzyMaps;

defined( 'ABSPATH' ) OR exit;

include_once(plugin_dir_path(__FILE__) . _DS . 'styles.php');
include_once(plugin_dir_path(__FILE__) . _DS . 'explore.php');
include_once(plugin_dir_path(__FILE__) . _DS . 'settings.php');
include_once(plugin_dir_path(__FILE__) . _DS . 'help.php');
include_once(plugin_dir_path(__FILE__) . _DS . 'helpers.php');

class SnazzyMaps_Index {

	public static function admin_perform_post (){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			
			//Perform the post for the tab that was selected        
			$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : null; 
			
			if($active_tab == 0){ \SnazzyMaps\SnazzyMaps_Styles::admin_styles_head(0); }
			if($active_tab == 1){ \SnazzyMaps\SnazzyMaps_Explore::admin_explore_head(1); }
			if($active_tab == 2){ \SnazzyMaps\SnazzyMaps_Settings::admin_my_snazzymaps_head(2); }
			if($active_tab == 3){ \SnazzyMaps\SnazzyMaps_Help::admin_help_head(3); }
		}
	}

	public static function admin_enqueue_script($hook){

		//Only include the javascript in our page
		if($hook != 'appearance_page_snazzy_maps') return;
			
		//Include the javascript
		$handle = 'admin-snazzymaps-js';
		wp_enqueue_script($handle, plugins_url('index.js', __FILE__), $deps = array('jquery'),
						$ver = SNAZZY_MAPS_VERSION_NUMBER);
		wp_localize_script($handle, 'SnazzyData', array('API_KEY' => SNAZZY_MAPS_API_KEY,
													'API_BASE' => SNAZZY_MAPS_API_BASE,
													'USER_API_KEY' => get_option('MySnazzyAPIKey', null)));
		
		//Include the bower components
		$bower_components = array(
			//history js
			'history.js' . _DS . 'scripts' . 
			_DS . 'bundled' . _DS . 'html5' .
			_DS . 'native.history.js',
			//simplequerystring
			'simple-query-string' . _DS . 'src' . 
			_DS . 'simplequerystring.min.js',
			//mustache
			'mustache' . _DS . 'mustache.min.js'
		);
		foreach((array)$bower_components as $index => $bower_component){
			wp_enqueue_script("admin-bower-component-$index", 
					\SnazzyMaps\SnazzyMaps_Main::resourceURL('bower_components' . _DS . $bower_component),
					$deps = array(),
					$ver = SNAZZY_MAPS_VERSION_NUMBER); 
		}
		
		//Include the css
		wp_enqueue_style('admin-snazzymaps-css', 
						plugins_url('index.css', __FILE__),
						$deps = array(),
						$ver = SNAZZY_MAPS_VERSION_NUMBER); 
	}

	public static function admin_add_custom_content(){
		$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : '0'; 
		
		if(isset($_GET['welcome'])) {
			update_option('HideWelcomeMessage', sanitize_text_field($_GET['welcome']) == 'hide');
		}
	?>
		<div class="wrap sm-plugin">
			<h2>Snazzy Maps</h2>  
			<script id="style-template" type="text/template">
				<form action="?page=snazzy_maps&tab=0" method="POST" class="col-sm-6 col-md-4 style">
					<div class="sm-style">
						<div class="sm-map">
							<img src="{{imageUrl}}" alt="{{name}}"/>
						</div>
						<div class="sm-content info">
							<h3><a href="{{url}}" class="title" target="_blank" title="Check out {{name}} on Snazzy Maps">{{name}}</a></h3>
							<div class="author">by {{author}}</div>
							<div class="stats">
								<div class="views"><span class="icon-eye" title="Views"></span>{{views}}</div>
								<div class="favorites"><span class="icon-star" title="Favorites"></span>{{favorites}} favorites</div>
							</div>
							<button class="button button-primary button-large" type="submit">SAVE STYLE</button>   
							<input type="hidden" name="new_style" value=""/>
							<?php wp_nonce_field( 'snazzy_maps_save_style' ); ?> 
						</div>
					</div>
				</form>   
			</script>   
			<?php if(!get_option('HideWelcomeMessage', false)) { ?>
				<div id="welcome-panel" class="box-shadow-cell welcome-panel">
					<a href="<?php echo \SnazzyMaps\SnazzyMaps_Helpers::esc_rel_url('?page=snazzy_maps&tab=' . $active_tab . '&welcome=hide'); ?>" class="welcome-panel-close">Dismiss</a>
					<div class="row">
						<div class="col-md-10 col-lg-6">
							<h3>Welcome!</h3>
							<p>
								Thanks for installing the Snazzy Maps plugin! <a href="?page=snazzy_maps&tab=1">Explore</a> some styles to get started 
								or check out the <a href="?page=snazzy_maps&tab=3">Help</a> page for detailed instructions and frequently asked questions.
							</p>
						</div>
					</div>
				</div>
			<?php } ?>  
			<div class="row">  
				<div class="nav-tab-container col-md-12">                       
					<h2 class="nav-tab-wrapper">
						<?php
							$tabs = array('Site Styles', 'Explore', 'Settings', 'Help');
							foreach((array)$tabs as $index => $tab){
							?>
								<a href="<?php echo \SnazzyMaps\SnazzyMaps_Helpers::esc_rel_url('?page=snazzy_maps&tab=' . $index);?>"
								class="nav-tab <?php echo esc_attr($active_tab == $index ? 'nav-tab-active' : '');?>">
									<?php echo esc_html($tab);?>
								</a>
							<?php
							}
						?>
					</h2>         
					<?php if($active_tab == 0) { \SnazzyMaps\SnazzyMaps_Styles::admin_styles_tab(0); } ?>     
					<?php if($active_tab == 1) { \SnazzyMaps\SnazzyMaps_Explore::admin_explore_tab(1); } ?>
					<?php if($active_tab == 2) { \SnazzyMaps\SnazzyMaps_Settings::admin_my_snazzymaps_tab(2); } ?>  
					<?php if($active_tab == 3) { \SnazzyMaps\SnazzyMaps_Help::admin_help_tab(3); } ?>        
				</div>                
			</div>    
			<div class="footer">
				<div class="container-fluid">
					<a href="https://snazzymaps.com/" class="icon-brand" target="_blank"></a>
					<div class="social clearfix">
						<a href="https://twitter.com/snazzymaps" class="icon-twitter" target="_blank"></a>
						<a href="https://www.facebook.com/SnazzyMaps" class="icon-facebook" target="_blank"></a>
						<a href="https://google.com/+SnazzyMaps" class="icon-googleplus" target="_blank"></a>
						<a href="mailto:support@snazzymaps.com" class="icon-mail" target="_blank"></a>
					</div>
				</div>
			</div>
		</div>
<?php 
	}
} 
?>