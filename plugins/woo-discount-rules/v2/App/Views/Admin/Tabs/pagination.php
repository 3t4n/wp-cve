<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
?>

<?php
$base_url = '';
if (!empty($name)) {
    $base_url .= '&name='.$name;
}
if ($sort != 0) {
    $base_url .= '&re_order='.$sort;
}
if ($limit ) {
    $base_url .= '&limit='.$limit;
}
$pagination = '';
?>
<?php if($total_page > 1) { ?>
<div>
    <?php if($current_page > 1) { ?>
        <a class="last-page button"  href="?page=woo_discount_rules&page_no=1<?php echo $base_url; ?>"><span class="screen-reader-text"></span>
            <span aria-hidden="true">«</span></a>
        <a class="first-page button" href="?page=woo_discount_rules&page_no=<?php echo $current_page - 1; ?><?php echo $base_url; ?>">
            <span class="screen-reader-text"></span><span aria-hidden="true">‹</span></a>
    <?php } else { ?>
        <span class="tablenav-pages-navspan button disabled">«</span><span class="tablenav-pages-navspan button disabled">‹</span>
    <?php }?>
    <span class="paging-input"><label for="current-page-selector" class="screen-reader-text"></label>
                        <input class="current-page" id="current-page-top" type="text" name="paged" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="<?php echo  esc_attr($current_page); ?>" size="1" aria-describedby="table-paging"><span class="tablenav-paging-text"> <?php _e('Of', 'woo-discount-rules'); ?> <span class="total-pages"><?php echo $total_page; ?></span></span></span>
    <?php if($current_page >= 1 && $current_page < $total_page) { ?>
        <a class="first-page button" href="?page=woo_discount_rules&page_no=<?php echo $current_page + 1; ?><?php echo $base_url; ?>">
            <span class="screen-reader-text"></span><span aria-hidden="true">›</span></a>
        <a class="last-page button"  href="?page=woo_discount_rules&page_no=<?php echo $total_page; ?><?php echo $base_url; ?>"><span class="screen-reader-text"></span>
            <span aria-hidden="true">»</span></a>
    <?php } else { ?>
        <span class="tablenav-pages-navspan button disabled" aria-hidden='true'> › </span><span class="tablenav-pages-navspan button disabled" aria-hidden='true'> » </span>
    <?php }?>
    <?php } ?>

