
<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

global $wpdb;
$gallery_wp_nonce_add_gallery = wp_create_nonce('gallery_wp_nonce_add_gallery');
if (isset($_GET['id$rowim']) && $_GET['id'] != '') {
	$id = intval($_GET['id']);
}

if (!isset($id) || $id == 0) {
	wp_die("missing gallery ID");
}

if (isset($_GET["ref"]) && $_GET["ref"] == "albums") {
	$album_id = $_GET["album_id"];
} elseif (isset($_POST["album_mode"])) {
	$album_id = $_POST["album_mode"];
} else {
	$album_id = null;
}

$album_image = null;
if (!is_null($album_id)) {
	$query = $wpdb->prepare("SELECT `cover_image` FROM " . $wpdb->prefix . "ux_gallery_album_has_gallery where id_album = %d AND id_gallery = %d", $album_id, $id);
	$album_image = $wpdb->get_row($query)->cover_image;
}

if ((isset($_GET["ref"]) && $_GET["ref"] == "albums") || isset($_POST["album_mode"])) {
	$hide = "style='display:none !important; visibility: hidden !important;'";
	$mode = "album";
} else {
	$hide = "";
	$mode = "gallery";
}
?>
<div class="wrap uxgallery_wrap">
	<?php
	$path_site = plugins_url("../images", __FILE__);
	$save_data_nonce = wp_create_nonce('uxgallery_nonce_save_data' . $id);
	$add_video_nonce = wp_create_nonce('gallery_add_video_nonce' . $id);
	?>
    <div class="clear"></div>
    <form action="admin.php?page=galleries_uxgallery&id=<?php echo $row->id; ?>&save_data_nonce=<?php echo $save_data_nonce; ?>"
          method="post" name="adminForm" method="post"
          name="adminForm" id="adminForm">
         <input type="hidden" class="changedvalues" value="" name="changedvalues" size="80">

        <div id="poststuff">
            <div id="gallery-header" <?= $hide ?>>
                <!-- Full width Header -->
            </div>
            <div id="post-body" class="metabox-holder columns-2">

                <!-- Options -->



                <!-- Content -->
                <div id="post-body-content">
                   <div class="image_gallery_page_heading">
                      <h1>Edit Gallery</h1> <a href="<?php echo admin_url('admin.php?page=galleries_uxgallery&amp;task=add_gallery&gallery_wp_nonce_add_gallery='.$gallery_wp_nonce_add_gallery); ?>" class="page-title-action">Add Gallery</a>
                       <input class="name_field" onkeyup="name_changeTop(this)"
                             type="text"
                              name="name" id="name" maxlength="250"
                              value="<?php echo esc_html(stripslashes($row->name)); ?>"/>
                   </div>


                    <div id="gallery-unique-options" class="postbox">
                        <h3 class="hndle" <?= $hide ?>>
                            <span><?php echo __('Image Gallery Custom Options', 'gallery-img'); ?></span>
                        </h3>

                        <ul id="gallery-unique-options-list">
                                <li>
                                    <label for="ux_sl_effects"><?php echo __('Choose Template', 'gallery-img'); ?></label>
                                    <select name="ux_sl_effects" id="ux_sl_effects">
                                        <option <?php if ($row->ux_sl_effects == '10') {echo 'selected';} ?> value="10" data-demo="https://uxgallery.net/demo-elastic-grid/">
		                                    <?php echo __('Elastic Grid', 'gallery-img'); ?>
                                        </option>
                                        <option <?php if ($row->ux_sl_effects == '0') {	echo 'selected';} ?> value="0" data-demo="https://uxgallery.net/demo-popup/">
                                            <?php echo __('Popup Grid', 'gallery-img'); ?>
                                        </option>
                                        <option <?php if ($row->ux_sl_effects == '1') {	echo 'selected';} ?> value="1" data-demo="https://uxgallery.net/demo-info-slider/">
                                            <?php echo __('Info Slider', 'gallery-img'); ?>
                                        </option>
                                        <option <?php if ($row->ux_sl_effects == '5') {echo 'selected';	} ?> value="5" data-demo="https://uxgallery.net/demo-masonry/">
                                            <?php echo __('Masonry', 'gallery-img'); ?>
                                        </option>
                                        <option <?php if ($row->ux_sl_effects == '3') {	echo 'selected';} ?> value="3" data-demo="https://uxgallery.net/demo-slideshow/">
                                            <?php echo __('Slideshow', 'gallery-img'); ?>
                                        </option>
                                        <option <?php if ($row->ux_sl_effects == '4') {	echo 'selected';} ?> value="4" data-demo="https://uxgallery.net/demo-lightbox-grid/">
                                            <?php echo __('Lightbox Grid', 'gallery-img'); ?>
                                        </option>
                                        <option <?php if ($row->ux_sl_effects == '6') {echo 'selected';} ?> value="6" data-demo="https://uxgallery.net/demo-justified/">
                                            <?php echo __('Justified', 'gallery-img'); ?>
                                        </option>
                                        <option <?php if ($row->ux_sl_effects == '7') {	echo 'selected';} ?> value="7" data-demo="https://uxgallery.net/demo-blog-style-gallery/">
                                            <?php echo __('Blog Style Gallery', 'gallery-img'); ?>
                                        </option>
                                    </select>
                                    <a href="" target="_blank" class="view_template_demo" title="See Template Demo">
                                        <img src="<?php echo UXGALLERY_IMAGES_URL; ?>/admin_images/see_demo.png" alt="See Template Demo" />
                                    </a>

                                </li>
                                <li style="z-index:2;" >
                                    <label style="float:left; z-index:2;" for="uxgallery_description"><?php echo __('Gallery Caption', 'gallery-img'); ?></label>
                                    <textarea name="gallery_description" style="margin-left: 5px;" id="uxgallery_description"><?php echo esc_html(stripslashes($row->description)); ?></textarea>
                                </li>
                                <li>
                                    <label for="rating"><?php echo __('Enable Ratings', 'gallery-img'); ?></label>
                                    <select id="rating" name="rating">
                                        <option <?php if ($row->rating == 'off') {
                                            echo 'selected';
                                        } ?> value="off"><?php echo __('Off', 'gallery-img'); ?></option>
                                        <option <?php if ($row->rating == 'dislike') {
                                            echo 'selected';
                                        } ?>
                                                value="dislike"><?php echo __('Like/Dislike', 'gallery-img'); ?></option>
                                        <option <?php if ($row->rating == 'heart') {
                                            echo 'selected';
                                        } ?> value="heart"><?php echo __('Heart', 'gallery-img'); ?></option>
                                    </select>
                                </li>
                                <li>
                                    <label for="disable_right_click"><?php echo __('Disable Image Right Click', 'gallery-img'); ?></label>
                                    <select id="disable_right_click" name="disable_right_click" class="short">

                                        <option <?php if (get_option('uxgallery_disable_right_click') == 'on') {
                                            echo 'selected';
                                        } ?> value="on"><?php echo __('On', 'gallery-img'); ?></option>
                                        <option <?php if (get_option('uxgallery_disable_right_click') == 'off') {
                                            echo 'selected';
                                        } ?>
                                                value="off"><?php echo __('Off', 'gallery-img'); ?></option>
                                    </select>
                                </li>

                                <li  class="hidden slider_options">
                                    <label
                                            for="sl_width"><?php echo __('Slider Width', 'gallery-img'); ?></label>
                                    <input type="number" name="sl_width" id="sl_width"
                                           value="<?php echo esc_attr($row->sl_width); ?>"
                                           class="text_area"/>
                                </li>
                                <li  class="hidden slider_options">
                                    <label
                                            for="sl_height"><?php echo __('Slider Height', 'gallery-img'); ?></label>
                                    <input type="number" name="sl_height" id="sl_height"
                                           value="<?php echo esc_attr($row->sl_height); ?>"
                                           class="text_area"/>
                                </li>
                                <li  class="hidden slider_options">
                                    <label
                                            for="gallery_list_effects_s"><?php echo __('Slider Effects', 'gallery-img'); ?></label>
                                    <select name="gallery_list_effects_s" id="gallery_list_effects_s">
                                        <option <?php if ($row->gallery_list_effects_s == 'none') {
                                            echo 'selected';
                                        } ?>
                                                value="none"><?php echo __('None', 'gallery-img'); ?>
                                        </option>

                                        <option <?php if ($row->gallery_list_effects_s == 'fade') {
                                            echo 'selected';
                                        } ?>
                                                value="fade"><?php echo __('Fade', 'gallery-img'); ?></option>
                                        <option <?php if ($row->gallery_list_effects_s == 'sliceH') {
                                            echo 'selected';
                                        } ?>
                                                value="sliceH"><?php echo __('Slice Horizontal', 'gallery-img'); ?></option>
                                        <option <?php if ($row->gallery_list_effects_s == 'sliceV') {
                                            echo 'selected';
                                        } ?>
                                                value="sliceV"><?php echo __('Slice Vertical', 'gallery-img'); ?></option>
                                        <option <?php if ($row->gallery_list_effects_s == 'slideH') {
                                            echo 'selected';
                                        } ?>
                                                value="slideH"><?php echo __('Slide Horizontal', 'gallery-img'); ?></option>
                                        <option <?php if ($row->gallery_list_effects_s == 'slideV') {
                                            echo 'selected';
                                        } ?>
                                                value="slideV"><?php echo __('Slide Vertical', 'gallery-img'); ?></option>
                                        <option <?php if ($row->gallery_list_effects_s == 'scaleOut') {
                                            echo 'selected';
                                        } ?>
                                                value="scaleOut"><?php echo __('Scale Out', 'gallery-img'); ?></option>
                                        <option <?php if ($row->gallery_list_effects_s == 'scaleIn') {
                                            echo 'selected';
                                        } ?>
                                                value="scaleIn"><?php echo __('Scale In', 'gallery-img'); ?></option>
                                        <option <?php if ($row->gallery_list_effects_s == 'blockScale') {
                                            echo 'selected';
                                        } ?>
                                                value="blockScale"><?php echo __('Block Scale', 'gallery-img'); ?></option>
                                        <option <?php if ($row->gallery_list_effects_s == 'kaleidoscope') {
                                            echo 'selected';
                                        } ?>
                                                value="kaleidoscope"><?php echo __('Kaleidoscope', 'gallery-img'); ?></option>
                                        <option <?php if ($row->gallery_list_effects_s == 'fan') {
                                            echo 'selected';
                                        } ?>
                                                value="fan"><?php echo __('Fan', 'gallery-img'); ?></option>
                                        <option <?php if ($row->gallery_list_effects_s == 'blindH') {
                                            echo 'selected';
                                        } ?>
                                                value="blindH"><?php echo __('Blind Horizontal', 'gallery-img'); ?></option>
                                        <option <?php if ($row->gallery_list_effects_s == 'blindV') {
                                            echo 'selected';
                                        } ?>
                                                value="blindV"><?php echo __('Blind Vertical', 'gallery-img'); ?></option>

                                    </select>
                                </li>

                                <li class="hidden slider_options">
                                    <label
                                            for="slider_position"><?php echo __('Slider Position', 'gallery-img'); ?></label>
                                    <select name="sl_position" id="slider_position">
                                        <option <?php if ($row->sl_position == 'left') {
                                            echo 'selected';
                                        } ?>
                                                value="left"><?php echo __('Left', 'gallery-img'); ?></option>
                                        <option <?php if ($row->sl_position == 'right') {
                                            echo 'selected';
                                        } ?>
                                                value="right"><?php echo __('Right', 'gallery-img'); ?></option>
                                        <option <?php if ($row->sl_position == 'center') {
                                            echo 'selected';
                                        } ?>
                                                value="center"><?php echo __('Center', 'gallery-img'); ?></option>
                                    </select>
                                </li>

                                <li class="hidden content_slider_options">
                                    <label
                                            for="autoslide"><?php echo __('Enable Autoslide', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off" name="autoslide"/>
                                    <input type="checkbox" name="autoslide" value="on"
                                           id="autoslide" <?php if ($row->autoslide == 'on') {
                                        echo 'checked="checked"';
                                    } ?> />
                                </li>

                                <li class="hidden content_slider_options slider_options">
                                    <label
                                            for="pause_on_hover"><?php echo __('Pause on hover', 'gallery-img'); ?></label>
                                    <input type="hidden" value="off" name="pause_on_hover"/>
                                    <input type="checkbox" name="pause_on_hover" value="on"
                                           id="pause_on_hover" <?php if ($row->pause_on_hover == 'on') {
										echo 'checked="checked"';
									} ?> />
                                </li>
                                <li class="hidden content_slider_options slider_options">
                                    <label
                                            for="sl_pausetime"><?php echo __('Pause Duration', 'gallery-img'); ?></label>
                                    <input type="number" name="sl_pausetime" id="sl_pausetime"
                                           value="<?php echo esc_html(stripslashes($row->param));?>"
                                           class="text_area" />
                                </li>
                               <!--
                                <li class="hidden content_slider_options slider_options">
                                    <label
                                            for="sl_changespeed"><?php echo __('Sliding Speed', 'gallery-img'); ?></label>
                                    <input type="number" name="sl_changespeed" id="sl_changespeed"
                                           value="<?php echo esc_html(stripslashes($row->param)); ?>"
                                           class="text_area"/>
                                </li>-->

                                <li class="hidden pagination_options">
                                    <label for="display_type"><?php echo __('Load More Images', 'gallery-img'); ?></label>
                                    <select id="display_type" name="display_type">

                                        <option <?php if ($row->display_type == 0) {
                                            echo 'selected';
                                        } ?>
                                                value="0"><?php echo __('Pagination', 'gallery-img'); ?></option>
                                        <option <?php if ($row->display_type == 1) {
                                            echo 'selected';
                                        } ?>
                                                value="1"><?php echo __('Load More', 'gallery-img'); ?></option>
                                        <option <?php if ($row->display_type == 2) {
                                            echo 'selected';
                                        } ?>
                                                value="2"><?php echo __('Show All', 'gallery-img'); ?></option>
                                    </select>
                                </li>

                                <li id="content_per_page_li" class="hidden pagination_options">
                                    <label
                                            for="content_per_page"><?php echo __('Images Per Page', 'gallery-img'); ?></label>
                                    <input type="number" name="content_per_page" id="content_per_page"
                                           value="<?php echo esc_attr($row->content_per_page); ?>"
                                           class="numb_area"/>
                                </li>
                        </ul>
                    </div>

                    <!-- IMAGE LIST -->
					<?php add_thickbox(); ?>
                    <div id="post-body">
                        <div id="images_list_wrapper" class="postbox">
                            <h3 class=""><?php echo __('Gallery Items', 'gallery-images'); ?></h3>
                            <div class="buttons_block">
                                <input type="hidden" name="imagess" id="_unique_name">
                                <input class="order_by" type="hidden" name="order_by_1" value="0">
                                <div class="ux-newuploader uploader  add-new-image">
                                    <input type="button" class="button wp-media-buttons-icon button-primary"
                                           name="_unique_name_button"
                                           id="_unique_name_button" value="Add Image"/>
                                </div>
                                <a href="" class="free_notice_button button button-primary add-video-slide">
	                                <?php echo __('Add Video', 'gallery-img'); ?>
                                </a>
                            </div>

                            <div class="inside">
                                <ul id="gallery-images-list" class="images_list_sortable">
                                    <?php
                                    $i = 2;

                                    $strn=0;
                                    foreach ($rowim as $key => $rowimages) { ?>

                                        <?php if ($rowimages->sl_type == '') {
                                            $rowimages->sl_type = 'image';
                                        }

                                        $gallery_nonce_remove_image = wp_create_nonce('gallery_nonce_remove_image' . $rowimages->id);


                                        switch ($rowimages->sl_type) {
                                            case 'image': ?>
                                                <li id="order_elem_<?php echo $rowimages->id; ?>">
                                                    <input class="order_by" type="hidden" name="order_by_<?php echo $rowimages->id; ?>" value="<?php echo $rowimages->ordering; ?>"/>
                                                    <div class="image-container">
                                                        <div class="list-img-wrapper">
                                                            <img src="<?php echo $rowimages->image_url; ?>"/>
                                                        </div>
                                                        <div>
                                                            <input type="hidden" name="imagess<?php echo $rowimages->id; ?>"
                                                                   id="_unique_name<?php echo $rowimages->id; ?>"
                                                                   value="<?php echo esc_attr($rowimages->image_url); ?>"/>
                                                            <span class="wp-media-buttons-icon"></span>
                                                        </div>
                                                    </div>
                                                    <div class="image-options">
                                                        <div class="remove-image-container">
                                                            <a id="remove_image<?php echo $rowimages->id; ?>"
                                                               class="remove-image"
                                                               data-image-id="<?php echo $rowimages->id; ?>"
                                                               data-gallery-id="<?php echo $row->id; ?>"
                                                               data-nonce-value="<?php echo $gallery_nonce_remove_image; ?>">x</a>
                                                        </div>
                                                        <div class="edit-image-container">
                                                            <a id="edit_image<?php echo $rowimages->id; ?>"
                                                               class="edit-image"
                                                               data-image-id="<?php echo $rowimages->id; ?>"
                                                               data-gallery-id="<?php echo $row->id; ?>"
                                                               data-nonce-value="<?php echo $gallery_nonce_remove_image; ?>">e</a>
                                                        </div>
                                                    </div>


                                                    <div class="clear"></div>
                                                </li>


                                                <?php
                                                break;
                                            case 'video':
                                                ?>

                                                <li id="order_elem_<?php echo $rowimages->id; ?>">
                                                    <input class="order_by" type="hidden"  name="order_by_<?php echo $rowimages->id; ?>" value="<?php echo $rowimages->ordering; ?>"/>
                                                    <?php if (strpos($rowimages->image_url, 'youtube') !== false || strpos($rowimages->image_url, 'youtu') !== false) {
                                                        $liclass = "youtube";
                                                        $video_thumb = uxgallery_get_video_id_from_url($rowimages->image_url);
                                                        $video_thumb_url = $video_thumb[0];
                                                        $thumburl = '<img src="https://img.youtube.com/vi/' . $video_thumb_url . '/mqdefault.jpg" alt="" class="video-thumb-img" />';
                                                    } else if (strpos($rowimages->image_url, 'vimeo') !== false) {
                                                        $liclass = "vimeo";
                                                        $vimeo = $rowimages->image_url;
                                                        $vimeo_explode = explode("/", $vimeo);
                                                        $imgid = end($vimeo_explode);
                                                        $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $imgid . ".php"));
                                                        $imgsrc = $hash[0]['thumbnail_large'];
                                                        $thumburl = '<img src="' . $imgsrc . '" alt="" class="video-thumb-img" />';
                                                    }
                                                    ?>
                                                    <div class="image-container">
                                                        <?php echo $thumburl; ?>
                                                        <div class="play-icon <?php echo $liclass; ?>"></div>
                                                        <div>
                                                            <input type="hidden" name="imagess<?php echo $rowimages->id; ?>"
                                                                   value="<?php echo esc_attr($rowimages->image_url); ?>"/>
                                                        </div>
                                                    </div>
                                                    <div class="image-options">
                                                        <div class="remove-image-container">
                                                            <a id="remove_image<?php echo $rowimages->id; ?>"
                                                               class="remove-image"
                                                               data-image-id="<?php echo $rowimages->id; ?>"
                                                               data-gallery-id="<?php echo $row->id; ?>"
                                                               data-nonce-value="<?php echo $gallery_nonce_remove_image; ?>">x</a>
                                                        </div>
                                                        <div class="edit-image-container">
                                                            <a id="edit_image<?php echo $rowimages->id; ?>"
                                                               class="edit-image"
                                                               data-image-id="<?php echo $rowimages->id; ?>"
                                                               data-gallery-id="<?php echo $row->id; ?>"
                                                               data-nonce-value="<?php echo $gallery_nonce_remove_image; ?>">e</a>
                                                        </div>
                                                    </div>
                                                    <div class="clear"></div>
                                                </li>
                                                <?php
                                                break;
                                        }
                                        $strn++;
                                    }

                                    if($strn==0){ ?>
                                        <div class="theme-browser">
                                        <div class="theme add-new-theme">
                                            <div class="theme add-new-theme ux-newuploader"><a id="_unique_name_button" href=""><div class="theme-screenshot"><span></span></div><h2 class="theme-name">Add The First Image</h2></a></div>
                                        </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </ul>
                            </div>

                            <!-- modal start -->
                            <div id="modal_images_list_wrapper" class="media-modal-content" role="document"><div class="edit-attachment-frame mode-select hide-menu hide-router modula-edit-popup">
                                    <div class="edit-media-header">
                                        <button class="left dashicons" data-action="left-change"><span class="screen-reader-text">Edit previous media item</span></button>
                                        <button class="right dashicons disabled" data-action="right-change"><span class="screen-reader-text">Edit next media item</span></button>
                                        <button type="button" class="media-modal-close dashicons" data-action="close"><span class="media-modal-icon"><span class="screen-reader-text">Close dialog</span></span></button>
                                    </div>

                                    <div class="media-frame-title">
                                        <h1>Edit Image</h1>
                                    </div>
                                    <div class="media-frame-content">
                                        <div class="attachment-details save-ready">
                                            <ul class="modal_images_list">
	                                            <?php
	                                            $i = 2;

	                                            $strn=0;
	                                            foreach ($rowim as $key => $rowimages) { ?>

	                                            <?php if ($rowimages->sl_type == '') {
		                                            $rowimages->sl_type = 'image';
	                                            }

	                                            $gallery_nonce_remove_image = wp_create_nonce('gallery_nonce_remove_image' . $rowimages->id);
	                                            ?>

                                                    <li class="<?php if($strn==0){echo 'active';} ?>" id="elem_<?php echo $rowimages->id; ?>">
                                                      <?php
                                                        switch ($rowimages->sl_type) {
                                                        case 'image': ?>

                                                            <!-- Left -->
                                                            <div class="attachment-media-view portrait">
                                                                <div class="thumbnail thumbnail-image">
                                                                    <img class="details-image" src="<?php echo $rowimages->image_url; ?>" draggable="false" />
                                                                </div>
                                                            </div>
                                                        <?php

                                                        case 'video':?>
                                                            <?php if (strpos($rowimages->image_url, 'youtube') !== false || strpos($rowimages->image_url, 'youtu') !== false) {
                                                                $liclass = "youtube";
                                                                $video_thumb = uxgallery_get_video_id_from_url($rowimages->image_url);
                                                                $video_thumb_url = $video_thumb[0];
                                                                $thumburl = '<img src="https://img.youtube.com/vi/' . $video_thumb_url . '/hqdefault.jpg" alt="" class="details-image video-thumb-img" draggable="false" />';
                                                            } else if (strpos($rowimages->image_url, 'vimeo') !== false) {
                                                                $liclass = "vimeo";
                                                                $vimeo = $rowimages->image_url;
                                                                $vimeo_explode = explode("/", $vimeo);
                                                                $imgid = end($vimeo_explode);
                                                                $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $imgid . ".php"));
                                                                $imgsrc = $hash[0]['thumbnail_large'];
                                                                $thumburl = '<img src="' . $imgsrc . '" alt="" class="details-image video-thumb-img" draggable="false" />';
                                                            }
                                                            ?>

                                                            <div class="attachment-media-view portrait">
                                                                <div class="thumbnail thumbnail-image">
                                                                    <?php echo $thumburl; ?>
                                                                    <div class="play-icon <?php echo $liclass; ?>"></div>
                                                                    <div>
                                                                        <input type="hidden" name="imagess<?php echo $rowimages->id; ?>"
                                                                               value="<?php echo esc_attr($rowimages->image_url); ?>"/>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <?php
                                                                break;
                                                            }
                                                            ?>

                                                            <!-- Right -->
                                                            <div class="attachment-info">
                                                                <!-- Settings -->
                                                                <div class="settings">
                                                                    <!-- Attachment ID -->
                                                                    <input type="hidden" name="id" value="24">

                                                                    <!-- Image Title -->
                                                                    <label class="setting">
                                                                        <span class="name">Title</span>
                                                                        <input class="text_area" type="text"
                                                                               placeholder="<?php echo __('Title:', 'gallery-img'); ?>"
                                                                               id="titleimage<?php echo $rowimages->id; ?>"
                                                                               name="titleimage<?php echo $rowimages->id; ?>"
                                                                               id="titleimage<?php echo $rowimages->id; ?>"
                                                                               value="<?php echo esc_attr(str_replace('__5_5_5__', '%', $rowimages->name)); ?>">
                                                                    </label>


                                                                    <!-- Caption Text -->
                                                                    <label class="setting">
                                                                        <span class="name">Caption Text</span>
                                                                        <textarea id="im_description<?php echo $rowimages->id; ?>"
                                                                                  placeholder="<?php echo __('Description:', 'gallery-img'); ?>"
                                                                                  name="im_description<?php echo $rowimages->id; ?>"><?php echo esc_html(str_replace('__5_5_5__', '%', $rowimages->description)); ?></textarea>
                                                                        <div class="description">Image caption can take any type of HTML.</div>
                                                                    </label>

                                                                    <!-- Alignment
                                                                    <div class="setting">
                                                                        <span class="name">Alignment</span>
                                                                        <select name="halign" class="inline-input">
                                                                            <option>left</option>
                                                                            <option selected="">center</option>
                                                                            <option>right</option>
                                                                        </select>
                                                                        <select name="valign" class="inline-input">
                                                                            <option>top</option>
                                                                            <option selected="">middle</option>
                                                                            <option>bottom</option>
                                                                        </select>
                                                                    </div>
        -->
                                                                    <!-- Link -->
                                                                    <div class="setting">
                                                                        <label class="">
                                                                            <span class="name">URL</span>
                                                                            <input class="text_area url-input" type="text"
                                                                                   placeholder="<?php echo __('URL:', 'gallery-img'); ?>"
                                                                                   id="sl_url<?php echo $rowimages->id; ?>"
                                                                                   name="sl_url<?php echo $rowimages->id; ?>"
                                                                                   value="<?php echo str_replace('__5_5_5__', '%', $rowimages->sl_url); ?>">
                                                                        </label>
                                                                        <label>
                                                                            <span class="description">
                                                                                <input type="hidden"
                                                                                       name="sl_link_target<?php echo $rowimages->id; ?>"
                                                                                       value=""/>
                                                                            <input <?php if ($rowimages->link_target == 'on') {
                                                                                echo 'checked="checked"';
                                                                            } ?> class="link_target" type="checkbox"
                                                                                 id="sl_link_target<?php echo $rowimages->id; ?>"
                                                                                 name="sl_link_target<?php echo $rowimages->id; ?>"/>
                                                                                <span>Opens your image links in a new browser window / tab.</span>
                                                                            </span>
                                                                        </label>
                                                                    </div>

                                                                    <!-- Raitings -->
                                                                    <label class="setting">
                                                                        <span class="name"><?php echo __('Ratings', 'gallery-img'); ?></span>

                                                                        <div class="description ratings_off">Ratings are disabled from custom options</div>
                                                                        <div class="like_dislike_wrapper">
                                                                            <div>
                                                                                <label for="like_<?php echo $rowimages->id; ?>" class="like"><?php echo __('Like', 'gallery-img'); ?></label>
                                                                                <input class="" type="number" id="like_<?php echo $rowimages->id; ?>" name="like_<?php echo $rowimages->id; ?>" value="<?php echo str_replace('__5_5_5__', '%', $rowimages->like); ?>">
                                                                            </div>
                                                                            <div>
                                                                                <label for="dislike_<?php echo $rowimages->id; ?>" class="dislike"><?php echo __('Dislike', 'gallery-img'); ?></label>
                                                                                <input class="" num="<?php echo $rowimages->id; ?>" type="number" id="dislike_<?php echo $rowimages->id; ?>" name="dislike_<?php echo $rowimages->id; ?>" value="<?php echo str_replace('__5_5_5__', '%', $rowimages->dislike); ?>">
                                                                            </div>
                                                                        </div>
                                                                        <div class="heart_wrapper">
                                                                            <div>
                                                                                <label for="like_<?php echo $rowimages->id; ?>" class="like"><?php echo __('Hearts', 'gallery-img'); ?></label>
                                                                                <input class="" num="<?php echo $rowimages->id; ?>" type="number" id="like_<?php echo $rowimages->id; ?>" name="like_<?php echo $rowimages->id; ?>" value="<?php echo str_replace('__5_5_5__', '%', $rowimages->like); ?>">
                                                                            </div>
                                                                        </div>
                                                                    </label>

                                                                </div>
                                                                <!-- /.settings -->

                                                                <!-- Actions -->
                                                                <div class="actions">
                                                                    <input type="button" onclick="galleryImgSubmitButton('apply')" value="Save" id="save-buttom" class="button button-primary button-large">
                                                                    <a id="remove_image<?php echo $rowimages->id; ?>" class="remove-image button media-button button-large media-button-insert" data-image-id="<?php echo $rowimages->id; ?>" data-gallery-id="<?php echo $row->id; ?>" data-nonce-value="<?php echo $gallery_nonce_remove_image; ?>">Remove Image</a>
                                                                    <!-- Save Spinner -->
                                                                    <span class="settings-save-status">
                                                                        <span class="spinner"></span>
                                                                        <span class="saved">Saved.</span>
                                                                    </span>


                                                                </div>
                                                                <!-- /.actions -->
                                                            </div>
                                                        </li>
                                                <?php

                                                    $strn++;
                                                }?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- modal end -->
                        </div>
                    </div>

                </div>

                <!-- SIDEBAR -->
                <div id="postbox-container-1" class="update_gallery_wrapper postbox-container">
                    <div id="side-sortables" class="meta-box-sortables ui-sortable">
                        <div id="gallery-shortcode-box" class="postbox shortcode ms-toggle">
                            <h3 class="hndle"><span><?php echo __('Save & Publish', 'gallery-img'); ?></span></h3>
                            <div class="inside">
                                <div id="">
                                    <div id="update_block">
                                        <input type="button" onclick="galleryImgSubmitButton('apply')"
                                               value="Update Gallery"
                                               id="save-buttom" class="button button-primary button-large">
                                        <?php if (isset($_GET["ref"]) || isset($_POST["album_mode"])) { ?>
                                            <input type="hidden" name="album_mode" value="<?= $album_id ?>">
                                        <?php } ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <p><?php echo __('Copy and paste the blue color shortcode in your page/post editor, grey collor addition can be used for PHP integration.'); ?></p>
                                <pre  class="shortcode_block">&lt;?php echo do_shortcode('&emsp;<div class="active">[uxgallery id="<?php echo $row->id; ?>"]</div>'); ?&gt;</pre>
                            </div>
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
        <input type="hidden" name="task" value=""/>
    </form>
</div>
<?php
require_once(UXGALLERY_TEMPLATES_PATH . DIRECTORY_SEPARATOR . 'admin' . DIRECTORY_SEPARATOR . 'video-add-html.php');
?>