<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Our main class
 *
 */
final class CTT_Tracking {

	/* Internal variables */
	protected $ctt_url      = 'https://www.ctt.pt/feapl_2/app/open/objectSearch/objectSearch.jspx';
	//protected $ctt_url_more = 'https://www.ctt.pt/feapl_2/app/open/objectSearch/objectSearch.jspx';
	protected $ctt_url_more = 'https://appserver.ctt.pt/CustomerArea/PublicArea_Detail?IsFromPublicArea=true&ObjectCodeInput=%s&SearchInput=%s';
	protected $wpml_active  = false;
	protected $locale       = false;
	protected $hpos_enabled = false;

	/* Single instance */
	protected static $_instance = null;

	/* Constructor */
	public function __construct() {
		$this->wpml_active = function_exists( 'icl_object_id' ) && function_exists( 'icl_register_string' );
		if ( version_compare( WC_VERSION, '7.1', '>=' ) ) {
			if ( wc_get_container()->get( \Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled() ) {
				$this->hpos_enabled = true;
			}
		}
		$this->init_hooks();
	}

	/* Ensures only one instance of our plugin is loaded or can be loaded */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/* Hooks */
	private function init_hooks() {
		add_action( 'add_meta_boxes', array( $this, 'shop_order_add_meta_boxes' ) );
		add_action( 'woocommerce_process_shop_order_meta', array( $this, 'save_tracking_field' ) );
		add_action( 'woocommerce_order_details_after_order_table', array( $this, 'ctt_tracking_order_details' ) );
		switch( get_option( 'ctt_tracking_email_link_position' ) ) {
			case 'before_order_table':
				add_action( 'woocommerce_email_before_order_table', array( $this, 'ctt_tracking_email_details' ), 10, 3 );
				break;
			case 'after_order_table':
				add_action( 'woocommerce_email_after_order_table', array( $this, 'ctt_tracking_email_details' ), 10, 3 );
				break;
			default:
				add_action( 'woocommerce_email_customer_details', array( $this, 'ctt_tracking_email_details' ), 30, 3 );
				break;
		}
		if ( is_admin() && !wp_doing_ajax() ) {
			add_filter( 'woocommerce_shipping_settings', array( $this, 'woocommerce_shipping_settings' ), PHP_INT_MAX );
			add_action( 'woocommerce_admin_field_ctt_tracking_title', array( $this, 'woocommerce_admin_field_ctt_tracking_title' ) );
		}
		//Let 3rd party update tracking number for an order
		add_action( 'portugal_ctt_tracking_set_tracking_code', array( $this, 'ctt_tracking_set_tracking_code_for_order' ), 10, 2 );
		//Let 3rd party update tracking information from CTT for an order
		add_action( 'portugal_ctt_tracking_update_info_for_order', array( $this, 'ctt_tracking_get_info_for_order' ), 10, 1 );
	}

	/* Add settings link to plugin actions */
	function add_settings_link( $links ) {
		$action_links = array(
			'ctt_tracking_settings' => '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=shipping&section=options#ctt_tracking' ) . '">' . __( 'Settings', 'portugal-ctt-tracking-woocommerce' ) . '</a>',
		);
		return array_merge( $action_links, $links );
	}

