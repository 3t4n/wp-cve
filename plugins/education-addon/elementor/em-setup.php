<?php
/*
 * All Elementor Init
 * Author & Copyright: NicheAddon
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( !class_exists('Education_Elementor_Addon_Core_Elementor_init') ){
	class Education_Elementor_Addon_Core_Elementor_init{

		/*
		 * Minimum Elementor Version
		*/
		const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

		/*
		 * Minimum PHP Version
		*/
		const MINIMUM_PHP_VERSION = '5.6';

    /*
	   * Instance
	  */
		private static $instance;

		/*
		 * Main Education Addon plugin Class Constructor
		*/
		public function __construct(){
			add_action( 'plugins_loaded', [ $this, 'init' ] );

			// Js Enqueue
			add_action( 'elementor/frontend/after_enqueue_scripts', function() {
				wp_enqueue_script( 'naedu-elementor', plugins_url( '/', __FILE__ ) . '/js/naedu-elementor.js', [ 'jquery' ], false, true );
			} );

		}

		/*
		 * Class instance
		*/
		public static function getInstance(){
			if (null === self::$instance) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/*
		 * Initialize the plugin
		*/
		public function init() {

			// Check for required Elementor version
			if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
				return;
			}

			// Check for required PHP version
			if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
				add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
				return;
			}

			// elementor Custom Group Controls Include
			self::controls_helper();

			// elementor categories
			add_action( 'elementor/elements/categories_registered', [ $this, 'basic_widget_categories' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'naedu_pro_widget_categories' ] );

			// Elementor Widgets Registered
			 add_action( 'elementor/widgets/widgets_registered', [ $this, 'naedu_basic_widgets_registered' ] );

		}

		/*
		 * Admin notice
		 * Warning when the site doesn't have a minimum required Elementor version.
		*/
		public function admin_notice_minimum_elementor_version() {
			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
			$message = sprintf(
				/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'education-addon' ),
				'<strong>' . esc_html__( 'Education Addon', 'education-addon' ) . '</strong>',
				'<strong>' . esc_html__( 'Elementor', 'education-addon' ) . '</strong>',
				 self::MINIMUM_ELEMENTOR_VERSION
			);
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		}

		/*
		 * Admin notice
		 * Warning when the site doesn't have a minimum required PHP version.
		*/
		public function admin_notice_minimum_php_version() {
			if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
			$message = sprintf(
				/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
				esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'education-addon' ),
				'<strong>' . esc_html__( 'Education Addon', 'education-addon' ) . '</strong>',
				'<strong>' . esc_html__( 'PHP', 'education-addon' ) . '</strong>',
				 self::MINIMUM_PHP_VERSION
			);
			printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
		}

		/*
		 * Class Group Controls
		*/
		public static function controls_helper(){
			$group_controls = ['lib'];
			foreach($group_controls as $control){
				if ( file_exists( plugin_dir_path( __FILE__ ) . '/lib/'.$control.'.php' ) ){
					require_once( plugin_dir_path( __FILE__ ) . '/lib/'.$control.'.php' );
				}
			}
		}

		/*
		 * Widgets elements categories
		*/
		public function basic_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naedu-basic-category',
				[
					'title' => __( 'Education Basic Elements : By Niche Addons', 'education-addon' ),
				]
			);
		}
		public function naedu_pro_widget_categories($elements_manager){
			$elements_manager->add_category(
				'naedu-pro-category',
				[
					'title' => __( 'Education Pro Elements : By Niche Addons', 'education-addon' ),
				]
			);
		}

		/*
		 * Widgets registered
		*/
		public function naedu_basic_widgets_registered(){
			// init widgets
			$basic_dir = plugin_dir_path( __FILE__ ) . '/widgets/basic/';
			// Open a directory, and read its contents
			if (is_dir($basic_dir)){
			  $basic_dh = opendir($basic_dir);
		    while (($basic_file = readdir($basic_dh)) !== false){
		    	if (!in_array(trim($basic_file), ['.', '..'])) {
						$basic_template_file = plugin_dir_path( __FILE__ ) . '/widgets/basic/'.$basic_file;
						if ( $basic_template_file && is_readable( $basic_template_file ) ) {
							include_once $basic_template_file;
						}
			    }
		    }
		    closedir($basic_dh);
			}
		}

	} //end class

	if (class_exists('Education_Elementor_Addon_Core_Elementor_init')){
		Education_Elementor_Addon_Core_Elementor_init::getInstance();
	}

}

