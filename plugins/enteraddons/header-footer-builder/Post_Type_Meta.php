<?php
namespace Enteraddons\HeaderFooterBuilder;

/**
 * Enteraddons Post Type Meta class
 *
 * @package     Enteraddons
 * @author      ThemeLooks
 * @copyright   2022 ThemeLooks
 * @license     GPL-2.0-or-later
 *
 *
 */

class Post_Type_Meta {

	private static $instance = null;

	private function __construct() {
		add_action( 'add_meta_boxes', [ __CLASS__, 'add_custom_box' ] );
		add_action( 'save_post_ea_builder_template', [ __CLASS__, 'save_postdata' ] );
		add_filter( 'views_edit-ea_builder_template', [ __CLASS__, 'add_filters' ] );
		add_filter( 'manage_ea_builder_template_posts_columns', [ __CLASS__, 'add_col_ea_builder_template' ] );
		add_action( 'manage_ea_builder_template_posts_custom_column' , [ __CLASS__, 'builder_template_column' ], 10, 2 );
	}

	public static function getInstance() {
		if( is_null( self::$instance ) ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	public static function add_custom_box() {
		$screens = [ 'ea_builder_template' ];
		add_meta_box(
			'ea_header_footer_meta', // Unique ID
			esc_html__( 'Set Type', 'enteraddons' ), // Box title
			[ __CLASS__, 'meta_fields' ], // Content callback, must be of type callable
			$screens, // Post type
			'side', // context 
			'high' // priority
		);
	}

	public static function meta_fields( $post ) {
		$status = get_post_meta( absint( $post->ID ), '_ea_hf_status', true );
		$type = get_post_meta( absint( $post->ID ), '_ea_hf_type', true );
		$useHeader = get_post_meta( absint( $post->ID ), '_ea_use_on_header', true );
		$showonfof = get_post_meta( absint( $post->ID ), '_ea_hf_show_onfof', true );
		$excludePages = get_post_meta( absint( $post->ID ), '_ea_exclude_page', true );
		$excludePages = json_decode( $excludePages, true );
		
		$is_pro_active = \Enteraddons\Classes\Helper::is_pro_active();

		$optDisabled = 'disabled';
		$optText = esc_html__( 'On Page ( Pro )', 'enteraddons' );
		if( $is_pro_active ) {
			$optDisabled = '';
			$optText = esc_html__( 'On Page', 'enteraddons' );
		}

		?>
		<style>
			#pageparentdiv {
				display: none;
			}
		</style>
		<div class="ea-meta-field-group">
			<p class="post-attributes-label-wrapper hf-type-label-wrapper">
				<label class="post-attributes-label" for="ea_type_field"><?php esc_html_e( 'Is Active', 'enteraddons' ); ?>
					<input type="checkbox" <?php checked( $status, 'yes' ); ?> name="ea_hf_status" value="yes" id="ea_hf_status">
				</label>
			</p>
		</div>
		<div class="ea-meta-field-group">
			<p class="post-attributes-label-wrapper hf-type-label-wrapper">
				<label class="post-attributes-label" for="ea_type_field"><?php esc_html_e( 'Type', 'enteraddons' ); ?></label>
			</p>
			<select name="ea_hf_type" id="ea_type_field" class="postbox">
				<option value="header" <?php selected( $type, 'header' ); ?>><?php esc_html_e( 'Header', 'enteraddons' ); ?></option>
				<option value="footer" <?php selected( $type, 'footer' ); ?>><?php esc_html_e( 'Footer', 'enteraddons' ); ?></option>
			</select>
		</div>
		<div class="ea-meta-field-group">
			<p class="post-attributes-label-wrapper hf-type-label-wrapper">
				<label class="post-attributes-label" for="ea_type_field"><?php esc_html_e( 'Use On', 'enteraddons' ); ?></label>
			</p>
			<select name="use_on_header" id="ea_use_on_header" class="postbox">
				<option value="global" <?php selected( $useHeader, 'global' ); ?>><?php esc_html_e( 'Global', 'enteraddons' ); ?></option>
				<option <?php echo esc_attr( $optDisabled ); ?> value="on-page" <?php selected( $useHeader, 'on-page' ); ?>><?php echo esc_html( $optText ); ?></option>
			</select>
			<p><?php esc_html_e( 'Global settings work on all pages like posts, pages, singular, archive etc. On the other hand "on page" work on the specific page that you should set from page.', 'enteraddons' ); ?></p>
		</div>
		<div class="ea-meta-field-group">
			<p class="post-attributes-label-wrapper hf-type-label-wrapper">
				<label class="post-attributes-label" for="ea_type_field"><?php esc_html_e( 'Exclude Page', 'enteraddons' ); ?></label>
			</p>
			<select name="exclude_page[]" id="exclude_page" class="postbox ea-multiple-select" multiple="multiple">
				<?php 
				$pages = \Enteraddons\Classes\Helper::getPages();
				if( !empty( $pages ) ) {
					foreach( $pages as $page ) {
						$postName = $page->post_name;

						$getVal = '';
		                if( is_array( $excludePages ) && in_array( $postName , $excludePages ) ) {
		                  $getVal = $postName;
		                }
						echo '<option value="'.esc_html( $postName ).'" '.selected( $getVal, $postName, false ).'>'.esc_html( $page->post_title ).'</option>';
					}
				}
				?>
			</select>
			<p><?php esc_html_e( 'Select pages where you don\'t want to show this Header/Footer.', 'enteraddons' ); ?></p>
		</div>
		<div class="ea-meta-field-group">
			<p class="post-attributes-label-wrapper hf-type-label-wrapper">
				<label class="post-attributes-label" for="ea_hf_show_onfof"><?php esc_html_e( 'Show On 404 Page', 'enteraddons' ); ?>
					<input type="checkbox" <?php checked( $showonfof, 'yes' ); ?> name="ea_hf_show_onfof" value="yes" id="ea_hf_show_onfof">
				</label>
			</p>
		</div>
		<?php
		wp_nonce_field( 'ea_hf_meta_verify', '_ea_hf_meta_check' );
	}

	public static function save_postdata( $post_id ) {

		$metaNonceCheck = isset( $_REQUEST['_ea_hf_meta_check'] ) ? $_REQUEST['_ea_hf_meta_check'] : '';

		if( empty( $metaNonceCheck ) || !wp_verify_nonce( $metaNonceCheck, 'ea_hf_meta_verify' ) ) {
			return;
		}
		
		$status = !empty( $_POST['ea_hf_status'] ) ? $_POST['ea_hf_status'] : '';
		update_post_meta(
			absint( $post_id ),
			'_ea_hf_status',
			sanitize_text_field( $status )
		);

		$type = !empty( $_POST['ea_hf_type'] ) ? $_POST['ea_hf_type'] : '';
		update_post_meta(
			absint( $post_id ),
			'_ea_hf_type',
			sanitize_text_field( $type )
		);

		$useOn = !empty( $_POST['use_on_header'] ) ? $_POST['use_on_header'] : '';
		update_post_meta(
			absint( $post_id ),
			'_ea_use_on_header',
			sanitize_text_field( $useOn )
		);

		$excludePage = !empty( $_POST['exclude_page'] ) ? $_POST['exclude_page'] : [];
		update_post_meta(
			absint( $post_id ),
			'_ea_exclude_page',
			sanitize_text_field( json_encode( $excludePage ) )
		);
		
		$show_onfof = !empty( $_POST['ea_hf_show_onfof'] ) ? $_POST['ea_hf_show_onfof'] : '';
		update_post_meta(
			absint( $post_id ),
			'_ea_hf_show_onfof',
			sanitize_text_field( $show_onfof )
		);

	}

	public static function add_filters( $link ) {

		$reStoreLink = $link;
		$allLink = $link['all'];

		$adminUrl = admin_url('edit.php');

		$headerUrl = $adminUrl.'?ea_filter_type=header&post_type=ea_builder_template';
		$footerUrl = $adminUrl.'?ea_filter_type=footer&post_type=ea_builder_template';

		$newLink = [
			'all' 	 => $allLink,
			'header' => '<a href="'.esc_url( $headerUrl ).'">'.esc_html__( 'Header', 'enteraddons' ).'</a>',
			'footer' => '<a href="'.esc_url( $footerUrl ).'">'.esc_html__( 'Footer', 'enteraddons' ).'</a>'
		];

		array_splice( $reStoreLink, 0,1, $newLink );

		return $reStoreLink;

	}

	public static function add_col_ea_builder_template( $columns ) {
		
		unset( $columns['date'] );
		$columns['status'] = esc_html__( 'Status', 'enteraddons' );
		$columns['type']   = esc_html__( 'Type', 'enteraddons' );
		$columns['use_on'] = esc_html__( 'Use On', 'enteraddons' );
		$columns['date']   = esc_html__( 'Date', 'enteraddons' );

		return $columns;
	}

	public static function builder_template_column( $column, $post_id ) {

	    switch( $column ) {
	        case 'status' :
	        	$r = get_post_meta( $post_id , '_ea_hf_status', true );
	        	if( $r == 'yes' ) {
	        		echo esc_html__( 'Active', 'enteraddons' );
	        	} else {
	        		echo esc_html__( 'Inactive', 'enteraddons' );
	        	}
	            break;
	        case 'type' :
	            echo get_post_meta( $post_id , '_ea_hf_type' , true ); 
	            break;
	        case 'use_on' :
	            echo get_post_meta( $post_id , '_ea_use_on_header' , true ); 
	            break;
	    }
	}

} // END CLASS
