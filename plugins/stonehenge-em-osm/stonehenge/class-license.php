<?php
if( !defined('ABSPATH') ) exit;

if( !class_exists('Stonehenge_License')) :
Class Stonehenge_License extends Stonehenge_PDF {

	var $license;


	#===============================================
	public function is_valid( $plugin ) {
		$base 		 	= $plugin['base'];
		$license 		= get_site_option( "{$base}_license" );
		$is_valid 		= !$this->is_licensed ? true : (is_array($license) && isset($license['license']) && $license['license'] === 'valid' ? true : false);
		$this->license  = $license;
		return $is_valid;
	}


	#===============================================
	public function license_server( $license_key, $method, $plugin ) {
		$parameters = array(
			'edd_action'	=> $method,
			'item_name'		=> $plugin['short'],
			'license' 		=> trim($license_key),
			'url'			=> $_SERVER['SERVER_NAME'],
		);
		$remote = wp_remote_post( STONEHENGE, array( 'timeout' => 25, 'sslverify' => false, 'body' => $parameters ) );

		if( $remote['response']['code'] != 200 ) {
			return array( 'error' => 'Something went wrong fetching the license info.' );
		}

	    $license_data = json_decode( wp_remote_retrieve_body( $remote ) , true );
		return $license_data;
	}


	#===============================================
	public function activate_license( $license_key, $plugin ) {
		$license_data 	= $this->license_server( $license_key, 'activate_license', $plugin );
		$check_data 	= $this->check_license( $license_key, $plugin );
		return;
	}


	#===============================================
	public function deactivate_license( $license_key, $plugin ) {
		$license_data 	= $this->license_server( $license_key, 'deactivate_license', $plugin );
		delete_site_option( $plugin['base'].'_license' );
		return;
	}


	#===============================================
	public function check_license( $license_key, $plugin ) {
		$license_data 	= $this->license_server( $license_key, 'check_license', $plugin );
		$save_data 		= $this->process_license_data( $license_data, $license_key, $plugin );
		return $license_data;
	}


	#===============================================
	public function validate_license( $plugin ) {
		$license = get_site_option( $plugin['base'].'_license' );
		if( $this->is_licensed && is_array($license) ) {
			$license_key	= $license['license_key'];
			$license_data 	= $this->license_server( $license_key, 'check_license', $plugin );
			$save_data 		= $this->process_license_data( $license_data, $license_key, $plugin );
		}
		return;
	}


	#===============================================
	private function delete_license( $plugin ) {
		$base 		= $plugin['base'];
		$file 		= "{$base}/{$base}.php";
		$active		= get_site_option('active_plugins', array());

		$flipped 	= array_flip($active);
		unset( $flipped[$file] );
		$current 	= array_flip($flipped);

		update_site_option('active_plugins', $current);
		delete_site_option("{$base}_license");
		echo '<meta http-equiv="refresh" content="0; URL='. admin_url() .'">';
	}


	#===============================================
	public function process_remote() {
		if( isset($_REQUEST['action']) && $_REQUEST['action'] === 'scl_disabled' ) {
			$key 		= sanitize_text_field( $_REQUEST['key'] );
			$base 		= sanitize_text_field( $_REQUEST['slug'] );
			$function 	= str_replace('-', '_', $base );
			$plugin 	= $function();
			$license 	= get_site_option("{$base}_license");

			if( isset($license['license_key']) &&  $license['license_key'] === $key ) {
				$this->check_license( $key, $plugin );
			}
		}
	}


	#===============================================
	private function process_license_data( $license_data, $license_key, $plugin ) {
		$new_data 	= array();
		$data 		= array( 'success' => '', 'license' => '', 'expires' => '', 'customer_name' => '', 'customer_email' => '', 'customer_full' => '', 'license_limit' => '', 'site_count' => '', 'activations_left' => '', 'item_id' => '', 'item_name' => '', 'error' => '');

		$new_data = wp_parse_args( $license_data, $data );
		$new_data['license_key'] = $license_key;

		if( !add_site_option( $plugin['base'].'_license' , $new_data, '', 'no' ) ) {
			update_site_option( $plugin['base'].'_license' , $new_data, 'no' );
		}
		return $new_data;
	}


	#===============================================
	public function renewal_url( $plugin ) {
		$url = esc_url( add_query_arg( array(
			'edd_license_key' => trim( sanitize_text_field( $this->license['license_key'] ) ),
			'download' => trim( sanitize_text_field( $plugin['short'] ) )
		), STONEHENGE .'checkout') );
		return $url;
	}


	#===============================================
	public function show_license( $plugin ) {
		$text 			= $plugin['text'];
		$license 		= $this->license;
		$show 			= $this->is_valid ? 'closed' : '';
		$is_allowed 	= current_user_can('install_plugins') ? true : false;
		$not_allowed	= $this->show_notice( __('Sorry, you are not allowed to manage plugins for this site.'), 'error');

		if( isset($_POST['activate_license']) ) {
			$license_key = sanitize_text_field( $_POST['license_key'] );
			$this->activate_license( $license_key, $plugin );
			echo '<meta http-equiv="refresh" content="0">';
		}

		if( isset($_POST['check_license']) ) {
			$this->check_license( esc_attr($license['license_key']), $plugin );
			echo '<meta http-equiv="refresh" content="0">';
		}

		if( isset($_POST['deactivate_license']) ) {
			$this->deactivate_license( esc_attr($license['license_key']), $plugin );
			echo '<meta http-equiv="refresh" content="0">';
		}

		if( isset($_POST['delete_license']) ) {
			$this->delete_license( $plugin );
		}

		?>
		<div class="stonehenge-metabox" id="items-metabox">
			<div class="stonehenge-metabox-header">
				<h3 class="handle"><?php echo esc_html__('License', $text); ?></h3>
			</div>
			<section class="stonehenge-section" id="license">
				<table class="stonehenge-table">
					<form action="" method="post">
						<?php
						// New install.
						if( !is_array($license) ) {
							echo '<tr>';
							echo 	'<th>License Status:</th>';
							echo 	'<td>Please enter your license key.</td>';
							echo '</tr><tr>';
							echo 	'<th>License Key:</th>';
							echo 	'<td><input type="text" name="license_key" value="" class="regular-text"></td>';
							echo '</tr><tr><th></th><td>';
							echo $is_allowed ? '<input type="submit" name="activate_license" value="Activate License Key" class="button-primary">' : $not_allowed;
							echo '</td></tr>';
						}
						else {
							$status 	= $license['license'];
							$state 		= !empty($license['license']) ? str_replace('_', ' ' , ucfirst($license['license'])) : $license['error'];
							$class 		= $status != 'valid' ? 'stonehenge-error' : 'stonehenge-success';
							$type 		= $license['license_limit'] === 0 ? 'Unlimited ' : $license['license_limit'] .'-';

							$expiry 	= $this->localize_date($license['expires'], 'long');
							$days_left 	= $this->localize_date_difference($license['expires'], true);

							if( $license['expires'] === 'lifetime' ) {
								$expiry = ucfirst($license['expires']);
								$days_left = $expiry;
							}

							$renew_url 	= esc_url_raw( $this->renewal_url($plugin) );
							$profile  	= esc_url_raw( STONEHENGE . 'account' );
							$site_count = $license['site_count'] ?? '0';

							echo '<tr>';
							echo 	'<th>License Status:</th>';
							echo 	'<td><span class="'. esc_attr($class, ENT_QUOTES) .'">'. $state .'</span></td>';
							echo '</tr><tr>';
							echo 	'<th>License Key:</th>';
							echo 	'<td>';

							switch( $status ) {
								case 'valid':
									echo '<strong>'. $this->mask($license['license_key']) .'</strong></td></tr>';
									if( $is_allowed ) {
										echo '<tr><th>License Type:</th><td>'. esc_html($type) .'Install License ';
										echo '('. esc_html($site_count) .' used)';
										echo '</td></tr>';
										echo '<tr><th>Expires on:</th>';
										echo '<td>'. esc_html($expiry) .' ('. esc_html($days_left) .')</td>';
										echo '</tr><tr><th></th>';
										echo 	'<td><input type="submit" name="check_license" value="Sync Data" class="button-primary">&nbsp;&nbsp;<a href="'. $profile .'" target="_blank" class="button-secondary">Manage Account</a>&nbsp;&nbsp;<input type="submit" name="deactivate_license" value="Deactivate License" class="button-secondary">';
										echo 	'</td>';
									}
									else {
										echo '<td colspan=2">' . $not_allowed . '</td>';
									}
									echo '</tr>';
								break;

								case 'expired':
									echo '<span class="red">Expired on '. esc_html($expiry) .'.</span></td></tr>';
									echo '<tr><th></th><td>';
									echo $is_allowed ? '<a href="'. $renew_url .'" target="_blank"><button type="button" class="button-primary">Renew License</button></a>' : $not_allowed;
									echo '</td></tr>';
								break;

								case 'disabled':
									echo 'This license key has been administratively disabled.<br>Please deactivate and delete this plugin.</td></tr>';
									echo '<tr><th></th><td>';
									echo $is_allowed ? '<input type="submit" name="delete_license" value="Continue" class="button-primary">' : $not_allowed;
									echo '</td></tr>';
								break;

								case 'invalid':
								default:
									echo '<input type="text" name="license_key" id="license_key" value="'. esc_attr(@$license['license_key']) .'" class="regular-text"></td></tr>';
									echo '<tr><th></th><td>';
									echo $is_allowed ? '<input type="submit" name="activate_license" value="Activate" class="button-primary">' : $not_allowed;
									echo '</td></tr>';
								break;
							} // End switch.
						}
						?>
					</form>
				</table>
			</section>
		</div>
		<br style="clear:both;">
		<?php
	}

} // End class.
endif;


