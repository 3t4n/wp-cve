<?php

global  $woorousell_fs ;
/**
 * Edit form layout
 *
 * @author 		MojofyWP
 * @package 	builder/settings-page/views
 * 
 */
$carousel_type = ( isset( $values['carousel_type'] ) ? $values['carousel_type'] : 'product' );
?>
<!-- General Settings -->
<div id="wrslb-form-general" class="wrslb-form-section">

	<h3 class="wrslb-form-section-title"><?php 
_e( 'General', WRSL_SLUG );
?></h3>

	<div class="wrslb-form-control">
		<label class="wrslb-input-label" for="<?php 
echo  $this->input_id( 'title' ) ;
?>"><?php 
_e( 'Name', WRSL_SLUG );
?></label>
		<input<?php 
echo  $this->attributes( 'title' ) ;
?> type="text" class="wrslb-input-text" value="<?php 
echo  $this->get_value( 'title', $values ) ;
?>">
	</div><!-- .wrslb-form-control -->

	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'shortcode' ) ;
?>><?php 
_e( 'Shortcode', WRSL_SLUG );
?></label>
				<input<?php 
echo  $this->attributes( 'shortcode' ) ;
?> type="text" class="wrslb-input-text_medium wrslb-input-readonly" value='[woorousell id="<?php 
echo  $id ;
?>"]' readonly>
			</div><!-- .wrslb-form-control -->
		</li>
	</ul>

</div><!-- .wrslb-form-section -->

<!-- Product Query Settings -->
<div id="wrslb-form-product-query" class="wrslb-form-section">

	<h3 class="wrslb-form-section-title"><?php 
_e( 'Product Query', WRSL_SLUG );
?></h3>

	<?php 
$terms = get_terms( 'product_cat', array(
    'hide_empty' => false,
) );

if ( !is_wp_error( $terms ) ) {
    ?>
			<div class="wrslb-form-control">
				<label class="wrslb-input-label" for=<?php 
    echo  $this->input_id( 'category' ) ;
    ?>><?php 
    _e( 'Category to Display', WRSL_SLUG );
    ?></label>
				<select<?php 
    echo  $this->attributes( 'category' ) ;
    ?> class="wrslb-input-select">
					<option value="0" <?php 
    echo  $this->selected( $values, 'category', '0' ) ;
    ?>><?php 
    _e( 'All', WRSL_SLUG );
    ?></option>
					<?php 
    
    if ( !empty($terms) && is_array( $terms ) ) {
        foreach ( $terms as $t ) {
            ?>
						<option value="<?php 
            echo  $t->term_id ;
            ?>" <?php 
            echo  $this->selected( $values, 'category', $t->term_id ) ;
            ?>><?php 
            echo  $t->name ;
            ?></option>
						<?php 
        }
        // end - foreach
    }
    
    // end - !empty( $terms )
    ?>
				</select>
			</div><!-- .wrslb-form-control -->
		<?php 
    // end - $woorousell_fs
}

// // if !is_wp_error
?>

	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'posts_per_page' ) ;
?>><?php 
_e( 'Number of items to show', WRSL_SLUG );
?></label>
				<input<?php 
echo  $this->attributes( 'posts_per_page' ) ;
?> type="number" class="wrslb-input-number" value="<?php 
echo  $this->get_value( 'posts_per_page', $values ) ;
?>" min="-1" max="100">
			</div><!-- .wrslb-form-control -->
		</li>
	</ul>
	<?php 
$sort_options = array(
    'newest-first' => __( 'Newest First', WRSL_SLUG ),
    'oldest-first' => __( 'Oldest First', WRSL_SLUG ),
    'title-asc'    => __( 'Title A-Z', WRSL_SLUG ),
    'title-desc'   => __( 'Title Z-A', WRSL_SLUG ),
    'price-asc'    => __( 'Price ( low to high )', WRSL_SLUG ),
    'price-desc'   => __( 'Price ( high to low )', WRSL_SLUG ),
);
?>
	<div class="wrslb-form-control wrslb-input-type-optselector">
		<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'order' ) ;