	//Plugin settings
	function woocommerce_shipping_settings( $settings ) {
		$updated_settings = array();
		foreach ( $settings as $section ) {
			if ( isset( $section['id'] ) && 'shipping_options' == $section['id'] && isset( $section['type'] ) && 'sectionend' == $section['type'] ) {
				$updated_settings[] = array( 
					'title'		=> __( 'Portugal CTT Tracking', 'portugal-ctt-tracking-woocommerce' ),
					'type'		=> 'ctt_tracking_title',
					'id'		=> 'shipping_options_ctt_tracking',
				);
				$updated_settings[] = array( 
					'title'		=> __( 'Email link target', 'portugal-ctt-tracking-woocommerce' ),
					'type'		=> 'select',
					'options'   => array(
						'website' => __( 'Order details on the shop', 'portugal-ctt-tracking-woocommerce' ),
						'ctt'     => __( 'Tracking details at ctt.pt', 'portugal-ctt-tracking-woocommerce' ),
						'none'    => __( 'No link', 'portugal-ctt-tracking-woocommerce' ),
					),
					'desc'      => __( 'The link type you want to show on the email information sent to the client', 'portugal-ctt-tracking-woocommerce' ),
					'desc_tip'  => true,
					'id'		=> 'ctt_tracking_email_link_type',
				);
				$updated_settings[] = array( 
					'title'		=> __( 'Email link position', 'portugal-ctt-tracking-woocommerce' ),
					'type'		=> 'select',
					'options'   => array(
						'before_order_table' => __( 'Top - Before order table', 'portugal-ctt-tracking-woocommerce' ),
						'after_order_table'  => __( 'Middle - After order table', 'portugal-ctt-tracking-woocommerce' ),
						''                   => __( 'Bottom - After customer details', 'portugal-ctt-tracking-woocommerce' ),
					),
					'desc'      => __( 'The link position on the email information sent to the client', 'portugal-ctt-tracking-woocommerce' ),
					'desc_tip'  => true,
					'id'		=> 'ctt_tracking_email_link_position',
				);
				$updated_settings[] = array( 
					'title'		=> __( 'Allow users to update info', 'portugal-ctt-tracking-woocommerce' ),
					'type'		=> 'select',
					'options'   => array(
						''     => __( 'No', 'portugal-ctt-tracking-woocommerce' ),
						'yes'  => __( 'Yes', 'portugal-ctt-tracking-woocommerce' ),
					),
					'desc'      => __( 'Allow users to update CTT tracking information at the order details on their account', 'portugal-ctt-tracking-woocommerce' ),
					'desc_tip'  => true,
					'id'		=> 'ctt_tracking_allow_users_update',
				);
			}
			$updated_settings[] = $section;
		}
		return $updated_settings;
	}
	function woocommerce_admin_field_ctt_tracking_title( $value ) {
		?>
		<tr valign="top">
			<td colspan="2" style="padding: 0px;">
				<?php echo '<a name="ctt_tracking"></a><h2>' . esc_html( $value['title'] ) . '</h2>'; ?>
			</td>
		</tr>
		<?php
	}

	/* Build "more" URL */
	function build_more_url( $ctt_tracking_code ) {
		return sprintf(
			$this->ctt_url_more,
			$ctt_tracking_code,
			$ctt_tracking_code
		);
	}

	/* Add tracking field and information to order */
	function shop_order_add_meta_boxes() {
		$screen = $this->hpos_enabled ? wc_get_page_screen_id( 'shop-order' ) : 'shop_order';
		add_meta_box(
			'ctt-tracking',
			__( 'Portugal CTT Tracking', 'portugal-ctt-tracking-woocommerce' ),
			array( $this, 'shop_order_add_meta_boxes_html' ),
			$screen,
			'normal',
			'default'
		);
	}
	function shop_order_add_meta_boxes_html( $post_or_order_object ) {
		$order_object = ( $post_or_order_object instanceof WP_Post ) ? wc_get_order( $post_or_order_object->ID ) : $post_or_order_object;
		$ctt_tracking_code = $order_object->get_meta( '_ctt_tracking_code' );
		wp_nonce_field( '_ctt_tracking_nonce', 'ctt_tracking_nonce' );
		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th>
						<label for="ctt_tracking_code"><?php _e( 'Tracking code', 'portugal-ctt-tracking-woocommerce' ); ?></label>
					</th>
					<td>
						<input type="text" name="ctt_tracking_code" id="ctt_tracking_code" value="<?php echo esc_attr( $ctt_tracking_code ); ?>">
					</td>
				</tr>
				<tr>
					<th>
						<label for="ctt_tracking_code"><?php _e( 'Information', 'portugal-ctt-tracking-woocommerce' ); ?></label>
					</th>
					<td>
						<?php $this->tracking_information_table( $order_object->get_id(), 'admin' ); ?>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
	}
	function save_tracking_field( $post_or_order_id ) {
		if ( ! isset( $_POST['ctt_tracking_nonce'] ) || ! wp_verify_nonce( $_POST['ctt_tracking_nonce'], '_ctt_tracking_nonce' ) ) return;
		$order_object = wc_get_order( $post_or_order_id );
		$update_info = false;
		//Tracking code
		if ( isset( $_POST['ctt_tracking_code'] ) ) {
			if ( trim( $_POST['ctt_tracking_code'] ) != $order_object->get_meta( '_ctt_tracking_code' ) ) $update_info = true;
			$order_object->update_meta_data( '_ctt_tracking_code', trim( stripslashes( sanitize_text_field( $_POST['ctt_tracking_code'] ) ) ) );
		}
		$order_object->save();
		//Tracking info
		if ( isset( $_POST['ctt_tracking_info_force_update'] ) && intval( $_POST['ctt_tracking_info_force_update'] ) == 1 ) $update_info = true;
		if ( $update_info ) {
			$this->ctt_tracking_get_info_for_order( $order_object->get_id() );
		}
	}

