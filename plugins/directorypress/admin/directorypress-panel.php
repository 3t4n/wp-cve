<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DirectoryPress_Admin_Panel {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		// Redirect to welcome page
		if ( isset( $_GET['page'] ) ) {
			if ($_GET['page'] == 'directorypress-admin-panel' || substr( $_GET['page'], 0, 15 ) == "directorypress_" || $_GET['page'] == 'directorypress_settings') {
				add_action( 'admin_footer', array( $this, 'quick_access' ) );
			}
		}
	}
	
	static function dashboard_menu() {
		global $submenu;

		$menus			= $submenu['directorypress-admin-panel'];
		$menu_size		= sizeof( $menus );
		$menu			= '';
		$crt_pg_name	= get_admin_page_title();
		$base			= explode( 'directorypress', get_current_screen()->base);
		$base			= 'directorypress' . $base[1];
		foreach ($menus as $sub_menu ) {
			$acive_page = ( $base == $sub_menu[2] ) ? ' nav-tab-active' : '' ;
			$menu .= '<a class="nav-tab' . $acive_page . '" href="' . esc_url( self_admin_url( 'admin.php?page='.$sub_menu[2] ) ) . '">' . esc_html( $sub_menu[0], 'DIRECTORYPRESS' ) . '</a>';
		}
		echo wp_kses_post($menu);
	}
	
	static function listing_dashboard_header() {
		echo '<div class="directorypress-admin-header wp-clearfix">';
			echo '<div class="directorypress-admin-logo"></div>';
			echo '<div class="directorypress-admin-version">'.esc_html__( 'V', 'DIRECTORYPRESS' ).' '.DIRECTORYPRESS_VERSION.'</div>';
		echo '</div>';
		echo '<div class="directorypress-admin-header-after">';
			echo '<div class="row">';
				echo '<div class="col-md-6 col-sm-6 col-xs-12 mt-30">';
						echo '<a href="https://designinvento.net/themes/" target="_blank"><img src="'. esc_url(DIRECTORYPRESS_URL .'admin/assets/images/themes-banner.png') .'" alt="Premium Themes" /></a>';
				echo '</div>';
				echo '<div class="col-md-6 col-sm-6 col-xs-12 mt-30">';
						echo '<a href="https://designinvento.net/directorypress-extentions/" target="_blank"><img src="'. esc_url(DIRECTORYPRESS_URL .'admin/assets/images/extensions-banner.png') .'" alt="Premium Extensions" /></a>';
				echo '</div>';
			echo '</div>';
		echo '</div>';
		echo '<div class="nav-tab-wrapper wp-clearfix">';
			DirectoryPress_Admin_Panel::dashboard_menu();
		echo '</div>';
		
	}
	public function enqueue_scripts() {
		 if ( isset( $_GET['page'] ) ) :
			if ($_GET['page'] == 'directorypress-admin-panel' || substr( $_GET['page'], 0, 15 ) == "directorypress_" || $_GET['page'] == 'directorypress_settings') :
				wp_enqueue_style('bootstrap');
				wp_enqueue_style('fontawesome');
				wp_enqueue_style( 'directorypress-admin-panel-styles', DIRECTORYPRESS_URL . 'admin/assets/css/directorypress-panel.css', 99 );
				wp_enqueue_style('directorypress-select2');
				wp_enqueue_script('directorypress-select2');
				wp_enqueue_script('directorypress-select2-triger');
				wp_enqueue_script('directorypress_admin_script');
			endif; // substr
		endif; // isset 
	}

	public function admin_menus() {
		
			add_menu_page(
				esc_html__( 'DirectoryPress', 'DIRECTORYPRESS' ),
				esc_html__( 'DirectoryPress', 'DIRECTORYPRESS' ),
				'manage_options',
				'directorypress-admin-panel',
				array($this, 'screen_welcome'),
				'',
				15
			);
			
			add_submenu_page(
				'directorypress-admin-panel',
				esc_html__( 'Extensions', 'DIRECTORYPRESS' ),
				esc_html__( 'Extensions', 'DIRECTORYPRESS' ),
				'manage_options',
				'directorypress_extensions',
				array($this, 'screen_extensions'),
				99
			);
			add_submenu_page(
				'directorypress-admin-panel',
				esc_html__( 'Premium Themes', 'DIRECTORYPRESS' ),
				esc_html__( 'Premium Themes', 'DIRECTORYPRESS' ),
				'manage_options',
				'directorypress_themes',
				array($this, 'screen_themes'),
				99
			);
	}
	
	public function screen_welcome() {
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		do_action('directorypress_dashboad_panel');
	}
	public function screen_extensions() {
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		require_once( 'partials/addons.php' );
	}
	public function screen_themes() {
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		require_once( 'partials/themes.php' );
	}
	public function generate_panel() {
		ReduxFramework::init();
        do_action( 'redux/init' );
		
    }
	public function dicode_products($parameters) {
		$request = wp_safe_remote_get( 'https://designinvento.net/edd-api/v2/products/?'. $parameters );

		if( is_wp_error( $request ) ) {
			return false; // Bail early
		}

		$body = wp_remote_retrieve_body( $request );
		$data = json_decode( $body );
		if( ! empty( $data ) ) {
			echo '<div class="row directorypress-extension-items">';
				foreach( $data->products as $product ) {
					echo '<div class="directorypress-extension-item col-md-3 col-sm-4 col-xs-12">';
						echo '<div class="directorypress-extension-item-holder">';
							echo '<a class="title" href="' . esc_url( $product->info->link ) . '" target="_blank"><img src="'. esc_url( $product->info->thumbnail ) .'" alt="'. esc_attr($product->info->title) .'"/></a>';
							echo '<div class="price">';
								echo sprintf(esc_html__('From %s', 'DIRECTORYPRESS'), $product->info->price);
							echo '</div>';
							echo '<a class="title" href="' . esc_url( $product->info->link ) . '" target="_blank" title="'. esc_attr($product->info->title) .'">' . wp_trim_words(esc_html($product->info->title), 5, ' ...') . '</a>';
						echo '</div>';
					echo '</div>';
				}
			echo '</div>';
		}
		
    }
	public function quick_access() {
		
		$current_scr 	= get_current_screen();
		$current_page	= $current_scr->id;
		$protocol		= is_ssl() ? 'https://' : 'http://';
		?>
		
		<div class="directorypress-qucik-help-wrapper">
			<div class="directorypress-qucik-help-icon">
				<i class="fas fa-info"></i>
			</div>
			<ul class="directorypress-qucik-help-content">
				<?php

				switch ($current_page) {
					case 'directorypress_page_directorypress_locations_depths': ?>
						<li>
							<a href="<?php echo esc_url($protocol . 'help.designinvento.net/docs/direcotypress/directorypress-fields/how-to-create-new-fileds/'); ?>" target="_blank" >
								<?php esc_html_e( 'How To Create New Filed', 'DIRECTORYPRESS' ); ?>
							</a>
						</li>
						<?php
						break;
					case 'directorypress_page_directorypress_fields': ?>
						<li>
							<a href="<?php echo esc_url($protocol . 'help.designinvento.net/docs/directorypress/directorypress-fields/how-to-create-new-fileds/'); ?>" target="_blank" >
								<?php esc_html_e( 'How To Create New Filed', 'DIRECTORYPRESS' ); ?>
							</a>
						</li>
						<li>
							<a href="<?php echo esc_url($protocol . 'help.designinvento.net/docs/directorypress/directorypress-fields/how-to-edit-filed/'); ?>" target="_blank" >
								<?php esc_html_e( 'How To Edit Filed', 'DIRECTORYPRESS' ); ?>
							</a>
						</li>
						<?php
						break;
					case 'directorypress_page_directorypress_directorytypes': ?>
						<li>
							<a href="<?php echo esc_url($protocol . 'help.designinvento.net/docs/direcotypress/directorypress-fields/how-to-create-new-fileds/'); ?>" target="_blank" >
								<?php esc_html_e( 'How To Create New Filed', 'DIRECTORYPRESS' ); ?>
							</a>
						</li>
						<?php
						break;
					case 'directorypress_page_directorypress_packages': ?>
						<li>
							<a href="<?php echo esc_url($protocol . 'help.designinvento.net/docs/direcotypress/directorypress-fields/how-to-create-new-fileds/'); ?>" target="_blank" >
								<?php esc_html_e( 'How To Create New Package', 'DIRECTORYPRESS' ); ?>
							</a>
						</li>
						<?php
						break;
					
					default: ?>
						<li>
							<a href="<?php echo esc_url($protocol . 'help.designinvento.net/docs/direcotypress/directorypress-fields/how-to-create-new-fileds/'); ?>" target="_blank" >
								<?php esc_html_e( 'How To Create New Filed', 'DIRECTORYPRESS' ); ?>
							</a>
						</li>
						<?php
						break;
				} ?>
			</ul>
		</div>
	<?php
	}
}
new DirectoryPress_Admin_Panel();