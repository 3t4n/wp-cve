<style type="text/css">
	.advanced_home,
	.advanced_home_width,
	.advanced_frontpage,
	.advanced_frontpage_width,
	.advanced_archive,
	.advanced_archive_width,
	.advanced_search,
	.advanced_search_width,
	.page_all,
	.page_selection
	{ display: none; }

	<?php
	if ( 'selection' === $options['advanced_home'] ) {
		echo '.advanced_home{ display: table-row; }';
		echo '.advanced_home_width{ display: table-row; }';
	} elseif ( 'all' === $options['advanced_home'] ) {
		echo '.advanced_home{ display: none; }';
		echo '.advanced_home_width{ display: table-row; }';
	}

	if ( 'selection' === $options['advanced_frontpage'] ) {
		echo '.advanced_frontpage{ display: table-row; }';
		echo '.advanced_frontpage_width{ display: table-row; }';
	} elseif ( 'all' === $options['advanced_frontpage'] ) {
		echo '.advanced_frontpage{ display: none; }';
		echo '.advanced_frontpage_width{ display: table-row; }';
	}

	if ( 'selection' === $options['advanced_archive'] ) {
		echo '.advanced_archive{ display: table-row; }';
		echo '.advanced_archive_width{ display: table-row; }';
	} elseif ( 'all' === $options['advanced_archive'] ) {
		echo '.advanced_archive{ display: none; }';
		echo '.advanced_archive_width{ display: table-row; }';
	}

	if ( 'selection' === $options['advanced_search'] ) {
		echo '.advanced_search{ display: table-row; }';
		echo '.advanced_search_width{ display: table-row; }';
	} elseif ( 'all' === $options['advanced_search'] ) {
		echo '.advanced_search{ display: none; }';
		echo '.advanced_search_width{ display: table-row; }';
	}

	if ( 'all' === $options['advanced_page_main'] ) {
		echo '.page_all{ display: table-row; }';
		echo '.page_selection{ display: none; }';
	} elseif ( 'selection' === $options['advanced_page_main'] ) {
		echo '.page_all{ display: table-row; }';
		echo '.page_selection{ display: table-row; }';
	}
	?>
</style>

<?php
$excerpt_yes_options = array(
	'0' => array(
		'value' => 'no',
		'label' => __( 'No', 'easy-custom-auto-excerpt' ),
	),
	'1' => array(
		'value' => 'yes',
		'label' => __( 'Yes', 'easy-custom-auto-excerpt' ),
	),
);

$advanced_all_selection = array(
	'0' => array(
		'value' => 'disable',
		'label' => __( 'Disable', 'easy-custom-auto-excerpt' ),
	),
	'1' => array(
		'value' => 'all',
		'label' => __( 'All Content', 'easy-custom-auto-excerpt' ),
	),
	'2' => array(
		'value' => 'selection',
		'label' => __( 'Selected Post type and Categories', 'easy-custom-auto-excerpt' ),
	),
);


$search_select = array(
	'name'         => 'tonjoo_ecae_options[search]',
	'description'  => '',
	'label'        => __( 'Search excerpt', 'easy-custom-auto-excerpt' ),
	'value'        => $options['search'],
	'select_array' => $excerpt_yes_options,
);

$home_select = array(
	'name'         => 'tonjoo_ecae_options[home]',
	'description'  => '',
	'label'        => __( 'Blog page excerpt', 'easy-custom-auto-excerpt' ),
	'value'        => $options['home'],
	'select_array' => $excerpt_yes_options,
);

$front_page_select = array(
	'name'         => 'tonjoo_ecae_options[front_page]',
	'description'  => '',
	'label'        => __( 'Front page excerpt', 'easy-custom-auto-excerpt' ),
	'value'        => $options['front_page'],
	'select_array' => $excerpt_yes_options,
);

$archive_select = array(
	'name'         => 'tonjoo_ecae_options[archive]',
	'description'  => '',
	'label'        => __( 'Archive excerpt', 'easy-custom-auto-excerpt' ),
	'value'        => $options['archive'],
	'select_array' => $excerpt_yes_options,
);

/**
 * Excerpt in page
 */
$page_on_front  = get_option( 'page_on_front' );
$page_for_posts = get_option( 'page_for_posts' );

$args = array(
	'selected' => explode( '|', $options['excerpt_in_page'] ),
	'echo'     => 0,
	'exclude'  => "$page_on_front,$page_for_posts",
	'walker'   => new Walker_PageDropdown_Multiple(),
);

