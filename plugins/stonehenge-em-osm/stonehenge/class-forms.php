<?php
if( !defined('ABSPATH') ) exit;
if( !class_exists('Stonehenge_Forms')) :

Class Stonehenge_Forms extends Stonehenge_Functions {


	#===============================================
	public function ajax_loader() {
		$url 	= plugins_url('/assets/images/ajax-loader.gif', __FILE__);
		$image 	= sprintf('<div id="loader" style="display:none; text-align:center;"><img src="%s"></div>', $url);
		return $image;
	}


	#===============================================
	public function form_actions() {
		if( !isset($_POST['stonehenge_form_nonce']) ) {
			$response = stonehenge()->show_notice( __wp('Invalid form submission.'), 'error');
			wp_send_json( $response );
			exit();
		}

		$class 		= sanitize_text_field( $_POST['processor_class'] );
		$type 	 	= sanitize_text_field( $_POST['processor_type'] );
		$processor 	= "process_{$type}";
		$input 		= $_POST[$type];
		$response 	= (new $class)->$processor( $input );
		wp_send_json( $response );
		exit();
	}


	#===============================================
	public function render_metabox( $section, $section_id, $count = 1 ) {
		if( !is_array($section) ) {
			return;
		}
		?>
		<div class="stonehenge-metabox" id="<?php echo esc_html($section_id); ?>-metabox">
			<div class="stonehenge-metabox-header">
				<h3 class="handle"><?php echo esc_html( $section['label'] ); ?></h3>
			</div>
			<?php $this->render_section($section, $section_id, $count); ?>
		</div>
		<br style="clear:both;">
		<?php
		return;
	}


	#===============================================
	public function render_section( $section, $section_id, $count = 1 ) {
		if( !is_array($section) ) {
			return;
		}

		?>
		<section class="stonehenge-section" id="<?php echo $section_id; ?>">
			<table class="stonehenge-table">
				<?php $this->render_fields( $section['fields'], $section_id, $count); ?>
			</table>
		</section>
		<?php
	}


	#===============================================
	public function render_form( $section, $submit_label, $plugin ) {
		$section_id = str_replace('-', '_', $section['id']);

		ob_start();
		?>
		<form id="stonehenge_form" method="post" action="<?php echo admin_url('admin-ajax.php?action=stonehenge_form'); ?>" data-parsley-validate="" novalidate="">
			<div id="stonehenge_form_fields">
				<?php wp_nonce_field('stonehenge_form_nonce', 'stonehenge_form_nonce'); ?>
				<input type="hidden" name="processor_class" value="<?php echo $plugin['class']; ?>" readonly>
				<input type="hidden" name="processor_type" value="<?php echo $section_id; ?>" readonly>
				<table class="stonehenge-table">
					<?php
						// Show Form Inputs.
						$this->render_fields($section['fields'], $section_id);

						// Add the default required form fields.
						$this->render_default_fields( $submit_label, $section_id );
					?>
				</table>
			</div>
			<div id="stonehenge_form_result"></div>
		</form>
		<?php
		$output = ob_get_clean();
		return $output;
	}


	#===============================================
	public function render_default_fields( $submit_label, $section_id, $loader = true) {
		$fields = array();

		if( !is_backend() ) {
			$fields['consent'] = array(
				'id' 		=> 'consent',
				'label' 	=> $this->privacy_consent_checkbox(),
				'type'		=> 'checkbox',
				'required'	=> true,
			);
		}

		if( $loader ) {
			$fields['loader'] = array(
				'id'		=> 'loader',
				'type'		=> 'loader',
				'default' 	=> $this->ajax_loader(),
			);
		}

		$fields['submit'] =	array(
			'id'		=> 'submit',
			'label'		=> $submit_label,
			'type'		=> 'submit',
		);
		$this->render_fields( $fields, $section_id );
		return;
	}


	#===============================================
	public function privacy_consent_checkbox() {
		$page_id 	= get_option('wp_page_for_privacy_policy');
		$page_title = get_the_title( $page_id );
		$page_link 	= get_permalink( $page_id );

		$string 	= get_option('dbem_data_privacy_consent_text');
		$string 	= apply_filters('stonehenge_privacy_consent_text', $string);

		$link 		= "<a href='{$page_link}' target='_blank' title='{$page_title}'>{$page_title}</a>";
		$label 		= str_replace('%s', $link, $string );
		return $label;
	}


	#===============================================
	public function sanitize_form_input( $input ) {
		$output = array();

		foreach( $input as $key => $value ) {
			switch( $key ) {
				case 'item_selected':
					$output[$key] = sanitize_key($value);
				break;
				case 'first_name':
				case 'last_name':
				case 'name':
				case 'address':
				case 'company':
					$output[$key] = stonehenge()->localize_name($value);
				break;
				case 'city':
					$output[$key] = trim( ucwords( sanitize_text_field($value) ) );
				break;
				case 'email':
					$output[$key] = trim(strtolower(sanitize_email($value)));
				break;
				case 'postcode':
				case 'state':
				case 'country':
				case 'vat':
					$output[$key] = strtoupper(trim(sanitize_text_field($value)));
				break;
				case 'subject':
					$output[$key] = trim(sanitize_text_field(stripslashes($value)));
				break;
				case 'content':
					$output[$key] = wp_kses_allowed(stripslashes($value));
				break;
				default:
					$value = is_array($value) ? array_map('sanitize_text_field', $value) : trim(sanitize_text_field($value));
					$output[$key] = $value;
				break;
			}
		}
		return $output;
	}

} // End class.
endif;
