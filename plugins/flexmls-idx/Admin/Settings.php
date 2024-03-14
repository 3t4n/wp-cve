<?php
namespace FlexMLS\Admin;

defined( 'ABSPATH' ) or die( 'This plugin requires WordPress' );

class Settings {

	public static function admin_menu_cb_intro(){
		$tab = isset( $_GET[ 'tab' ] ) ? sanitize_title( $_GET[ 'tab' ] ) : 'api';
		$fmc_plugin_dir = FMC_PLUGIN_DIR;
		if( !file_exists( $fmc_plugin_dir . 'views/admin-intro-' . $tab . '.php' ) ){
			$tab = '404';
		}
		?>
			<div class="wrap about-wrap about-flexmls">

				<div class="intro-banner">
					<img src="<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/dashboard-banner.png'; ?>">
				</div>

				<!-- <div class="wp-badge">Version <?php // echo FMC_PLUGIN_VERSION; ?></div> -->
				<h2 class="nav-tab-wrapper wp-clearfix">
					<a href="<?php echo admin_url( 'admin.php?page=fmc_admin_intro&tab=api' ); ?>" class="nav-tab<?php echo ( 'api' == $tab ? ' nav-tab-active' : '' ); ?>">Account</a>

					<a href="<?php echo admin_url( 'admin.php?page=fmc_admin_intro&tab=support' ); ?>" class="nav-tab<?php echo ( 'support' == $tab ? ' nav-tab-active' : '' ); ?>">Support</a>

					<a href="<?php echo admin_url( 'admin.php?page=fmc_admin_intro&tab=features' ); ?>" class="nav-tab<?php echo ( 'features' == $tab ? ' nav-tab-active' : '' ); ?>">Features</a>
				</h2>
				<div class="intro-wrap-content">
					<?php include_once( $fmc_plugin_dir . 'views/admin-intro-' . $tab . '.php' ); ?>
				</div>
			</div>
		<?php
	}

