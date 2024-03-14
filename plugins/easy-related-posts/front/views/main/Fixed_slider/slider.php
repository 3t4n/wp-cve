<?php
/**
 * @title Fixed slider
 * @description This is the template description
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
$containerClass = $uniqueID . 'Container';
$thumbClass = $uniqueID . 'Thumbnail';
$titleClass = $uniqueID . 'PostTitle';
$excClass = $uniqueID . 'Exc';
if ($options['thumbCaption'] && in_array('thumbnail', $optionsObj->getContentPositioning()) ){
	$showThumbCaptions = true;
} else {
	$showThumbCaptions = false;
}
?>
<div class="metroW" style="width: 100%; z-index:100; "></div>
<div class="erpProContainer <?php echo $containerClass; ?>">
    <noscript><h2 class="erpProTitle" style="line-height: 1.4;"><?php if (isset($title)) echo $title; ?></h2></noscript>
    <div class="container-fluid">
        <?php
        if (isset($posts)) {
            foreach ($posts as $k => $v) {
                if ($k % $options['numOfPostsPerRow'] === 0) {
                    ?>
                    <div class="row">
                        <?php
                    }
                    ?>
                    <div class="col-md-<?php echo 12 / $options['numOfPostsPerRow']; ?>"
                    <?php
                    if (current_user_can('activate_plugins')) {
                        echo 'title="Rating: ' . $v->getRating() . ' Post date: ' . $v->getTheTime() . '"';
                    }
                    ?>
                         >
                        <a href="<?php echo $v->getPermalink() ?>" class="erpProPostLink">
                            <?php
                            foreach ($optionsObj->getContentPositioning() as $key => $value) {
                                include plugin_dir_path(__FILE__) . 'components/' . $value . '.php';
                            }
                            ?>
                        </a>
                    </div>
                    <?php
                    if ($k % $options['numOfPostsPerRow'] + 1 === $options['numOfPostsPerRow'] || count($posts) === $k + 1) {
                        ?>
                    </div>
                    <div class="cf"></div>
                    <?php
                }
                ?>
                <?php
            } // foreach ($posts as $k => $v)
        } // if (isset($posts))
        ?>
    </div>
</div>
<script type="text/javascript">
    (function($) {
        $(function() {
            $(window).load(function() {
<?php
if ($showThumbCaptions) {
    ?>
                    $('.<?php echo $thumbClass; ?>').captionjs({
                        'class_name': 'erpProcaptionjs', // Class name assigned to each <figure>
                        'schema': false, // Use schema.org markup (i.e., itemtype, itemprop)
                        'mode': 'animated', // default | static | animated | hide
                        'debug_mode': false, // Output debug info to the JS console
                        'force_dimensions': false        // Force the dimensions in case they can't be detected (e.g., image is not yet painted to viewport)
                    });
    <?php
}
?>

                slider = new ERPSlider(
                        "<?php echo $position; ?>",
                        "<?php echo $backgroundColor; ?>",
<?php echo $triggerAfter; ?>,
<?php echo $backgroundTransparency; ?>,
                        $('.<?php echo $containerClass; ?>'),
                        '<?php echo $title; ?>');

                slider.sliderInitializer();
                slider.buttons();
                slider.erpToggler();

            });
        });
    }(jQuery));
</script>