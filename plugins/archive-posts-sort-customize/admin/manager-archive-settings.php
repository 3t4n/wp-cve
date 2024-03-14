<?php

if ( !class_exists( 'APSC_Admin_Controller_Manager_Archive_Settings' ) ) :

final class APSC_Admin_Controller_Manager_Archive_Settings extends APSC_Admin_Abstract_Manager
{

	private $current_tab;
	private $tab_name;
	
	public function __construct()
	{
		
		global $APSC;

		$this->id             = 'archive_settings';
		$this->do_screen_slug = $APSC->main_slug;
		$this->menu_title     = __( $APSC->name , $APSC->ltd );
		
		$this->setup_tab();

		parent::__construct();

	}
	
	private function setup_tab()
	{
		
		global $APSC;

		$this->tab_name = $APSC->main_slug . '_tab';
		
		if( empty( $this->current_tab ) ) {
			
			$tab = 'home';
			
			if( !empty( $_GET[ $this->tab_name ] ) ) {
				
				$tab = strip_tags( $_GET[ $this->tab_name ] );
				
			}

			$this->current_tab = $tab;
			
		}

	}

	public function admin_menu()
	{

		global $APSC;
		
		$this->menu_hook = add_menu_page( $this->page_title , $this->menu_title , $APSC->Cap->capability , $this->do_screen_slug , array( $this , 'view' ) );

	}

	public function view()
	{
		
		global $APSC;
		
		$include_file = apply_filters( $APSC->main_slug . '_admin_tab_view_' . $this->current_tab , '' );
		
		if( empty( $include_file ) ) {
			
			return false;
			
		}
		
		include_once( $this->view_dir . $include_file );

	}
	
	private function tabs()
	{
		
		global $APSC;

		$tabs = apply_filters( $APSC->main_slug . '_add_tabs' , array() );
		
		if( empty( $tabs ) ) {
			
			return false;
			
		}
		
		foreach( $tabs as $tab_id => $tab_label ) {
			
			$url = add_query_arg( array( $this->tab_name => $tab_id) , $APSC->Link->admin );
			
			$active = false;
			
			if( $this->current_tab == $tab_id ) {
				
				$active = 'nav-tab-active';

			}
			
			printf( '<a href="%s" class="nav-tab %s">%s</a>' , $url , $active , $tab_label );
			
		}
		
	}
	
	private function settings_section( $args = array() )
	{
		
		global $APSC;
		
		$section_id = $args['id'];
		$section_title = $args['title'];
		$section_name_field = $args['name_field'];
		
		$individual = false;
		
		if( !empty( $args['individual'] ) ) {
			
			$individual = true;
			
		}
		
		$parent_name = false;

		if( !empty( $args['parent_name'] ) ) {
			
			$parent_name = $args['parent_name'];
			
		}

		$section_settings = $this->get_settings( $args );

		include( $this->elements_dir . 'section.php' );

	}
	
	private function get_settings( $args )
	{
		
		global $APSC;
		
		$settings = apply_filters( $APSC->main_slug . '_get_settings_' . $this->current_tab , array() , $args );
		
		return $settings;
		
	}
	
	protected function post_data()
	{

		global $APSC;
		
		if( !empty( $_POST[ $this->nonce . '_update' ] ) ) {
			
			$nonce_key = $this->nonce . '_update';

			if(	check_admin_referer( $nonce_key , $nonce_key ) ) {
				
				$errors = apply_filters( $APSC->main_slug . '_post_data_update_' . $this->current_tab , $_POST );

				$update_message = __( 'Settings saved.' );
				$notice = 'update_' . $this->name;
				
			}

		} elseif( !empty( $_POST[ $this->nonce . '_remove' ] ) ) {
			
			$nonce_key = $this->nonce . '_remove';

			if(	check_admin_referer( $nonce_key , $nonce_key ) ) {
				
				$errors = apply_filters( $APSC->main_slug . '_post_data_remove_' . $this->current_tab , $_POST );

				$update_message = __( 'Settings saved.' );
				$notice = 'remove_' . $this->name;
				
			}

		}
		
		if( !isset( $errors ) ) {

			return false;
			
		}
		
		if( ! is_wp_error( $errors ) ) {
			
			return false;
			
		}
		
		$error_codes = $errors->get_error_codes();
		
		if( !empty( $error_codes ) ) {
			
			foreach ( $error_codes as $code ) {
				
				$APSC->Helper->set_notice( $errors->get_error_message( $code ) , $code , 'error' );
				
			}

			$this->errors = $errors;
			
		} else {
			
			$APSC->Helper->set_notice( $update_message , $notice );
			
			wp_redirect( esc_url_raw( remove_query_arg( 'updated' , add_query_arg( 'updated' , true ) )) );
			exit;

		}
		
	}