	public static function admin_menu_cb_neighborhood(){
		$fmc_settings = get_option( 'fmc_settings' );
		$system = new \SparkAPI\System();

		if( !empty( $_POST ) && isset( $_POST[ 'create_neighborhood_draft_nonce' ] ) && wp_verify_nonce( $_POST[ 'create_neighborhood_draft_nonce' ], 'create_neighborhood_draft_action' ) ){
			$new_template_id = wp_insert_post( array(
				'post_title' => 'Neighborhood Template Draft',
				'post_type' => 'page'
			) );
			if( $new_template_id ){
				printf(
					'<div class="notice notice-success">
						<p>Your new page <em>%s</em> has been created. <a href="%s">Click here to edit this new page</a> or continue creating your new neighborhood below.</p>
					</div>',
					'Neighborhood Template Draft',
					admin_url( 'post.php?post=' . $new_template_id . '&action=edit' )
				);
			}
		}
		if( !empty( $_POST ) && isset( $_POST[ 'add_neighborhood_nonce' ] ) && wp_verify_nonce( $_POST[ 'add_neighborhood_nonce' ], 'add_neighborhood_action' ) ){
			$loc = $system->parse_location_search_string( stripcslashes( $_POST[ 'location' ] ) );
			if( empty( $loc ) ){
				echo '	<div class="notice notice-error">
							<p>Your new page was not created because you did not select a location. Please try again.</p>
						</div>';
			} else {
				$loc_title = $loc[ 0 ][ 'l' ];
				$loc_raw = $loc[ 0 ][ 'r' ];
				$shortcode = '[neighborhood_page title="' . $loc_title . '" location="' . $loc_raw . '" template="' . sanitize_text_field( $_POST[ 'template' ] ) . '"]';
				$new_page_id = wp_insert_post( array(
					'post_title' => $loc_title,
					'post_content' => $shortcode,
					'post_type' => 'page',
					'post_status' => 'publish',
					'post_parent' => intval( sanitize_text_field( $_POST[ 'parent' ] ) )
				) );
				$template_id = intval( sanitize_text_field( $_POST[ 'template' ] ) );
				if( !isset( $fmc_settings[ 'neigh_template' ] ) || empty( $fmc_settings[ 'neigh_template' ] ) ){
					$fmc_settings[ 'neigh_template' ] = $template_id;
					update_option( 'fmc_settings', $fmc_settings );
				}
				$template_page_template = get_post_meta( $template_id, '_wp_page_template', true );
				update_post_meta( $new_page_id, '_wp_page_template', $template_page_template);
				printf(
					'<div class="notice notice-success">
						<p>Your neighborhood has been created! You can <a href="%s">click here to edit this new page</a>, or add another neighborhood below.</p>
					</div>',
					admin_url( 'post.php?post=' . $new_page_id . '&action=edit' )
				);
			}
		}
		$can_create_neighborhood = true;
		$templates = get_posts( array(
			'order' => 'ASC',
			'orderby' => 'menu_order name',
			'nopaging' => true,
			'post_status' => 'draft',
			'post_type' => 'page'
		) );
		if( !$templates ){
			$can_create_neighborhood = false;
		}
		?>
		<div class="notice notice-warning">
		    <p>We will be deprecating the Neighborhood widget in a future update. We recommend using the <a href="https://fbsdata.zendesk.com/hc/en-us/articles/232021727-IDX-Slideshow">IDX Slideshow</a> and/or <a href="https://fbsdata.zendesk.com/hc/en-us/articles/232280088--IDX-Listing-Summary">IDX Listing Summary</a> widgets to display listings. <a href="https://fbsdata.zendesk.com/hc/en-us/articles/232023247--Neighborhood-or-Location-Based-Pages">Click here</a> more details.</p>
		</div>
			<div class="wrap">
				<h1><?php echo get_admin_page_title(); ?></h1>
				<p>To create a new neighborhood page automatically, select your location and template below. You can create additional templates by adding additional <em>Pages</em> and setting them to <em>Draft</em> status. <a href="<?php echo admin_url( 'post-new.php?post_type=page' ); ?>">Click here to create a new page</a>.</p>
				<form action="<?php echo admin_url( 'admin.php?page=fmc_admin_neighborhood' ); ?>" method="post" autocomplete="off">
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row"><label for="fmc_template">Neighborhood Template</label></th>
								<td>
									<?php if( !$can_create_neighborhood ): ?>
										<?php wp_nonce_field( 'create_neighborhood_draft_action', 'create_neighborhood_draft_nonce' ); ?>
										<button type="submit" class="button-secondary">Create A Template For Me</button>
										<p class="description">You do not have any draft pages set up for your Neighborhood template. Click the button above to automatically create a draft page you can use for your Neighborhood template.</p>
									<?php else: ?>
										<select name="template" id="fmc_template" class="regular-text">
											<?php foreach( $templates as $template ): ?>
												<option value="<?php echo $template->ID; ?>" <?php selected( $template->ID, $fmc_settings[ 'neigh_template' ] ); ?>><?php
													echo $template->post_title;
													if( $fmc_settings[ 'neigh_template' ] == $template->ID ){
														echo ' (Saved Default)';
													}
												?></option>
											<?php endforeach; ?>
										</select>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="fmc_parent">Parent Page</label></th>
								<td>
									<?php if( !$can_create_neighborhood ): ?>
										<p class="description">Create a draft first to make this selection available.</p>
									<?php else: ?>
										<?php wp_dropdown_pages( array(
											'class' => 'regular-text',
											'id' => 'fmc_parent',
											'name' => 'parent',
											'option_none_value' => 0,
											'show_option_none' => '(No Parent)'
										) ); ?>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="fmc_location">Location</label></th>
								<td>
									<?php if( !$can_create_neighborhood ): ?>
										<p class="description">Create a draft first to make this selection available.</p>
									<?php else: ?>

										<div class="flexmls_connect__location">

										  <select class="flexmlsAdminLocationSearch regular-text"
										    id="fmc_shortcode_field_location" name="fmc_shortcode_field_location"
										    data-portal-slug="<?= \flexmlsConnect::get_portal_slug()  ?>">
										  </select>

										  <input fmc-field="location" fmc-type='text' type='hidden' name="location"
										    class='flexmls_connect__location_fields' />

											<select style="display:none;" fmc-field="property_type" class="flexmls_connect__property_type"
												fmc-type="select" id="property_type" name="property_type">
												<option value="A" selected="selected"></option>
											</select>
        						</div>

									<?php endif; ?>
								</td>
							</tr>
						</tbody>
					</table>
					<?php if( $can_create_neighborhood ): ?>
						<p><?php wp_nonce_field( 'add_neighborhood_action', 'add_neighborhood_nonce' ); ?><button type="submit" class="button-primary">Add Neighborhood</button></p>
					<?php endif; ?>
				</form>
			</div>
		<?php
	}

