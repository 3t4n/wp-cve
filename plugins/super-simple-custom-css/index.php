<?php
/*
Plugin Name: Super Simple Custom CSS
Plugin URI:
Description: Super Simple Custom CSS wordpress plugin works perfect when user will need to add custom styling to very specific area of the website like All Post, All Page , Specific page, Specific post or sitewide
Version: 2.0
Author: ColoredWeb
Author URI: http://coloredweb.in
License: GPL3
License URI: http://www.gnu.org/licenses/gpl.html
*/


/**
 * Create table for plugin on plugin activation
 */
function cw_custom_css_plugin_create_db()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . 'cw_css';
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        type text NOT NULL,
        list text NOT NULL,
        css text NOT NULL,
        UNIQUE KEY id (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
register_activation_hook(__FILE__, 'cw_custom_css_plugin_create_db');



/**
 * This function return option of specific post type
 * @param string $ids
 * @param string $type 'post|page'
 * @return string|string[]
 */
function cw_get_all_option($ids, $type)
{
    if( isset($type) && $type!='' ){
        $posts_id = explode(',', $ids);
        $posts_id = array_filter($posts_id);
        global $wpdb;
        $myrows = $wpdb->get_results( $wpdb->prepare("SELECT ID,post_title FROM $wpdb->posts where post_status='publish' and post_type=%s and post_type!='revision'  ",$type) );
        $html = '';
        foreach ($myrows as $myrow) {
            if (in_array($myrow->ID, $posts_id)) {
                $html .= "<option value='$myrow->ID' selected > $myrow->post_title </option>";
            } else {
                $html .= "<option value='$myrow->ID'> $myrow->post_title </option>";
            }
        }
        return str_replace('"', '&quot;', $html);
    }
}


/**
 * Enqueue script & style for plugin
 */
function cw_custom_css_admin_add_script_style(){
    /* include chosen css file */
    wp_enqueue_style( 'sscc-chosen', plugins_url('library/chosen/chosen.css', __FILE__) ,false,'1.1','all');
    wp_enqueue_script( 'sscc-chosen-script', plugins_url('library/chosen/chosen.jquery.js', __FILE__), array ( 'jquery' ), 1.1, true);

    /* include ace editor js file */
    wp_enqueue_script( 'sscc-ace', 'https://cdn.jsdelivr.net/ace/1.1.9/min/ace.js', array ( 'jquery' ), 1.1, true);
    wp_enqueue_script( 'sscc-theme-monokai', 'https://cdn.jsdelivr.net/ace/1.1.9/min/theme-monokai.js', array ( 'jquery' ), 1.1, true);
    wp_enqueue_script( 'sscc-mode-css', 'https://cdn.jsdelivr.net/ace/1.1.9/min/mode-css.js', array ( 'jquery' ), 1.1, true);
}

add_action('admin_head', 'cw_custom_css_admin_add_script_style');


/**
 * Creat admin menu for plugin
 */
function cw_custom_css_admin_page(){
    add_menu_page(
        __('Super Simple Custom CSS', 'textdomain'),
        __('Super Simple Custom CSS', 'textdomain'),
        'manage_options',
        'super_simple_custom_css_slug',
        'cw_custom_css_setting',
        ''
    );
}
add_action('admin_menu', 'cw_custom_css_admin_page');


/**
 * Display setting page content
 */
function cw_custom_css_setting()
{
    global $wpdb;
    ?>

    <style type="text/css">
        .cw_sscc_btn {
            background: #0b9e79;
            padding: 10px 25px 9px 25px;
            border-radius: 3px;
            color: white;
            font-weight: bold;
            box-shadow: 2px 2px 4px black;
            margin-left: 10px;
            cursor: pointer;
            border: 0px;
        }

        div#tabs {
            display: flex;
            margin: 10px;
            padding: 9px 5px 5px 5px;
        }

        div#tabs li {
            float: left;
            background: #56c9d6;
            margin-right: 10px;
            padding: 10px 10px 10px 10px;
            color: white;
            list-style-type: none;
            font-size: 15px;
            cursor: pointer;
            border-radius: 3px;
            box-shadow: 1px 1px 1px gray;
        }

        li.t_selected {
            background: black !important;
        }

        .chosen-container {
            width: 50% !Important;
            margin-bottom: 10px;
        }

        ul.chosen-choices {
            border: 1px solid #ddd !important;
        }

        .add_c_button {
            border: 0px;
            padding: 10px 25px 10px 20px;
            background: black;
            color: white;
            font-weight: bold;
            border-radius: 3px;
            box-shadow: 2px 2px 2px gray;
            cursor: pointer;
        }

        .sscs_editor {
            /** Setting height is also important, otherwise editor wont showup**/
            height: 300px;
            width: 500px;
        }
    </style>
    <?php
    if (isset($_POST['cw_save_css_all_place'])) {
        $wpdb->get_results("Delete from " . $wpdb->prefix . "cw_css where type='All' ");

        $all_css = sanitize_textarea_field($_POST['cw_css_all_place']);
        $wpdb->insert($wpdb->prefix . 'cw_css', array(
            'type' => 'All',
            'css' => $all_css
        ));
    }
    if (isset($_POST['cw_save_css_all_post'])) {
        $wpdb->get_results("Delete from " . $wpdb->prefix . "cw_css where type='All Post' ");

        $all_post = sanitize_textarea_field($_POST['cw_css_all_post']);
        $wpdb->insert($wpdb->prefix . 'cw_css', array(
            'type' => 'All Post',
            'css' => $all_post
        ));
    }
    if (isset($_POST['cw_css_all_page'])) {
        $wpdb->get_results("Delete from " . $wpdb->prefix . "cw_css where type='All Page' ");

        $all_page = sanitize_textarea_field($_POST['cw_css_all_page']);
        $wpdb->insert($wpdb->prefix . 'cw_css', array(
            'type' => 'All Page',
            'css' => $all_page
        ));
    }
    if( isset($_POST['cw_save_css_sp_post']) ){
        $wpdb->get_results("Delete from " . $wpdb->prefix . "cw_css where type='Specific Post' ");

        if (isset($_POST['cw_css_sp_post'])) {
            for ($i = 0; $i < count($_POST['cw_css_sp_post']); $i++) {
                $s_p_d = sanitize_text_field(implode(',', $_POST['cw_css_sp_post'][$i]));
                if ($s_p_d != '') {
                    $sp_post = sanitize_textarea_field($_POST['cw_css_box_sp_post'][$i]);
                    $wpdb->insert($wpdb->prefix . 'cw_css', array(
                        'type' => 'Specific Post',
                        'list' => $s_p_d,
                        'css' => $sp_post
                    ));
                }
            }
        }
    }

    if( isset($_POST['cw_save_css_sp_page']) ){
        $wpdb->get_results("Delete from " . $wpdb->prefix . "cw_css where type='Specific Page' ");

        if (isset($_POST['cw_css_sp_page'])) {
            for ($i = 0; $i < count($_POST['cw_css_sp_page']); $i++) {
                $s_p_d = sanitize_text_field(implode(',', $_POST['cw_css_sp_page'][$i]));
                if ($s_p_d != '') {
                    $sp_page = sanitize_textarea_field($_POST['cw_css_box_sp_page'][$i]);
                    $wpdb->insert($wpdb->prefix . 'cw_css', array(
                        'type' => 'Specific Page',
                        'list' => $s_p_d,
                        'css' => $sp_page
                    ));
                }
            }
        }
    }

    $all_place = $wpdb->get_var('SELECT css FROM ' . $wpdb->prefix . 'cw_css where type="All" ');
    $all_post = $wpdb->get_var('SELECT css FROM ' . $wpdb->prefix . 'cw_css where type="All Post" ');
    $all_page = $wpdb->get_var('SELECT css FROM ' . $wpdb->prefix . 'cw_css where type="All Page" ');

    $s_post= $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'cw_css where type="Specific Post" ');
    $s_page = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'cw_css where type="Specific Page" ');

    ?>
    <h1>Super Simple Custom CSS Setting</h1>
    <div id='tabs'>
        <li id='tab_1' class='t_selected'>
            <div>Sitewide</div>
        </li>
        <li id='tab_2'>
            <div>All Post</div>
        </li>
        <li id='tab_3'>
            <div>All Page</div>
        </li>
        <li id='tab_4'>
            <div>Specific Post</div>
        </li>
        <li id='tab_5'>
            <div>Specific page</div>
        </li>
    </div>
    <div id='tabs_con'>
        <div id='tab_1_con' class="tab_con" style='margin: 10px;'>
            <h3>Sitewide CSS Box</h3><br>
            <form method="post" class="cscc_form">
                <textarea name="cw_css_all_place" class="cw_ace_editor" id="cw_css_all_place_textbox"
                          editor-id="cw_css_all_place_editor"
                          style="display: none"><?php echo stripslashes(isset($all_place) ? $all_place : ''); ?></textarea>
                <pre id="cw_css_all_place_editor" style="width: 500px"
                     class=""><?php echo stripslashes(isset($all_place) ? $all_place : ''); ?></pre>
                <input type="submit" name='cw_save_css_all_place' class="cw_sscc_btn">
            </form>
        </div>
        <div id='tab_2_con' class="tab_con" style='margin: 10px;display:none;'>
            <h3>All Post CSS Box</h3><br>
            <form method="post" class="cscc_form">
                <textarea name="cw_css_all_post" class="cw_ace_editor" id="cw_css_all_post_textbox"
                          editor-id="cw_css_all_post_editor"
                          style="display: none"><?php echo stripslashes(isset($all_post) ? $all_post : ''); ?></textarea>
                <pre id="cw_css_all_post_editor" style="width: 500px"
                     class=""><?php echo stripslashes(isset($all_post) ? $all_post : ''); ?></pre>
                <input type="submit" name='cw_save_css_all_post' class="cw_sscc_btn">
            </form>
        </div>
        <div id='tab_3_con' class="tab_con" style='margin: 10px;display:none;'>
            <h3>All Page CSS Box</h3><br>
            <form method="post" class="cscc_form">
                <textarea name="cw_css_all_page" class="cw_ace_editor" id="cw_css_all_page_textbox"
                          editor-id="cw_css_all_page_editor"
                          style="display: none"><?php echo stripslashes(isset($all_page) ? $all_page : ''); ?></textarea>
                <pre id="cw_css_all_page_editor" style="width: 500px"
                     class=""><?php echo stripslashes(isset($all_page) ? $all_page : ''); ?></pre>
                <input type="submit" name='cw_save_css_all_page' class="cw_sscc_btn">
            </form>
        </div>
        <div id='tab_4_con' class="tab_con" style='margin: 10px;display:none;'>
            <h3>Specific Post CSS Box</h3><br>
            <form method="post" class="cscc_form">
                <div>
                    <button type="button" class='add_c_button' id='add_om_po_cs'>Add One More</button>
                </div>
                <br>
                <div class="cw_scc_sp_post_box">
                    <?php
                    $j = 0;
                    foreach ($s_post as $c_p) {
                        ?>
                        <select name='cw_css_sp_post[<?php echo $j ?>][]' style="width: 50%;"
                                data-placeholder='Select Specific Post...' class='chosen-select' multiple tabindex='4'>
                            <?php echo cw_get_all_option($c_p->list,'post'); ?>
                        </select>
                        <textarea class="cw_ace_editor" id="cw_css_box_sp_post_<?= $j ?>_textbox"
                                  editor-id="cw_css_box_sp_post_<?= $j ?>_editor"
                                  name='cw_css_box_sp_post[<?php echo $j ?>]' style="display: none"
                                  placeholder="Add Css"><?php echo stripslashes($c_p->css); ?></textarea>
                        <pre id="cw_css_box_sp_post_<?= $j ?>_editor" style="width: 500px"
                             class=""><?php echo stripslashes($c_p->css); ?></pre>
                        <hr>
                        <?php
                        $j++;
                    }
                    ?>
                    <select name='cw_css_sp_post[<?php echo $j ?>][]' style="width: 50%;"
                            data-placeholder='Select Specific Post...' class='chosen-select' multiple tabindex='4'>
                        <?php echo cw_get_all_option('','post'); ?>
                    </select>
                    <textarea class="cw_ace_editor" id="cw_css_box_sp_post_<?= $j ?>_textbox"
                              editor-id="cw_css_box_sp_post_<?= $j ?>_editor" name='cw_css_box_sp_post[<?php echo $j ?>]'
                              style="display: none"
                              placeholder="Add Css"></textarea>
                    <pre id="cw_css_box_sp_post_<?= $j ?>_editor" style="width: 500px" class=""></pre>
                </div>
                <input type="submit" name='cw_save_css_sp_post' class="cw_sscc_btn">
            </form>
        </div>
        <div id='tab_5_con' class="tab_con" style='margin: 10px;display:none;'>
            <h3>Specific page</h3><br>
            <form method="post" class="cscc_form">
                <div class="cw_scc_sp_page_box">
                    <div>
                        <button type="button" class='add_c_button' id='add_om_pa_cs'>Add One More</button>
                    </div>
                    <br>
                    <?php
                    $k = 0;
                    foreach ($s_page as $cp) {
                        ?>
                        <select name='cw_css_sp_page[<?php echo $k ?>][]' style="width: 50%;"
                                data-placeholder='Select Specific Page...' class='chosen-select' multiple tabindex='4'>
                            <?php echo cw_get_all_option($cp->list,'page'); ?>
                        </select>
                        <textarea class="cw_ace_editor" id="cw_css_box_sp_page_<?= $k ?>_textbox"
                                  editor-id="cw_css_box_sp_page_<?= $k ?>_editor" name='cw_css_box_sp_page[<?php echo $k ?>]' style="display: none"
                                  placeholder="Add Css"><?php echo stripslashes($cp->css) ?></textarea>
                        <pre id="cw_css_box_sp_page_<?= $k ?>_editor" style="width: 500px"
                             class=""><?php echo stripslashes($cp->css); ?></pre>
                        <hr>
                        <?php
                        $k++;
                    }
                    ?>
                    <select name='cw_css_sp_page[<?php echo $k ?>][]' style="display: none"
                            data-placeholder='Select Specific Page...' class='chosen-select' multiple tabindex='4'>
                        <?php echo cw_get_all_option('','page') ?>
                    </select>
                    <textarea class="cw_ace_editor" id="cw_css_box_sp_page_<?= $k ?>_textbox"
                              editor-id="cw_css_box_sp_page_<?= $k ?>_editor" name='cw_css_box_sp_page[<?php echo $k ?>]' style="display: none"
                              placeholder="Add Css"></textarea>
                    <pre id="cw_css_box_sp_page_<?= $k ?>_editor" style="width: 500px"
                         class=""></pre>
                </div>
                <input type="submit" name='cw_save_css_sp_page' class="cw_sscc_btn">
            </form>
        </div>
    </div>
    <div style="    padding-left: 9px;
    padding-bottom: 7px;
    color: red;"><span style="color:black;">Note:</span> Don't include style tag in css
    </div>


    <script>
        jQuery(document).ready(function ($) {
            $(".chosen-select").chosen();
            create_ace_editor(null, null);

            function create_ace_editor(editor_id = null, text_box_id = null) {
                if (editor_id == null) {
                    $(".cw_ace_editor").each(function (index, element) {
                        var editor_id = $(element).attr('editor-id')

                        var editor = ace.edit(editor_id);
                        editor.setTheme("ace/theme/monokai");
                        editor.getSession().setMode("ace/mode/css")
                        editor.setOptions({maxLines: 30, minLines: 10});

                        editor.on(
                            'change', function (e) {
                                $('#' + element.id).val(editor.getSession().getValue());
                                editor.resize();
                            }
                        );

                    })
                } else {
                    var editor = ace.edit(editor_id);
                    editor.setTheme("ace/theme/monokai");
                    editor.getSession().setMode("ace/mode/css")
                    editor.setOptions({maxLines: 30, minLines: 10});

                    editor.on(
                        'change', function (e) {
                            $('#' + text_box_id).val(editor.getSession().getValue());
                            editor.resize();
                        }
                    );
                }
            }


            $('.cscc_form').submit(function () {
                $("#cw_css_all_place_value").attr('value', editor.getValue());
            })

            var c_p_c =<?php echo $j + 1; ?>;
            $('#tabs_con').on('click', '#add_om_po_cs', function () {
                $(".cw_scc_sp_post_box").append("<hr><br><select name='cw_css_sp_post[" + c_p_c + "][]' style='width: 50%;' data-placeholder='Select Specific Post...' id='post_abccc_" + c_p_c + "' multiple  tabindex='4'><?php echo cw_get_all_option('','post') ?></select>" +
                    "<textarea class='cw_ace_editor' id='cw_css_box_sp_post_" + c_p_c + "_textbox' editor-id='cw_css_box_sp_post_" + c_p_c + "_editor' name='cw_css_box_sp_post[" + c_p_c + "]'  style='display:none' rows='10' placeholder='Add Css'></textarea>" +
                    "<pre id='cw_css_box_sp_post_" + c_p_c + "_editor' style='width: 500px' ></pre>");
                $("#post_abccc_" + c_p_c).chosen();
                create_ace_editor('cw_css_box_sp_post_' + c_p_c + '_editor', 'cw_css_box_sp_post_' + c_p_c + '_textbox');
                c_p_c++;
                return false;
            });

            var c_pa_c =<?php echo $k + 1; ?>;
            $('#tabs_con').on('click', '#add_om_pa_cs', function () {
                $(".cw_scc_sp_page_box").append("<hr><br><select name='cw_css_sp_page[" + c_pa_c + "][]' style='width: 50%;' data-placeholder='Select Specific Page...' id='page_abccc_" + c_pa_c + "' multiple  tabindex='4'><?php echo cw_get_all_option('','page') ?></select>"+
                "<textarea class='cw_ace_editor' id='cw_css_box_sp_page_" + c_pa_c + "_textbox' editor-id='cw_css_box_sp_page_" + c_pa_c + "_editor' name='cw_css_box_sp_page[" + c_pa_c + "]'  style='display:none' placeholder='Add Css'></textarea>"+
                "<pre id='cw_css_box_sp_page_" + c_pa_c + "_editor' style='width: 500px' ></pre>");
                $("#page_abccc_" + c_pa_c).chosen();
                create_ace_editor('cw_css_box_sp_page_' + c_pa_c + '_editor', 'cw_css_box_sp_page_' + c_pa_c + '_textbox');
                c_pa_c++;
                return false;
            });

            $("div#tabs li").click(function () {
                $("div#tabs li").removeClass("t_selected");
                $(this).addClass("t_selected");
                $("div#tabs_con .tab_con").css('display', 'none');
                var cl = $(this).attr('id');
                console.log(cl);
                $("#" + cl + "_con").show();
            });

            $("select").change(function () {
                var id = $(this).attr('data-id');

                if ($(this).val() == 'All Post' || $(this).val() == 'All Page') {
                    $("#cw_css_sp_post_" + id + "_chosen").hide();
                    $("#cw_css_sp_page_" + id + "_chosen").hide();
                }
                if ($(this).val() == 'Specific Post') {
                    $("#cw_css_sp_post_" + id + "_chosen").show();
                    $("#cw_css_sp_page_" + id + "_chosen").hide();
                }
                if ($(this).val() == 'Specific Page') {
                    $("#cw_css_sp_post_" + id + "_chosen").hide();
                    $("#cw_css_sp_page_" + id + "_chosen").show();
                }

            })


        })
    </script>
    <?php
}