?>>
			<?php 
_e( 'Sort by', WRSL_SLUG );
?>
		</label>
		<div class="wrslb-optselector-options">
		<?php 
foreach ( $sort_options as $sort_value => $sort_name ) {
    ?>
				<button class="wrslb-optselector-btn<?php 
    echo  $this->option_selected( $values, 'order', $sort_value ) ;
    ?>" data-optselector-value="<?php 
    echo  $sort_value ;
    ?>"><?php 
    echo  $sort_name ;
    ?></button>
				<?php 
}
?>
		</div>
		<input<?php 
echo  $this->attributes( 'order' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'order', $values ) ;
?>" class="wrslb-optselector-input" />
	</div><!-- .wrslb-form-control -->

	<?php 
// end - $woorousell_fs->is__premium_only()
?>

	<!-- misc -->
	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control wrslb-input-type-optselector">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'hide_on_sale' ) ;
?>>
					<?php 
_e( 'on-sale product(s) Filter', WRSL_SLUG );
?>
				</label>
				<div class="wrslb-optselector-options">
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'hide_on_sale', 'on' ) ;
?>" data-optselector-value="on"><?php 
_e( 'Hide All', WRSL_SLUG );
?></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'hide_on_sale', '' ) ;
?>" data-optselector-value=""><?php 
_e( 'Show All', WRSL_SLUG );
?></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'hide_on_sale', 'only-sale' ) ;
?>" data-optselector-value="only-sale"><?php 
_e( 'Show Only on-sale Products', WRSL_SLUG );
?></button>
				</div>
				<input<?php 
echo  $this->attributes( 'hide_on_sale' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'hide_on_sale', $values ) ;
?>" class="wrslb-optselector-input" />
			</div><!-- .wrslb-form-control -->
		</li>
		<li>
			<div class="wrslb-form-control wrslb-input-type-optselector">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'hide_oos' ) ;
?>>
					<?php 
_e( 'Hide Out-of-stock product(s)', WRSL_SLUG );
?>
				</label>
				<div class="wrslb-optselector-options">
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'hide_oos', 'on' ) ;
?>" data-optselector-value="on"><?php 
_e( 'Yes', WRSL_SLUG );
?></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'hide_oos', '' ) ;
?>" data-optselector-value=""><?php 
_e( 'No', WRSL_SLUG );
?></button>
				</div>
				<input<?php 
echo  $this->attributes( 'hide_oos' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'hide_oos', $values ) ;
?>" class="wrslb-optselector-input" />
			</div><!-- .wrslb-form-control -->
		</li>
	</ul><!-- .wrslb-form-row -->

</div><!-- .wrslb-form-section -->

<!-- Columns Settings -->
<div id="wrslb-form-columns" class="wrslb-form-section">

	<h3 class="wrslb-form-section-title"><?php 
_e( 'Columns', WRSL_SLUG );
?></h3>

	<div class="wrslb-form-control wrslb-input-type-optselector">
		<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'total_col' ) ;
?>>
			<?php 
_e( 'Columns', WRSL_SLUG );
?>
		</label>
		<div class="wrslb-optselector-options">
			<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'total_col', '1' ) ;
?>" data-optselector-value="1"><?php 
_e( '1 Column', WRSL_SLUG );
?></button>
			<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'total_col', '2' ) ;
?>" data-optselector-value="2"><?php 
_e( '2 Columns', WRSL_SLUG );
?></button>
			<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'total_col', '3' ) ;
?>" data-optselector-value="3"><?php 
_e( '3 Columns', WRSL_SLUG );
?></button>
			<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'total_col', '4' ) ;
?>" data-optselector-value="4"><?php 
_e( '4 Columns', WRSL_SLUG );
?></button>
			<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'total_col', '5' ) ;
