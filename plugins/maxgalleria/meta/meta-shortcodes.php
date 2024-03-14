<?php
global $post;
?>

<p><?php esc_html_e('Use either of the following shortcodes to display this gallery:', 'maxgalleria') ?></p>

<textarea readonly style="width: 100%; height: 45px; font-weight: bold; font-size: 13px;">[maxgallery id="<?php echo esc_attr($post->ID) ?>"]</textarea>

<?php if ($post->post_status == 'publish') { ?>
	<textarea readonly style="width: 100%; height: 45px; font-weight: bold; font-size: 13px;">[maxgallery name="<?php echo esc_html($post->post_name) ?>"]</textarea>
<?php } ?>