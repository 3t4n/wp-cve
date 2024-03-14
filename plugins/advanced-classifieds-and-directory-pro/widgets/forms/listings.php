<?php

/**
 * Listings.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp">
    <div class="acadp-widget-form acadp-flex acadp-flex-col acadp-gap-3">
        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="acadp-form-label">
                <?php esc_html_e( 'Title', 'advanced-classifieds-and-directory-pro' ); ?>
            </label> 
            <input type="text" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" class="acadp-form-control acadp-form-input widefat" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </div>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'view' ) ); ?>" class="acadp-form-label">
                <?php esc_html_e( 'Select template', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'view' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'view' ) ); ?>" class="acadp-form-control acadp-form-select widefat"> 
                <?php
                $options = array(
                    'standard' => __( 'Default', 'advanced-classifieds-and-directory-pro' ),
                    'map'      => __( 'Map', 'advanced-classifieds-and-directory-pro' )
                );
            
                foreach ( $options as $key => $value ) {
                    printf( 
                        '<option value="%s"%s>%s</option>', 
                        $key, 
                        selected( $key, $instance['view'] ), 
                        esc_html( $value )
                    );
                }
                ?>
            </select>
        </div>

        <?php if ( $instance['has_location'] ) : ?>
            <div class="acadp-form-group">
                <label for="<?php echo esc_attr( $this->get_field_id( 'location' ) ); ?>" class="acadp-form-label">
                    <?php esc_html_e( 'Select location', 'advanced-classifieds-and-directory-pro' ); ?>
                </label> 
                <?php
                $locations_args = array(
                    'placeholder' => '— ' . esc_html__( 'Select location', 'advanced-classifieds-and-directory-pro' ) . ' —',
                    'taxonomy'    => 'acadp_locations',
                    'parent'      => (int) $instance['base_location'],
                    'name' 	      => esc_attr( $this->get_field_name( 'location' ) ),              
                    'class'       => 'acadp-form-control widefat',
                    'selected'    => ! empty( $instance['location'] ) ? (int) $instance['location'] : (int) $instance['base_location']
                );

                echo acadp_get_terms_dropdown_html( $locations_args );
                ?>
            </div>
        <?php endif; ?>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" class="acadp-form-label">
                <?php esc_html_e( 'Select category', 'advanced-classifieds-and-directory-pro' ); ?>
            </label> 
            <?php
            $categories_args = array(
                'placeholder' => '— ' . esc_html__( 'Select category', 'advanced-classifieds-and-directory-pro' ) . ' —',
                'taxonomy'    => 'acadp_categories',
                'parent'      => 0,
                'name' 	      => esc_attr( $this->get_field_name( 'category' ) ),
                'class'       => 'acadp-form-control widefat postform',
                'selected'    => (int) $instance['category']
            );

            echo acadp_get_terms_dropdown_html( $categories_args );
            ?>
        </div>  
        
        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" class="acadp-form-label">
                <?php esc_html_e( 'Number of columns', 'advanced-classifieds-and-directory-pro' ); ?>
            </label> 
            <input type="number" name="<?php echo esc_attr( $this->get_field_name( 'columns' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'columns' ) ); ?>" class="acadp-form-control acadp-form-input widefat" value="<?php echo esc_attr( $instance['columns'] ); ?>" />
        </div>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" class="acadp-form-label">
                <?php esc_html_e( 'Number of listings', 'advanced-classifieds-and-directory-pro' ); ?>
            </label> 
            <input type="number" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" class="acadp-form-control acadp-form-input widefat" value="<?php echo esc_attr( $instance['limit'] ); ?>" />
        </div>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="acadp-form-label">
                <?php esc_html_e( 'Order by', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ); ?>" class="acadp-form-control acadp-form-select widefat"> 
                <?php
                $options = array(
                    'title' => __( 'Title', 'advanced-classifieds-and-directory-pro' ),
                    'date'  => __( 'Date posted', 'advanced-classifieds-and-directory-pro' ),
                    'price' => __( 'Price', 'advanced-classifieds-and-directory-pro' ),
                    'views' => __( 'Views count', 'advanced-classifieds-and-directory-pro' ),
                    'rand'  => __( 'Random', 'advanced-classifieds-and-directory-pro' )
                );
            
                foreach ( $options as $key => $value ) {
                    printf( 
                        '<option value="%s"%s>%s</option>', 
                        $key, 
                        selected( $key, $instance['orderby'] ), 
                        esc_html( $value ) 
                    );
                }
                ?>
            </select>
        </div>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="acadp-form-label">
                <?php esc_html_e( 'Order', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
            <select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" class="acadp-form-control acadp-form-select widefat"> 
                <?php
                $options = array(
                    'asc'  => __( 'Ascending', 'advanced-classifieds-and-directory-pro' ),
                    'desc' => __( 'Descending', 'advanced-classifieds-and-directory-pro' )
                );
            
                foreach ( $options as $key => $value ) {
                    printf( 
                        '<option value="%s"%s>%s</option>', 
                        $key, 
                        selected( $key, $instance['order'] ), 
                        esc_html( $value )
                    );
                }
                ?>
            </select>
        </div>       

        <?php if ( $instance['has_featured'] ) : ?>
            <div class="acadp-form-group">
                <label for="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
                    <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'featured' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'featured' ) ); ?>" class="acadp-form-control acadp-form-checkbox" alue="1" <?php checked( $instance['featured'] ); ?> />
                    <?php esc_html_e( 'Featured only', 'advanced-classifieds-and-directory-pro' ); ?>
                </label>
            </div>
        <?php endif; ?>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'related_listings' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'related_listings' ) ); ?>"  id="<?php echo esc_attr( $this->get_field_id( 'related_listings' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['related_listings'] ); ?> />
                <?php esc_html_e( 'Related listings', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
        </div>         

        <?php if ( $instance['has_images'] ) : ?>
            <div class="acadp-form-group">
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
                    <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['show_image'] ); ?> />
                    <?php esc_html_e( 'Show image', 'advanced-classifieds-and-directory-pro' ); ?>
                </label>
            </div>
            
            <div class="acadp-form-group">
                <label for="<?php echo esc_attr( $this->get_field_id( 'image_position' ) ); ?>" class="acadp-form-label">
                    <?php esc_html_e( 'Select image position', 'advanced-classifieds-and-directory-pro' ); ?>
                </label>
                <select name="<?php echo esc_attr( $this->get_field_name( 'image_position' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'image_position' ) ); ?>" class="acadp-form-control acadp-form-select widefat"> 
                    <?php
                    $options = array(
                        'top'  => __( 'Top', 'advanced-classifieds-and-directory-pro' ),
                        'left' => __( 'Left', 'advanced-classifieds-and-directory-pro' )
                    );
                
                    foreach ( $options as $key => $value ) {
                        printf( 
                            '<option value="%s"%s>%s</option>', 
                            $key, 
                            selected( $key, $instance['image_position'] ), 
                            esc_html( $value )
                        );
                    }
                    ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['show_excerpt'] ); ?> />
                <?php esc_html_e( 'Show excerpt (short description)', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
        </div>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_category' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_category' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['show_category'] ); ?> />
                <?php esc_html_e( 'Show category name', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
        </div>

        <?php if ( $instance['has_location'] ) : ?>
            <div class="acadp-form-group">
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_location' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
                    <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_location' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_location' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['show_location'] ); ?> />
                    <?php esc_html_e( 'Show location name', 'advanced-classifieds-and-directory-pro' ); ?>
                </label>
            </div>
        <?php endif; ?>

        <?php if ( $instance['has_price'] ) : ?>
            <div class="acadp-form-group">
                <label for="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
                    <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_price' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['show_price'] ); ?> />
                    <?php esc_html_e( 'Show price', 'advanced-classifieds-and-directory-pro' ); ?>
                </label>
            </div>
        <?php endif; ?>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['show_date'] ); ?> />
                <?php esc_html_e( 'Show date', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
        </div>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_user' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_user' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_user' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['show_user'] ); ?> />
                <?php esc_html_e( 'Show user', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
        </div>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_views' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_views' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_views' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['show_views'] ); ?> />
                <?php esc_html_e( 'Show views count', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
        </div>

        <div class="acadp-form-group">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_custom_fields' ) ); ?>" class="acadp-flex acadp-gap-1.5 acadp-items-center">
                <input type="checkbox" name="<?php echo esc_attr( $this->get_field_name( 'show_custom_fields' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'show_custom_fields' ) ); ?>" class="acadp-form-control acadp-form-checkbox" value="1" <?php checked( $instance['show_custom_fields'] ); ?> />
                <?php esc_html_e( 'Show custom fields', 'advanced-classifieds-and-directory-pro' ); ?>
            </label>
        </div>
    </div>
</div>