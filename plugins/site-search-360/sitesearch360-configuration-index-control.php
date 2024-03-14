<?php
    $ss360_plugin = new SiteSearch360Plugin();
    $ss360_config = $ss360_plugin->getConfig();
    $ss360_acf_exists = $ss360_plugin->usesACFs();
    $ss360_acf_groups = function_exists('acf_get_field_groups') ? acf_get_field_groups() : array();
    $ss360_acf_def = get_option('ss360_acf_def');
    $ss360_updated_flag = false;
    if($ss360_acf_def == null) {
        $ss360_acf_def = array('images' => array(), 'snippets' => array(), 'texts' => array(), 'titles' => array());
    }
    $ss360_is_configured = get_option("ss360_is_configured");
    if($ss360_is_configured==null){
        if($ss360_page > 3 || ($ss360_integration_type=='filter' && $ss360_page>2)){
            $ss360_is_configured = true;
            update_option("ss360_is_configured", true);
        }
    }
    $ss360_is_indexed = get_option("ss360_is_indexed");

    if (!empty($_POST) && isset($_POST['_wpnonce']) && $_POST['action']=='ss360_updateCustomFieldsIndexing') {
        $ss360_acf_def = array('images' => array(), 'snippets' => array(), 'texts' => array(), 'titles' => array()); // reset the settings
        foreach($_POST  as $ss360_post_key => $ss360_post_value) {
            if(strpos($ss360_post_key, 'ss360acf_') === 0) {
                $ss360_parts = explode('_', $ss360_post_key);
                $ss360_acf_id = $ss360_parts[1];
                $ss360_acf_type = $ss360_parts[2];
                $ss360_target_arr = $ss360_acf_def[$ss360_acf_type];
                if($ss360_target_arr == null) {
                    $ss360_target_arr = array();
                }
                $ss360_target_arr[] = $ss360_acf_id;
                $ss360_acf_def[$ss360_acf_type] = $ss360_target_arr;
            }
        }
        update_option('ss360_acf_def', $ss360_acf_def);
        $ss360_updated_flag = true;
    }

    $ss360_custom_field_groups = array();
    foreach($ss360_acf_groups as $ss360_acf_group) {
        if(function_exists('acf_get_fields')) {
            $ss360_field_group_key = isset($ss360_acf_group['key']) ? $ss360_acf_group['key'] : $ss360_acf_group['ID'];
            $ss360_group_fields = acf_get_fields($ss360_field_group_key);
            $ss360_group_filtered = array();
            foreach($ss360_group_fields as $ss360_group) {
                if($ss360_group['type'] == 'text' || $ss360_group['type'] == 'image' || $ss360_group['type'] == 'wysiwyg') {
                    $ss360_group_filtered[] = $ss360_group;
                }
            }
            if(sizeof($ss360_group_filtered) > 0) {
                $ss360_acf_group['fields'] = $ss360_group_filtered;
                $ss360_custom_field_groups[] = $ss360_acf_group;
            }
        }
    }

    $ss360_default_caption = 'Found #COUNT# search results for "#QUERY#';
    $ss360_default_correction = 'Did you mean "#CORRECTION#"?';
    $ss360_default_emptySet = 'Sorry, we have not found any matches for your query.';

    $ss360_data_points = get_option('ss360_data_points');
    $ss360_inactive_dp = get_option('ss360_inactive_dp');
    $ss360_renamed_dp = get_option('ss360_renamed_dp');
    if($ss360_data_points==null){
        $ss360_data_points = array();
    }
    if($ss360_inactive_dp==null){
        $ss360_inactive_dp = array();
    }
    if($ss360_renamed_dp==null){
        $ss360_renamed_dp = array();
    }

    if (!empty($_POST) && isset($_POST['_wpnonce']) && $_POST['action']=='ss360_updateIndexSynchronization') {
        $ss360_updated_flag = true;
    }

    if (!empty($_POST) && isset($_POST['_wpnonce']) && $_POST['action']=='ss360_updateWoocommerce') {
        $ss360_updated_flag = true;
    }

    if (!empty($_POST) && isset($_POST['_wpnonce']) && $_POST['action']=='ss360_updateDataPoints') {
        $ss360_plugin->saveConfig($ss360_config);
        $ss360_config = $ss360_plugin->getConfig();
        $ss360_updated_flag = true;

        if(sizeof($ss360_data_points) > 0){
            $ss360_inactive_dp = array();
            $ss360_renamed_dp = array();
            foreach($ss360_data_points as $ss360_data_point){
                $ss360_key = str_replace(array('.',' ','"'), "-", $ss360_data_point);
                $ss360_active_key = $ss360_key.'_active';
                $ss360_view_name_key = $ss360_key.'_viewName';
                if((!isset($_POST[$ss360_active_key]) || $_POST[$ss360_active_key]!='on') && $_POST['action']=='ss360_updateDataPoints'){
                    $ss360_inactive_dp[] = $ss360_data_point;
                }
                if(isset($_POST[$ss360_view_name_key]) && strlen($_POST[$ss360_view_name_key]) > 0 && $_POST['action']=='ss360_updateDataPoints'){
                    $ss360_view_name = stripslashes($_POST[$ss360_view_name_key]);
                    if($ss360_view_name!=$ss360_data_point){
                        $ss360_renamed_dp[$ss360_data_point] = $ss360_view_name;
                    }
                }
            }
            if ($_POST['action']=='ss360_updateDataPoints') {
                update_option('ss360_inactive_dp', $ss360_inactive_dp);
                update_option('ss360_renamed_dp', $ss360_renamed_dp);
            }
        }

        update_option('ss360_config_modifications', ((int) get_option('ss360_config_modifications')) + 1);
    }

    $ss360_groups = isset($ss360_config['contentGroups']) ? $ss360_config['contentGroups'] : array();
    $otherContentGroupName = isset($ss360_groups['otherName']) ? $ss360_groups['otherName'] : '';


