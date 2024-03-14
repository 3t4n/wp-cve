<?php
/* Get Whole Site Options */
function ppws_get_whole_site_password(){
    return get_option( 'ppws_general_settings' );
}
/* Get child pages */
function ppws_get_child_pages($per_id, $marg, $value = array()){
    if(empty($value))   $value = array();
    $arg = array(
        'child_of' => $per_id,
    );
    $pages = get_pages( $arg );
    if(isset($pages) && !empty($pages)){ ?>
        <div class="ppws_all_user_list ppws_sub_category_list" >
            <?php
            foreach( $pages as $mpage ) { ?>
                <div class="ppws_all_user_list_input">        
                    <input type="checkbox" class="ppws-checkbox" name="ppws_page_settings[<?php esc_attr_e( $marg ) ?>][]" id="<?php esc_attr_e( $marg ) ?>" value="<?php esc_attr_e($mpage->post_title); ?>" <?php if(in_array($mpage->post_title,$value)){ esc_attr_e('checked'); } ?>>
                    <label for="<?php esc_attr_e( $marg ) ?>"><?php esc_attr_e($mpage->post_title); ?></label>
                    <?php ppws_get_child_pages($mpage->ID, $marg, $value); ?>
                </div>
                <?php
            } ?>
        </div>
        <?php
    }
}

/* Woocommerce product categories */
function ppws_get_woocommerce_product_categories( $category_id, $args, $value ){
    $args2 = array(
        'taxonomy' => 'product_cat',
        'child_of' => 0,
        'parent' => $category_id,
        'orderby' => 'name',
        'show_count' => 0,
        'pad_counts' => 0,
        'hierarchical' => 1,
        'hide_empty' => 0
    );
    $sub_cats = get_categories($args2);
    if ($sub_cats) { ?>
        <div class="ppws_all_user_list ppws_sub_category_list"> <?php
        foreach ($sub_cats as $sub_category) {
            $sub_category_id = $sub_category->term_id;
            ?>
            <div class="ppws_all_user_list_input">
            
                <label><input type="checkbox" class="ppws-checkbox" name="ppws_product_categories_settings[<?php esc_attr_e( $args ) ?>][]" value="<?php esc_attr_e($sub_category_id) ?>"  <?php if(in_array($sub_category_id,$value)){ esc_attr_e('checked'); } ?> ><?php esc_attr_e($sub_category->name); ?></label>
                
                <?php  ppws_get_woocommerce_product_categories($sub_category->term_id, $args, $value); ?>
            </div>
            <?php
        } ?>
        </div> <?php
    }
}

/* Encrypted Password */
function ppws_encrypted_password($ppws_pass){
    $ciphering = "AES-128-CTR";
    $options = 0;
    $encryption_iv = '1234567891011121';
    $encryption_key = "password-protected-store-for-woocommerce";
    $new_ppws_pass = (isset($ppws_pass) && !empty($ppws_pass)) ? openssl_encrypt($ppws_pass, $ciphering,$encryption_key, $options, $encryption_iv): '';
    return $new_ppws_pass;
}

/* Decrypted Password */
function ppws_decrypted_password($ppws_pass){
    $ciphering = "AES-128-CTR";
    $options = 0;
    $decryption_iv = '1234567891011121';
    $decryption_key = "password-protected-store-for-woocommerce";
    $new_ppws_pass = (isset($ppws_pass) && !empty($ppws_pass)) ? openssl_decrypt($ppws_pass, $ciphering,$decryption_key, $options, $decryption_iv): '';
    return $new_ppws_pass;
}

/* Get cookie on form submit */
function ppws_get_cookie($ppws_cookie_name){
    if ( ! isset( $_COOKIE[ $ppws_cookie_name ] ) ) {
        return false;
    }

    if( isset( $_COOKIE[ $ppws_cookie_name ] ) ){
        $ppws_cookie = sanitize_text_field($_COOKIE[ $ppws_cookie_name ]);
        return $ppws_cookie;
    }
}