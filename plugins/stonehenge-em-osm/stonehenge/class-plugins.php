<?php
if (!defined('ABSPATH')) exit;
include_once(ABSPATH.'wp-admin/includes/plugin.php');

if( !class_exists('Stonehenge_Creations_Plugins') ) :
Class Stonehenge_Creations_Plugins {


	#===============================================
	public function __construct(){
		add_action('stonehenge_menu', array($this, 'add_submenu_page'), 100);
	}


	#===============================================
	public function add_submenu_page() {
		add_submenu_page(
			'stonehenge-creations',
			'More...',
			'<span class="dashicons-before dashicons-smiley" style="margin-top:-1px; margin-left: -4px; font-size:14px;"></span>&nbsp;More...',
			'manage_options',
			'stonehenge-plugins',
			array($this, 'show_this_page')
		);
	}


	#===============================================
	public function show_this_page() {
		$plugins = $this->define_plugins();
		stonehenge()->load_admin_assets();
		?>
		<div class="wrap">
			<h1>More Plugins by Stonehenge Creations</h1>
			<div id="poststuff">
				<div class="inside">
					<div id="postbox-container" class="postbox-container stonehenge-settings">
						<div class="meta-box-sortables ui-sortable" id="normal-sortables">
							<div class="postbox" id="more">
								<div class="inside">
									<p>Here is an overview of other plugins and add-ons that I have created. Feel free to check them all out! :-)<br>
										Missing anything or needing custom coding? <a href="https://www.stonehengecreations.nl/contact/" target="_blank"><strong>Let me know!</strong></a>
									</p>
									<?php
									if( !$plugins || empty($plugins) ) {
										echo '<p>There was an error retrieving the Plugins Overview. Please try again later.</p>';
									} else {
										foreach($plugins as $plugin ) {
											$base		= $plugin['slug'];
											$file 		= "{$base}/{$base}.php";
											$image_path = plugin_dir_path( __FILE__). "/plugins/{$base}.jpg";
											$image_url 	= plugins_url("/plugins/{$base}.jpg", __FILE__);
											$default 	= plugins_url('/plugins/stonehenge.jpg', __FILE__);
											$image 		= empty($plugin['icon']) ? $default : ( file_exists($image_path) ? $image_url : $plugin['icon'] );
											$link 		= "a href='{$plugin['link']}' target='_blank' title='{$plugin['name']}'";
											$needs 		= !empty($plugin['needs']) ? "Requires: <font color='#0081bc'>{$plugin['needs']}</font>" : "";
											$name 		= explode(' - ', $plugin['name']);
											$key 		= $plugin['paid'] ? '<span class="dashicons dashicons-admin-network"></span>' : null;
											echo "<div class='addon-card'><div class='row title'>{$key}<a {$link}><smaller>{$name[0]}<br></smaller><h4>{$name[1]}</h4></a></div><div class='row info'><a {$link}><img src='{$image}' class='icon' alt='{$plugin['name']}'></a>{$plugin['info']}</div>";

											echo "<div class='row'>";
												// Check if plugin is already installed and activated.
												if( is_plugin_active( $file ) ) {
													echo '<span class="stonehenge-success" style="float:right;">'. __('Already Installed') .'</span>';
												}
												else {
													if( !$plugin['type'] ) {
														$install = __('Install Now');
														echo "<{$link}><button class='stonehenge-button' style='float:right;'>{$install}</button></a>";
													}
													else {
														echo '<span class="stonehenge-info" style="float:right;">Coming Soon</span>';
													}
												}
											echo "</div></div>";
										}
									}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}


	#===============================================
	public function define_plugins() {
		$plugins = get_transient('stonehenge_creations_plugins_feed');
		if( false === $plugins ) {
			$response = json_decode( file_get_contents( STONEHENGE . 'edd-api/v2/products/', true));
			unset($response->request_speed);
			$plugins = array();
			foreach( $response as $products ) {
				foreach( $products as $product ) {
					$info = $product->info;
					$title = str_replace(' – ', ' ', $info->title);
					$plugins[$title] = array(
						'id' 	=> $info->id,
						'slug' 	=> $info->slug,
						'name' 	=> $info->title,
						'link'	=> $info->link,
						'icon'	=> $info->thumbnail,
						'type' 	=> $product->coming_soon,
						'needs' => @$info->category[0]->name,
						'paid'	=> $product->licensing->enabled,
						'info'	=> $info->excerpt,
					);
				}
			}
			ksort($plugins);
			set_transient('stonehenge_creations_plugins_feed', $plugins, 86400 ); // Once per day.
		}
		return $plugins;
	}


	#===============================================
	public static function show_new_plugin() {
		if( is_plugin_active('events-manager-pro/events-manager-pro.php') && !is_plugin_active('stonehenge-em-gift-cards/stonehenge-em-gift-cards.php') ) {
			$plugins = get_transient('stonehenge_creations_plugins_feed');
			if( !is_array($plugins) ) {
				return;
			}

			foreach( $plugins as $plugin ) {
				$base		= $plugin['slug'];
				$file 		= "{$base}/{$base}.php";
				$image_path = plugin_dir_path( __FILE__). "/plugins/{$base}.jpg";
				$image_url 	= plugins_url("/plugins/{$base}.jpg", __FILE__);
				$default 	= plugins_url('/plugins/stonehenge.jpg', __FILE__);
				$image 		= empty($plugin['icon']) ? $default : ( file_exists($image_path) ? $image_url : $plugin['icon'] );
				$link 		= "a href='{$plugin['link']}' target='_blank' title='{$plugin['name']}'";
				$needs 		= !empty($plugin['needs']) ? "Requires: <font color='#0081bc'>{$plugin['needs']}</font>" : "";
				$name 		= explode(' - ', $plugin['name']);
				$key 		= $plugin['paid'] ? '<span class="dashicons dashicons-admin-network"></span>' : null;
				$install 	= __('More info');

				if( $base === 'stonehenge-em-gift-cards' ) {
					echo '<div style="display: table; width: auto; margin: 40px auto 0 auto; border: 1px dashed Crimson; padding:5px 3px;">';
					echo '<h2 style="margin: 0 auto; padding: 0; text-align: center;">Now Available</h2>';

					echo "<div class='addon-card' style='background-color:white;'>";
					echo "<div class='row title'>{$key}<a {$link}><smaller>{$name[0]}<br></smaller><h4>{$name[1]}</h4></a></div><div class='row info'><a {$link}><img src='{$image}' class='icon' alt='{$plugin['name']}'></a>{$plugin['info']}</div>";
					echo "<div class='row'>";
					echo "<{$link}><button class='stonehenge-button' style='float:right;'>{$install}</button></a>";
					echo "</div></div>";

					echo "</div>";
				}
			}
			return;
		}
	}




} // End class.

new Stonehenge_Creations_Plugins();
endif;

