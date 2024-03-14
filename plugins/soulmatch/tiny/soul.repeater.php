<?php
	// SoulRepeater v0.1.0

	if ( ! class_exists( 'SoulRepeater' ) ) {
		class SoulRepeater {
			public $settings = false;
			public $parent = false;
			private $notices = array();

			public function __construct( $settings, $parent ) {
				$this->settings = $settings;
				$this->parent = $parent;
				if ( isset( $settings['links'] ) ) {
					add_filter( "plugin_action_links_{$settings['links']['file']}", array( $this, 'list_link' ) );
				}
				add_action( 'admin_menu', 					array( $this, 'init_page' ) );
				add_action( 'init', 								array( $this, 'save') );
				add_action( 'wp_ajax_add_repeater',	array( $this, 'ajax' ) );
				// add_action( 'admin_menu', array( $this, 'init_fields' ) );
			}
			public function list_link( $links ) {
				foreach( $this->settings['links']['links'] as $link ) {
					$link_defaults = array(
						'uri'	=> $this->_get_menu_uri(),
						'title' => '',
						'class' => '',
					);
					$link = wp_parse_args( $link, $link_defaults );
					$link = "<a href='{$link['uri']}' class='{$link['class']}'>{$link['title']}</a>";
					$links[] = $link;
				}
				return $links;
			}
			private function _get_menu_uri() {
				if( isset( $this->settings['page']['parent'] ) ) {
				} else {
					$uri = admin_url( "options-general.php?page={$this->settings['page']['slug']}" );
				}
				return $uri;
			}
			public function ajax() {
				if ( !current_user_can( 'manage_options') ) {
					wp_send_json_error( 'permission_error' );
					die();
				}
				ob_start();
				$this->repeater();
				$data = ob_get_contents();
				ob_end_clean();
				wp_send_json_success( $data );
				die();
			}
			public function save() {
				if( !isset( $_POST[ $this->settings['page']['slug'] ] ) ) {
					return true;
				}
				if ( ! isset( $_POST['_wpnonce'] )
					|| ! wp_verify_nonce( $_POST['_wpnonce'], $this->settings['page']['slug'] )
				) {
					$this->add_notice( 'error', $this->settings['l10n']['nonce_error'] );
					return true;
				}
				$data = $_POST[ $this->settings['page']['slug'] ];
				$temp = array();
				foreach ( $data as $field_id => $values ) {
					if ( isset( $this->settings['fields'][ $field_id ]['callback'] ) && 'checkbox' == $this->settings['fields'][ $field_id ]['callback'] ) {
						echo 'checkbox';
						$values = $this->_save_checkbox( $values );
						$data[ $field_id ] = $values;
					}
					foreach ( $values as $no => $value ) {
						if ( !isset( $temp[ $no ] ) ) {
							$temp[ $no ] = array();
						}
						$temp[ $no ][ $field_id ] = $value;
					}
				}
				update_option( $this->settings['page']['option'], $temp );
				$this->add_notice( 'success', $this->settings['l10n']['save_success'] );
				return true;
				// echo '<pre>';
				// var_dump( $_POST[ $this->settings['page']['slug'] ] );
				// var_dump( $temp );
				// echo '</pre>';
				// wp_die('Saving');
			}
			private function _save_checkbox( $inputs ) {
				$j = 0;
				foreach( $inputs as $input ){
				  if( '0' !== $input){
				    unset( $inputs[ $j-1 ] );
				  }
				  $j++;
				}
				$inputs = array_values( $inputs );
				return $inputs;
			}
			private function add_notice( $type, $text ) {
				$this->notices[] = array(
					'type'	=> $type,
					'text'	=> $text,
				);
			}
			private function show_notices( ) {
				foreach( $this->notices as $notice ) {
					$this->show_notice( $notice );
				}
				$this->notices = array();
			}
			private function show_notice( $notice = array() ) {
				echo "<div class='notice notice-{$notice['type']} is-dismissible'>".wpautop( $notice['text'] )."</div>";
			}
			public function init_page() {
				$defaults = array(
					'title'			 => '',
					'menu_title' => '',
					'description'=> '',
					'role'			 => 'manage_options',
					'slug'			 => __FILE__,
					'callback'	 => array( $this, 'page' ),
				);
				$this->settings['page'] = wp_parse_args( $this->settings['page'], $defaults );
				if( isset( $this->settings['page']['parent'] ) ) {
					add_submenu_page(
						$this->settings['page']['parent'],
						$this->settings['page']['title'],
						$this->settings['page']['menu_title'],
						$this->settings['page']['role'],
						$this->settings['page']['slug'],
						$this->settings['page']['callback']
					);
				} else {
					add_options_page(
						$this->settings['page']['title'],
						$this->settings['page']['menu_title'],
						$this->settings['page']['role'],
						$this->settings['page']['slug'],
						$this->settings['page']['callback']
					);
				}
			}

			private function repeater( $values = array() ) {
				?>
				<div class="postbox">
					<div class="handlediv" title="Click to toggle"><br></div>
					<h2 class="hndle"><span><?php echo $this->settings['l10n']['repeater']; ?></span></h2>
					<div class="inside">
				<?php
					$count = 0;
					foreach( $this->settings['fields'] as $field_id => $field ) {
						$func = false;
						if ( isset( $field['callback'] ) ) {
							$func = $field['callback'];
						}
						if ( !is_callable( $func ) && isset( $field['callback'] ) ) {
							$func = array( $this, $field['callback'] );
						}
						if ( !is_callable( $func ) ) {
							$func = array( $this, 'input' );
						}
						call_user_func( $func, $field_id, $field, $values, $count++ );
					}
				?>
						<a class="button-secondary soulrepeater-delete" href="#" title="<?php echo esc_attr( $this->settings['l10n']['delete_repeater'] ); ?>"><?php echo $this->settings['l10n']['delete_repeater']; ?></a>

					</div>
					<!-- .inside -->
				</div>
				<!-- .postbox -->

				<?php
			}
			private function input( $field_id, $field, $values, $count ) {
				$defaults = array(
					'attributes' => array(),
				);
				$field = wp_parse_args( $field, $defaults );
				echo '<p>';
				$default_attributes = array(
					'class' => 'large-text',
					'id'	=> $this->settings['page']['slug']."_{$field_id}_{$count}",
					'placeholder'	=> '',
					'name' => $this->settings['page']['slug']."[{$field_id}][]",
					'type' => 'text',
					'value'	=> isset( $values[ $field_id ] ) ? $values[ $field_id ] : false,
				);
				$atts = wp_parse_args( $field['attributes'], $default_attributes );
				foreach( $atts as $key=>$value ) {
					$atts[ $key ] = "{$key}='{$value}'";
				}
				$flat_atts = implode( ' ', $atts );
				echo "<label for='{$atts['id']}'>{$field['title']}</label>";
				echo "<input {$flat_atts}/>";
				echo '</p>';
			}
			private function checkbox( $field_id, $field, $values, $count ) {
				$defaults = array(
					'attributes' => array(),
				);
				$field = wp_parse_args( $field, $defaults );
				echo '<p>';
				$default_attributes = array(
					'class' => '',//'large-text',
					'id'	=> $this->settings['page']['slug']."_{$field_id}_{$count}",
					'placeholder'	=> '',
					'name' => $this->settings['page']['slug']."[{$field_id}][]",
					'type' => 'checkbox',
					'value'	=> 1,
				);
				// $values[ $field_id ]
				$atts = wp_parse_args( $field['attributes'], $default_attributes );
				$flat_atts = array();
				foreach( $atts as $key=>$value ) {
					$flat_atts[ $key ] = "{$key}='{$value}'";
				}
				$hidden_atts = $flat_atts;
				$hidden_atts['type'] = "type='hidden'";
				$hidden_atts['value'] = "value='0'";
				unset( $hidden_atts['class'] );
				unset( $hidden_atts['id'] );
				$flat_atts[] = checked( ( isset( $values[ $field_id ] ) ? $values[ $field_id ] : false ), 1, false );
				$flat_atts 		= implode( ' ', $flat_atts );
				$hidden_atts 	= implode( ' ', $hidden_atts );
				echo "<input {$hidden_atts}/>";
				echo "<input {$flat_atts}/>";
				echo "<label for='{$atts['id']}'>{$field['title']}</label>";
				echo '</p>';
			}
			private function listfield( $field_id, $field, $values, $count ) {
				$defaults = array(
					'attributes' => array(),
				);
				$field = wp_parse_args( $field, $defaults );
				echo '<p>';
				$default_attributes = array(
					'class' => 'large-text',
					'id'	=> $this->settings['page']['slug']."_{$field_id}_{$count}",
					'placeholder'	=> '',
					'name' => $this->settings['page']['slug']."[{$field_id}][]",
					'type' => 'text',
				);
				$atts = wp_parse_args( $field['attributes'], $default_attributes );
				$type = $atts['type'];
				$val = $values[ $field_id ];
				if ( !is_array( $val ) ) {
					$val = array( $val );
				}
				unset( $atts['type'] );
				foreach( $atts as $key=>$value ) {
					$atts[ $key ] = "{$key}='{$value}'";
				}
				$flat_atts = implode( ' ', $atts );
				echo "<label for='{$atts['id']}'>{$field['title']}</label>";
				echo '<br/>';
				echo "<select {$flat_atts}>";
				foreach( $field['list'] as $value => $title ) {
					$selected = selected( in_array( $value, $val), true, false );
					echo "<option value='{$value}' {$selected}>{$title}</option>";
				}
				echo '</select>';
				echo '</p>';
			}

			public function page(){
				if ( !current_user_can( $this->settings['page']['role'] ) ) {
		        wp_die( $this->settings['l10n']['no_access'] );
		    }
			  ?>
			    <div class="wrap soulrepeater-wrap">
			      <div class="icon32" id="icon-page"><br></div>
			      <h2><?php echo $this->settings['page']['title']; ?></h2>
						<?php $this->show_notices(); ?>
						<?php echo wpautop( $this->settings['page']['description'] ); ?>
						<form action="" method="post">
							<?php wp_nonce_field( $this->settings['page']['slug'] ); ?>
							<?php //settings_fields( $this->settings['page']['slug'] ); ?>
							<div id="poststuff">

								<div id="post-body" class="metabox-holder columns-2">

									<!-- main content -->
									<div id="post-body-content">

										<div class="meta-box-sortables ui-sortable">
											<?php
												$repeaters = get_option( $this->settings['page']['option'] );
												if ( $repeaters ) {
													foreach( $repeaters as $repeater ) {
														$this->repeater( $repeater );
													}
												} else {
													$this->repeater();
												}
												// $this->repeater();

											 ?>

										</div>
										<!-- .meta-box-sortables .ui-sortable -->

									</div>
									<!-- post-body-content -->

									<!-- sidebar -->
									<div id="postbox-container-1" class="postbox-container">

										<div class="meta-box-sortables ui-sortable">
											<div class="postbox">
												<div class="inside">
													<?php submit_button( $this->settings['l10n']['save_changes'] ); ?>
													<br/>
													<a class="button-secondary soulrepeater-add" href="#" title="<?php echo esc_attr( $this->settings['l10n']['add_repeater'] ); ?>"><?php echo $this->settings['l10n']['add_repeater']; ?></a>
												</div>
												<!-- .inside -->
											</div>
											<!-- .postbox -->
										</div>
										<!-- .meta-box-sortables -->
									</div>
									<!-- #postbox-container-1 .postbox-container -->
								</div>
								<!-- #post-body .metabox-holder .columns-2 -->
								<br class="clear">
							</div>
							<!-- #poststuff -->
			      </form>
			    </div>
			  <?php
			}
		}
	}