/* Add Featured Image support in event organizer */
add_post_type_support( 'tribe_organizer', 'thumbnail' );

/* Excerpt Length */
class Education_Elementor_Addon_Excerpt {
  public static $length = 55;
  public static $types = array(
    'short' => 25,
    'regular' => 55,
    'long' => 100
  );
  public static function length($new_length = 55) {
    Education_Elementor_Addon_Excerpt::$length = $new_length;
    add_filter('excerpt_length', 'Education_Elementor_Addon_Excerpt::new_length');
    Education_Elementor_Addon_Excerpt::output();
  }
  public static function new_length() {
    if ( isset(Education_Elementor_Addon_Excerpt::$types[Education_Elementor_Addon_Excerpt::$length]) )
      return Education_Elementor_Addon_Excerpt::$types[Education_Elementor_Addon_Excerpt::$length];
    else
      return Education_Elementor_Addon_Excerpt::$length;
  }
  public static function output() {
    the_excerpt();
  }
}

// Custom Excerpt Length
function naedu_excerpt($length = 55) {
  Education_Elementor_Addon_Excerpt::length($length);
}

function naedu_new_excerpt_more( $more ) {
  return '...';
}
add_filter('excerpt_more', 'naedu_new_excerpt_more');

function naedu_paging_nav($numpages = '', $pagerange = '', $paged='') {

    if (empty($pagerange)) {
      $pagerange = 2;
    }
    if (empty($paged)) {
      $paged = 1;
    } else {
      $paged = $paged;
    }
    if ($numpages == '') {
      global $wp_query;
      $numpages = $wp_query->max_num_pages;
      if (!$numpages) {
        $numpages = 1;
      }
    }
    global $wp_query;
    $big = 999999999;
    if ($wp_query->max_num_pages != '1' ) { ?>
    <div class="naedu-pagination">
      <?php echo paginate_links( array(
        'base' => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
        'format' => '?paged=%#%',
        'prev_text' => '<i class="fa fa-angle-double-left" aria-hidden="true"></i>',
        'next_text' => '<i class="fa fa-angle-double-right" aria-hidden="true"></i>',
        'current' => $paged,
        'total' => $numpages,
        'type' => 'list'
      )); ?>
    </div>
  <?php }
}

function naedu_clean_string($string) {
  $string = str_replace(' ', '', $string);
  return preg_replace('/[^\da-z ]/i', '', $string);
}

/* Validate px entered in field */
function naedu_core_check_px( $num ) {
  return ( is_numeric( $num ) ) ? $num . 'px' : $num;
}

function naedu_add_category_image( $taxonomy ) { ?>
  <div class="form-field term-group">
    <label for="lp-taxonomy-image-id"><?php esc_html_e( 'Image', 'education-addon' ); ?></label>
    <input type="hidden" id="lp-taxonomy-image-id" name="lp-taxonomy-image-id" class="custom_media_url" value="">
    <div id="category-image-wrapper"></div>
    <p>
      <input type="button" class="button button-secondary lp_tax_media_button" id="lp_tax_media_button" name="lp_tax_media_button" value="<?php esc_html_e( 'Add Image', 'education-addon' ); ?>" />
      <input type="button" class="button button-secondary lp_tax_media_remove" id="lp_tax_media_remove" name="lp_tax_media_remove" value="<?php esc_html_e( 'Remove Image', 'education-addon' ); ?>" />
    </p>
  </div>
  <?php }
add_action('course_category_add_form_fields', 'naedu_add_category_image', 10, 2);