?>" data-optselector-value="5"><?php 
_e( '5 Columns', WRSL_SLUG );
?></button>
			<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'total_col', '6' ) ;
?>" data-optselector-value="6"><?php 
_e( '6 Columns', WRSL_SLUG );
?></button>
		</div>
		<input<?php 
echo  $this->attributes( 'total_col' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'total_col', $values ) ;
?>" class="wrslb-optselector-input" />
	</div><!-- .wrslb-form-control -->

	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control wrslb-form-colorpicker">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'col_bg' ) ;
?>><?php 
_e( 'Background Color', WRSL_SLUG );
?></label>
				<input<?php 
echo  $this->attributes( 'col_bg' ) ;
?> type="text" class="wrslb-input-colorpicker" value="<?php 
echo  $this->get_value( 'col_bg', $values ) ;
?>">
			</div><!-- .wrslb-form-control -->
		</li>
		<li>
			<div class="wrslb-form-control wrslb-form-colorpicker">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'btn_color' ) ;
?>><?php 
_e( 'Button Color', WRSL_SLUG );
?></label>
				<input<?php 
echo  $this->attributes( 'btn_color' ) ;
?> type="text" class="wrslb-input-colorpicker" value="<?php 
echo  $this->get_value( 'btn_color', $values ) ;
?>">
			</div><!-- .wrslb-form-control -->
		</li>	
	</ul><!-- .wrslb-form-row -->

<?php 
// end - is__premium_only
?>

</div><!-- .wrslb-form-section -->


<!-- Display Settings -->
<div id="wrslb-form-display" class="wrslb-form-section">

	<h3 class="wrslb-form-section-title"><?php 
_e( 'Display', WRSL_SLUG );
?></h3>

	<div class="wrslb-form-control wrslb-input-type-optselector">
		<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'box_style' ) ;
?>>
			<?php 
_e( 'Layout', WRSL_SLUG );
?>
		</label>
		<div class="wrslb-optselector-options">
			<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'box_style', 'style-1' ) ;
?>" data-optselector-value="style-1"><?php 
_e( 'Style 1', WRSL_SLUG );
?></button>
			<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'box_style', 'style-2' ) ;
?>" data-optselector-value="style-2"><?php 
_e( 'Style 2', WRSL_SLUG );
?></button>
		<?php 
?>
		</div>
		<input<?php 
echo  $this->attributes( 'box_style' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'box_style', $values ) ;
?>" class="wrslb-optselector-input" />
	</div><!-- .wrslb-form-control -->

	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control wrslb-input-type-optselector">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'show_titles' ) ;
?>>
					<?php 
_e( 'Show Titles', WRSL_SLUG );
?>
				</label>
				<div class="wrslb-optselector-options">
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_titles', 'on' ) ;
?>" data-optselector-value="on"><?php 
_e( 'Yes', WRSL_SLUG );
?></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_titles', '' ) ;
?>" data-optselector-value=""><?php 
_e( 'No', WRSL_SLUG );
?></button>
				</div>
				<input<?php 
echo  $this->attributes( 'show_titles' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'show_titles', $values ) ;
?>" class="wrslb-optselector-input" />
			</div><!-- .wrslb-form-control -->
		</li>
	</ul><!-- .wrslb-form-row -->

	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control wrslb-input-type-optselector">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'show_excerpts' ) ;
?>>
					<?php 
_e( 'Show Excerpts', WRSL_SLUG );
?>
				</label>
				<div class="wrslb-optselector-options">
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_excerpts', 'on' ) ;
?>" data-optselector-value="on"><?php 
_e( 'Yes', WRSL_SLUG );
?></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_excerpts', '' ) ;
?>" data-optselector-value=""><?php 
_e( 'No', WRSL_SLUG );
?></button>
				</div>
				<input<?php 
