<?php

switch (Photo_Gallery_WP()->settings->album_masonry_onhover_effects) {
    case 0:
        $hover_class = "view-first";
        break;
    case 1:
        $hover_class = "view-second";
        break;
    case 2:
        $hover_class = "view-third";
        break;
    case 3:
        $hover_class = "view-forth";
        break;
    case 4:
        $hover_class = "view-fifth";
        break;
    default:
        $hover_class = "view-first";
        break;
}

if (Photo_Gallery_WP()->settings->album_masonry_show_sharing_buttons === "false" || Photo_Gallery_WP()->settings->album_masonry_show_sharing_buttons === "no") {
    $album_share = 0;
} else {
    $album_share = 1;
}

$mosaic_count = 1;
$mosaic = 1;
$mosaic_width = Photo_Gallery_WP()->settings->masonry_image_width_in_px;

$cat_class = array();
$cat_class_all = array();

foreach ($album_categories as $val) {
    $cat_class_all[] = ".ux_cat_" . $val->id;
}

$show_title = (in_array(Photo_Gallery_WP()->settings->album_masonry_show_title, array("yes", "true"))) ? "yes" : "no";
$show_desc = (in_array(Photo_Gallery_WP()->settings->album_masonry_show_description, array("yes", "true"))) ? "yes" : "no";
?>


<input type="hidden" name="sharing_buttons" value="<?= $album_share ?>">
<input type="hidden" name="mosaic" value="<?= $mosaic ?>">
<input type="hidden" name="mosaic_count" value="<?= $mosaic_count ?>">
<input type="hidden" name="mosaic_width" value="<?= $mosaic_width ?>">
<input type="hidden" name="show_title" value="<?= $show_title ?>">
<input type="hidden" name="show_desc" value="<?= $show_desc ?>">

<div id="main" style="display: inline-block; width:100%;">
    <div id="gallery_images" class="album_list"></div>
    <div id="album_image_place" class=" album_list"></div>
    <div id="album_list_container">
        <div id="album_disabled_layer"></div>
        <?php if (!empty($cat_class_all)) { ?>
            <div class="row album_categories">
                <ul id="filters" class="clearfix">
                    <li><span class="filter active" id="album_all_categories"
                              data-filter=".ux_cat_0, <?php echo implode(', ', $cat_class_all); ?>"><?= __("All", "gallery-images") ?></span>
                    </li>
                    <?php foreach ($album_categories as $key => $cat) { ?>
                        <li><span class="filter" data-filter=".ux_cat_<?= $cat->id ?>"><?= $cat->name ?></span></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>
        <div class="row filtr-container album_list" id="album_list">

            <?php foreach ($albums as $key => $val) {
                foreach ($val->galleries as $album) { ?>
                    <div class="view  <?= $hover_class; ?> <?php echo implode(" ", $album->cat_class); ?> ux_cat_0">

                        <div class="<?= $hover_class; ?>-wrapper view-wrapper">
                            <?php if (in_array(Photo_Gallery_WP()->settings->album_masonry_show_image_count_2, array('true', 'yes'))) { ?>
                                <span class="album_images_count"><?= $album->image_count ?><br><span
                                            class="count_image">Images</span></span>
                            <?php } ?>
                            <img src="<?= $album->image_url ?>" alt="<?= $album->name ?>"/>
                            <div class="mask">
                                <a href="#" class="get_galleries" data-id="<?= $album->id ?>"
                                   data-hover="<?= $hover_class ?>">
                                    <div class="mask-text">
                                        <?php if (in_array(Photo_Gallery_WP()->settings->album_masonry_show_title, array('true', 'yes'))) { ?>
                                            <h2><?= $album->name ?></h2>
                                        <?php }
                                        if (in_array(Photo_Gallery_WP()->settings->album_masonry_show_description, array('true', 'yes'))) { ?>
                                            <span class="text-category"><?= $album->description ?></span>
                                        <?php } ?>
                                    </div>
                                </a>
                                <?php /*if (in_array(Photo_Gallery_WP()->settings->album_masonry_show_sharing_buttons, array('true', 'yes'))) { ?>
                                <div class="album_socials"></div>
                            <?php } */ ?>
                                <a href="#" class="get_galleries" data-id="<?= $album->id ?>"
                                   data-hover="<?= $hover_class ?>">
                                    <div class="mask-bg"></div>
                                </a>
                            </div>
                        </div>
                        </a>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
</div>

<?php if ($hover_class == "view-fifth") { ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery(' #album_list > .view-fifth ').each(function () {
                jQuery(this).hoverdir();
            });
        });
    </script>
<?php } ?>

<script type="text/javascript">
    jQuery(function () {
        var filterList = {
            init: function () {
                jQuery('#album_list').mixItUp({
                    selectors: {
                        target: '.view',
                        filter: '.filter'
                    }
                });
            }
        };
        filterList.init();
    });


    jQuery(document).ready(function () {
        alert("hapa");
        jQuery("#album_all_categories").addClass("active");
        // jQuery("#album_list").mosaicflow();
    })


</script>

