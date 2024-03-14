<p class="search-box">
<input type="search" id="ti-woocommerce-order-search" name="s" href="<?php echo $page_url .'&pi=1&q=%s'; ?>" value="<?php echo $list_page_term; ?>">
<input type="submit" class="button" value="<?php echo TrustindexWoocommercePlugin::___('Search'); ?>">
</p>
<div class="tablenav top">
<div class="tablenav-pages">
<span class="pagination-links">
<?php if($list_page_index > 2): ?>
<a class="next-page button" href="<?php echo $page_url .'&pi=1&q='. $list_page_term; ?>">
<span aria-hidden="true">«</span>
</a>
<?php else: ?>
<span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
<?php endif; ?>
<?php if($list_page_index > 1): ?>
<a class="next-page button" href="<?php echo $page_url .'&pi='. ($list_page_index - 1) .'&q='. $list_page_term; ?>">
<span aria-hidden="true">‹</span>
</a>
<?php else: ?>
<span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
<?php endif; ?>
<span class="paging-input">
<input <?php if($results->total == 0): ?>disabled<?php endif; ?> class="current-page" id="ti-woocommerce-page-selector" href="<?php echo $page_url .'&pi=%d&q='. $list_page_term; ?>" max="<?php echo $results->max_num_pages; ?>" type="text" value="<?php echo $list_page_index; ?>" size="1" aria-describedby="table-paging">
<span class="tablenav-paging-text">. / <span class="total-pages"><?php echo $results->max_num_pages; ?></span></span>
</span>
<?php if($list_page_index < $results->max_num_pages): ?>
<a class="next-page button" href="<?php echo $page_url .'&pi='. ($list_page_index + 1) .'&q='. $list_page_term; ?>">
<span aria-hidden="true">›</span>
</a>
<?php else: ?>
<span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
<?php endif; ?>
<?php if($list_page_index < $results->max_num_pages - 1): ?>
<a class="next-page button" href="<?php echo $page_url .'&pi='. $results->max_num_pages .'&q='. $list_page_term; ?>">
<span aria-hidden="true">»</span>
</a>
<?php else: ?>
<span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
<?php endif; ?>
</span>
</div>
<br class="clear">
</div>