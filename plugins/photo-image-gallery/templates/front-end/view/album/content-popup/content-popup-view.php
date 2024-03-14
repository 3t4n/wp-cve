<?php
wp_register_style('album-popup-css', UXGallery()->plugin_url() . "/assets/albums/style/album-popup.css");
wp_enqueue_style('album-popup-css');

wp_register_script('album-popup-view-js', UXGallery()->plugin_url() . '/assets/albums/js/album_popup_view.js', array('jquery'), '1.0.0', true);
wp_enqueue_script('album-popup-view-js');

switch (get_option("uxgallery_album_popup_onhover_effects")) {
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


$album_share = 1;

$mosaic_count = 1;
$mosaic = 1;

$cat_class = array();
$cat_class_all = array();

foreach ($album_categories as $val) {
    $cat_class_all[] = ".ux_cat_" . $val->id;
}

$mosaic_width = get_option("uxgallery_ht_view2_element_width");
$show_title = (in_array(get_option("uxgallery_album_popup_show_title"), array("yes", "true", "on"))) ? "yes" : "no";
$show_desc = (in_array(get_option("uxgallery_album_popup_show_description"), array("yes", "true", "on"))) ? "yes" : "no";
?>

<input type="hidden" name="mosaic" value="<?= $mosaic ?>">
<input type="hidden" name="mosaic_count" value="<?= $mosaic_count ?>">
<input type="hidden" name="mosaic_width" value="<?= $mosaic_width ?>">
<input type="hidden" name="show_title" value="<?= $show_title ?>">
<input type="hidden" name="show_desc" value="<?= $show_desc ?>">

<div id="main" style="display: inline-block; width:100%;">
    <div id="album_list_container">
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
            <?php
            foreach ($albums as $key => $val) {
                foreach ($val->galleries as $album) { ?>
                    <div class="view <?= $hover_class; ?>  <?php echo implode(" ", $album->cat_class); ?> ux_cat_0">
                        <div class="<?= $hover_class; ?>-wrapper view-wrapper">
                            <?php if (in_array(get_option("uxgallery_album_popup_show_image_count_2"), array('true', 'yes', 'on'))) { ?>
                                <span class="album_images_count"><?= $album->image_count ?><br><span
                                            class="count_image">Images</span></span>
                            <?php } ?>
                            <a href="#" class="uxphoto-album-gallery-<?= $album->id ?> uxphoto-gallery-link">
                                <img src="<?= $album->image_url ?>" alt="<?= $album->name ?>"/>
                                <div class="mask">
                                    <a href="#" class="uxphoto-album-gallery-<?= $album->id ?> uxphoto-gallery-link">
                                        <div class="mask-text">
                                            <?php if ($show_title == "yes") { ?>
                                                <h2><?= $album->name ?></h2>
                                            <?php }
                                            if ($show_desc == "yes") { ?>
                                                <span class="text-category"><?= $album->description ?></span>
                                            <?php } ?>
                                        </div>

                                        <a href="#"
                                           class="uxphoto-album-gallery-<?= $album->id ?> uxphoto-gallery-link">
                                            <div class="mask-bg"></div>
                                        </a>
                                </div>
                            </a>
                        </div>
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
        jQuery("#uxphotobox-thumbs").hide();

        jQuery(document).ready(function () {
            jQuery("#album_all_categories").addClass("active");
        })

        var shareButtons = '<ul class="uxmodernsl-share-buttons" style="display: block;">';
        shareButtons += '<li><a title="Facebook" class="album_social_fb" id="uxmodernsl-share-facebook" target="_blank"></a></li>';
        shareButtons += '<li><a title="Twitter" class="album_social_twitter" id="uxmodernsl-share-twitter" target="_blank"></a></li>';
        shareButtons += '<li><a title="Google Plus" class="album_social_google" id="uxmodernsl-share-googleplus" target="_blank"></a></li>';
        shareButtons += '</ul>';

        jQuery(".album_socials").append(shareButtons);

        jQuery('.album_social_fb').attr('href', 'https://www.facebook.com/sharer/sharer.php?u=' + (encodeURIComponent(document.URL)));
        jQuery('.album_social_twitter').attr('href', 'https://twitter.com/intent/tweet?text=&url=' + (encodeURIComponent(document.URL)));
        jQuery('.album_social_google').attr('href', 'https://plus.google.com/share?url=' + (encodeURIComponent(document.URL)));
    });
</script>

<script type="text/javascript">
    var uxphoto_albums_galleries = [],
        uxphoto_albums_galleries_images = {},
        uxphoto_albums_isotopes = [],
        uxphoto_albums_isotopes_config = [],
        uxphoto_albums_sort = {"56458": false, "56464": false, "56462": false};

    jQuery(document).ready(function ($) {

        $(".ux_album_item").click(function () {
        });

        var uxphoto_container_56458 = '';

        uxphoto_container_56458 = $('#uxphoto-gallery-56458').uxphotoImagesLoaded(function () {
            $('.uxphoto-gallery-item img').show();
        });

        <?php
        foreach ($albums as $key => $album){


        foreach($album->galleries as $k => $gallery){

        echo "uxphoto_albums_galleries_images[$gallery->id] = [];";
        foreach($gallery->images as $k_img => $image){
        if ($image->sl_type == "video") {
            $videourl = uxgallery_get_video_id_from_url($image->image_url);
            if ($videourl[1] == 'youtube') {
                $thumb = "https://img.youtube.com/vi/" . esc_html($videourl[0]) . "/mqdefault.jpg";
            } else {
                $hash = unserialize(wp_remote_fopen("https://vimeo.com/api/v2/video/" . $videourl[0] . ".php"));
                $imgsrc = $hash[0]['thumbnail_large'];
                $thumb = esc_attr($imgsrc);
            }
        } else {
            $thumb = esc_attr($image->image_url);
        }
        $image->image_url = $thumb;
        ?>

        uxphoto_albums_galleries_images[<?= $gallery->id ?>].push({
            href: '<?= $image->image_url ?>',
            id: <?= $album->id ?>+<?= $gallery->id ?> + <?= $image->id ?>,
            gallery_id: <?= $gallery->id ?>,
            alt: '',
            caption: '',
            title: "<?= $image->name ?>",
            index: <?= $k_img ?>,
            thumbnail: '<?= $image->image_url ?>',
            mobile_thumbnail: '<?= $image->image_url ?>'
        });

        <?php }
        ?>


        $(document).on('click', '.uxphoto-album-gallery-<?= $gallery->id ?>', function (e) {
            e.preventDefault();

            uxphoto_albums_galleries['<?= $gallery->id ?>'] = $.uxphotobox.open(uxphoto_albums_galleries_images['<?= $gallery->id ?>'], {
                lightboxTheme: 'base',
                margin: 40,
                padding: 15,
                arrows: 1,
                aspectRatio: 1,
                loop: 1,
                mouseWheel: 1,
                preload: 1,
                openEffect: 'none',
                closeEffect: 'none',
                nextEffect: 'fade',
                prevEffect: 'fade',
                tpl: {
                    wrap: '<div class="uxphotobox-wrap" tabIndex="-1"><div class="uxphotobox-skin uxphotobox-theme-base"><div class="uxphotobox-outer"><div class="uxphotobox-inner"><div class="uxphotobox-actions base "></div><div class="uxphotobox-position-overlay uxphoto-gallery-top-left"></div><div class="uxphotobox-position-overlay uxphoto-gallery-top-right"></div><div class="uxphotobox-position-overlay uxphoto-gallery-bottom-left"></div><div class="uxphotobox-position-overlay uxphoto-gallery-bottom-right"></div></div></div></div></div>',
                    image: '<img class="uxphotobox-image" src="{href}" alt="" data-uxphoto-title="" data-uxphoto-caption="" data-uxphoto-index="" data-uxphoto-data="" />',
                    iframe: '<iframe id="uxphotobox-frame{rnd}" name="uxphotobox-frame{rnd}" class="uxphotobox-iframe" frameborder="0" vspace="0" hspace="0" allowtransparency="true"\></iframe>',
                    error: '<p class="uxphotobox-error">The requested content cannot be loaded.<br/>Please try again later.</p>',
                    closeBtn: '<a title="Close" class="uxphotobox-item uxphotobox-close" href="javascript:;"></a>',
                    next: '<a title="Next" class="uxphotobox-nav uxphotobox-next uxphotobox-arrows-inside" href="javascript:;"><span></span></a>',
                    prev: '<a title="Previous" class="uxphotobox-nav uxphotobox-prev uxphotobox-arrows-inside" href="javascript:;"><span></span></a>'
                },
                helpers: {
                    video: {
                        autoplay: 0,
                        playpause: 1,
                        progress: 1,
                        current: 1,
                        duration: 1,
                        volume: 1,
                    },
                    title: {
                        type: 'float',
                        alwaysShow: '',
                    },
                    thumbs: {
                        width: 75,
                        height: 50,
                        mobile_thumbs: 1,
                        mobile_width: 75,
                        mobile_height: 50,
                        source: function (current) {
                            /* current is our images_id array object */
                            return current.thumbnail;
                        },
                        mobileSource: function (current) {
                            /* current is our images_id array object */
                            return current.mobile_thumbnail;
                        },
                        dynamicMargin: false,
                        dynamicMarginAmount: 0,
                        position: 'bottom',
                    },
                    buttons: {
                        tpl: '<div id="uxphotobox-buttons"><ul><li><a class="btnPrev" title="Previous" href="javascript:;"></a></li><li><a class="btnNext" title="Next" href="javascript:;"></a></li><li><a class="btnClose" title="Close" href="javascript:;"></a></li></ul></div>',
                        position: 'top',
                        padding: ''
                    },
                    navDivsRoot: false,
                    actionDivRoot: false,
                },
                beforeLoad: function () {
                    if (typeof uxphoto_albums_galleries_images['<?= $gallery->id ?>'][this.index].caption !== 'undefined') {
                        this.title = uxphoto_albums_galleries_images['<?= $gallery->id ?>'][this.index].caption;
                    } else {
                        this.title = uxphoto_albums_galleries_images['<?= $gallery->id ?>'][this.index].title;
                    }
                },
                afterLoad: function (current, previous) {
                },
                beforeShow: function () {

                    $(window).on({
                        'resize.uxphotobox': function () {
                            $.uxphotobox.update();
                        }
                    });

                    /* Set alt, data-uxphoto-title, data-uxphoto-caption and data-uxphoto-index attributes on Lightbox image */
                    $('img.uxphotobox-image').attr('alt', uxphoto_albums_galleries_images['<?= $gallery->id ?>'][this.index].alt)
                        .attr('data-uxphoto-gallery-id', '<?= $gallery->id ?>')
                        .attr('data-uxphoto-item-id', uxphoto_albums_galleries_images['<?= $gallery->id ?>'][this.index].id)
                        .attr('data-uxphoto-title', uxphoto_albums_galleries_images['<?= $gallery->id ?>'][this.index].title)
                        .attr('data-uxphoto-caption', uxphoto_albums_galleries_images['<?= $gallery->id ?>'][this.index].caption)
                        .attr('data-uxphoto-index', this.index);

                    $('.uxphotobox-overlay').addClass('uxphotobox-thumbs');


                    $('.uxphotobox-overlay').addClass('overlay-video');

                    var overlay_supersize = false;
                    if (overlay_supersize) {
                        $('.uxphotobox-overlay').addClass('overlay-supersize');
                        $('#uxphotobox-thumbs').addClass('thumbs-supersize');
                    }
                    $('.uxphoto-close').click(function (event) {
                        event.preventDefault();
                        $.uxphotobox.close();
                    });
                    $('.uxphotobox-overlay').addClass('overlay-video');
                },
                afterShow: function (i) {
                    $('.uxphotobox-wrap').swipe({
                        swipe: function (event, direction, distance, duration, fingerCount, fingerData) {
                            if (direction === 'left') {
                                $.uxphotobox.next(direction);
                            } else if (direction === 'right') {
                                $.uxphotobox.prev(direction);
                            } else if (direction === 'up') {
                            }
                        }
                    });

                    var overlay_supersize = false;
                    if (overlay_supersize) {
                        $('#uxphotobox-thumbs').addClass('thumbs-supersize');
                    }


                    if ($('#uxphoto-gallery-wrap-56458 div.uxphoto-pagination').length > 0) {
                        var uxphotobox_page = ( $('#uxphoto-gallery-wrap-56458 div.uxphoto-pagination').data('page') );
                    } else {
                        var uxphotobox_page = 0;
                    }
                    this.inner.find('img').attr('data-pagination-page', uxphotobox_page);
                    // console.log (uxphotobox_page);

                },
                beforeClose: function () {
                },
                afterClose: function () {
                    $(window).off('resize.uxphotobox');
                },
                onUpdate: function () {
                    var uxphoto_buttons_56458 = $('#uxphotobox-buttons li').map(function () {
                            return $(this).width();
                        }).get(),
                        uxphoto_buttons_total_56458 = 0;

                    $.each(uxphoto_buttons_56458, function (i, val) {
                        uxphoto_buttons_total_56458 += parseInt(val, 10);
                    });
                    uxphoto_buttons_total_56458 += 1;

                    $('#uxphotobox-buttons ul').width(uxphoto_buttons_total_56458);
                    $('#uxphotobox-buttons').width(uxphoto_buttons_total_56458).css('left', ($(window).width() - uxphoto_buttons_total_56458) / 2);
                },
                onCancel: function () {
                },
                onPlayStart: function () {
                },
                onPlayEnd: function () {
                }
            });

            $('.uxphotobox-overlay').addClass('overlay-base');

        });

        <?php
        }
        }
        ?>

        uxphoto_albums_isotopes['56464'] = uxphoto_container_56464 = $('#uxphoto-gallery-56464').uxphototope(uxphoto_albums_isotopes_config['56464']);
        uxphoto_albums_isotopes['56464'].uxphotoImagesLoaded()
            .done(function () {
                uxphoto_albums_isotopes['56464'].uxphototope('layout');
            })
            .progress(function () {
                uxphoto_albums_isotopes['56464'].uxphototope('layout');
            });
        uxphoto_container_56464 = $('#uxphoto-gallery-56464').uxphotoImagesLoaded(function () {
            $('.uxphoto-gallery-item img').fadeTo('slow', 1);
        });
        var uxphoto_container_56462 = '';


        uxphoto_container_56462 = $('#uxphoto-gallery-56462').uxphotoImagesLoaded(function () {
            $('.uxphoto-gallery-item img').fadeTo('slow', 1);
        });

        $('.simplefilter li').click(function () {
            $('.simplefilter li').removeClass('active');
            $(this).addClass('active');
        });
        //Multifilter controls
        $('.multifilter li').click(function () {
            $(this).toggleClass('active');
        });
        //Shuffle control
        $('.shuffle-btn').click(function () {
            $('.sort-btn').removeClass('active');
        });
        //Sort controls
        $('.sort-btn').click(function () {
            $('.sort-btn').removeClass('active');
            $(this).addClass('active');
        });
    });

</script>