/**
 * Display css in website
 */
function cw_custom_css_display_css(){
    global $wpdb;

    $html='';

    /* get site wide css */
    $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'cw_css where type="All" ');
    if (isset($results[0]->css)) {
        $html .= stripslashes( sanitize_textarea_field( $results[0]->css ) );
    }

    if (is_single()) {

        /* get all post css */
        $results_all = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'cw_css where type="All Post" ');
        foreach ($results_all as $res) {
            $html .= stripslashes( sanitize_textarea_field( $res->css ) );
        }

        /* get specific post css */
        $results_s = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'cw_css where type="Specific Post" ');
        foreach ($results_s as $r_s) {
            $ids = explode(',', $r_s->list);
            $ids = array_filter($ids);
            if (in_array(get_the_ID(), $ids)) {
                $html .= stripslashes( sanitize_textarea_field($r_s->css));
            }

        }

    }
    if (is_page()) {

        /* get all page css */
        $results_all = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'cw_css where type="All Page" ');
        foreach ($results_all as $res) {
            $html .= stripslashes( sanitize_textarea_field($res->css));
        }

        /* get specific page css */
        $results_s = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'cw_css where type="Specific Page" ');
        foreach ($results_s as $r_s) {
            $ids = explode(',', $r_s->list);
            $ids = array_filter($ids);
            if (in_array(get_the_ID(), $ids)) {
                $html .= stripslashes( sanitize_textarea_field($r_s->css));
            }

        }
    }

    /* get current post type css */
    $html.= stripslashes(get_post_meta(get_the_ID(), 'cw_css_c_post', true));

    echo "<style id='cw_css' >" . $html . "</style>";

}
add_action('wp_head', 'cw_custom_css_display_css');



