<?php

class ARFLITEwidgetForm extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'description' => __( 'Display Form of ARForms Lite', 'arforms-form-builder' ) );
		parent::__construct( 'arformslite_widget_form', __( 'ARForms Lite Form', 'arforms-form-builder' ), $widget_ops );

		add_action( 'load-widgets.php', array( $this, 'arflite_load_wiget_scripts' ) );
	}

	function arflite_load_wiget_scripts() {
		global $arfliteverion;
		wp_register_script( 'arflite_widget_script', ARFLITEURL . '/core/widgets/arflite_widget_script.js', array(), $arfliteverion );
		wp_enqueue_script( 'arflite_widget_script' );
	}

	function form( $instance ) {

		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title' => false,
				'form'  => false,
			)
		);
		?>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php echo esc_html__( 'Title', 'arforms-form-builder' ); ?>:</label>
			<input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr( stripslashes( $instance['title'] ) ); ?>" />
		</p>

		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'form' )); ?>"><?php echo esc_html__( 'Form', 'arforms-form-builder' ); ?>:</label>
			<?php
				global $arfliteformhelper;
				$arfliteformhelper->arflite_forms_dropdown_widget( $this->get_field_name( 'form' ), $instance['form'], false, $this->get_field_id( 'form' ) )
			?>
		</p>

		<p>
			<label for=""><?php echo esc_html__( 'Disable Mutlicolumn in Form', 'arforms-form-builder' ); ?></label>
			<br/>
			<?php
				$is_multicolumn_checked = '';
				$is_multicolumn_val     = '0';
			if ( isset( $instance['enable_multicolumn'] ) && $instance['enable_multicolumn'] == 1 ) {
				$is_multicolumn_val     = '1';
				$is_multicolumn_checked = "checked='checked'";
			} elseif ( ! isset( $instance['enable_multicolumn'] ) ) {
				$is_multicolumn_val     = '1';
				$is_multicolumn_checked = "checked='checked'";
			}

			?>
			<input type="hidden" class="arf_enable_multicolumn_hidden" name="<?php echo esc_attr($this->get_field_name( 'enable_multicolumn' )); ?>" id="<?php echo esc_attr($this->get_field_id( 'enable_multicolumn' )); ?>" value="<?php echo esc_attr( $is_multicolumn_val ); ?>">

			<input type="checkbox" id="arf_enable_multicolumn_checkbox" <?php echo esc_attr( $is_multicolumn_checked ); ?> /> &nbsp;<label for="<?php echo esc_attr($this->get_field_id( 'enable_multicolumn' )); ?>"><?php echo esc_html__( 'Yes', 'arforms-form-builder' ); ?></label>
		</p>
		<?php
	}

	function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	function widget( $args, $instance ) {
		global $arfliteform, $arfliteversion, $arfliteformcontroller, $arflite_forms_loaded;
		extract( $args );
		?>
		<style>
			.arflite_main_div_<?php echo ( isset( $instance['form'] ) ) ? esc_html( $instance['form'] ) : ''; ?> .arf_submit_div.left_container { text-align:center !important; clear:both !important; margin-left:auto !important; margin-right:auto !important; }
			.arflite_main_div_<?php echo ( isset( $instance['form'] ) ) ? esc_html( $instance['form'] ) : ''; ?> .arf_submit_div.right_container { text-align:center !important; clear:both !important; margin-left:auto !important; margin-right:auto !important; }
			.arflite_main_div_<?php echo ( isset( $instance['form'] ) ) ? esc_html( $instance['form'] ) : ''; ?> .arf_submit_div.top_container,
			.arflite_main_div_<?php echo ( isset( $instance['form'] ) ) ? esc_html( $instance['form'] ) : ''; ?> .arf_submit_div.none_container { text-align:center !important; clear:both !important; margin-left:auto !important; margin-right:auto !important; }

			.arflite_main_div_<?php echo ( isset( $instance['form'] ) ) ? esc_html( $instance['form'] ) : ''; ?> #hexagon.left_container { text-align:center !important; margin-left:auto !important; margin-right:auto !important; }
			.arflite_main_div_<?php echo ( isset( $instance['form'] ) ) ? esc_html( $instance['form'] ) : ''; ?> #hexagon.right_container { text-align:center !important; margin-left:auto !important; margin-right:auto !important; }
			.arflite_main_div_<?php echo ( isset( $instance['form'] ) ) ? esc_html( $instance['form'] ) : ''; ?> #hexagon.top_container,
			.arflite_main_div_<?php echo ( isset( $instance['form'] ) ) ? esc_html( $instance['form'] ) : ''; ?> #hexagon.none_container { text-align:center !important; margin-left:auto !important; margin-right:auto !important; }

			.arflite_main_div_<?php echo ( isset( $instance['form'] ) ) ? esc_html( $instance['form'] ) : ''; ?> .arfsubmitbutton .arf_submit_btn { margin: 10px 0 0 0 !important; }

		</style>
		<?php
		$form_name = '';
		if ( isset( $instance['form'] ) ) {
			$form_name = $arfliteform->arflitegetName( $instance['form'] );
		}
		global $wpdb,$ARFLiteMdlDb, $tbl_arf_forms;
		$form_data = '';
		if ( isset( $instance['form'] ) ) {
			$form_data = $wpdb->get_row( $wpdb->prepare( 'SELECT * FROM ' . $tbl_arf_forms . ' WHERE id = %d', $instance['form'] ) ); //phpcs:ignore
		}
		if ( $form_data ) {
			$formoptions = maybe_unserialize( $form_data->options );
			if ( isset( $formoptions['display_title_form'] ) && $formoptions['display_title_form'] == '1' ) {
				$is_title       = true;
				$is_description = true;
			} else {
				$is_title       = false;
				$is_description = false;
			}
		}
		$arflite_forms_loaded[] = $form_data;

		$is_title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'] );
		echo $before_widget; //phpcs:ignore
		$widget_cls = '';
		if ( isset( $instance['enable_multicolumn'] ) && $instance['enable_multicolumn'] == 0 ) {
			$widget_cls = ' arf_multicolumn_widget_form ';
		}
		echo '<div class="arf_widget_form ' . esc_attr($widget_cls) . '">';
		if ( $is_title ) {
			echo $before_title . stripslashes( $is_title ) . $after_title; //phpcs:ignore
		}

		if ( is_ssl() ) {
			$upload_main_url = str_replace( 'http://', 'https://', ARFLITE_UPLOAD_URL . '/maincss' );
		} else {
			$upload_main_url = ARFLITE_UPLOAD_URL . '/maincss';
		}

		$upload_main_url = esc_url_raw( $upload_main_url );

		$is_material = false;
		$handler     = '';
		if ( isset( $form_data ) && is_array( $form_data ) && ! empty( $form_data ) && count( $form_data ) ) {
			$form_css = maybe_unserialize( $form_data->form_css );
			if ( isset( $form_css ) && is_array( $form_css ) && ! empty( $form_css ) ) {
				$input_style = $form_css['arfinputstyle'];
				if ( $input_style == 'material' ) {
					$is_material = true;
					$handler     = 'maincss_materialize';
				}
			}
		}

		global $arflitemainhelper, $arfliterecordcontroller;
		if ( $is_material ) {
			if ( isset( $instance['form'] ) ) {
				$fid = $upload_main_url . '/maincss_materialize_' . $instance['form'] . '.css';
			}
		} else {
			if ( isset( $instance['form'] ) ) {
				$fid = $upload_main_url . '/maincss_' . $instance['form'] . '.css';
			}
		}
		$arflite_data_uniq_id = rand( 1, 99999 );
		if ( empty( $arflite_data_uniq_id ) || $arflite_data_uniq_id == '' ) {
			if ( isset( $instance['form'] ) ) {
				$arflite_data_uniq_id = $instance['form'];
			}
		}

		/*
		 arf_dev_flag passed unique id to handle of css
		 * once there was ticket for conflict with yoast seo
		 * moreover also change hangle of arf_front.css (not done yet)
		 */
		if ( isset( $instance['form'] ) ) {
			wp_register_style( 'arfliteformscss_' . $handler . '_' . $instance['form'], $fid, array(), $arfliteversion );
		}
		$arflite_func_val = '';
		if ( isset( $instance['form'] ) ) {
			$arflite_func_val = apply_filters( 'arflite_hide_forms', $arfliteformcontroller->arflite_class_to_hide_form( $instance['form'] ), $instance['form'] );
		}

		if ( $arflite_func_val == '' ) {
			if ( isset( $instance['form'] ) ) {
				$arflitemainhelper->arflite_load_styles( array( 'arfliteformscss_' . $instance['form'] . $arflite_data_uniq_id, 'arflitedisplaycss', 'bootstrap' ) );
			}
		} else {
			if ( isset( $instance['form'] ) ) {
				$arflitemainhelper->arflite_load_styles( array( 'arfliteformscss_' . $instance['form'] . $arflite_data_uniq_id ) );
			}
		}

				$key = '';
				require_once ARFLITE_VIEWS_PATH . '/arflite_front_form.php';

		if ( isset( $instance['form'] ) ) {
			$contents = arflite_get_form_builder_string( $instance['form'], $key, false, false, '', $arflite_data_uniq_id );

			$contents = apply_filters( 'arflite_pre_display_arfomrms', $contents, $instance['form'], $key );

			/* arf_dev_flag widget echo css here */
			echo $arfliteformcontroller->arflite_get_form_style( $instance['form'], $arflite_data_uniq_id ); //phpcs:ignore

			echo $contents; //phpcs:ignore
		}

		$arflitesidebar_width = '';
		echo '</div>';
		echo $after_widget; //phpcs:ignore
	}
}
?>
