<?php

/**
 * The widget functionality of the plugin.
 *
 * @link       https://thebrandiD.com
 * @since      2.0.0
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/includes
 */

/**
 * The widget functionality of the plugin.
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/includes
 */
class Social_Proof_Slider_Widget extends WP_Widget {

	/**
	 * The ID of this plugin.
	 *
	 * @since 		2.0.0
	 * @access 		private
	 * @var 		string 			$plugin_name 		The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 		2.0.0
	 * @access 		private
	 * @var 		string 			$version 			The current version of this plugin.
	 */
	private $version;

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {

		$this->plugin_name = 'social-proof-slider';
		$this->version = SPSLIDER_PLUGIN_VERSION;

		$name = esc_html__( 'Social Proof Slider', 'social-proof-slider' );
		$opts['classname'] = '';
		$opts['description'] = esc_html__( 'Display a Social Proof Slider', 'social-proof-slider' );
		$control = array( 'width' => '', 'height' => '' );

		parent::__construct( false, $name, $opts, $control );

		add_action( 'admin_footer-widgets.php', array( $this, 'print_custom_admin_scripts' ), 9999 );

	} // __construct()

	public function print_custom_admin_scripts() {
	?>
	<script>
		( function( $ ){

			function showItem(item) {
				return $(item).show().addClass("visible");
			}

			function hideItem(item) {
				return $(item).removeClass("visible").hide();
			}

			function showItemsWithIDs(ids) {
				$(ids).each(function(index, id) {
					showItem(id);
				});
			}

			function hideItemsWithIDs(ids) {
				$(ids).each(function(index, id) {
					hideItem(id);
				});
			}

			function hideUncheckedOptions(){

				/* ********** Auto-Play ********** */
				// Assign elements
				var showautoplayElems = [
					$( ".spslider_options_displaytime" )
				];
				$.each(showautoplayElems, function(i, elem) {
					$(elem).addClass('sub-option autoplaytime');
				});

				$( 'div[id*="_social_proof_slider_widget-"].widget:not([id*="__i__"]) input[id*="autoplay"]' ).each(function(){

					// Check first
					if( $(this).is( ':checked' ) ) {
						showItemsWithIDs(showautoplayElems);
					} else {
						hideItemsWithIDs(showautoplayElems);
					}
					// Click function
					$(this).click(function () {
						if( $( this ).is( ':checked' ) ) {
							showItemsWithIDs(showautoplayElems);
						} else {
							hideItemsWithIDs(showautoplayElems);
						}
					})
				});


				/* ********** Auto-Height ********** */
				// Assign elements
				var showautoheightElems = [
					$( ".spslider_options_verticalalign" )
				];
				$.each(showautoheightElems, function(i, elem) {
					$(elem).addClass('sub-option valign');
				});

				$( "div[id*='_social_proof_slider_widget-'].widget:not([id*='__i__']) select[id*='autoheight']" ).each(function(){

					// Check first
					// if( $(this).is( ':checked' ) ) {
					var optionSelected = $(this).find("option:selected");
					var valueSelected  = optionSelected.val();
					if ( valueSelected == "1" ) {
						hideItemsWithIDs(showautoheightElems);
					} else {
						showItemsWithIDs(showautoheightElems);
					}

					// OnChange function
					$(this).change(function () {
						var optionSelected = $(this).find("option:selected");
						var valueSelected  = optionSelected.val();
						if ( valueSelected == "1" ) {
							hideItemsWithIDs(showautoheightElems);
						} else {
							showItemsWithIDs(showautoheightElems);
						}
					});

				});


				/* ********** Padding Override ********** */
				// Assign elements
				var paddingoverrideElems = [
					$( ".spslider_options_contentpaddingtop" ),
					$( ".spslider_options_contentpaddingbottom" ),
					$( ".spslider_options_featimgmargintop" ),
					$( ".spslider_options_featimgmarginbottom" ),
					$( ".spslider_options_textpaddingtop" ),
					$( ".spslider_options_textpaddingbottom" ),
					$( ".spslider_options_quotemarginbottom" ),
					$( ".spslider_options_dotsmargintop" )
				];
				$.each(paddingoverrideElems, function(i, elem) {
					$(elem).addClass('sub-option padding');
				});
				$( "div[id*='_social_proof_slider_widget-']:not([id*='__i__']).widget input[id*='paddingoverride']" ).each(function(){
					// Check first
					if( $(this).is( ':checked' ) ) {
						showItemsWithIDs(paddingoverrideElems);
					} else {
						hideItemsWithIDs(paddingoverrideElems);
					}
					// Click function
					$(this).click(function () {
						if( $( this ).is( ':checked' ) ) {
							showItemsWithIDs(paddingoverrideElems);
						} else {
							hideItemsWithIDs(paddingoverrideElems);
						}
					})
				});

				/* ********** Featured Image ********** */
				// Assign elements
				var showfeaturedimgElems = [
					$( ".spslider_options_imgborderradius" ),
					$( ".spslider_options_showimgborder" ),
					$( ".spslider_options_imgbordercolor" ),
					$( ".spslider_options_imgborderthickness" ),
					$( ".spslider_options_imgborderpadding" )
				];
				$.each(showfeaturedimgElems, function(i, elem) {
					$(elem).addClass('sub-option featimg');
				});
				$( "div[id*='_social_proof_slider_widget-'].widget:not([id*='__i__']) input[id*='showfeaturedimg']" ).each(function(){
					// Check first
					if( $(this).is( ':checked' ) ) {
						showItemsWithIDs(showfeaturedimgElems);
					} else {
						hideItemsWithIDs(showfeaturedimgElems);
					}
					// Click function
					$(this).click(function () {
						if( $( this ).is( ':checked' ) ) {
							showItemsWithIDs(showfeaturedimgElems);
						} else {
							hideItemsWithIDs(showfeaturedimgElems);
						}
					})
				});

				/* ********** Arrows ********** */
				// Assign elements
				var showarrowsElems = [
					$( ".spslider_options_arrowiconstyle" ),
					$( ".radio-group.arrowiconstyle" ),
					$( ".spslider_options_arrowcolor" ),
					$( ".spslider_options_arrowhovercolor" )
				];
				$.each(showarrowsElems, function(i, elem) {
					$(elem).addClass('sub-option arrows');
				});
				$( "div[id*='_social_proof_slider_widget-'].widget:not([id*='__i__']) input[id*='showarrows']" ).each(function(){
					// Check first
					if( $(this).is( ':checked' ) ) {
						showItemsWithIDs(showarrowsElems);
					} else {
						hideItemsWithIDs(showarrowsElems);
					}
					// Click function
					$(this).click(function () {
						if( $( this ).is( ':checked' ) ) {
							showItemsWithIDs(showarrowsElems);
						} else {
							hideItemsWithIDs(showarrowsElems);
						}
					})
				});

				/* ********** Dots ********** */
				// Assign elements
				var showdotsElems = [
					$( ".spslider_options_dotscolor" )
				];
				$.each(showdotsElems, function(i, elem) {
					$(elem).addClass('sub-option dots');
				});
				$( "div[id*='_social_proof_slider_widget-'].widget:not([id*='__i__']) input[id*='showdots']" ).each(function(){
					// Check first
					if( $(this).is( ':checked' ) ) {
						showItemsWithIDs(showdotsElems);
					} else {
						hideItemsWithIDs(showdotsElems);
					}
					// Click function
					$(this).click(function () {
						if( $( this ).is( ':checked' ) ) {
							$(".sub-option.dots").show();
						} else {
							$(".sub-option.dots").hide();
						}
					})
				});

				/* ********** Limit Testimonials ********** */
				// Assign elements
				var limitByIDElem = [
					$( ".spslider_options_excincIDs" )
				];
				var limitByCatElem = [
					$( ".spslider_options_catSlug" )
				];

				$.each(limitByIDElem, function(i, elem) {
					$(elem).addClass('sub-option limit');
				});

				$.each(limitByCatElem, function(i, elem) {
					$(elem).addClass('sub-option limit');
				});

				$( "div[id*='_social_proof_slider_widget-']:not([id*='__i__']).widget select[id*='excinc']" ).each(function(){

					// Check for selected first
					var optionSelected = $(this).find("option:selected");
					var valueSelected  = optionSelected.val();
					if ( valueSelected == "cat" ) {
						showItemsWithIDs(limitByCatElem);
						hideItemsWithIDs(limitByIDElem);
					} else {
						showItemsWithIDs(limitByIDElem);
						hideItemsWithIDs(limitByCatElem);
					}

					// Check on change
					$(this).on('change', function() {
						if ($(this).val() == 'cat') {
							showItemsWithIDs(limitByCatElem);
							hideItemsWithIDs(limitByIDElem);
						} else {
							showItemsWithIDs(limitByIDElem);
							hideItemsWithIDs(limitByCatElem);
						}
					});

				});

			}

			function initColorPicker( widget ) {
				widget.find( '.color-picker' ).wpColorPicker( {
					change: _.throttle( function() { // For Customizer
						$(this).trigger( 'change' );
					}, 3000 ),
					clear: _.throttle( function() { // For Customizer - required in WP 4.9
						$(this).trigger( 'change' );
					}, 4000 )
				});
			}

			function updateCBoxes(){

				/* ********** Styling Radio Buttons Without Dots ********** */
				$( 'input:radio[data-radio-id*="verticalalign"], input:radio[data-radio-id*="textalign"], input:radio[data-radio-id*="arrowiconstyle"]' ).hide();

				$('.radio-group a.icon').on('click', function(e) {
					// don't follow anchor link
					e.preventDefault();

					// the clicked item
					var unique = $(this).attr('data-radio-id');

					//console.log( 'clicked: ' + $(this).prev('input:radio').attr('value') );

					// change all span elements class to 'radio'
					$("a[data-radio-id='"+unique+"'] span").attr('class','radio');

					// change all radio buttons to unchecked
					$(":radio[data-radio-id='"+unique+"']").attr('checked',false);

					// find this span item and give it 'radio-checked' class
					$(this).find('span').attr('class','radio-checked');

					// find this radio button and make it 'checked'
					$(this).prev('input:radio').attr('checked',true);

					$(this).prev('input:radio').trigger('change');

				}).on('keydown', function(e) {
					// on keyboard entry, trigger click event
					if ((e.keyCode ? e.keyCode : e.which) == 32) {
						$(this).trigger('click');
					}
				});

			}

			function onFormUpdate( event, widget ) {
				initColorPicker( widget );
				updateCBoxes();
				hideUncheckedOptions();
			}

			$( document ).on( 'widget-added widget-updated', onFormUpdate );

			$( document ).ready( function($) {

				$( '#widgets-right .widget:has(.color-picker)' ).each( function () {
					initColorPicker( $( this ) );
				} );

				updateCBoxes();
				hideUncheckedOptions();

			} );

		}( jQuery ) );

	</script>
	<?php
	}


