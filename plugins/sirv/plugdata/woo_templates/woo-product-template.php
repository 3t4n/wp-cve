<?php defined('ABSPATH') or die('No script kiddies please!'); ?>

<style>
  .sirv-skeleton-wrapper {
    position: relative;
    padding-top: 87%;
    height: auto;
    margin-top: 5px;
  }

  .sirv-smv-container {
    position: absolute;
    width: 100%;
    top: 0;
    padding: 0;
    height: 100%;
  }

  .sirv-skeleton {
    background-repeat: no-repeat;
    background-image:
      linear-gradient(#c7c6c6cc 100%, transparent 0),
      linear-gradient(#c7c6c6cc 100%, transparent 0),
      linear-gradient(#c7c6c6cc 100%, transparent 0),
      linear-gradient(#c7c6c6cc 100%, transparent 0),
      linear-gradient(#c7c6c6cc 100%, transparent 0),
      linear-gradient(#fdfdfdcc 100%, transparent 0);
    background-size:
      100% 70%,
      /* main image */
      20% 70px,
      /* selector 1 */
      20% 70px,
      /* selector 2 */
      20% 70px,
      /* selector 3 */
      20% 70px,
      /* selector 4 */
      100% 100%;
    /* container */
    background-position:
      0 0,
      /* main image */
      10% 95%,
      /* selector 1 */
      37% 95%,
      /* selector 2 */
      64% 95%,
      /* selector 3 */
      91% 95%,
      /* selector 4 */
      0 0;
    /* container */
  }

  .sirv-woo-wrapper {
    width: 100%;
    height: 100%;
    max-width: 100%;
    max-height: 100%;
  }

  @media only screen and (max-width: 768px) {
    .sirv-woo-wrapper {
      width: 100% !important;
    }
  }

  .sirv-woo-smv-caption {
    width: 100%;
    min-height: 25px;
    margin: 5px 0 5px;
    font-size: 18px;
    line-height: initial;
    font-weight: bold;
    text-align: center;
  }

  .sirv-woo-opacity-zero {
    opacity: 0;
  }

  .sirv-woo-opacity {
    opacity: 1;
    transition: all 0.1s;
  }
</style>

<?php

function sirv_sanitize_custom_styles($data)
{
  $string = $data;
  $string = str_replace('\r', "", $string);
  $string = str_replace('\n', "", $string);

  return $string;
}

require_once(dirname(__FILE__) . '/../includes/classes/woo.class.php');

global $post;

$woo = new Woo($post->ID);
$woo->add_frontend_assets();

$custom_styles_data = get_option('SIRV_WOO_MV_CONTAINER_CUSTOM_CSS');
$skeleton_option = get_option('SIRV_WOO_MV_SKELETON');
$isSkeleton = $skeleton_option == '1' ? true : false;
$custom_styles = !empty($custom_styles_data) ? 'style="' . sirv_sanitize_custom_styles($custom_styles_data) . '"' : '';

$custom_classes_option = get_option("SIRV_WOO_CONTAINER_CLASSES");
$custom_classes_attr = !empty($custom_classes_option) ? $custom_classes_option : '';

$skeletonClass = $isSkeleton ? ' sirv-skeleton ' : '';
?>

<div class="sirv-woo-wrapper<?php echo $custom_classes_attr; ?>" <?php echo $custom_styles; ?>>
  <div class="sirv-skeleton-wrapper">
    <div class="sirv-smv-container <?php echo $skeletonClass; ?>">
      <?php echo $woo->get_woo_product_gallery_html(); ?>
    </div>

  </div>

  <div class="sirv-after-product-smv-wrapper">
    <?php do_action('sirv_after_product_smv'); ?>
  </div>
</div>
