<?php
/*
 * Plugin Name: Tidekey
 * Plugin URI: http://www.makong.kiev.ua/plugins/tidekey.zip
 * Description: Custom meta tags(title, description, keywords)
 * Version: 1.1
 * Author: makong
 * Author URI: http://www.makong.kiev.ua
 * License: GPL2
 */

define('TIDEKEY_I18N_DOMAIN', 'tidekey');
define('TIDEKEY_PATH', WP_PLUGIN_DIR . '/tidekey/');
define('TIDEKEY_URL', WP_PLUGIN_URL . '/tidekey/');

register_activation_hook( __FILE__, 'tidekey_install');
register_deactivation_hook( __FILE__, 'tidekey_uninstall');

load_plugin_textdomain(
    TIDEKEY_I18N_DOMAIN,
    'wp-content/plugins/tidekey/languages',
    'tidekey/languages'
);

/*actions*/
add_action('admin_menu', 'tidekey_admin_page');
add_action('admin_init', 'tidekey_styles');
add_action('wp_head', 'tidekey_meta', 11, 1);
add_filter('wp_title', 'tidekey_rw_title', 11, 2);

/*filters*/
function tidekey_install(){
    add_option('tidekey_options',array('pagin' => __('Pages %d','tidekey')));
    add_option('tidekey_templates',array());
    add_option('tidekey_titles',array());
}
function tidekey_uninstall(){
	/*
    delete_option('tidekey_options');
    delete_option('tidekey_templates');
    delete_option('tidekey_titles');
	*/
}

function tidekey_styles(){
    wp_register_style('tidekey-style', TIDEKEY_URL.'css/style.css');
    wp_enqueue_style('tidekey-style');
}

function tidekey_admin_page(){
    global $hook;
    $hook = add_options_page('tidekey', 'Tidekey', 8, 'tidekey', 'tidekey_page');
}

function tidekey_page(){
    global $hook, $tab;
    if($hook):
	$settings_tabs = array(
            'settings' => __('Settings','tidekey'),
            'titles' => __('Titles','tidekey'),
            'descriptions' => __('Descriptions','tidekey'),
            'keywords' => __('Keywords','tidekey')
        );
        $tab = isset( $_GET['tab'] ) ? $_GET['tab'] : key($settings_tabs);
        
        tidekey_catch_post($_POST); 
        tidekey_catch_get($_GET);
        
        $tidekey_options = get_option('tidekey_options');
        $tidekey_templates = @array_map('stripslashes_deep',get_option('tidekey_templates'));
        ${"tidekey_$tab"} = @array_map('stripslashes_deep',get_option('tidekey_'.$tab));
        $langs = (ICL_LANGUAGE_CODE and 'on' == ($tidekey_options['wpml'])) 
            ? $langs = icl_get_languages() 
            : array('default' => array('translated_name'=>''));?>
        
        <h2 class="tidekey-title"><?php _e('Tidekey','tidekey');?></h2>
        <div class="wrap"><?php tidekey_tabs( $settings_tabs, 'tidekey'); ?></div>
        <?php switch($tab):
            case 'settings':?>
                <h3><?php _e('General','tidekey')?>:</h3>
                <form class="card pressthis" name="tidekey_general_form" method="post" action="<?=$_SERVER['PHP_SELF']?>?page=tidekey&tab=<?= $tab?>&message=1">
                    <?php if (function_exists ('wp_nonce_field') ) wp_nonce_field('tidekey_general_form');?>
                    <input type="hidden" name="action" value="tidekey_general"/>
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><label><?php _e('Paginated addition','tidekey');?></label></th>
                                <td>
                                    <input type="text" name="pagin" value="<?= $tidekey_options['pagin']?>">
                                    <br><small>%d - <?php _e('Number of pages', 'tidekey')?></small>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><label><?php _e('Dedicated metadata','tidekey');?></label></th>
                                <td><ul>
                                    <?php foreach(get_post_types(array('public' => true ), 'objects') as $pt):
                                        $checked = ('on' == $tidekey_options['ind'][$pt->name]) ? 'checked' : ''?>
                                        <li><input type="checkbox" name="ind[<?= $pt->name?>]" <?= $checked?>>&nbsp;<?= $pt->labels->name?></li>
                                    <?php endforeach;?>
                                </ul></td>
                            </tr>
                            <tr>
                                <th scope="row"><label><?php _e('WPML Compatibility','tidekey');?></label></th>
                                <td><input type="checkbox" name="wpml" <?= ('on' == $tidekey_options['wpml']) ? 'checked' : ''?> /></td>
                            </tr>
                        </tbody>
                    </table>
                    <input type="submit" name="general_btn" class="button success" value="<?php _e('Update','tidekey')?>">
                </form>
                <h3><?php _e('Postmeta templates','tidekey')?>:</h3>
                <div class="tidekey-templates card pressthis">
                    <?php foreach($tidekey_templates as $pm => $tpl):?>
                        <form name="tidekey_templates_form" method="post" action="<?=$_SERVER['PHP_SELF']?>?page=tidekey&tab=<?= $tab?>&message=1">
                            <?php if (function_exists ('wp_nonce_field') ) wp_nonce_field('tidekey_templates_form');?>
                            <input type="hidden" name="action" value="tidekey_templates"/>
                            <table><tr>
                                <td><select name="postmeta">
                                    <option></option>
                                    <?php foreach(tidekey_meta_keys() as $postmeta){
                                        $sel = ($postmeta == $pm) ? 'selected' : '';
                                        echo '<option '.$sel.'>'.$postmeta.'</option>';
                                    }?>
                                </select></td>
                                <td>&#8596;</td>
                                <td><input type="text" name="template" placeholder="%EXAMPLE%" value="<?= $tpl?>"></td>
                                <td>
                                    <input type="submit" name="edit_templates_btn" class="button" value="<?php _e('Edit','tidekey')?>">
                                    <input type="submit" name="del_templates_btn" class="button delete" value="<?php _e('Del','tidekey')?>" onclick="return confirm('<?php _e('Are you sure?','tidekey')?>')">
                                </td>
                            </tr></table>
                        </form>
                    <?php endforeach;?>
                </div>
                <form class="card pressthis" name="tidekey_templates_form" method="post" action="<?=$_SERVER['PHP_SELF']?>?page=tidekey&tab=<?= $tab?>&message=1">
                    <?php if (function_exists ('wp_nonce_field') ) wp_nonce_field('tidekey_templates_form');?>
                    <input type="hidden" name="action" value="tidekey_templates"/>
                    <table><tr>
                        <td><select name="postmeta">
                            <option></option>
                            <?php foreach(tidekey_meta_keys() as $postmeta){
                            echo '<option>'.$postmeta.'</option>';}?>
                        </select></td>
                        <td>&#8596;</td>
                        <td><input type="text" name="template" placeholder="%EXAMPLE%"></td>
                        <td><input type="submit" name="add_templates_btn" class="button success" value="<?php _e('Add','tidekey')?>"></td>
                    </tr></table>
                </form>
            <?php break;
            default:
                include('template.php');
            break;
        endswitch;
    endif;
}

