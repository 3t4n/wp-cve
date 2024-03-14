<?php               namespace ng_bootstrap;
/*
Plugin Name: Bootstrap Modals
Plugin URI: http://wpbeaches.com
Description: Using Bootstrap Modals in WordPress
Author: Neil Gee
Version: 1.3.2
Author URI:http://wpbeaches.com
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: bootstrap-modal
Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) {
				die;
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_textdomain' );
/**
* Register our text domain.
*
* @since 1.3.0
*/
function load_textdomain() {
	load_plugin_textdomain( 'bootstrap-modal', false, basename( dirname( __FILE__ ) ) . '/languages' );
}


add_action( 'wp_enqueue_scripts',  __NAMESPACE__ . '\\scripts_styles' );
/**
* Register and Enqueue Scripts and Styles.
*
* @since 1.0.0
*/
//Script-tac-ulous -> All the Scripts and Styles Registered and Enqueued, scripts first - then styles
function scripts_styles() {
  $options = get_option( 'bootstrap_modal_settings' );	
  $options_default = array(
    'ng_modal_disable_bootstrap' => '',  
    );
    $options = wp_parse_args( $options, $options_default );

	wp_register_script( 'modaljs' , plugins_url( '/js/bootstrap.min.js',  __FILE__), array( 'jquery' ), '3.3.7', true );
	wp_register_style( 'modalcss' , plugins_url( '/css/bootstrap.css',  __FILE__), '' , '3.3.7', 'all' );
	wp_register_style( 'custommodalcss' , plugins_url( '/css/custommodal.css',  __FILE__), '' , '3.3.7', 'all' );
	

	if( $options['ng_modal_disable_bootstrap'] == false ) {
		wp_enqueue_script( 'modaljs' );
		wp_enqueue_style( 'modalcss' );
 	}
}

add_action( 'admin_enqueue_scripts',  __NAMESPACE__ . '\\admin_modal' );
/**
 * Add scripts in back-end.
 *
 * @since 1.3.0
 */
function admin_modal($hook) {
    if ( 'settings_page_bootstrap-modal' != $hook ) {
        return;
    }
    wp_enqueue_script( 'modaljs' , plugins_url( '/js/bootstrap.min.js',  __FILE__), array( 'jquery' ), '3.3.7', true );
    wp_enqueue_style( 'modalcss' , plugins_url( '/css/bootstrap.css',  __FILE__), '' , '3.3.7', 'all' );
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker-alpha', plugins_url( '/js/wp-color-picker-alpha.min.js',  __FILE__ ), array( 'wp-color-picker' ), '2.1.2', true );
}


add_action( 'admin_menu', __NAMESPACE__ . '\\plugin_page' );
/**
 * Create the plugin option page.
 *
 * @since 1.3.0
 */

function plugin_page() {

/**
* Use the add options_page function
* add_options_page( $page_title, $menu_title, $capability, $menu-slug, $function )
*/

add_options_page(
	__( 'Bootstrap Modal Plugin','bootstrap-modal' ), //$page_title
	__( 'Bootstrap Modal ', 'bootstrap-modal' ), //$menu_title
	'manage_options', //$capability
	'bootstrap-modal', //$menu-slug
	__NAMESPACE__ . '\\plugin_options_page' //$callbackfunction
	);
}


/**
 * Include the plugin option page.
 *
 * @since 1.3.0
 */

function plugin_options_page() {

    if( !current_user_can( 'manage_options' ) ) {

      wp_die( "Hall and Oates 'Say No Go'" );
    }

   require( 'inc/options-page-wrapper.php' );
}


add_action('admin_init', __NAMESPACE__ . '\\plugin_settings');
/**
 * Register our option fields
 *
 * @since 1.3.0
 */
