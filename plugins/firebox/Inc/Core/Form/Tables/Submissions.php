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

namespace FireBox\Core\Form\Tables;

if (!defined('ABSPATH'))
{
	exit; // Exit if accessed directly.
}

// Load WP_List_Table if not loaded
if (!class_exists('WP_List_Table'))
{
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

use \FireBox\Core\Helpers\Form\Form;

class Submissions extends \WP_List_Table
{
	/**
	 * Total items.
	 * 
	 * @var  array
	 */
	private $total_items;
	
	/**
	 * Total items per page.
	 * 
	 * @var  int
	 */
	private $per_page = 20;

	/**
	 * All forms.
	 * 
	 * @var  array
	 */
	private $forms = [];

	/**
	 * The selected form id.
	 * 
	 * @var  string
	 */
	private $selected_form_id = null;

	/**
	 * The selected form.
	 * 
	 * @var  array
	 */
	private $selected_form = null;

	public function __construct($args = [])
	{
		parent::__construct($args);

		$this->forms = Form::getForms();

		$this->selected_form_id = isset($_GET['form_id']) ? sanitize_key($_GET['form_id']) : (isset($this->forms[0]['id']) ? $this->forms[0]['id'] : null); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$this->selected_form = $this->getForm($this->selected_form_id);
	}
	
	/**
	 * This is a default column renderer.
	 *
	 * @param   Object  $item
	 * @param   string  $column_name
	 * 
	 * @return  string
	 */
	public function column_default($item, $column_name)
	{
		// Find field related values
		if (strpos($column_name, 'field_') === 0)
		{
			$form_fields = $this->getFormFields();
			
			foreach ($item->meta as $meta_item)
			{
				if ('field_' . $meta_item->meta_key !== $column_name)
				{
					continue;
				}

				$field = current(array_filter($form_fields, function($field) use ($meta_item) {
					return $field->getOptionValue('id') === $meta_item->meta_key;
				}));

				if ($field)
				{
					return $field->prepareValueHTML($meta_item->meta_value);
				}
				else
				{
					return nl2br($meta_item->meta_value);
				}
			}

			return '';
		}
		
		return $item->$column_name;
	}

	/**
	 * Gets a list of CSS classes for the WP_List_Table table tag.
	 *
	 * @since 3.1.0
	 *
	 * @return string[] Array of CSS classes for the table tag.
	 */
	protected function get_table_classes() {
		$mode = get_user_setting( 'posts_list_mode', 'list' );

		$mode_class = esc_attr( 'table-view-' . $mode );

		return array( 'widefat', 'striped', $mode_class, $this->_args['plural'] );
	}

	/**
	 * Returns the form fields.
	 * 
	 * @return  array
	 */
	private function getFormFields()
	{
		return isset($this->selected_form['fields']) ? $this->selected_form['fields'] : [];
	}

	/**
	 * Column "state" output.
	 * 
	 * @param   object  $item
	 * 
	 * @return  void
	 */
	public function column_state($item)
	{
		echo \FPFramework\Helpers\HTML::renderFPToggle([
			'input_class' => ['fb-toggle-form-state'],
			'name' => 'fb_submission_state_' . $item->id,
			'extra_atts' => [
				'data-submission-id' => $item->id
			],
			'value' => $item->state === '1' ? 1 : 0
		]);
	}

	/**
	 * Column "created at" output.
	 * 
	 * @param   object  $item
	 * 
	 * @return  void
	 */
	public function column_created_at($item)
	{
		return get_date_from_gmt($item->created_at);
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
		$url = admin_url('admin.php?page=firebox-submissions&task=edit&id=' . $item->id . '&_wpnonce=' . wp_create_nonce('edit-firebox-submission'));
		
		return '<a href="' . $url . '">' . $item->id . '</a>';
	}
	
	/**
	 * Column "user id" output.
	 * 
	 * @param   object  $item
	 * 
	 * @return  void
	 */
	public function column_user_id($item)
	{
		if ($item->user_id === '0')
		{
			return '';
		}

		return '<a href="' . get_edit_user_link($item->user_id) . '">' . $item->user_id . '</a>';
	}

	/**
	 * Column "user name" output.
	 * 
	 * @param   object  $item
	 * 
	 * @return  void
	 */
	public function column_user_name($item)
	{
		if ($item->user_id === '0')
		{
			return '';
		}

		$user = get_user_by('id', $item->user_id);
		
		return '<a href="' . get_edit_user_link($item->user_id) . '">' . $user->display_name . '</a>';
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
		return sprintf('<input type="checkbox" name="id[]" value="%s" />', esc_attr($item->id));
	}

	/**
	 * Returns all table columns.
	 * 
	 * @return  array
	 */
	function get_columns()
	{
		$columns = [
			'cb' => '<input type="checkbox" />',
			'state' => fpframework()->_('FPF_STATUS'),
			'id' => fpframework()->_('FPF_ID'),
			'created_at' => firebox()->_('FB_DATE_SUBMITTED')
		];

		if ($fields = $this->getFormFields())
		{
			foreach ($fields as $field)
			{
				$columns = array_merge($columns, [
					'field_' . $field->getOptionValue('id') => !empty($field->getLabel()) ? $field->getLabel() : $field->getOptionValue('name')
				]);
			}
		}

		$columns = array_merge($columns, [
			'user_id' => fpframework()->_('FPF_USER_ID'),
			'user_name' => fpframework()->_('FPF_USER_NAME'),
			'visitor_id' => fpframework()->_('FPF_VISITOR_ID'),
		]);

		return $columns;
	}

	/**
	 * [OPTIONAL] This method return columns that may be used to sort table
	 * all strings in array - is column names
	 * notice that true on name column means that its default sort
	 *
	 * @return array
	 */
	public function get_sortable_columns()
	{
		return [
			'state' 		 => ['state', false],
			'id'		  	 => ['id', true],
			'created_at' 	 => ['created_at', false],
			'user_id' 		 => ['user_id', false],
			'visitor_id'  	 => ['visitor_id', false],
		];
	}

	/**
	 * Returns the allowed sortable columns keys.
	 * 
	 * @return  array
	 */
	private function getAllowedOrders()
	{
		return array_keys($this->get_sortable_columns());
	}

	/**
	 * Set bulk actions.
	 *
	 * @return  array
	 */
	public function get_bulk_actions()
	{
		return [
			'publish' => fpframework()->_('FPF_PUBLISH'),
			'unpublish' => fpframework()->_('FPF_UNPUBLISH'),
			'delete' => fpframework()->_('FPF_DELETE')
		];
	}

	/**
	 * Processes the bulk actions.
	 * 
	 * @return  void
	 */
	public function process_bulk_action()
	{
		if (!isset($_GET['form_id']))
		{
			return;
		}
		
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
		
		// Get nonce
		$nonce = isset($_GET['_wpnonce']) ? sanitize_text_field($_GET['_wpnonce']) : '';
		if (!$nonce)
		{
			return;
		}

		// Validate nonce
		if (!wp_verify_nonce($nonce, 'bulk-firebox_page_firebox-submissions'))
		{
			wp_die(fpframework()->_('FPF_CANNOT_VALIDATE_REQUEST'));
		}

		unset($_GET['fpframework_fields']);

		// Ensure we have IDs
		$ids = isset($_GET['id'] ) ? array_filter($_GET['id'], 'intval') : [];
		if (!$ids)
		{
			return;
		}

		// Publish or Unpublish action
		if (in_array($action, ['publish', 'unpublish']))
		{
			$new_state = $action === 'publish' ? 1 : 0;
			
			foreach ($ids as $id)
			{
				if (!\FireBox\Core\Helpers\Form\Submission::updateState($id, $new_state))
				{
					wp_die(firebox()->_('FB_CANNOT_UPDATE_SUBMISSION'));
				}
			}

			\FPFramework\Libs\AdminNotice::displaySuccess(firebox()->_('FB_SUBMISSIONS_UPDATED'));
		}
		// Delete action
		else if ($action === 'delete')
		{
			$idsPlaceholders = array_fill(0, count($ids), '%s');
			$placeholdersForIds = implode(', ', $idsPlaceholders);

			global $wpdb;

			// Find all box log ids for each submission
			$meta_table_name = firebox()->tables->submissionmeta->getFullTableName();

			$box_log_ids = $wpdb->get_results(
				$wpdb->prepare(
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
					"SELECT DISTINCT(meta_value) FROM $meta_table_name WHERE meta_key = %s AND submission_id IN ($placeholdersForIds)",
					array_merge(['box_log_id'], $ids)
				),
				ARRAY_A
			);

			// Delete the form conversions for these submissions
			if ($box_log_ids)
			{
				$logIdsPlaceholders = array_fill(0, count($box_log_ids), '%s');
				$placeholdersForLogIds = implode(', ', $logIdsPlaceholders);
	
				$box_log_table_name = firebox()->tables->boxlogdetails->getFullTableName();

				// Prepare the box log ids
				$box_log_ids = array_map(function($box_log_id) {
					return $box_log_id['meta_value'];
				}, $box_log_ids);

				$wpdb->query(
					$wpdb->prepare(
						// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
						"DELETE FROM $box_log_table_name WHERE event = 'conversion' AND event_source = 'form' AND log_id IN ($placeholdersForLogIds)",
						...$box_log_ids
					)
				);
			}

			// Delete submission
			$s_table_name = firebox()->tables->submission->getFullTableName();
			$wpdb->query(
				$wpdb->prepare(
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
					"DELETE FROM $s_table_name WHERE id IN ($placeholdersForIds)",
					...$ids
				)
			);
			
			// Also delete all submission meta associated with this submission
			$wpdb->query(
				$wpdb->prepare(
					// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
					"DELETE FROM $meta_table_name WHERE submission_id IN ($placeholdersForIds)",
					...$ids
				)
			);

			\FPFramework\Libs\AdminNotice::displaySuccess(firebox()->_('FB_SUBMISSIONS_UPDATED'));
		}
	}

	/**
	 * Returns the views.
	 * 
	 * @return  array
	 */
	public function get_views()
	{
		$current = isset($_GET['status']) ? sanitize_key($_GET['status']) : ''; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$base_url = $this->get_base_url();

		// Base URL
		$remove = ['status', 'paged', '_wpnonce'];
		$url = remove_query_arg($remove, $base_url);

		$count = '&nbsp;<span class="count">(%d)</span>';

		// All
		$all_class = in_array($current, ['', 'all'], true) ? ' class="current"' : '';
		$all_count = sprintf($count, esc_attr(count($this->total_items)));
		$all_label = fpframework()->_('FPF_ALL') . $all_count;
		
		// Published
		$p_class = in_array($current, ['published'], true) ? ' class="current"' : '';
		$p_count = sprintf($count, esc_attr($this->getPublishedCount()));
		$p_label = fpframework()->_('FPF_PUBLISHED') . $p_count;
		
		// Unpublished
		$t_class = in_array($current, ['unpublished'], true) ? ' class="current"' : '';
		$t_count = sprintf($count, esc_attr($this->getUnpublishedCount()));
		$t_label = fpframework()->_('FPF_UNPUBLISHED') . $t_count;

		$views = [
			'all' => sprintf('<a href="%s"%s>%s</a>', esc_url($url), $all_class, $all_label),
			'published' => sprintf('<a href="%s"%s>%s</a>', esc_url(add_query_arg('status', 'published', $base_url)), $p_class, $p_label),
			'unpublished' => sprintf('<a href="%s"%s>%s</a>', esc_url(add_query_arg('status', 'unpublished', $base_url)), $t_class, $t_label),
		];

		return $views;
	}

	/**
	 * Returns how many published items we have.
	 * 
	 * @return  int
	 */
	private function getPublishedCount()
	{
		return count(array_filter($this->total_items, function($submission) {
			return $submission->state === '1';
		}));
	}

	/**
	 * Returns how many unpublished items we have.
	 * 
	 * @return  int
	 */
	private function getUnpublishedCount()
	{
		return count(array_filter($this->total_items, function($submission) {
			return $submission->state === '0';
		}));
	}

	/**
	 * When no items, display this message.
	 * 
	 * @return  void
	 */
	public function no_items()
	{
		esc_html_e(firebox()->_('FB_NO_SUBMISSIONS_FOUND'));
	}

	/**
	 * Returns the form given its ID.
	 * 
	 * @param   int    $id
	 * 
	 * @return  array
	 */
	private function getForm($id = '')
	{
		if (!$id)
		{
			return;
		}

		$hash = md5('getForm_' . $id);

		// check cache
		if ($form = wp_cache_get($hash))
		{
			return $form;
		}

		$form = array_filter($this->forms, function($form_item) use ($id) {
			return $id === $form_item['id'];
		});
		$form = reset($form);
		
		wp_cache_set($hash, $form);

		return $form;
	}

	/**
	 * Prepares items to be shown in table.
	 *
	 * @return  void
	 */
	function prepare_items()
	{
		$columns = $this->get_columns();

		$sortable = $this->get_sortable_columns();

		$this->_column_headers = [$columns, [], $sortable];

		$offset = isset($_GET['paged']) ? max(0, intval($_GET['paged'] - 1) * $this->per_page) : 0; //phpcs:ignore WordPress.Security.NonceVerification.Recommended

		$orderby = isset($_GET['orderby']) && in_array($_GET['orderby'], $this->getAllowedOrders()) ? sanitize_key($_GET['orderby']) : 'created_at'; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$order = isset($_GET['order']) && in_array($_GET['order'], ['asc', 'desc']) ? sanitize_key($_GET['order']) : 'desc'; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$orderby = $orderby . ' ' . $order;

		$payload = [
			'where' => [
				'form_id' => " = '" . esc_sql($this->selected_form_id) . "'"
			],
			'offset' => $offset,
			'limit' => $this->per_page,
			'orderby' => $orderby
		];

		$status = isset($_GET['status']) ? sanitize_key($_GET['status']) : false; //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ($status)
		{
			$payload['where'] = array_merge($payload['where'], [
				'state = ' => "'" . esc_sql($status === 'published' ? 1 : 0) . "'"
			]);
		}
		
		$this->total_items = firebox()->tables->submission->getResults([
			'where' => [
				'form_id' => " = '" . esc_sql($this->selected_form_id) . "'"
			]
		], true);

		$total_items_count = count($this->total_items);

		$this->items = $total_items_count ? firebox()->tables->submission->getResults($payload) : [];

		if ($this->items)
		{
			// Set submission fields values
			foreach ($this->items as &$item)
			{
				// Find field values
				$meta = firebox()->tables->submissionmeta->getResults([
					'where' => [
						'submission_id' => " = " . esc_sql($item->id)
					]
				]);
				$item->meta = $meta;
			}

			$this->set_pagination_args([
				'total_items' => $total_items_count,
				'per_page'    => $this->per_page,
				'total_pages' => ceil($total_items_count / $this->per_page),
			]);
		}
	}

	/**
	 * Generates the table navigation above or below the table
	 * 
	 * Overriden.
	 *
	 * @param   string  $which
	 * 
	 * @return  void
	 */
	protected function display_tablenav($which)
	{
		if ('top' === $which)
		{
			wp_nonce_field('bulk-' . $this->_args['plural'], '_wpnonce', false, true);
		}
		?>
		<div class="tablenav <?php echo esc_attr($which); ?>">

			<?php if ($this->has_items()): ?>
			<div class="alignleft actions bulkactions">
				<?php $this->bulk_actions($which); ?>
			</div>
			<?php
			endif;
			$this->extra_tablenav($which);
			$this->pagination($which);
			?>

			<br class="clear" />
		</div>
		<?php
	}
}