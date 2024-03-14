<?php
/**
 * @package   Easy_Related_Posts_Templates_Widget
 * @author Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
$containerClass = $uniqueID . 'Container';
$thumbClass = $uniqueID . 'Thumbnail';
$titleClass = $uniqueID . 'PostTitle';
$excClass = $uniqueID . 'Exc';
if ($options['thumbCaption'] && in_array('thumbnail', $optionsObj->getContentPositioning())){
	$showThumbCaptions = true;
} else {
	$showThumbCaptions = false;
}
?>
<ul>
    <?php
    if (isset($posts)) {
        foreach ($posts as $k => $v) {
            ?>
            <li class="row"
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
            </li>
            <?php
        } // foreach ($posts as $k => $v)
    } // if (isset($posts))
    ?>
</ul>
<?php
if ($showThumbCaptions) {
    ?>
    <script type="text/javascript">
        (function($) {
            $(function() {
                $(window).load(function() {
                    $('.<?php echo $thumbClass; ?>').captionjs({
                        'class_name': 'erpProcaptionjs', // Class name assigned to each <figure>
                        'schema': false, // Use schema.org markup (i.e., itemtype, itemprop)
                        'mode': 'animated', // default | static | animated | hide
                        'debug_mode': false, // Output debug info to the JS console
                        'force_dimensions': false // Force the dimensions in case they can't be detected (e.g., image is not yet painted to viewport)
                    });
                });
            });
        }(jQuery));

    </script>
    <?php
}
?>