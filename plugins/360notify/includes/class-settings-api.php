<?php

defined( 'ABSPATH' ) || exit;

class WooNotify_360Messenger_Settings_Api {
	private $settings_sections = [];
	private $settings_fields = [];
	private $desc_style = 'style="margin-top:4px;color:#616161;font-style:italic;font-size:13px;line-height:25px;"';
	
		
		
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
	}

	function admin_enqueue_scripts() {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media();
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_script( 'jquery' );
	}

	function set_sections( $sections ) {
		$this->settings_sections = $sections;

		return $this;
	}

	function add_section( $section ) {
		$this->settings_sections[] = $section;

		return $this;
	}

	function set_fields( $fields ) {
		$this->settings_fields = $fields;

		return $this;
	}

	function add_field( string $section, $field ) {
		$defaults = [
			'name'  => '',
			'label' => '',
			'desc'  => '',
			'type'  => 'text',
		];

		$this->settings_fields[ $section ][] = wp_parse_args( $field, $defaults );

		return $this;
	}

	function admin_init() {
		foreach ( $this->settings_sections as $section ) {
			if ( false == get_option( $section['id'] ) ) {
				add_option( $section['id'] );
			}
			if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
				$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
				$callback        = create_function( '', ' "' . str_replace( '"', '\"', esc_html ($section['desc'] )) . '";' );
			} else {
				$callback = '__return_false';
			}
			add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
		}

		foreach ( $this->settings_fields as $section => $field ) {
			foreach ( $field as $option ) {
				$type = isset( $option['type'] ) ? $option['type'] : 'text';
				$args = [
					'id'                => $option['name'],
					'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
					'desc2'             => isset( $option['desc2'] ) ? $option['desc2'] : '',
					'label_for'         => $type != 'html' && isset( $option['name'] ) ? $section . '[' . $option['name'] . ']' : '',
					'name'              => @$option['label'],
					'section'           => $section,
					'size'              => isset( $option['size'] ) ? $option['size'] : null,
					'row'               => isset( $option['row'] ) ? $option['row'] : '',
					'ltr'               => isset( $option['ltr'] ) ? $option['ltr'] : false,
					'options'           => isset( $option['options'] ) ? $option['options'] : '',
					'br'                => isset( $option['br'] ) ? $option['br'] : '',
					'std'               => isset( $option['default'] ) ? $option['default'] : '',
					'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
				];
				add_settings_field( $section . '[' . esc_html(sanitize_text_field($option['name'])) . ']', @esc_html(sanitize_text_field($option['label'])), [
					$this,
					'callback_' . $type,
				], $section, $section, $args );
			}
		}

		foreach ( $this->settings_sections as $section ) {
			register_setting( $section['id'], $section['id'], [ $this, 'sanitize_options' ] );
		}
	}

	function callback_text( $args ) {
		$kses_WooNotifytext = array(
			'input' => array(
				'type' => array(),
				'class' => array(),
				'id' => array(),
				'name' => array(),
				'value' => array(),
				'style' => array()
			),
			
			'div' => array(
				'class' => array(),
				'style' => array()
			),
			
			
		);
		$value = esc_html( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$style = '';
		if ( ! empty( $args['ltr'] ) && $args['ltr'] === true ) {
			$style .= 'text-align:left !important; direction:ltr !important;';
		}
		$html = (sprintf( '<input type="text" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" style="%5$s"/>',$size,$args['section'],$args['id'],$value , $style ));
		$html .= (sprintf( '<div class="description" ' . esc_html(sanitize_text_field($this->desc_style)) . '> %s</div>',  $args['desc'] ));
		
		echo wp_kses(($html),$kses_WooNotifytext);
	}

	function get_option( $option, $section, $default = '' ) {
		$options = get_option( $section );
		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}

		return $default;
	}

	function callback_checkbox( $args ) {
		$kses_WooNotifycheckbox = array(
			'label' => array(
				'for' => array()
			),
			'input' => array(
				'type' => array(),
				'class' => array(),
				'id' => array(),
				'name' => array(),
				'value' => array(),
				'checked' => array()
			),
			'div' => array(
				'class' => array(),
				'style' => array()
			),
			'p' => array(),
			
		);
		$value = esc_html( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$html  = (sprintf( '<input type="hidden" name="%1$s[%2$s]" value="off" />', $args['section'], $args['id'] ));
		$html  .= (sprintf( '<input type="checkbox" class="checkbox" id="wpuf-%1$s[%2$s]" name="%1$s[%2$s]" value="on" %4$s />',$args['section'], $args['id'], $value, checked( $value, 'on', false ) ));
		$html  .= (sprintf( '<label for="wpuf-%1$s[%2$s]"> %3$s</label>', $args['section'], $args['id'], $args['desc'] ));

		if ( ! empty( $args['desc2'] ) ) {
			$html .= (sprintf( '<div class="description" ' . esc_html(sanitize_text_field($this->desc_style)) . '> %s</div>', $args['desc2'] ));
		}
		//echo wp_kses($html , $kses_WooNotifycheckbox);
		echo wp_kses(($html),$kses_WooNotifycheckbox);
	}

	function callback_multicheck( $args ) {
		$kses_WooNotifymulticheck = array(
			'input' => array(
				'type' => array(),
				'class' => array(),
				'id' => array(),
				'name' => array(),
				'value' => array(),
				'checked' => array()
			),
			'label' => array(
				'for' => array()
			),
			'div' => array(
				'class' => array(),
				'style' => array()
			),
			'p' => array(),
			'br' => array()
		);
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$html = '';
	
		foreach ( $args['options'] as $key => $label ) {
			$checked = isset( $value[ $key ] ) ? $value[ $key ] : '0';
			$html .= (sprintf(
				'<input type="checkbox" class="checkbox" id="%1$s[%2$s][%3$s]" name="%1$s[%2$s][%3$s]" value="%3$s"%4$s />',
				esc_html( $args['section'] ),
				esc_html( $args['id'] ),
				esc_html( $key ),
				checked( $checked, $key, false )
			));
			$html .= (sprintf(
				'<label for="%1$s[%2$s][%4$s]"> %3$s</label><br>',
				esc_html( $args['section'] ),
				esc_html( $args['id'] ),
				esc_html( $label ),
				esc_html( $key )
			));
		}
		$html .= (sprintf(
			'<div class="description" ' . esc_html(sanitize_text_field($this->desc_style)) . '> %s</div>',
			esc_html( $args['desc'] )
		));
	
		echo wp_kses( $html, $kses_WooNotifymulticheck );
	}
	

	function callback_radio( $args ) {
		
		$kses_radio = array(
			'label' => array(
				'style' => array(),
				'style' => array(),
				'for' => array()
			),
			'input' => array(
				'type' => array(),
				'class' => array(),
				'id' => array(),
				'name' => array(),
				'checked' => array(),
				'value' => array()
			),
			'div' => array(
				'class' => array(),
				'style' => array()
			),
			'p' => array(),
			
		);
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$html  = '';
		$style = is_rtl() ? 'margin-left:10px;' : 'margin-right:10px;';

		foreach ( $args['options'] as $key => $label ) {
			$html  .= (sprintf( '<label style="' . $style . '" for="wpuf-%s[%s][%s]">',  $args['section'] ,$args['id'] , $key  ));
			$html  .= (sprintf( '<input type="radio" class="radio inline" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s"%4$s />', $args['section'], $args['id'],  $key , checked( $value, $key, false ) )) . esc_html(sanitize_text_field($label));
			$html  .= '</label>';
			
			if ( ! empty( $args['br'] ) ) {
				$html .= '<p></p>';
			}
		}
		if ( ! empty( $args['br'] ) ) {
			$html .= '<p></p>';
		}
		$html .= (sprintf( '<div class="description" ' . esc_html(sanitize_text_field($this->desc_style)) . '> %s</div>', $args['desc']));
		//echo wp_kses( $html , $kses_radio ) ;
		echo wp_kses(($html),$kses_radio);
	}

	function callback_select( $args ) {
		$kses_select = array(
			'option' => array(
				'value' => array(),
				'style' => array(),
				'selected' => array()
			),
			'select' => array(
				'class' => array(),
				'name' => array(),
				'id' => array(),
				'dir' => array()
			),
			'div' => array(
				'class' => array(),
				'style' => array()
			),
			'p' => array(),
		);
		
		$value = esc_html( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'wc-enhanced-select regular';

		$ltr = ! empty( $args['ltr'] ) && $args['ltr'] === true ? 'dir="ltr"' : '';

		$html = (sprintf( '<div style="max-width:350px"><select class="%1$s" name="%2$s[%3$s]" id="%2$s[%3$s]" %4$s>',  $size , $args['section'] , $args['id'] , $ltr));
		foreach ( $args['options'] as $key => $label ) {
			$html .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
		}
		$html .= sprintf( '</select></div>' );
		$html .= (sprintf( '<div class="description" ' . esc_html(sanitize_text_field($this->desc_style)) . '> %s</div>', $args['desc'] ));
		//echo wp_kses($html,$kses_select);
		echo wp_kses(($html),$kses_select);
	}

	




	function callback_textarea( $args ) {
		$kses_textarea = array(
			'textarea' => array(
				'rows' => array(),
				'style' => array(),
				'class' => array(),
				'id' => array(),
				'name' => array()
			),
			'div' => array(
				'class' => array(),
				'style' => array()
			),
			'br' => array(),
		);
	
		$value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : 'regular';
		$row   = ! empty( $args['row'] ) ? $args['row'] : '6';
		$style = 'width: 50%';
		
		$html  = (sprintf(
			'<textarea rows="%6$s" style="%5$s" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]">%4$s</textarea>',
			esc_html( $size ),
			esc_html( $args['section'] ),
			esc_html( $args['id'] ),
			esc_textarea( $value ),
			esc_html( $style ),
			esc_html( $row )
		));
	
		$html  .= sprintf(
			'<br><div class="description" %s> %s</div>',
			esc_html(sanitize_text_field($this->desc_style)),
			wp_kses( $args['desc'], $kses_textarea )
		);
	
		echo wp_kses( $html, $kses_textarea );
	}
	

	function callback_html( $args ) {
		$kses_description = array(
			'a' => array(
				'href' => array(),
				'onclick' => array(),
				'style' => array()
			),
			'div' => array(
				'class' => array(),
				'style' => array(),
			),
			'strong' => array(),
			'code' => array(),
			'br' => array(),
		);
	
		echo sprintf(
			('<div class="description" %s> %s</div>'),
			esc_html( sanitize_text_field($this->desc_style) ),
			wp_kses( $args['desc'], $kses_description )
		);
	}
	

	function callback_wysiwyg( $args ) {
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? $args['size'] : '500px';
	
		echo '<div style="width: ' . esc_html( $size ) . ';">';
		wp_editor( $value, $args['section'] . '-' . $args['id'] . '', array(
			'teeny'         => true,
			'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
			'textarea_rows' => 10,
		) );
		echo '</div>';
	
		echo (sprintf(
			('<br><div class="description" %s> %s</div>'),
			esc_html(sanitize_text_field($this->desc_style)),
			wp_kses_post( $args['desc'] )
		));
	}
	

	function callback_file( $args ) {
		$kses_uploadfile = array(
			'input' => array(
				'type' => array(),
				'class' => array(),
				'id' => array(),
				'name' => array(),
				'value' => array()
			),
			'div' => array(
				'class' => array(),
				'style' => array()
			),
			'br' => array(),
		);
	
		$value = esc_html(esc_html( $this->get_option( $args['id'], $args['section'], $args['std'] ) ));
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? esc_html(sanitize_text_field($args['size'])) : esc_html('regular');
	
		// Generate the input field for file URL
		$input_field =(sprintf(
			'<input type="text" class="%1$s-text WooNotify-url" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>',
			esc_html( $size ),
			esc_html( $args['section'] ),
			esc_html( $args['id'] ),
			esc_html( $value )
		));
	
		// Generate the "Browse" button
		$browse_button = (sprintf(
			'<input type="button" class="button WooNotify-browse" value="%s" />',
			esc_html(__( 'Browse' ))
		));
	
		// Generate the description
		$description = (
			sprintf(
				'<div class="description" %s> %s</div>',
				esc_html(sanitize_text_field($this->desc_style)),
				wp_kses_post( $args['desc'] )
			)
        );
	
		// Combine all elements into the final HTML
		$html = $input_field . $browse_button . $description;
	
		echo wp_kses( $html, $kses_uploadfile );
	}
	

	function callback_password( $args ) {
		$kses_password = array(
			'input' => array(
				'type' => array(),
				'class' => array(),
				'id' => array(),
				'name' => array(),
				'value' => array()
			),
			'div' => array(
				'class' => array(),
				'style' => array()
			),
		);
	
		$value = esc_html( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? esc_html(sanitize_text_field($args['size'])) : esc_html('regular');
	
		// Generate the password input field
		$input_field = (sprintf(
			'<input type="password" class="%1$s-text" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s"/>',
			esc_html( $size ),
			esc_html( $args['section'] ),
			esc_html( $args['id'] ),
			esc_html( $value )
		));
	
		// Generate the description
		$description = (sprintf(
			'<div class="description" %s> %s</div>',
			esc_html(sanitize_text_field($this->desc_style)),
			wp_kses_post( $args['desc'] )
		));
	
		// Combine all elements into the final HTML
		$html = $input_field . $description;
	
		echo wp_kses( $html, $kses_password );
	}
	

	function callback_color( $args ) {
		$kses_color = array(
			'input' => array(
				'type' => array(),
				'class' => array(),
				'id' => array(),
				'name' => array(),
				'data-default-color' => array(),
				'value' => array()
			),
			'div' => array(
				'class' => array(),
				'style' => array()
			),
		);
	
		$value = esc_html( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		$size  = isset( $args['size'] ) && ! is_null( $args['size'] ) ? esc_html(sanitize_text_field($args['size'])) : esc_html('regular');
	
		// Generate the color picker input field
		$input_field = (sprintf(
			'<input type="text" class="%1$s-text wp-color-picker-field" id="%2$s[%3$s]" name="%2$s[%3$s]" value="%4$s" data-default-color="%5$s" />',
			esc_html( $size ),
			esc_html( $args['section'] ),
			esc_html( $args['id'] ),
			esc_html( $value ),
			esc_html( $args['std'] )
		));
	
		// Generate the description
		$description = (sprintf(
			'<div class="description" %s style="display:block;"> %s</div>',
			esc_html(sanitize_text_field($this->desc_style)),
			wp_kses_post( $args['desc'] )
		));
	
		// Combine all elements into the final HTML
		$html = $input_field . $description;
	
		echo wp_kses( $html, $kses_color );
	}
	

	function sanitize_options( $options ) {
		foreach ( $options as $option_slug => $option_value ) {
			$sanitize_callback = $this->get_sanitize_callback( $option_slug );
			if ( $sanitize_callback ) {
				$options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
				continue;
			}
		}

		return $options;
	}

	function get_sanitize_callback( $slug = '' ) {
		if ( empty( $slug ) ) {
			return false;
		}
		foreach ( $this->settings_fields as $section => $options ) {
			foreach ( $options as $option ) {
				if ( $option['name'] != $slug ) {
					continue;
				}

				return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
			}
		}

		return false;
	}

	function show_navigation() {
		$kses_navigation = array(
			'nav' => array(
				'class' => array()
			),
			'a' => array(
				'href' => array(),
				'class' => array(),
				'id' => array()
			),
		);
	
		$html       = '<nav class="nav-tab-wrapper woo-nav-tab-wrapper">';
		$tab_number = 0;
		$current_tab = sanitize_key( $_GET['tab'] ?? '' );
	
		foreach ( $this->settings_sections as $form ) {
			$tab_number++;
			$class = '';
	
			if ( empty( $current_tab ) ) {
				$class = ( $tab_number == 1 ) ? 'nav-tab-active' : '';
			} elseif ( stripos( $form['id'], $current_tab ) !== false ) {
				$class = 'nav-tab-active';
			}
	
			$url  = add_query_arg(
				array(
					'tab' => str_ireplace( array( '360Messenger_', '_settings' ), '', $form['id'] )
				)
			);
			
			$url = remove_query_arg(
				array(
					'settings-updated',
					'paged',
					'order',
					'orderby',
					's',
					'action',
					'_wpnonce',
					'item',
					'id',
					'product_id',
					'add',
					'edit',
				),
				$url
			);
	
			$html .= (sprintf(
				'<a href="%1$s" class="nav-tab %3$s" id="%1$s-tab">%2$s</a>',
				esc_url( $url ),
				esc_html( $form['title'] ),
				esc_html( $class )
			));
		}
	
		$html .= '</nav>';
		echo wp_kses( $html, $kses_navigation );
	}
	

	function show_forms() {
		if ( defined( 'WC_VERSION' ) ) {
			wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', [], WC_VERSION );
			wp_enqueue_script( 'wc-enhanced-select' );
		}
	
		$success_message = ( get_locale() == 'fa_IR' ) ? esc_html('تنظمیات ذخیره شدند') : esc_html('Settings saved');
		$updated = isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true';
	
		if ( $updated ) : ?>
			<div class="notice notice-success below-h2">
				<p><strong><?php echo esc_html( $success_message ); ?></strong></p>
			</div>
		<?php else :
			do_action( 'WooNotify_settings_form_admin_notices' );
		endif;
		?>
		<style>
			table.form-table th {
				padding-left: 24px !important;
				position: relative;
				width: 240px;
			}
		</style>
	
		<?php
		$current_tab = sanitize_key( $_GET['tab'] ?? '' );
		foreach ( $this->settings_sections as $form ) {
			@$tab_number ++;
			$display = false;
	
			if ( empty( $current_tab ) ) {
				$display = ( $tab_number == 1 );
			} elseif ( stripos( $form['id'], $current_tab ) !== false ) {
				$display = true;
			}
	
			if ( ! $display ) {
				continue;
			}
			?>
			<div id="<?php echo esc_html( $form['id'] ); ?>" class="group">
				<?php
				if ( ! isset( $form['form_tag'] ) || $form['form_tag'] !== false ) :
					$show_form = true; ?>
					<form method="post" action="options.php">
						<?php settings_fields( $form['id'] ); ?>
				<?php endif; ?>
				<?php
				do_action( 'WooNotify_settings_form_top_' . $form['id'], $form );
				do_settings_sections( $form['id'] );
				do_action( 'WooNotify_settings_form_bottom_' . $form['id'], $form );
				do_action( 'WooNotify_settings_form_submit_' . $form['id'], $form );
				?>
				<?php if ( ! empty( $show_form ) ) : ?>
					<?php remove_all_actions( 'WooNotify_settings_form_submit_' . $form['id'] ); ?>
					<div style="padding-right: 10px">
						<?php submit_button(); ?>
					</div>
					</form>
				<?php endif; ?>
			</div>
			<?php
			break; // برای نمایش فقط یک فرم، این خط تغییر داده شده است.
		}
		$this->script();
	}
	

	function script() { ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {

                /*
                $('.group').hide();
                var activetab = '';
                if (typeof(localStorage) !== 'undefined') {
                    activetab = localStorage.getItem("activetab");
                }

                if (activetab !== '' && $(activetab).length) {
                    $(activetab).fadeIn();
                } else {
                    $('.group:first').fadeIn();
                }

                if (activetab !== '' && $(activetab + '-tab').length) {
                    $(activetab + '-tab').addClass('nav-tab-active');
                }
                else {
                    $('.nav-tab-wrapper a:first').addClass('nav-tab-active');
                }
                $('.nav-tab-wrapper a').click(function (evt) {
                    $('.nav-tab-wrapper a').removeClass('nav-tab-active');
                    $(this).addClass('nav-tab-active').blur();
                    var clicked_group = $(this).attr('href');
                    if (typeof(localStorage) !== 'undefined') {
                        localStorage.setItem("activetab", $(this).attr('href'));
                    }

                    $('.group').hide();
                    $(clicked_group).fadeIn();
                    evt.preventDefault();
                });*/

                $('.wp-color-picker-field').wpColorPicker();

                $('.group .collapsed').each(function () {
                    $(this).find('input:checked').parent().parent().parent().nextAll().each(
                        function () {
                            if ($(this).hasClass('last')) {
                                $(this).removeClass('hidden');
                                return false;
                            }
                            $(this).filter('.hidden').removeClass('hidden');
                        });
                });

                var file_frame = null;
                $('.WooNotify-browse').on('click', function (event) {
                    event.preventDefault();
                    var self = $(this);
                    if (file_frame) {
                        file_frame.open();
                        return false;
                    }
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: self.data('uploader_title'),
                        button: {
                            text: self.data('uploader_button_text')
                        },
                        multiple: false
                    });
                    file_frame.on('select', function () {
                        var attachment = file_frame.state().get('selection').first().toJSON();

                        self.prev('.WooNotify-url').val(attachment.url);
                    });
                    file_frame.open();
                });
            });

        </script>
		<?php
	}
}