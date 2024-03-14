<?php

if (!defined('ABSPATH')){
  exit;
}


function wpacptdcf7_add_products_tag_generator_menu() {
    if (class_exists('WPCF7_TagGenerator')){
        $tag_generator = WPCF7_TagGenerator::get_instance();
        $tag_generator->add( 'products', __( 'WooCommerce Products drop-down menu', 'woocommerce-product-list-field-for-contact-form-7' ),'wpacptdcf7_tag_products_generator_menu');
    }
}

function wpacptdcf7_tag_products_generator_menu( $contact_form, $args = '' ) {
    $args = wp_parse_args( $args, array() );
    $type = 'products';
    $description = __( "Generate a form-tag for a WooCommerce Products drop-down menu. For more details, see %s.", 'woocommerce-product-list-field-for-contact-form-7' ); ?>
    <div class="control-box">
        <fieldset>
            <legend><?php echo esc_html( $description ) ; ?></legend>

            <table class="form-table">
            <tbody>
                <tr>
                <th scope="row"><?php echo esc_html( __( 'Field type', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></th>
                <td>
                    <fieldset>
                    <legend class="screen-reader-text"><?php echo esc_html( __( 'Field type', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></legend>
                    <label><input type="checkbox" name="required" /> <?php echo esc_html( __( 'Required field', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></label>
                    </fieldset>
                </td>
                </tr>
                <tr>
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Name', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></label></th>
                <td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
                </tr>
                <tr>
                <th scope="row"><label><?php echo esc_html( __( 'Select Filter Option', 'woocommerce-product-list-field-for-contact-form-7' )); ?></label></th>
                    <td>
                        <select name="filter_options" id="filter_options">
                            <option value=""><?php echo esc_html( __( '--- Select Option ---', 'woocommerce-product-list-field-for-contact-form-7' )); ?></option>
                            <option value="category"><?php echo esc_html( __( 'Category', 'woocommerce-product-list-field-for-contact-form-7' )); ?></option>
                            <option value="tagsss" disabled><?php echo esc_html( __( 'Tags', 'woocommerce-product-list-field-for-contact-form-7' )); ?></option>
                            <option value="featured" disabled><?php echo esc_html( __( 'Featured Product', 'woocommerce-product-list-field-for-contact-form-7' )); ?></option>
                            <option value="bestselling" disabled><?php echo esc_html( __( 'Best Selling Product', 'woocommerce-product-list-field-for-contact-form-7' )); ?></option>
                        </select>
                    </td>
                </tr>
                <tr id="hide_cat_box">
                <th scope="row"><label><?php echo esc_html( __( 'Category', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></label></th>
                <td>
                        <?php
                            $orderby = 'name';
                            $order = 'asc';
                            $hide_empty = true;
                            $cat_args = array(
                                'orderby'    => $orderby,
                                'order'      => $order,
                                'hide_empty' => $hide_empty,
                            );
                             
                            $product_categories = get_terms( 'product_cat', $cat_args );
                             
                            if( !empty($product_categories) ){
                            foreach ($product_categories as $key => $category) {
                        ?>
                        <input type="radio" name="category" value="<?php echo esc_attr($category->name);?>" class="option"> <?php echo esc_attr($category->name);?>
                        <?php } } ?>
                </td>
                </tr>
               
                <tr><th><a href="https://www.plugin999.com/plugin/product-dropdown-field-for-contact-form-7/" target="_blank" class="wplfcf7_pro_link">Go Pro</a></th></tr>
                <tr class="WPLFCF7_fetures">
                    <th scope="row"><?php echo esc_html( __( 'Options', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></th>
                    <td>
                        <fieldset>
                        <label><input type="checkbox" name="multiple" class="option" /> <?php echo esc_html( __( 'Allow multiple selections', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></label><br />
                        <label><input type="checkbox" name="include_blank" class="option" /> <?php echo esc_html( __( 'Insert a blank item as the first option', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></label>
                        <label><input type="checkbox" name="enable_search_box" class="option" /> <?php echo esc_html( __( 'Enable Search box on List Dropdown.', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></label>
                        </fieldset>
                    </td>
                </tr>
                <tr class="WPLFCF7_fetures">
                    <th scope="row"><?php echo esc_html( __( 'Metadata', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></th>
                    <td>
                        <fieldset>
                        <input type="text" name="meta_data" class="meta_data oneline option" id="<?php echo esc_attr( $args['content'] . '-meta_data' ); ?>" />
                        <br>
                        <span class="description">
                            <?php echo esc_html( __( 'Use pipe-separated post attributes (e.g.sku|review|date|time|slug|author|category|tags|meta_key) per field.', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?>
                        </span>
                        </fieldset>
                    </td>
                </tr>
                <tr class="WPLFCF7_fetures">
                    <th scope="row"><?php echo esc_html( __( 'Image Options', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></th>
                    <td>
                        <fieldset>
                        <label><input type="checkbox" name="show_image" class="option" checked/> <?php echo esc_html( __( 'Show Or Hide Image', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></label><br />
                        <label><input type="number" name="image_size" class="image_size oneline option" id="<?php echo esc_attr( $args['content'] . '-image_size' ); ?>"  min="0" placeholder="80"/> <?php echo esc_html( __( 'Custom Image Size (Width)', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></label>
                        </fieldset>
                    </td>
                </tr>
                <tr class="WPLFCF7_fetures">
                    <th scope="row"><?php echo esc_html( __( 'Content Options', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></th>
                    <td>
                        <fieldset>
                        <label><input type="checkbox" name="show_p_price" class="option" checked/> <?php echo esc_html( __( 'Show Or Hide Product Price', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></label>
                        </fieldset>
                    </td>
                </tr>
                
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-id' ); ?>"><?php echo esc_html( __( 'Id attribute', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></label></th>
                <td><input type="text" name="id" class="idvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-id' ); ?>" /></td>
                </tr>
                <tr>
                <th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?></label></th>
                <td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
                </tr>
            </tbody>
            </table>
        </fieldset>
    </div>
    <div class="insert-box">
        <input type="text" name="<?php echo $type; ?>" class="tag code" readonly="readonly" onfocus="this.select()" />

        <div class="submitbox">
        <input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'woocommerce-product-list-field-for-contact-form-7' ) ); ?>" />
        </div>

        <br class="clear" />

        <p class="description mail-tag"><label for="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>"><?php echo sprintf( esc_html( __( "To use the value input through this field in a mail field, you need to insert the corresponding mail-tag (%s) into the field on the Mail tab.", 'woocommerce-product-list-field-for-contact-form-7' ) ), '<strong><span class="mail-tag"></span></strong>' ); ?><input type="text" class="mail-tag code hidden" readonly="readonly" id="<?php echo esc_attr( $args['content'] . '-mailtag' ); ?>" /></label></p>
    </div>
    <?php
}

if ( is_admin() ) {
    add_action( 'admin_init', 'wpacptdcf7_add_products_tag_generator_menu', 25 );
}