// Check validation
function plugin_settings() {
  register_setting(
        'ng_bm_settings_group', //option group name
        'bootstrap_modal_settings'// options setting name
     //  __NAMESPACE__ . '\\bootstrap_modal_validate_input' //sanitize the inputs
  );

  add_settings_section(
        'ng_bootstrap_modal_section', //declare the section id
        'Bootstrap Modal Settings', //page title
         __NAMESPACE__ . '\\ng_bootstrap_section_callback', //callback function below
        'bootstrap-modal' //page that it appears on
    );

    add_settings_field(
        'ng_modal_use_css', //unique id of field
        'Custom Modal CSS', //title
         __NAMESPACE__ . '\\ng_modal_use_css_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_overlay', //unique id of field
        'Overlay Background Color', //title
         __NAMESPACE__ . '\\ng_overlay_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_overlay_opacity', //unique id of field
        'Overlay Opacity', //title
         __NAMESPACE__ . '\\ng_overlay_opacity_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_modal_color', //unique id of field
        'Modal Text Color', //title
         __NAMESPACE__ . '\\ng_modal_color_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_modal_color_background', //unique id of field
        'Modal Background Color ', //title
         __NAMESPACE__ . '\\ng_modal_color_background_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_button_background', //unique id of field
        'Close Button Background Color', //title
         __NAMESPACE__ . '\\ng_button_background_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_button_color', //unique id of field
        'Close Button Text Color', //title
         __NAMESPACE__ . '\\ng_button_color_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );
    add_settings_field(
        'ng_button_background_hover', //unique id of field
        'Close Button Background Hover Color ', //title
         __NAMESPACE__ . '\\ng_button_background_hover_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_button_color_hover', //unique id of field
        'Close Button Text Hover Color', //title
         __NAMESPACE__ . '\\ng_button_color_hover_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
	);
	
	add_settings_field(
        'ng_modal_header_alignment', //unique id of field
        'Modal Header Alignment', //title
         __NAMESPACE__ . '\\ng_modal_header_alignment_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_modal_alignment', //unique id of field
        'Modal Body Alignment', //title
         __NAMESPACE__ . '\\ng_modal_alignment_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_modal_footer_alignment', //unique id of field
        'Modal Footer Alignment', //title
         __NAMESPACE__ . '\\ng_modal_footer_alignment_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_modal_use_borders', //unique id of field
        'Use Modal Borders', //title
         __NAMESPACE__ . '\\ng_modal_use_borders_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

    add_settings_field(
        'ng_modal_border_color', //unique id of field
        'Modal Border Color', //title
         __NAMESPACE__ . '\\ng_modal_border_color_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
	);
	
	add_settings_field(
        'ng_modal_disable_bootstrap', //unique id of field
        'Disable Plugins Bootstrap Files', //title
        __NAMESPACE__ . '\\ng_modal_disable_bootstrap_callback', //callback function below
        'bootstrap-modal', //page that it appears on
        'ng_bootstrap_modal_section' //settings section declared in add_settings_section
    );

}

/**
 * Register our section call back
 * (not much happening here)
 * @since 1.3.0
 */

function ng_bootstrap_section_callback() {

}

/**
 *  Add default rgba overlay color
 *
 * @link https://github.com/23r9i0/wp-color-picker-alpha/blob/master/dist/wp-color-picker-alpha.min.js
 *
 * @since 1.3.0
 */

function ng_overlay_callback() {
$options = get_option( 'bootstrap_modal_settings' );

if( !isset( $options['ng_overlay'] ) ) $options['ng_overlay'] = 'rgba(0,0,0,0.5)';

echo '<input type="text" class="color-picker" data-alpha="true" data-default-color="rgba(0,0,0,0.5)" name="bootstrap_modal_settings[ng_overlay]" value="' . sanitize_text_field($options['ng_overlay']) . '"/>';

}

/**
 *  Add default rgba overlay opacity
 *
 *
 * @since 1.3.0
 */

function ng_overlay_opacity_callback() {
$options = get_option( 'bootstrap_modal_settings' );

if( !isset( $options['ng_overlay_opacity'] ) ) $options['ng_overlay_opacity'] = 1;

echo '<input type=range min=0 max=1 step=0.00392156863 class="regular-text" name="bootstrap_modal_settings[ng_overlay_opacity]" value="' . sanitize_text_field($options['ng_overlay_opacity']) . '"/>';

}

/**
 *  Button color
 *
 *
 * @since 1.3.0
 */

function ng_button_color_callback() {
$options = get_option( 'bootstrap_modal_settings' );

if( !isset( $options['ng_button_color'] ) ) $options['ng_button_color'] = 'rgba(255,255,255,1)';

echo '<input type="text" class="color-picker" data-alpha="true" data-default-color="rgba(255,255,255,1)" name="bootstrap_modal_settings[ng_button_color]" value="' . sanitize_text_field($options['ng_button_color']) . '"/>';

}


/**
 *  Button Backgroundcolor
 *
 *
 * @since 1.3.0
 */

function ng_button_background_callback() {
$options = get_option( 'bootstrap_modal_settings' );

if( !isset( $options['ng_button_background'] ) ) $options['ng_button_background'] = 'rgba(0,0,0,1)';

echo '<input type="text" class="color-picker" data-alpha="true" data-default-color="rgba(0,0,0,1)" name="bootstrap_modal_settings[ng_button_background]" value="' . sanitize_text_field($options['ng_button_background']) . '"/>';

}

/**
 *  Button color
 *
 *
 * @since 1.3.0
 */

function ng_button_color_hover_callback() {
$options = get_option( 'bootstrap_modal_settings' );

if( !isset( $options['ng_button_color_hover'] ) ) $options['ng_button_color_hover'] = 'rgba(255,255,255,1)';

echo '<input type="text" class="color-picker" data-alpha="true" data-default-color="rgba(255,255,255,1)" name="bootstrap_modal_settings[ng_button_color_hover]" value="' . sanitize_text_field($options['ng_button_color_hover']) . '"/>';

}


/**
 *  Button Backgroundcolor
 *
 *
 * @since 1.3.0
 */

function ng_button_background_hover_callback() {
$options = get_option( 'bootstrap_modal_settings' );

if( !isset( $options['ng_button_background_hover'] ) ) $options['ng_button_background_hover'] = 'rgba(0,0,0,.5)';

echo '<input type="text" class="color-picker" data-alpha="true" data-default-color="rgba(0,0,0,.5)" name="bootstrap_modal_settings[ng_button_background_hover]" value="' . sanitize_text_field($options['ng_button_background_hover']) . '"/>';

}

/**
 *  Modal color
 *
 *
 * @since 1.3.0
 */

function ng_modal_color_callback() {
$options = get_option( 'bootstrap_modal_settings' );

if( !isset( $options['ng_modal_color'] ) ) $options['ng_modal_color'] = 'rgba(0,0,0,1)';

echo '<input type="text" class="color-picker" data-alpha="true" data-default-color="rgba(0,0,0,1)" name="bootstrap_modal_settings[ng_modal_color]" value="' . sanitize_text_field($options['ng_modal_color']) . '"/>';

}


/**
 *  Modal Background color
 *
 *
 * @since 1.3.0
 */

function ng_modal_color_background_callback() {
$options = get_option( 'bootstrap_modal_settings' );

if( !isset( $options['ng_modal_color_background'] ) ) $options['ng_modal_color_background'] = 'rgba(255,255,255,1)';

echo '<input type="text" class="color-picker" data-alpha="true" data-default-color="rgba(255,255,255,1)" name="bootstrap_modal_settings[ng_modal_color_background]" value="' . sanitize_text_field($options['ng_modal_color_background']) . '"/>';

}

/**
 *  Header Alignment
 *
 *
 * @since 1.3.1
 */

 function ng_modal_header_alignment_callback() {
	$options = get_option( 'bootstrap_modal_settings' );
	
	if( !isset( $options['ng_modal_header_alignment'] ) ) $options['ng_modal_header_alignment'] = 'left';
	?>
	
	<select name="bootstrap_modal_settings[ng_modal_header_alignment]" id="ng_modal_header_alignment">
		<option selected="selected" value="left" <?php selected($options['ng_modal_header_alignment'], 'left'); ?>>Left</option>
		<option value="center" <?php selected($options['ng_modal_header_alignment'], 'center'); ?>>Center</option>
	  <option value="right" <?php selected($options['ng_modal_header_alignment'], 'right'); ?>>Right</option>
	</select>
	<?php
	
	}

/**
 *  Modal Alignment
 *
 *
 * @since 1.3.0
 */

function ng_modal_alignment_callback() {
$options = get_option( 'bootstrap_modal_settings' );

if( !isset( $options['ng_modal_alignment'] ) ) $options['ng_modal_alignment'] = 'left';
?>

<select name="bootstrap_modal_settings[ng_modal_alignment]" id="ng_modal_alignment">
	<option selected="selected" value="left" <?php selected($options['ng_modal_alignment'], 'left'); ?>>Left</option>
	<option value="center" <?php selected($options['ng_modal_alignment'], 'center'); ?>>Center</option>
  <option value="right" <?php selected($options['ng_modal_alignment'], 'right'); ?>>Right</option>
</select>
<?php

}

/**
 *  Modal Footer Alignment
 *
 *
 * @since 1.3.0
 */

function ng_modal_footer_alignment_callback() {
$options = get_option( 'bootstrap_modal_settings' );

if( !isset( $options['ng_modal_footer_alignment'] ) ) $options['ng_modal_footer_alignment'] = 'right';
?>

<select name="bootstrap_modal_settings[ng_modal_footer_alignment]" id="ng_modal_footer_alignment">
	<option selected="selected" value="left" <?php selected($options['ng_modal_footer_alignment'], 'left'); ?>>Left</option>
	<option value="center" <?php selected($options['ng_modal_footer_alignment'], 'center'); ?>>Center</option>
  <option value="right" <?php selected($options['ng_modal_footer_alignment'], 'right'); ?>>Right</option>
</select>
<?php

}

/**
 *  Use Modal Border
 *
 *
 * @since 1.3.0
 */

 function ng_modal_use_borders_callback() {
 $options = get_option( 'bootstrap_modal_settings' );
 if( !isset( $options['ng_modal_use_borders'] ) ) $options['ng_modal_use_borders'] = '';

 ?>
  <fieldset>
  	<label for="ng_modal_use_borders">
  		<input name="bootstrap_modal_settings[ng_modal_use_borders]" type="checkbox" id="ng_modal_use_borders" value="1"<?php checked( 1, $options['ng_modal_use_borders'], true ); ?> />
  		<span><?php esc_attr_e( 'Use borders in modal', 'bootstrap-modal' ); ?></span>
  	</label>
  </fieldset>
<?php
 }

 /**
  *   Modal Border Color
  *
  *
  * @since 1.3.0
  */

  function ng_modal_border_color_callback() {
  $options = get_option( 'bootstrap_modal_settings' );

  if( !isset( $options['ng_modal_border_color'] ) ) $options['ng_modal_border_color'] = '#e5e5e5';

  echo '<input type="text" class="color-picker" data-alpha="true" data-default-color="#e5e5e5" name="bootstrap_modal_settings[ng_modal_border_color]" value="' . sanitize_text_field($options['ng_modal_border_color']) . '"/>';
  }

 /**
   *  Disable Plugins Bootstrap Files
   *
   *
   * @since 1.3.1
   */

   function ng_modal_disable_bootstrap_callback() {
	$options = get_option( 'bootstrap_modal_settings' );
	if( !isset( $options['ng_modal_disable_bootstrap'] ) ) $options['ng_modal_disable_bootstrap'] = '';
 
	?>
	 <fieldset>
		 <label for="ng_modal_disable_bootstrap">
			 <input name="bootstrap_modal_settings[ng_modal_disable_bootstrap]" type="checkbox" id="ng_modal_disable_bootstrap" value="1"<?php checked( 1, $options['ng_modal_disable_bootstrap'], true ); ?> />
			 <span><?php esc_attr_e( 'Disable Plugins Bootstrap Files', 'bootstrap-modal' ); ?></span>
		 </label>
	 </fieldset>
   <?php
	}

  /**
   *  Use Modal CSS
   *
   *
   * @since 1.3.0
   */

   function ng_modal_use_css_callback() {
   $options = get_option( 'bootstrap_modal_settings' );
   if( !isset( $options['ng_modal_use_css'] ) ) $options['ng_modal_use_css'] = '';

   ?>
    <fieldset>
    	<label for="ng_modal_use_css">
    		<input name="bootstrap_modal_settings[ng_modal_use_css]" type="checkbox" id="ng_modal_use_css" value="1"<?php checked( 1, $options['ng_modal_use_css'], true ); ?> />
    		<span><?php esc_attr_e( 'Use below custom CSS', 'bootstrap-modal' ); ?></span>
    	</label>
    </fieldset>
  <?php
   }




add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\inline_modal' );   
/**
 *  Adding inline CSS
 *
 *
 * @since 1.3.0
 */
function inline_modal() {

      $options = get_option('bootstrap_modal_settings');

      $options_default = array(
          'ng_overlay'                 => '',
          'ng_overlay_opacity'         => '',
          'ng_button_color'            => 'rgba(255,255,255,1)',
          'ng_button_background'       => 'rgba(0,0,0,1)',
          'ng_button_color_hover'      => 'rgba(255,255,255,1)',
          'ng_button_background_hover' => 'rgba(0,0,0,0.5)',
          'ng_modal_color'             => 'rgba(0,0,0,1)',
		  'ng_modal_color_background'  => 'rgba(255,255,255,1)',
		  'ng_modal_header_alignment'  => 'left',
          'ng_modal_alignment'         => 'left',
          'ng_modal_footer_alignment'  => 'right',
          'ng_modal_border_color'      => '',
          'ng_modal_use_borders'       => '',
		  'ng_modal_use_css'           => '',
		  'ng_modal_disable_bootstrap' => '',
		  
      );

      $options = wp_parse_args( $options, $options_default );


		$ng_overlay                 = $options['ng_overlay'];
		$ng_overlay_opacity         = $options['ng_overlay_opacity'];
		$ng_button_color            = $options['ng_button_color'];
		$ng_button_background       = $options['ng_button_background'];
		$ng_button_color_hover      = $options['ng_button_color_hover'];
		$ng_button_background_hover = $options['ng_button_background_hover'];
		$ng_modal_color             = $options['ng_modal_color'];
		$ng_modal_color_background  = $options['ng_modal_color_background'];
		$ng_modal_header_alignment  = $options['ng_modal_header_alignment'];
		$ng_modal_alignment         = $options['ng_modal_alignment'];
		$ng_modal_footer_alignment  = $options['ng_modal_footer_alignment'];
		$ng_modal_border_color      = $options['ng_modal_border_color'];
		$ng_modal_use_borders       = $options['ng_modal_use_borders'];
		$ng_modal_use_css           = $options['ng_modal_use_css'];
		$ng_modal_disable_bootstrap = $options['ng_modal_disable_bootstrap'];
	   




        //All the user input CSS settings as set in bootstrap modal settings
      if($ng_modal_use_css) {
        $modal_custom_css = "
		.modal .modal-backdrop,
		.modal {
          background: {$ng_overlay};
        }
        .modal .modal-backdrop.in {
          opacity: {$ng_overlay_opacity};
        }
        .modal-dialog .modal-content .close {
          color: {$ng_button_color};
		  background: {$ng_button_background};
		  opacity: 1;
		  text-shadow: none;
		  position: absolute;
		  right: -15px;
		  top: -15px;
        }
        .modal-dialog .modal-content .close:hover {
          color: {$ng_button_color_hover};
		  background: {$ng_button_background_hover};
		  border: none;
		}
		.modal-dialog .modal-title {
			color: {$ng_modal_color};
		}
        .modal-dialog .modal-content {
          color: {$ng_modal_color};
          background: {$ng_modal_color_background};
		}
		.modal-dialog .modal-header{
			text-align:{$ng_modal_header_alignment};
			position: relative;
		}
        .modal-dialog {
          text-align:{$ng_modal_alignment};
        }
        .modal-dialog .modal-footer {
          text-align:{$ng_modal_footer_alignment};
		}
		.admin-bar  .modal.in .modal-dialog {
			top: 46px;
		}
		@media screen and (min-width: 783px) {
			.admin-bar  .modal.in .modal-dialog {
				top: 32px;
			}
		}
        ";
        if($ng_modal_use_borders) {
          $modal_custom_css .= "
		.modal-dialog .modal-header {
		  border-bottom: 1px solid {$ng_modal_border_color};
        }
        .modal-dialog .modal-footer {
          border-top: 1px solid {$ng_modal_border_color};
        }";
        }
        else {
          $modal_custom_css .="
		.modal-dialog .modal-header {
		  border-bottom: none;
        }
        .modal-dialog .modal-footer {
          border-top: none;
        }
        ";
        }
      }
      else {
        $modal_custom_css = "";
	  }
	  if( $options['ng_modal_disable_bootstrap'] == true ) {
		
		$modal_custom_css .= "
		.modal-dialog .modal-content .close {
			color: {$ng_button_color};
			background: {$ng_button_background};
			opacity: 1;
			text-shadow: none;
			position: relative;
			right: 0;
			top: 0;
		  }";
		
		wp_enqueue_style( 'custommodalcss' );
		//add the above custom CSS via wp_add_inline_style
		wp_add_inline_style( 'custommodalcss', $modal_custom_css );
	  }
	  else {
		  //add the above custom CSS via wp_add_inline_style
		wp_add_inline_style( 'modalcss', $modal_custom_css );  
	  }
}


add_shortcode( 'bs_modal', __NAMESPACE__ . '\\bm_shortcode' );
/**
 *  Output Bootstrap Modal shortcode
 *
 *
 * @since 1.3.0
 */
function bm_shortcode( $atts, $content = null ) {

	$atts = shortcode_atts(
		array(
			'id' 	      => '',
			'class'     => '',
			'header'    => '',
			'footer'    => '',
			'arialabel' => '',
		),
		$atts,
		'bs_modal'
		);

		?>
	<div id="<?php esc_attr_e( $atts['id']); ?>" class="modal fade" tabindex="-1" role="dialog" <?php if( $atts['arialabel'] ) : ?>aria-labelledby="<?php esc_attr_e($atts['arialabel']); ?>"<?php endif; ?>>
		<div class="modal-dialog <?php esc_attr_e($atts['class']); ?>" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button class="close" type="button" data-dismiss="modal" aria-label="Close">×</button>
									<?php if( $atts['header'] ) : ?>
						<h4 class="modal-title" <?php if( $atts['arialabel'] ) : ?>aria-labelledby="<?php esc_attr_e($atts['arialabel']); ?>"<?php endif; ?>><?php echo $atts['header']; ?></h4>
										<?php endif; ?>
				</div>
				<div class="modal-body"><?php echo $content; ?></div>
								<?php if( $atts['footer'] ) : ?>
				<div class="modal-footer"><?php echo $atts['footer']; ?></div>
						<?php endif; ?>
			</div>
		</div>
	</div>
<?php
}

add_shortcode( 'bs_trigger', __NAMESPACE__ . '\\bm__trigger_shortcode' );			
/**
 *  Output Bootstrap Modal Trigger shortcode
 *
 *
 * @since 1.3.0
 */
function bm__trigger_shortcode( $atts, $content = null ) {

	$atts = shortcode_atts(
		array(
			'id' 	    => '',
			'class'     => '',
			'arialabel' => '',
		),
		$atts,
			'bs_trigger'
		);

		?>
		<a class="btn btn-primary btn-lg <?php esc_attr_e($atts['class']); ?>" href="#<?php esc_attr_e($atts['id']); ?>" data-toggle="modal"><?php echo $content; ?></a>

<?php
}