$exc_in_page = wp_dropdown_pages( $args );
$exc_in_page = preg_replace( '#^\s*<select[^>]*>#', '', $exc_in_page );
$exc_in_page = preg_replace( '#</select>\s*$#', '', $exc_in_page );
?>

<tr valign="top"><th colspan="3" style="padding: 5px 0">
	<a class="button location-settings-btn" id="basic" href="javascript:;"><?php esc_html_e( 'Basic', 'easy-custom-auto-excerpt' ); ?></a>&nbsp;
	<a class="button location-settings-btn" id="advanced" href="javascript:;"><?php esc_html_e( 'Advanced', 'easy-custom-auto-excerpt' ); ?></a>&nbsp;

	<input type="hidden" name='tonjoo_ecae_options[location_settings_type]' value="<?php echo esc_attr( $options['location_settings_type'] ); ?>" style="display:none;">
</th></tr>

<style type="text/css">
	<?php if ( 'basic' === $options['location_settings_type'] ) : ?>
	#advanced-form {
		display: none;
	}
	<?php elseif ( 'advanced' === $options['location_settings_type'] ) : ?>
	#basic-form {
		display: none;
	}
	<?php endif; ?>
</style>

<tbody class="location-settings-form" id="basic-form">

<tr><td colspan=3><h3 class="meta-subtitle"><?php esc_html_e( 'Basic Settings', 'easy-custom-auto-excerpt' ); ?></h3></td></tr>

<?php
/**
 * Print the options
 */
tj_print_select_option( $home_select );
tj_print_select_option( $front_page_select );
tj_print_select_option( $archive_select );
tj_print_select_option( $search_select );
?>

<tr valign="top">
	<th><?php esc_html_e( 'Excerpt in page', 'easy-custom-auto-excerpt' ); ?></th>
	<td colspan="2">
		<div class="ordinary-select-2">
			<select name="excerpt_in_page[]" multiple="multiple" class="excerpt-in-select2">
				<?php echo $exc_in_page; ?>
			</select>
		</div>
	</td>
</tr>

</tbody>

<tbody class="location-settings-form" id="advanced-form">

<?php
/**
 * Excerpt in category
 */
function category_excerpt( $name, $options, $valueonly = false, $selected = false ) {
	$args       = array( 'public' => true );
	$taxonomies = get_taxonomies( $args, 'objects' );

	// unset unexpected taxonomy.
	unset( $taxonomies['post_tag'], $taxonomies['link_category'], $taxonomies['post_format'] );

	$all_cat = '';

	foreach ( $taxonomies as $key => $value ) {
		$args       = array(
			'selected'   => $selected ? $selected : $options[ $name ],
			'echo'       => 0,
			'hide_empty' => 0,
			'taxonomy'   => $key,
			'walker'     => new Walker_CategoryDropdown_Multiple(),
		);
		$exc_in_cat = wp_dropdown_categories( $args );
		$exc_in_cat = preg_replace( '#^\s*<select[^>]*>#', '', $exc_in_cat );
		$exc_in_cat = preg_replace( '#</select>\s*$#', '', $exc_in_cat );

		$all_cat .= "<optgroup label='{$value->labels->name}'>";
		$all_cat .= $exc_in_cat;
		$all_cat .= '</optgroup>';
	}

	if ( $valueonly ) {
		echo $all_cat;
	} else {
		echo '<div class="ordinary-select-2">';
		echo '<select name="' . esc_attr( $name ) . '[]" multiple="multiple" class="excerpt-in-select2">';
		echo $all_cat;
		echo '</select></div>';
	}
}

/**
 * Excerpt in post type
 */
function post_type_excerpt( $name, $options, $valueonly = false, $selected = false ) {
	$args       = array( 'public' => true );
	$post_types = get_post_types( $args, 'objects' );

	// unset unexpected post type.
	unset( $post_types['page'], $post_types['attachment'] );

	$post_type = '';

	$arr_sel_post_type = $selected ? $selected : $options[ $name ];

	foreach ( $post_types as $key => $value ) {
		$selected = '';

		if ( is_array( $arr_sel_post_type ) ) {
			$selected = in_array( $key, $arr_sel_post_type ) ? 'selected' : '';
		}

		$post_type .= "<option value='$key' $selected>";
		$post_type .= $value->labels->name;
		$post_type .= '</option>';
	}

	if ( $valueonly ) {
		$print = $post_type;
	} else {
		$print  = '<div class="ordinary-select-2">';
		$print .= '<select name="' . $name . '[]" multiple="multiple" class="excerpt-in-select2">';
		$print .= $post_type;
		$print .= '</select></div>';
	}

	echo $print;
}

