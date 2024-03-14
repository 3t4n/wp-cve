<?php

/**
 * Image Field.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<?php if ( 'add' == $page ) : ?>
    <div class="acadp acadp-require-js form-field term-group" data-script="categories">
        <label for="acadp-categories-image-id">
            <?php esc_html_e( 'Image', 'advanced-classifieds-and-directory-pro' ); ?>
        </label>
        
        <div id="acadp-categories-image-wrapper"></div>

        <p>
            <input type="hidden" name="image" id="acadp-categories-image-id" />

            <button type="button" id="acadp-button-categories-upload-image" class="button button-secondary">
                <?php esc_attr_e( 'Add Image', 'advanced-classifieds-and-directory-pro' ); ?>
            </button>

            <button type="button" id="acadp-button-categories-remove-image" class="button button-secondary" hidden>
                <?php esc_attr_e( 'Remove Image', 'advanced-classifieds-and-directory-pro' ); ?>
            </button>
        </p>
    </div>
<?php elseif ( 'edit' == $page ) : ?>
	<tr class="acadp acadp-require-js form-field term-group-wrap" data-script="categories">
    	<th scope="row">
        	<label for="acadp-categories-image-id">
                <?php esc_html_e( 'Image', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
        </th>
        <td>
            <div id="acadp-categories-image-wrapper">
            	<?php if ( $image_src ) : ?>
            		<img src="<?php echo esc_url( $image_src ); ?>" alt="" />
                <?php endif; ?>
            </div>

            <p>
                <input type="hidden" name="image" id="acadp-categories-image-id" value="<?php echo esc_attr( $image_id ); ?>" />
            	
                <button type="button" id="acadp-button-categories-upload-image" class="button button-secondary"<?php if ( $image_src ) echo ' hidden'; ?>>
                    <?php esc_attr_e( 'Add Image', 'advanced-classifieds-and-directory-pro' ); ?>
                </button>

            	<button type="button" id="acadp-button-categories-remove-image" class="button button-secondary"<?php if ( ! $image_src ) echo ' hidden'; ?>>
                    <?php esc_attr_e( 'Remove Image', 'advanced-classifieds-and-directory-pro' ); ?>
                </button>
        	</p>
        </td>
    </tr>
<?php endif;