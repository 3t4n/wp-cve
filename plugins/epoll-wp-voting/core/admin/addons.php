<?php
$tab = 'installed';
if(isset($_GET['tab'])){
    $tab = $_GET['tab'];
}
if(isset($_POST['install-submit'])){
    if ( ! isset( $_POST['addon_upload_nonce'] )  || ! wp_verify_nonce( $_POST['addon_upload_nonce'], 'it_epoll_upload_addon' ) ) {?>
        <div class="error notice is-dismissible"><p><?php esc_attr_e('Please Try Again! Invalid Nonce','it_epoll');?></p></div>
   <?php
    }else{
        if(isset($_FILES['zip_file'])){
            it_epoll_install_from_local_zip('backend/addons/','it_epoll_upload_addon','addon');
        }
    }
}

?>
<div class="wrap">
    <h1><?php esc_attr_e('Add-ons','it_epoll');?> <button type="button" class="page-title-action" role="button"  onClick="jQuery('.upload-plugin').slideToggle();"><span class="upload"><?php esc_attr_e('Upload Add-on','it_epoll');?></span></button></h1>

    <div class="upload-plugin-wrap">
		<div class="upload-plugin">
            <p class="install-help"><?php esc_attr_e('If you have ePoll addon in a .zip format, you may install or update it by uploading it here.','it_epoll');?></p>
            <form method="post" enctype="multipart/form-data" class="wp-upload-form it_epoll_upload_theme_form" action="">
               <?php wp_nonce_field( 'it_epoll_upload_addon', 'addon_upload_nonce' ); ?>
                <label class="screen-reader-text" for="zip_file">Themplate's zip file</label>
                <input type="file" id="zip_file" name="zip_file" accept=".zip">
                <input type="submit" name="install-submit" id="install-plugin-submit" class="button" value="Install Now" disabled="">	
            </form>
        </div>
	</div>
    <div class="wp-filter">
        <ul class="filter-links">
            <li class="epoll_templates-installed">
                <a href="?page=epoll_addons&tab=installed"<?php if($tab== 'installed') echo esc_attr(' class=current','it_epoll');?>>
                    <?php esc_attr_e('Installed','it_epoll');?>    
                </a>
            </li>
            <li class="epoll_templates-store">
                <a href="?page=epoll_addons&tab=store"<?php if($tab== 'store') echo esc_attr(' class=current','it_epoll');?>>
                    <?php esc_attr_e('Store','it_epoll');?>
                </a>
            </li>
        </ul>
        <a target="_blank" href="<?php echo esc_url('https://infotheme.net/item/epoll-pro/','it_epoll');?>" class="button-primary button-small right" style="margin-top:10px;" role="button"><span class="upload"><?php esc_attr_e('Buy ePoll PRO','it_epoll');?></span></a>
  
    </div>
        <div class="it_epoll_admin_extensions">
            <?php  
                    if($tab == 'store'){
                        get_it_epoll_store_addons();
                    } else{
                        get_it_epoll_local_addons();
                    }
                
                ?>
        </div>
</div>