/**
 * Premium anouncement
 */
$anouncement = '';
?>

<!-- Post Page Excerpt -->
<tr><td colspan=3><h3 class="meta-subtitle"><?php esc_html_e( 'Blog Page Excerpt', 'easy-custom-auto-excerpt' ); ?> <?php echo esc_html( $anouncement ); ?> </h3></td></tr>

<?php
$advanced_select = array(
	'name'         => 'tonjoo_ecae_options[advanced_home]',
	'description'  => '',
	'label'        => __( 'Blog page excerpt', 'easy-custom-auto-excerpt' ),
	'value'        => $options['advanced_home'],
	'select_array' => $advanced_all_selection,
);

tj_print_select_option( $advanced_select );
?>

<tr valign="top" class="advanced_home_width">
	<th><?php esc_html_e( 'Excerpt size', 'easy-custom-auto-excerpt' ); ?></th>
	<td><input type="number" name="tonjoo_ecae_options[advanced_home_width]" value="<?php echo esc_attr( $options['advanced_home_width'] ); ?>"></td>
	<td><?php esc_html_e( 'If greater than 0, the general excerpt size and method will be overridden', 'easy-custom-auto-excerpt' ); ?></td>
</tr>

<tr valign="top" class="advanced_home">
	<th><?php esc_html_e( 'Post type', 'easy-custom-auto-excerpt' ); ?></th>
	<td colspan="2">
		<?php post_type_excerpt( 'home_post_type', $options ); ?>
	</td>
</tr>

<tr valign="top" class="advanced_home">
	<th><?php esc_html_e( 'Categories', 'easy-custom-auto-excerpt' ); ?></th>
	<td colspan="2">
		<?php category_excerpt( 'home_category', $options ); ?>
	</td>
</tr>

<!-- Front Page Excerpt -->
<tr><td colspan=3><h3 class="meta-subtitle"><?php esc_html_e( 'Front Page Excerpt', 'easy-custom-auto-excerpt' ); ?> <?php echo esc_html( $anouncement ); ?> </h3></td></tr>

<?php
$advanced_select = array(
	'name'         => 'tonjoo_ecae_options[advanced_frontpage]',
	'description'  => '',
	'label'        => __( 'Front page excerpt', 'easy-custom-auto-excerpt' ),
	'value'        => $options['advanced_frontpage'],
	'select_array' => $advanced_all_selection,
);

tj_print_select_option( $advanced_select );
?>

<tr valign="top" class="advanced_frontpage_width">
	<th><?php esc_html_e( 'Excerpt size', 'easy-custom-auto-excerpt' ); ?></th>
	<td><input type="number" name="tonjoo_ecae_options[advanced_frontpage_width]" value="<?php echo esc_attr( $options['advanced_frontpage_width'] ); ?>"></td>
	<td><?php esc_html_e( 'If greater than 0, the general excerpt size and method will be overridden', 'easy-custom-auto-excerpt' ); ?></td>
</tr>

<tr valign="top" class="advanced_frontpage">
	<th><?php esc_html_e( 'Post type', 'easy-custom-auto-excerpt' ); ?></th>
	<td colspan="2">
		<?php post_type_excerpt( 'frontpage_post_type', $options ); ?>
	</td>
</tr>

<tr valign="top" class="advanced_frontpage">
	<th><?php esc_html_e( 'Categories', 'easy-custom-auto-excerpt' ); ?></th>
	<td colspan="2">
		<?php category_excerpt( 'frontpage_category', $options ); ?>
	</td>
</tr>

<!-- Archive Excerpt -->
<tr><td colspan=3><h3 class="meta-subtitle"><?php esc_html_e( 'Archive Excerpt', 'easy-custom-auto-excerpt' ); ?> <?php echo esc_html( $anouncement ); ?> </h3></td></tr>

<?php
$advanced_select = array(
	'name'         => 'tonjoo_ecae_options[advanced_archive]',
	'description'  => '',
	'label'        => __( 'Archive excerpt', 'easy-custom-auto-excerpt' ),
	'value'        => $options['advanced_archive'],
	'select_array' => $advanced_all_selection,
);

tj_print_select_option( $advanced_select );
?>

