<meta charset="utf-8">
<?
global $wpdb;
$table_name = $wpdb->prefix . "seo_images";

$select_watermark_text = "SELECT * FROM {$table_name} WHERE wgi_id = 1";
$qselect_watermark_text = $wpdb->get_row($select_watermark_text);
?>
<div id="id_watermark" class="wrap">


    <div id="icon-options-general" class="icon32"><br /></div><h2>Edit WordPress SEO Images</h2>        

    <h3 class="title">1. Modify your watermark text</h3>

    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row"><label for="default_category">Watermark Text</label></th>
                <td>
                    <input type="hidden" name="wgi_plugin_url" id="wgi_plugin_url" value="<?= plugins_url();?>"/>
                    <input type="text" name="wgi_text" id="wgi_text" class="postform" value="<?= $qselect_watermark_text->wgi_text; ?>" SIZE="60" MAXLENGTH="60"/>
                </td>
            </tr>
        </tbody>
    </table>
    <table class="form-table">
        <tbody>
            <tr valign="top">
                <th scope="row">
                    <span id="wp-seo-images-update-message" style="display:none"><img src="<?=plugins_url();?>/wp-seo-images/assets/img/working.gif" width="20px"/></span> 
                    <span id="id_modify_ok" style="display:none">Your changes have been saved</span>       
                </th>
            </tr>
        </tbody>
    </table>

    <p class="submit"><input type="submit" name="wp-seo-images-update-submit" id="wp-seo-images-update-submit" class="button button-primary" value="Save"/></p>

    <h3 class="title">2. Add to your .htaccess, after "RewriteBase /" line</h3>

    <table class="form-table">
        <tbody>
            <tr valign="top">
                <td>
                    <textarea rows="5" class="large-text readonly code" name="rules" id="rules">    
# WordPress SEO Images plugin
RewriteCond %{HTTP_USER_AGENT} Googlebot-Image [NC] 
RewriteRule ^(wp-content/uploads/.*)$ /wp-admin/admin-ajax.php?action=wp_seo_images_action_get&url=$1 [L]
                    </textarea>
                </td>
            </tr>
        </tbody>
    </table>


</div>