function naedu_save_category_image( $term_id, $tt_id ) {
  if ( isset( $_POST['lp-taxonomy-image-id'] ) && '' !== $_POST['lp-taxonomy-image-id'] ) {
    add_term_meta(
      $term_id,
      'lp-taxonomy-image-id',
      absint( $_POST['lp-taxonomy-image-id'] ),
      true
    );
  }
}
add_action( 'created_course_category', 'naedu_save_category_image',  10, 2 );

function naedu_update_category_image( $term, $taxonomy ) { ?>
  <tr class="form-field term-group-wrap">
    <th scope="row">
      <label for="lp-taxonomy-image-id"><?php esc_html_e( 'Image', 'education-addon' ); ?></label>
    </th>
    <td>
      <?php $image_id = get_term_meta( $term->term_id, 'lp-taxonomy-image-id', true ); ?>
      <input type="hidden" id="lp-taxonomy-image-id" name="lp-taxonomy-image-id" value="<?php echo esc_attr( $image_id ); ?>">
      <div id="category-image-wrapper">
        <?php if( $image_id ) { ?>
          <?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
        <?php } ?>
      </div>
      <p>
        <input type="button" class="button button-secondary lp_tax_media_button" id="lp_tax_media_button" name="lp_tax_media_button" value="<?php esc_html_e( 'Add Image', 'education-addon' ); ?>" />
        <input type="button" class="button button-secondary lp_tax_media_remove" id="lp_tax_media_remove" name="lp_tax_media_remove" value="<?php esc_html_e( 'Remove Image', 'education-addon' ); ?>" />
      </p>
    </td>
  </tr>
<?php }
add_action( 'course_category_edit_form_fields', 'naedu_update_category_image', 10, 2 );

function naedu_updated_category_image( $term_id, $tt_id ) {
  if ( isset( $_POST['lp-taxonomy-image-id'] ) && '' !== $_POST['lp-taxonomy-image-id'] ) {
    update_term_meta( $term_id, 'lp-taxonomy-image-id', absint( $_POST['lp-taxonomy-image-id'] ) );
  } else {
    update_term_meta( $term_id, 'lp-taxonomy-image-id', '' );
  }
}
add_action( 'edited_course_category', 'naedu_updated_category_image', 10, 2);

function naedu_load_media() {
  if ( !isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != 'course_category' ) {
    return;
  }
  wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'naedu_load_media' );

function naedu_add_script() {
  if ( !isset( $_GET['taxonomy'] ) || $_GET['taxonomy'] != 'course_category' ) {
    return;
  } ?>
  <script>
  jQuery(document).ready( function($) {
    _wpMediaViewsL10n.insertIntoPost = '<?php esc_html_e( "Insert", "education-addon" ); ?>';
    function ct_media_upload(button_class) {
      var _custom_media = true, _orig_send_attachment = wp.media.editor.send.attachment;
      $('body').on('click', button_class, function(e) {
        var button_id = '#'+$(this).attr('id');
        var send_attachment_bkp = wp.media.editor.send.attachment;
        var button = $(button_id);
        _custom_media = true;
        wp.media.editor.send.attachment = function(props, attachment){
          if( _custom_media ) {
            $('#lp-taxonomy-image-id').val(attachment.id);
            $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
            $( '#category-image-wrapper .custom_media_image' ).attr( 'src',attachment.url ).css( 'display','block' );
          } else {
            return _orig_send_attachment.apply( button_id, [props, attachment] );
          }
        }
        wp.media.editor.open(button); return false;
      });
    }
    ct_media_upload('.lp_tax_media_button.button');
    $('body').on('click','.lp_tax_media_remove',function(){
      $('#lp-taxonomy-image-id').val('');
      $('#category-image-wrapper').html('<img class="custom_media_image" src="" style="margin:0;padding:0;max-height:100px;float:none;" />');
    });
    $(document).ajaxComplete(function(event, xhr, settings) {
      var queryStringArr = settings.data.split('&');
      if( $.inArray('action=add-tag', queryStringArr) !== -1 ){
        var xml = xhr.responseXML;
        $response = $(xml).find('term_id').text();
        if($response!=""){
          // Clear the thumb image
          $('#category-image-wrapper').html('');
        }
      }
    });
  });
  </script>
<?php }
add_action( 'admin_footer', 'naedu_add_script' );

