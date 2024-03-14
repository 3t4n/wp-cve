<?php
/**
 * @var $galleryID int
 * @var $total int
 * @var $num int
 * @var $disp_type string
 * @var $view_slug string
 * @var $gallery_default_params
 * @var $pID int
 * @var $uxgallery_get_option array
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>
<section id="uxgallery_container_<?php echo $galleryID; ?>"
         data-image-object-name="<?php echo "gallery_images_obj_" . $galleryID; ?>">
    <div id="uxgallery_content_<?php echo $galleryID; ?>"
         class="gallery-img-content elastic_grid view-<?php echo $view_slug ?>"
         data-pages-count="<?php echo esc_attr($total); ?>"
         data-content-per-page="<?php echo esc_attr($num); ?>"
         data-current-page="2"
         data-image-behaviour="<?php echo esc_attr($uxgallery_get_option['uxgallery_ht_view10_image_behaviour']); ?>">

    </div>
    <?php
    if ($disp_type == 0) :
        ?>
        <div class="paginate">
            <?php
            $protocol = stripos($_SERVER['SERVER_PROTOCOL'], 'https') === true ? 'https://' : 'http://';
            $actual_link = esc_url($protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "");
            $checkREQ = '';
            $pattern = "/\?p=/";
            $pattern2 = "/&page-img[0-9]+=[0-9]+/";
            $pattern3 = "/?page-img[0-9]+=[0-9]+/";
            if ($total != 1) {
                if (preg_match($pattern, $actual_link)) {
                    if (preg_match($pattern2, $actual_link)) {
                        $actual_link = preg_replace($pattern2, '', $actual_link);
                    }
                    $checkREQ = $actual_link . '&page-img' . $galleryID . $pID;
                } else {
                    $checkREQ = '?page-img' . $galleryID . $pID;
                }
                $pervpage = '';
                if ($page != 1) {
                    $pervpage = '<a href= ' . $checkREQ . '=1><i class="icon-style uxgallery-icons-fast-backward" ></i></a>  
			      <a href= ' . $checkREQ . '=' . ($page - 1) . '><i class="icon-style uxgallery-icons-chevron-left"></i></a> ';
                }
                $nextpage = '';
                if ($page != $total) {
                    $nextpage = ' <a href= ' . $checkREQ . '=' . ($page + 1) . '><i class="icon-style uxgallery-icons-chevron-right"></i></a>  
			      <a href= ' . $checkREQ . '=' . $total . '><i class="icon-style uxgallery-icons-fast-forward" ></i></a>';
                }
                echo $pervpage . $page . '/' . $total . $nextpage;
            }
            ?>
        </div>
        <?php
    endif;
    ?>
</section>