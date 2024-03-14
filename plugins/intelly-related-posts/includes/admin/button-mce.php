<?php
add_action('admin_head', 'irp_add_mce_button');
function irp_add_mce_button() {
    global $typenow;

    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }

    if(!in_array($typenow, array('post', 'page'))) {
        return;
    }
    if (get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "irp_add_mce_plugin");
        add_filter('mce_buttons', 'irp_register_mce_button');
    }
}

function irp_add_mce_plugin($plugin_array) {
    $plugin_array['irp_mce_button']=IRP_PLUGIN_ASSETS.'js/button-mce.js';
    return $plugin_array;
}
function irp_register_mce_button($buttons) {
    array_push($buttons, "irp_mce_button");
    return $buttons;
}

function irp_ui_button_editor() {
    global $irp;

    wp_enqueue_style( 'irp_free_select2_style', IRP_PLUGIN_ASSETS . 'css/style.css' );
    wp_enqueue_script( 'irp_free_common', IRP_PLUGIN_ASSETS . 'js/common.js', array('jquery') );
    $postType = '';
    if ( isset($_REQUEST['irp_post_type']) ) {
        $postType = sanitize_text_field($_REQUEST['irp_post_type']);
    }
    wp_add_inline_script( 'irp_free_common', 'const search_data = ' . json_encode( array(
        'post_type' => $postType ) ) );

    $irp->Utils->printScriptCss();
    $irp->Form->prefix='Editor';
    $irp->Form->labels=FALSE;

    $args=array('class'=>'wp-admin wp-core-ui admin-bar'
        , 'style'=>'padding:10px; margin-left:auto; margin-right:auto;');
    $irp->Form->formStarts('post', '', $args);
    {
        ?>
        <p style="text-align:center;"><?php $irp->Lang->P('EditorSubtitle') ?></p>
        <?php
        $irp->Form->select('irpPostId', '', array() );
        ?>
        <div style="clear:both;"></div>
        <p style="text-align:right;">
            <input type="button" id="btnInsert" class="button button-primary irp-button irp-submit" value="<?php $irp->Lang->P('Insert')?>"/>
            <input type="button" id="btnClose" class="button irp-button" value="<?php $irp->Lang->P('Cancel')?>"/>
        </p>
    <?php
    }
    $irp->Form->formEnds();
    exit;
}

