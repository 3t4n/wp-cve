<?php

require_once dirname( __FILE__ ) . '/base.ctrl.php';

class CF7_CTRL_DATA_TABLE extends CF7_COMPLEMENTARY_CTRL {

	protected $title = 'Data Table';
	protected $description;
	protected $image_url = 'data-table/image.png';

	public function __construct( $container_obj, $slug ) {
		$this->description = __( 'Interactive table to display the record-set records.', 'cf7-datasource' );
		parent::__construct( $container_obj, $slug );

		if ( $this->container_obj->is_control_active( $slug ) ) {
			add_action( 'wpcf7_init', array( $this, 'wpcf7_init' ) );
			add_action( 'wpcf7_admin_init', array( $this, 'wpcf7_add_tag_generator_datatable' ), 55, 0 );
		}
	} // End __construct

	public function wpcf7_init() {
		wpcf7_add_form_tag(
			'datatable',
			array( $this, 'wpcf7_datatable_tag_handler' ),
			array(
				'name-attr' => true,
			)
		);
	} // End wpcf7_init

	public function wpcf7_datatable_tag_handler( $tag ) {
		$atts  = array();
		$class = wpcf7_form_controls_class( $tag->type );

		// For backward compatibility
		if ( preg_match( '/recordset\:([^\s\]]*)/i', $tag->name, $matches ) ) {
			$recordset = $matches[1];
			$tag->name = '';
		} else {
			$recordset = $tag->get_option( 'recordset', 'id', true );
		}

		if ( $recordset && ( $recordset = sanitize_key( $recordset ) ) != '' ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
			$atts['data-recordset'] = $recordset;
		}

		$atts['class'] = $tag->get_class_option( $class );

		$atts['data-autowidth']    = $tag->has_option( 'autowidth' );
		$atts['data-paging']       = $tag->has_option( 'paging' );
		$atts['data-lengthchange'] = $tag->has_option( 'lengthchange' );
		$atts['data-ordering']     = $tag->has_option( 'ordering' );
		$atts['data-scrollx']      = $tag->has_option( 'scrollx' );

		if ( $tag->has_option( 'scrolly' ) ) {
			$scrolly = $tag->get_option( 'scrolly', 'int', true );
			if ( is_numeric( $scrolly ) && ( $scrolly = intval( $scrolly ) ) != 0 ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
				$atts['data-scrolly'] = $scrolly;
			}
		}

		$atts['data-searching'] = $tag->has_option( 'searching' );

		$atts['data-columns'] = array();
		$columns              = $tag->pipes->to_array();

		foreach ( $columns as $column ) {
			if ( empty( $column ) ) {
				continue;
			}
			$column[0] = trim( empty( $column[0] ) ? $column[1] : $column[0] );
			$column[1] = trim( empty( $column[1] ) ? $column[0] : $column[1] );

			if ( empty( $column[0] ) || empty( $column[1] ) ) {
				continue;
			}
			$atts['data-columns'][] = $column;
		}

		$language = $tag->get_option( 'language', '', true );
		if ( ! empty( $language ) ) {
			$atts['data-language'] = $language;
		}

		$html = '<div ' . ( ! empty( $tag->name ) ? 'id="' . esc_attr( $tag->name ) . '"' : '' );
		foreach ( $atts as $attr_name => $attr_value ) {
			$html .= ' ' . $attr_name . '="' . esc_attr( is_array( $attr_value ) ? json_encode( $attr_value ) : $attr_value ) . '"';
		}
		$html .= '></div>';

		// Enqueue resources
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'cf7-ds-datatable-vendor-js', plugins_url( '/data-table/vendor/datatables.min.js', __FILE__ ), array( 'jquery' ), CF7_DATASOURCE::$version, true );
		wp_enqueue_style( 'cf7-ds-datatable-vendor-css', plugins_url( '/data-table/vendor/datatables.min.css', __FILE__ ), array(), CF7_DATASOURCE::$version );

		wp_enqueue_script( 'cf7-ds-datatable-js', plugins_url( '/data-table/script.js', __FILE__ ), array( 'cf7-ds-datatable-vendor-js' ), CF7_DATASOURCE::$version, true );
		wp_enqueue_style( 'cf7-ds-datatable-css', plugins_url( '/data-table/styles.css', __FILE__ ), array(), CF7_DATASOURCE::$version );

