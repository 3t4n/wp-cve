<?php
/**
 * Settings Page View Class
 *
 * @author 		MojofyWP
 * @package 	builder/settings-page
 * 
 */

if ( !class_exists('WRSL_Builder_Settings_View') ) :

class WRSL_Builder_Settings_View {

	/**
	 * Hook prefix
	 *
	 * @access private
	 * @var string
	 */
	private $_hook_prefix = null;

	/**
	 * Class Constructor
	 *
	 * @access private
	 */
    function __construct() {

		// setup variables
		$this->_hook_prefix = wrsl()->plugin_hook() . 'builder_settings/view/';


    }

	/**
	 * sample render
	 *
	 * @access public
	 */
	public function render_header( $subtitle = '' ) {

		ob_start();
		?>
		<h1 class="wrsl-builder-title">
			<?php if ( !empty( $subtitle ) ) : ?>
				<span class="subtitle"><?php echo esc_attr( $subtitle ); ?></span>
			<?php endif; ?>
		</h1>
		<?php
		$html = ob_get_clean();

		return apply_filters( $this->_hook_prefix . 'render_header' , ( !empty( $html ) ? $html : '' ) , $subtitle , $this );
	}


	/**
	 * Render Overview
	 *
	 * @access public
	 */
	public function render_overview( $args = array() ) {

		$defaults = array(
			'carousels' => array(),
		);

		$instance = wp_parse_args( $args, $defaults );
		extract( $instance );	

		ob_start();
		?>
		<div class="wrslb-create-carousel">
			<button class="wrslb-button-success wrslb-new-carousel"><i class="fa fa-plus-circle"></i><?php _e( 'Create New WoorouSell' , WRSL_SLUG ); ?></button>
		</div>
		<table id="wrslb-carousel-list" class="wrslb-table">
			<thead>
				<td class="wrslb-trow-name"><?php _e( 'Name' , WRSL_SLUG ); ?></td>
				<td class="wrslb-trow-shortcode"><?php _e( 'Shortcode' , WRSL_SLUG ); ?></td>
				<td class="wrslb-trow-actions"><?php _e( 'Actions' , WRSL_SLUG ); ?></td>
			</thead>
			<tbody>
			<?php if ( !empty( $carousels ) ) : ?>
				<?php foreach ( $carousels as $carousel ) { 
					$carousel_type = wrslb_get_meta( array( 'id' => $carousel['id'] , 'key' => 'carousel_type' , 'default' => 'post' , 'esc' => 'attr' ) );

					if ( !wrsl_product_exists() && $carousel_type == 'product' )
						continue; // skip product carousel if products didn't exist

					?>
					<tr>
						<td class="wrslb-trow-name"><?php echo get_the_title( $carousel['id'] ); ?></td>
						<td class="wrslb-trow-shortcode">
							<input type="text" value='[woorousell id="<?php echo $carousel['id']; ?>"]' readonly />
						</td>
						<td class="wrslb-trow-actions">
							<a href="<?php echo wrslb_options_page_url( array( 'view' => 'edit' , 'id' => $carousel['id'] ) ); ?>" class="wrslb-button-info"><i class="fa fa-edit"></i><?php _e( 'Edit' , WRSL_SLUG ); ?></a>
							<button class="wrslb-button-danger wrslb-open-selectedmodal" data-modal-id="#wrslb-delete-confirm-<?php echo $carousel['id']; ?>" data-modal-type="small"><i class="fa fa-trash-o"></i><?php _e( 'Delete' , WRSL_SLUG ); ?></button>
							<?php echo $this->delete_confirmation( $carousel['id'] ); ?>
						</td>
					</tr>
				<?php } // end - foreach ?>
			<?php else : ?>
				<tr>
					<td colspan="3"><?php _e( 'No WoorouSell added yet.' , WRSL_SLUG ); ?></td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table><!-- .wrslb-table -->
		<?php
		$html = ob_get_clean();

		return apply_filters( $this->_hook_prefix . 'render_overview' , ( !empty( $html ) ? $html : '' ) , $args , $this );
	}


