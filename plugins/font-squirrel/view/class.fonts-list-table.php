<?php
class FontSq_List_Table extends WP_Posts_List_Table {
	/**
	 * Prepares the list of items for displaying.
	 * @uses WP_List_Table::set_pagination_args()
	 *
	 * @since 3.1.0
	 * @access public
	 */
	public function prepare_items($API, $classification) {
		// first : get entire response to setup paging
		$this->items = $API->list_families($classification);
		$total_items = sizeof($this->items);
		$per_page = 5;
		$this->set_pagination_args( array(
			'total_items' => $total_items,
			'total_pages' => ceil( $total_items / $per_page ),
			'per_page' => $per_page
		) );

		// second : truncate result to the good part and get dome additionnal info
		$this->items = array_slice($this->items, ($this->get_pagenum()-1) * $per_page, $per_page);
		foreach( $this->items as $n => $family ){
			$this->items[$n]->preview_image = $API->get_preview_image($family->family_urlname);
		}

		$this->is_trash = false;
		$this->_column_headers = array( array(
			"cb"	=>	'<input type="checkbox" />',
			"title"	=>	__("Title"),
			"previews" => __("Sample"),
		), array(false), array(false));
	}

	/**
	 * Whether the table has items to display or not
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @return bool
	 */
	public function has_items() {
		return !empty( $this->items );
	}

	/**
	 * Generate the table rows
	 *
	 * @since 3.1.0
	 * @access public
	 */
	public function display_rows() {
		?><style>.fixed .column-previews{width: 70%}</style><?php
		foreach ( $this->items as $item )
			$this->single_row( $item );
	}

	/**
	 * Generates content for a single row of the table
	 *
	 * @since 3.1.0
	 * @access public
	 *
	 * @param object $item The current item
	 */
	public function single_row( $item ) {
		?><tr>
			<th scope="row" class="check-column">
				<label class="screen-reader-text" for="cb-select-<?php echo $item->id; ?>"><?php printf( __( 'Select %s' ), $item->family_name ); ?></label>
				<input id="cb-select-<?php echo $item->id; ?>" type="checkbox" name="font[]" value="<?php echo $item->id; ?>" />
				<div class="locked-indicator"></div>
			</th>
			<td class="column-title">
				<strong><?php echo $item->family_name ?></strong>
				<?php 
					$actions = array('install' => '<a href="' . admin_url('post-new.php?post_type=font&amp;family='.$item->family_urlname) . '" title="' . __('Download from Font Squirrel and install', 'fontsquirrel') . '">Installer</a>');
					echo $this->row_actions( $actions ); 
				?>
			</td>
			<td>
				<img src="<?php echo $item->preview_image ?>"/>
			</td>
		</tr><?php
	}
}
