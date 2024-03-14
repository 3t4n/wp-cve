<?php
// if(isset($_GET['action'])){
// // if(isset($_GET['action']){
//     if($_GET['action'] == 'export_us_subscriber'){
//         die('expo');
//     }
// }else{


    
?>
<div class="wrap">
    <div id="icon-users" class="icon32"><br/></div>
    <h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>
    <a href="<?php echo esc_url(admin_url('/admin.php?page=ultimate-subscribe-users&action=add-new-user')) ?>" class="page-title-action"><?php esc_html_e('Add New','ultimate-subscribe') ?></a>
    <a href="<?php echo esc_url(admin_url('/admin.php?page=ultimate-subscribe-users&action=import_us_subscriber')) ?>" class="page-title-action"><?php esc_html_e('Import','ultimate-subscribe') ?></a>
    <a href="<?php echo esc_url(admin_url('/admin.php?page=ultimate-subscribe-users&action=export_us_subscriber')) ?>" class="page-title-action"><?php esc_html_e('Export','ultimate-subscribe') ?></a>
    <?php if(isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated']== true): ?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e( 'settings saved successfully', 'ultimate-subscribe' ); ?></p>
    </div>
    <?php endif; ?>
    <?php //if(isset($_REQUEST['ac'])) ?>
    <!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
    <form id="movies-filter" method="get">
        <!-- For plugins, we also need to ensure that the form posts back to our current page -->
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
        <!-- Now we can render the completed list table -->
    </form>

        <?php 

            if(isset($_GET['action']) && $_GET['action'] == 'import_us_subscriber'){
                 
                ?>
                <form id="us-import-form" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="us_import_action23">
                    <?php wp_nonce_field( 'ultimate_subscribe_import'); ?>
                    <div id="us-import-con">
                        <div class="us-import-header">
                            <h2><?php esc_html_e( 'Import Subcribers', 'ultimate-subscribe' ); ?></h2>
                        </div>
                        <div class="us-import-body">
                            <div class="import-progress-con">
                                <div class="import-progress"></div>
                            </div>
                            <div style="clear: both;"></div>
                            <div class="form-fields">
                                <div class="us-field">
                                    <label for="us_imcsv_status">Status</label>
                                    <select name="us_imcsv_status" id="us_imcsv_status">
                                        <option value="0">Not Confirmed</option>
                                        <option value="1">Confirmed</option>
                                    </select>                                    
                                </div>
                                <div class="us-field">
                                    <input name="us_imcsv_file" type="file" accept=".csv,.txt">                                    
                                    <p><?php esc_html_e( 'Select a CSV file', 'ultimate-subscribe' ); ?></p>
                                </div>
                                <div class="us-field">
                                    <button type="submit" class="button us-import-btn"><?php esc_html_e( 'Import', 'ultimate-subscribe' ); ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <?php
            }else{
                //Create an instance of our package class...
                $usListTable = new Ultimate_Subscribe_List_Table();
                //Fetch, prepare, sort, and filter our data...
                $usListTable->prepare_items();
                $usListTable->display(); 
            }
        ?>
    
</div>
<?php
// }