	/**
	 * Render Edit
	 *
	 * @access public
	 */
	public function render_edit( $args = array() ) {

		$defaults = array(
			'id' => 0,
			'values' => array(),
		);

		$instance = wp_parse_args( $args, $defaults );
		extract( $instance );	

		ob_start();
		?>
		<div class="wrslb-edit-wrapper">
			<div class="wrslb-back-overview">
				<a href="<?php echo wrslb_options_page_url(); ?>" class="wrslb-button-success"><i class="fa fa-angle-double-left"></i><?php _e( 'Go back' , WRSL_SLUG ); ?></a>
			</div>
			<form id="wrslb-edit-form-<?php echo $id; ?>" class="wrslb-edit-form" method="post">

				<?php
					// load form
					try {
						require_once wrsl()->plugin_path( 'builder/settings-page/views/form.php' );
					} catch ( Exception $e ){
						echo '<br><br>View Form Error';			
					}
				?>

				<!-- Hidden field -->
				<input type="hidden" name="wrslb_carousel_id" value="<?php echo $id; ?>" />
				<input type="hidden" name="action" value="wrslb-update-settings" />
				<?php wp_nonce_field( 'wrslb_update_settings' , '_update_settings_nonce' ); ?>

			</form><!-- .wrslb-edit-form -->
		</div><!-- .wrslb-edit-wrapper -->
		<?php
		$html = ob_get_clean();

		return apply_filters( $this->_hook_prefix . 'render_edit' , ( !empty( $html ) ? $html : '' ) , $args , $this );
	}

	/**
	 * render attributes
	 *
	 * @access public
	 */
	public function attributes( $name = '' ) {

		$attributes = '';
		$attributes .= ' id="'.$this->input_id( $name ).'"';
		$attributes .= ' name="'.$this->input_name( $name ).'"';

		return apply_filters( $this->_hook_prefix . 'attributes' , $attributes , $name , $this );
	}

	/**
	 * render input id
	 *
	 * @access public
	 */
	public function input_id( $name = '' ) {
		return apply_filters( $this->_hook_prefix . 'input_id' , ( !empty( $name ) ? 'wrslb-field-' . $name : '' ) , $name , $this );
	}

	/**
	 * render input name
	 *
	 * @access public
	 */
	public function input_name( $name = '' ) {
		return apply_filters( $this->_hook_prefix . 'input_name' , ( !empty( $name ) ? 'wrslb_carousel[' . $name . ']' : '' ) , $name , $this );
	}

	/**
	 * Get value
	 *
	 * @access public
	 */
	public function get_value( $name = '' , $value = array() , $esc = 'attr' ) {

		$return = '';

		if ( !empty( $value[ $name ] ) ) {
			if ( $esc == 'attr' ) {
				$return = esc_attr( $value[ $name ] );
			} elseif ( $esc == 'url' ) {
				$return = esc_url( $value[ $name ] );
			} else {
				$return = $value[ $name ];
			}
		}

		return apply_filters( $this->_hook_prefix . 'get_value' , $return , $name , $value , $esc , $this );
	}

	/**
	 * whether is selected
	 *
	 * @access public
	 */
	public function selected( $value = array() , $name = '' , $compare = '' ) {

		$return = '';

		if ( !empty( $value[ $name ] ) ) {
			$return = selected( $value[ $name ] , $compare , false );
		}

		return apply_filters( $this->_hook_prefix . 'selected' , $return , $name , $value , $compare , $this );
	}

	/**
	 * whether is option selected
	 *
	 * @access public
	 */
	public function option_selected( $value = array() , $name = '' , $compare = '' ) {

		$selected = false;

		if ( isset( $value[ $name ] ) && $value[ $name ] == $compare ) {
			$selected = true;
		}

		return apply_filters( $this->_hook_prefix . 'option_selected' , ( $selected ? ' wrslb-optselector-active' : '' ) , $name , $value , $compare , $this );
	}

	/**
	 * whether is checked
	 *
	 * @access public
	 */
	public function checked( $name = '' , $value = array() ) {

		$return = '';

		if ( !empty( $value[ $name ] ) && $value[ $name ] == 'on' ) {
			$return = 'checked="checked"';
		}

		return apply_filters( $this->_hook_prefix . 'selected' , $return , $name , $value , $this );
	}

	/**
	 * render show if selector
	 *
	 * @access public
	 */
	public function show_if( $name = '', $value = 'true' , $operator = '==' ) {
		return apply_filters( $this->_hook_prefix . 'show_if' , 'data-show-if="'.$this->input_id( $name ).'" data-show-if-value="'.$value.'" data-show-if-operator="'.$operator.'"' , $name , $value , $operator , $this );
	}

	/**
	 * Add Modal Container
	 *
	 * @access public
	 */
	public function modal_container() {
		ob_start();
		?>
		<div id="wrslb-main-modal" class="zoom-anim-dialog mfp-hide"></div>
		<?php
		$html = ob_get_clean();

		return apply_filters( $this->_hook_prefix . 'modal_container' , ( !empty( $html ) ? $html : '' ) , $this );
	}

