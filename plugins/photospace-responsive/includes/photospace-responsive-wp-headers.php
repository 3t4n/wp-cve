<!--	photospace [ START ] -->
<style type="text/css">
<?php if(get_option('psres_reset_css')){ ?>

    /* reset */
    .photospace_res img,
    .photospace_res ul.thumbs,
    .photospace_res ul.thumbs li,
    .photospace_res ul.thumbs li a {
        padding:0;
        margin:0;
        border:none !important;
        background:none !important;
    }
    .photospace_res span {
        padding:0;
        margin:0;
        border:none !important;
        background:none !important;
    }
        
<?php } ?>

.photospace_res ul.thumbs img {
    width: <?php echo intval(get_option('psres_thumbnail_width')) . 'px'; ?>;
    height: <?php echo intval(get_option('psres_thumbnail_height')) . 'px'; ?>;
}

.photospace_res .thumnail_row a.pageLink {
    width: <?php echo intval(get_option('psres_button_size')) . 'px'; ?>;
    height: <?php echo intval(get_option('psres_button_size')) . 'px'; ?>;
    line-height: <?php echo intval(get_option('psres_button_size')) . 'px'; ?>;
}

<?php if(!empty(get_option('psres_thumbnail_margin'))){ ?>
    .photospace_res ul.thumbs li {
        margin-bottom: <?php echo intval(get_option('psres_thumbnail_margin')) . 'px !important'; ?>;
        margin-right: <?php echo intval(get_option('psres_thumbnail_margin')) . 'px !important'; ?>;
    }

    .photospace_res .next,
    .photospace_res .prev {
        margin-right: <?php echo intval(get_option('psres_thumbnail_margin')) . 'px !important'; ?>;
        margin-bottom: <?php echo intval(get_option('psres_thumbnail_margin')) . 'px !important'; ?>;
    }
<?php } ?>

<?php if((bool) get_option('psres_hide_thumbs')){ ?>
    .photospace_res .thumnail_row {
        display:none !important;
    }
<?php } ?>

</style>
<!--	photospace [ END ] --> 
