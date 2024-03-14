<?php

class CLP_Customizer_Google_Fonts_control extends WP_Customize_Control {
    private $fonts = false;

	/**
	 * Fire the constructor up :)
	 *
	 * @param [type] $manager [description]
	 * @param [type] $id      [description]
	 * @param array  $args    [description]
	 * @param array  $options [description]
	 */
	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		$this->fonts = $this->get_fonts();
        parent::__construct( $manager, $id, $args );
	}

	/**
	 * Render the content of the category dropdown
	 */
	public function render_content() {
		if ( ! empty( $this->fonts ) ) {
            $selected = json_decode( $this->value(), true );
            $selectedFamily = $selected['family'];
            $selectedVariant = $selected['selected']['variant'];
			?>

            <div class="wrapper">
				<label>
					<span class="customize-control-title"><?php _e('Font Family', 'clp-custom-login-page'); ?></span>
					<select class="clp-font-family-select">
						<?php
                        foreach ( $this->fonts as $key => $font ) {
                            $value = array(
                                'family'   => $font->family,
                                'variants' => $font->variants
                            );
                            printf( "<option value='%s' %s data-family='%s'>%s</option>", esc_attr( $font->family ), selected( $selectedFamily, $font->family, false ), json_encode($value), ucfirst( $font->family ) );
                        }
						?>
					</select>
                </label>
                <br>
                <br>

				<label>
					<span class="customize-control-title"><?php _e('Font Variant', 'clp-custom-login-page'); ?></span>
					<select class="clp-font-variant-select" style="text-transform:capitalize">
						<?php                            
                        foreach ( $selected['variants'] as $variant ) {
                            printf( '<option value="%s" %s>%s</option>', esc_attr( $variant ), selected( $selectedVariant, $variant, false ), $variant );
                        }
						?>
					</select>
				</label>

                <input class="clp-font" type="hidden" <?php $this->link(); ?>>
                <script>
                    (function() {
                        
                        var $fontFamilySelect = jQuery('#customize-control-<?php echo esc_attr($this->id);?> .clp-font-family-select');
                        var $fontVariantSelect = jQuery('#customize-control-<?php echo esc_attr($this->id);?> .clp-font-variant-select');
                        var $inputValue = jQuery('#customize-control-<?php echo esc_attr($this->id);?> .clp-font');
                        
                        // update Font Family Value
                        $fontFamilySelect.on('change', function() {
                            var value = jQuery(this).find(':selected').data('family');
                            var selectedVariant = $fontVariantSelect.val();
                            value.selected = {
                                variant: $fontVariantSelect.val(),
                            }


                            // refresh variant select
                            $fontVariantSelect.empty();

                            value.variants.forEach(function(variant) {
                                let option  = document.createElement('option');
                                option.value = variant;
                                option.text = variant;
                                // select same variant if new font has it
                                if ( selectedVariant === variant ) {
                                    option.selected = true;
                                // else default to regular, and update variant value
                                } else if ( variant === 'regular' ){
                                    option.selected = true;
                                    value.selected = {
                                        variant: 'regular',
                                    }
                                }

                                $fontVariantSelect.append(option);
                            });

                            // update final value
                            $inputValue.val( JSON.stringify(value) ).trigger('change');

                        });

                        // Update Font Variant Value
                        $fontVariantSelect.on('change', function() {
                            var value = JSON.parse($inputValue.val());
                            value.selected = {
                                variant: jQuery(this).val(),
                            }
                            // update final value
                            $inputValue.val( JSON.stringify(value) ).trigger('change');
                        });
                    })();
                </script>
            </div>

			<?php
		}
	}

	/**
	 * Get the google fonts from the API or in the cache.
	 *
	 * @return string
	 */
	public function get_fonts() {
        include( CLP_PLUGIN_DIR . 'includes/google-fonts-list.php' );
        return json_decode( $fonts );
	}

}