		return $html;
	} // End wpcf7_datatable_tag_handler

	public function wpcf7_add_tag_generator_datatable() {
		$tag_generator = WPCF7_TagGenerator::get_instance();
		$tag_generator->add(
			'datatable',
			__( 'Data Table', 'cf7-datasource' ),
			array( $this, 'wpcf7_tag_generator_datatable' ),
			array( 'nameless' => 1 )
		);
	} // End wpcf7_add_tag_generator_datatable

	public function wpcf7_tag_generator_datatable( $contact_form, $args = '' ) {
		$args        = wp_parse_args( $args, array() );
		$description = __( 'Generate a data-table with record-set records. For more details, see', 'cf7-datasource' );

		$desc_link = '<a href="https://cf7-datasource.dwbooster.com/complementary-controls-addon#data-table" target="_blank">' . esc_html__( 'Data table', 'cf7-datasource' ) . '</a>';

		?>
		<script>
		jQuery(function(){
			var $ = jQuery,
				form_editor = $('textarea[id="wpcf7-form"]');
			if(form_editor.length)
			{
				var form_structure = form_editor.val(),
					result = Array.from(form_structure.matchAll(new RegExp('\\[\\s*([^\\]]+)\\]', 'g'))),
					components,
					type,
					name,
					datalist_recordsets = $('datalist[id="<?php echo esc_attr( $args['content'] . '-recordset-fields' ); ?>"]');

				datalist_recordsets.html('');

				for(var i in result)
				{
					try
					{
						components = result[i][1].replace(/\s+/g, ' ').split(/\s/);
						type = components[0].replace(/\*/g, '').toLowerCase();
						name = components[1].replace(/"/g, '');
						if(type == 'cf7-recordset')
						{
							components = result[i][1].match(/\sid\s*=\s*['"]([^'"]+)['"]/);
							if(components)
								$('<option />').attr('value', components[1]).appendTo(datalist_recordsets);
						}
					}catch(err){continue;}
				}
			}
		});
		</script>
		<div class="control-box">
			<fieldset>
				<legend><?php echo sprintf( $description . ' %s.', $desc_link ); // phpcs:ignore WordPress.Security.EscapeOutput ?></legend>
				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-name' ); ?>"><?php echo esc_html( __( 'Id', 'contact-form-7' ) ); ?></label></th>
							<td><input type="text" name="name" class="tg-name oneline" id="<?php echo esc_attr( $args['content'] . '-name' ); ?>" /></td>
						</tr>

						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-recordset' ); ?>"><?php echo esc_html( __( 'Recordset field', 'cf7-datasource' ) ); ?></label></th>
							<td>
							<input type="text" name="recordset" class="oneline option" id="<?php echo esc_attr( $args['content'] . '-recordset' ); ?>" list="<?php echo esc_attr( $args['content'] . '-recordset-fields' ); ?>" />
							<datalist id="<?php echo esc_attr( $args['content'] . '-recordset-fields' ); ?>"></datalist>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-columns' ); ?>"><?php echo esc_html( __( 'Data table columns', 'cf7-datasource' ) ); ?></label></th>
							<td>
								<fieldset>
									<legend class="screen-reader-text"><?php echo esc_html( __( 'column title|record attribute', 'cf7-datasource' ) ); ?></legend>
									<span style="font-weight:bold;"><?php esc_html_e( 'column title|record attribute', 'cf7-datasource' ); ?></span><br>
									<textarea name="values" class="values" id="<?php echo esc_attr( $args['content'] . '-values' ); ?>"></textarea><br>
									<label for="<?php echo esc_attr( $args['content'] . '-values' ); ?>">
										<span class="description"><?php echo __( 'One pair <b>column title|record attribute</b> per line.<br>Use <b>| symbol</b> as separartor between column title and record attribute.', 'cf7-datasource' ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
									</label><br />

									<label><input type="checkbox" name="autowidth" class="option" /> <?php echo esc_html( __( 'Auto width columns', 'cf7-datasource' ) ); ?></label><br />

									<label><input type="checkbox" name="paging" class="option" /> <?php echo esc_html( __( 'Multipage table', 'cf7-datasource' ) ); ?></label>

									<label><input type="checkbox" name="lengthchange" class="option" /> <?php echo esc_html( __( 'When pagination is enabled, display option to change the number of records per page', 'cf7-datasource' ) ); ?></label>

									<label><input type="checkbox" name="ordering" class="option" /> <?php echo esc_html( __( 'Enable ordering of columns', 'cf7-datasource' ) ); ?></label>

									<label><input type="checkbox" name="scrollx" class="option" /> <?php echo esc_html( __( 'Show horizontal scrolling in too wide tables', 'cf7-datasource' ) ); ?></label>

									<label><?php echo esc_html( __( 'Table height, numbers only (optional)', 'cf7-datasource' ) ); ?><br>
									<input type="text" name="scrolly" class="option" id="<?php echo esc_attr( $args['content'] . '-scrolly' ); ?>" /></label>

									<label><input type="checkbox" name="searching" class="option" /> <?php echo esc_html( __( 'Include search box control in the table', 'cf7-datasource' ) ); ?></label>

									<label>
										<?php echo esc_html( __( 'URL to a JSON language file (optional)', 'cf7-datasource' ) ); ?><br>
										<input type="text" name="language" class="option" id="<?php echo esc_attr( $args['content'] . '-language' ); ?>" /><br>
										<a href="https://datatables.net/plug-ins/i18n/" target="_blank"><?php esc_html_e( 'Language files', 'cf7-datasource' ); ?></a>
									</label>

								</fieldset>
							</td>
						</tr>

						<tr>
							<th scope="row"><label for="<?php echo esc_attr( $args['content'] . '-class' ); ?>"><?php echo esc_html( __( 'Class attribute', 'contact-form-7' ) ); ?></label></th>
							<td><input type="text" name="class" class="classvalue oneline option" id="<?php echo esc_attr( $args['content'] . '-class' ); ?>" /></td>
						</tr>
					</tbody>
				</table>
			</fieldset>
		</div>

		<div class="insert-box">
			<input type="text" name="datatable" class="tag code" readonly="readonly" onfocus="this.select()" />

			<div class="submitbox">
				<input type="button" class="button button-primary insert-tag" value="<?php echo esc_attr( __( 'Insert Tag', 'contact-form-7' ) ); ?>" />
			</div>
		</div>
		<?php
	} // End wpcf7_tag_generator_datatable

} // End CF7_CTRL_DATA_TABLE

if ( isset( $container_obj ) && $slug ) {
	new CF7_CTRL_DATA_TABLE( $container_obj, $slug );
}