	/**
	 * Back-end widget form.
	 *
	 * @see		WP_Widget::form()
	 *
	 * @uses	wp_parse_args
	 * @uses	esc_attr
	 * @uses	get_field_id
	 * @uses	get_field_name
	 * @uses	checked
	 *
	 * @param	array	$instance	Previously saved values from database.
	 */
	function form( $instance ) {

		// DEFAULTS
		$defaults['title'] = '';
		$defaults['sortby'] = 'rand';
		$defaults['autoplay'] = 1;
		$defaults['displaytime'] = 3000;
		$defaults['animationstyle'] = 'fade';
		$defaults['autoheight'] = 1;
		$defaults['verticalalign'] = 'align_top';
		$defaults['paddingoverride'] = 0;
		$defaults['contentpaddingtop'] = 20;
		$defaults['contentpaddingbottom'] = 20;
		$defaults['featimgmargintop'] = 20;
		$defaults['featimgmarginbottom'] = 20;
		$defaults['textpaddingtop'] = 50;
		$defaults['textpaddingbottom'] = 50;
		$defaults['quotemarginbottom'] = 30;
		$defaults['dotsmargintop'] = 10;
		$defaults['showfeaturedimg'] = 1;
		$defaults['imgborderradius'] = 0;
		$defaults['showimgborder'] = 0;
		$defaults['imgbordercolor'] = '#000000';
		$defaults['imgborderthickness'] = 2;
		$defaults['imgborderpadding'] = 6;
		$defaults['bgcolor'] = '';
		$defaults['surroundquotes'] = '';
		$defaults['textalign'] = 'align_center';
		$defaults['textcolor'] = '#333333';
		$defaults['showarrows'] = 1;
		$defaults['arrowiconstyle'] = 'style_zero';
		$defaults['arrowcolor'] = '#000000';
		$defaults['arrowhovercolor'] = '#999999';
		$defaults['showdots'] = 1;
		$defaults['dotscolor'] = '#333333';
		$defaults['excinc'] = 'cat';
		$defaults['excincIDs'] = '';
		$defaults['catSlug'] = '';

		$instance = wp_parse_args( (array) $instance, $defaults );

		// WIDGET TITLE
		$field_widgetTitle = 'title'; // name of the field
		$field_widgetTitle_id = $this->get_field_id( $field_widgetTitle );
		$field_widgetTitle_name = $this->get_field_name( $field_widgetTitle );
		$field_widgetTitle_value = esc_attr( $instance[$field_widgetTitle] );
		echo '<div class="spslider_options_widgettitle"><label for="' . $field_widgetTitle_id . '">' . __('Widget Title: ') . '<input class="widefat" id="' . $field_widgetTitle_id . '" name="' . $field_widgetTitle_name . '" type="text" value="' . $field_widgetTitle_value . '" /></label></div>';


		// SORT BY
		$field_sortby = 'sortby'; // name of the field
		$field_sortby_id = $this->get_field_id( $field_sortby );
		$field_sortby_name = $this->get_field_name( $field_sortby );
		$field_sortby_value = esc_attr( $instance[$field_sortby] );

		echo '<div class="spslider_options_sortby"><label for="' . $field_sortby_id . '">' . __('Sort By: ') . '';
		?>
		<select name="<?php echo $field_sortby_name; ?>">
			<option value="rand" <?php echo $field_sortby_value == 'rand' ? 'selected="selected"' : ''; ?> >Random (default)</option>
			<option value="desc" <?php echo $field_sortby_value == 'desc' ? 'selected="selected"' : ''; ?> >Date - Descending</option>
			<option value="asc" <?php echo $field_sortby_value == 'asc' ? 'selected="selected"' : ''; ?> >Date - Ascending</option>
		</select>
		<?php
		echo '</label></div>';


		// AUTO PLAY
		$field_autoplay = 'autoplay'; // name of the field
		$field_autoplay_id = $this->get_field_id( $field_autoplay );
		$field_autoplay_name = $this->get_field_name( $field_autoplay );
		$field_autoplay_value = esc_attr( $instance[$field_autoplay] );

		$checked = ( (int)$field_autoplay_value == 1 ) ? 'checked' : '';

		echo '<div class="spslider_options_autoplay"><label for="' . $field_autoplay_id . '">' . __('Auto Play: ') . '';
		?>
		<input aria-role="checkbox"
				<?php echo $checked; ?>
				class=""
				id="<?php echo $field_autoplay_id; ?>"
				name="<?php echo $field_autoplay_name; ?>"
				type="checkbox"
				value="1" />
		<?php
		echo '</label></div>';


		// DISPLAY TIME
		$field_displaytime = 'displaytime'; // name of the field
		$field_displaytime_id = $this->get_field_id( $field_displaytime );
		$field_displaytime_name = $this->get_field_name( $field_displaytime );
		$field_displaytime_value = esc_attr( $instance[$field_displaytime] );

		echo '<div class="spslider_options_displaytime"><label for="' . $field_displaytime_id . '">' . __('Display Time: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_displaytime_id; ?>"
				name="<?php echo $field_displaytime_name; ?>"
				type="number"
				value="<?php echo $field_displaytime_value; ?>"
			/>ms
			<?php
		echo '</label></div>';


		// ANIMATION STYLE
		$field_animationstyle = 'animationstyle'; // name of the field
		$field_animationstyle_id = $this->get_field_id( $field_animationstyle );
		$field_animationstyle_name = $this->get_field_name( $field_animationstyle );
		$field_animationstyle_value = esc_attr( $instance[$field_animationstyle] );

		echo '<div class="spslider_options_animationstyle"><label for="' . $field_displaytime_id . '">' . __('Animation Style: ') . '';
		?>
		<select name="<?php echo $field_animationstyle_name; ?>" id="<?php echo $field_animationstyle_id; ?>">
			<option value="fade" <?php echo $field_animationstyle_value == 'fade' ? 'selected="selected"' : ''; ?> >Fade (default)</option>
			<option value="slide" <?php echo $field_animationstyle_value == 'slide' ? 'selected="selected"' : ''; ?> >Slide</option>
		</select>
		<?php
		echo '</label></div>';


		// AUTO HEIGHT
		$field_autoheight = 'autoheight'; // name of the field
		$field_autoheight_id = $this->get_field_id( $field_autoheight );
		$field_autoheight_name = $this->get_field_name( $field_autoheight );
		$field_autoheight_value = esc_attr( $instance[$field_autoheight] );

		echo '<div class="spslider_options_autoheight"><label for="' . $field_autoheight_id . '">' . __('Variable Height: ') . '';
		?>
		<select name="<?php echo $field_autoheight_name; ?>" id="<?php echo $field_autoheight_id; ?>">
			<option value="1" <?php echo $field_autoheight_value == '1' ? 'selected="selected"' : ''; ?> >Variable Height</option>
			<option value="0" <?php echo $field_autoheight_value == '0' ? 'selected="selected"' : ''; ?> >Fixed Height</option>
		</select>
		<?php
		echo '</label></div>';


		// VERTICAL ALIGN
		$field_verticalalign = 'verticalalign'; // name of the field
		$field_verticalalign_id = $this->get_field_id( $field_verticalalign );
		$field_verticalalign_name = $this->get_field_name( $field_verticalalign );
		$field_verticalalign_value = esc_attr( $instance[$field_verticalalign] );

		echo '<div class="spslider_options_verticalalign"><label for="' . $field_verticalalign_id . '">' . __('Vertical Align: ') . '';
		?>
		<div class="radio-group verticalalign">
			<div class="item">
				<input type="radio" name="<?php echo $field_verticalalign_name; ?>" value="align_top" data-radio-id="<?php echo $field_verticalalign_id; ?>" <?php echo $field_verticalalign_value == 'align_top' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_verticalalign_id; ?>" class="icon" href="#"><span class="<?php echo $field_verticalalign_value == 'align_top' ? 'radio-checked' : 'radio'; ?>"><i class="dashicons-align-top"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_verticalalign_name; ?>" value="align_middle" data-radio-id="<?php echo $field_verticalalign_id; ?>" <?php echo $field_verticalalign_value == 'align_middle' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_verticalalign_id; ?>" class="icon" href="#"><span class="<?php echo $field_verticalalign_value == 'align_middle' ? 'radio-checked' : 'radio'; ?>"><i class="dashicons-align-middle"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_verticalalign_name; ?>" value="align_bottom" data-radio-id="<?php echo $field_verticalalign_id; ?>" <?php echo $field_verticalalign_value == 'align_bottom' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_verticalalign_id; ?>" class="icon" href="#"><span class="<?php echo $field_verticalalign_value == 'align_bottom' ? 'radio-checked' : 'radio'; ?>"><i class="dashicons-align-bottom"></i></span></a>
			</div>
		</div>
		<?php
		echo '</label></div>';


		// PADDING OVERRIDE
		$field_paddingoverride = 'paddingoverride'; // name of the field
		$field_paddingoverride_id = $this->get_field_id( $field_paddingoverride );
		$field_paddingoverride_name = $this->get_field_name( $field_paddingoverride );
		$field_paddingoverride_value = esc_attr( $instance[$field_paddingoverride] );

		$checked = ( (int)$field_paddingoverride_value == 1 ) ? 'checked' : '';

		echo '<div class="spslider_options_paddingoverride"><label for="' . $field_paddingoverride_id . '">' . __('Padding Override: ') . '';
		?>
		<input aria-role="checkbox"
				<?php echo $checked; ?>
				class=""
				id="<?php echo $field_paddingoverride_id; ?>"
				name="<?php echo $field_paddingoverride_name; ?>"
				type="checkbox"
				value="1" />
		<?php
		echo '</label></div>';


		// CONTENT PADDING TOP
		$field_contentpaddingtop = 'contentpaddingtop'; // name of the field
		$field_contentpaddingtop_id = $this->get_field_id( $field_contentpaddingtop );
		$field_contentpaddingtop_name = $this->get_field_name( $field_contentpaddingtop );
		$field_contentpaddingtop_value = esc_attr( $instance[$field_contentpaddingtop] );

		echo '<div class="spslider_options_contentpaddingtop"><label for="' . $field_contentpaddingtop_id . '">' . __('Content Padding Top: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_contentpaddingtop_id; ?>"
				name="<?php echo $field_contentpaddingtop_name; ?>"
				type="number"
				value="<?php echo $field_contentpaddingtop_value; ?>"
			/>px
			<?php
		echo '</label></div>';


		// CONTENT PADDING BOTTOM
		$field_contentpaddingbottom = 'contentpaddingbottom'; // name of the field
		$field_contentpaddingbottom_id = $this->get_field_id( $field_contentpaddingbottom );
		$field_contentpaddingbottom_name = $this->get_field_name( $field_contentpaddingbottom );
		$field_contentpaddingbottom_value = esc_attr( $instance[$field_contentpaddingbottom] );

		echo '<div class="spslider_options_contentpaddingbottom"><label for="' . $field_contentpaddingbottom_id . '">' . __('Content Padding Bottom: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_contentpaddingbottom_id; ?>"
				name="<?php echo $field_contentpaddingbottom_name; ?>"
				type="number"
				value="<?php echo $field_contentpaddingbottom_value; ?>"
			/>px
			<?php
		echo '</label></div>';


		// FEATURED IMAGE MARGIN TOP
		$field_featimgmargintop = 'featimgmargintop'; // name of the field
		$field_featimgmargintop_id = $this->get_field_id( $field_featimgmargintop );
		$field_featimgmargintop_name = $this->get_field_name( $field_featimgmargintop );
		$field_featimgmargintop_value = esc_attr( $instance[$field_featimgmargintop] );

		echo '<div class="spslider_options_featimgmargintop"><label for="' . $field_featimgmargintop_id . '">' . __('Image Margin Top: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_featimgmargintop_id; ?>"
				name="<?php echo $field_featimgmargintop_name; ?>"
				type="number"
				value="<?php echo $field_featimgmargintop_value; ?>"
			/>px
			<?php
		echo '</label></div>';


		// FEATURED IMAGE MARGIN BOTTOM
		$field_featimgmarginbottom = 'featimgmarginbottom'; // name of the field
		$field_featimgmarginbottom_id = $this->get_field_id( $field_featimgmarginbottom );
		$field_featimgmarginbottom_name = $this->get_field_name( $field_featimgmarginbottom );
		$field_featimgmarginbottom_value = esc_attr( $instance[$field_featimgmarginbottom] );

		echo '<div class="spslider_options_featimgmarginbottom"><label for="' . $field_featimgmarginbottom_id . '">' . __('Image Margin Bottom: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_featimgmarginbottom_id; ?>"
				name="<?php echo $field_featimgmarginbottom_name; ?>"
				type="number"
				value="<?php echo $field_featimgmarginbottom_value; ?>"
			/>px
			<?php
		echo '</label></div>';


		// TEXT PADDING TOP
		$field_textpaddingtop = 'textpaddingtop'; // name of the field
		$field_textpaddingtop_id = $this->get_field_id( $field_textpaddingtop );
		$field_textpaddingtop_name = $this->get_field_name( $field_textpaddingtop );
		$field_textpaddingtop_value = esc_attr( $instance[$field_textpaddingtop] );

		echo '<div class="spslider_options_textpaddingtop"><label for="' . $field_textpaddingtop_id . '">' . __('Text Padding Top: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_textpaddingtop_id; ?>"
				name="<?php echo $field_textpaddingtop_name; ?>"
				type="number"
				value="<?php echo $field_textpaddingtop_value; ?>"
			/>px
			<?php
		echo '</label></div>';


		// TEXT PADDING BOTTOM
		$field_textpaddingbottom = 'textpaddingbottom'; // name of the field
		$field_textpaddingbottom_id = $this->get_field_id( $field_textpaddingbottom );
		$field_textpaddingbottom_name = $this->get_field_name( $field_textpaddingbottom );
		$field_textpaddingbottom_value = esc_attr( $instance[$field_textpaddingbottom] );

		echo '<div class="spslider_options_textpaddingbottom"><label for="' . $field_textpaddingbottom_id . '">' . __('Text Padding Bottom: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_textpaddingbottom_id; ?>"
				name="<?php echo $field_textpaddingbottom_name; ?>"
				type="number"
				value="<?php echo $field_textpaddingbottom_value; ?>"
			/>px
			<?php
		echo '</label></div>';


		// QUOTE MARGIN BOTTOM
		$field_quotemarginbottom = 'quotemarginbottom'; // name of the field
		$field_quotemarginbottom_id = $this->get_field_id( $field_quotemarginbottom );
		$field_quotemarginbottom_name = $this->get_field_name( $field_quotemarginbottom );
		$field_quotemarginbottom_value = esc_attr( $instance[$field_quotemarginbottom] );

		echo '<div class="spslider_options_quotemarginbottom"><label for="' . $field_quotemarginbottom_id . '">' . __('Quote Margin Bottom: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_quotemarginbottom_id; ?>"
				name="<?php echo $field_quotemarginbottom_name; ?>"
				type="number"
				value="<?php echo $field_quotemarginbottom_value; ?>"
			/>px
			<?php
		echo '</label></div>';


		// DOTS MARGIN TOP
		$field_dotsmargintop = 'dotsmargintop'; // name of the field
		$field_dotsmargintop_id = $this->get_field_id( $field_dotsmargintop );
		$field_dotsmargintop_name = $this->get_field_name( $field_dotsmargintop );
		$field_dotsmargintop_value = esc_attr( $instance[$field_dotsmargintop] );

		echo '<div class="spslider_options_dotsmargintop"><label for="' . $field_dotsmargintop_id . '">' . __('Dots Margin Top: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_dotsmargintop_id; ?>"
				name="<?php echo $field_dotsmargintop_name; ?>"
				type="number"
				value="<?php echo $field_dotsmargintop_value; ?>"
			/>px
			<?php
		echo '</label></div>';


		// SHOW FEATURED IMAGE
		$field_showfeaturedimg = 'showfeaturedimg'; // name of the field
		$field_showfeaturedimg_id = $this->get_field_id( $field_showfeaturedimg );
		$field_showfeaturedimg_name = $this->get_field_name( $field_showfeaturedimg );
		$field_showfeaturedimg_value = esc_attr( $instance[$field_showfeaturedimg] );

		$checked = ( (int)$field_showfeaturedimg_value == 1 ) ? 'checked' : '';

		echo '<div class="spslider_options_showfeaturedimg"><label for="' . $field_showfeaturedimg_id . '">' . __('Show Featured Images: ') . '';
		?>
		<input aria-role="checkbox"
				<?php echo $checked; ?>
				class=""
				id="<?php echo $field_showfeaturedimg_id; ?>"
				name="<?php echo $field_showfeaturedimg_name; ?>"
				type="checkbox"
				value="1" />
		<?php
		echo '</label></div>';


		// IMAGE BORDER RADIUS
		$field_imgborderradius = 'imgborderradius'; // name of the field
		$field_imgborderradius_id = $this->get_field_id( $field_imgborderradius );
		$field_imgborderradius_name = $this->get_field_name( $field_imgborderradius );
		$field_imgborderradius_value = esc_attr( $instance[$field_imgborderradius] );

		echo '<div class="spslider_options_imgborderradius"><label for="' . $field_imgborderradius_id . '">' . __('Image Border Radius: ') . '';
		?>
			<input
				class="small-number-field"
				id="<?php echo $field_imgborderradius_id; ?>"
				name="<?php echo $field_imgborderradius_name; ?>"
				type="number"
				value="<?php echo $field_imgborderradius_value; ?>"
			/>px
			<?php
		echo '</label></div>';


		// SHOW IMAGE BORDER
		$field_showimgborder = 'showimgborder'; // name of the field
		$field_showimgborder_id = $this->get_field_id( $field_showimgborder );
		$field_showimgborder_name = $this->get_field_name( $field_showimgborder );
		$field_showimgborder_value = esc_attr( $instance[$field_showimgborder] );

		$checked = ( (int)$field_showimgborder_value == 1 ) ? 'checked' : '';

		echo '<div class="spslider_options_showimgborder"><label for="' . $field_showimgborder_id . '">' . __('Show Image Border: ') . '';
		?>
		<input aria-role="checkbox"
				<?php echo $checked; ?>
				class=""
				id="<?php echo $field_showimgborder_id; ?>"
				name="<?php echo $field_showimgborder_name; ?>"
				type="checkbox"
				value="1" />
		<?php
		echo '</label></div>';


		// IMAGE BORDER COLOR
		$field_imgbordercolor = 'imgbordercolor'; // name of the field
		$field_imgbordercolor_id = $this->get_field_id( $field_imgbordercolor );
		$field_imgbordercolor_name = $this->get_field_name( $field_imgbordercolor );
		$field_imgbordercolor_value = esc_attr( $instance[$field_imgbordercolor] );

		echo '<div class="spslider_options_imgbordercolor"><label for="' . $field_imgbordercolor_id . '">' . __('Border Color:') . '</label> ';
		?>
			<input
			class="color-picker"
			id="<?php echo $field_imgbordercolor_id; ?>"
			name="<?php echo $field_imgbordercolor_name; ?>"
			type="text"
			value="<?php echo $field_imgbordercolor_value; ?>" />
		<?php
		echo '</div>';


		// IMAGE BORDER THICKNESS
		$field_imgborderthickness = 'imgborderthickness'; // name of the field
		$field_imgborderthickness_id = $this->get_field_id( $field_imgborderthickness );
		$field_imgborderthickness_name = $this->get_field_name( $field_imgborderthickness );
		$field_imgborderthickness_value = esc_attr( $instance[$field_imgborderthickness] );

		echo '<div class="spslider_options_imgborderthickness"><label for="' . $field_imgborderthickness_id . '">' . __('Border Thickness: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_imgborderthickness_id; ?>"
				name="<?php echo $field_imgborderthickness_name; ?>"
				type="number"
				value="<?php echo $field_imgborderthickness_value; ?>"
			/>px
			<?php
		echo '</label></div>';


		// IMAGE BORDER PADDING
		$field_imgborderpadding = 'imgborderpadding'; // name of the field
		$field_imgborderpadding_id = $this->get_field_id( $field_imgborderpadding );
		$field_imgborderpadding_name = $this->get_field_name( $field_imgborderpadding );
		$field_imgborderpadding_value = esc_attr( $instance[$field_imgborderpadding] );

		echo '<div class="spslider_options_imgborderpadding"><label for="' . $field_imgborderpadding_id . '">' . __('Border Padding: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_imgborderpadding_id; ?>"
				name="<?php echo $field_imgborderpadding_name; ?>"
				type="number"
				value="<?php echo $field_imgborderpadding_value; ?>"
			/>px
			<?php
		echo '</label></div>';


		// BG COLOR
		$field_bgcolor = 'bgcolor'; // name of the field
		$field_bgcolor_id = $this->get_field_id( $field_bgcolor );
		$field_bgcolor_name = $this->get_field_name( $field_bgcolor );
		$field_bgcolor_value = esc_attr( $instance[$field_bgcolor] );

		echo '<div class="spslider_options_bgcolor"><label for="' . $field_bgcolor_id . '">' . __('Background Color: ') . '</label> ';
		?>
			<input
			class="color-picker"
			id="<?php echo $field_bgcolor_id; ?>"
			name="<?php echo $field_bgcolor_name; ?>"
			type="text"
			value="<?php echo $field_bgcolor_value; ?>" />
		<?php
		echo '</div>';


		// SURROUND WITH SMART QUOTES
		$field_surroundquotes = 'surroundquotes'; // name of the field
		$field_surroundquotes_id = $this->get_field_id( $field_surroundquotes );
		$field_surroundquotes_name = $this->get_field_name( $field_surroundquotes );
		$field_surroundquotes_value = esc_attr( $instance[$field_surroundquotes] );

		$checked = ( (int)$field_surroundquotes_value == 1 ) ? 'checked' : '';

		echo '<div class="spslider_options_surroundquotes"><label for="' . $field_surroundquotes_id . '">' . __('Smart Quotes: ') . '';
		?>
		<input aria-role="checkbox"
				<?php echo $checked; ?>
				class=""
				id="<?php echo $field_surroundquotes_id; ?>"
				name="<?php echo $field_surroundquotes_name; ?>"
				type="checkbox"
				value="1" />
		<?php
		echo '</label></div>';


		// TEXT ALIGN
		$field_textalign = 'textalign'; // name of the field
		$field_textalign_id = $this->get_field_id( $field_textalign );
		$field_textalign_name = $this->get_field_name( $field_textalign );
		$field_textalign_value = esc_attr( $instance[$field_textalign] );

		echo '<div class="spslider_options_textalign"><label for="' . $field_textalign_id . '">' . __('Text Align: ') . '';
		?>
		<div class="radio-group textalign">
			<div class="item">
				<input type="radio" name="<?php echo $field_textalign_name; ?>" value="align_left" data-radio-id="<?php echo $field_textalign_id; ?>" <?php echo $field_textalign_value == 'align_left' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_textalign_id; ?>" class="icon" href="#"><span class="<?php echo $field_textalign_value == 'align_left' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-align-left"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_textalign_name; ?>" value="align_center" data-radio-id="<?php echo $field_textalign_id; ?>" <?php echo $field_textalign_value == 'align_center' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_textalign_id; ?>" class="icon" href="#"><span class="<?php echo $field_textalign_value == 'align_center' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-align-center"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_textalign_name; ?>" value="align_right" data-radio-id="<?php echo $field_textalign_id; ?>" <?php echo $field_textalign_value == 'align_right' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_textalign_id; ?>" class="icon" href="#"><span class="<?php echo $field_textalign_value == 'align_right' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-align-right"></i></span></a>
			</div>
		</div>
		<?php
		echo '</label></div>';


		// TEXT COLOR
		$field_textcolor = 'textcolor'; // name of the field
		$field_textcolor_id = $this->get_field_id( $field_textcolor );
		$field_textcolor_name = $this->get_field_name( $field_textcolor );
		$field_textcolor_value = esc_attr( $instance[$field_textcolor] );

		echo '<div class="spslider_options_textcolor"><label for="' . $field_textcolor_id . '">' . __('Text Color: ') . '</label> ';
		?>
			<input
			class="color-picker"
			id="<?php echo $field_textcolor_id; ?>"
			name="<?php echo $field_textcolor_name; ?>"
			type="text"
			value="<?php echo $field_textcolor_value; ?>" />
		<?php
		echo '</div>';


		// SHOW ARROWS
		$field_showarrows = 'showarrows'; // name of the field
		$field_showarrows_id = $this->get_field_id( $field_showarrows );
		$field_showarrows_name = $this->get_field_name( $field_showarrows );
		$field_showarrows_value = esc_attr( $instance[$field_showarrows] );

		$checked = ( (int)$field_showarrows_value == 1 ) ? 'checked' : '';

		echo '<div class="spslider_options_showarrows"><label for="' . $field_showarrows_id . '">' . __('Show Arrows: ') . '';
		?>
		<input aria-role="checkbox"
				<?php echo $checked; ?>
				class=""
				id="<?php echo $field_showarrows_id; ?>"
				name="<?php echo $field_showarrows_name; ?>"
				type="checkbox"
				value="1" />
		<?php
		echo '</label></div>';


		// ARROW ICON STYLE
		$field_arrowiconstyle = 'arrowiconstyle'; // name of the field
		$field_arrowiconstyle_id = $this->get_field_id( $field_arrowiconstyle );
		$field_arrowiconstyle_name = $this->get_field_name( $field_arrowiconstyle );
		$field_arrowiconstyle_value = esc_attr( $instance[$field_arrowiconstyle] );

		echo '<div class="spslider_options_arrowiconstyle">';
		echo '<label for="' . $field_arrowiconstyle_id . '">' . __('Arrow Icons: ') . '</label>';
		?>
		<div class="radio-group arrowiconstyle">
			<div class="item">
				<input type="radio" name="<?php echo $field_arrowiconstyle_name; ?>" value="style_zero" data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" <?php echo $field_arrowiconstyle_value == 'style_zero' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" class="icon" href="#"><span class="<?php echo $field_arrowiconstyle_value == 'style_zero' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-angle-left"></i> <i class="fa fa-angle-right"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_arrowiconstyle_name; ?>" value="style_one" data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" <?php echo $field_arrowiconstyle_value == 'style_one' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" class="icon" href="#"><span class="<?php echo $field_arrowiconstyle_value == 'style_one' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-angle-double-left"></i> <i class="fa fa-angle-double-right"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_arrowiconstyle_name; ?>" value="style_two" data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" <?php echo $field_arrowiconstyle_value == 'style_two' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" class="icon" href="#"><span class="<?php echo $field_arrowiconstyle_value == 'style_two' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-arrow-circle-left"></i> <i class="fa fa-arrow-circle-right"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_arrowiconstyle_name; ?>" value="style_three" data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" <?php echo $field_arrowiconstyle_value == 'style_three' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" class="icon" href="#"><span class="<?php echo $field_arrowiconstyle_value == 'style_three' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-arrow-circle-o-left"></i> <i class="fa fa-arrow-circle-o-right"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_arrowiconstyle_name; ?>" value="style_four" data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" <?php echo $field_arrowiconstyle_value == 'style_four' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" class="icon" href="#"><span class="<?php echo $field_arrowiconstyle_value == 'style_four' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-arrow-left"></i> <i class="fa fa-arrow-right"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_arrowiconstyle_name; ?>" value="style_five" data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" <?php echo $field_arrowiconstyle_value == 'style_five' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" class="icon" href="#"><span class="<?php echo $field_arrowiconstyle_value == 'style_five' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-caret-left"></i> <i class="fa fa-caret-right"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_arrowiconstyle_name; ?>" value="style_six" data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" <?php echo $field_arrowiconstyle_value == 'style_six' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" class="icon" href="#"><span class="<?php echo $field_arrowiconstyle_value == 'style_six' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-caret-square-o-left"></i> <i class="fa fa-caret-square-o-right"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_arrowiconstyle_name; ?>" value="style_seven" data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" <?php echo $field_arrowiconstyle_value == 'style_seven' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" class="icon" href="#"><span class="<?php echo $field_arrowiconstyle_value == 'style_seven' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-chevron-circle-left"></i> <i class="fa fa-chevron-circle-right"></i></span></a>
			</div>
			<div class="item">
				<input type="radio" name="<?php echo $field_arrowiconstyle_name; ?>" value="style_eight" data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" <?php echo $field_arrowiconstyle_value == 'style_eight' ? 'checked="checked"' : ''; ?> />
				<a data-radio-id="<?php echo $field_arrowiconstyle_id; ?>" class="icon" href="#"><span class="<?php echo $field_arrowiconstyle_value == 'style_eight' ? 'radio-checked' : 'radio'; ?>"><i class="fa fa-chevron-left"></i> <i class="fa fa-chevron-right"></i></span></a>
			</div>
		</div>
		<?php
		echo '</div>';

		// ARROW COLOR
		$field_arrowcolor = 'arrowcolor'; // name of the field
		$field_arrowcolor_id = $this->get_field_id( $field_arrowcolor );
		$field_arrowcolor_name = $this->get_field_name( $field_arrowcolor );
		$field_arrowcolor_value = esc_attr( $instance[$field_arrowcolor] );

		echo '<div class="spslider_options_arrowcolor"><label for="' . $field_arrowcolor_id . '">' . __('Arrow Color: ') . '</label> ';
		?>
			<input
			class="color-picker"
			id="<?php echo $field_arrowcolor_id; ?>"
			name="<?php echo $field_arrowcolor_name; ?>"
			type="text"
			value="<?php echo $field_arrowcolor_value; ?>" />
		<?php
		echo '</div>';


		// ARROW HOVER COLOR
		$field_arrowhovercolor = 'arrowhovercolor'; // name of the field
		$field_arrowhovercolor_id = $this->get_field_id( $field_arrowhovercolor );
		$field_arrowhovercolor_name = $this->get_field_name( $field_arrowhovercolor );
		$field_arrowhovercolor_value = esc_attr( $instance[$field_arrowhovercolor] );

		echo '<div class="spslider_options_arrowhovercolor"><label for="' . $field_arrowhovercolor_id . '">' . __('Arrow Hover Color: ') . '</label> ';
		?>
			<input
			class="color-picker"
			id="<?php echo $field_arrowhovercolor_id; ?>"
			name="<?php echo $field_arrowhovercolor_name; ?>"
			type="text"
			value="<?php echo $field_arrowhovercolor_value; ?>" />
		<?php
		echo '</div>';


		// SHOW DOTS
		$field_showdots = 'showdots'; // name of the field
		$field_showdots_id = $this->get_field_id( $field_showdots );
		$field_showdots_name = $this->get_field_name( $field_showdots );
		$field_showdots_value = esc_attr( $instance[$field_showdots] );

		$checked = ( (int)$field_showdots_value == 1 ) ? 'checked' : '';

		echo '<div class="spslider_options_showdots"><label for="' . $field_showdots_id . '">' . __('Show Dots: ') . '';
		?>
		<input aria-role="checkbox"
				<?php echo $checked; ?>
				class=""
				id="<?php echo $field_showdots_id; ?>"
				name="<?php echo $field_showdots_name; ?>"
				type="checkbox"
				value="1" />
		<?php
		echo '</label></div>';


		// DOTS COLOR
		$field_dotscolor = 'dotscolor'; // name of the field
		$field_dotscolor_id = $this->get_field_id( $field_dotscolor );
		$field_dotscolor_name = $this->get_field_name( $field_dotscolor );
		$field_dotscolor_value = esc_attr( $instance[$field_dotscolor] );

		echo '<div class="spslider_options_dotscolor"><label for="' . $field_dotscolor_id . '">' . __('Dots Color: ') . '</label> ';
		?>
			<input
			class="color-picker"
			id="<?php echo $field_dotscolor_id; ?>"
			name="<?php echo $field_dotscolor_name; ?>"
			type="text"
			value="<?php echo $field_dotscolor_value; ?>" />
		<?php
		echo '</div>';


		// INCLUDE/EXCLUDE SELECT
		$field_excinc = 'excinc'; // name of the field
		$field_excinc_id = $this->get_field_id( $field_excinc );
		$field_excinc_name = $this->get_field_name( $field_excinc );
		$field_excinc_value = esc_attr( $instance[$field_excinc] );

		echo '<div class="spslider_options_excinc"><label for="' . $field_excinc_id . '">' . __('Limit Testimonials: ') . '</label><br>';
		?>
		<select name="<?php echo $field_excinc_name; ?>"  id="<?php echo $field_excinc_id; ?>">
			<option value="cat" <?php echo $field_excinc_value == 'cat' ? 'selected="selected"' : ''; ?> >Limit by Category</option>
			<option value="in" <?php echo $field_excinc_value == 'in' ? 'selected="selected"' : ''; ?> >Include by ID</option>
			<option value="ex" <?php echo $field_excinc_value == 'ex' ? 'selected="selected"' : ''; ?> >Exclude by ID</option>
		</select>
		<?php
		echo '</div>';


		// INCLUDE/EXCLUDE IDS TEXTBOX
		$field_excincIDs = 'excincIDs'; // name of the field
		$field_excincIDs_id = $this->get_field_id( $field_excincIDs );
		$field_excincIDs_name = $this->get_field_name( $field_excincIDs );
		$field_excincIDs_value = esc_attr( $instance[$field_excincIDs] );

		echo '<div class="spslider_options_excincIDs"><label for="' . $field_excincIDs_id . '">' . __('IDs: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_excincIDs_id; ?>"
				name="<?php echo $field_excincIDs_name; ?>"
				type="text"
				value="<?php echo $field_excincIDs_value; ?>"
			/>
			<?php
		echo '</label></div>';

		// LIMIT BY CATEGORY TEXTBOX
		$field_catSlug = 'catSlug'; // name of the field
		$field_catSlug_id = $this->get_field_id( $field_catSlug );
		$field_catSlug_name = $this->get_field_name( $field_catSlug );
		$field_catSlug_value = esc_attr( $instance[$field_catSlug] );

		echo '<div class="spslider_options_catSlug"><label for="' . $field_catSlug_id . '">' . __('Category: ') . '';
		?>
			<input
				class=""
				id="<?php echo $field_catSlug_id; ?>"
				name="<?php echo $field_catSlug_name; ?>"
				type="text"
				value="<?php echo $field_catSlug_value; ?>"
			/>
			<?php
		echo '</label></div>';

	} // form()


	/**
	 * Front-end display of widget.
	 *
	 * @see		WP_Widget::widget()
	 *
	 * @uses	apply_filters
	 * @uses	get_widget_layout
	 *
	 * @param	array	$args		Widget arguments.
	 * @param 	array	$instance	Saved values from database.
	 */
	function widget( $args, $instance ) {

		$cache = wp_cache_get( $this->plugin_name, 'widget' );

		if ( ! is_array( $cache ) ) {

			$cache = array();

		}

		if ( ! isset ( $args['widget_id'] ) ) {

			$args['widget_id'] = $this->plugin_name;

		}

		if ( isset ( $cache[ $args['widget_id'] ] ) ) {

			return print $cache[ $args['widget_id'] ];

		}

		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		// Manipulate widget's values based on their input fields here

		$instance['thisWidgetID'] = $args['widget_id']."-wrap";

		ob_start();

		echo '<div class="'. $args["widget_id"] .'">';

		include( plugin_dir_path( __FILE__ ) . 'partials/social-proof-slider-display-widget.php' );

		echo '</div>';

		$widget_string .= ob_get_clean();
		$widget_string .= $after_widget;

		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( $this->plugin_name, $cache, 'widget' );

		print $widget_string;

	} // widget()


	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see		WP_Widget::update()
	 *
	 * @param	array	$new_instance	Values just sent to be saved.
	 * @param	array	$old_instance	Previously saved values from database.
	 *
	 * @return 	array	$instance		Updated safe values to be saved.
	 */
	function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['sortby'] = $new_instance['sortby'];
		$instance['autoplay'] = $new_instance['autoplay'];
		$instance['displaytime'] = $new_instance['displaytime'];
		$instance['animationstyle'] = $new_instance['animationstyle'];
		$instance['autoheight'] = $new_instance['autoheight'];
		$instance['verticalalign'] = $new_instance['verticalalign'];
		$instance['paddingoverride'] = $new_instance['paddingoverride'];
		$instance['contentpaddingtop'] = sanitize_text_field( $new_instance['contentpaddingtop'] );
		$instance['contentpaddingbottom'] = sanitize_text_field( $new_instance['contentpaddingbottom'] );
		$instance['featimgmargintop'] = sanitize_text_field( $new_instance['featimgmargintop'] );
		$instance['featimgmarginbottom'] = sanitize_text_field( $new_instance['featimgmarginbottom'] );
		$instance['textpaddingtop'] = sanitize_text_field( $new_instance['textpaddingtop'] );
		$instance['textpaddingbottom'] = sanitize_text_field( $new_instance['textpaddingbottom'] );
		$instance['quotemarginbottom'] = sanitize_text_field( $new_instance['quotemarginbottom'] );
		$instance['dotsmargintop'] = sanitize_text_field( $new_instance['dotsmargintop'] );
		$instance['showfeaturedimg'] = $new_instance['showfeaturedimg'];
		$instance['imgborderradius'] = $new_instance['imgborderradius'];
		$instance['showimgborder'] = $new_instance['showimgborder'];
		$instance['imgbordercolor'] = $new_instance['imgbordercolor'];
		$instance['imgborderthickness'] = $new_instance['imgborderthickness'];
		$instance['imgborderpadding'] = $new_instance['imgborderpadding'];
		$instance['bgcolor'] = $new_instance['bgcolor'];
		$instance['surroundquotes'] = $new_instance['surroundquotes'];
		$instance['textalign'] = $new_instance['textalign'];
		$instance['textcolor'] = $new_instance['textcolor'];
		$instance['showarrows'] = $new_instance['showarrows'];
		$instance['arrowiconstyle'] = $new_instance['arrowiconstyle'];
		$instance['arrowcolor'] = $new_instance['arrowcolor'];
		$instance['arrowhovercolor'] = $new_instance['arrowhovercolor'];
		$instance['showdots'] = $new_instance['showdots'];
		$instance['dotscolor'] = $new_instance['dotscolor'];
		$instance['excinc'] = $new_instance['excinc'];
		$instance['excincIDs'] = $new_instance['excincIDs'];
		$instance['catSlug'] = $new_instance['catSlug'];

		return $instance;

	} // update()

} // class