echo  $this->attributes( 'show_excerpts' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'show_excerpts', $values ) ;
?>" class="wrslb-optselector-input" />
			</div><!-- .wrslb-form-control -->
		</li>
		<li>
			<div class="wrslb-form-control" <?php 
echo  $this->show_if( 'show_excerpts', 'on', 'opt_selected' ) ;
?>>
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'excerpt_length' ) ;
?>><?php 
_e( 'Excerpts Length', WRSL_SLUG );
?></label>
				<input<?php 
echo  $this->attributes( 'excerpt_length' ) ;
?> type="number" class="wrslb-input-number" value="<?php 
echo  $this->get_value( 'excerpt_length', $values ) ;
?>">
			</div><!-- .wrslb-form-control -->
		</li>
	</ul><!-- .wrslb-form-row -->

	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control wrslb-input-type-optselector">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'show_price' ) ;
?>>
					<?php 
_e( 'Show Price', WRSL_SLUG );
?>
				</label>
				<div class="wrslb-optselector-options">
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_price', 'on' ) ;
?>" data-optselector-value="on"><?php 
_e( 'Yes', WRSL_SLUG );
?></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_price', '' ) ;
?>" data-optselector-value=""><?php 
_e( 'No', WRSL_SLUG );
?></button>
				</div>
				<input<?php 
echo  $this->attributes( 'show_price' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'show_price', $values ) ;
?>" class="wrslb-optselector-input" />
			</div><!-- .wrslb-form-control -->
		</li>
		<li>
			<div class="wrslb-form-control wrslb-input-type-optselector">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'show_badges' ) ;
?>>
					<?php 
_e( 'Show Sale Badge', WRSL_SLUG );
?>
				</label>
				<div class="wrslb-optselector-options">
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_badges', 'on' ) ;
?>" data-optselector-value="on"><?php 
_e( 'Yes', WRSL_SLUG );
?></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_badges', '' ) ;
?>" data-optselector-value=""><?php 
_e( 'No', WRSL_SLUG );
?></button>
				</div>
				<input<?php 
echo  $this->attributes( 'show_badges' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'show_badges', $values ) ;
?>" class="wrslb-optselector-input" />
			</div><!-- .wrslb-form-control -->
		</li>
	</ul><!-- .wrslb-form-row -->

	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control wrslb-input-type-optselector">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'show_ratings' ) ;
?>>
					<?php 
_e( 'Show Ratings', WRSL_SLUG );
?>
				</label>
				<div class="wrslb-optselector-options">
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_ratings', 'on' ) ;
?>" data-optselector-value="on"><?php 
_e( 'Yes', WRSL_SLUG );
?></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_ratings', '' ) ;
?>" data-optselector-value=""><?php 
_e( 'No', WRSL_SLUG );
?></button>
				</div>
				<input<?php 
echo  $this->attributes( 'show_ratings' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'show_ratings', $values ) ;
?>" class="wrslb-optselector-input" />
			</div><!-- .wrslb-form-control -->
		</li>
		<li>
			<div class="wrslb-form-control wrslb-input-type-optselector">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'show_buy_button' ) ;
?>>
					<?php 
_e( 'Show Buy Button', WRSL_SLUG );
?>
				</label>
				<div class="wrslb-optselector-options">
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_buy_button', 'on' ) ;
?>" data-optselector-value="on"><?php 
_e( 'Yes', WRSL_SLUG );
?></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'show_buy_button', '' ) ;
?>" data-optselector-value=""><?php 
_e( 'No', WRSL_SLUG );
?></button>
				</div>
				<input<?php 
echo  $this->attributes( 'show_buy_button' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'show_buy_button', $values ) ;
?>" class="wrslb-optselector-input" />
			</div><!-- .wrslb-form-control -->
		</li>
	</ul><!-- .wrslb-form-row -->

	<div class="wrslb-form-control wrslb-input-type-optselector">
		<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'content_align' ) ;
?>>
			<?php 
