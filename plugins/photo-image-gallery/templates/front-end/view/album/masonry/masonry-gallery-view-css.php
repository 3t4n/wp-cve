<?php
$cat_style = Photo_Gallery_WP()->settings->album_masonry_category_style;
$count_style = Photo_Gallery_WP()->settings->album_masonry_count_style;

$image_width = Photo_Gallery_WP()->settings->masonry_image_width_in_px;
$image_border = Photo_Gallery_WP()->settings->masonry_image_border_width_in_px;
$image_border_color = Photo_Gallery_WP()->settings->masonry_image_border_color;
$image_border_radius = Photo_Gallery_WP()->settings->masonry_image_border_radius;
$image_margin = Photo_Gallery_WP()->settings->masonry_image_margin_in_px;

$title_font_size = Photo_Gallery_WP()->settings->masonry_title_font_size_in_px;

$img_hover_dark_text_color = Photo_Gallery_WP()->settings->album_masonry_dark_text_color;
$img_hover_blur_text_color = Photo_Gallery_WP()->settings->album_masonry_blur_text_color;
$img_hover_scale_bg = Photo_Gallery_WP()->settings->album_masonry_scale_color;
$img_hover_scale_opacity = Photo_Gallery_WP()->settings->album_masonry_scale_opacity / 100;
$img_hover_scale_text_color = Photo_Gallery_WP()->settings->album_masonry_scale_text_color;
$img_hover_bottom_bg = Photo_Gallery_WP()->settings->album_masonry_bottom_color;
$img_hover_bottom_text_color = Photo_Gallery_WP()->settings->album_masonry_bottom_text_color;
$img_hover_elastic_text_color = Photo_Gallery_WP()->settings->album_masonry_elastic_text_color;
?>

    <style>
.album_images_count {
    float: right;
    position: absolute;
    top: 3px;
    right: 3px;
}

#hover {
    color: rgba(188, 175, 204, 0.9);
}

h2#testimonials {
    color: #fffae3;
}

div#all {
    width: 100%;
    height: 100%;
}

.album_back_button .album_socials {
    float: right;
    top: -8px
}

.album_back_button {
    margin-bottom: 15px;
}

.album_back_button .uxmodernsl-share-buttons {
    margin: 0px;
    padding: 6px 1px 0px 5px;
    background: rgba(0, 0, 0, 0.7);
    border-radius: 3px;
}

#back_to_albums, #back_to_galleries {
    background: #616161;
    padding: 10px;
    color: #fff;
    border-radius: 3px;
}

#back_to_albums:hover, #back_to_galleries:hover {
    background-color: #000;
}

.view img {
    height: auto;
}

.mosaicflow__column {
    float: left;
    width: <?= $image_width ?>px !important;
    margin-right: <?= $image_margin ?>px;
}

/*=========  hover style ===========*/

.view {
    color: #fff;
    margin-bottom: <?= $image_margin ?>px;
    overflow: hidden;
    position: relative;
    text-align: center;
    float: left;
    cursor: default;
    width: <?= $image_width ?>px !important;
    height: auto;
    border: <?= $image_border . "px" ?> solid #<?= $image_border_color ?>;
    border-radius: <?= $image_border_radius ?>px;
}

.view img {
    display: block;
    position: relative;
    transition: all 0.2s linear;
    max-width: 100%;
    width: 100%;
    margin: 0 auto;
    height: auto;
}

.mask-text h2 {
    font-size: <?= $title_font_size ?>px !important;
}

/* view 1 */
.view-first .text-category, .view-first .mask-text h2, .view-first .mask-text p {
    color: #<?= $img_hover_dark_text_color ?>;
}

/*  view 2 */
.view-second .text-category, .view-second .mask-text h2, .view-second p {
    color: #<?= $img_hover_blur_text_color ?>;
}

.view-second .mask-text h2 {
    border-bottom: 1px solid #<?= $img_hover_blur_text_color ?>;
}

/* view 3*/
.view-third .mask {
    background-color: <?php list($r, $g, $b) = array_map('hexdec', str_split($img_hover_scale_bg, 2));
$opacity = $img_hover_scale_opacity;
echo 'rgba(' . $r . ',' . $g . ',' . $b . ',' . $opacity . ')  !important'; ?>;
    transition: all 0.5s linear;
    opacity: 0;
    color: #<?= $img_hover_scale_text_color ?>
}

.view-third h2 {
    border-bottom: 1px solid #<?= $img_hover_scale_text_color ?>;
    background: transparent;
    margin: 5px 40px 0px 40px;
    transform: scale(0);
    color: #<?= $img_hover_scale_text_color ?>;
    transition: all 0.5s linear;
    opacity: 0;
}

.view-third p {
    color: #<?= $img_hover_scale_text_color ?>;
    opacity: 0;
    transform: scale(0);
    transition: all 0.5s linear;
}

.view-third .text-category {
    color: #<?= $img_hover_scale_text_color ?>;
}