	/**
	 * Add new wizard
	 *
	 * @access public
	 */
	public function add_new_wizard() {

		ob_start();
		?>
		<div class="wrslb-addnew-container">
			<h2 class="wrslb-modal-headline"><?php _e( 'Add New Carousel' , WRSL_SLUG ); ?></h2>

			<div class="wrslb-addnew-fields">
				<form class="wrslb-addnew-form" method="post">
					<div class="wrslb-form-section">
						<div class="wrslb-form-control">
							<label class="wrslb-input-label" for="wrslb-new-carousel-title"><?php _e( 'Enter a name below' , WRSL_SLUG ); ?></label>
							<input id="wrslb-new-carousel-title" name="wrslb_new_carousel[title]" type="text" class="wrslb-input-text" value="" placeholder="<?php _e( '( e.g. Apple Orange Banana )' , WRSL_SLUG ); ?>">
						</div><!-- .wrslb-form-control -->
					</div><!-- .wrslb-form-section -->
					<input type="hidden" name="action" value="wrslb-create-new" />
					<?php wp_nonce_field( 'wrslb_create_carousel' , '_create_carousel_nonce' ); ?>
				</form><!-- .wrslb-addnew-form -->
			</div><!-- .wrslb-addnew-fields -->

			<div class="wrslb-modal-actions">
				<button class="wrslb-modal-cancel-btn wrslb-close-modal"><?php _e( 'Cancel' , WRSL_SLUG ); ?></button>
				<button class="wrslb-create-carousel wrslb-modal-action-primary"><?php _e( 'Create' , WRSL_SLUG ); ?></button>
			</div>
		</div>
		<?php
		$html = ob_get_clean();

		return apply_filters( $this->_hook_prefix . 'add_new_wizard' , ( !empty( $html ) ? $html : '' ) , $this );
	}

	/**
	 * Render delete confirmation modal
	 *
	 * @access public
	 */
	public function delete_confirmation( $id = '' ) {

		ob_start();
		?>
		<div id="wrslb-delete-confirm-<?php echo $id; ?>" class="wrslb-delete-confirm zoom-anim-dialog mfp-hide">
			<div class="wrslb-modal-container">
				<div class="wrslb-modal-delete">
					<h3 class="wrslb-modal-headline"><?php echo sprintf( __( 'Are you sure you want to delete "%s"?' , WRSL_SLUG ) , get_the_title( $id ) ); ?></h3>
					<h4 class="wrslb-modal-subheadline"><?php _e( 'You will not be able to recover it' , WRSL_SLUG ); ?></h4>
				</div><!-- .wrslb-modal-delete -->
				<div class="wrslb-modal-actions">
					<button class="wrslb-modal-cancel-btn wrslb-close-selectedmodal" data-modal-id="wrslb-delete-confirm-<?php echo $id; ?>"><?php _e( 'Cancel' , WRSL_SLUG ); ?></button>
					<button class="wrslb-delete-carousel wrslb-modal-action-danger" data-carousel-id="<?php echo $id; ?>"><?php _e( 'Delete' , WRSL_SLUG ); ?></button>
				</div>
			</div><!-- .wrslb-modal-container -->
		</div><!-- #wrslb-delete-confirm- -->
		<?php
		$html = ob_get_clean();

		return apply_filters( $this->_hook_prefix . 'delete_confirmation' , ( !empty( $html ) ? $html : '' ) , $id , $this );
	}

	/**
	 * sample render
	 *
	 * @access public
	 */
	public function render_categories_filter( $args = array() ) {

		$defaults = array(
			'sample' => true,
		);

		$instance = wp_parse_args( $args, $defaults );
		extract( $instance );	

		ob_start();
		?>

		<?php
		$html = ob_get_clean();

		return apply_filters( $this->_hook_prefix . 'render_categories_filter' , ( !empty( $html ) ? $html : '' ) , $args , $this );
	}

	/**
	 * sample render
	 *
	 * @access public
	 */
	public function sample_render( $args = array() ) {

		$defaults = array(
			'sample' => true,
		);

		$instance = wp_parse_args( $args, $defaults );
		extract( $instance );	

		ob_start();
		?>

		<?php
		$html = ob_get_clean();

		return apply_filters( $this->_hook_prefix . 'sample_render' , ( !empty( $html ) ? $html : '' ) , $args , $this );
	}

	/* END
	------------------------------------------------------------------- */

} // end - class WRSL_Builder_Settings_View

endif; // end - !class_exists('WRSL_Builder_Settings_View')