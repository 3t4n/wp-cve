<?php

namespace ZPOS\Admin\Stations;

use ZPOS\Plugin;
use ZPOS\Station;
use ZPOS_UI\License as UILicense;

class Layout
{
	public function __construct()
	{
		add_filter('screen_layout_columns', [$this, 'singleColumn']);
		add_action('manage_posts_custom_column', [$this, 'actionsColumn'], 10, 2);
		add_filter('get_user_option_screen_layout_' . Post::TYPE, [$this, 'defaultUserSingleColumn']);
		add_action('load-post-new.php', [$this, 'removeColumns']);
		add_action('admin_enqueue_scripts', [$this, 'styles']);

		add_action('admin_menu', [$this, 'removeDefaultBoxes']);
		add_action('edit_form_after_title', [$this, 'addSubmitButton'], 20);

		add_filter('views_edit-pos-station', [$this, 'adminPageViewsEmpty']);
		add_filter('bulk_actions-edit-pos-station', [$this, 'adminPageViewsEmpty']);

		add_action('restrict_manage_posts', [$this, 'hideDateFilter']);
		add_filter('manage_edit-pos-station_columns', [$this, 'tableViewColumns']);
		add_filter('post_row_actions', [$this, 'postActions'], 10, 2);
	}

	public function styles()
	{
		if (\get_current_screen()->id !== 'edit-' . Post::TYPE) {
			return;
		}

		$data = <<<CSS
		.post-type-pos-station .manage-column.column-actions, .post-type-pos-station .actions.column-actions {
			text-align: right;
		}
		.title.column-title {
			vertical-align: middle;
		}
		.post-type-pos-station .actions.column-actions a.button {
			min-height: 0;
			line-height: 20px;
			padding: 4px;
		}
		@media screen and (max-width: 782px) {
			.post-type-pos-station .manage-column.column-actions, .post-type-pos-station .actions.column-actions {
				text-align: left;
			}
			.post-type-pos-station .wp-list-table tr,
			.post-type-pos-station .wp-list-table tr td,
			.post-type-pos-station .wp-list-table tr th {
				display: block;
			} 
			.post-type-pos-station .wp-list-table tr th.manage-column.column-actions {
				display: none;
			}
			.post-type-pos-station .wp-list-table tr td.column-primary ~ td.actions.column-actions {
				display: block;
			}
			.post-type-pos-station .wp-list-table tr td.column-primary ~ td.actions.column-actions {
				padding: 8px 10px;
			} 
			.post-type-pos-station .wp-list-table tr td.column-primary ~ td.actions.column-actions:before {
				display: none;
			}
		}
CSS;
		wp_add_inline_style('edit', $data);
	}

	public function actionsColumn($column, $post)
	{
		if ($column !== 'actions') {
			return;
		}
		if (current_user_can('manage_woocommerce_pos', $post)) { ?>
			<a
				class="button"
				href="<?= get_edit_post_link($post) ?>"
				aria-label="<?= __('Edit Station', 'zpos-wp-api') ?>"
			>
				<span class="dashicons dashicons-edit"></span>
				<?= __('Edit Station', 'zpos-wp-api') ?>
			</a>
		<?php }
		if (current_user_can('access_woocommerce_pos', $post)) {
			$permalink = Station::getURL($post); ?>
			<a
				<?php echo !Plugin::isActive('pos-ui') || UILicense::isActive()
    	? ''
    	: 'disabled onclick="event.preventDefault()"'; ?>
				class="button"
				href="<?= $permalink ?>"
				target="_blank"
				rel="noreferrer noopener"
				aria-label="<?= __('View POS', 'zpos-wp-api') ?>"
			>
				<span class="dashicons dashicons-align-full-width"></span>
				<?= __('View POS', 'zpos-wp-api') ?>
			</a>
		<?php
		}
	}

	public function postActions($actions, $post)
	{
		if ($post->post_type === Post::TYPE) {
			return [];
		}
		return $actions;
	}

	public function tableViewColumns($columns)
	{
		$columns = [
			'title' => __('Title', 'zpos-wp-api'),
			'actions' => __('Actions', 'zpos-wp-api'),
		];
		return $columns;
	}

	public function singleColumn($columns)
	{
		$columns[Post::TYPE] = 1;
		return $columns;
	}

	public function adminPageViewsEmpty()
	{
		return [];
	}

	public function defaultUserSingleColumn()
	{
		return 1;
	}

	public function removeColumns()
	{
		if (\get_current_screen()->id !== Post::TYPE) {
			return;
		}
		\get_current_screen()->remove_option('layout_columns');
	}

	public function removeDefaultBoxes()
	{
		\remove_meta_box('submitdiv', Post::TYPE, 'side');
		\remove_meta_box('slugdiv', Post::TYPE, 'normal');
	}

	public function hideDateFilter($type)
	{
		if ($type === Post::TYPE) {
			ob_get_clean();
			ob_start();
		}
	}

	public function addSubmitButton($post)
	{
		if (get_current_screen()->id !== Post::TYPE) {
			return;
		} ?>
		<div id="submitpost">
			<div id="minor-publishing">
				<?php submit_button(__('Save', 'zpos-wp-api'), 'primary', 'publish', false, [
    	'id' => 'publish',
    ]); ?>
			</div>
		</div>
		<?php
	}
}