<tr valign="top" class="advanced_archive_width">
	<th><?php esc_html_e( 'Excerpt size', 'easy-custom-auto-excerpt' ); ?></th>
	<td><input type="number" name="tonjoo_ecae_options[advanced_archive_width]" value="<?php echo esc_attr( $options['advanced_archive_width'] ); ?>"></td>
	<td><?php esc_html_e( 'If greater than 0, the general excerpt size and method will be overridden', 'easy-custom-auto-excerpt' ); ?></td>
</tr>

<tr valign="top" class="advanced_archive">
	<th><?php esc_html_e( 'Post type', 'easy-custom-auto-excerpt' ); ?></th>
	<td colspan="2">
		<?php post_type_excerpt( 'archive_post_type', $options ); ?>
	</td>
</tr>

<tr valign="top" class="advanced_archive">
	<th><?php esc_html_e( 'Categories', 'easy-custom-auto-excerpt' ); ?></th>
	<td colspan="2">
		<?php category_excerpt( 'archive_category', $options ); ?>
	</td>
</tr>

<!-- Search Excerpt -->
<tr><td colspan=3><h3 class="meta-subtitle"><?php esc_html_e( 'Search Excerpt', 'easy-custom-auto-excerpt' ); ?> <?php echo esc_html( $anouncement ); ?> </h3></td></tr>

<?php
$advanced_select = array(
	'name'         => 'tonjoo_ecae_options[advanced_search]',
	'description'  => '',
	'label'        => __( 'Search excerpt', 'easy-custom-auto-excerpt' ),
	'value'        => $options['advanced_search'],
	'select_array' => $advanced_all_selection,
);

tj_print_select_option( $advanced_select );
?>

<tr valign="top" class="advanced_search_width">
	<th><?php esc_html_e( 'Excerpt size', 'easy-custom-auto-excerpt' ); ?></th>
	<td><input type="number" name="tonjoo_ecae_options[advanced_search_width]" value="<?php echo esc_html( $options['advanced_search_width'] ); ?>"></td>
	<td><?php esc_html_e( 'If greater than 0, the general excerpt size and method will be overridden', 'easy-custom-auto-excerpt' ); ?></td>
</tr>

<tr valign="top" class="advanced_search">
	<th><?php esc_html_e( 'Post type', 'easy-custom-auto-excerpt' ); ?></th>
	<td colspan="2">
		<?php post_type_excerpt( 'search_post_type', $options ); ?>
	</td>
</tr>

<tr valign="top" class="advanced_search">
	<th><?php esc_html_e( 'Categories', 'easy-custom-auto-excerpt' ); ?></th>
	<td colspan="2">
		<?php category_excerpt( 'search_category', $options ); ?>
	</td>
</tr>


<!-- Page Excerpt -->
<tr><td colspan=3><h3 class="meta-subtitle"><?php esc_html_e( 'Page Excerpt', 'easy-custom-auto-excerpt' ); ?> <?php echo esc_html( $anouncement ); ?> </h3></td></tr>

<?php
$args = array(
	'selected' => 0,
	'name'     => 'advanced_page[]',
);

$advanced_page  = $options['advanced_page'];
$page_post_type = $options['page_post_type'];
$page_category  = $options['page_category'];

$count_advanced_page = count( $advanced_page );

$advanced_select = array(
	'name'         => 'tonjoo_ecae_options[advanced_page_main]',
	'description'  => '',
	'label'        => __( 'Page excerpt', 'easy-custom-auto-excerpt' ),
	'value'        => $options['advanced_page_main'],
	'select_array' => $advanced_all_selection,
);

tj_print_select_option( $advanced_select );
?>

<?php
/**
 * Excerpt in page for the advanced options
 */
$args            = array(
	'selected' => $options['excerpt_in_page_advanced'],
	'echo'     => 0,
	'exclude'  => "$page_on_front,$page_for_posts",
	'walker'   => new Walker_PageDropdown_Multiple(),
);
$exc_in_page_adv = wp_dropdown_pages( $args );
$exc_in_page_adv = preg_replace( '#^\s*<select[^>]*>#', '', $exc_in_page_adv );
$exc_in_page_adv = preg_replace( '#</select>\s*$#', '', $exc_in_page_adv );
?>

