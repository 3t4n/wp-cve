<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * *Quote page
 * !Doesnt work yet the ajax request. only works pdf generator
 */
class SCCQuoteListView {

	public $quotes_from_id;
	public $quote_status;
	private $disableIPandBrowserColumn;
	public function get_calculator_name() {
		global $wpdb;
		$name = $wpdb->get_results( $wpdb->prepare( "SELECT formname FROM `{$wpdb->prefix}df_scc_forms` WHERE id = %d", $this->quotes_from_id ) );
		return $name[0];
	}
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 90 );
		add_action(
			'admin_head',
			function () {
				remove_submenu_page( 'scc-tabs', 'df-scc-view-quotes' );
			}
		);
		$this->page();
	}
	function page() {
		wp_register_style( 'scc-select2', SCC_URL . 'lib/tom-select/tom-select-quotes.css', array(), STYLISH_COST_CALCULATOR_VERSION );
		wp_register_script( 'scc-select2', SCC_URL . 'lib/tom-select/tom-select.complete.min.js', array(), STYLISH_COST_CALCULATOR_VERSION );

		wp_enqueue_style( 'scc-select2' );
		wp_enqueue_script( 'scc-select2' );
		if ( isset( $_GET['id'] ) ) {
			$this->quotes_from_id = absint( $_GET['id'] );
			// setting up status
			$this->quote_status = array(
				array( 'closed' => 'New (Not Connected)' ),
				array( 'replied-nconnected' => 'Replied (Not Connected)' ),
				array( 'unqualified-nconnected' => 'UnQualified (Not Connected)' ),
				array( 'qualified-connected' => 'Qualified (Connected)' ),
				array( 'qualified-unassigned' => 'Qualified (Un-Assigned)' ),
				array( 'qualified-assigned' => 'Qualified (Assigned)' ),
				array( 'in-discussion-connected' => 'In-Neogotiation (Connected)' ),
				array( 'follow-up-later' => 'Follow-Up Later (Connected)' ),
				array( 'closed-won' => 'Closed (Won)' ),
				array( 'closed-lost' => 'Closed (Lost)' ),
			);
		} else {
			echo 'Bad parameters';
			return;
		}
		add_action( 'scc_get_quote_form_fields', array( $this, 'get_field_params' ), 7, 1 );
		// get table column data from the quote fields
		do_action( 'scc_get_quote_form_fields', 'placeholder' );
		// load the entries
		$this->load_entries_list_table( $this->quotes_from_id );
		?>
		<div style="background-color: #314af3;margin: 0px 0px 10px -35px;display: flex;">
			<h1 style="color:white;padding-left:35px;margin: 30px 0;">Quotes from <?php echo esc_html( $this->get_calculator_name()->formname ); ?></h1>
		</div>
				<div id="quotes" class="container-fluid">
			<?php if ( true ) : ?>
				<div id="table" style="margin-top: 30px;">
					<?php
					echo wp_kses(
						sprintf( /* translators: %s - number of entries found. */
							_n(
								'Found <strong>%s entry</strong>',
								'Found <strong>%s entries</strong>',
								absint( $this->crm_table->total_items ),
								'scc'
							),
							absint( $this->crm_table->total_items )
						),
						array(
							'strong' => array(),
						)
					);
					?>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-12 col-lg-12">
						<a class="btn btn-settings-bar" style="color:white;background-color: #20319f;padding: 10px 30px;font-weight: 500;text-transform: uppercase;
						font-size: 15px;letter-spacing: 0.5px;" href="<?php echo esc_url( admin_url( "admin.php?page=scc_edit_items&id_form={$_GET["id"]}" ) ); ?> ">Edit Calculator</a>
					</div>
				</div>
			<?php endif ?>
			<?php $this->crm_table->display(); ?>
		</div>
		<div id="filter-fields-options" style="display: none;" title="choose fields you want to show in the table">
		</div>
		<style>
			#alert-remove-col-scc {
				color: red;
				font-size: 14px;
			}

			.swal2-actions {
				z-index: unset !important;
			}

			.swal2-title {
				font-size: 1.575rem !important;
			}

			.swal2-content .items.ts-input.full.has-items {
				text-align: left;
			}

			.input-hidden .items input {
				display: none !important;
			}

			#the-list tr>td {
				vertical-align: middle;
			}

			th#actions,
			td.actions.column-actions {
				width: 10%;
			}

			#date_created,
			.date_created .column-date_created {
				width: 10%;
			}
			.tablenav-pages.one-page{
				margin-right: 10px;
			}
			
		</style>
		<script id="page-params">
			<?php
			echo 'var settings =' . json_encode(
				array(
					'id'                 => $this->quotes_from_id,
					'formFields'         => json_encode( $this->formFields ),
					'formFieldsToShow'   => json_encode( $this->formFieldsToShow ),
					'quoteStatuses'      => json_encode( $this->quote_status ),
					'iPandBrowserColumn' => json_encode( $this->disableIPandBrowserColumn ),
				)
			)
			?>
		</script>
		<script>
			let formFields = JSON.parse(settings.formFields);
			let formFieldsToShow = JSON.parse(settings.formFieldsToShow);
			formFields = Object.entries(formFields).map(ee => ({
				value: ee[0],
				text: ee[1]
			}))
			var pageParameters;

			function handleViewClick($this) {
				let quoteId = jQuery($this).data('submissionId');
				let calculatorId = settings.id;
				jQuery.ajax({
					url: wp.ajax.settings.url,
					type: 'POST',
					context: $this,
					data: {
						action: 'preGeneratedQuoteDownload',
						data: {
							quoteId,
							calculatorId
						}
					},
					success: function(b64) {
						const linkSource = `data:application/pdf;base64,${b64.data}`;
						const downloadLink = document.createElement("a");
						let quotePersonName = jQuery(this).closest('tr').find('td.name')[0].innerText;
						let quoteCreationDate = jQuery(this).closest('tr').find('td.date_created')[0].innerText
						const fileName = quotePersonName + ' ' + quoteCreationDate + ".pdf";
						downloadLink.href = linkSource;
						downloadLink.download = fileName;
						downloadLink.click();
					},
				})
			}

			function handleQuoteDeletion($this) {
				let quoteId = jQuery($this).data('quoteId');
				Swal.fire({
					title: '<strong>Are you sure? You want to delete this quote?</strong>',
					showCancelButton: true
				}).then((result) => {
					var srcQuoteRow = jQuery($this).closest('tr');
					if (result.isConfirmed) {
						jQuery.ajax({
							url: wp.ajax.settings.url,
							type: 'POST',
							data: {
								action: 'scc_delete_quote',
								quoteId
							},
							success: function(status) {
								if (status.successful) {
									srcQuoteRow.hide(2000);
									showSweet(true, "The quote has been deleted")
								}
							},
						});
					}
				});
			}
			jQuery(document).ready(($) => {
				$('#filter-fields').on('click', ($this) => {
					Swal.fire({
						title: '<strong>Columns Customizer</strong><br>',
						html: `<select placeholder="click here to select the columns" class="quote-columns-filter" multiple="multiple">
							</select>
							<span id="alert-remove-col-scc" style="display:none">You can't remove that field</span><br>
							<input type="checkbox" id="disable-ip-ua" ${(settings.iPandBrowserColumn == "1") ? 'checked' : ''}>
							<label style="font-size: 0.9rem;" for="disable-ip-ua">Disable Browser and IP address column</label>
							`,
						didRender: () => {

							var opop = new TomSelect('.quote-columns-filter', {
								plugins: ['remove_button'],
								options: formFields,
								items: Object.keys(formFieldsToShow),
								onItemRemove: function(values) {
									/**fields that cant be remove to display in table */
									if (values == "name" || values == "email") {
										opop.addItem(values)
										show_alert_()
									}
								}
							});

							function show_alert_() {
								var oooo = jQuery("#alert-remove-col-scc").fadeIn(200)
								var tt = setTimeout(() => {
									oooo.fadeOut(200)
								}, 4000);
							}
						},
						showCancelButton: true,
						confirmButtonText: "Save",
						confirmButtonColor: '#314af3',
						cancelButtonText: "Cancel",
						// preConfirm: console.log
					}).then((result) => {
						if (result.isConfirmed) {
							/**
							 * *created a json like the old with neccesary values with no error
							 * !this feeds the show column in db
							 */
							var choosenColumnsComposed = []
							let choosenColumns = jQuery('select.quote-columns-filter').each(function(e, v) {
								for (let i = 0; i < v.options.length; i++) {
									var ss = {
										[v.options[i].value]: {
											name: v.options[i].label
										}
									}
									choosenColumnsComposed.push(ss)
								}
							})
							// finding the columns which were not choosen
							let calculatorId = settings.id;
							let disableIPandBrowserColumn = jQuery('#disable-ip-ua').is(':checked')

							var w = jQuery.ajax({
								url: wp.ajax.settings.url,
								type: 'POST',
								data: {
									action: 'scc_save_quote_management_table_columns_filter',
									data: {
										choosenColumnsComposed,
										calculatorId,
										disableIPandBrowserColumn
									}
								},
								success: function(b64) {
									if (b64.passed) {
										window.location = window.location
									}
								},
							})
						}
					})
				})
				let config = {
					options: JSON.parse(settings.quoteStatuses).map(e => {
						return {
							value: Object.keys(e)[0],
							text: Object.values(e)[0]
						}
					}),
					placeholder: "Select a tag",
					hidePlaceholder: true,
					onChange: function(selected) {
						let srcQuoteId = jQuery(this.input).data('quoteId');
						jQuery.ajax({
							url: ajaxurl,
							type: 'POST',
							data: {
								action: 'scc_save_individual_quote_status',
								data: {
									statusTag: selected,
									quoteId: srcQuoteId
								}
							},
							success: function(status) {
								showSweet(true, "The quote has been updated")
							},
						});
					}
				}
				jQuery('select.quote-status-setup').each((index, element) => {
					let selected = jQuery(element).data('selectedKey')
					if (selected.length) {
						config.items = selected
					}
					let select = new TomSelect(element, config);
				})
			})

			function showSweet(respuesta, message) {
				if (respuesta) {
					Swal.fire({
						toast: true,
						title: message,
						icon: "success",
						showConfirmButton: false,
						timer: 3000,
						position: 'top-end',
						background: 'white'
					})
				} else {
					Swal.fire({
						toast: true,
						title: message,
						icon: "error",
						showConfirmButton: false,
						timer: 3000,
						position: 'top-end',
						background: 'white'
					})
				}
			}
		</script>
		<?php
	}
	public function get_field_params() {
		/**
		 * !formFieldsArray needs to be inserted from quote button in calculator shortcode
		 * ? the format of json / array was adapted to pull data from formFieldsArray column in db
		 * todo: when in calculator is clicked ...
		 */
		global $wpdb;
		// gets custom quote fields and processes them to a key value pairs
		$formParams = $wpdb->get_row( $wpdb->prepare( "SELECT showFieldsQuoteArray, formFieldsArray FROM {$wpdb->prefix}df_scc_forms WHERE id = %d;", $this->quotes_from_id ) );

		//obejeter array para filtrar si no existe poner por defecto
		if ( ! $formParams->formFieldsArray ) {
			$formParams->formFieldsArray = '[ { "name": { "name": "YourName" } }, { "email": { "name": "YourEmail" } }, { "phone": { "name": "YourPhone" } }, { "referer": { "name": "Referring Page" } } ]';
		}

		if ( ! $formParams->showFieldsQuoteArray ) {
			$formParams->showFieldsQuoteArray = '{ "displayIpBrowserColumn": "true", "showInTable": [ { "name": { "name": "Your Name" } }, { "email": { "name": "Your Email" } }, { "phone": { "name": "Your Phone" } }, { "referer": { "name": "Referring Page" } } ] }';
		}

		/**
		 * !to show in filter
		 * */
		$arrParamsFilter = json_decode( $formParams->showFieldsQuoteArray, true );
		//!show options in table
		$formFieldsFilter = $arrParamsFilter['showInTable'];

		/**
		 * !option available to filter
		 * */
		$arrParamsShowinTable = json_decode( $formParams->formFieldsArray, true );
		if ( $arrParamsFilter['displayIpBrowserColumn'] == 'true' ) {
			$this->disableIPandBrowserColumn = 1;
		} else {
			$this->disableIPandBrowserColumn = 0;
		}

		if ( ! function_exists( 'tableColsKV' ) ) {
			function tableColsKV( $cc ) {
				$key   = array_keys( ( $cc ) )[0];
				$value = $cc[ $key ]['name'];
				return array( $key => $value );
			}
		}

		/**
		 * !show data in filter
		 */
		$tmp_table_head = array_map( 'tableColsKV', $arrParamsShowinTable );
		$table_cols     = array();
		for ( $i = 0; $i < count( $tmp_table_head ); $i++ ) {
			$table_cols += $tmp_table_head[ $i ];
		};
		if ( ! function_exists( 'shownTableHeadKV' ) ) {
			function shownTableHeadKV( $cc ) {
				$key   = array_keys( ( $cc ) )[0];
				$value = $cc[ $key ]['name'];
				return array( $key => $value );
			}
		}
		// filters and sets the key and name pairs
		/**
		 * !selected options in filter
		 */
		$tmp_table_head_to_show = array_map( 'shownTableHeadKV', $formFieldsFilter );
		$table_cols_to_show     = array();
		for ( $i = 0; $i < count( $tmp_table_head_to_show ); $i++ ) {
			if ( isset( $tmp_table_head_to_show[ $i ] ) ) {
				$table_cols_to_show += $tmp_table_head_to_show[ $i ];
			}
		};
		if ( $this->disableIPandBrowserColumn == 0 ) {
			$table_cols_to_show['browser'] = 'Browser';
			$table_cols_to_show['user_ip'] = 'User IP';
		}

		// field ID and it's name passed to create column headers
		$this->formFields       = $table_cols;
		$this->formFieldsToShow = $table_cols_to_show;
	}
	public function load_entries_list_table( $quotes_from_id ) {
		$this->crm_table                    = new QuoteListTable();
		$this->crm_table->form_id           = $quotes_from_id;
		$this->crm_table->quote_status      = $this->quote_status;
		$this->crm_table->quote_form_fields = $this->formFieldsToShow;
		$this->crm_table->prepare_items();
	}
	function admin_menu() {
		add_submenu_page( 'scc-tabs', 'Quote Viewer', null, 'manage_options', 'df-scc-view-quotes', array( $this, 'page' ) );
	}
}
new SCCQuoteListView();

