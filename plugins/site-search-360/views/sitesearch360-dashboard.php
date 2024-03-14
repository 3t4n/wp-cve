<?php
    $ss360_plugin = new SiteSearch360Plugin();
    $ss360_client = new SiteSearch360Client();
    $ss360_is_indexed = get_option("ss360_is_indexed");
    $ss360_is_configured = get_option("ss360_is_configured");
    $ss360_page = !empty($_POST) && isset($_POST['page']) ? $_POST['page'] : 1;
    $ss360_integration_type = $ss360_plugin->getType();
	$ss360_inject_search = true;
	$ss360_is_full = $ss360_plugin->getType() == 'full';
	$ss360_config = $ss360_plugin->getConfig();
	$ss360_menus = get_registered_nav_menus();
	$ss360_update_flag = false;
	if (get_option("ss360_inject_search") === 0) {
		$ss360_inject_search = false;
	}
    if($ss360_is_configured==null){
        if($ss360_page > 3 || ($ss360_integration_type=='filter' && $ss360_page>2)){
            $ss360_is_configured = true;
            update_option("ss360_is_configured", true);
        }
    }
    $ss360_jwt = $ss360_client->presign();
	if(!isset($requestUri)) {
		$requestUri = esc_url($_SERVER['REQUEST_URI']);
	}
	if (!empty($_POST) && $_POST['action']=='ss360_updateSearchBoxSettings') {
        $ss360_selected_menus = array();
        foreach(array_keys($ss360_menus) as $ss360_menu_key) {
            if (isset($_POST['menu_'.$ss360_menu_key]) && $_POST['menu_'.$ss360_menu_key] == 'on') {
                $ss360_selected_menus[] = $ss360_menu_key;
            }
        }
        update_option('ss360_selected_menus', $ss360_selected_menus);

        $ss360_plugin->saveConfig($ss360_config);
        $ss360_update_flag = true;
        update_option('ss360_config_modifications', ((int) get_option('ss360_config_modifications')) + 1);
    }
    $searchBox_selector = isset($ss360_config['searchBox']) && isset($ss360_config['searchBox']['selector']) ?  htmlspecialchars($ss360_config['searchBox']['selector']) : '#searchBox';
    $searchBox_searchButton = isset($ss360_config['searchBox']) && isset($ss360_config['searchBox']['searchButton']) ? htmlspecialchars($ss360_config['searchBox']['searchButton']) : '';
    $searchBox_placeholder = isset($ss360_config['searchBox']) && isset($ss360_config['searchBox']['placeholder']) ? htmlspecialchars($ss360_config['searchBox']['placeholder']) : '';
    $searchBox_searchButtonLabel = isset($ss360_config['searchBox']) && isset($ss360_config['searchBox']['searchButtonLabel']) ? htmlspecialchars($ss360_config['searchBox']['searchButtonLabel']) : '';
    $ss360_selected_menus = get_option('ss360_selected_menus');
    if ($ss360_selected_menus == NULL) {
        $ss360_selected_menus = array();
    }
	$ss360_has_valid_token = $ss360_client->hasValidToken();
?>



