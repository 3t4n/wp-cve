<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class UXGallery_Ajax
{

    public function __construct()
    {
        add_action('wp_ajax_nopriv_uxgallery_ajax', array($this, 'callback'));
        add_action('wp_ajax_uxgallery_ajax', array($this, 'callback'));

        add_action('wp_ajax_uxgallery_get_album_images', array($this, 'uxgallery_get_album_images'));
        add_action('wp_ajax_nopriv_uxgallery_get_album_images', array($this, 'uxgallery_get_album_images'));
    }

    public function callback()
    {
        if (isset($_POST['task'])) {
            $task = sanitize_text_field($_POST['task']);
            switch ($task) {
                case 'load_images_content':
                    $this->content_popup_load_more();
                    break;
                case 'load_images_lightbox':
                    $this->lightbox_load_more();
                    break;
                case 'load_image_justified':
                    $this->justified_load_more();
                    break;
                case 'load_image_thumbnail':
                    $this->thumbnail_load_more();
                    break;
                case 'load_blog_view':
                    $this->blog_style__load_more();
                    break;
                case 'like':
                    $this->like_action();
                    break;
                case 'dislike':
                    $this->dislike_action();
                    break;
            }
        }
    }

    public static function content_popup_load_more()
    {

        if (!isset($_POST['task']) || !wp_verify_nonce($_POST['galleryImgContentLoadNonce'], 'uxgallery_content_load_nonce')) {
            wp_die('Security check fail');
        }
        global $wpdb;
        global $ux_ip;
        $page = 1;
        if (!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page'] > 0) {
            $page = intval($_POST["page"]);
            $num = intval($_POST['perpage']);
            $start = $page * $num - $num;
            $idofgallery = intval($_POST['galleryid']);
            $pID = intval($_POST['pID']);
            $likeStyle = esc_html($_POST['likeStyle']);
            $ratingCount = esc_html($_POST['ratingCount']);
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = '%d'  order by ordering ASC LIMIT %d,%d", $idofgallery, $start, $num);
            $page_images = $wpdb->get_results($query);
            $output = '';
            foreach ($page_images as $key => $row) {
                if (!isset($_COOKIE['Like_' . $row->id . ''])) {
                    $_COOKIE['Like_' . $row->id . ''] = '';
                }
                if (!isset($_COOKIE['Dislike_' . $row->id . ''])) {
                    $_COOKIE['Dislike_' . $row->id . ''] = '';
                }
                $num2 = $wpdb->prepare("SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip` = '" . $ux_ip . "'", (int)$row->id);
                $res3 = $wpdb->get_row($num2);
                $num3 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Like_' . $row->id . ''] . "'", (int)$row->id);
                $res4 = $wpdb->get_row($num3);
                $num4 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Dislike_' . $row->id . ''] . "'", (int)$row->id);
                $res5 = $wpdb->get_row($num4);
                $link = $row->sl_url;
                $video_name =
                    str_replace('__5_5_5__', '%', $row->name);
                $id = $row->id;
                $descnohtml = strip_tags(
                    str_replace('__5_5_5__', '%', $row->description));
                $result = substr($descnohtml, 0, 50);
                if ($video_name == '' && (empty($row->sl_url) || $row->sl_url == '')) {
                    $no_title = 'no-title';
                } else {
                    $no_title = '';
                }
                ?>
                <?php
                $imagerowstype = $row->sl_type;
                if ($row->sl_type == '') {
                    $imagerowstype = 'image';
                }
                switch ($imagerowstype) {
                    case 'image':
                        ?>
                        <?php
                        if (get_option('uxgallery_image_natural_size_contentpopup') == 'natural') {
                            $imgurl = $row->image_url;
                        } else {
                            $imgurl = esc_url(uxgallery_get_image_by_sizes_and_src($row->image_url, array(
                                get_option('uxgallery_ht_view2_element_width'),
                                get_option('uxgallery_ht_view2_element_height')
                            ), false));
                        } ?>
                        <?php if ($row->image_url != ';') {
                        $video = '<a  class="" href="#' . $id . '" title="' . $video_name . '"><img id="wd-cl-img' . $key . '" src="' . $imgurl . '" alt="" /></a>';
                    } else {
                        $video = '<a  class="" href="#' . $id . '" title="' . $video_name . '"><img id="wd-cl-img' . $key . '" src="images/noimage.jpg" alt="" /></a>';
                    } ?>
                        <?php
                        break;
                    case 'video':
                        ?>
                        <?php
                        $videourl = uxgallery_get_video_id_from_url($row->image_url);
                        if ($videourl[1] == 'youtube') {
                            if (empty($row->thumb_url)) {
                                $thumb_pic = 'https://img.youtube.com/vi/' . $videourl[0] . '/mqdefault.jpg';
                            } else {
                                $thumb_pic = $row->thumb_url;
                            }
                            $video = '<a  class="" href="#' . $id . '" title="' . $video_name . '"><img src="' . $thumb_pic . '" alt="" /></a>  ';
                        } else {
                            $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                            if (empty($row->thumb_url)) {
                                $imgsrc = $hash[0]['thumbnail_large'];
                            } else {
                                $imgsrc = $row->thumb_url;
                            }
                            $video = '<a  class="" href="#' . $id . '" title="' . $video_name . '"><img src="' . $imgsrc . '" alt="" /></a>';
                        }
                        ?>
                        <?php
                        break;
                }
                ?>
                <?php if ($row->sl_url == '' || empty($row->sl_url)) {
                    $button = '';
                } else {
                    if ($row->link_target == "on") {
                        $target = 'target="_blank"';
                    } else {
                        $target = '';
                    }
                    $button = '<div class="button-block"><a href="' . $row->sl_url . '" ' . $target . ' >' . sanitize_text_field($_POST['linkbutton']) . '</a></div>';
                }
                ?>
                <?php
                $thumb_status_like = '';
                if (isset($res3->image_status) && $res3->image_status == 'liked') {
                    $thumb_status_like = $res3->image_status;
                } elseif (isset($res4->image_status) && $res4->image_status == 'liked') {
                    $thumb_status_like = $res4->image_status;
                } else {
                    $thumb_status_like = 'unliked';
                }
                $thumb_status_dislike = '';
                if (isset($res3->image_status) && $res3->image_status == 'disliked') {
                    $thumb_status_dislike = $res3->image_status;
                } elseif (isset($res5->image_status) && $res5->image_status == 'disliked') {
                    $thumb_status_dislike = $res5->image_status;
                } else {
                    $thumb_status_dislike = 'unliked';
                }
                $likeIcon = '';
                if ($likeStyle == 'heart') {
                    $likeIcon = '<i class="uxgallery-icons-heart likeheart"></i>';
                } elseif ($likeStyle == 'dislike') {
                    $likeIcon = '<i class="uxgallery-icons-thumbs-up like_thumb_up"></i>';
                }
                $likeCount = '';
                if ($likeStyle != 'heart') {
                    $likeCount = $row->like;
                }
                $thumb_text_like = '';
                if ($likeStyle == 'heart') {
                    $thumb_text_like = $row->like;
                }
                $displayCount = '';
                if ($ratingCount == 'off') {
                    $displayCount = 'ux_hide';
                }
                if ($likeStyle != 'heart') {
                    $dislikeHtml = '<div class="uxgallery_dislike_wrapper">
                                <span class="ux_dislike">
                                    <i class="uxgallery-icons-thumbs-down dislike_thumb_down"></i>
                                    <span class="ux_dislike_thumb" id="' . $row->id . '" data-status="' . $thumb_status_dislike . '"></span>
                                    <span class="ux_dislike_count ' . $displayCount . '" id="' . $row->id . '">' . $row->dislike . '</span>
                                </span>
                            </div>';
                }
/////////////////////////////
                if ($likeStyle != 'off') {
                    $likeCont = '<div class="uxgallery_like_cont_' . $idofgallery . $pID . '">
                                <div class="uxgallery_like_wrapper">
                                    <span class="ux_like">' . $likeIcon . '
                                        <span class="ux_like_thumb" id="' . $row->id . '" data-status="' . $thumb_status_like . '">' . $thumb_text_like . '</span>
                                        <span class="ux_like_count ' . $displayCount . '" id="' . $row->id . '">' . $likeCount . '</span>
                                    </span>
                                </div>' . $dislikeHtml . '
                           </div>';
                }
///////////////////////////////
                $desc = '<span class="text-category">' . $row->description . '</span>';

                if (in_array(get_option("uxgallery_album_popup_show_title"), array("yes", "on")) && $row->name != "") {
                    $title = '<div class="mask-text"><h2>' . $row->name . '</h2>' . $desc . '</div>';
                } else {
                    $title = '<div class="mask-text">' . $desc . '</div>';
                }
                $output .= '<div class="element ' . $no_title . ' element_' . $idofgallery . ' view ' . esc_html($_POST["view_style"]) . ' " tabindex="0" data-symbol="' . $video_name . '"  data-category="alkaline-earth">';
                $output .= '<input type="hidden" class="pagenum" value="' . $page . '" />';
                $output .= '<div class="' . esc_html($_POST["view_style"]) . '-wrapper view-wrapper gallery-image-overlay">';
                $output .= $video;
                $output .= '<div class="gallery-image-overlay mask"><a  class="" href="#' . $id . '" title="' . $video_name . '">' . $title . '</a><a  class="" href="#' . $id . '" title="' . $video_name . '"><div class="mask-bg"></div></a></div>' . $likeCont . '
                         </div>';
                $output .= '</div>';
                $output .= $button;
                $output .= '</div>';
                $output .= '</div>';
            }
            echo json_encode(array("success" => $output));
            die();
        }
    }

    public static function lightbox_load_more()
    {
        if (!isset($_POST['galleryImgLightboxLoadNonce']) || !wp_verify_nonce($_POST['galleryImgLightboxLoadNonce'], 'uxgallery_lightbox_load_nonce')) {
            wp_die('Security check fail');
        }
        global $wpdb;
        global $ux_ip;
        $page = 1;
        if (!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page'] > 0) {
            $page = intval($_POST["page"]);
            $num = intval($_POST["perpage"]);
            $start = $page * $num - $num;
            $idofgallery = intval($_POST["galleryid"]);
            $pID = intval($_POST["pID"]);
            $likeStyle = esc_html($_POST['likeStyle']);
            $ratingCount = esc_html($_POST['ratingCount']);
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = '%d' order by ordering ASC LIMIT %d,%d", $idofgallery, $start, $num);
            $page_images = $wpdb->get_results($query);
            $output = '';
            foreach ($page_images as $key => $row) {
                if (!isset($_COOKIE['Like_' . $row->id . ''])) {
                    $_COOKIE['Like_' . $row->id . ''] = '';
                }
                if (!isset($_COOKIE['Dislike_' . $row->id . ''])) {
                    $_COOKIE['Dislike_' . $row->id . ''] = '';
                }
                $num2 = $wpdb->prepare("SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip` = '" . $ux_ip . "'", (int)$row->id);
                $res3 = $wpdb->get_row($num2);
                $num3 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Like_' . $row->id . ''] . "'", (int)$row->id);
                $res4 = $wpdb->get_row($num3);
                $num4 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Dislike_' . $row->id . ''] . "'", (int)$row->id);
                $res5 = $wpdb->get_row($num4);
                $link = $row->sl_url;
                $video_name =
                    str_replace('__5_5_5__', '%', $row->name);
                $descnohtml = strip_tags(str_replace('__5_5_5__', '%', $row->description));
                $result = substr($descnohtml, 0, 50);
                ?>
                <?php
                $imagerowstype = $row->sl_type;
                if ($row->sl_type == '') {
                    $imagerowstype = 'image';
                }
                $desc = '<span class="text-category">' . $row->description . '</span>';

                if (in_array(get_option("uxgallery_album_lightbox_show_title"), array("yes", "on")) && $row->name != "") {
                    $target = ($row->link_target == "on") ? "'_blank'" : "'_self'";
                    $url = "'$row->sl_url'";
                    if ($row->sl_url != "") {
                        $title = '<div class="mask-text"><h2  onclick="event.stopPropagation(); event.preventDefault();window.open(' . $url . ', ' . $target . ')">' . $row->name . '</h2>' . $desc . '</div>';
                    } else {
                        $title = '<div class="mask-text"><h2>' . $row->name . '</h2>' . $desc . '</div>';
                    }
                } else {
                    $title = '<div class="mask-text">' . $desc . '</div>';
                }
                switch ($imagerowstype) {
                    case 'image':
                        ?>
                        <?php $imgurl = explode(";", $row->image_url); ?>
                        <?php

                        if ($row->image_url != ';') {
                            $video = '<a href="' . $imgurl[0] . '" title="' . $video_name . '"><img id="wd-cl-img' . $key . '" src="' . esc_url(uxgallery_get_image_by_sizes_and_src(
                                    $imgurl[0], array(
                                    get_option('uxgallery_ht_view6_width'),
                                    ''
                                ), false
                                )) . '" alt="' . $video_name . '" />
                                <div class="mask">' . $title . '<div class="mask-bg"></div></div></a>';
                        } else {
                            $video = '<img id="wd-cl-img' . $key . '" src="images/noimage.jpg" alt="" />';
                        } ?>
                        <?php
                        break;
                    case 'video':
                        ?>
                        <?php
                        $videourl = uxgallery_get_video_id_from_url($row->image_url);
                        if ($videourl[1] == 'youtube') {
                            if (empty($row->thumb_url)) {
                                $thumb_pic = 'https://img.youtube.com/vi/' . $videourl[0] . '/mqdefault.jpg';
                            } else {
                                $thumb_pic = $row->thumb_url;
                            }
                            $video = '<a class="giyoutube ux_videogallery_item gallery_group' . $idofgallery . '"  href="https://www.youtube.com/embed/' . $videourl[0] . '" title="' . $video_name . '">
                                            <img src="' . $thumb_pic . '" alt="' . $video_name . '" />
                                            <div class="play-icon ' . $videourl[1] . '-icon"></div>
                                            <div class="mask">' . $title . '<div class="mask-bg"></div></div>
                                        </a>';
                        } else {
                            $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                            if (empty($row->thumb_url)) {
                                $imgsrc = $hash[0]['thumbnail_large'];
                            } else {
                                $imgsrc = $row->thumb_url;
                            }
                            $video = '<a class="givimeo ux_videogallery_item gallery_group' . $idofgallery . '" href="https://player.vimeo.com/video/' . $videourl[0] . '" title="' . $video_name . '">
                                    <img src="' . $imgsrc . '" alt="" />
                                    <div class="play-icon ' . $videourl[1] . '-icon"></div>
                                    <div class="mask">' . $title . '<div class="mask-bg"></div></div>
                                </a>';
                        }
                        ?>
                        <?php
                        break;
                }
                ?>
                <?php if (
                    str_replace('__5_5_5__', '%', $row->name) != ""
                ) {
                    if ($row->link_target == "on") {
                        $target = 'target="_blank"';
                    } else {
                        $target = '';
                    }
                    $linkimg = '<div class="title-block_' . $idofgallery . '" title="' . $video_name . '">';
                    if ($link != '' || !empty($link)) {
                        $linkimg .= '<a href="' . $link . '"' . $target . '>';
                    }
                    $linkimg .= $video_name;
                    if ($link != '' || !empty($link)) {
                        $linkimg .= '</a>';
                    }
                    $linkimg .= '</div>';
                } else {
                    $linkimg = '';
                }
                ?>
                <?php
                $thumb_status_like = '';
                if (isset($res3->image_status) && $res3->image_status == 'liked') {
                    $thumb_status_like = $res3->image_status;
                } elseif (isset($res4->image_status) && $res4->image_status == 'liked') {
                    $thumb_status_like = $res4->image_status;
                } else {
                    $thumb_status_like = 'unliked';
                }
                $thumb_status_dislike = '';
                if (isset($res3->image_status) && $res3->image_status == 'disliked') {
                    $thumb_status_dislike = $res3->image_status;
                } elseif (isset($res5->image_status) && $res5->image_status == 'disliked') {
                    $thumb_status_dislike = $res5->image_status;
                } else {
                    $thumb_status_dislike = 'unliked';
                }
                $likeIcon = '';
                if ($likeStyle == 'heart') {
                    $likeIcon = '<i class="uxgallery-icons-heart likeheart"></i>';
                } elseif ($likeStyle == 'dislike') {
                    $likeIcon = '<i class="uxgallery-icons-thumbs-up like_thumb_up"></i>';
                }
                $likeCount = '';
                if ($likeStyle != 'heart') {
                    $likeCount = $row->like;
                }
                $thumb_text_like = '';
                if ($likeStyle == 'heart') {
                    $thumb_text_like = $row->like;
                }
                $displayCount = '';
                if ($ratingCount == 'off') {
                    $displayCount = 'ux_hide';
                }
                if ($likeStyle != 'heart') {
                    $dislikeHtml = '<div class="uxgallery_dislike_wrapper">
                                <span class="ux_dislike">
                                    <i class="uxgallery-icons-thumbs-down dislike_thumb_down"></i>
                                    <span class="ux_dislike_thumb" id="' . $row->id . '" data-status="' . $thumb_status_dislike . '">
                                    </span>
                                    <span class="ux_dislike_count ' . $displayCount . '" id="' . $row->id . '">' . $row->dislike . '</span>
                                </span>
                            </div>';
                }
/////////////////////////////
                if ($likeStyle != 'off') {
                    $likeCont = '<div class="uxgallery_like_cont_' . $idofgallery . $pID . '">
                                <div class="uxgallery_like_wrapper">
                                    <span class="ux_like">' . $likeIcon . '
                                        <span class="ux_like_thumb" id="' . $row->id . '" data-status="' . $thumb_status_like . '">' . $thumb_text_like . '</span>
                                        <span class="ux_like_count ' . $displayCount . '" id="' . $row->id . '">' . $likeCount . '</span>
                                    </span>
                                </div>' . $dislikeHtml . '
                           </div>';
                }
///////////////////////////////
                $output .= '<div class="view ' . esc_html($_POST["view_style"]) . ' element element_' . $idofgallery . '" tabindex="0" data-symbol="' . $video_name . '"  data-category="alkaline-earth">';
                $output .= '<input type="hidden" class="pagenum" value="' . $page . '" />';
                $output .= '<div class="' . esc_html($_POST["view_style"]) . '-wrapper view-wrapper">';
                $output .= $video;
//                $output .= $linkimg;
                $output .= '</div>';
                $output .= $likeCont;
                $output .= '</div>';
            }
            echo json_encode(array("success" => $output));
            die();
        }
    }

    public static function justified_load_more()
    {
        if (!isset($_POST['galleryImgJustifiedLoadNonce']) || !wp_verify_nonce($_POST['galleryImgJustifiedLoadNonce'], 'uxgallery_justified_load_nonce')) {
            wp_die('Security check fail');
        }
        global $wpdb;
        global $ux_ip;
        $page = 1;
        if (!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page'] > 0) {
            $page = intval($_POST["page"]);
            $num = intval($_POST["perpage"]);
            $start = $page * $num - $num;
            $idofgallery = intval($_POST["galleryid"]);
            $pID = intval($_POST["pID"]);
            $likeStyle = esc_html($_POST['likeStyle']);
            $ratingCount = esc_html($_POST['ratingCount']);
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = '%d' order by ordering ASC LIMIT %d,%d", $idofgallery, $start, $num);
            $output = '';
            $page_images = $wpdb->get_results($query);
            foreach ($page_images as $key => $row) {
                if (!isset($_COOKIE['Like_' . $row->id . ''])) {
                    $_COOKIE['Like_' . $row->id . ''] = '';
                }
                if (!isset($_COOKIE['Dislike_' . $row->id . ''])) {
                    $_COOKIE['Dislike_' . $row->id . ''] = '';
                }
                $num2 = $wpdb->prepare("SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip` = '" . $ux_ip . "'", (int)$row->id);
                $res3 = $wpdb->get_row($num2);
                $num3 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Like_' . $row->id . ''] . "'", (int)$row->id);
                $res4 = $wpdb->get_row($num3);
                $num4 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Dislike_' . $row->id . ''] . "'", (int)$row->id);
                $res5 = $wpdb->get_row($num4);
                $video_name = str_replace('__5_5_5__', '%', $row->name);
                $videourl = uxgallery_get_video_id_from_url($row->image_url);
                $imgurl = explode(";", $row->image_url);
                $image_prefix = "_ux_small_gallery";
                $imagerowstype = $row->sl_type;
                $thumb_status_like = '';
                if (isset($res3->image_status) && $res3->image_status == 'liked') {
                    $thumb_status_like = $res3->image_status;
                } elseif (isset($res4->image_status) && $res4->image_status == 'liked') {
                    $thumb_status_like = $res4->image_status;
                } else {
                    $thumb_status_like = 'unliked';
                }
                $thumb_status_dislike = '';
                if (isset($res3->image_status) && $res3->image_status == 'disliked') {
                    $thumb_status_dislike = $res3->image_status;
                } elseif (isset($res5->image_status) && $res5->image_status == 'disliked') {
                    $thumb_status_dislike = $res5->image_status;
                } else {
                    $thumb_status_dislike = 'unliked';
                }
                $likeIcon = '';
                if ($likeStyle == 'heart') {
                    $likeIcon = '<i class="uxgallery-icons-heart likeheart"></i>';
                } elseif ($likeStyle == 'dislike') {
                    $likeIcon = '<i class="uxgallery-icons-thumbs-up like_thumb_up"></i>';
                }
                $likeCount = '';
                if ($likeStyle != 'heart') {
                    $likeCount = $row->like;
                }
                $thumb_text_like = '';
                if ($likeStyle == 'heart') {
                    $thumb_text_like = $row->like;
                }
                $displayCount = '';
                if ($ratingCount == 'off') {
                    $displayCount = 'ux_hide';
                }
                if ($likeStyle != 'heart') {
                    $dislikeHtml = '<div class="uxgallery_dislike_wrapper">
                                <span class="ux_dislike">
                                    <i class="uxgallery-icons-thumbs-down dislike_thumb_down"></i>
                                    <span class="ux_dislike_thumb" id="' . $row->id . '" data-status="' . $thumb_status_dislike . '">
                                    </span>
                                    <span class="ux_dislike_count ' . $displayCount . '" id="' . $row->id . '">' . $row->dislike . '</span>
                                </span>
                            </div>';
                }
/////////////////////////////
                if ($likeStyle != 'off') {
                    $likeCont = '<div class="uxgallery_like_cont_' . $idofgallery . $pID . '">
                                <div class="uxgallery_like_wrapper">
                                    <span class="ux_like">' . $likeIcon . '
                                        <span class="ux_like_thumb" id="' . $row->id . '" data-status="' . $thumb_status_like . '">' . $thumb_text_like . '
                                        </span>
                                        <span class="ux_like_count ' . $displayCount . '" id="' . $row->id . '">' . $likeCount . '</span>
                                    </span>
                                </div>' . $dislikeHtml . '
                           </div>';
                }
///////////////////////////////
                if ($row->sl_type == '') {
                    $imagerowstype = 'image';
                }
                switch ($imagerowstype) {
                    case 'image':
                        if ($row->image_url != ';') {
                            $imgperfix = esc_url(uxgallery_get_image_by_sizes_and_src($imgurl[0], array(
                                '',
                                get_option('uxgallery_ht_view8_element_height')
                            ), false));
                            $video = '<a class="gallery_group' . $idofgallery . '" href="' . $imgurl[0] . '" title="' . $video_name . '">
                                            <img  id="wd-cl-img' . $key . '" alt="' . $video_name . '" src="' . $imgperfix . '"/>
                                            ' . $likeCont . '
                                        </a>
                                        <input type="hidden" class="pagenum" value="' . $page . '" />'; ?>
                        <?php } else {
                            $video = '<img alt="' . $video_name . '" id="wd-cl-img' . $key . '" src="images/noimage.jpg"  />
                                                ' . $likeCont . '
                                        <input type="hidden" class="pagenum" value="' . $page . '" />';
                        } ?>
                        <?php
                        break;
                    case 'video':
                        if ($videourl[1] == 'youtube') {
                            if (empty($row->thumb_url)) {
                                $thumb_pic = 'https://img.youtube.com/vi/' . $videourl[0] . '/mqdefault.jpg';
                            } else {
                                $thumb_pic = $row->thumb_url;
                            }
                            $video = '<a class="giyoutube ux_videogallery_item gallery_group' . $idofgallery . '"  href="https://www.youtube.com/embed/' . $videourl[0] . '" title="' . $video_name . '">
                                                <img  src="' . $thumb_pic . '" alt="' . $video_name . '" />
                                                ' . $likeCont . '
                                                <div class="play-icon ' . $videourl[1] . '-icon"></div>
                                        </a>';
                        } else {
                            $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                            if (empty($row->thumb_url)) {
                                $imgsrc = $hash[0]['thumbnail_large'];
                            } else {
                                $imgsrc = $row->thumb_url;
                            }
                            $video = '<a class="givimeo ux_videogallery_item gallery_group' . $idofgallery . '" href="https://player.vimeo.com/video/' . $videourl[0] . '" title="' . $video_name . '">
                                                <img alt="' . $video_name . '" src="' . $imgsrc . '"/>
                                                ' . $likeCont . '
                                                <div class="play-icon ' . $videourl[1] . '-icon"></div>
                                        </a>';
                        }
                        break;
                }
                $output .= $video . '<input type="hidden" class="pagenum" value="' . $page . '" />';
            }
            echo json_encode(array("success" => $output));
            die();
        }
    }

    public static function thumbnail_load_more()
    {
        if (!isset($_POST['galleryImgThumbnailLoadNonce']) || !wp_verify_nonce($_POST['galleryImgThumbnailLoadNonce'], 'uxgallery_thumbnail_load_nonce')) {
            wp_die('Security check fail');
        }
        global $wpdb;
        global $ux_ip;
        $page = 1;
        if (!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page'] > 0) {
            $page = intval($_POST["page"]);
            $num = intval($_POST["perpage"]);
            $start = $page * $num - $num;
            $idofgallery = intval($_POST["galleryid"]);
            $pID = intval($_POST["pID"]);
            $likeStyle = esc_html($_POST['likeStyle']);
            $ratingCount = esc_html($_POST['ratingCount']);
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = '%d' order by ordering ASC LIMIT %d,%d", $idofgallery, $start, $num);
            $output = '';
            $page_images = $wpdb->get_results($query);
            foreach ($page_images as $key => $row) {
                if (!isset($_COOKIE['Like_' . $row->id . ''])) {
                    $_COOKIE['Like_' . $row->id . ''] = '';
                }
                if (!isset($_COOKIE['Dislike_' . $row->id . ''])) {
                    $_COOKIE['Dislike_' . $row->id . ''] = '';
                }
                $num2 = $wpdb->prepare("SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip` = '" . $ux_ip . "'", (int)$row->id);
                $res3 = $wpdb->get_row($num2);
                $num3 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Like_' . $row->id . ''] . "'", (int)$row->id);
                $res4 = $wpdb->get_row($num3);
                $num4 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Dislike_' . $row->id . ''] . "'", (int)$row->id);
                $res5 = $wpdb->get_row($num4);
                $video_name = str_replace('__5_5_5__', '%', $row->name);
                $imgurl = explode(";", $row->image_url);
                $videourl = uxgallery_get_video_id_from_url($row->image_url);
                $imagerowstype = $row->sl_type;
                if ($row->sl_type == '') {
                    $imagerowstype = 'image';
                }
                $desc = '<span class="text-category">' . $row->description . '</span>';
                $target = ($row->link_target == "on") ? '_blank' : '_self';
                $link = "";
                if ($row->sl_url != "") {
                    $link = "event.stopPropagation(); event.preventDefault();window.open('" . $row->sl_url . "', '" . $target . "')";
                }
                if (in_array(get_option("uxgallery_album_thumbnail_show_title"), array("yes", "on")) && $row->name != "") {

                    $title = '<div class="mask-text"><h2 onclick="' . $link . '">' . $row->name . '</h2>' . $desc . '</div>';
                } else {
                    $title = '<div class="mask-text">' . $desc . '</div>';
                }
                switch ($imagerowstype) {
                    case 'image':

                        if (get_option('uxgallery_image_natural_size_thumbnail') == 'resize') {
                            $imgperfix = esc_url(uxgallery_get_image_by_sizes_and_src($imgurl[0], array(
                                get_option('uxgallery_thumb_image_width'),
                                get_option('uxgallery_thumb_image_height')
                            ), false));
                        } else {
                            $imgperfix = $imgurl[0];
                        }
                        $video = '
                            <a class="ph-lightbox gallery_group' . $idofgallery . '" href="' . $row->image_url . '" title="' . $video_name . '">
                            <img  src="' . $imgperfix . '" alt="' . $video_name . '" />
                            <div class="mask">' . $title . '
                            <div class="mask-bg"></div></div></a>';
                        break;
                    case 'video':
                        if ($videourl[1] == 'youtube') {
                            $video = '
                            <a class="giyoutube uxgallery_item gallery_group' . $idofgallery . '"  href="https://www.youtube.com/embed/' . $videourl[0] . '" title="' . str_replace("__5_5_5__", "%", $row->name) . '">
                            <img alt="' . str_replace("__5_5_5__", "%", $row->name) . '" src="https://img.youtube.com/vi/' . $videourl[0] . '/mqdefault.jpg"  />
                            <div class="mask">' . $title . '
                            <div class="mask-bg"></div>
                            </div></a>';
                        } else {
                            $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                            $imgsrc = $hash[0]['thumbnail_large'];
                            $video = '
                            <a class="givimeo uxgallery_item gallery_group' . $idofgallery . '" href="https://player.vimeo.com/video/' . $videourl[0] . '" title="' . str_replace("__5_5_5__", "%", $row->name) . '">
                            <img alt="' . str_replace("__5_5_5__", "%", $row->name) . '" src="' . $imgsrc . '"  />
                            <div class="mask">' . $title . '
                            <div class="mask-bg"></div>
                            </div></a>';
                        }
                        ?>
                        <?php
                        break;
                }
                ?>
                <?php
                $thumb_status_like = '';
                if (isset($res3->image_status) && $res3->image_status == 'liked') {
                    $thumb_status_like = $res3->image_status;
                } elseif (isset($res4->image_status) && $res4->image_status == 'liked') {
                    $thumb_status_like = $res4->image_status;
                } else {
                    $thumb_status_like = 'unliked';
                }
                $thumb_status_dislike = '';
                if (isset($res3->image_status) && $res3->image_status == 'disliked') {
                    $thumb_status_dislike = $res3->image_status;
                } elseif (isset($res5->image_status) && $res5->image_status == 'disliked') {
                    $thumb_status_dislike = $res5->image_status;
                } else {
                    $thumb_status_dislike = 'unliked';
                }
                $likeIcon = '';
                if ($likeStyle == 'heart') {
                    $likeIcon = '<i class="uxgallery-icons-heart likeheart"></i>';
                } elseif ($likeStyle == 'dislike') {
                    $likeIcon = '<i class="uxgallery-icons-thumbs-up like_thumb_up"></i>';
                }
                $likeCount = '';
                if ($likeStyle != 'heart') {
                    $likeCount = $row->like;
                }
                $thumb_text_like = '';
                if ($likeStyle == 'heart') {
                    $thumb_text_like = $row->like;
                }
                $displayCount = '';
                if ($ratingCount == 'off') {
                    $displayCount = 'ux_hide';
                }
                if ($likeStyle != 'heart') {
                    $dislikeHtml = '<div class="uxgallery_dislike_wrapper">
                                <span class="ux_dislike">
                                    <i class="uxgallery-icons-thumbs-down dislike_thumb_down"></i>
                                    <span class="ux_dislike_thumb" id="' . $row->id . '" data-status="' . $thumb_status_dislike . '">
                                    </span>
                                    <span class="ux_dislike_count ' . $displayCount . '" id="' . $row->id . '">' . $row->dislike . '</span>
                                </span>
                            </div>';
                }
/////////////////////////////
                if ($likeStyle != 'off') {
                    $likeCont = '<div class="uxgallery_like_cont_' . $idofgallery . $pID . '">
                                <div class="uxgallery_like_wrapper">
                                    <span class="ux_like">' . $likeIcon . '
                                        <span class="ux_like_thumb" id="' . $row->id . '" data-status="' . $thumb_status_like . '">' . $thumb_text_like . '
                                        </span>
                                        <span class="ux_like_count ' . $displayCount . '" id="' . $row->id . '">' . $likeCount . '</span>
                                    </span>
                                </div>' . $dislikeHtml . '
                           </div>';
                }
///////////////////////////////
                $output .= '
                <div class="ux_big_li view ' . esc_html($_POST["view_style"]) . '">
                    <div class="' . esc_html($_POST["view_style"]) . '-wrapper view-wrapper">
                     ' . $likeCont . '<input type="hidden" class="pagenum" value="' . $page . '" />
                        ' . $video . '
                    </div>
                </div>
            ';
            }
            echo json_encode(array("success" => $output));
            die();
        }
    }

    public static function blog_style__load_more()
    {
        if (!isset($_POST['galleryImgBlogLoadNonce']) || !wp_verify_nonce($_POST['galleryImgBlogLoadNonce'], 'uxgallery_blog_load_nonce')) {
            wp_die('Security check fail');
        }
        global $wpdb;
        global $ux_ip;
        $page = 1;
        if (!empty($_POST["page"]) && is_numeric($_POST['page']) && $_POST['page'] > 0) {
            $page = intval($_POST["page"]);
            $num = intval($_POST["perpage"]);
            $start = $page * $num - $num;
            $idofgallery = intval($_POST["galleryid"]);
            $pID = intval($_POST["pID"]);
            $likeStyle = esc_html($_POST['likeStyle']);
            $ratingCount = esc_html($_POST['ratingCount']);
            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images where gallery_id = '%d' order by ordering ASC LIMIT %d,%d", $idofgallery, $start, $num);
            $output = '';
            $page_images = $wpdb->get_results($query);
            foreach ($page_images as $key => $row) {
                $img2video = '';
                if (!isset($_COOKIE['Like_' . $row->id . ''])) {
                    $_COOKIE['Like_' . $row->id . ''] = '';
                }
                if (!isset($_COOKIE['Dislike_' . $row->id . ''])) {
                    $_COOKIE['Dislike_' . $row->id . ''] = '';
                }
                $num2 = $wpdb->prepare("SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip` = '" . $ux_ip . "'", (int)$row->id);
                $res3 = $wpdb->get_row($num2);
                $num3 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Like_' . $row->id . ''] . "'", (int)$row->id);
                $res4 = $wpdb->get_row($num3);
                $num4 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $_COOKIE['Dislike_' . $row->id . ''] . "'", (int)$row->id);
                $res5 = $wpdb->get_row($num4);
                $img_src = $row->image_url;
                $img_name = str_replace('__5_5_5__', '%', $row->name);
                $img_desc = str_replace('__5_5_5__', '%', $row->description);
                $videourl = uxgallery_get_video_id_from_url($row->image_url);
                $imagerowstype = $row->sl_type;
                $img3video = '';
                if ($imagerowstype == '') {
                    $imagerowstype = 'image';
                }
                if ($imagerowstype == 'image') {
                    $img2video .= '<img class="view9_img" src="' . $img_src . '">';
                } else {
                    if ($videourl[1] == 'youtube') {
                        $img3video .= '<div class="iframe_cont">
                                        <iframe class="video_blog_view" src="//www.youtube.com/embed/' . $videourl[0] . '" style="border: 0;" allowfullscreen></iframe>
                                    </div>';
                    } else {
                        $img3video .= '<div class="iframe_cont">
                                                <iframe class="video_blog_view" src="//player.vimeo.com/video/' . $videourl[0] . '" style="border: 0;" allowfullscreen></iframe>
                                            </div>';
                    }
                }
                if ($imagerowstype == 'image') {
                    $link_img_video = $img2video;
                } else {
                    $link_img_video = $img3video;
                }
                $thumb_status_like = '';
                if (isset($res3->image_status) && $res3->image_status == 'liked') {
                    $thumb_status_like = $res3->image_status;
                } elseif (isset($res4->image_status) && $res4->image_status == 'liked') {
                    $thumb_status_like = $res4->image_status;
                } else {
                    $thumb_status_like = 'unliked';
                }
                $thumb_status_dislike = '';
                if (isset($res3->image_status) && $res3->image_status == 'disliked') {
                    $thumb_status_dislike = $res3->image_status;
                } elseif (isset($res5->image_status) && $res5->image_status == 'disliked') {
                    $thumb_status_dislike = $res5->image_status;
                } else {
                    $thumb_status_dislike = 'unliked';
                }
                $likeIcon = '';
                if ($likeStyle == 'heart') {
                    $likeIcon = '<i class="uxgallery-icons-heart likeheart"></i>';
                } elseif ($likeStyle == 'dislike') {
                    $likeIcon = '<i class="uxgallery-icons-thumbs-up like_thumb_up"></i>';
                }
                $likeCount = '';
                if ($likeStyle != 'heart') {
                    $likeCount = $row->like;
                }
                $thumb_text_like = '';
                if ($likeStyle == 'heart') {
                    $thumb_text_like = $row->like;
                }
                $displayCount = '';
                if ($ratingCount == 'off') {
                    $displayCount = 'ux_hide';
                }
                if ($likeStyle != 'heart') {
                    $dislikeHtml = '<div class="uxgallery_dislike_wrapper">
                                <span class="ux_dislike">
                                    <i class="uxgallery-icons-thumbs-down dislike_thumb_down"></i>
                                    <span class="ux_dislike_thumb" id="' . $row->id . '" data-status="' . $thumb_status_dislike . '">
                                    </span>
                                    <span class="ux_dislike_count ' . $displayCount . '" id="' . $row->id . '">' . $row->dislike . '</span>
                                </span>
                            </div>';
                }
/////////////////////////////
                if ($likeStyle != 'off') {
                    $likeCont = '<div class="uxgallery_like_cont_' . $idofgallery . $pID . '">
                                <div class="uxgallery_like_wrapper">
                                    <span class="ux_like">' . $likeIcon . '
                                        <span class="ux_like_thumb" id="' . $row->id . '" data-status="' . $thumb_status_like . '">' . $thumb_text_like . '
                                        </span>
                                        <span class="ux_like_count ' . $displayCount . '" id="' . $row->id . '">' . $likeCount . '</span>
                                    </span>
                                </div>' . $dislikeHtml . '
                           </div>';
                }
///////////////////////////////
                if ($likeStyle != 'heart') {
                    $output .= '<div class="view9_container">
                                <input type="hidden" class="pagenum" value="' . $page . '" />
                                <h1 class="new_view_title">' . $img_name . '</h1>' . $link_img_video . '
                                <div class="new_view_desc">' . $img_desc . '</div>' . $likeCont . '</div>
                          <div class="clear"></div>';
                }
                if ($likeStyle == 'heart') {
                    $output .= '<div class="view9_container">
                                <input type="hidden" class="pagenum" value="' . $page . '" />
                                <h1 class="new_view_title">' . $img_name . '</h1><div class="blog_img_wrapper">' . $link_img_video . $likeCont . '</div>
                                <div class="new_view_desc">' . $img_desc . '</div></div>
                          <div class="clear"></div>';
                }
            }
        }
        echo json_encode(array("success" => $output, "typeOfres" => $imagerowstype));
        die();
    }

    public static function like_action()
    {
        $ux_ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ux_ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ux_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ux_ip = $_SERVER['REMOTE_ADDR'];
        }
        global $wpdb;

        if (!isset($_POST['image_id']) || absint($_POST['image_id']) != $_POST['image_id']) {
            echo json_encode(array('success' => 0));
            die();
        }

        $image_id = absint($_POST['image_id']);
        $cook = sanitize_text_field($_POST['cook']);

        $num = $wpdb->prepare("SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d", $image_id);
        $num2 = $wpdb->prepare("SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip` = '" . $ux_ip . "'", $image_id);
        $res = $wpdb->get_results($num);
        $res2 = $wpdb->get_results($num, ARRAY_A);
        $res3 = $wpdb->get_row($num2);
        $num3 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $cook . "'", $image_id);
        $res4 = $wpdb->get_row($num3);
        $resIP = '';
        for ($i = 0; $i < count($res2); $i++) {
            $resIP .= $res2[$i]['ip'] . '|';
        }
        $arrIP = explode("|", $resIP);
        if (!isset($res3) && !isset($res4)) {
            $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ux_gallery_like_dislike (`image_id`,`image_status`,`ip`,`cook`) VALUES ( %d, 'liked', '" . $ux_ip . "',%s)", $image_id, $cook));
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  `like` = `like`+1 WHERE id = %d ", $image_id));
            $numLike = $wpdb->prepare("SELECT `like` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resLike = $wpdb->get_results($numLike);
            $numDislike = $wpdb->prepare("SELECT `dislike` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resDislike = $wpdb->get_results($numDislike);
            echo json_encode(array("like" => $resLike[0]->like, "statLike" => 'Liked'));
        } elseif ((isset($res3) && $res3->image_status == 'liked' && $res3->ip == $ux_ip) || (isset($res4) && $res4->image_status == 'liked' && $res4->cook == $cook)) {
            if (isset($res3) && $res3->image_status == 'liked' && $res3->ip == $ux_ip) {
                $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip`='" . $ux_ip . "'", $image_id));
            } elseif (isset($res4) && $res4->cook == $cook) {
                $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook`='" . $cook . "'", $image_id));
            }
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  `like` = `like`-1 WHERE id = %d ", $image_id));
            $numLike = $wpdb->prepare("SELECT `like` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resLike = $wpdb->get_results($numLike);
            $numDislike = $wpdb->prepare("SELECT `dislike` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resDislike = $wpdb->get_results($numDislike);
            echo json_encode(array("like" => $resLike[0]->like, "statLike" => 'Like'));
        } elseif ((isset($res3) && $res3->image_status == 'disliked' && $res3->ip == $ux_ip) || (isset($res4) && $res4->image_status == 'disliked' && $res4->cook == $cook)) {
            if (isset($res3) && $res3->image_status == 'disliked' && $res3->ip == $ux_ip) {
                $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip`='" . $ux_ip . "'", $image_id));
            } elseif (isset($res4) && $res4->image_status == 'disliked' && $res4->cook == $cook) {
                $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook`='" . $cook . "'", $image_id));
            }
            $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ux_gallery_like_dislike (`image_id`,`image_status`,`ip`,`cook`) VALUES ( %d, 'liked', '" . $ux_ip . "',%s)", $image_id, $cook));
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  `like` = `like`+1 WHERE id = %d ", $image_id));
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  `dislike` = `dislike`-1 WHERE id = %d ", $image_id));
            $numLike = $wpdb->prepare("SELECT `like` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resLike = $wpdb->get_results($numLike);
            $numDislike = $wpdb->prepare("SELECT `dislike` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resDislike = $wpdb->get_results($numDislike);
            echo json_encode(array(
                "like" => $resLike[0]->like,
                "dislike" => $resDislike[0]->dislike,
                "statLike" => 'Liked',
                "statDislike" => 'Dislike'
            ));
        }
        die();
    }

    public static function dislike_action()
    {
        $ux_ip = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ux_ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ux_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ux_ip = $_SERVER['REMOTE_ADDR'];
        }
        global $wpdb;

        if (!isset($_POST['image_id']) || absint($_POST['image_id']) != $_POST['image_id']) {
            echo json_encode(array('success' => 0));
            die();
        }

        $image_id = absint($_POST['image_id']);
        $cook = sanitize_text_field($_POST['cook']);

        $num = $wpdb->prepare("SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d", $image_id);
        $num2 = $wpdb->prepare("SELECT `image_status`,`ip` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip` = '" . $ux_ip . "'", $image_id);
        $res = $wpdb->get_results($num);
        $res2 = $wpdb->get_results($num, ARRAY_A);
        $res3 = $wpdb->get_row($num2);
        $num3 = $wpdb->prepare("SELECT `image_status`,`ip`,`cook` FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook` = '" . $cook . "'", $image_id);
        $res4 = $wpdb->get_row($num3);
        $resIP = '';
        for ($i = 0; $i < count($res2); $i++) {
            $resIP .= $res2[$i]['ip'] . '|';
        }
        $arrIP = explode("|", $resIP);
        if (!isset($res3) && !isset($res4)) {
            $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ux_gallery_like_dislike (`image_id`,`image_status`,`ip`,`cook`) VALUES ( %d, 'disliked', '" . $ux_ip . "',%s)", $image_id, $cook));
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  `dislike` = `dislike`+1 WHERE id = %d ", $image_id));
            $numDislike = $wpdb->prepare("SELECT `dislike` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resDislike = $wpdb->get_results($numDislike);
            $numLike = $wpdb->prepare("SELECT `like` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resLike = $wpdb->get_results($numLike);
            echo json_encode(array("dislike" => $resDislike[0]->dislike, "statDislike" => 'Disliked'));
        } elseif ((isset($res3) && $res3->image_status == 'disliked' && $res3->ip == $ux_ip) || (isset($res4) && $res4->image_status == 'disliked' && $res4->cook == $cook)) {
            if (isset($res3) && $res3->image_status == 'disliked' && $res3->ip == $ux_ip) {
                $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip`='" . $ux_ip . "'", $image_id));
            } elseif (isset($res4) && $res4->image_status == 'disliked' && $res4->cook == $cook) {
                $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook`='" . $cook . "'", $image_id));
            }
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  `dislike` = `dislike`-1 WHERE id = %d ", $image_id));
            $numDislike = $wpdb->prepare("SELECT `dislike` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resDislike = $wpdb->get_results($numDislike);
            $numLike = $wpdb->prepare("SELECT `like` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resLike = $wpdb->get_results($numLike);
            echo json_encode(array("dislike" => $resDislike[0]->dislike, "statDislike" => 'Dislike'));
        } elseif ((isset($res3) && $res3->image_status == 'liked' && $res3->ip == $ux_ip) || (isset($res4) && $res4->image_status == 'liked' && $res4->cook == $cook)) {
            if (isset($res3) && $res3->image_status == 'liked' && $res3->ip == $ux_ip) {
                $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `ip`='" . $ux_ip . "'", $image_id));
            } elseif (isset($res4) && $res4->image_status == 'liked' && $res4->cook == $cook) {
                $wpdb->query($wpdb->prepare("DELETE FROM " . $wpdb->prefix . "ux_gallery_like_dislike WHERE image_id = %d AND `cook`='" . $cook . "'", $image_id));
            }
            $wpdb->query($wpdb->prepare("INSERT INTO " . $wpdb->prefix . "ux_gallery_like_dislike (`image_id`,`image_status`,`ip`,`cook`) VALUES ( %d, 'disliked', '" . $ux_ip . "',%s)", $image_id, $cook));
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  `dislike` = `dislike`+1 WHERE id = %d ", $image_id));
            $wpdb->query($wpdb->prepare("UPDATE " . $wpdb->prefix . "ux_gallery_images SET  `like` = `like`-1 WHERE id = %d ", $image_id));
            $numDislike = $wpdb->prepare("SELECT `dislike` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resDislike = $wpdb->get_results($numDislike);
            $numLike = $wpdb->prepare("SELECT `like` FROM " . $wpdb->prefix . "ux_gallery_images WHERE id = %d LIMIT 1", $image_id);
            $resLike = $wpdb->get_results($numLike);
            echo json_encode(array(
                "like" => $resLike[0]->like,
                "dislike" => $resDislike[0]->dislike,
                "statLike" => 'Like',
                "statDislike" => 'Disliked'
            ));
        }
        die();
    }

    public function uxgallery_get_album_images($id)
    {
        check_ajax_referer('get_album_images', 'nonce');

        global $wpdb;

        $response = array();
        $required_data = array();
        $gallery_id = array();

        if (isset($_POST["album_id"])) {
            $id = esc_html($_POST["album_id"]);
            // $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_album_has_gallery AS album_has_gallery LEFT JOIN " . $wpdb->prefix . "ux_gallery_gallerys AS galleries
            // ON (album_has_gallery.id_gallery = galleries.id) left join " . $wpdb->prefix . "ux_gallery_images AS images on(galleries.id = images.gallery_id) WHERE album_has_gallery.id_album = '%d' ORDER BY album_has_gallery.order ASC, images.ordering ASC ", $id);

            $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "ux_gallery_images AS images WHERE images.gallery_id = '%d' ORDER BY images.ordering ASC ", $id);

            $gallerys = $wpdb->get_results($query);

            //uxgallery_get_album_images
            foreach ($gallerys as $key => $val) {
                $target = ($val->link_target == "on") ? "_blank" : "_self";
                $val->name = strip_tags($val->name);
                $thumb = null;
                if ($val->sl_type == "video") {
                    $videourl = uxgallery_get_video_id_from_url($val->image_url);
                    if ($videourl[1] == 'youtube') {
                        $thumb = ' <img src="https://img.youtube.com/vi/' . esc_html($videourl[0]) . '/mqdefault.jpg"
                                     alt="' . esc_attr($val->name) . '">';
                        $url = $val->image_url;
                    } else {
                        $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                        $imgsrc = $hash[0]['thumbnail_large'];
                        $thumb = ' <img src="' . esc_attr($imgsrc) . '" alt="' . esc_attr($val->name) . '">';
                        //$val->sl_url = "https://vimeo.com/" . $videourl[0];
                        $url = "https://player.vimeo.com/video/" . $videourl[0];
                    }
                } else {
                    $thumb = ' <img src="' . esc_attr($val->image_url) . '" alt="' . esc_attr($val->name) . '">';
                    $url = $val->image_url;
                }

                $required_data[$key] = array(
                    "id" => intval($val->id),
                    "id_gallery" => intval($val->gallery_id),
                    "name" => esc_html($val->name),
                    "description" => $val->description,
                    "image_url" => esc_url($val->image_url),
                    "sl_url" => $val->sl_url,
                    "target" => $target,
                    "thumbnail" => $thumb,
                    "url" => $url
                );
            }
            $response = array(
                "success" => 1,
                'images' => $required_data,
            );

        }

        echo json_encode($response);
        wp_die();
    }

}