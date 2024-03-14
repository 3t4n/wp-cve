<?php
/**
 * Register Fields for Category.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    Sight
 */

/**
 * Class Register Fields
 */
class Sight_Category_Fields {

	/**
	 * Initialize
	 */
	public function __construct() {
		add_action( 'sight-categories_add_form_fields', array( $this, 'category_add_form_fields' ), 10 );
		add_action( 'sight-categories_edit_form_fields', array( $this, 'category_edit_form_fields' ), 10, 2 );
		add_action( 'created_sight-categories', array( $this, 'save_category' ), 10, 2 );
		add_action( 'edited_sight-categories', array( $this, 'save_category' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10 );
	}

	/**
	 * Add fields to Category
	 *
	 * @param string $taxonomy The taxonomy slug.
	 */
	public function category_add_form_fields( $taxonomy ) {
		wp_nonce_field( 'category_options', 'sight_category' );
		?>
			<div class="form-field">
				<label><?php esc_html_e( 'Featured Image', 'sight' ); ?></label>

				<div class="sight-featured-image" data-frame-title="<?php esc_html_e( 'Select or upload image', 'sight' ); ?>" data-frame-btn-text="<?php esc_html_e( 'Set image', 'sight' ); ?>">
					<p class="uploaded-img-box">
						<span class="sight-uploaded-image"></span>
						<input id="sight_featured_image" class="sight-uploaded-img-id" name="sight_featured_image" type="hidden"/>
					</p>
					<p class="hide-if-no-js">
						<a class="sight-upload-img-link button button-primary" href="#"><?php esc_html_e( 'Upload image', 'sight' ); ?></a>
						<a class="sight-delete-img-link button button-secondary hidden" href="#"><?php esc_html_e( 'Remove image', 'sight' ); ?></a>
					</p>
				</div>
			</div>
			<br>
		<?php
	}

	/**
	 * Edit fields from Category
	 *
	 * @param object $tag      Current taxonomy term object.
	 * @param string $taxonomy Current taxonomy slug.
	 */
	public function category_edit_form_fields( $tag, $taxonomy ) {
		wp_nonce_field( 'category_options', 'sight_category' );

		$sight_featured_image = get_term_meta( $tag->term_id, 'sight_featured_image', true );
		?>
		<tr class="form-field">
			<th scope="row" valign="top">
				<label for="sight_featured_image"><?php esc_html_e( 'Featured Image', 'sight' ); ?></label>
			</th>
			<td>
				<div class="sight-featured-image" data-frame-title="<?php esc_html_e( 'Select or upload image', 'sight' ); ?>" data-frame-btn-text="<?php esc_html_e( 'Set image', 'sight' ); ?>">
					<p class="uploaded-img-box">
						<span class="sight-uploaded-image">
							<?php if ( $sight_featured_image ) : ?>
								<?php
									echo wp_get_attachment_image( $sight_featured_image, 'large', false, array(
										'style' => 'max-width:100%; height: auto;',
									) );
								?>
							<?php endif; ?>
						</span>

						<input id="sight_featured_image" class="sight-uploaded-img-id" name="sight_featured_image" type="hidden" value="<?php echo esc_attr( $sight_featured_image ); ?>" />
					</p>
					<p class="hide-if-no-js">
						<a class="sight-upload-img-link button button-primary <?php echo esc_attr( $sight_featured_image ? 'hidden' : '' ); ?>" href="#"><?php esc_html_e( 'Upload image', 'sight' ); ?></a>
						<a class="sight-delete-img-link button button-secondary <?php echo esc_attr( ! $sight_featured_image ? 'hidden' : '' ); ?>" href="#"><?php esc_html_e( 'Remove image', 'sight' ); ?></a>
					</p>
				</div>

				<p class="description"><?php esc_html_e( 'This image is used in the category blocks.', 'sight' ); ?></p>
			</td>
		</tr>
		<?php
	}

	/**
	 * Save category fields
	 *
	 * @param int    $term_id  ID of the term about to be edited.
	 * @param string $taxonomy Taxonomy slug of the related term.
	 */
	public function save_category( $term_id, $taxonomy ) {

		// Bail if we're doing an auto save.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// if our nonce isn't there, or we can't verify it, bail.
		if ( ! isset( $_POST['sight_category'] ) || ! wp_verify_nonce( $_POST['sight_category'], 'category_options' ) ) { // Input var ok; sanitization ok.
			return;
		}

		if ( isset( $_POST['sight_featured_image'] ) ) { // Input var ok; sanitization ok.
			$sight_featured_image = sanitize_text_field( $_POST['sight_featured_image'] ); // Input var ok; sanitization ok.

			update_term_meta( $term_id, 'sight_featured_image', $sight_featured_image );
		}
	}

	/**
	 * Register the stylesheets and JavaScript for the admin area.
	 *
	 * @param string $page Current page.
	 */
	public function admin_enqueue_scripts( $page ) {
		if ( 'edit-tags.php' === $page || 'term.php' === $page ) {
			wp_enqueue_script( 'jquery-core' );

			wp_enqueue_media();

			ob_start();
			?>
			<script>
			( function() {

				var portfolioFeaturedContainer = '.sight-featured-image';

				var portfolioFeaturedFrame;


				jQuery( document ).ready( function( $ ) {

					/* Add Image Link */
					jQuery( portfolioFeaturedContainer ).find( '.sight-upload-img-link' ).on( 'click', function( event ){
						event.preventDefault();

						var parentContainer = $( this ).parents( portfolioFeaturedContainer );

						// Options.
						var options = {
							title: parentContainer.data( 'frame-title' ) ? parentContainer.data( 'frame-title' ) : 'Select or Upload Media',
							button: {
								text: parentContainer.data( 'frame-btn-text' ) ? parentContainer.data( 'frame-btn-text' ) : 'Use this media',
							},
							library : { type : 'image' },
							multiple: false // Set to true to allow multiple files to be selected.
						};

						// Create a new media frame
						portfolioFeaturedFrame = wp.media( options );

						// When an image is selected in the media frame...
						portfolioFeaturedFrame.on( 'select', function() {

							// Get media attachment details from the frame state.
							var attachment = portfolioFeaturedFrame.state().get('selection').first().toJSON();

							// Send the attachment URL to our custom image input field.
							parentContainer.find( '.sight-uploaded-image' ).html( '<img src="' + attachment.url + '" style="max-width:100%;"/>' );
							parentContainer.find( '.sight-uploaded-img-id' ).val( attachment.id ).change();
							parentContainer.find( '.sight-upload-img-link' ).addClass( 'hidden' );
							parentContainer.find( '.sight-delete-img-link' ).removeClass( 'hidden' );

							portfolioFeaturedFrame.close();
						});

						// Finally, open the modal on click.
						portfolioFeaturedFrame.open();
					});


					/* Delete Image Link */
					$( portfolioFeaturedContainer ).find( '.sight-delete-img-link' ).on( 'click', function( event ){
						event.preventDefault();

						$( this ).parents( portfolioFeaturedContainer ).find( '.sight-uploaded-image' ).html( '' );
						$( this ).parents( portfolioFeaturedContainer ).find( '.sight-upload-img-link' ).removeClass( 'hidden' );
						$( this ).parents( portfolioFeaturedContainer ).find( '.sight-delete-img-link' ).addClass( 'hidden' );
						$( this ).parents( portfolioFeaturedContainer ).find( '.sight-uploaded-img-id' ).val( '' ).change();
					});
				});

				jQuery( document ).ajaxSuccess(function(e, request, settings){
					let action   = settings.data.indexOf( 'action=add-tag' );
					let screen   = settings.data.indexOf( 'screen=edit-category' );
					let taxonomy = settings.data.indexOf( 'taxonomy=category' );

					if( action > -1 && screen > -1 && taxonomy > -1 ){
						$( portfolioFeaturedContainer ).find( '.sight-delete-img-link' ).click();
					}
				});

			} )();
			</script>
			<?php
			wp_add_inline_script( 'jquery-core', str_replace( array( '<script>', '</script>' ), '', ob_get_clean() ) );
		}
	}
}

new Sight_Category_Fields();
