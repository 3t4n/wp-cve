<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $wpdb;
$id = "";

$gallery_wp_nonce_add_album = wp_create_nonce('gallery_wp_nonce_add_album');
$gallery_wp_nonce_delete_gallery_from_album = wp_create_nonce('gallery_wp_nonce_delete_gallery_from_album');
if (isset($_GET['id']) && $_GET['id'] != '') {
    $id = intval($_GET['id']);

    if ($id == 0) {
        wp_die("undefined ID");
    }
}

//$query = $wpdb->prepare("SELECT `cover_image` FROM " . $wpdb->prefix . "ux_gallery_album_has_gallery WHERE `id_album`=%s" ,  $id);
//$album_image = $wpdb->get_row($query)->cover_image;

?>
<div class="wrap uxgallery_wrap">
    <?php $path_site = plugins_url("../images", __FILE__);
    $save_data_nonce = wp_create_nonce('uxgallery_nonce_save_data' . $id);
    $add_video_nonce = wp_create_nonce('gallery_add_video_nonce' . $id);
    ?>
    <div class="clear"></div>
    <div id="poststuff">
    <form action="admin.php?page=galleries_ux_albums&id=<?php echo $album_row->id; ?>&save_data_nonce=<?php echo $save_data_nonce; ?>"
          method="post"
          name="adminForm" id="adminForm">
            <input type="hidden" class="changedvalues" value="" name="changedvalues" size="80">

            <div id="post-body" class="metabox-holder columns-2">
                <!-- Content -->
                <div id="post-body-content">
                    <div class="image_gallery_page_heading">
                        <h1>Edit Album</h1>
                        <a class="add-new-h2 free_notice_button"><?php echo __('Add Album', 'photo-gallery-wp'); ?></a>
                        <input class="name_field" onkeyup="name_changeTop(this)"
                               type="text"
                               name="album_name"
                               id="name"
                               maxlength="250"
                               value="<?php echo esc_html(stripslashes($album_row->name)); ?>" />

                    </div>
                    <?php add_thickbox(); ?>
                    <div id="post-body">

                    <?php
                    if (count($all_galleries)) { ?>
                        <div class="postbox">
                            <h3 class="hndle"><?php echo __('Select Galleries to add in Album', 'gallery-images'); ?></h3>
                            <ul id="album_all_galleries_list">
                                <?php
                                $strnull=0;
                                foreach ($all_galleries as $val) {
	                                $strnull++;
                                    ?>

                                    <li>
                                        <div class="add_gallery_to_album_block">
                                            <div class="image_block">
                                                <div class="ttv_slider">
		                                            <?php

		                                            $query2 = $wpdb->prepare("SELECT `image_url` FROM `" . $wpdb->prefix . "ux_gallery_images` WHERE `gallery_id`=%s",$val->id );
		                                            $rowim = $wpdb->get_results($query2);
		                                            $plugin_dir_path = dirname(__FILE__);
		                                            $rowim=  array_reverse($rowim);

		                                            $strNum=0;
		                                            $strList="";
		                                            $strImage="";
		                                            foreach ($rowim as $key => $value){

			                                            /*Images List and count*/
			                                            $strImage=$value->image_url;
			                                            if(is_array(getimagesize($strImage)) and $strNum<4) {
				                                            $strNum++;

				                                            $image_id   = attachment_url_to_postid( $strImage );
				                                            $thumbnail_url = wp_get_attachment_image_src( $image_id, 'thumbnail' );
				                                            $strtr="<img src='$thumbnail_url[0]' />";
				                                            $strList.= '<a href = "" >'.$strtr.'</a>';
			                                            }

			                                            //if($strNum > 4) break;
		                                            }

		                                            if($strList=="") {
			                                            /*placeholder*/
			                                            $strList='<a href = "" ><img src="'.UXGALLERY_IMAGES_URL.'/admin_images/no-image-found.jpg" ></a>';
		                                            }

		                                            echo $strList;
		                                            ?>
                                                </div>
                                            </div>
                                            <div class="info_block">
                                                <span><?php echo $val->name; ?></span>
                                            </div>
                                            <div class="add_triger">
                                                <label for="unplugged-<?= $val->id ?>">
                                                    <input id="unplugged-<?= $val->id ?>" type="checkbox" name="unplugged[]" value="<?= $val->id ?>">
                                                </label>
                                            </div>
                                        </div>
                                    </li>

                                    <?php
                                }
                                ?>

	                            <?php if($strnull==0){?>
                                    <li class="no_gallery_placeholder">
                                        <p>No Gallery found. Create your first gallery to add into album.</p>
                                        <a onclick="window.location.href='admin.php?page=galleries_uxgallery&amp;task=add_gallery&amp;gallery_wp_nonce_add_gallery=bbe48f4fe7'" class="add-new-h2">Crate First Gallery</a>
                                    </li>
	                            <?php }?>
                            </ul>
                               <input type="button" onclick="albumImgSubmitButton('apply')"
                               value="Add selected galleries" disabled
                               id="add_galleries_button" class="button button-primary button-large">
                        </div>
                    <?php } ?>
                        <div class="postbox">
                            <h3 class="hndle albums_list"> <?php echo __('Album Items', 'gallery-images'); ?>

                                <label class="select_album_template" for="image_gallery_wp_album_style"><?= __('Select Album Template:', 'uxgallery') ?>
                                <select name="image_gallery_wp_album_style" id="image_gallery_wp_album_style">
                                    <option value="1" <?php if ($album_row->photo_gallery_wp_album_style == 1) echo 'selected="selected"' ?>>
                                        Popup Grid
                                    </option>
                                    <option value="2" <?php if ($album_row->photo_gallery_wp_album_style == 2) echo 'selected="selected"' ?>>
                                        Masonry
                                    </option>
                                    <option value="3" <?php if ($album_row->photo_gallery_wp_album_style == 3) echo 'selected="selected"' ?>>
                                        Lightbox Grid
                                    </option>
                                </select>
                                </label>
                            </h3>
                            <ul id="album_selected_galleries_list" class="ui-sortable  images_list_sortable">
                                <?php
                                $i = 2;
                                $strnll=0;
                                foreach ($row_galleries as $key => $val) {
	                                $strnll++;
                                    if ($val->sl_type == "video") {
                                        $videourl = uxgallery_get_video_id_from_url($val->img_url);
                                        if ($videourl[1] == 'youtube') {
                                            $thumb = "https://img.youtube.com/vi/" . esc_html($videourl[0]) . "/mqdefault.jpg";
                                        } else {
                                            $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                                            $imgsrc = $hash[0]['thumbnail_large'];
                                            $thumb = esc_attr($imgsrc);
                                        }
                                    } else {
                                        $thumb = esc_attr($val->img_url);
                                    }
                                    $val->img_url = $thumb;

                                    ?>

                                    <li id="order_elem_<?php echo $val->id ; ?>" class="album_gall">
                                        <input class="order_by" type="hidden"
                                               name="order_by_<?php echo $val->id; ?>"
                                               value="<?php echo $val->ordering; ?>"/>
                                        <div class="image-container">
                                           <!--
                                            <div class="list-img-wrapper">
                                                <img src="<?php if ($val->cover_image != "") echo $val->cover_image; else echo $val->img_url ?>"/>
                                            </div>
                                            -->


                                            <div class="ttv_slider">
		                                        <?php

		                                        $query2 = $wpdb->prepare("SELECT `image_url` FROM `" . $wpdb->prefix . "ux_gallery_images` WHERE `gallery_id`=%s", $val->id );
		                                        $rowim = $wpdb->get_results($query2);
		                                        $plugin_dir_path = dirname(__FILE__);
		                                        $rowim=  array_reverse($rowim);

		                                        $strNum=0;
		                                        $strList="";
		                                        $strImage="";
		                                        foreach ($rowim as $key => $value){

			                                        /*Images List and count*/
			                                        $strImage=$value->image_url;
			                                        if(is_array(getimagesize($strImage)) and $strNum<4) {
				                                        $strNum++;
				                                        $strtr="<img src='$strImage' />";
				                                        $strList.= '<a href = "" >'.$strtr.'</a>';
			                                        }

			                                        //if($strNum > 4) break;
		                                        }

		                                        if($strList=="") {
			                                        /*placeholder*/
			                                        $strList='<a href = "" ><img src="'.UXGALLERY_IMAGES_URL.'/admin_images/no-image-found.jpg" ></a>';
		                                        }

		                                        echo $strList;
		                                        ?>
                                            </div>
                                            <div>
                                                <span class="wp-media-buttons-icon"></span>
                                            </div>

                                        </div>
                                        <div class="image-options">
                                            <h3 style="">
                                                <?= $val->name ?>
                                            </h3>

                                            <?php if (!empty($categories)) { ?>
                                                <div class="album_categories_list">
                                                    <strong><?php echo __('Belongs to Category:', 'gallery-images'); ?></strong>
                                                    <ul class="gallery_cat_list">
                                                        <?php
                                                        if (!empty($categories)) {
                                                            foreach ($categories as $key => $value) {
                                                                $checked = (in_array($value->id, $val->category_arr)) ? "checked" : "";
                                                                ?>
                                                                <li>
                                                                    <label>
                                                                    <input type="checkbox"  <?php echo $checked; ?>
                                                                           name="gallery_cat[<?php echo $val->id_gallery; ?>][]"
                                                                           value="<?php echo ++$key; ?>"

                                                                           id="gallery_cat_<?php echo $val->id_gallery; ?>_<?php echo $value->id; ?>">
                                                                    <?php echo $value->name; ?>
                                                                    </label>
                                                                </li>
                                                                <?php
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="actions">
                                        <div class="remove-image-container">
                                            <a href="?page=galleries_ux_albums&task=delete_gallery&gallery_id=<?= $val->id ?>&id=<?= $album_row->id ?>&gallery_nonce_remove_gallery_from_album=<?= $gallery_wp_nonce_delete_gallery_from_album ?>"
                                               class="remove-image" >x</a>
                                        </div>
                                        <div class="edit-image-container">
                                            <a  href="?page=galleries_uxgallery&ref=albums&task=edit_cat&album_id=<?= $id ?>&id=<?= $val->id ?>"
                                                class="edit-image"
                                                target="_blank"
                                                id="remove_image<?php echo $val->id; ?>"
                                            >e</a>
                                        </div>
                                        </div>
                                        <div class="clear"></div>
                                    </li>
                                <?php } ?>

                                <?php if($strnll==0){?>
                                    <li class="no_album_placeholder">No Gallery added to Album. Select galleries from the list above and click on Add button.</li>
                                <?php }?>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- SIDEBAR -->



                <div id="postbox-container-1" class="update_gallery_wrapper postbox-container">

                    <div id="side-sortables" class="meta-box-sortables ui-sortable">
                        <div id="gallery-shortcode-box" class="postbox shortcode ms-toggle">
                            <h3 class="hndle"><span><?php echo __('Save & Publish', 'uxgallery'); ?></span></h3>

                            <div class="inside">
                                <div id="">
                                    <div id="update_block">
                                        <a type="button" onclick=""
                                               value="Update Album"
                                           id="save-buttom" class="free_notice_button button button-primary button-large">Update Album</a>
					                    <?php if (isset($_GET["ref"]) || isset($_POST["album_mode"])) { ?>
                                            <input type="hidden" name="" value="<?php echo esc_html(stripslashes($album_row->name)); ?>">
					                    <?php } ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <p><?php echo __('Copy and paste the blue color shortcode in your page/post editor, grey collor addition can be used for PHP integration.'); ?></p>
                                <pre  class="shortcode_block">&lt;?php echo do_shortcode('&emsp;<div class="active">[uxgallery_album id="<?php echo $row->id; ?>"]</div>'); ?&gt;</pre>
                            </div>
                        </div>



                       <!-- <div id="gallery-unique-options" class="postbox">
                            <h3 class="hndle">
                                <span><?php echo __('Album Custom Options', 'uxgallery'); ?></span>
                            </h3>
                            <ul id="gallery-unique-options-list">
                                <?php //ns code start ?>

                                <li>

                                </li>

                                <div id="major-publishing-actions" style="background-color: #ffffff;">
                                    <div id="publishing-action">
                                        <input type="button" onclick="albumImgSubmitButton('apply')"
                                               value="Save Album"
                                               id="save-buttom" class="button button-primary button-large">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                        </div>-->


                        <div class="postbox">
                            <h3 class="hndle"><span><?php echo __('Categories', 'portfolio-gallery'); ?></span>
                            </h3>
                            <div class="inside ">
                                <ul class="album_cat_list">
                                    <?php
                                    $cat_array = array();
                                    if (isset($album_row->category) && $album_row->category != "") {
                                        $cat_array = explode(",", $album_row->category);
                                    }
                                    if (!empty($categories)) {
                                        foreach ($categories as $key => $value) {
                                            ?>
                                            <li>

                                                <input class="del_val" name="cat_names[]"
                                                       value="<?php echo esc_attr(str_replace("_", " ", $value->name)); ?>"
                                                >
                                                <span class="delete_cat"><img
                                                            src="<?php echo UXGALLERY_IMAGES_URL . "/admin_images/remove.jpg"; ?>"
                                                            width="9" height="9" value="a"></span>
                                            </li>
                                            <?php
                                        }
                                    }
                                    ?>
                                </ul>
                                <hr>
                                <p><?= __("Add new category", "uxgallery") ?></p>
                                <input type="text" id="new_cat" name="new_category">
                                <a href="#"
                                   id="add_new_cat">+ <?= __("Add", "uxgallery") ?></a>
                            </div>
                        </div>
                        <div class="postbox free_sidebar">
                            <h2>
                                Get</br>
                                <strong>Pro Version</strong></br>
                                for more functionality
                            </h2>
                            <a href="https://uxgallery.net/pricing/" target="_blank">Get Now!</a>

                            <ul class="features">
                                <li>Video Gallery</li>
                                <li>Albums Functionality</li>
                                <li>Template Editor</li>
                                <li>Lightbox Editor</li>
                                <li>Email Support</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        <?php wp_nonce_field('uxgallery_nonce_save_album', 'uxgallery_nonce_save_album') ?>
        <input type="hidden" name="task" value=""/>
    </form>
    </div>
</div>


<script type="text/javascript">

    jQuery(document).ready(function ($) {


        jQuery("#album_all_galleries_list li").click(function(){
            if(jQuery(this).find('input[type="checkbox"]').is(":checked")){
                jQuery(this).find('input[type="checkbox"]').prop("checked",false);
                jQuery(this).removeClass("checked");
            }
            else {
                jQuery(this).find('input[type="checkbox"]').attr("checked","checked");
                jQuery(this).addClass("checked");
            }
            var active_checkboxes = $("input[name='unplugged[]']:checked").length;
            if (active_checkboxes > 0) {
                jQuery("#add_galleries_button").removeAttr("disabled");
            }
            else {
                jQuery("#add_galleries_button").attr("disabled", true);
            }
        });



        $("input[name='unplugged[]']").change(function () {
            if(jQuery(this).is(":checked")){
                jQuery(this).prop("checked",false);
                jQuery(this).parents("li").removeClass("checked");
            }
            else {
                jQuery(this).attr("checked","checked");
                jQuery(this).parents("li").addClass("checked");
            }

            var active_checkboxes = $("input[name='unplugged[]']:checked").length;
            if (active_checkboxes > 0) {
                $("#add_galleries_button").removeAttr("disabled");
            }
            else {
                $("#add_galleries_button").attr("disabled", true);
            }
        });


        $("#get_count").click(function (e) {
            e.preventDefault();
            var cat_cnt = $("input[name='categories[]']").length;
        });


        $("#add_new_cat").click(function (e) {
            e.preventDefault();
            var new_cat = $("#new_cat").val();
            if (new_cat.length == 0) {
                $("#new_cat").css("border", "1px solid red");
                setTimeout(function () {
                    $("#new_cat").css("border", "1px solid #ddd");
                }, 3000);
                return;
            }

            var cat_id = $("input[name='categories[]']").length + 1;


            $(".album_cat_list").append("<li><input style='class='del_val' name='cat_names[]' value='" + new_cat + "'>" +
                "&nbsp;<span class='delete_cat'><img src='<?php echo UXGALLERY_IMAGES_URL . '/admin_images/remove.jpg'; ?>' width='9' height='9' value='a'></span></li>");
            $("#new_cat").val("");
        });

        $(".delete_cat").live("click", function () {
            $(this).parent().remove();
        })


        $("#image_gallery_wp_album_style").change(function () {
            $(".editor_embed").val('[uxgallery_album id="' + $("#hidden_album_id").val() + '" style="' + $(this).val() + '"]');
            var sh_code = "<" + "?php echo do_shortcode(\"[uxgallery_album id='" + $("#hidden_album_id").val() + "' style='" + $(this).val() + "']\")?>";
            $(".script_embed").val(sh_code);
        })

    });

</script>
