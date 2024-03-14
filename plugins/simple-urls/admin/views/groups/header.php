<?php

use LassoLite\Classes\Group;
use LassoLite\Classes\Helper;
use LassoLite\Classes\Enum;
use LassoLite\Classes\Page;

$get     = Helper::GET();
$post_id = $get['post_id'] ?? 0;
$page    = $get['page'];
$subpage = $get['subpage'] ?? '';
$allow_add_a_link = false;

// ? Default value
$term_h1          = 'Add a New Group';
$slug             = '';
$create_new_group = true;
$url_count        = 0;
$url_count_text   = '';
$group_id         = 0;
$name             = '';
$description      = '';

if ( $post_id > 0 ) {
	$lasso_group      = Group::get_by_id( $post_id );
	$term_name        = $lasso_group->get_name();
	$term_h1          = $term_name;
	$term_description = $lasso_group->get_description();
	$slug             = $lasso_group->get_slug();
	$create_new_group = false;
	$allow_add_a_link = true;
	if ( Enum::PAGE_GROUP_DETAIL === $page ) {
		$allow_add_a_link = false;
	}
	$url_count        = $lasso_group ? $lasso_group->get_total_links() : 0;
	$url_count_text   = ' <span class="badge px-2 purple-bg white" id="group-badge">' . esc_html( $url_count ) . '</span>';

	$group_id    = $lasso_group->get_id();
	$name        = $lasso_group->get_name();
	$description = $lasso_group->get_description();
}
?>

<!-- TITLE -->
<div class="row align-items-center mb-3">
	<div class="col-lg text-lg-left text-center">
		<h1 class="m-0 mr-2 d-inline-block align-middle"><?php echo $term_h1; ?></h1>
	</div>
</div>

<!-- SUB NAVIGATION & SHORTCODE -->
<div class="row align-items-center mb-4">
	<div class="col-lg">
		<ul class="nav justify-content-lg-start justify-content-center font-weight-bold">
			<?php if ( ! $create_new_group ) { ?>
				<?php
				$links_url  = Page::get_page_url( Helper::add_prefix_page(Enum::PAGE_GROUP_DETAIL . '&post_id=' . $post_id . '&subpage=' . Enum::SUB_PAGE_GROUP_URLS) );
				$detail_url = Page::get_page_url( Helper::add_prefix_page(Enum::PAGE_GROUP_DETAIL . '&post_id=' . $post_id. '&subpage=' . Enum::SUB_PAGE_GROUP_DETAIL) );

				$link_active_class   = Enum::SUB_PAGE_GROUP_URLS === $subpage ? 'active' : '';
				$detail_active_class = Enum::SUB_PAGE_GROUP_DETAIL === $subpage || empty( $subpage ) ? 'active' : '';
				?>
				<li class="nav-item mr-3">
					<a class="nav-link purple hover-underline px-0 <?php echo $link_active_class; ?>" href="<?php echo $links_url ?>">Links<?php echo $url_count_text; ?></a>
				</li>
				<li class="nav-item mx-3">
					<a class="nav-link purple hover-underline px-0 <?php echo $detail_active_class; ?>" href="<?php echo $detail_url ?>">Details</a>
				</li>
			<?php } ?>
		</ul>
	</div>
</div>