	public function after_current_plugin_view()
	{

		global $APSC;

		include_once( $this->elements_dir . 'information.php' );

?>
<script>
jQuery(document).ready( function($) {
	
	$('.<?php echo $APSC->main_slug; ?>_form .handlediv').on('click', function() {
		
		$(this).parent().toggleClass('closed');
		
	});
	
	$('.change-individual').on('click', function() {
		
		var $inside = $(this).parent().parent();
		var is_use = $(this).prop('checked');
		
		if( is_use ) {
			
			$inside.find('.setting-section').removeClass( 'disable' );

		} else {
			
			$inside.find('.setting-section').addClass( 'disable' );
			
		}

	});
	
	$('.item-posts-per-page .change-posts-per-page').on('click', function() {
		
		var $current_field = $(this).parent().parent().parent().parent();
		var posts_per_page = $(this).val();
		var $number_field = $current_field.find('.post-per-page-set-number input');
		
		if( posts_per_page == 'set' ) {
			
			$number_field.prop('disabled', false).removeClass( 'disabled' );
			
		} else {
			
			$number_field.prop('disabled', true).addClass( 'disabled' );

		}
		
	});
	
	$('.item-orderby .change-orderby').on('change', function() {
		
		var $current_field = $(this).parent().parent();
		var orderby = $(this).val();
		var $custom_field = $current_field.find('.custom-fields');
		var $ignore_word = $current_field.find('.ignore-words');
		
		if( orderby == 'custom_fields' ) {
			
			$custom_field.removeClass( 'disable' );
			$ignore_word.addClass( 'disable' );
			
		} else if( orderby == 'title' ) {
			
			$custom_field.addClass( 'disable' );
			$ignore_word.removeClass( 'disable' );
			
		} else {
			
			$custom_field.addClass( 'disable' );
			$ignore_word.addClass( 'disable' );

		}
		
	});

	$('.load-all-custom-fields').on('click', function() {
		
		var $current_field = $(this).parent().parent().parent().parent();
		var $custom_field_names_el = $current_field.find('.all-custom-fields-names');
		var $spinner = $current_field.find('.spinner');
		
		var PostData = {
			action: '<?php echo $this->action; ?>_ajax_load_custom_fields',
			<?php echo $this->nonce . '_ajax_load_custom_fields'; ?>: '<?php echo wp_create_nonce( $this->nonce . '_ajax_load_custom_fields' ); ?>'
		}

		$.ajax({
	
			type: 'post',
			url: ajaxurl,
			data: PostData,
			beforeSend: function() {
				
				$spinner.css('visibility', 'visible');

			}
	
		}).done(function( xhr ) {
			
			$spinner.css('visibility', 'hidden');

			if( xhr.success ) {
				
				$custom_field_names_el.html( '' );

				var add_el = '<ul>';
				
				$.each( xhr.data.custom_fields, function( index , field_name ) {
					
					add_el += '<li><a href="javascript:void(0);" class="button button-small">' + field_name + '</a></li>';

				});

				add_el += '</ul>';
				
				$custom_field_names_el.html( add_el );

			} else {
					
				errors = xhr.data.errors;
				
				var alert_text = '';

				$.each( errors, function( error_code , content ) {
					
					if( content.msg ) {
						
						alert_text += content.msg;

					}

				});
				
				if( alert_text ) {
					
					alert( alert_text );
					
				}
				
			}
			
		});

	});
	
	$(document).on('click', '.all-custom-fields-names li a', function() {
		
		var $current_field = $(this).parent().parent().parent().parent();

		$current_field.find('.field-custom-field-name').val( $(this).text() );
		
	});
	
	$('.add-ignore-word').on('click', function() {
		
		var $current_field = $(this).parent().parent();
		
		var add_el = $current_field.find('.ignore-word-add-field').html();
		
		$current_field.find('.ignore-words-field').append( add_el );
		
		
	});
	
	$(document).on('click', '.remove-ignore-word', function() {
		
		$(this).parent().remove();
		
	});
	
});
</script>
<?php

	}
	
	public function do_ajax()
	{
		
		add_action( 'wp_ajax_' . $this->action . '_ajax_load_custom_fields' , array( $this , 'ajax_load_custom_fields' ) );
		
	}
	
	public function ajax_load_custom_fields()
	{
		
		global $APSC;

		if( empty( $_POST ) ) {

			return false;
			
		}
		
		$nonce_key = $this->nonce . '_ajax_load_custom_fields';
		
		if( empty( $_POST[$nonce_key] ) ) {

			return false;
			
		}
		
		check_ajax_referer( $nonce_key , $nonce_key );

		$all_custom_fields = $APSC->Helper->get_custom_fields();
		
		if( is_array( $all_custom_fields ) ) {
			
			wp_send_json_success( array( 'custom_fields' => $all_custom_fields ) );
			
		} else {
			
			$return_errors = array();
			$return_errors['error_load_custom_fields'] = array( 'msg' => sprintf( __( 'ERROR: %s' ) , __( 'Load custom fields' , $APSC->ltd ) ) );
			wp_send_json_error( array( 'errors' => $return_errors ) );

		}

		die();

	}
	
}

new APSC_Admin_Controller_Manager_Archive_Settings();

endif;