?>

<section id="ss360" class="wrap wrap--blocky flex flex--column flex--center">
    <?php
        if($ss360_updated_flag){ ?>
            <section class="wrapper wrapper--narrow bg-g message">
                <div class="block block--first flex">
                    <span><?php esc_html_e('The configuration has been saved.', 'site-search-360'); ?></span>
                    <button class="button button--close message__close" aria-label="<?php esc_html_e('Close', 'site-search-360'); ?>">&times;</button>
                </div>
            </section>
       <?php }
    ?>
    <?php include('views/sitesearch360-indexing.php') ?>
    <?php if(sizeof($ss360_data_points)>0){?>
        <section class="wrapper wrapper--narrow" style="margin-top: 40px">
            <form class="block block--first" method="post" name="ss360_basic_config" action="<?php esc_url($_SERVER['REQUEST_URI'])?>">
                <input type="hidden" name="action" value="ss360_updateDataPoints">
                <?php wp_nonce_field(); ?>
                <h2><?php esc_html_e('Data Points', 'site-search-360'); ?></h2>
                    <section>
                    <span class="m-t-1"><?php esc_html_e('Data Points enhance your search results by including additional information in form of a structured data table. Please note that any changes to the data point configuration require a re-index to be applied.', 'site-search-360'); ?></span>
                    <table class="configuration">
                        <?php foreach($ss360_data_points as $ss360_data_point){
                                $ss360_dp = htmlspecialchars($ss360_data_point);
                                $ss360_key = str_replace(array('.',' ','"'), "-", $ss360_data_point);
                                $ss360_view_name = isset($ss360_renamed_dp[$ss360_data_point]) && $ss360_renamed_dp[$ss360_data_point] != null
                                    ? $ss360_renamed_dp[$ss360_data_point] : $ss360_data_point;
                                $ss360_view_name = htmlspecialchars($ss360_view_name);
                                $ss360_is_active = !in_array($ss360_data_point, $ss360_inactive_dp);
                            ?>
                            <tr>
                                <td style="max-width:250px;width:250px;"><input type="text" class="input input--inline" placeholder="<?php echo $ss360_dp?>" value="<?php echo $ss360_view_name;?>" name="<?php echo $ss360_key . '.viewName' ?>"></td>
                                <td><label class="checkbox"><?php esc_html_e('active', 'site-search-360') ?><input class="fake-hide" type="checkbox" id="<?php echo $ss360_key . '.active' ?>" name="<?php echo $ss360_key . '.active' ?>" <?php echo $ss360_is_active ? 'checked' : ''?>/><span class="checkbox_checkmark"></span></label></td>
                                <td></td>
                            </tr>
                        <?php } ?>
                    </table>
                    </section>
                    <div class="flex flex--center w-100 m-t-1">
                        <button class="button button--padded" type="submit"><?php esc_html_e('Save', 'site-search-360'); ?></button>
                    </div>
            </form>
        </section>
    <?php } ?>
    <?php include('views/sitesearch360-index-sync.php') ?>
    <?php if($ss360_acf_exists && sizeof($ss360_custom_field_groups) > 0) { ?>
        <section class="wrapper wrapper--narrow" style="margin-top:40px">
            <form class="block block--first"  method="post" name="ss360_edit_acfs" action="<?php esc_url($_SERVER['REQUEST_URI'])?>">
                <input type="hidden" name="action" value="ss360_updateCustomFieldsIndexing">
                <h2><?php esc_html_e('Custom Fields Indexing', 'site-search-360') ?></h2>
                <p class="m-v-1"><?php esc_html_e('Here you can select which custom fields should be indexed.','site-search-360')?></p>
                <?php wp_nonce_field(); ?>
                <?php foreach($ss360_custom_field_groups as $ss360_cf_group) { ?>
                    <h3 class="m-b-0 c-b"><?php echo $ss360_cf_group['title']; ?></h3>
                    <table class="configuration">
                        <tbody>
                            <?php foreach($ss360_cf_group['fields'] as $ss360_cf) {
                                $ss360_field_id = str_replace('_', 'xxx', $ss360_cf['key'])
                                ?>
                                <tr data-slug="<?php echo $ss360_cf['name'] ?>">
                                    <td style="width:200px;"><?php echo $ss360_cf['label'] ?><br/><em>(<?php echo $ss360_cf['type'] ?>)</em></td>
                                    <td>
                                        <?php if($ss360_cf['type'] == 'image') { ?>
                                            <label class="checkbox">
                                                <?php esc_html_e('featured image', 'site-search-360') ?>
                                                <input class="fake-hide" type="checkbox" id="<?php echo $ss360_field_id ?>_image" name="ss360acf_<?php echo $ss360_field_id ?>_images" <?php echo in_array($ss360_field_id, $ss360_acf_def['images']) ? 'checked' : '' ?>/>
                                                <span class="checkbox_checkmark"></span>
                                            </label>
                                        <?php } else { ?>
                                            <label class="checkbox">
                                                <?php esc_html_e('add to content', 'site-search-360') ?>
                                                <input class="fake-hide" type="checkbox" id="<?php echo $ss360_field_id ?>_content" name="ss360acf_<?php echo $ss360_field_id ?>_texts" <?php echo in_array($ss360_field_id, $ss360_acf_def['texts']) ? 'checked' : '' ?>/>
                                                <span class="checkbox_checkmark"></span>
                                            </label>
                                            <label class="checkbox">
                                                <?php esc_html_e('search result snippet', 'site-search-360') ?>
                                                <input class="fake-hide" type="checkbox" id="<?php echo $ss360_field_id ?>_snippet" name="ss360acf_<?php echo $ss360_field_id ?>_snippets" <?php echo in_array($ss360_field_id, $ss360_acf_def['snippets']) ? 'checked' : '' ?>/>
                                                <span class="checkbox_checkmark"></span>
                                            </label>
                                            <label class="checkbox">
                                                <?php esc_html_e('title', 'site-search-360') ?>
                                                <input class="fake-hide" type="checkbox" id="<?php echo $ss360_field_id ?>_title" name="ss360acf_<?php echo $ss360_field_id ?>_titles" <?php echo in_array($ss360_field_id, $ss360_acf_def['titles']) ? 'checked' : '' ?>/>
                                                <span class="checkbox_checkmark"></span>
                                            </label>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php }
                ?>

                <div class="flex flex--center w-100 m-t-1">
                    <button id="submit-btn" class="button button--padded" type="submit"><?php esc_html_e('Save', 'site-search-360'); ?></button>
                </div>
            </form>
        </section>
    <?php } ?>
    <?php 
		if (class_exists('WooCommerce')) {
			include('views/sitesearch360-woocommerce.php');
		} ?>
</section>


<script type="text/javascript">
(function(){
    var urlRow = jQuery(".url-block");
    var navigationPositionRow = jQuery("#navigation-position-row");
    var navigationTypeRow = jQuery("#navigation-type-row");
    jQuery("#embedConfig-contentBlock").on("input", function(e){
        if(!e.target.value){
            urlRow.hide();
        }else {
            urlRow.show();
        }
    });
    var updateTypeVisibility = function(){
        var currentPosition = jQuery("input[name='navigation.position']:checked").val();
        if(currentPosition==="none"){
            navigationTypeRow.hide();
        }else {
            navigationTypeRow.show();
        }
    }
    jQuery("#results-group").on("change", function(e){
        if(e.target.checked){
            navigationPositionRow.show();
            updateTypeVisibility();
        }else {
            navigationPositionRow.hide();
            navigationTypeRow.hide();
        }
    });
    jQuery('input[name="navigation.type"]').on("change", function(e) {
        if(e.target.value === 'scroll') {
            jQuery('.tab-only').hide();
        } else {
            jQuery('.tab-only').show();
        }
    });
    jQuery("input[name='navigation.position']").on("change", updateTypeVisibility);
    jQuery(".message__close").on("click", function(e){
        jQuery(e.target).parents(".message").fadeOut();
    });
}());

</script>
<script src="<?php echo plugins_url('assets/sitesearch360_admin_scripts.js',  __FILE__)  ?>" async></script>