<?php

if(!function_exists('contest_gal1ery_entry_on_off')){

    function contest_gal1ery_entry_on_off($atts){

        // PLUGIN VERSION CHECK HERE

        contest_gal1ery_db_check();

        if(is_admin()){
            return '';
        }

        $shortcode_name = 'cg_entry_on_off';

        extract( shortcode_atts( array(
            'id' => ''
        ), $atts ) );
        $GalleryID = trim($atts['id']);

        // PLUGIN VERSION CHECK HERE --- END
        wp_enqueue_style( 'cg_v10_css_cg_gallery', plugins_url('/../v10/v10-css-min/cg_gallery.min.css', __FILE__), false, cg_get_version_for_scripts() );
        wp_enqueue_script( 'cg_v10_js_cg_gallery', plugins_url( '/../v10/v10-js-min/cg_gallery.min.js', __FILE__ ), array('jquery'), cg_get_version_for_scripts());
        wp_localize_script( 'cg_v10_js_cg_gallery', 'post_cg_pro_version_info_recognized_wordpress_ajax_script_function_name', array(
            'cg_pro_version_info_recognized_ajax_url' => admin_url( 'admin-ajax.php' )
        ));
        wp_localize_script( 'cg_v10_js_cg_gallery', 'post_cg_login_wordpress_ajax_script_function_name', array(
            'cg_login_ajax_url' => admin_url( 'admin-ajax.php' )
        ));

        global $wpdb;

        $tablename_options_visual = $wpdb->prefix . "contest_gal1ery_options_visual";
        $tablename = $wpdb->base_prefix . "contest_gal1ery";
        $tablename_options = $wpdb->base_prefix . "contest_gal1ery_options";
        $table_posts = $wpdb->prefix."posts";

        $optionsVisual = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_options_visual WHERE GalleryID = %d"  ,[$GalleryID]));
        $FeControlsStyleUpload = $optionsVisual->FeControlsStyleUpload;
        $BorderRadiusUpload = $optionsVisual->BorderRadiusUpload;

        $options = $wpdb->get_row($wpdb->prepare( "SELECT * FROM $tablename_options WHERE id = %d"  ,[$GalleryID]));
        $galleryDBversion = $options->Version;

        $BorderRadiusClass = '';
        $cgFeControlsStyle='cg_fe_controls_style_white';
        if($FeControlsStyleUpload=='black'){
            $cgFeControlsStyle='cg_fe_controls_style_black';
        }
        if($BorderRadiusUpload=='1'){
            $BorderRadiusClass = 'cg_border_radius_controls_and_containers';
        }

        $wp_upload_dir = wp_upload_dir();
        $optionsFile = $wp_upload_dir['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/json/'.$GalleryID.'-options.json';

        $options = json_decode(file_get_contents($optionsFile),true);

        $isProVersion = false;
        $plugin_dir_path = plugin_dir_path(__FILE__);
        if(is_dir ($plugin_dir_path.'/../../contest-gallery-pro') && strpos(cg_get_version_for_scripts(),'-PRO')!==false){
            $isProVersion = true;
        }

        if(!$isProVersion){

            ob_start();

            echo "<p><strong>Only available in PRO version</strong></p>";

            $contest_gal1ery_cg_entry_on_off = ob_get_clean();

            return $contest_gal1ery_cg_entry_on_off;
        }

        if((!empty($_GET['cg_on_id']) || !empty($_GET['cg_off_id'])) && !empty($_GET['cg_hash'])){

            $isActivate = false;
            $isDeactivate = false;

            if(!empty($_GET['cg_on_id'])){
                $isActivate = true;
                $id = absint($_GET['cg_on_id']);
            }else if(!empty($_GET['cg_off_id'])){
                $isDeactivate = true;
                $id = absint($_GET['cg_off_id']);
            }

            ob_start();

            $cg_hash = sanitize_text_field($_GET['cg_hash']);
            $cg_hash_to_compare = cg_hash_function('---cngl1---'.$id);

            if($cg_hash!=$cg_hash_to_compare){

                echo '<div class="cg_border_radius_controls_and_containers '.$cgFeControlsStyle.' '.$BorderRadiusClass.' mainCGdivUploadForm mainCGdivUploadFormStatic" data-cg-gid="'.$GalleryID.'">';

                    echo "<p>Hash compare for cg_entry_on_off id=\"$GalleryID\" shortcode not successful</p>";

                echo '</div>';

            }else{

                // update main table
                $wpdb->update(
                    "$tablename",
                    array('Active' => 1),
                    array('id' => $id),
                    array('%d'),
                    array('%d')
                );

                $objectRow = $wpdb->get_row( "SELECT DISTINCT $table_posts.*, $tablename.* FROM $table_posts, $tablename WHERE 
                                              ($tablename.GalleryID='$GalleryID' AND $tablename.id=$id and $table_posts.ID = $tablename.WpUpload)  OR 
                                              ($tablename.GalleryID='$GalleryID' AND $tablename.id=$id AND $tablename.WpUpload = 0) 
                                          GROUP BY $tablename.id ORDER BY $tablename.id DESC");

                if(empty($objectRow)){
                    echo '<div class="cg_border_radius_controls_and_containers '.$cgFeControlsStyle.' '.$BorderRadiusClass.' mainCGdivUploadForm mainCGdivUploadFormStatic" data-cg-gid="'.$GalleryID.'">';
                        echo "<p>ID not found. Entry must be deleted for cg_entry_on_off id=\"$GalleryID\" shortcode.</p>";
                    echo '</div>';
                }else{

                    echo '<div class="cg_border_radius_controls_and_containers '.$cgFeControlsStyle.' '.$BorderRadiusClass.' mainCGdivUploadForm mainCGdivUploadFormStatic" data-cg-gid="'.$GalleryID.'">';

                    if($isActivate){

                        $WpPage = $objectRow->WpPage;
                        $WpPageUser = $objectRow->WpPageUser;
                        $WpPageNoVoting = $objectRow->WpPageNoVoting;
                        $WpPageWinner = $objectRow->WpPageWinner;

                        $uploadFolder = wp_upload_dir();
                        $thumbSizesWp = array();
                        $thumbSizesWp['thumbnail_size_w'] = get_option("thumbnail_size_w");
                        $thumbSizesWp['medium_size_w'] = get_option("medium_size_w");
                        $thumbSizesWp['large_size_w'] = get_option("large_size_w");
                        $imageArray = array();

                        $imageArray = cg_create_json_files_when_activating($GalleryID,$objectRow,$thumbSizesWp,$uploadFolder,$imageArray);

                        // take care of order!
                        //cg_set_data_in_images_files_with_all_data($GalleryID,$imageArray);
                        //cg_json_upload_form_info_data_files($GalleryID,null);
                        cg_json_upload_form_info_data_files_new($GalleryID);

                        echo "<div class='cg_entry_on_off_link_header'>";
                            echo "<p>Entry ID $id successful activated</p>";
                        echo '</div>';

                        echo "<div class='cg_entry_on_off_link_container'>";

                        if(get_post_status( $WpPage ) == 'trash'){
                            echo "<div class='cg_entry_on_off_link'>";
                                echo "<a href='".get_bloginfo('wpurl') . "/wp-admin/edit.php?post_status=trash&post_type=contest-gallery' target='_blank' class='cg_entry_page_url'>";
                                echo "cg_gallery <b>moved to trash</b> - can be restored";
                                echo "</a>";
                            echo "</div>";
                        }else{
                            $permalink = get_permalink($WpPage);
                            if($permalink===false){
                                echo "<div class='cg_entry_on_off_link'>";
                                    echo "<a href='#' target='_blank' class='cg_entry_page_url cg_disabled_background_color_e0e0e0'>";
                                    echo "cg_gallery <b>deleted</b> - can be corrected in \"Edit options\" >>> \"Status, repair...\"";
                                    echo "</a>";
                                echo "</div>";
                            }else{
                                echo "<div class='cg_entry_on_off_link'>";
                                    echo "<a href='".$permalink."' target='_blank' class='cg_entry_page_url'>";
                                    echo "cg_gallery";
                                    echo "</a>";
                                echo "</div>";
                            }
                        }
                        if(get_post_status( $WpPageUser ) == 'trash'){
                            echo "<div class='cg_entry_on_off_link'>";
                                echo "<a href='".get_bloginfo('wpurl') . "/wp-admin/edit.php?post_status=trash&post_type=contest-gallery' target='_blank' class='cg_entry_page_url'>";
                                echo "cg_gallery_user <b>moved to trash</b> - can be restored";
                                echo "</a>";
                            echo "</div>";
                        }else{
                            $permalink = get_permalink($WpPageUser);
                            if($permalink===false){
                                echo "<div class='cg_entry_on_off_link'>";
                                    echo "<a href='#' target='_blank' class='cg_entry_page_url cg_disabled_background_color_e0e0e0'>";
                                    echo "cg_gallery_user <b>deleted</b> - can be corrected in \"Edit options\" >>> \"Status, repair...\"";
                                    echo "</a>";
                                echo "</div>";
                            }else{
                                echo "<div class='cg_entry_on_off_link'>";
                                    echo "<a href='".$permalink."' target='_blank' class='cg_entry_page_url'>";
                                    echo "cg_gallery_user";
                                    echo "</a>";
                                echo "</div>";
                            }
                        }
                        if(get_post_status( $WpPageNoVoting ) == 'trash'){
                            echo "<div class='cg_entry_on_off_link'>";
                                echo "<a href='".get_bloginfo('wpurl') . "/wp-admin/edit.php?post_status=trash&post_type=contest-gallery' target='_blank' class='cg_entry_page_url'>";
                                echo "cg_gallery_no_voting <b>moved to trash</b> - can be restored";
                                echo "</a>";
                            echo "</div>";
                        }else{
                            $permalink = get_permalink($WpPageNoVoting);
                            if($permalink===false){
                                echo "<div class='cg_entry_on_off_link'>";
                                        echo "<a href='#' target='_blank' class='cg_entry_page_url cg_disabled_background_color_e0e0e0'>";
                                        echo "cg_gallery_no_voting <b>deleted</b> - can be corrected in \"Edit options\" >>> \"Status, repair...\"";
                                        echo "</a>";
                                echo "</div>";
                            }else{
                                echo "<div class='cg_entry_on_off_link'>";
                                    echo "<a href='".$permalink."' target='_blank' class='cg_entry_page_url'>";
                                    echo "cg_gallery_no_voting";
                                    echo "</a>";
                                echo "</div>";
                            }
                        }
                        if(get_post_status( $WpPageWinner ) == 'trash'){
                            echo "<div class='cg_entry_on_off_link'>";
                                echo "<a href='".get_bloginfo('wpurl') . "/wp-admin/edit.php?post_status=trash&post_type=contest-gallery' target='_blank' class='cg_entry_page_url'>";
                                echo "cg_gallery_winner <b>moved to trash</b> - can be restored";
                                echo "</a>";
                            echo "</div>";
                        }else{
                            $permalink = get_permalink($WpPageWinner);
                            if($permalink===false){
                                echo "<div class='cg_entry_on_off_link'>";
                                    echo "<a href='#' target='_blank' class='cg_entry_page_url cg_disabled_background_color_e0e0e0'>";
                                    echo "cg_gallery_winner <b>deleted</b> - can be corrected in \"Edit options\" >>> \"Status, repair...\"";
                                    echo "</a>";
                                echo "</div>";
                            }else{
                                echo "<div class='cg_entry_on_off_link'>";
                                    echo "<a href='".$permalink."' target='_blank' class='cg_entry_page_url'>";
                                    echo "cg_gallery_winner";
                                    echo "</a>";
                                echo "</div>";
                            }
                        }

                        echo '</div>';

                    }

                    if($isDeactivate){

                        cg_deactivate_images($GalleryID,$wp_upload_dir,[$id => $id]);

                        $imagesFile = $wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images.json";
                        if(file_exists($imagesFile)){
                            $imagesFileContent = json_decode(file_get_contents($imagesFile),true);
                            if(!empty($imagesFileContent) && isset($imagesFileContent[$id])){
                                unset($imagesFileContent[$id]);
                                file_put_contents($imagesFile,json_encode($imagesFileContent));
                            }
                        }
                        $imagesInfoValuesFile = $wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-info-values.json";
                        if(file_exists($imagesInfoValuesFile)){
                            $imagesInfoValuesFileContent = json_decode(file_get_contents($imagesInfoValuesFile),true);
                            if(!empty($imagesInfoValuesFileContent) && isset($imagesInfoValuesFileContent[$id])){
                                unset($imagesInfoValuesFileContent[$id]);
                                file_put_contents($imagesInfoValuesFile,json_encode($imagesInfoValuesFileContent));
                            }
                        }
                        $imagesSortValuesFile = $wp_upload_dir['basedir']."/contest-gallery/gallery-id-".$GalleryID."/json/".$GalleryID."-images-sort-values.json";
                        if(file_exists($imagesSortValuesFile)){
                            $imagesSortValuesFileContent = json_decode(file_get_contents($imagesSortValuesFile),true);
                            if(!empty($imagesSortValuesFileContent) && isset($imagesSortValuesFileContent[$id])){
                                unset($imagesSortValuesFileContent[$id]);
                                file_put_contents($imagesSortValuesFile,json_encode($imagesSortValuesFileContent));
                            }
                        }

                        echo "<div class='cg_entry_on_off_link_header'>";
                        echo "<p>Entry ID $id successful deactivated</p>";
                        echo '</div>';

                    }

                    echo '</div>';

                }

            }



            $contest_gal1ery_cg_entry_on_off = ob_get_clean();

            //apply_filters( 'cg_filter_users_login', $contest_gal1ery_users_login );

            return $contest_gal1ery_cg_entry_on_off;
        }else{

            ob_start();

            echo '<div class="cg_border_radius_controls_and_containers '.$cgFeControlsStyle.' '.$BorderRadiusClass.' mainCGdivUploadForm mainCGdivUploadFormStatic" data-cg-gid="'.$GalleryID.'">';
                echo "<p>No parameters from email forwarded for cg_entry_on_off id=\"$GalleryID\" shortcode</p>";
            echo '</div>';


            $contest_gal1ery_cg_entry_on_off = ob_get_clean();

            return $contest_gal1ery_cg_entry_on_off;

        }

    }

}

?>