_e( 'Content Alignment', WRSL_SLUG );
?>
		</label>
		<div class="wrslb-optselector-options">
			<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'content_align', 'text-left' ) ;
?>" data-optselector-value="text-left"><i class="fa fa-align-left"></i></button>
			<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'content_align', 'text-center' ) ;
?>" data-optselector-value="text-center"><i class="fa fa-align-center"></i></button>
			<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'content_align', 'text-right' ) ;
?>" data-optselector-value="text-right"><i class="fa fa-align-right"></i></button>
		</div>
		<input<?php 
echo  $this->attributes( 'content_align' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'content_align', $values ) ;
?>" class="wrslb-optselector-input" />
	</div><!-- .wrslb-form-control -->

</div><!-- .wrslb-form-section -->

<!-- Carousel Settings -->
<div id="wrslb-form-carousel" class="wrslb-form-section">

	<h3 class="wrslb-form-section-title"><?php 
_e( 'Carousel', WRSL_SLUG );
?></h3>

	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'c_speed' ) ;
?>><?php 
_e( 'Transition duration (in ms)', WRSL_SLUG );
?></label>
				<input<?php 
echo  $this->attributes( 'c_speed' ) ;
?> type="number" class="wrslb-input-number" value="<?php 
echo  $this->get_value( 'c_speed', $values ) ;
?>">
			</div><!-- .wrslb-form-control -->
		</li>
		<li>
			<div class="wrslb-form-control">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'c_slidemargin' ) ;
?>><?php 
_e( 'Margin between each item', WRSL_SLUG );
?></label>
				<input<?php 
echo  $this->attributes( 'c_slidemargin' ) ;
?> type="number" class="wrslb-input-number" value="<?php 
echo  $this->get_value( 'c_slidemargin', $values ) ;
?>">
			</div><!-- .wrslb-form-control -->
		</li>
	</ul><!-- .wrslb-form-row -->

	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control wrslb-input-type-optselector">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'c_moveone' ) ;
?>>
					<?php 
_e( 'Move only one item on transition', WRSL_SLUG );
?>
				</label>
				<div class="wrslb-optselector-options">
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'c_moveone', 'on' ) ;
?>" data-optselector-value="on"><?php 
_e( 'Yes', WRSL_SLUG );
?></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'c_moveone', '' ) ;
?>" data-optselector-value=""><?php 
_e( 'No', WRSL_SLUG );
?></button>
				</div>
				<input<?php 
echo  $this->attributes( 'c_moveone' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'c_moveone', $values ) ;
?>" class="wrslb-optselector-input" />
			</div><!-- .wrslb-form-control -->
		</li>
		<li style="display: none;">
			<div class="wrslb-form-control wrslb-input-type-optselector">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'c_touchenabled' ) ;
?>>
					<?php 
_e( 'Allow touch swipe transitions', WRSL_SLUG );
?>
				</label>
				<div class="wrslb-optselector-options">
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'c_touchenabled', 'on' ) ;
?>" data-optselector-value="on"><?php 
_e( 'Yes', WRSL_SLUG );
?></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'c_touchenabled', '' ) ;
?>" data-optselector-value=""><?php 
_e( 'No', WRSL_SLUG );
?></button>
				</div>
				<input<?php 
echo  $this->attributes( 'c_touchenabled' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'c_touchenabled', $values ) ;
?>" class="wrslb-optselector-input" />
			</div><!-- .wrslb-form-control -->
		</li>
	</ul>

<?php 
?>

</div><!-- .wrslb-form-section -->

<!-- Controller Settings -->
<div id="wrslb-form-controller" class="wrslb-form-section">

	<h3 class="wrslb-form-section-title"><?php 
_e( 'Controller', WRSL_SLUG );
?></h3>

	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'controller_type' ) ;
?>><?php 
_e( 'Type', WRSL_SLUG );
?></label>
				<select<?php 
