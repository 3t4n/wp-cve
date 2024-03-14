<?php
$paged = $query->query_vars['paged'];
if (!$paged) {
    $paged = 1;
}
$next_page = intval($paged) + 1;
if ( $next_page > $query->max_num_pages) {
    $next_page = 0;
}
$prev_page = $paged - 1;

if ( $prev_page || $next_page ) {
    ?>
    <div class="nav-tab-paging">
        <a href="#" data-paged="<?php echo esc_attr($prev_page); ?>" class="tab-paging back-page <?php echo ($prev_page > 0) ? 'active' : 'disable'; ?>"><span class="screen-reader-text"><?php esc_html_e( 'Previous', 'megamenu-wp' ) ; ?></span></a>
        <a href="#" data-paged="<?php echo esc_attr( $next_page ); ?>" class="tab-paging next-page <?php echo ($next_page) ? 'active' : 'disable'; ?>"><span class="screen-reader-text"><?php esc_html_e( 'Next', 'megamenu-wp' ) ; ?></span></a>
    </div>
    <?php
}