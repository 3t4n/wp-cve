<?php

/* hook the metabox */
add_action('admin_init', 'dmb_rtbs_add_help', 1);
function dmb_rtbs_add_help()
{
	add_meta_box(
		'rtbs_help',
		'Shortcode',
		'dmb_rtbs_help_display',
		'rtbs_tabs',
		'side',
		'high'
	);
}


/* displays the metabox */
function dmb_rtbs_help_display()
{ ?>

<div class="dmb_side_block">
    <p>
        <?php
			global $post;
			$slug = '';
			$slug = $post->post_name; ?>

        <?php if ($slug != '') { ?>
        <span
            style="display:inline-block;border:solid 2px lightgray; background:white; padding:0 8px; font-size:13px; line-height:25px; vertical-align:middle;">[rtbs
            name="<?php echo esc_attr($slug); ?>"]</span>
        <?php } else { ?>
        <span style='display:inline-block;color:#849d3a'>
            <?php /* translators: Leave HTML tags */ esc_attr_e("Publish your tab set before you can see your shortcode.", RTBS_TXTDM); ?>
        </span>
        <?php } ?>
    </p>
    <p>
        <?php /* translators: Leave HTML tags */ esc_attr_e('To display your tab set on your site, copy-paste the shortcode above in your post/page.', RTBS_TXTDM) ?>
    </p>
</div>

<div class="dmb_side_block">
    <div class="dmb_help_title">
        Get support
    </div>
    <a target="_blank" href="https://wpdarko.com/support/submit-a-request/">Submit a ticket</a><br />
    <a target="_blank" href="https://wpdarko.com/support/docs/get-started-with-the-responsive-tabs-plugin/">View
        documentation</a>
</div>

<?php } ?>