function tidekey_tabs($settings_tabs, $page) {
    $current_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : key($settings_tabs);
    screen_icon();?>
    <h2 class="nav-tab-wrapper">
    <?php foreach ( $settings_tabs as $tab_key => $tab_caption ):
        $active = $current_tab == $tab_key ? 'nav-tab-active' : '';?>
        <a class="nav-tab <?= $active?>" href="?page=<?= $page?>&tab=<?= $tab_key?>"><?= $tab_caption ?></a>
	<?php endforeach;?>
    </h2>
<?php }

function tidekey_catch_post($post){
    global $tab;
    if(isset($post['action'])){
        if(function_exists('current_user_can') && !current_user_can('manage_options') ) die ( _e('Hacker?', 'megaba') );
        if (function_exists ('check_admin_referer') ) check_admin_referer($post['action'].'_form');
 
        switch($post['action']):
            case 'tidekey_general':
                $tidekey_options = array(
                    'wpml' => $post['wpml'],
                    'pagin' => $post['pagin'],
                    'ind' => $post['ind']
                );
                update_option('tidekey_options', $tidekey_options);
            break;
            case 'tidekey_templates':
                $tidekey_templates = get_option('tidekey_templates');
                if(isset($post['del_templates_btn'])){
                    unset($tidekey_templates[$post['postmeta']]);
                    update_option('tidekey_templates', $tidekey_templates);
                }
                else{
                    update_option(
                        'tidekey_templates', 
                        array_merge(
                            $tidekey_templates, 
                            array($post['postmeta'] => $post['template'])
                        )
                    );
                }
            break;
            case "tidekey_$tab":
                update_option("tidekey_$tab", $post["tidekey_$tab"]);
            break;
        endswitch;
    }
}

function tidekey_catch_get($get){
    if(isset($get['message'])){
        switch($get['message']){
            case 1:
                echo '<div class="updated"><p><strong>' . __('Operation successfull!','tidekey') . '</strong></p></div>';
            break;
        }
    }
}

