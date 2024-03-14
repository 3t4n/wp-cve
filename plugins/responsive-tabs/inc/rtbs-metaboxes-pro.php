<?php

/* hook the metabox */
add_action('admin_init', 'dmb_rtbs_add_pro', 1);
function dmb_rtbs_add_pro()
{
    add_meta_box(
        'rtbs_pro',
        'Get PRO',
        'dmb_rtbs_pro_display',
        'rtbs_tabs',
        'side',
        'high'
    );
}

/* display the metabox */
function dmb_rtbs_pro_display()
{ ?>

<div class="dmb_side_block">
    <div class="dmb_side_block_title">
        <span class="dashicons dashicons-yes" style="color:#81c240;"></span> Tab styling
    </div>
    Add a small arrow below the current tab. Choose between squared and rounded tabs.
</div>

<div class="dmb_side_block">
    <div class="dmb_side_block_title">
        <span class="dashicons dashicons-yes" style="color:#81c240;"></span> Links to specific tabs
    </div>
    Create links to your tab page with a specific tab open.
</div>

<div class="dmb_side_block">
    <div class="dmb_side_block_title">
        <span class="dashicons dashicons-yes" style="color:#81c240;"></span> Icons for your tabs
    </div>
    Add icons to your tabs using the Font-Awesome free library.
</div>

<div class="dmb_side_block">
    <div class="dmb_side_block_title">
        <span class="dashicons dashicons-yes" style="color:#81c240;"></span> Link-only tabs
    </div>
    Create tabs without content. Just links.
</div>

<a class="dmb_big_button_primary dmb_see_pro" target="_blank" href="https://wpdarko.com/items/responsive-tabs-pro/">
    Check out PRO features&nbsp;
</a>

<span style="display:block;margin-top:15px; font-size:12px; color:#0073AA; line-height:20px;">
    <span class="dashicons dashicons-cart"></span> Discount code
    <strong>7832922</strong> (10% OFF)
</span>

<?php } ?>