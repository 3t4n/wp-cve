<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery('#uxgalleryinsert').on('click', function () {
            var id = jQuery('#uxgallery-select option:selected').val();
            window.send_to_editor('[uxgallery id="' + id + '"]');
            tb_remove();
        })
    });
</script>

<div id="uxgallery" style="display: none;width: 500px">

    <h3><?php echo __('Select UX Gallery to insert into post', 'gallery-img'); ?></h3>
    <?php
    global $wpdb;
    $query = "SELECT * FROM " . $wpdb->prefix . "ux_gallery_gallerys order by id ASC";
    $shortcodegallerys = $wpdb->get_results($query);
    ?>
    <?php if (count($shortcodegallerys)) {
        echo "<select id='uxgallery-select'>";
        foreach ($shortcodegallerys as $shortcodegallery) {
            echo "<option value='" . $shortcodegallery->id . "'>" . $shortcodegallery->name . "</option>";
        }
        echo "</select>";
        echo "<button class='button primary' id='uxgalleryinsert'>Insert gallery</button>";
    } else {
        echo "No slideshows found", "uxgallery";
    }
    ?>
</div>
<div id="uxgallery-2" style="display: none;width: 500px">
    <h3><?php echo __('Select UX Albums to insert into post', 'gallery-img'); ?></h3>
    <div>

        <div class="ph-g-wp-gallery-tbl-head">
            <div class="ph-g-wp-inline"><?php echo __('N', 'gallery-img'); ?></div>
            <div class="ph-g-wp-inline"><?php echo __('Title', 'gallery-img'); ?></div>
            <div class="ph-g-wp-inline"><?php echo __('Items Count', 'gallery-img'); ?></div>
        </div>
        <?php
        foreach ($shortcodealbums as $album) { ?>
            <div class="shortcode_album_list" data-shortcode="<?php echo $album->id ?>" style="padding: 5px 0">
                <div class="ph-g-wp-inline">
                    <input type="checkbox" name="list[]" class="uxgallery_item"
                           value="<?= $album->id ?>">
                </div>

                <div class="ph-g-wp-inline"><?php echo $album->name ?></div>
                <div class="ph-g-wp-inline">(<?php echo $album->galleries_count ?>)</div>
            </div>
        <?php } ?>
    </div>
    <div style="clear: both;border-bottom: 1px solid #CCC;margin-bottom: 5px;"></div>
    <div class="ux_shortcode_album_style" style="display: none;">
        <label for="image_gallery_wp_album_style"><?= __('Select View', 'photo-gallery-wp') ?></label>
        <select name="image_gallery_wp_album_style" id="image_gallery_wp_album_style">
            <option value="1">
                1. Gallery/Content-Popup
            </option>
            <option value="2">
                2. Lightbox-Gallery
            </option>
            <option value="3" selected="selected">
                3. Thumbnails View
            </option>
            <!--<option value="4">
                4. Masonry
            </option>
            <option value="5">
                5. Mosaic
            </option>-->
        </select>
    </div>
    <div class="ux_popup_footer">
        <button class='button-primary ph-g-wp-popup-btn'
                id="ux_images_gallery_insert_album"
                disabled><?php echo __('Insert Album', 'gallery-img'); ?></button>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var list = [];
        $(".uxgallery_item").change(function () {
            if ($(this).prop("checked") === true) {
                list.push($(this).val());
            }
            else {
                var index = list.indexOf($(this).val());
                list.splice(index, 1);
            }

            if (list.length > 0) {
                $("#ux_images_gallery_insert_album").removeAttr("disabled");
            }
            else {
                $("#ux_images_gallery_insert_album").attr("disabled", "disabled");
            }
            if (list.length > 1) {
                $(".ux_shortcode_album_style").show();
            }
            else {
                $(".ux_shortcode_album_style").hide();
            }
        });

        $('#ux_images_gallery_insert_album').on('click', function () {
            if (list.length > 1) {
                var selected_style = $("#image_gallery_wp_album_style").val();
                window.send_to_editor('[uxgallery_album id="' + list.join() + '" style="' + selected_style + '"]');
                tb_remove();
            }
            else if (list.length == 1) {
                window.send_to_editor('[uxgallery_album id="' + list.join() + '"]');
                tb_remove();
            }
            else {
                alert("Select at least one please");
            }
        });
    });

</script>
<style>
    .ph-g-wp-inline {
        display: inline-block !important;
        vertical-align: middle;
        width: 30%;
    }

    #ux_images_gallery_insert_album {
        float: right;
    }

    .ux_shortcode_album_style {
        float: left;
    }
</style>