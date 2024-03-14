<?php

/**
 * Shortcode Builder.
 *
 * @link    http://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */

$fields = acadp_get_shortcode_fields();
?>

<div id="acadp-shortcode-builder" class="acadp-flex acadp-flex-col acadp-gap-6">
    <!-- Shortcode Selector -->
    <div id="acadp-shortcode-selector" class="acadp-flex acadp-flex-col acadp-gap-2 acadp-shadow acadp-p-6">
        <p class="about-text acadp-m-0">
            <?php esc_html_e( 'Select a shortcode type', 'advanced-classifieds-and-directory-pro' ); ?>
        </p>

        <div class="acadp-flex acadp-flex-col acadp-gap-3 md:acadp-flex-row">
            <?php
            foreach ( $fields as $shortcode => $params ) {
                echo '<label class="acadp-flex acadp-gap-1.5 acadp-items-center">';

                echo sprintf( 
                    '<input type="radio" name="shortcode" class="acadp-form-control acadp-form-radio" value="%s"%s/>%s', 
                    esc_attr( $shortcode ), 
                    checked( $shortcode, 'listings', false ), 
                    esc_html( $params['title'] ) 
                );

                echo '</label>';
            }
            ?>
        </div>         
    </div>

    <!-- Shortcode Builder -->
    <div class="acadp-grid acadp-grid-cols-1 acadp-gap-6 md:acadp-grid-cols-3"> 
        <!-- Left Column -->  
        <div class="md:acadp-col-span-2">
            <?php foreach ( $fields as $shortcode => $params ) : ?>
                <div id="acadp-shortcode-form-<?php echo esc_attr( $shortcode ); ?>" class="acadp-shortcode-form" data-shortcode="<?php echo esc_attr( $shortcode ); ?>" hidden>
                    <div class="acadp-accordion">
                        <?php foreach ( $params['sections'] as $name => $section ) : ?>                         
                            <div class="acadp-accordion-panel acadp-accordion-panel-<?php echo esc_attr( $name ); ?><?php if ( 'general' === $name ) echo ' open'; ?>"> 
                                <div class="acadp-accordion-header"> 
                                    <span class="dashicons-before dashicons-plus"></span>
                                    <span class="dashicons-before dashicons-minus"></span>
                                    <?php echo esc_html( $section['title'] ); ?> 
                                </div>  
                                                        
                                <div class="acadp-accordion-body">
                                    <div class="acadp-overflow-hidden">
                                        <div class="acadp-flex acadp-flex-col acadp-gap-3 acadp-p-3">
                                            <?php foreach ( $section['fields'] as $field ) : ?>
                                                <div class="acadp-form-group acadp-form-group-<?php echo esc_attr( $field['name'] ); ?> acadp-flex acadp-flex-col acadp-gap-1">                                                
                                                    <?php if ( 'text' == $field['type'] || 'url' == $field['type'] || 'number' == $field['type'] ) : ?>                                        
                                                        <label class="acadp-block">
                                                            <?php echo esc_html( $field['label'] ); ?>
                                                        </label>
                                                        <input type="text" name="<?php echo esc_attr( $field['name'] ); ?>" class="acadp-form-control acadp-form-input acadp-shortcode-field widefat" value="<?php echo esc_attr( $field['value'] ); ?>" data-default="<?php echo esc_attr( $field['value'] ); ?>" />
                                                    <?php elseif ( 'textarea' == $field['type'] ) : ?>
                                                        <label class="acadp-block">
                                                            <?php echo esc_html( $field['label'] ); ?>
                                                        </label>
                                                        <textarea name="<?php echo esc_attr( $field['name'] ); ?>" class="acadp-form-control acadp-form-textarea acadp-shortcode-field widefat" rows="8" data-default="<?php echo esc_attr( $field['value'] ); ?>"><?php echo esc_textarea( $field['value'] ); ?></textarea>
                                                    <?php elseif ( 'select' == $field['type'] || 'radio' == $field['type'] ) : ?>
                                                        <label class="acadp-block">
                                                            <?php echo esc_html( $field['label'] ); ?>
                                                        </label> 
                                                        <select name="<?php echo esc_attr( $field['name'] ); ?>" class="acadp-form-control acadp-form-select acadp-shortcode-field widefat" data-default="<?php echo esc_attr( $field['value'] ); ?>">
                                                            <?php
                                                            foreach ( $field['options'] as $value => $label ) {
                                                                printf( 
                                                                    '<option value="%s"%s>%s</option>', 
                                                                    esc_attr( $value ), 
                                                                    selected( $value, $field['value'], false ), 
                                                                    esc_html( $label ) 
                                                                );
                                                            }
                                                            ?>
                                                        </select>                                                                               
                                                    <?php elseif ( 'checkbox' == $field['type'] ) : ?>                                        
                                                        <label class="acadp-flex acadp-gap-1.5 acadp-items-center">				
                                                            <input type="checkbox" name="<?php echo esc_attr( $field['name'] ); ?>" class="acadp-form-control acadp-form-checkbox acadp-shortcode-field" value="1" data-default="<?php echo esc_attr( $field['value'] ); ?>" <?php checked( $field['value'] ); ?> />
                                                            <?php echo esc_html( $field['label'] ); ?>
                                                        </label>                                            
                                                    <?php elseif ( 'color' == $field['type'] ) : ?>                                        
                                                        <label class="acadp-block">
                                                            <?php echo esc_html( $field['label'] ); ?>
                                                        </label>
                                                        <input type="text" name="<?php echo esc_attr( $field['name'] ); ?>" class="acadp-form-control acadp-form-control-color-picker acadp-form-input acadp-shortcode-field widefat" value="<?php echo esc_attr( $field['value'] ); ?>" data-default="<?php echo esc_attr( $field['value'] ); ?>" />
                                                    <?php elseif ( 'locations' == $field['type'] ) : ?>
                                                        <label class="acadp-block">
                                                            <?php echo esc_html( $field['label'] ); ?>
                                                        </label> 
                                                        <?php
                                                        $locations_args = array(
                                                           'placeholder' => '— ' . esc_html( $field['label'] ) . ' —',
                                                           'taxonomy'    => 'acadp_locations',
                                                           'parent'      => max( 0, (int) $general_settings['base_location'] ),
                                                           'name' 	     => esc_attr( $field['name'] ),   
                                                           'class'       => 'acadp-form-control acadp-shortcode-field widefat',
                                                        );

                                                        echo acadp_get_terms_dropdown_html( $locations_args );
                                                        ?>
                                                    <?php elseif ( 'categories' == $field['type'] ) : ?>
                                                        <label class="acadp-block">
                                                            <?php echo esc_html( $field['label'] ); ?>
                                                        </label> 
                                                        <?php
                                                        $categories_args = array(
                                                            'placeholder' => '— ' . esc_html( $field['label'] ) . ' —',
                                                            'taxonomy'    => 'acadp_categories',
                                                            'parent'      => 0,
                                                            'name' 	     => esc_attr( $field['name'] ),   
                                                            'class'       => 'acadp-form-control acadp-shortcode-field widefat',
                                                         );
 
                                                        echo acadp_get_terms_dropdown_html( $categories_args );
                                                        ?>
                                                    <?php endif; ?>

                                                    <!-- Hint -->
                                                    <?php if ( isset( $field['description'] ) ) : ?>                            
                                                        <span class="description acadp-block acadp-text-muted">
                                                            <?php echo wp_kses_post( $field['description'] ); ?>
                                                        </span>                        
                                                    <?php endif; ?>                                                            
                                                </div>    
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <br />

            <button type="button" id="acadp-button-shortcode-generate" class="acadp-button-modal button button-primary button-hero" data-target="#acadp-modal-shortcode">
                <?php esc_attr_e( 'Generate Shortcode', 'advanced-classifieds-and-directory-pro' ); ?>
            </button>
        </div>

        <!-- Right Column -->
        <div class="md:acadp-col-span-1">
            <p class="about-description acadp-m-0">
                <?php esc_html_e( '"Advanced Classifieds and Directory Pro" provides several methods to add the plugin content (listings, categories, locations, search form, etc.) in your site front-end. Choose one of the following methods best suited for you,', 'advanced-classifieds-and-directory-pro' ); ?>
            </p>

            <p>
                <span class="dashicons dashicons-arrow-left-alt"></span> 
                <?php esc_html_e( 'Use the shortcode builder in this page to build your shortcode, then add it in your POST/PAGE.', 'advanced-classifieds-and-directory-pro' ); ?>
            </p>

            <p>
                2. <?php printf( __( 'Use our "Advanced Classifieds and Directory Pro" <a href="%s" target="_blank">Gutenberg blocks</a>.', 'advanced-classifieds-and-directory-pro' ), esc_url( admin_url( 'post-new.php?post_type=page' ) ) ); ?>
            </p>

            <p>
                3. <?php printf( __( 'Use our <a href="%s" target="_blank">widgets</a> in your website sidebars.', 'advanced-classifieds-and-directory-pro' ), esc_url( admin_url( 'widgets.php' ) ) ); ?>
            </p>
        </div>
    </div>

    <!-- Shortcode Modal -->
    <div id="acadp-modal-shortcode" class="acadp-modal">
		<!-- Dialog -->
		<div class="acadp-modal-dialog">
			<div class="acadp-modal-content">
				<!-- Header -->
				<div class="acadp-modal-header">
					<p class="acadp-m-0 md:acadp-text-lg">
                        <?php esc_html_e( 'Copy the shortcode below and paste it in your POST/PAGE where you need the gallery.', 'advanced-classifieds-and-directory-pro' ); ?>
                    </p>

					<button type="button" class="acadp-button acadp-button-close button acadp-p-2">
						<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" width="20px" height="20px" stroke-width="1.5" stroke="currentColor" class="acadp-flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
					</button>
				</div>
				<!-- Body -->
				<div class="acadp-modal-body">
                    <textarea id="acadp-shortcode" class="acadp-form-textarea acadp-font-mono widefat" autofocus="autofocus" onfocus="this.select()"></textarea>
				</div>
				<!-- Footer -->
				<div class="acadp-modal-footer">
                    <div class="acadp-status-text" hidden>
                        <div class="acadp-text-success">
                            <?php esc_html_e( 'Copied!', 'advanced-classifieds-and-directory-pro' ); ?>
                        </div>
                    </div>

					<button type="button" id="acadp-button-shortcode-copy" class="acadp-button button button-primary">
						<?php esc_html_e( 'Copy Shortcode', 'advanced-classifieds-and-directory-pro' ); ?>
					</button>
				</div>
			</div>
		</div>
	</div>
</div>