if ( ! class_exists( 'WP_List_Table' ) ) {
	include_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
class QuoteListTable extends \WP_List_Table {

	public $quote_form_fields;
	public $form_id;
	public $total_items;
	public $quote_status;
	public function __construct() {
		parent::__construct(
			array(
				'singular' => 'Quote Request',
				'plural'   => 'Quotes Request',
				'ajax'     => false,
				'screen'   => 'stylish-cost-calculator_page_view-quotes',
			)
		);
	}
	// private $quote_form_fields;
	function no_items() {
		esc_html_e( 'No Quotes Found', 'scc' );
	}
	function prepare_items() {
		global $wpdb;
		$this->get_counts();
		// Setup the columns.
		$columns = $this->get_columns();
		// Hidden columns (none).
		$hidden = array();
		// Define which columns can be sorted.
		$sortable = $this->get_sortable_columns();
		// Get a primary column. It's will be a 3-rd column.
		$primary = key( array_slice( $columns, 2, 1 ) );
		// Set column headers.
		$this->_column_headers = array( $columns, $hidden, $sortable, $primary );
		$page                  = $this->get_pagenum();
		$order                 = isset( $_GET['order'] ) ? sanitize_key( $_GET['order'] ) : 'DESC';
		$orderby               = isset( $_GET['orderby'] ) ? sanitize_key( $_GET['orderby'] ) : 'calc_id';
		$per_page              = 25;
		$data_args             = array(
			'form_id'  => $this->form_id,
			'page-max' => $per_page,
			'offset'   => $per_page * ( $page - 1 ),
			'order'    => $order,
			'orderby'  => $orderby,
		);
		if ( ! empty( $_GET['type'] ) && $_GET['type'] === 'starred' ) {
			$data_args['starred'] = '1';
		}
		if ( ! empty( $_GET['type'] ) && $_GET['type'] === 'unread' ) {
			$data_args['viewed'] = '0';
		}
		if ( ! empty( $_GET['status'] ) ) {
			$data_args['status'] = sanitize_text_field( $_GET['status'] );
		}
		if ( array_key_exists( 'notes_count', $columns ) ) {
			$data_args['notes_count'] = true;
		}
		$data = $wpdb->get_results( $wpdb->prepare( 
			"SELECT * FROM {$wpdb->prefix}df_scc_quote_submissions WHERE {$wpdb->prefix}df_scc_quote_submissions.calc_id=%d LIMIT %d OFFSET %d;", 
			$data_args['form_id'], 
			$data_args['page-max'], 
			$data_args['offset']
		) );
		$total_items = $wpdb->get_results( $wpdb->prepare( 
			"SELECT COUNT(*) total FROM {$wpdb->prefix}df_scc_quote_submissions WHERE {$wpdb->prefix}df_scc_quote_submissions.calc_id=%d;", 
			$data_args['form_id']
		) );

		$total_items = empty( $total_items ) ? 0 : $total_items[0]->total;
		// add data
		$this->items       = $data;
		$this->total_items = $total_items;
		$this->set_pagination_args(
			array(
				'total_items' => $this->total_items,
				'per_page'    => $per_page,
			)
		);
	}
	private function get_quote_form_values( $entry, $column_name ) {
		if ( $column_name == 'status' ) {
			return '';
		}
		$fields = json_decode( $entry->submit_fields );
		if ( empty( $fields ) ) {
			return 'Empty';
		}
		foreach ( $fields as $key => $value ) {
			if ( $value[0] == $column_name ) {
				return $value[1];
			}
		}
	}
	protected function extra_tablenav( $which ) {
		if ( $which === 'bottom' ) {
			return;
		}
		?>
		<div class="alignright">
			<button id="export-csv" class="button " title="coming soon">
				Export CSV
			</button>
			<button id="filter-fields" class="button">
				Columns
			</button>
		</div>
		<?php
	}
	/* Process column row value */
	public function column_default( $entry, $column_name ) {
		$field_type = $this->get_field_type( $entry, $column_name );
		switch ( strtolower( $column_name ) ) {
			case 'user_ip':
				$value = $entry->user_ip;
				break;
			case 'browser':
				$value = $entry->browser_ua;
				break;
			case 'referer':
				$quote_data = json_decode( $entry->quote_data, true );
				if ( isset( $quote_data['referer'] ) ) {
					$value = $quote_data['referer'];
				}
				if ( ! isset( $quote_data['referer'] ) || empty( $quote_data['referer'] ) ) {
					$value = 'Not Available';
				}
				break;
			default:
				$value = $this->get_quote_form_values( $entry, $column_name );
		}
		return apply_filters( 'my_filter', $value, $entry, $column_name );
	}
	function column_cb( $item ) {
		return sprintf( '<input type="checkbox" data-idq="%s">', $item->id );
	}
	function column_status( $item ) {
		$currentStatus = empty( $item->status ) ? 'Not Set' : $item->status;
		$this->quote_status;
		return "<select data-quote-id={$item->id} data-selected-key={$currentStatus} class=\"quote-status-setup\">
            </select>";
	}
	function column_actions( $item ) {
		return "<a href='javascript:void(0)' onclick='handleViewClick(this)' data-submission-id=$item->id>Download</a>&nbsp;|&nbsp;<a data-quote-id=$item->id onclick='handleQuoteDeletion(this)' href='javascript:void(0)'>Delete</a>";
	}
	function column_starred( $item ) {
		return (bool) absint( $item->starred ) ? 'Yes' : 'No';
	}
	function column_opened( $item ) {
		return (bool) absint( $item->opened ) ? 'Yes' : 'No';
	}
	function column_type( $item ) {
		return ! empty( $item->type ) ? $item->type : 'Unknown';
	}
	function column_submitted_data( $item ) {
		return ! empty( $item->submit_fields ) ? $item->submit_fields : 'Empty Data';
	}
	function column_date_created( $item ) {
		return ! empty( $item->created_at ) ? $item->created_at : 'Empty Data';
	}
	function column_date_updated( $item ) {
		return ! empty( $item->updated_at ) ? $item->updated_at : 'Empty Data';
	}
	function get_columns() {
		$arr1    = array(
			'cb'     => '<input type="checkbox" />',
			'status' => __( 'Status', 'scc' ),
		);
		$arr2    = $this->quote_form_fields;
		$arr3    = array(
			'date_created' => __( 'Created', 'scc' ),
			'actions'      => 'Actions',
		);
		$columns = array_merge( $arr1, $arr2, $arr3 );
		return apply_filters( 'scc__table_columns', $columns, $this->form_data );
	}
}