echo  $this->attributes( 'controller_type' ) ;
?> class="wrslb-input-select_small">
					<option value="center" <?php 
echo  $this->selected( $values, 'controller_type', 'center' ) ;
?>><?php 
_e( 'Center', WRSL_SLUG );
?></option>
					<option value="center-hover" <?php 
echo  $this->selected( $values, 'controller_type', 'center-hover' ) ;
?>><?php 
_e( 'Center (on hover only)', WRSL_SLUG );
?></option>
					<option value="top-left" <?php 
echo  $this->selected( $values, 'controller_type', 'top-left' ) ;
?>><?php 
_e( 'Top Left', WRSL_SLUG );
?></option>
					<option value="top-center" <?php 
echo  $this->selected( $values, 'controller_type', 'top-center' ) ;
?>><?php 
_e( 'Top center', WRSL_SLUG );
?></option>
					<option value="top-right" <?php 
echo  $this->selected( $values, 'controller_type', 'top-right' ) ;
?>><?php 
_e( 'Top Right', WRSL_SLUG );
?></option>
					<option value="bottom-left" <?php 
echo  $this->selected( $values, 'controller_type', 'bottom-left' ) ;
?>><?php 
_e( 'Bottom Left', WRSL_SLUG );
?></option>
					<option value="bottom-center" <?php 
echo  $this->selected( $values, 'controller_type', 'bottom-center' ) ;
?>><?php 
_e( 'Bottom center', WRSL_SLUG );
?></option>
					<option value="bottom-right" <?php 
echo  $this->selected( $values, 'controller_type', 'bottom-right' ) ;
?>><?php 
_e( 'Bottom Right', WRSL_SLUG );
?></option>
				</select>
			</div><!-- .wrslb-form-control -->
		</li>
		<li>
			<div class="wrslb-form-control wrslb-input-type-optselector">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'controller_icon' ) ;
?>>
					<?php 
_e( 'Icon', WRSL_SLUG );
?>
				</label>
				<div class="wrslb-optselector-options">
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'controller_icon', 'caret' ) ;
?>" data-optselector-value="caret"><i class="fa fa-caret-right"></i></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'controller_icon', 'angle' ) ;
?>" data-optselector-value="angle"><i class="fa fa-angle-right"></i></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'controller_icon', 'angle-double' ) ;
?>" data-optselector-value="angle-double"><i class="fa fa-angle-double-right"></i></button>
					<button class="wrslb-optselector-btn<?php 
echo  $this->option_selected( $values, 'controller_icon', 'chevron' ) ;
?>" data-optselector-value="chevron"><i class="fa fa-chevron-right"></i></button>
				</div>
				<input<?php 
echo  $this->attributes( 'controller_icon' ) ;
?> type="hidden" value="<?php 
echo  $this->get_value( 'controller_icon', $values ) ;
?>" class="wrslb-optselector-input" />
			</div><!-- .wrslb-form-control -->
		</li>
	</ul>
	<ul class="wrslb-form-row wrslb-form-2x">
		<li>
			<div class="wrslb-form-control wrslb-form-colorpicker">
				<label class="wrslb-input-label" for=<?php 
echo  $this->input_id( 'controller_color' ) ;
?>><?php 
_e( 'Color', WRSL_SLUG );
?></label>
				<input<?php 
echo  $this->attributes( 'controller_color' ) ;
?> type="text" class="wrslb-input-colorpicker" value="<?php 
echo  $this->get_value( 'controller_color', $values ) ;
?>">
			</div><!-- .wrslb-form-control -->
		</li>
	</ul>

</div><!-- .wrslb-form-section -->

<!-- Save Button -->
<div id="wrslb-form-save" class="wrslb-form-section">
	<button type="submit" class="wrslb-button-info wrslb-save-changes"><?php 
_e( 'Save Changes', WRSL_SLUG );
?></button>
</div><!-- .wrslb-form-section -->