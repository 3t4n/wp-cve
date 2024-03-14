<?php
    require_once('../../../../wp-load.php'); //load Wordpress

    if (is_user_logged_in()):
        global $acf_version;

        wp_enqueue_style('buckets_popup', plugins_url() . '/buckets/css/popup.css', array('common'));
        wp_enqueue_script('buckets-popup', plugins_url() . '/buckets/js/popup.js', array('jquery', 'jquery-ui-sortable'));

        acf_form_head();
?>
<!doctype html>
<html>
<head>
    <?php wp_head(); ?>
</head>
<body>
    <div id="bucket-popup" class="wp-core-ui">
        <?php
            if (isset($_GET['bucket_id'])){

                acf_form(array(
                    'post_id'       => intval($_GET['bucket_id']),
                    'post_title'    => true,
                    'submit_value'  => 'Update'
                ));

            } else {

                $field_group_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'acf_buckets' OR post_name = 'group_buckets' ");

                if ($acf_version >= 5){
                    acf_form(array(
                        'post_id'       => 'new_post',
                        'new_post'      => array(
                            'post_type'     => 'buckets',
                            'post_status'   => 'publish'
                        ),
                        'post_title'    => true,
                        'field_groups' => array($field_group_id),
                        'submit_value'  => 'Publish'
                    ));

                    acf_enqueue_uploader();

                } else {

                    acf_form(array(
                        'post_id'       => 'new_post',
                        'field_groups' => array($field_group_id),
                        'html_before_fields' => '<div id="acf_4" class="acf_postbox"><div class="field field_type-text" data-field_type="text"><label for="bucket_title">Title</label><div class="acf-input-wrap"><input type="text" id="bucket_title" class="text" name="bucket_title" value="" placeholder=""></div></div></div>',
                        'submit_value'  => 'Publish'
                    ));

                }
            }

        ?>
    </div>
    <?php wp_footer(); ?>
</body>
</html>

<?php
    else:
        wp_redirect(get_bloginfo('url'), 301);
    endif;
?>