	/* Show tracking information table */
	function tracking_information_table( $order_id, $context = 'user' ) {
		if ( $context == 'admin' ) { ?>
			<style type="text/css">
			@media screen and (min-width: 783px) {
				#ctt-tracking .form-table th {
					width: 150px;
				}
			}
			#ctt-tracking-information p {
				margin-bottom: 1em;
			}
			#ctt-tracking-information table {
				border-collapse: collapse;
				width: 100%;
				margin-bottom: 1em;
			}
			#ctt-tracking-information table th,
			#ctt-tracking-information table td {
				vertical-align: top;
				text-align: left;
				padding: 0.5em;
				margin: 0px;
				width: auto !important;
				line-height: normal;
				font-size: 0.8em;
				border: 1px solid #f1f1f1;
			}
			#ctt-tracking-information table th {
				font-weight: 600;
				background-color: #f1f1f1;
			}
			</style>
			<?php
		}
		?>
		<a name="ctt-tracking-information"></a>
		<div id="ctt-tracking-information">
			<?php
			$order_object      = wc_get_order( $order_id );
			$ctt_tracking_code = $order_object->get_meta( '_ctt_tracking_code' );
			//Get tracking info from the database			
			$ctt_tracking_info = $order_object->get_meta( '_ctt_tracking_info' );
			//Force update tracking info? - Has info, has last update, filter true and status not final
			if ( $context == 'user' && isset( $_POST ) && isset( $_POST['ctt_tracking_info_force_update'] ) && intval( $_POST['ctt_tracking_info_force_update'] ) == 1 && get_option( 'ctt_tracking_allow_users_update' ) == 'yes' ) {
				$ctt_tracking_info = $this->ctt_tracking_get_info_for_order( $order_object->get_id() );
				?>
				<script>
					jQuery(document).ready(function($) {
						$( 'html, body' ).animate({
							scrollTop: $( '#ctt-tracking-information' ).offset().top
						}, 200);
					});
				</script>
				<?php
			} elseif ( $ctt_tracking_info && $ctt_tracking_info['status'] && $ctt_tracking_info['last_update'] && apply_filters( 'portugal_ctt_tracking_auto_update_info', true ) && ! $ctt_tracking_info['info']['status_final'] ) {
				$date1 = strtotime( date_i18n( 'Y-m-d H:i' ) );  
				$date2 = strtotime( $ctt_tracking_info['last_update'] ); 
				$diff  = abs($date2 - $date1);
				//More than 4 hours?
				if ( $diff > intval( intval( apply_filters( 'portugal_ctt_tracking_auto_update_hours', 4 ) ) * 60 * 60 ) ) {
					$ctt_tracking_info = $this->ctt_tracking_get_info_for_order( $order_object->get_id() );
				}
			}
			if ( ! $ctt_tracking_code ) {
				?>
				<p><strong><?php _e( 'CTT tracking code not available', 'portugal-ctt-tracking-woocommerce' ); ?></strong></p>
				<?php
			} else {
				if ( ! $ctt_tracking_info ) {
					?>
					<p>
						<strong><?php _e( 'Tracking code', 'portugal-ctt-tracking-woocommerce' ); ?>:</strong>
						<?php echo $ctt_tracking_code; ?>
					</p>
					<p><strong><?php _e( 'CTT tracking information not available', 'portugal-ctt-tracking-woocommerce' ); ?></strong></p>
					<?php
					if ( $context == 'user' && get_option( 'ctt_tracking_allow_users_update' ) == 'yes' ) {
						$this->tracking_information_table_update_button_public();
					}
				} else {
					if ( ! $ctt_tracking_info['status'] ) {
						if ( $context == 'admin' ) {
							?>
							<p><strong><?php echo $ctt_tracking_info['message'] ?></strong></p>
							<?php
						} else {
							?>
							<p><strong><?php _e( 'CTT tracking information not available', 'portugal-ctt-tracking-woocommerce' ); ?></strong></p>
							<?php
						}
					} else {
						?>
						<p>
							<strong><?php _e( 'Tracking code', 'portugal-ctt-tracking-woocommerce' ); ?>:</strong>
							<?php echo $ctt_tracking_code; ?>
							<br/>
							<strong><?php _e( 'Status', 'portugal-ctt-tracking-woocommerce' ); ?>:</strong>
							<?php echo $ctt_tracking_info['info']['status']; ?>
							<small>(<?php echo $ctt_tracking_info['info']['date']; ?> <?php echo $ctt_tracking_info['info']['time']; ?>)</small>
						</p>
						<?php if ( count( $ctt_tracking_info['info']['events'] ) > 0 ) { ?>
							<table>
								<thead>
									<tr>
										<th><?php _e( 'Date', 'portugal-ctt-tracking-woocommerce' ); ?></th>
										<th><?php _e( 'Hour', 'portugal-ctt-tracking-woocommerce' ); ?></th>
										<th><?php _e( 'Status', 'portugal-ctt-tracking-woocommerce' ); ?></th>
										<th><?php _e( 'Reason', 'portugal-ctt-tracking-woocommerce' ); ?></th>
										<th><?php _e( 'Place', 'portugal-ctt-tracking-woocommerce' ); ?></th>
										<th><?php _e( 'Receiver', 'portugal-ctt-tracking-woocommerce' ); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php
									foreach ( $ctt_tracking_info['info']['events'] as $date => $events ) {
										foreach ( $events as $event ) {
											?>
											<tr>
												<td class="ctt-tracking-information-table-date" style="white-space: nowrap;"><?php echo $date; ?></td>
												<td class="ctt-tracking-information-table-hour" style="white-space: nowrap;"><?php echo $event['time']; ?></td>
												<td class="ctt-tracking-information-table-status"><?php echo $event['status']; ?></td>
												<td class="ctt-tracking-information-table-reason"><?php echo $event['reason']; ?></td>
												<td class="ctt-tracking-information-table-place"><?php echo ucwords( strtolower( $event['place'] ) ); ?></td>
												<td class="ctt-tracking-information-table-receiver"><?php echo ucwords( strtolower( $event['receiver'] ) ); ?></td>
											</tr>
											<?php
										}
									}
									?>
								</tbody>
							</table>
						<?php }
					}
					?>
					<p>
						<small>
							<?php _e( 'Last update', 'portugal-ctt-tracking-woocommerce' ); ?>:
							<?php echo $ctt_tracking_info['last_update']; ?>
							-
							<a href="<?php echo esc_url( $this->build_more_url( $ctt_tracking_code ) ); ?>" target="_blank"><?php _e( 'Check out the latest available information at ctt.pt', 'portugal-ctt-tracking-woocommerce' ); ?></a>
						</small>
					</p>
					<?php
					if ( $context == 'user' && get_option( 'ctt_tracking_allow_users_update' ) == 'yes' ) {
						$this->tracking_information_table_update_button_public();
					}
				}
			}
			if ( $context == 'admin' ) {
				$this->tracking_information_table_update_button();
			}
			?>
		</div>
		<?php
	}
	function tracking_information_table_update_button() {
		?>
		<p>
			<input type="hidden" id="ctt_tracking_info_force_update" name="ctt_tracking_info_force_update" value="0"/>
			<button type="button" class="button button-primary" id="ctt_tracking_info_force_update_button"><?php _e( 'Update code and information', 'portugal-ctt-tracking-woocommerce' ); ?></button>
			<script type="text/javascript">
			jQuery( document ).ready( function( $ ) {
				$( '#ctt_tracking_info_force_update_button' ).on( 'click', function() {
					$( '#ctt_tracking_info_force_update' ).val( '1' );
					$( '#post' ).trigger('submit');  //Non-HPOS
					$( '#order' ).trigger('submit'); //HPOS
				} );
			} );
			</script>
		</p>
		<?php
	}
	function tracking_information_table_update_button_public() {
		?>
		<form action="" method="post">
			<input type="hidden" id="ctt_tracking_info_force_update" name="ctt_tracking_info_force_update" value="1"/>
			<input type="submit" class="button" value="<?php echo esc_attr( __( 'Update CTT tracking information', 'portugal-ctt-tracking-woocommerce' ) ); ?>"/>
		</form>
		<?php
	}

	/* CTT tracking information on the order details page */
	function ctt_tracking_order_details( $order_object ) {
		if ( $ctt_tracking_code = $order_object->get_meta( '_ctt_tracking_code' ) ) {
			?>
			<a name="ctt_tracking"></a>
			<h2><?php _e( 'CTT Tracking', 'portugal-ctt-tracking-woocommerce' ); ?></h2>
			<?php
			$this->tracking_information_table( $order_object->get_id() );
		}
	}

	/* CTT tracking information on the email */
	function ctt_tracking_email_details( $order_object, $sent_to_admin = false, $plain_text = false ) {
		ob_start();
		$this->maybe_change_locale( $order_object );
		if ( $ctt_tracking_code = $order_object->get_meta( '_ctt_tracking_code' ) ) {
			switch( get_option( 'ctt_tracking_email_link_type' ) ) {
				case 'none':
					$link      = false;
					$link_text = '';
					break;
				case 'ctt':
					$link      = $this->build_more_url( $ctt_tracking_code );
					$link_text = __( 'More details at ctt.pt', 'portugal-ctt-tracking-woocommerce' );
					break;
				case 'website':
				default:
					$link      = $order_object->get_view_order_url().'#ctt_tracking';
					$link_text = __( 'More details on our website', 'portugal-ctt-tracking-woocommerce' );
					break;
			}
			if ( $plain_text ) {
				//Not done yet
				echo "\n" . strtoupper( __( 'CTT Tracking', 'portugal-ctt-tracking-woocommerce' ) ) . "\n";
				echo __( 'Tracking code', 'portugal-ctt-tracking-woocommerce' ).': '.$ctt_tracking_code . "\n";
				if ( $link ) {
					echo $link_text.': '.$link . "\n";
				}
			} else {
				?>
				<h3><?php _e( 'CTT Tracking', 'portugal-ctt-tracking-woocommerce' ); ?></h3>
				<p>
					<strong><?php _e( 'Tracking code', 'portugal-ctt-tracking-woocommerce' ); ?>:</strong>
					<?php echo $ctt_tracking_code; ?>
					<?php if ( $link ) { ?>
						<br/>
						<small>
							<a href="<?php echo esc_url( $link ); ?>" target="blank"><?php echo $link_text; ?></a>
						</small>
					<?php } ?>
				</p>
				<?php
			}
		}
		echo apply_filters( 'portugal_ctt_tracking_email_info', ob_get_clean(), $order_object, $sent_to_admin, $plain_text );
	}

	/* Maybe change locale */
	public function maybe_change_locale( $order_object ) {
		if ( $this->wpml_active ) {
			//Just for WPML
			global $sitepress;
			if ( $sitepress ) {
				$lang = $order_object->get_meta( 'wpml_language' );
				if ( ! empty( $lang ) ) {
					$this->locale = $sitepress->get_locale( $lang );
				}
			}
		} else {
			//Store language != current user/admin language?
			if ( is_admin() ) {
				$current_user_lang = get_user_locale( wp_get_current_user() );
				if ( $current_user_lang != get_locale() ) {
					$this->locale = get_locale();
				}
			}
		}
		if ( ! empty ( $this->locale ) ) {
			//Unload
			unload_textdomain( 'portugal-ctt-tracking-woocommerce' );
			add_filter( 'plugin_locale', array( $this, 'set_locale_for_emails' ), 10, 2 );
			load_plugin_textdomain( 'portugal-ctt-tracking-woocommerce' );
			remove_filter( 'plugin_locale', array( $this, 'set_locale_for_emails' ), 10, 2 );
		}
	}
	public function set_locale_for_emails( $locale, $domain ) {
		if ( $domain == 'portugal-ctt-tracking-woocommerce' && $this->locale ) {
			$locale = $this->locale;
		}
		return $locale;
	}

	/* Get information from CTT Website */
	function ctt_tracking_get_info_body( $ctt_tracking_code ) {
		//POST
		$response = wp_remote_post( $this->ctt_url, array(
			'body' => array(
				'showResults' => 'true',
				'objects'     => $ctt_tracking_code,
			),
		) );
		//OK?
		if ( is_wp_error( $response ) ) {
			return array(
				'status'  => false,
				'message' => __( 'Error getting tracking information', 'portugal-ctt-tracking-woocommerce' ) . ': ' . $response->get_error_message()
			);
		} else {
			//200?
			if ( wp_remote_retrieve_response_code( $response ) != 200 ) {
				return array(
					'status'  => false,
					'message' => __( 'Error getting tracking information', 'portugal-ctt-tracking-woocommerce' ) . ': ' . wp_remote_retrieve_response_message( $response )
				);
			} else {
				$body = wp_remote_retrieve_body( $response );
				//Some basic tests
				if ( trim( $body ) != '' && stristr( $body, 'objectSearchResult' ) ) {
					return array(
						'status' => true,
						'body'   => trim( $body ),
					);
				} else {
					return array(
						'status'  => false,
						'message' => __( 'Error getting tracking information', 'portugal-ctt-tracking-woocommerce' ) . ': ' . __( 'HTML not found', 'portugal-ctt-tracking-woocommerce' )
					);
				}
			}
		}
	}

	/* Update tracking code on order - for 3rd party usage */
	function ctt_tracking_set_tracking_code_for_order( $order_id, $ctt_tracking_code ) {
		if ( $order_object = wc_get_order( $order_id ) ) {
			$order_object->update_meta_data( '_ctt_tracking_code', $ctt_tracking_code );
			$order_object->save();
			//Update Tracking info
			$this->ctt_tracking_get_info_for_order( $order_object->get_id() );
		}
	}

	/* Get information from CTT Website and store it on order */
	function ctt_tracking_get_info_for_order( $order_id ) {
		if ( $order_object = wc_get_order( $order_id ) ) {
			if ( $ctt_tracking_code = $order_object->get_meta( '_ctt_tracking_code' ) ) {
				if ( trim( $ctt_tracking_code ) != '' ) {
					
					//Until 2022-09-01
					//$info = $this->ctt_tracking_get_info( trim( $ctt_tracking_code ) ); //Completely removed on 2.2
					//$info[ 'last_update' ] = date_i18n( 'Y-m-d H:i' );
					
					//After 2022-09-01
					$info = array(
						'status'      => 0,
						'message'     => sprintf(
							__( "Due to changes in CTT's systems, the tracking information is currently unavailable and can only be accessed through their website, <a href='%s' target='_blank'>here</a>.", 'portugal-ctt-tracking-woocommerce' ),
							esc_url( $this->build_more_url( $ctt_tracking_code ) )
						),
						'last_update' => date_i18n( 'Y-m-d H:i' ),
					);


					$order_object->update_meta_data( '_ctt_tracking_info', $info );
					$order_object->save();
					//return it
					return $info;
				}
			}
		}
		return false;
	}

	/* Helper functions */
	function ctt_tracking_clean_html_node_content( $content ) {
		return html_entity_decode( trim( $content ) );
	}
	function ctt_tracking_fix_date( $date ) {
		$date = trim( $date );
		//aaaa/mm/dd ?
		if ( strlen( $date ) == 10 && stristr( $date, '/' ) ) {
			$date = str_replace( '/', '-', $date );
		} else {
			if ( stristr( $date, ',' ) ) {
				$temp = explode( ',', $date );
				$temp = explode( ' ', trim( $temp[1] ) );
				//Year
				$date = array( $temp[2] );
				//Month
				$months = array(
					'Janeiro'   => '01',
					'Fevereiro' => '02',
					'Março'     => '03',
					'Abril'     => '04',
					'Maio'      => '05',
					'Junho'     => '06',
					'Julho'     => '07',
					'Agosto'    => '08',
					'Setembro'  => '09',
					'Outubro'   => '10',
					'Novembro'  => '11',
					'Dezembro'  => '12',
				);
				$date[] = $months[trim( $temp[1] )];
				//Day
				$date[] = intval( $temp[0] ) < 10 ? '0'.intval( $temp[0] ) : intval( $temp[0] );
				$date = implode( '-' , $date );
			}
		}
		return $date;
	}
	function ctt_tracking_is_status_final( $status ) {
		switch( trim( $status ) ) {
			case 'Objeto entregue':
			case 'Entregue':
				return true;
				break;
		}
		return false;
	}

}