function tidekey_meta_keys(){
    global $wpdb;
    $meta_keys = array();
    foreach(get_post_types(array('public' => true), 'names') as $pt){
        $query = "
            SELECT DISTINCT($wpdb->postmeta.meta_key) 
            FROM $wpdb->posts 
            LEFT JOIN $wpdb->postmeta 
            ON $wpdb->posts.ID = $wpdb->postmeta.post_id 
            WHERE $wpdb->posts.post_type = '%s' 
            AND $wpdb->postmeta.meta_key != '' 
            AND $wpdb->postmeta.meta_key NOT RegExp '(^[_0-9].+$)' 
            AND $wpdb->postmeta.meta_key NOT RegExp '(^[0-9]+$)'
        ";
        $meta_keys = array_merge($meta_keys,$wpdb->get_col($wpdb->prepare($query, $pt)));
    }
    return $meta_keys;
}

function tidekey_get_string($param){
    $tidekey_string = @array_map('stripslashes_deep',get_option("tidekey_$param"));
    $tidekey_options = get_option('tidekey_options');
    $tidekey_templates = get_option('tidekey_templates');
    $lang = (ICL_LANGUAGE_CODE and 'on' == $tidekey_options['wpml']) 
        ? ICL_LANGUAGE_CODE 
        : 'default';
    
    if(is_home()){
        $string = $tidekey_string['other']['home'][$lang];
    }
    elseif(is_single() or is_page()){
        global $post;
        
        if(!empty($tidekey_string['pt'][$post->post_type]['ind_'.$post->ID][$lang])){
            $string = $tidekey_string['pt'][$post->post_type]['ind_'.$post->ID][$lang];
        }
        else{
            $string = $tidekey_string['pt'][$post->post_type]['single'][$lang];
        }
        
        $string = str_replace('%TITLE%', $post->post_title, $string);
        
        foreach($tidekey_templates as $pm => $tpl){
            if(strstr($string, $tpl)){
                                
                $meta_value = get_post_meta($post->ID, $pm, true);
                if(is_array($meta_value)){
                    
                    if(2 == count($meta_value)) $sep = __(' and ', 'tidekey');
                    else $sep = ', ';
                    
                    $meta_string = '';
                    
                    foreach($meta_value as $term_id){
                        $meta_string .= tidekey_term_name($term_id).$sep;
                    }
                    $meta_string = substr($meta_string, 0, '-'.strlen($sep));
                }
                else{
                    $meta_string = (string)$meta_value;
                }
                
                $string = str_replace($tpl, $meta_string, $string);
            }
        }
    }
    elseif(is_tax()){
        $object = get_queried_object();
        $string = str_replace(
            array('%TITLE%', '%POSTS_COUNT%'),
            array($object->name, tidekey_posts_count($object)), 
            $tidekey_string['tax'][$object->taxonomy][$lang]
        );
    }
    elseif(is_archive()){
        $object = get_queried_object();
        $string = str_replace(
            array('%TITLE%', '%POSTS_COUNT%'),
            array($object->label, tidekey_posts_count($object)),
            $tidekey_string['pt'][$object->name]['archive'][$lang]
        );
    }
    else{
        $string = '';
    }
    return $string;
}

function tidekey_meta(){
    $tidekey_descriptions = tidekey_get_string('descriptions');
    $tidekey_keywords = tidekey_get_string('keywords');

    if(!empty($tidekey_descriptions))
        echo '<meta name="description" content="'.$tidekey_descriptions.'">'."\n";
    if(!empty($tidekey_keywords))
        echo '<meta name="keywords" content="'.$tidekey_keywords.'">'."\n";
}

function tidekey_rw_title($title, $sep){
    global $page, $paged;
    $tidekey_title = tidekey_get_string('titles');
    $tidekey_options = get_option('tidekey_options');
    
    if(!empty($tidekey_title))
        $title = $tidekey_title;
    
    if ( $paged >= 2 || $page >= 2 ) {
        $title .= " {$sep} " . sprintf( $tidekey_options['pagin'], max( $paged, $page ) );
    }
        
    return $title;
}

function tidekey_term_name($term_id){
    global $wpdb;
    $name = '';
    $name = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT name FROM $wpdb->terms WHERE term_id = %d", 
            absint($term_id)
        )
    );
    return $name[0]->name;
}

function tidekey_posts_count($object){
    if(is_tax()){
        $count = $object->count;
    }
    elseif(is_archive()){
        $the_query = new WP_Query( array(
            'post_type' => $object->name,
            'post_status' => 'publish',
            'showposts' => -1,
            'posts_per_page' => -1
        ) );
        $count = $the_query->found_posts;
    }
    else $count = 0;
        
    return $count;
}

function tidekey_get_rows($post_type, $post_id = null){
    global $wpdb;
    $rows = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT ID, post_title FROM $wpdb->posts WHERE post_type = '%s' AND post_status = 'publish'", 
            $post_type
        )
    );
    return $rows;
}