/**
 * Add meta box for post type
 */
function cw_custom_css_register_meta_boxes_post(){
    add_meta_box('cw_css_id', __('Super Simple Custom CSS', 'textdomain'), 'cw_custom_css_metabox_display', 'post');
}
add_action('add_meta_boxes', 'cw_custom_css_register_meta_boxes_post');


/**
 * Add meta box for page type
 */
function cw_custom_css_register_meta_boxes_page(){
    add_meta_box('cw_css_id', __('Super Simple Custom CSS', 'textdomain'), 'cw_custom_css_metabox_display', 'page');
}
add_action('add_meta_boxes', 'cw_custom_css_register_meta_boxes_page');


/**
 * Display meta box content
 * @param $post
 */
function cw_custom_css_metabox_display($post)
{
    ?>
    <textarea class='cw_ace_editor' id='cw_css_c_post_textbox' placeholder="Add css here" style="display: none"
              name="cw_css_c_post"><?php echo get_post_meta($post->ID, 'cw_css_c_post', true) ?></textarea>
    <pre id="cw_css_c_post_editor"><?php echo get_post_meta($post->ID, 'cw_css_c_post', true) ?></pre>

    <script>
        jQuery(document).ready(function ($) {
            var editor = ace.edit('cw_css_c_post_editor');
            editor.setTheme("ace/theme/monokai");
            editor.getSession().setMode("ace/mode/css")
            editor.setOptions({maxLines: 30, minLines: 10});

            editor.on(
                'change', function (e) {
                    $('#cw_css_c_post_textbox').val(editor.getSession().getValue());
                }
            );
        })

    </script>

    <?php
}


/**
 * Save css for single post type
 * @param $post_id
 */
function cw_custom_css_save_meta_box($post_id){
    if (isset($_POST['cw_css_c_post'])) {
        $css_c = sanitize_textarea_field($_POST['cw_css_c_post']);
        update_post_meta($post_id, 'cw_css_c_post', $css_c);
    }
}
add_action('save_post', 'cw_custom_css_save_meta_box');

?>