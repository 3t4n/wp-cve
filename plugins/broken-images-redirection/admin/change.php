<?php
if( ! class_exists( 'BIR_change' ) ) {
    class BIR_change{

        public function __construct() {
            add_action( 'admin_menu', array($this,'broken_image_domain_options_page') );
            add_action( 'admin_enqueue_scripts', array($this,'wpdocs_selectively_enqueue_admin_script' ));

        }


        public function broken_image_domain_options_page() {
            add_submenu_page( '', 'Existing WP Docs Orders', 'Existing WP Docs Orders', 'administrator', 'broken_image_change', array($this,'broken_image_domain_options_page_html') );
        }

        public function save_data(){
            if(!isset($_POST['broken_image']) || empty($_POST['broken_image']) || !isset($_POST['new_img']) || empty($_POST['new_img'])){
                return false;
            }
            global $wpdb;
            $table_name = $wpdb->prefix . "BIR_replace_an_image";
            $media_url = wp_get_upload_dir();
            $media_url = isset($media_url['baseurl'])?$media_url['baseurl']:'';
            $broken_image = str_replace($media_url.'/','',esc_url_raw( $_POST['broken_image'] ));
            $save = $wpdb->insert($table_name, array(
                'old' => $broken_image,
                'new' => absint($_POST['new_img'])
            ),array(
                '%s',
                '%d')
            );
            $lastid = $wpdb->insert_id;
            $setCode = new BIR_setCode();
            $m = $setCode->modify_htaccess($broken_image,absint($_POST['new_img']),$lastid);

            return array('status'=>$save,'htaccess'=>$m,'dataId'=>$lastid);
        }
        public function delete_data($id){
            $id = absint($id);
            if(empty($id)){
                return false;
            }
            global $wpdb;
            $table_name = $wpdb->prefix . 'BIR_replace_an_image';
            $del = $wpdb->query(
                $wpdb->prepare(
                    "DELETE FROM $table_name WHERE id = %d",
                    $id
                )
            );
            $setCode = new BIR_setCode();
            $m = $setCode->clear_htaccess_upload($id);
            return $del;
        }
        function broken_image_domain_options_page_html(){
            if ( ! current_user_can( 'administrator' ) ) {
                return;
            }

            if ( isset( $_POST ) && !empty($_POST) ) {
                $save = $this->save_data();
            }
            if ( isset( $_GET['delete_id'] ) && !empty($_GET['delete_id']) ) {
                $delete = $this->delete_data(absint($_GET['delete_id']));
            }
            ?>
            <style>
                #wpbody-content .metabox-holder {
                    padding-top: 0px !important;
                }
                h2.h2_broken_tabs_header {
                    margin: 45px 16px 16px 7px;
                }

                a.broken_tabs_header {
                   
                    padding: 14px;
                    border: 1px solid #f0f0f1;
                    text-decoration: none;
                    text-transform: capitalize;
                }
            </style>
            <?php
            if(isset($save)){

                if(isset($save['status']) && $save['status']){
                    if(isset($save['htaccess']['status']) && !$save['htaccess']['status']) {
                    ?>
                        <div class="no_access">
                            Your .htaccess file is not writable, You need to modify the file manually.<br>
                            Please copy and past the following code in the first line of <?php esc_html(WP_CONTENT_DIR);?>/.htaccess file <br>
                            <code>
                                <?php
                                $image = wp_get_attachment_image_src(absint($_POST['new_img']));
                                $image = $image[0];

                                ?>
                                <br><br>
                                # BEGIN All_404_marker_comment_link_<?php echo absint($save['dataId']);?><br>
                                &#60;IfModule mod_rewrite.c><br>
                                RewriteEngine on<br>
                                RewriteCond %{REQUEST_FILENAME} !-f<br>
                                RewriteCond %{HTTP_HOST} !^<?php echo esc_url_raw( $_POST['broken_image'] );?>$ [NC]<br>
                                RewriteRule (.*)$ <?php echo esc_url_raw($image);?> [PT,NC,L]<br>
                                &#60;/IfModule><br>
                                # END All_404_marker_comment_link_<?php echo absint($save['dataId']);?><br>
                            </code><br><br>
                        </div>
                        <?php
                    }else{
                ?>
                <div class="updated notice">
                    <p><?php _e( 'Great! your settings are now verified & saved successfully', 'broken-image-domain' );?></p>
                </div>
                <?php
                    }
                }else{
                    ?>
                    <div class="error notice">
                        <p><?php _e( 'Oops! we have some error, please try again later', 'broken-image-domain' );?></p>
                    </div>
                        <?php
                }
            }
            if(isset($delete)){
                if($delete){
                ?>
                <div class="updated notice">
                    <p><?php _e( 'deleted data', 'broken-image-domain' );?></p>
                </div>
                <?php
                }else{
                    ?>
                    <div class="error notice">
                        <p><?php _e( 'Oops! we have some error, please try again later', 'broken-image-domain' );?></p>
                    </div>
                        <?php
                }
            }?>
			<h1>404 Image Redirection (Replace Broken Images)</h1><br />
            <h2 class="h2_broken_tabs_header">
                <a href="<?php echo esc_url(admin_url('admin.php?page=broken_image_domain'));?>"  class="broken_tabs_header"  ><?php _e( 'General Options', 'broken-image-domain' );?> </a>
                <a class="broken_tabs_header" href="<?php echo esc_url(admin_url('admin.php?page=broken_image_change'));?>" style="background: #ffffff;"> <?php _e( 'Custom Redirection', 'broken-image-domain' );?></a>
            </h2>

            <div id="">
                <div id="dashboard-widgets" class="metabox-holder">
                    <div id="" class="">
                        <div id="side-sortables" class="meta-box-sortables ui-sortable">
                            <div id="dashboard_quick_press" class="postbox"  style="border:none;">
                                <h2 class="hndle ui-sortable-handle">
									<span>
										
									</span>
                                </h2>
                                <div class="inside">

                                    <form action="#" method="post">

                                        <div class="input-text-wrap broken_img" id="title-wrap">
                                            <table class="form-table" role="presentation">
                                                <tbody>
                                                <tr class="broken_img_row">
                                                    <th scope="row">
                                                        <label for="Broken_image_URL"><?php _e( 'Broken image URL', 'broken-image-domain' ); ?>:</label>
                                                    </th>
                                                    <td>
                                                        <input type="url" class="WPLFLA_inp" value="" id="Broken_image_URL" name="broken_image">
                                                        <p class="description">
                                                           Please enter full image path
                                                        </p>
                                                    </td>
                                                </tr>
                                                <tr class="broken_img_row">
                                                    <th scope="row">
                                                        <label for="">New image :</label>
                                                    </th>
                                                    <td>
                                                        <a href="#" class="broken_image_upload_link"><span>Upload image</span></a>
                                                        <a href="#" class="broken_image_remove_link" style="display:none">Remove image</a>
                                                        <input type="hidden" class="broken_image_hidden" name="new_img" value="" >

                                                    <style>
                                                        a.broken_image_upload_link>span {
                                                            text-decoration: none;
                                                            background: #2271b1;
                                                            border-radius: 5px;
                                                            color: #fff;
                                                            padding: 7px 20px;
                                                        }

                                                        a.broken_image_remove_link {
                                                            text-decoration: none;
                                                            background: #bd0808;
                                                            border-radius: 5px;
                                                            color: #fff;
                                                            padding: 7px 20px;
                                                        }
                                                        .dataTables_wrapper .dataTables_length select{
                                                            width: 60px;
                                                        }
                                                    </style>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="submit">
                                            <?php
                                            submit_button( 'Save Settings' );
                                            ?>
                                            <br class="clear">
                                        </p>
                                    </form>
                                </div>





                                <h2 class="hndle ui-sortable-handle">
									<span>
										<span class="hide-if-no-js"><?php _e( 'Link URL', 'broken-image-domain' ); ?></span>
										<span class="hide-if-js"><?php _e( 'Link URL', 'broken-image-domain' ); ?></span>
									</span>
                                </h2>
                                <div class="inside" style="margin:30px;">
                                        <div class="input-text-wrap broken_img" id="title-wrap">
                                            <table class="form-table display" id="brocken_image" role="presentation" style="width:100%">
                                                <thead>
                                                <tr>
                                                    <th><?php _e( 'broken image URL', 'broken-image-domain' ); ?></th>
													<th style="width:40px"></th>
                                                    <th><?php _e( 'new image URL', 'broken-image-domain' ); ?></th>
                                                    
                                                    <th><?php _e( 'action', 'broken-image-domain' ); ?></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                global $wpdb;
                                                $table_name = $wpdb->prefix . "BIR_replace_an_image";
                                                $result = $wpdb->get_results("SELECT * FROM ".$table_name);
												
												$uploads_path = wp_upload_dir();
                                                foreach($result as $row){
													 if(!empty($row->new)) {
                                                        $image = wp_get_attachment_image_src($row->new);
													
                                                ?>
                                                <tr class="broken_img_row">
                                                    <td><?php echo esc_url($uploads_path['baseurl'].'/'.$row->old);?></td>
													<td><img style="height:30px; float:right" src="<?php echo esc_url($image[0]);?>"/></td>
                                                    <td>
                                                    
														
														
														<a target="_blank"  href="<?php echo esc_url($image[0]);?>">
														<?php
                                                        echo esc_url($image[0]);
                                                    }else{
                                                        _e('Oops, image not found', 'broken-image-domain');
                                                    }?><a/></td>
													
                                                    <td><a  onclick="return confirm('Are you sure?')" href="<?php echo esc_url(admin_url('admin.php?page=broken_image_change&delete_id='.absint($row->id)));?>">Delete</a></td>
                                                </tr>
                                                <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                </div>



                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <!-- The Modal -->



            <?php
        }

        function wpdocs_selectively_enqueue_admin_script( $hook ) {
            if (strpos($hook, 'broken_image_change') !== false) {
                if (!did_action('wp_enqueue_media')) {
                    wp_enqueue_media();
                }
                wp_enqueue_script( 'dataTables-min', broken_image_PLUGIN_URL . '/assets/js/jquery.dataTables.min.js', array('jquery'), '4.0' );
                wp_enqueue_script( 'broken_image_upload_img_js', broken_image_PLUGIN_URL . '/assets/js/custom.js', array('jquery'), '2.0' );
            }
        }



    }

    new BIR_change();
}
if ( ! function_exists( 'BIR_broken_image_change' ) ) {
    function BIR_broken_image_change($page)
    {
        if (isset($_GET['page']) && $_GET['page'] == 'broken_image_change') {
            wp_enqueue_style('dataTables-min', broken_image_PLUGIN_URL . '/assets/css/jquery.dataTables.min.css');
            wp_enqueue_style('broken-admin-css', broken_image_PLUGIN_URL . '/assets/css/admin-css.css?ver=5');
        }
    }

    add_action('admin_print_styles', 'BIR_broken_image_change');
}