	public static function admin_menu_cb_settings(){
		$tab = isset( $_GET[ 'tab' ] ) ? sanitize_title( $_GET[ 'tab' ] ) : 'behavior';
		$fmc_plugin_dir = FMC_PLUGIN_DIR;
		if( !file_exists( $fmc_plugin_dir . 'views/admin-settings-' . $tab . '.php' ) ){
			$tab = '404';
		}
		$SparkAPI = new \SparkAPI\Core();
		$auth_token = $SparkAPI->generate_auth_token();
		?>
			<div class="wrap">
				<h1><?php echo get_admin_page_title(); ?></h1>
				<h2 class="nav-tab-wrapper wp-clearfix">
					<?php if( $auth_token ): ?><a href="<?php echo admin_url( 'admin.php?page=fmc_admin_settings' ); ?>" class="nav-tab<?php echo ( 'behavior' == $tab ? ' nav-tab-active' : '' ); ?>">Behavior</a><?php endif; ?>
					<a href="<?php echo admin_url( 'admin.php?page=fmc_admin_settings&tab=style' ); ?>" class="nav-tab<?php echo ( 'style' == $tab ? ' nav-tab-active' : '' ); ?>">Style</a>
					<a href="<?php echo admin_url( 'admin.php?page=fmc_admin_settings&tab=portal' ); ?>" class="nav-tab<?php echo ( 'portal' == $tab ? ' nav-tab-active' : '' ); ?>">Portal</a>
					<?php if( $auth_token ): ?><a href="<?php echo admin_url( 'admin.php?page=fmc_admin_settings&tab=gmaps' ); ?>" class="nav-tab<?php echo ( 'gmaps' == $tab ? ' nav-tab-active' : '' ); ?>">Google Maps</a><?php endif; ?>
					<a href="<?php echo admin_url( 'admin.php?page=fmc_admin_settings&tab=cache' ); ?>" class="nav-tab<?php echo ( 'cache' == $tab ? ' nav-tab-active' : '' ); ?>">Clear Cache</a>
				</h2>
				<?php include_once( $fmc_plugin_dir . 'views/admin-settings-' . $tab . '.php' ); ?>
			</div>
		<?php
	}

	public static function did_clear_cache(){
		?>
			<div class="notice notice-success">
				<p>The cache has been cleared.</p>
			</div>
		<?php
	}

	public static function did_update_settings(){
		?>
			<div class="notice notice-success">
				<p>Your settings have been saved!</p>
			</div>
		<?php
	}

