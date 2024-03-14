<?php
if (!defined('ABSPATH')) {
    die('Do not open this file directly.');
}
?><form method="post" action="<?php echo admin_url( "admin-post.php" ); ?>">
<h3><?php esc_attr_e('Meta Viewer','order-export-and-more-for-woocommerce'); ?></h3>
<p class="instructions"><?php esc_attr_e('You can view meta data that is associated with a Product or Order.','order-export-and-more-for-woocommerce'); ?></p>
<p><a href="https://jem-products.com/blog/knowledgebase/using-meta-data-viewer/" target='_blank'><?php esc_attr_e('See the documentation here','order-export-and-more-for-woocommerce'); ?> </a></p>
<div>
	<label for="meta_id"><?php esc_attr_e('Product/Order ID','order-export-and-more-for-woocommerce'); ?></label>
	<input type="text" size="25" name="meta_id">
	<input type="radio" name="meta_type" value="product" checked><?php esc_attr_e('Product','order-export-and-more-for-woocommerce'); ?> &nbsp;&nbsp;
	<input type="radio" name="meta_type" value="order"><?php esc_attr_e('Order','order-export-and-more-for-woocommerce'); ?>

</div>
    <p class="submit">
        <input type="submit" name="submit" id="submit" class="btn btn-primary jem-dark-blue" value="View Meta">
    </p>
    <input type="hidden" name="action" value="update_meta">
    <input type="hidden" name="_wp_http_referer" value="<?php esc_attr_e(urlencode($_SERVER['REQUEST_URI'])); ?>">
<?php
    if($this->message != ""){
        JEMEXP_lite::wp_kses_wf($this->message);
    }

?>
    <TABLE class="jemxp-meta-table" style="font-family:monospace; text-align:left; width:100%;">
    <?php JEMEXP_lite::wp_kses_wf($html); ?>

    </TABLE>
    <TABLE class="jemxp-meta-table" style="">
        <?php JEMEXP_lite::wp_kses_wf($line_item_html); ?>

    </TABLE>
</form>




