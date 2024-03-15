<?php
add_action('add_meta_boxes', 'kopa_portfolio_meta_box_add');

function kopa_portfolio_meta_box_add() {
    add_meta_box('kopa-portfolio-edit', 'Meta Box', 'kopa_meta_box_portfolio_cb', 'portfolio', 'normal', 'high');
}

function kopa_meta_box_portfolio_cb($post) {
    $portfolio_thumbnail_size = get_post_meta($post->ID, 'portfolio_thumbnail_size', true);

    wp_nonce_field('portfolio_meta_box_nonce', 'portfolio_meta_box_nonce');
    ?>
    
    <p class="kopa_option_box">
        <label for="portfolio_thumbnail_size" class="kopa-desc"><?php _e('Thumbnail size', 'kopa-nictitate-toolkit'); ?>:</label>
        <select name="portfolio_thumbnail_size" id="portfolio_thumbnail_size">
            <?php 
            $thumbnail_sizes = array(
                '118x118' => '118 x 118',
                '118x239' => '118 x 239',
                '239x118' => '239 x 118',
                '239x239' => '239 x 239'
            );

            foreach ( $thumbnail_sizes as $value => $label )
                printf('<option value="%1$s" %2$s>%3$s</option>', $value, selected($value, $portfolio_thumbnail_size), $label);
            ?>
        </select>
    </p>  

    <?php
}

add_action('save_post', 'kopa_save_portfolio_data');

function kopa_save_portfolio_data($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    if (!isset($_POST['portfolio_meta_box_nonce']) || !wp_verify_nonce($_POST['portfolio_meta_box_nonce'], 'portfolio_meta_box_nonce'))
        return;
    if (!current_user_can('edit_post'))
        $allowed = array(
            'a' => array(
                'href' => array()
            )
        );

    if (isset($_POST['portfolio_thumbnail_size']))
        update_post_meta($post_id, 'portfolio_thumbnail_size', wp_kses($_POST['portfolio_thumbnail_size'], $allowed));
}