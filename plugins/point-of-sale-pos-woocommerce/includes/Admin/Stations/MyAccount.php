<?php

namespace ZPOS\Admin\Stations;

class MyAccount
{
	public function __construct()
	{
		add_action('init', [$this, 'addEndpoint']);

		if (!current_user_can('access_woocommerce_pos')) {
			return;
		}

		add_filter('woocommerce_account_menu_items', [$this, 'addMenuItem']);
		add_action('woocommerce_account_pos_endpoint', [$this, 'content']);
		add_action('wp_enqueue_scripts', [$this, 'styles']);
	}

	public function addEndpoint()
	{
		add_rewrite_endpoint('pos', EP_PAGES);
	}

	public function addMenuItem($items)
	{
		return array_merge(
			array_slice($items, 0, 2),
			['pos' => __('POS Stations', 'zpos-wp-api')],
			array_slice($items, 2)
		);
	}

	public function styles()
	{
		if (!is_account_page()) {
			return;
		}
		wp_add_inline_style(
			'woocommerce-inline',
			<<<CSS
			.pos-stations-title {
				vertical-align: middle;
			}
			.pos-stations-actions {
				text-align: right;
			}
			
			.pos-stations-actions .view:after {
				-webkit-font-smoothing: antialiased;
				-moz-osx-font-smoothing: grayscale;
				display: inline-block;
				font-style: normal;
				font-variant: normal;
				font-weight: normal;
				line-height: 1;
				font-family: 'Font Awesome 5 Free';
				font-weight: 900;
				line-height: inherit;
				vertical-align: baseline;
				content: "\\f06e";
				margin-left: 0.5407911001em;
			}
			
			.woocommerce-MyAccount-navigation ul li.woocommerce-MyAccount-navigation-link--pos a::before {
					content: "\\f07a";
			}
CSS
		);
	}

	public function content()
	{
		$posts = get_posts([
			'numberposts' => -1,
			'post_type' => Post::TYPE,
			'meta_query' => apply_filters(__METHOD__ . 'MetaQuery', []),
		]); ?>
		<table class="pos-stations">
			<thead>
			<tr>
				<th>POS Stations</th>
				<th class="pos-stations-actions">Actions</th>
			</tr>
			</thead>
			<?php foreach ($posts as $post): ?>
				<tr>
					<td class="pos-stations-title">
						<?= $post->post_title ?>
					</td>
					<td class="pos-stations-actions">
						<a class="button view" aria-label="View"
							 href="<?= get_permalink($post) ?>">View</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
		<?php
	}
}
