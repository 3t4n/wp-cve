<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// provided when rendering this partial
if ( empty( $type ) ) {
    return;
}

?>
<div class="form__row">
	<label class="text-select" for="<?php echo $type ?>[text][1]">
		<span><?php _e( 'Attribute builder', 'woo-image-seo' ) ?></span>

		<a
			href="#attribute-builder-help"
			class="dashicons dashicons-editor-help"
			title="<?php _e( 'Click to learn about the Attribute Builder', 'woo-image-seo' ) ?>"
		></a>
	</label>


	<div class="form__inner">
		<?php for ( $i = 1; $i < 4; $i++ ) : ?>
			<select
				name="<?php echo $type ?>[text][<?php echo $i ?>]"
				id="<?php echo $type ?>[text][<?php echo $i ?>]"
			>
                <optgroup label="<?php _e( 'Product Data', 'woo-image-seo' ) ?>">
                    <option
                        value="[name]"
                        <?php selected( $settings[ $type ]['text'][ $i ], '[name]' ) ?>
                    ><?php _e( 'Product Name', 'woo-image-seo' ) ?></option>

                    <option
                        value="[category]"
                        <?php selected( $settings[ $type ]['text'][ $i ], '[category]' ) ?>
                    ><?php _e( 'First Category', 'woo-image-seo' ) ?></option>

                    <option
                        value="[tag]"
                        <?php selected( $settings[ $type ]['text'][ $i ], '[tag]' ) ?>
                    ><?php _e( 'First Tag', 'woo-image-seo' ) ?></option>
                </optgroup>

                <optgroup label="<?php _e( 'Site Data', 'woo-image-seo' ) ?>">
                    <option
                        value="[site-name]"
                        <?php selected( $settings[ $type ]['text'][ $i ], '[site-name]' ) ?>
                    ><?php _e( 'Site Name', 'woo-image-seo' ) ?></option>

                    <option
                        value="[site-description]"
                        <?php selected( $settings[ $type ]['text'][ $i ], '[site-description]' ) ?>
                    ><?php _e( 'Site Description', 'woo-image-seo' ) ?></option>

                    <option
                        value="[site-domain]"
                        <?php selected( $settings[ $type ]['text'][ $i ], '[site-domain]' ) ?>
                    ><?php _e( 'Domain Name', 'woo-image-seo' ) ?></option>
                </optgroup>

                <optgroup label="<?php _e( 'Special', 'woo-image-seo' ) ?>">
                    <option
                        value="[current-date]"
                        <?php selected( $settings[ $type ]['text'][ $i ], '[current-date]' ) ?>
                    ><?php _e( 'Current Date', 'woo-image-seo' ) ?></option>

                    <option
                        value="[custom]"
                        <?php selected( $settings[ $type ]['text'][ $i ], '[custom]' ) ?>
                    ><?php _e( 'Custom Text', 'woo-image-seo' ) ?> <?php echo $i ?></option>

                    <option
                        value="[none]"
                        class="wis-c-999"
                        <?php selected( $settings[ $type ]['text'][ $i ], '[none]' ) ?>
                    >&#x3C;<?php _e( 'Empty', 'woo-image-seo' ) ?>&#x3E;</option>
                </optgroup>
			</select>
		<?php endfor; ?>
	</div>
</div><!-- /.form__row -->