<!-- Initial settings (integration type) + search test + personalization -->
<section id="ss360" class="wrap wrap--blocky flex flex--column flex--center">
    <?php if(!$ss360_is_indexed && $ss360_is_configured==null){ 
        ?>
        <?php include('sitesearch360-indexing.php') ?>
    <?php } else if($ss360_is_configured==null) { 
        ?>
        <div class="wrapper wrapper--narrow">
            <div class="block block--first">
                <section class="flex flex--column setup">
                    <h2 class="m-b-0--sm"><?php esc_html_e('First time configuration', 'site-search-360') ?></h2>
                    <nav role="navigation" class="setup_navigation hidden--sm">
                        <div role="menubar" class="flex">
                            <button id="start-nav-1" style="z-index:3;" role="menuitem" class="setup_navigation_item<?php echo $ss360_page==1 ?' setup_navigation_item--active' : '';?>">1. <?php esc_html_e('Integration', 'site-search-360') ?></button>
                            <button id="start-nav-2" style="z-index:2;" role="menuitem" class="setup_navigation_item<?php echo $ss360_page==2 ? ' setup_navigation_item--active' : '';?>">2. <?php esc_html_e('Search Test', 'site-search-360') ?></button>
                            <button id="start-nav-3" style="z-index:1;" role="menuitem" class="setup_navigation_item<?php echo $ss360_page==3 ? ' setup_navigation_item--active' : ($ss360_integration_type=='filter'? ' hidden' : '');?>">3. <?php esc_html_e('Personalization', 'site-search-360') ?></button>
                        </div>
                    </nav>
                    <?php if($ss360_page==1){ ?>
                        <?php include('sitesearch360-integration.php') ?>
                    <?php } else if($ss360_page==2){ 
                            ?>                        
                        <?php include('sitesearch360-searchtest.php') ?>
                    <?php } else if($ss360_page==3){ 
                        ?>
                        <?php include('sitesearch360-personalization.php') ?>
                    <?php } ?>
                    <span class="flex w-100 flex--center hidden--lg m-t-1 page-info"><?php echo sprintf(esc_html__('Step %d of %d','site-search-360'), $ss360_page, $ss360_integration_type!='filter' ? 3 : 2) ?></span>
                </section>
            </div>
        </div>
    <?php }else { 
        ?>
        <div class="wrapper wrapper--narrow">
            <div class="block block--first flex flex--center flex--column">
                <h1><a href="https://sitesearch360.com" target="_blank"><img aria-label="Site Search 360" src="<?php echo plugins_url('images/logo.svg',  dirname(__FILE__))?>"></a></h1>
                <p class="m-b-0"><?php esc_html_e('Logged in as', 'site-search-360')?> <strong><?php echo $ss360_plugin->getSiteId(); ?></strong>
				<?php if (!$ss360_has_valid_token) { ?>
					<p class="alert alert--error"><?php esc_html_e('Your authentication has expired, please log out and log in again to make sure all features are available.', 'site-search-360')?></p>
				<?php } ?>
                <?php include('sitesearch360-logout.php') ?>
            </div>
        </div>
        <?php include('sitesearch360-rating-cta.php') ?>
        <?php include('sitesearch360-stats.php') ?>
        <?php include('sitesearch360-plan.php') ?>
        <?php include('sitesearch360-control-panel.php') ?>
        <div class="wrapper wrapper--narrow">
            <div class="block block--first">
                <?php include('sitesearch360-integration.php') ?>
            </div>
        </div>
		<?php if($ss360_integration_type != 'filter') { ?>
		<div class="wrapper wrapper--narrow">
			<div class="block block--first">
				<h2><?php esc_html_e('Search Plugin Settings', 'site-search-360') ?></h2>
				<form id="inject-search" name="ss360_disable_plugin" method="post" action="<?php echo $requestUri; ?>" >
					<?php wp_nonce_field(); ?>
   					<input type="hidden" name="action" value="ss360_enablePlugin">
					<label class="checkbox">
						<?php esc_html_e('Go live with Site Search 360', 'site-search-360') ?>
						<input class="fake-hide" type="checkbox" id="ss360_enable_plugin_input" name="ss360Enabled" <?php echo $ss360_inject_search ? 'checked' : ''?>/>
						<span class="checkbox_checkmark"></span>
					</label>
					<div class="flex flex--center w-100 m-t-1">
						<button class="button button--padded" type="submit"><?php esc_html_e('Save', 'site-search-360'); ?></button>
					</div>
				</form>
			</div>
		</div>
		<?php } ?>
		<div class="wrapper wrapper--narrow">
            <div class="block block--first">
                <?php
                    if($ss360_update_flag){ ?>
                        <section class="wrapper wrapper--narrow bg-g message">
                            <div class="block block--first flex">
                                <span><?php esc_html_e('The configuration has been saved.', 'site-search-360'); ?></span>
                                <button class="button button--close message__close" aria-label="<?php esc_html_e('Close', 'site-search-360'); ?>">&times;</button>
                            </div>
                        </section>
                   <?php } 
                ?>
				<?php if (!empty($ss360_menus)) { ?>
                <section>
                    <form id="inject-search" name="ss360_save_search_box_settings" method="post" action="<?php echo $requestUri; ?>" >
                        <h3 class="m-b-0 c-b"><?php esc_html_e('Add Search Box', 'site-search-360') ?></h3>
                        <input type="hidden" name="action" value="ss360_updateSearchBoxSettings">
                        <table class="configuration">
                            <tbody>
                                <tr>
                                    <td style="vertical-align: top; padding-top: 8px;"><strong><?php esc_html_e('Add Search Box to Menu', 'site-search-360') ?></strong></td>
                                    <td>
                                    <?php foreach(array_keys($ss360_menus) as $ss360_menu) { ?>
                                        <label class="checkbox p-b-0-5 p-r-0-5"><?php esc_html_e($ss360_menus[$ss360_menu]) ?><input class="fake-hide" type="checkbox" name="menu.<?php echo $ss360_menu?>" <?php echo in_array($ss360_menu, $ss360_selected_menus) ? 'checked' : ''?>><span class="checkbox_checkmark"></span></label>
                                    <?php } ?>
                                    </td>
                                    <td style="vertical-align: top; padding-top: 8px;"><?php esc_html_e('Site Search 360 custom search form will be added to the selected menus.', 'site-search-360'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="flex flex--center w-100 m-t-1">
                            <button class="button button--padded" type="submit"><?php esc_html_e('Save', 'site-search-360'); ?></button>
                        </div>
                    </form>
                </section>
				<?php } ?>
            </div>
        </div>
        <?php include('sitesearch360-contact.php') ?>
    <?php } ?>
</section>
<script type="text/javascript">
    jQuery(".message__close").on("click", function(e){
        jQuery(e.target).parents(".message").fadeOut();
    });
</script>
<script src="<?php echo plugins_url('assets/sitesearch360_admin_scripts.js',  dirname(__FILE__))  ?>" async></script>