	public static function update_settings(){
		$fmc_settings = get_option( 'fmc_settings' );

		// Save API Credentials
		if( !empty( $_POST ) && isset( $_POST[ 'update_api_credentials_nonce' ] ) && wp_verify_nonce( $_POST[ 'update_api_credentials_nonce' ], 'update_api_credentials_action' ) ){
			$old_api_key = $fmc_settings[ 'api_key' ];
			$old_api_secret = $fmc_settings[ 'api_secret' ];

			$new_api_key = sanitize_text_field( $_POST[ 'fmc_settings' ][ 'api_key' ] );
			$new_api_secret = sanitize_text_field( $_POST[ 'fmc_settings' ][ 'api_secret' ] );

			$fmc_settings[ 'api_key' ] = $new_api_key;
			$fmc_settings[ 'api_secret' ] = $new_api_secret;
			update_option( 'fmc_settings', $fmc_settings );

			$SparkAPI = new \SparkAPI\Core();
			$SparkAPI->clear_cache( true );
			$auth_token = $SparkAPI->generate_auth_token();
			if( $auth_token ){
				add_action( 'admin_notices', array( '\FlexMLS\Admin\Settings', 'did_update_settings' ) );
			}
		}

		// User clears cache
		if( !empty( $_POST ) && isset( $_POST[ 'clear_api_cache_nonce' ] ) && wp_verify_nonce( $_POST[ 'clear_api_cache_nonce' ], 'clear_api_cache_action' ) ){
			$SparkAPI = new \SparkAPI\Core();
			$SparkAPI->clear_cache( true );
			$auth_token = $SparkAPI->generate_auth_token();
			if( $auth_token ){
				add_action( 'admin_notices', array( '\FlexMLS\Admin\Settings', 'did_clear_cache' ) );
			}
		}

		// User saves Behavior settings
		if( !empty( $_POST ) && isset( $_POST[ 'update_fmc_behavior_nonce' ] ) && wp_verify_nonce( $_POST[ 'update_fmc_behavior_nonce' ], 'update_fmc_behavior_action' ) ){
			$do_flush_rewrites = false;
			$old_permabase = $fmc_settings[ 'permabase' ];
			foreach( $_POST[ 'fmc_settings' ] as $key => $val ){
				switch( $key ){
					case 'default_titles':
					case 'contact_notifications':
					case 'multiple_summaries':
					case 'allow_sold_searching':
						// Simple 1 or 0 values
						$fmc_settings[ $key ] = ( 1 == $val ? 1 : 0 );
						break;
					case 'neigh_template':
					case 'listlink':
					case 'destlink':
						// Numeric values like post ids
						$fmc_settings[ $key ] = preg_replace( '/[^0-9]/', '', $val );
						break;
					case 'default_link':
					case 'destwindow':
                    case 'select2_turn_off':
					case 'destpref':
					case 'listpref':
					case 'permabase':
						// Text input values
						$fmc_settings[ $key ] = sanitize_text_field( $val );
						break;
					case 'property_types':
						// Special Case: Property Types
						$val = sanitize_text_field( $val );
						$fmc_settings[ $key ] = $val;
						$types = explode( ',', $val );
						foreach( $types as $type ){
							$fmc_settings[ 'property_type_label_' . $type ] = sanitize_text_field( $_POST[ 'fmc_settings' ][ 'property_type_label_' . $type ] );
						}
						break;
					case 'search_results_fields':
						// Special Case: Search Results
						$clean_fields = array();
						foreach( $val as $sr_key => $sr_val ){
							$clean_fields[ sanitize_text_field( $sr_key ) ] = sanitize_text_field( $sr_val );
						}
						$fmc_settings[ 'search_results_fields' ] = $clean_fields;
				}
			}
			if( !isset( $_POST[ 'fmc_settings' ][ 'destlink' ] ) ){
				$fmc_settings[ 'destlink' ] = 0;
			}
            if( !isset( $_POST[ 'fmc_settings' ][ 'select2_turn_off' ] ) ){
                $fmc_settings[ 'select2_turn_off' ] = 0;
            }
			if( !isset( $_POST[ 'fmc_settings' ][ 'destwindow' ] ) ){
				$fmc_settings[ 'destwindow' ] = '';
			}
			if( empty( $fmc_settings[ 'permabase' ] ) ){
				$fmc_settings[ 'permabase' ] = 'idx';
			}
			if( $old_permabase != $fmc_settings[ 'permabase' ] ){
				add_action( 'shutdown', 'flush_rewrite_rules' );
			}
			add_action( 'admin_notices', array( '\FlexMLS\Admin\Settings', 'did_update_settings' ) );
		}


		// User saves style settings
		if( !empty( $_POST ) && isset( $_POST[ 'update_fmc_style_nonce' ] ) && wp_verify_nonce( $_POST[ 'update_fmc_style_nonce' ], 'update_fmc_style_action' ) ){
			foreach( $_POST[ 'fmc_settings' ] as $key => $val ){
				switch( $key ){
					case 'search_listing_template_version':
					case 'search_listing_template_primary_color':
					case 'search_listing_template_heading_font':
					case 'search_listing_template_body_font':
						// Text input values
						$fmc_settings[ $key ] = sanitize_text_field( $val );
						break;
				}
			}
			add_action( 'admin_notices', array( '\FlexMLS\Admin\Settings', 'did_update_settings' ) );
		}

		// User saves Oauth/Portal settings
		if( !empty( $_POST ) && isset( $_POST[ 'update_fmc_portal_nonce' ] ) && wp_verify_nonce( $_POST[ 'update_fmc_portal_nonce' ], 'update_fmc_portal_action' ) ){
			foreach( $_POST[ 'fmc_settings' ] as $key => $val ){
				switch( $key ){
					case 'portal_carts':
					case 'portal_saving_searches':
					case 'portal_search':
					case 'portal_listing':
					case 'portal_force':
						$fmc_settings[ $key ] = ( 1 == $val ? 1 : 0 );
						break;
					case 'portal_mins':
					case 'detail_page':
					case 'search_page':
						$fmc_settings[ $key ] = preg_replace( '/[^0-9]/', '', $val );
						break;
					case 'oauth_key':
					case 'oauth_secret':
					case 'portal_position_x':
					case 'portal_position_y':
						$fmc_settings[ $key ] = sanitize_text_field( $val );
						break;
					case 'portal_text':
						$fmc_settings[ $key ] = wp_kses_post( $val );
						break;
				}
			}
			if( !isset( $_POST[ 'fmc_settings' ][ 'portal_carts' ] ) ){
				$fmc_settings[ 'portal_carts' ] = 0;
			}
			if( !isset( $_POST[ 'fmc_settings' ][ 'portal_saving_searches' ] ) ){
				$fmc_settings[ 'portal_saving_searches' ] = 0;
			}
			if( !isset( $_POST[ 'fmc_settings' ][ 'portal_search' ] ) ){
				$fmc_settings[ 'portal_search' ] = 0;
			}
			if( !isset( $_POST[ 'fmc_settings' ][ 'portal_listing' ] ) ){
				$fmc_settings[ 'portal_listing' ] = 0;
			}
			if( !isset( $_POST[ 'fmc_settings' ][ 'portal_force' ] ) ){
				$fmc_settings[ 'portal_force' ] = 0;
			}
			add_action( 'admin_notices', array( '\FlexMLS\Admin\Settings', 'did_update_settings' ) );
		}

		// User saves Google settings
		if( !empty( $_POST ) && isset( $_POST[ 'update_google_maps_nonce' ] ) && wp_verify_nonce( $_POST[ 'update_google_maps_nonce' ], 'update_google_maps_action' ) ){
			$fmc_settings[ 'google_maps_api_key' ] = sanitize_text_field( $_POST[ 'fmc_settings' ][ 'google_maps_api_key' ] );
			$fmc_settings[ 'map_height' ] = sanitize_text_field( $_POST[ 'fmc_settings' ][ 'map_height' ] );
			$fmc_settings[ 'google_maps_no_enqueue' ] = ( isset( $_POST[ 'fmc_settings' ][ 'google_maps_no_enqueue' ] ) ? 1 : 0 );
			add_action( 'admin_notices', array( '\FlexMLS\Admin\Settings', 'did_update_settings' ) );
		}

		update_option( 'fmc_settings', $fmc_settings );
	}

}
