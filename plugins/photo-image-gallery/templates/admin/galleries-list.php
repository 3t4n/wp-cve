<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $wpdb;
$gallery_wp_nonce_add_gallery    = wp_create_nonce( 'gallery_wp_nonce_add_gallery' );

require_once(UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'video-add-html.php');



?>

<div class="wrap uxgallery_wrap">

	<?php $path_site = plugins_url( "../images", __FILE__ ); ?>
    <div class="clear"></div>
    <div id="poststuff">
        <div id="gallerys-list-page" class="image_gallery_page_heading">
            <form method="post" onkeypress="galleryImgDoNothing()" action="admin.php?page=galleries_uxgallery"   id="admin_form" name="admin_form">
                <h1><?php echo __( 'UX Galleries', 'gallery-img' ); ?>
                    <a onclick="window.location.href='admin.php?page=galleries_uxgallery&task=add_gallery&gallery_wp_nonce_add_gallery=<?php echo $gallery_wp_nonce_add_gallery; ?>'"
                       class="add-new-h2"><?php echo __( 'Add New Gallery', 'gallery-images' ); ?></a>
                </h1>

                <table class="wp-list-table widefat fixed pages" style="width:95%">
                    <thead>
                    <tr>
                        <th scope="col" id="id" style="width:10px"></th>
                        <th scope="col" id="name" style="width:40px">
                            <span><?php echo __( 'Gallery Name', 'gallery-img' ); ?></span><span
                                    class="sorting-indicator"></span></th>
                        <th scope="col" id="prod_count" style="width:50px;">
                            <span><?php echo __( 'Shortcodes', 'gallery-img' ); ?></span><span
                                    class="sorting-indicator"></span></th>
                        <th scope="col" id="prod_count" style="width:10px;">
                            <span><?php echo __( 'Images', 'gallery-img' ); ?></span><span
                                    class="sorting-indicator"></span></th>

                    </tr>
                    </thead>
                    <tbody>
					<?php
					$trcount = 1;

					for ( $i = 0; $i < count( $rows ); $i ++ ) {
						$trcount ++;
						/*	$ka0 = 0;
							$ka1 = 0;
							if ( isset( $rows[ $i - 1 ]->id ) ) {
								if ( $rows[ $i ]->sl_width == $rows[ $i - 1 ]->sl_width ) {
									$x1  = $rows[ $i ]->id;
									$x2  = $rows[ $i - 1 ]->id;
									$ka0 = 1;
								} else {
									$jj = 2;
									while ( isset( $rows[ $i - $jj ] ) ) {
										if ( $rows[ $i ]->sl_width == $rows[ $i - $jj ]->sl_width ) {
											$ka0 = 1;
											$x1  = $rows[ $i ]->id;
											$x2  = $rows[ $i - $jj ]->id;
											break;
										}
										$jj ++;
									}
								}
								if ( $ka0 ) {
									$move_up = '<span><a href="#reorder" onclick="return galleryImgListItemTask(\'' . $x1 . '\',\'' . $x2 . '\')" title="Move Up">   <img src="' . plugins_url( 'images/uparrow.png', __FILE__ ) . '" width="16" height="16" border="0" alt="Move Up"></a></span>';
								} else {
									$move_up = "";
								}
							} else {
								$move_up = "";
							}
							if ( isset( $rows[ $i + 1 ]->id ) ) {

								if ( $rows[ $i ]->sl_width == $rows[ $i + 1 ]->sl_width ) {
									$x1  = $rows[ $i ]->id;
									$x2  = $rows[ $i + 1 ]->id;
									$ka1 = 1;
								} else {
									$jj = 2;
									while ( isset( $rows[ $i + $jj ] ) ) {
										if ( $rows[ $i ]->sl_width == $rows[ $i + $jj ]->sl_width ) {
											$ka1 = 1;
											$x1  = $rows[ $i ]->id;
											$x2  = $rows[ $i + $jj ]->id;
											break;
										}
										$jj ++;
									}
								}

								if ( $ka1 ) {
									$move_down = '<span><a href="#reorder" onclick="return galleryImgListItemTask(\'' . $x1 . '\',\'' . $x2 . '\')" title="Move Down">  <img src="' . plugins_url( 'images/downarrow.png', __FILE__ ) . '" width="16" height="16" border="0" alt="Move Down"></a></span>';
								} else {
									$move_down = "";
								}


							$uncat = $rows[ $i ]->par_name;
							var_dump($uncat);
							if ( isset( $rows[ $i ]->prod_count ) ) {
								$pr_count = $rows[ $i ]->prod_count;
							} else {
								$pr_count = 0;
							}						}
	*/
						$uxgallery_nonce_remove_gallery = wp_create_nonce( 'uxgallery_nonce_remove_gallery' . $rows[ $i ]->id );
						$uxgallery_nonce_duplicate_gallery = wp_create_nonce('uxgallery_nonce_duplicate_gallery'.$rows[$i]->id);
						?>
                        <tr <?php if ( $trcount % 2 == 0 ) {
							echo 'class="has-background"';
						} ?>>
                            <td>
                                <div class="ttv_slider">
									<?php
									$post_type = 'attorneys';
									$query2 = $wpdb->prepare("SELECT `image_url` FROM `" . $wpdb->prefix . "ux_gallery_images` WHERE `gallery_id`=%s", $rows[ $i ]->id  );
									$rowim = $wpdb->get_results($query2);
									$plugin_dir_path = dirname(__FILE__);
									$rowim=  array_reverse($rowim);

									$strNum=0;
									$strList="";
									$strImage="";
									foreach ($rowim as $key => $value){


										$strImage=$value->image_url;
										if(is_array(getimagesize($strImage)) and $strNum<4) {
											$strNum++;

											$image_id   = attachment_url_to_postid( $strImage );
											if($image_id){
												$thumbnail_url = wp_get_attachment_image_src( $image_id, 'thumbnail' );
												$thumb_url = $thumbnail_url[0];
											} else {
											    $thumb_url = $strImage;
											}


											$strtr="<img src='$thumb_url' />";
											$strList.= '<a href = "admin.php?page=galleries_uxgallery&task=edit_cat&id='.$rows[ $i ]->id.'" >'.$strtr.'</a>';
										}


									}

									if($strList=="") {

										$strList='<a href = "admin.php?page=galleries_uxgallery&task=edit_cat&id='.$rows[ $i ]->id.'" ><img src="'.UXGALLERY_IMAGES_URL.'/admin_images/no-image-found.jpg" ></a>';
									}

									echo $strList;
									?>

                                </div>

                            </td>
                            <td>
                                <h3><a href="admin.php?page=galleries_uxgallery&task=edit_cat&id=<?php echo $rows[ $i ]->id ?>"><?php echo esc_html( stripslashes( $rows[ $i ]->name ) ); ?></a></h3>
                                <div class="row-actions">
                                    <span class="edit"><a href="admin.php?page=galleries_uxgallery&task=duplicate_gallery_image&id=<?php echo $rows[ $i ]->id; ?>&gallery_duplicate_nonce=<?php echo $uxgallery_nonce_duplicate_gallery; ?>" >Duplicate</a> | </span>
                                    <span class="edit"><a href="admin.php?page=galleries_uxgallery&task=edit_cat&id=<?php echo $rows[ $i ]->id; ?>">Edit</a> | </span>
                                    <span class="trash"><a href="admin.php?page=galleries_uxgallery&task=remove_gallery&id=<?php echo $rows[ $i ]->id; ?>&uxgallery_nonce_remove_gallery=<?php echo $uxgallery_nonce_remove_gallery; ?>" class="submitdelete" >Delete</a></span>
                                </div>
                            </td>
                            <td>
                                <div class="shortcode_copy_block" onclick="select()" readonly="readonly"><input value='[uxgallery id="<?php echo $rows[ $i ]->id; ?>"]'><a href="#"></a> <p class="elemcop">Shortcode Copied</p></div>

                            </td>
                            <td>
                                <div class="gal_num">(<?php echo $strNum; ?>)</div>
                            </td>


                        </tr>
					<?php } ?>
                    </tbody>
                </table>
                <input type="hidden" name="oreder_move" id="oreder_move" value=""/>
                <input type="hidden" name="asc_or_desc" id="asc_or_desc"
                       value="<?php if ( isset( $_POST['asc_or_desc'] ) ) {
					       echo esc_attr($_POST['asc_or_desc']);
				       } ?>"/>
                <input type="hidden" name="order_by" id="order_by" value="<?php if ( isset( $_POST['order_by'] ) ) {
					echo esc_attr($_POST['order_by']);
				} ?>"/>
                <input type="hidden" name="saveorder" id="saveorder" value=""/>

				<?php
				require_once(UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'video-add-html.php');
				?>


            </form>
        </div>
    </div>
</div>