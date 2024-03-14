<?php
/**
 * @package         FireBox
 * @version         2.1.8 Free
 * 
 * @author          FirePlugins <info@fireplugins.com>
 * @link            https://www.fireplugins.com
 * @copyright       Copyright © 2024 FirePlugins All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

namespace FireBox\Core\FB;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

// Load WP_List_Table if not loaded
if (!class_exists('WP_List_Table'))
{
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

use FireBox\Core\Helpers\BoxHelper;

class CampaignsList extends \WP_List_Table
{
	private $per_page = 30;
	
	private $total_campaigns_data = [];

	function __construct()
	{
		$this->total_campaigns_data = (array) wp_count_posts('firebox', 'readable');

        parent::__construct([
            'singular' => 'firebox',
            'plural'   => 'fireboxes',
            'ajax'     => false
        ]);
    }

	/**
	 * Gets a list of CSS classes for the WP_List_Table table tag.
	 *
	 * @since 3.1.0
	 *
	 * @return string[] Array of CSS classes for the table tag.
	 */
	protected function get_table_classes()
	{
		$mode = get_user_setting('posts_list_mode', 'list');

		$mode_class = esc_attr('table-view-' . $mode);

		return ['widefat', 'striped', $mode_class, $this->_args['plural']];
	}

	protected function get_primary_column_name()
	{
		return 'title';
	}

	function get_columns()
	{
        return array(
            'cb'			   => '<input type="checkbox" />',
            'status'		   => 'Status',
            'title'			   => 'Title',
            'views'			   => 'Views',
            'conversions'	   => 'Conversions',
            'conversionrate'   => 'Conversion Rate',
            'id'			   => 'ID'
        );
    }

	function get_sortable_columns()
	{
		$sortable_columns = [
			'id'     		   => [ 'id', false ],
			'title'			   => [ 'title', false ],
			'views'			   => [ 'views', false ],
			'conversions'  	   => [ 'conversions', false ],
			'conversionrate'   => [ 'conversionrate', false ],
		];

		return $sortable_columns;
	}

	/**
	 * Renders a checkbox.
	 *
	 * @param   object  $item
	 * 
	 * @return  string
	 */
	public function column_cb($item)
	{
		return sprintf('<input type="checkbox" name="id[]" value="%s" />', esc_attr($item['ID']));
	}

	public function column_status($item)
	{
		echo \FPFramework\Helpers\HTML::renderFPToggle([
			'input_class' => ['fpf-toggle-post-status', 'size-small'],
			'name' => 'fb_toggle_post_' . $item['ID'],
			'extra_atts' => [
				'data-post-id' => $item['ID']
			],
			'value' => get_post_status($item['ID']) == 'publish' ? 1 : 0
		]);
	}

	public function column_views($item)
	{
		return isset($item['analytics']['views']) ? $item['analytics']['views'] : '';
	}

	public function column_conversions($item)
	{
		return isset($item['analytics']['conversions']) ? $item['analytics']['conversions'] : '';
	}

	public function column_conversionrate($item)
	{
		return isset($item['analytics']['conversionrate']) && $item['analytics']['conversionrate'] ? number_format($item['analytics']['conversionrate'], 2) . '%' : 'n/a';
	}

	/**
	 * Column "title" output.
	 * 
	 * @param   object  $item
	 * 
	 * @return  void
	 */
	public function column_title($item)
	{
		$url = admin_url('post.php?post=' . $item['ID'] . '&action=edit');
		
		return '<a href="' . $url . '">' . $item['label'] . '</a>';
	}

	/**
	 * Processes the bulk actions.
	 * 
	 * @return  void
	 */
	public function process_bulk_action()
	{
		// Ensure we have an action
		if (!$action = $this->current_action())
		{
			return;
		}

		// Ensure its a valid action
		$allowed_actions = $this->get_bulk_actions();
		if (!array_key_exists($action, $allowed_actions))
		{
			return;
		}

		// Ensure we have IDs
		$ids = isset($_GET['id']) ? array_map('intval', $_GET['id']) : [];
		if (!$ids)
		{
			return;
		}

		// Get nonce
		$nonce = isset($_GET['_wpnonce']) ? sanitize_text_field($_GET['_wpnonce']) : '';
		if (!$nonce)
		{
			return;
		}

		// Verify nonce
		$nonce_action = 'bulk-' . $this->_args['plural'];
		if (!wp_verify_nonce($nonce, $nonce_action))
		{
			return;
		}

		switch ($action)
		{
			case 'publish':
				$this->publishPosts($ids);
				\FPFramework\Libs\AdminNotice::displaySuccess(sprintf(firebox()->_('FB_X_CAMPAIGNS_HAVE_BEEN_PUBLISHED'), count($ids)));
				break;
			
			case 'unpublish':
				$this->unpublishPosts($ids);
				\FPFramework\Libs\AdminNotice::displaySuccess(sprintf(firebox()->_('FB_X_CAMPAIGNS_HAVE_BEEN_UNPUBLISHED'), count($ids)));
				break;

			case 'delete':
				$this->deletePosts($ids);
				\FPFramework\Libs\AdminNotice::displaySuccess(sprintf(firebox()->_('FB_X_CAMPAIGNS_HAVE_BEEN_DELETED'), count($ids)));
				break;

			case 'reset_stats':
				BoxHelper::resetBoxStats($ids);
				\FPFramework\Libs\AdminNotice::displaySuccess(sprintf(firebox()->_('FB_X_CAMPAIGNS_HAVE_BEEN_RESET'), count($ids)));
				break;
		}
	}

	private function publishPosts($ids = [])
	{
		if (!$ids)
		{
			return;
		}

		foreach ($ids as $id)
		{
			wp_update_post([
				'ID' => $id,
				'post_status' => 'publish'
			]);
		}
	}

	private function unpublishPosts($ids = [])
	{
		if (!$ids)
		{
			return;
		}

		foreach ($ids as $id)
		{
			wp_update_post([
				'ID' => $id,
				'post_status' => 'draft'
			]);
		}
	}

	private function deletePosts($ids = [])
	{
		if (!$ids)
		{
			return;
		}

		foreach ($ids as $id)
		{
			wp_delete_post($id);
		}
	}

	protected function handle_row_actions($item, $column_name, $primary)
	{
		if ($primary !== $column_name)
		{
			return '';
		}

		// Restores the more descriptive, specific name for use within this method.
		$post             = $item;
		$post_type_object = get_post_type_object( $post['post_type'] );
		$can_edit_post    = current_user_can( 'edit_post', $post['ID'] );
		$actions          = array();
		$title            = _draft_or_post_title();

		if ( $can_edit_post && 'trash' !== $post['post_status'] ) {
			$actions['edit'] = sprintf(
				'<a href="%s" aria-label="%s">%s</a>',
				get_edit_post_link( $post['ID'] ),
				/* translators: %s: Post title. */
				esc_attr( sprintf( __( 'Edit &#8220;%s&#8221;' ), $title ) ),
				__( 'Edit' )
			);
		}

		if ( current_user_can( 'delete_post', $post['ID'] ) ) {
			if ( 'trash' === $post['post_status'] ) {
				$actions['untrash'] = sprintf(
					'<a href="%s" aria-label="%s">%s</a>',
					wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post['ID'] ) ), 'untrash-post_' . $post['ID'] ),
					/* translators: %s: Post title. */
					esc_attr( sprintf( __( 'Restore &#8220;%s&#8221; from the Trash' ), $title ) ),
					__( 'Restore' )
				);
			} elseif ( EMPTY_TRASH_DAYS ) {
				$actions['trash'] = sprintf(
					'<a href="%s" class="submitdelete" aria-label="%s">%s</a>',
					get_delete_post_link( $post['ID'] ),
					/* translators: %s: Post title. */
					esc_attr( sprintf( __( 'Move &#8220;%s&#8221; to the Trash' ), $title ) ),
					_x( 'Trash', 'verb' )
				);
			}

			if ( 'trash' === $post['post_status'] || ! EMPTY_TRASH_DAYS ) {
				$actions['delete'] = sprintf(
					'<a href="%s" class="submitdelete" aria-label="%s">%s</a>',
					get_delete_post_link( $post['ID'], '', true ),
					/* translators: %s: Post title. */
					esc_attr( sprintf( __( 'Delete &#8220;%s&#8221; permanently' ), $title ) ),
					__( 'Delete Permanently' )
				);
			}
		}

		if ( is_post_type_viewable( $post_type_object ) ) {
			if ( in_array( $post['post_status'], array( 'pending', 'draft', 'future' ), true ) ) {
				if ( $can_edit_post ) {
					$preview_link    = get_preview_post_link( $post['ID'] );
					$actions['view'] = sprintf(
						'<a href="%s" rel="bookmark" aria-label="%s">%s</a>',
						esc_url( $preview_link ),
						/* translators: %s: Post title. */
						esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $title ) ),
						__( 'Preview' )
					);
				}
			}
		}

		// Add 'Duplicate' action
		$actions['duplicate'] = '<a href="admin.php?action=fb_duplicate_post_as_draft&post=' . $post['ID'] . '&_wpnonce=' . wp_create_nonce('duplicate-firebox-campaign') . '" title="' . firebox()->_('FB_DUPLICATE_CAMPAIGN') . '" rel="permalink">' . fpframework()->_('FPF_DUPLICATE') . '</a>';

		// Analytics
		$actions['analytics'] = '<a href="admin.php?page=firebox-analytics&campaign=' . $post['ID'] . '" title="' . firebox()->_('FB_VIEW_ANALYTICS_OF_CAMPAIGN') . '" rel="permalink">' . fpframework()->_('FPF_ANALYTICS') . '</a>';

		/**
		 * Check if cookie has been set
		 */
		if ((new \FireBox\Core\FB\Cookie(firebox()->box->get($post['ID'])))->exist())
		{
			$actions['clear_cookie'] = '<a class="firebox_red_text_color" href="admin.php?action=fb_clear_cookie&post=' . $post['ID'] . '&_wpnonce=' . wp_create_nonce('clearcookie-firebox-campaign') . '" title="' . firebox()->_('FB_CLEAR_COOKIE') . '" rel="permalink">' . firebox()->_('FB_HIDDEN_BY_COOKIE') . '</a>';
		}

		return $this->row_actions( $actions );
	}

	/**
	 * Returns the views.
	 * 
	 * @return  array
	 */
	public function get_views()
	{
		$current = $this->getCampaignStatus();
		$base_url = $this->get_base_url();

		// Base URL
		$remove = ['status', 'paged', '_wpnonce'];
		$url = remove_query_arg($remove, $base_url);

		$count = '&nbsp;<span class="count">(%d)</span>';

		$published = (int) $this->total_campaigns_data['publish'];
		$drafts = (int) $this->total_campaigns_data['draft'];
		$total_items = $published + $drafts;

		// All
		$all_class = in_array($current, ['', 'all'], true) ? ' class="current"' : '';
		$all_count = sprintf($count, esc_attr($total_items));
		$all_label = fpframework()->_('FPF_ALL') . $all_count;
		
		// Mine
		$m_class = in_array($current, ['mine'], true) ? ' class="current"' : '';
		$m_count = sprintf($count, esc_attr($this->getMineCount()));
		$m_label = fpframework()->_('FPF_MINE') . $m_count;
		
		// Published
		$p_class = in_array($current, ['published'], true) ? ' class="current"' : '';
		$p_count = sprintf($count, esc_attr($published));
		$p_label = fpframework()->_('FPF_PUBLISHED') . $p_count;
		
		// Drafts
		$d_class = in_array($current, ['drafts'], true) ? ' class="current"' : '';
		$d_count = sprintf($count, esc_attr($drafts));
		$d_label = fpframework()->_('FPF_DRAFTS') . $d_count;
		
		$views = [
			'all' => sprintf('<a href="%s"%s>%s</a>', esc_url($url), $all_class, $all_label),
			'mine' => sprintf('<a href="%s"%s>%s</a>', esc_url(add_query_arg('status', 'mine', $base_url)), $m_class, $m_label),
			'published' => sprintf('<a href="%s"%s>%s</a>', esc_url(add_query_arg('status', 'published', $base_url)), $p_class, $p_label),
			'drafts' => sprintf('<a href="%s"%s>%s</a>', esc_url(add_query_arg('status', 'drafts', $base_url)), $d_class, $d_label),
		];

		if ($this->total_campaigns_data['trash'])
		{
			$t_class = in_array($current, ['trash'], true) ? ' class="current"' : '';
			$t_count = sprintf($count, esc_attr((int) $this->total_campaigns_data['trash']));
			$t_label = fpframework()->_('FPF_TRASH') . $t_count;

			$views['trash'] = sprintf('<a href="%s"%s>%s</a>', esc_url(add_query_arg('status', 'trash', $base_url)), $t_class, $t_label);
		}

		$views['import'] = '<a href="admin.php?page=firebox-import">' . fpframework()->_('FPF_IMPORT') . '</a>';

		return $views;
	}

	private function getMineCount()
	{
		$query = new \WP_Query(
			[
				'post_type'  => 'firebox',
				'author'     => get_current_user_id()
			]
		);

		return $query->found_posts;
	}

	protected function get_bulk_actions()
	{
		return [
			'publish' => __( 'Publish', 'firebox' ),
			'unpublish' => __( 'Unpublish', 'firebox' ),
			'delete'  => __( 'Delete', 'firebox' ),
			'fb_export'  => __( 'Export', 'firebox' ),
			'reset_stats'  => __( 'Reset Views', 'firebox' ),
		];
	}

	/**
	 * Column "id" output.
	 * 
	 * @param   object  $item
	 * 
	 * @return  void
	 */
	public function column_id($item)
	{
		$url = admin_url('post.php?post=' . $item['ID'] . '&action=edit');
		
		return '<a href="' . $url . '">' . $item['ID'] . '</a>';
	}

	private function getCampaignStatus()
	{
		return isset($_GET['status']) ? sanitize_key($_GET['status']) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	function prepare_items()
	{
		$status = $this->getCampaignStatus();
        $per_page = $this->per_page;
        $current_page = $this->get_pagenum();

		$hidden = [];
        $columns = $this->get_columns();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = [$columns, $hidden, $sortable];

        $data = [];
        $args = [
            'post_type'      => 'firebox',
            'posts_per_page' => $per_page,
            'paged'          => $current_page
		];

		if (isset($_GET['s'])) //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		{
			$search_term = sanitize_text_field($_GET['s']); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$args['s'] = $search_term;
		}

		switch ($status)
		{
			case 'mine':
				$args['author'] = get_current_user_id();
				break;
			case 'published':
				$args['post_status'] = 'publish';
				break;
			case 'drafts':
				$args['post_status'] = 'draft';
				break;
			case 'trash':
				$args['post_status'] = 'trash';
				break;
		}

		$this->apply_initial_sorting($args);

        $query = new \WP_Query($args);
        if ($query->have_posts())
		{
            foreach ($query->posts as $post)
			{
                $data[] = [
                    'ID'           => $post->ID,
                    'label'		   => $post->post_title,
                    'post_type'    => $post->post_type,
                    'post_status'  => $post->post_status,
                    'date'         => $post->post_modified_gmt,
					'analytics'	   => $this->getCampaignAnalytics($post->ID),
				];
            }

			$this->apply_secondary_sorting($data);
        }

		$total_items = $query->post_count;

		if ($query->found_posts || $this->get_pagenum() === 1)
		{
			$total_items = $query->found_posts;
		}
		else
		{
			if (isset($_REQUEST['post_status']) && in_array($_REQUEST['post_status'], $avail_post_stati, true)) //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			{
				$total_items = $this->total_campaigns_data[$_REQUEST['post_status']]; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			elseif (isset($_REQUEST['show_sticky']) && $_REQUEST['show_sticky']) //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			{
				$total_items = $this->sticky_posts_count;
			}
			elseif (isset($_GET['author']) && get_current_user_id() === (int) $_GET['author']) //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			{
				$total_items = $this->user_posts_count;
			}
			else
			{
				$total_items = array_sum($this->total_campaigns_data);

				// Subtract post types that are not included in the admin all list.
				foreach (get_post_stati(['show_in_admin_all_list' => false]) as $state)
				{
					$total_items -= $this->total_campaigns_data[$state];
				}
			}
		}

        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page
        ]);

        $this->items = $data;
    }

	private function getCampaignAnalytics($campaign_id = null)
	{
		if (!$campaign_id)
		{
			return;
		}

		$data = new \FireBox\Core\Analytics\Data();

        $metrics = [
            'views',
            'conversions',
            'conversionrate'
		];
        $data->setMetrics($metrics);

    	$filters = [
            'campaign' => [
                'value' => [$campaign_id]
            ]
        ];
        $data->setFilters($filters);

        return $data->getData('count');
	}

	private function apply_initial_sorting(&$args = [])
	{
		$order = isset($_GET['order']) ? sanitize_key($_GET['order']) : 'desc'; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$orderby = isset($_GET['orderby']) ? sanitize_key($_GET['orderby']) : 'id'; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		
		switch ($orderby)
		{
			case 'id':
				$args['orderby'] = 'ID';
				$args['order'] = $order;
				break;
			case 'title':
				$args['orderby'] = 'title';
				$args['order'] = $order;
				break;
		}
	}

	private function apply_secondary_sorting(&$data)
	{
		$orderby = isset($_GET['orderby']) ? sanitize_key($_GET['orderby']) : 'id'; //phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$allowed_orderby = ['views', 'conversions', 'conversionrate'];
		if (!in_array($orderby, $allowed_orderby, true))
		{
			return;
		}

		$order = isset($_GET['order']) ? sanitize_key($_GET['order']) : 'desc'; //phpcs:ignore WordPress.Security.NonceVerification.Recommended

		usort($data, function ($a, $b) use ($order, $orderby) {
			if ($order === 'asc')
			{
				return strtolower($a['analytics'][$orderby]) > strtolower($b['analytics'][$orderby]) ? 1 : -1;
			}
			else
			{
				return strtolower($a['analytics'][$orderby]) < strtolower($b['analytics'][$orderby]) ? 1 : -1;
			}
		});
	}

	private function getTotalItems()
	{
		$status = $this->getCampaignStatus();
		$total = 0;

		switch ($status)
		{
			case 'mine':
				$total = $this->getMineCount();
				break;
			case 'published':
				$total = $this->total_campaigns_data['publish'];
				break;
			case 'drafts':
				$total = $this->total_campaigns_data['draft'];
				break;
			case 'trash':
				$total = $this->total_campaigns_data['trash'];
				break;
			default:
				$total = $this->total_campaigns_data['publish'] + $this->total_campaigns_data['draft'];
				break;
		}

		return $total;
	}
}