<tr valign="top" class="page_all">
	<th><?php esc_html_e( 'Excerpt size', 'easy-custom-auto-excerpt' ); ?></th>
	<td><input type="number" name="tonjoo_ecae_options[advanced_page_main_width]" value="<?php echo esc_html( $options['advanced_page_main_width'] ); ?>"></td>
	<td><?php esc_html_e( 'If greater than 0, the general excerpt size and method will be overridden', 'easy-custom-auto-excerpt' ); ?></td>
</tr>

<tr valign="top" class="page_all">
	<th><?php esc_html_e( 'Excerpt in page', 'easy-custom-auto-excerpt' ); ?></th>
	<td colspan="2">
		<div class="ordinary-select-2">
			<select name="excerpt_in_page_advanced[]" multiple="multiple" class="excerpt-in-select2">
				<?php echo $exc_in_page_adv; ?>
			</select>
		</div>
	</td>
</tr>

<tr class="page_selection">
	<td colspan="3" style="padding: 5px 0px;"><hr></td>
</tr>

<tr class="page_selection">
	<td colspan="3" style="padding:0px;">
		<!--page-excerpt-clone-->
		<div id="page-excerpt-clone">

			<?php if ( 0 === $count_advanced_page ) : ?>

				<div class="toclone">
					<table class="form-table">
						<tr valign="top">
							<th><?php esc_html_e( 'Page', 'easy-custom-auto-excerpt' ); ?></th>
							<td colspan="2" class="country">
								<?php wp_dropdown_pages( $args ); ?>
							</td>
						</tr>

						<tr valign="top">
							<th>Post type</th>
							<td colspan="2" class="country">
								<div class="ordinary-select-2">
								<select name="page_post_type[0][]" multiple="multiple" class="page_post_type_select">
								<!-- <option selected="selected" value="0" locked="locked">Select</option> -->
								<?php post_type_excerpt( 'page_post_type', $options, true ); ?>
								</select>
								</div>
							</td>
						</tr>

						<tr valign="top">
							<th>Categories</th>
							<td colspan="2">
								<div class="ordinary-select-2">
								<select name="page_category[0][]" multiple="multiple" class="page_category_select">
								<!-- <option selected="selected" value="0" locked="locked">Select</option> -->
								<?php category_excerpt( 'page_category', $options, true ); ?>
								</select>
								</div>
							</td>
						</tr>
					</table>
					<a href="#" class="button clone"><?php esc_html_e( 'New Page Excerpt', 'easy-custom-auto-excerpt' ); ?></a>
					<a href="#" class="button delete"><?php esc_html_e( 'Remove', 'easy-custom-auto-excerpt' ); ?></a>
				</div>

			<?php else : ?>

				<?php for ( $i = 0; $i < $count_advanced_page; $i++ ) : ?>

					<div class="toclone">
						<table class="form-table">
							<tr valign="top">
								<th><?php esc_html_e( 'Page', 'easy-custom-auto-excerpt' ); ?></th>
								<td colspan="2" class="country">
									<?php
									$args = array(
										'selected' => $advanced_page[ $i ],
										'name'     => 'advanced_page[]',
										'exclude'  => "$page_on_front,$page_for_posts",
									);

									wp_dropdown_pages( $args )
									?>
								</td>
							</tr>

							<tr valign="top">
								<th>Post type</th>
								<td colspan="2" class="country">
									<div class="ordinary-select-2">
										<select name="page_post_type[<?php echo esc_attr( $i ); ?>][]" multiple="multiple" class="page_post_type_select">
										<!-- <option value="0" selected="selected" locked="locked">Select</option> -->
										<?php post_type_excerpt( 'page_post_type', $options, true, $page_post_type[ $i ] ); ?>
										</select>
									</div>
								</td>
							</tr>

							<tr valign="top">
								<th>Categories</th>
								<td colspan="2">
									<div class="ordinary-select-2">
										<select name="page_category[<?php echo esc_attr( $i ); ?>][]" multiple="multiple" class="page_category_select">
											<?php category_excerpt( 'page_category', $options, true, $page_category[ $i ] ); ?>
										</select>
									</div>
								</td>
							</tr>
						</table>
						<a href="#" class="button clone"><?php esc_html_e( 'New Page Excerpt', 'easy-custom-auto-excerpt' ); ?></a>
						<a href="#" class="button delete"><?php esc_html_e( 'Remove', 'easy-custom-auto-excerpt' ); ?></a>
					</div>

				<?php endfor; ?>

			<?php endif; ?>
		</div>
		<!--page-excerpt-clone end-->
	</td>
</tr>

</tbody>