/* view 4 */

.view-forth .mask-bg {
    background: #<?= $img_hover_bottom_bg ?>;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
}

.view-forth .mask-text {
    color: #<?= $img_hover_bottom_text_color ?>;
    position: relative;
    z-index: 500;
    padding: 5px 8px;
}

.view-forth .mask-text h2 {
    margin: 0px;
    font-size: 13px;
    padding: 2px;
    color: #<?= $img_hover_bottom_text_color ?>;
}

.view-forth .mask-text h2:hover {
    cursor: pointer;
}

.view-forth .text-category {
    display: block;
    font-size: 15px;
    color: #<?= $img_hover_bottom_text_color ?>;
}

.view-forth p {
    color: #<?= $img_hover_bottom_text_color ?>;
}

/* view 5 */
.view-fifth .text-category, .view-fifth .text-category *, .view-fifth .mask-text h2, .view-fifth .mask-text p {
    color: #<?= $img_hover_elastic_text_color ?>;
}

/*=========  category style ===========*/

.album_categories li span {
    float: left;
    margin: 0 5px 5px 5px;
    display: block;
    text-align: center;
    padding: 7px 16px;
    text-decoration: none;
<?php if ($cat_style == 0) { ?> background-color: #43454f;
    color: white;
    border-radius: 3px;
<?php } elseif ($cat_style == 1) { ?> background-color: #e9515f;
    border-radius: 7px;
    color: #fff;
<?php } elseif ($cat_style == 2) { ?> background-color: #fff;
    border: 2px solid #43454f;
    border-radius: 3px;
    color: #43454f;
<?php } elseif ($cat_style == 3) { ?> background-image: url("<?= PHOTO_GALLERY_WP_IMAGES_URL . "/albums/category/bg_3.png"; ?>");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: 50%;
    color: #fff;
    border-radius: 4px;
    border: 2px solid #7b2436;
    box-shadow: -1px -2px 4px rgba(4, 4, 4, 0.42);
<?php } elseif ($cat_style == 4) { ?> background-image: url("<?= PHOTO_GALLERY_WP_IMAGES_URL . "/albums/category/bg_4.png"; ?>");
    background-size: cover;
    background-repeat: no-repeat;
    background-position: 50%;
    color: #fff;
    border-radius: 4px;
    border: 2px solid #78985d;
    border-bottom-color: #758865;
    box-shadow: 0px 1px 4px rgba(4, 4, 4, 0.42);
<?php } elseif ($cat_style == 5) { ?> background-color: #ed1b52;
    border-radius: 3px;
    color: #ffffff;
<?php } elseif ($cat_style == 6) { ?> background-color: #42cb6f;
    color: #fff;
    border-bottom: 4px solid #3ab75c;
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
<?php } ?>
}

.album_categories li span.active, .album_categories li span:hover {
    cursor: pointer;

<?php if ($cat_style == 0) { ?> background-color: #2e303c;
<?php } elseif ($cat_style == 1) { ?> background-color: #b93642;
<?php } elseif ($cat_style == 2) { ?> background-color: #43454f;
    color: #ffffff;
<?php } elseif ($cat_style == 3) { ?> color: #fff683;
<?php } elseif ($cat_style == 4) { ?> color: #2f4a18;
<?php } elseif ($cat_style == 5) { ?> background-color: #ab1b41;
<?php } elseif ($cat_style == 6) { ?> background-color: #3ab75c;
<?php } ?>
}

/*=========  count style ===========*/

.album_images_count {
    font-size: 17px !important;
    font-weight: bold !important;
    font-family: sans-serif !important;
    background-repeat: no-repeat !important;
    background-size: contain !important;
    z-index: 2;
    width: 47px;
    height: 47px;
    padding-top: 6px !important;
<?php
switch ($count_style) {
    case 0:
        $count = 0;
        $color = "#565656";
        break;
    case 1:
        $count = 1;
        $color = "#565656";
        echo "width: 65px;";
        echo "background-size: contain !important;";
        break;
    case 2:
        $count = 2;
        $color = "#ffffff";
        break;
    case 3:
        $count = 3;
        $color = "#ffffff";
        break;
    case 4:
        $count = 4;
        $color = "#ffffff";
        echo "background-size: contain !important; width: 87px; height: 30px; text-align: right;padding:0px !important; padding-right: 12px !important;font-size:15px !important;";
        break;
    default:
        $count = 3;
        $color = "#ffffff";
        break;
}

?> background-image: url('<?= PHOTO_GALLERY_WP_IMAGES_URL . "/albums/count/" . $count . ".png" ?>') !important;
    color: <?= $color; ?>;
}

.count_image {
    font-size: 9px;
    position: absolute;
    left: 0px;
    width: 100%;
<?php if($count_style == 4){
    echo "top:12px; ";
}
else echo "top: 25px;";
    ?>
}

<?= '</style>' ?>