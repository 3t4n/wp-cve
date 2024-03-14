<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxUtils as maxUtils;

$collectionBlock["layout"] = array('order' => 40,
								   'class' => "layoutBlock"
								  );

class layoutBlock extends block
{
	protected $blockname = "layout";
	protected $fields = array(
						"margin_left" => array("default" => "0px",
												"css" => "margin-left",
												"csspart" => "maxsocial"),

						"margin_right" => array("default" => "0px",
												"css" => "margin-right",
												"csspart" => "maxsocial"),

						"margin_top" => array("default" => "0px",
												"css" => "margin-top",
												"csspart" => "maxsocial"),

						"margin_bottom" => array("default" => "0px",
												"css" => "margin-bottom",
												"csspart" => "maxsocial"),
						"orientation" => array("default" => "auto"),

						"font" => array("default" => "",
															  "css" => "font-family",
															  "csspart" => 'mb-label,mb-share-count'
															  ),

						"font_label_size" => array("default" => "15px",
											  "css" => "font-size",
											  "csspart" => 'mb-label' ),

						"font_label_style" => array("default" => "normal",
											  "css" => "font-style",
											  "csspart" => 'mb-label'),

						"font_label_weight" => array("default" => "normal",
											  "css" => "font-weight",
											  "csspart" => 'mb-label'),

						"font_label_upper" =>  array('default' => 'none',
												'css' => 'text-transform',
												'csspart' => 'mb-label',
											),

						"font_icon_size" => array("default" => "15px",
											  "css" => "font-size",
											  "csspart" => 'mb-icon' ),

						"font_icon_style" => array("default" => "normal",
											  "css" => "font-style",
											  "csspart" => 'mb-icon'),

						"font_icon_weight" => array("default" => "normal",
											  "css" => "font-weight",
											  "csspart" => 'mb-icon'),

						'use_background' => array('default' => 1, ),
						'background_color' => array('default' => '#000',
													'css' => 'background-color',
													'csspart' => 'mb-social',
											),
						'background_color_hover' => array('default' => '#000',
													 'css' => 'background-color',
													 'csspart' => 'mb-social',
													 'csspseudo' => 'hover',
											),
						'color' 		=> array('default' => '#fff',
										 'css' => 'color',
										 'csspart' => 'mb-icon,mb-share-count,mb-label'),
						'color_hover'	 => array('default' => '#fff',
												  'css' => 'color',
												  'csspart' => 'mb-icon,mb-share-count,mb-label',
												  'csspseudo' => 'hover'),


						"button_spacing" => array('default' => '5',
												 ),
						"ignore_container" => array("default" => 1),


			);

	public function __construct()
	{
		parent::__construct();

		if (Install::isPro())
				add_filter('mbsocial/displaycss/', array($this, 'addWebFont') );

	}

	public function addWebFont($css)
	{
			$fonts = '';

			if ( isset ($this->data[$this->blockname]['webfonts']))
			{

					$webfonts = $this->data[$this->blockname]['webfonts'];
					$fonts .= '@import url(' . $webfonts . ');';

			}
				return $fonts . $css;
	}

	function parseCSS($css, $args)
	{
		$css = parent::parseCSS($css, $args);
		$w = MBSocial()->whistle();

		$hook = $this->collection->getHook();
		$blockdata = $this->data[$this->blockname];

		$network = $this->network;

		// if true, use network colors
		$use_background = isset($blockdata['use_background']) ? $blockdata['use_background'] : false;

		if ($use_background)
		{
			unset ($css['mb-social']['normal']['background-color']);
			unset ($css['mb-social']['hover']['background-color']);

			$color = $network->get('color');

			$css['mb-social']['normal']['background-color'] = $color;
			$css['mb-social']['hover']['background-color'] = "darken($color, 10%)";
		}

		$css["mb-item"]["normal"]["float"] = "left";
 		$css["mb-item"]["normal"]["display"] = "inline-block";
 		$css["mb-item a"]["normal"]["cursor"] = "pointer"; // by default social share = call to action.
		$css['mb-item a']['normal']['text-decoration'] = 'none';



		if (isset($blockdata['button_spacing']) && $blockdata['button_spacing'] > 0)
		{
			$orientation = $this->collection->orientation;
			$is_static = $w->ask('display/env/is_static');

			$button_spacing = $blockdata['button_spacing'];

			if ($orientation == 'horizontal')
			{
				$css['mb-item']['normal']['margin-right'] = $button_spacing . 'px';
			}
			if ($orientation == 'vertical')
			{
				$css['mb-item']['normal']['margin-bottom'] = $button_spacing . 'px';
			}
		}

		// Check font again.
			 if (Install::isPro())
			 {
				 //var_dump($css);
				 $blockdata = $this->data[$this->blockname];
				 $font = $blockdata['font'];
				 $args = array('font' => $font);
				 $webfont = patches::checkWebFonts($args);
				 $this->data[$this->blockname]['webfonts'] = $webfont;
			}

		return $css;

	}

	public function parse($itemObj, $args = array() )
	{
		$blockdata = $this->data[$this->blockname];

		$use_background = isset($blockdata['use_background']) ? $blockdata['use_background'] : false;

		return $itemObj;

	}

	function save_fields($data, $post)
	{
		$data = parent::save_fields($data, $post);
		if (! isset($post["ignore_container"]) && ! is_null($post))
			$data[$this->blockname]["ignore_container"] = 0;
		if (! isset($post['use_background']) && ! is_null($post))
			$data[$this->blockname]['use_background'] = 0;

		if ( count ($post) == 0)
			return $data; // don't run on default situations

	  // Do the font here
    $blockdata = $data[$this->blockname];
		$font = $blockdata['font'];
		//$label_weight = $blockdata['font_label_weight'];
	//	$icon_weight = $blockdata['icon_label_weight'];

		$args = array('font' => $font);

		$webfont = patches::checkWebFonts($args);
		$data[$this->blockname]['webfonts'] = $webfont;

		return $data;

	}

	function fontModal()
	{
		?>
		<div class='maxmodal-data' id='add-fonts' data-load='window.maxFoundry.maxfonts.load' data-height='90%'>
				<span class='title'><?php _e("Font Manager","maxbuttons-pro"); ?>

				</span>

				<span class='content'>
					<p><?php printf(__("Select from %s fonts you would like to use and they will show up in the dropdown list", 'maxbuttons-pro'), '<span class="fontcount"></span>'); ?></p>
					<div class="loading_overlay"></div>
					<div class='loading'>
						<img src="<?php echo MB()->get_plugin_url(false) ?>images/spinner.gif">
						<span><?php _e("Loading","maxbuttons-pro"); ?></span>
					</div>
					<div class="font_manager">
						<div class='font_search'> <span><?php _e("Search","maxbuttons-pro") ?></span>
								<input type='text' name='font_search' value='' />
						</div>

						<div class="font_wrap">
							<div class='font_left'>
							<ul class='items'>

							</ul>
							</div>
							<div class='font_right '>
								<ul class='items'>

								</ul>
							</div>
						</div>
						<div class='font_example'>
							<span class='placeholder'><?php _e("Click on a font to see an example","maxbuttons-pro"); ?></span>
							<span class='example_text'><?php _e("AaBbCcDdEeFfGgEeHh") ?></span>
						</div>
					</div>
				</span>
				<div class='controls'>
					<div class='controls_inline'>
						<button type='button' name='save_fonts' class='button-primary'><?php _e("Save changes", 'maxbuttons-pro'); ?></button> &nbsp;
						<button type='button' class='button-primary modal_close'><?php _e("Close","maxbuttons-pro"); ?></button>
					</div>
				</div>
			</div> <!-- maxmodal-data -->
			<?php
	}

	function admin()
	{
		$admin = mbSocial()->admin();

?>

	<div class='options option-container layout' id='layoutBlock' data-refresh='previewBlock'  >
		<div class='title'><?php _e('Customization', 'mbsocial' ); ?></div>
		<div class='inside'>
		<?php

			$colorsw = new maxField('switch');
			$colorsw->label = __('Use solid colors','mbsocial');
			$colorsw->id = 'use_background';
			$colorsw->value = 1;
			$colorsw->name = $colorsw->id;
			$colorsw->inputclass = 'update_change';
			$colorsw->checked = checked( $this->getValue('use_background'), 1, false);
			$admin->addField( $colorsw, 'start', '');


			$ispacer = new maxField('spacer');
			$ispacer->label = __('Use Social Network Brand Colors','mbsocial');
			$ispacer->publish = false;
			$ispacer->output('','end');

			$admin->addField($ispacer, '', 'end', false);

			$bgcolor = new maxField('color') ;
			$bgcolor->label = __('Background color', 'mbsocial');
			$bgcolor->value = $this->getColorValue('background_color');
			$bgcolor->id = 'background_color';
			$bgcolor->name = $bgcolor->id;
			$admin->addField($bgcolor, 'start', '');

			$bgcolor_hover = new maxField('color');
			$bgcolor_hover->label = __('Background Hover', 'mbsocial');
			$bgcolor_hover->value = $this->getColorValue('background_color_hover');
			$bgcolor_hover->id = 'background_color_hover';
			$bgcolor_hover->name = $bgcolor_hover->id;
			$admin->addField( $bgcolor_hover, '', 'end');

			$color = new maxField('color');
			$color->label = __('Color', 'mbsocial');
			$color->value = $this->getColorValue('color');
			$color->id = 'color';
			$color->name = $color->id;
			$admin->addField($color, 'start', '');

			$color_hover = new maxField('color');
			$color_hover->label = __('Hover', 'mbsocial');
			$color_hover->value = $this->getColorValue('color_hover');
			$color_hover->id = 'color_hover';
			$color_hover->name = $color_hover->id;
			$admin->addField( $color_hover, '', 'end');

			// FONTS
			/* $button = new maxField('generic');
			$button->label = '';
			$button->id = 'manage-fonts';
			$button->name = 'button_fonts';
			$button->content = '<div id="manage-fonts" class="button manage-fonts maxmodal" data-modal="add-fonts" title="' . __("Add additional fonts","maxbuttons-pro") . '" ><i class="dashicons dashicons-editor-spellcheck"></i>' . __('Font Manager', 'maxbuttons-pro') . '</div>';
			$admin->addField($button, 'start', 'end');
*/
							$fonts = MB()->getClass('admin')->loadFonts();

		 					$field_font = new maxField('generic');
		 					$field_font->label = __('Font Family','maxbuttons');
		 					$field_font->name = 'font';
		 					$field_font->id = $field_font->name;
		 					$field_font->value= $this->getValue('font');
		 					$field_font->content = maxUtils::selectify($field_font->name, $fonts, $field_font->value);
							$admin->addField($field_font,'start', 'end');
		 					?>

						<?php
							// FONT SIZE
							//global $maxbuttons_font_sizes;
							//$sizes = apply_filters('mb/editor/fontsizes', maxUtils::generate_font_sizes(10,50) );

							// *** LABEL SIZE *** //
							$field_size = new maxField('number');
							$field_size->label = __('Label Size', 'mbsocial');
							$field_size->name = 'font_label_size';
							$field_size->id= $field_size->name;
							$field_size->inputclass = 'tiny';
							$field_size->min = 8;
							$field_size->value = maxUtils::strip_px($this->getValue('font_label_size'));
							$admin->addField($field_size, 'start');

							// Font style checkboxes
					 		$fweight = new maxField('checkbox');
					 		$fweight->icon = 'dashicons-editor-bold';
					 		$fweight->title = __("Bold",'mbsocial');
					 		$fweight->id = 'check_label_fweight';
					 		$fweight->name = 'font_label_weight';
					 		$fweight->value = 'bold';
					 		$fweight->inputclass = 'check_button icon';
					 		$fweight->checked = checked( $this->getValue('font_label_weight'), 'bold', false);
							$admin->addField($fweight, 'group_start');

					 		$fstyle = new maxField('checkbox');
					 		$fstyle->icon = 'dashicons-editor-italic';
					 		$fstyle->title = __("Italic",'mbsocial');
					 		$fstyle->id = 'check_label_fstyle';
					 		$fstyle->name = 'font_label_style';
					 		$fstyle->value = 'italic';
					 		$fstyle->inputclass = 'check_button icon';
					 		$fstyle->checked = checked( $this->getValue('font_label_style'), 'italic', false);
							$admin->addField($fstyle);

							$fupper = new maxField('checkbox');
							$fupper->icon = 'dashicons-arrow-up';
							$fupper->title = __('Uppercase', 'mbsocial');
							$fupper->id = 'check_label_fupper';
							$fupper->name = 'font_label_upper';
							$fupper->value = 'uppercase';
							$fupper->inputclass = 'check_button icon';
							$fupper->checked = checked ( $this->getValue('font_label_upper'), 'uppercase', false);

							$admin->addField($fupper, '', array('group_end', 'end'));

							// *** ICON SIZE *** //
							$field_size = new maxField('number');
							$field_size->label = __('Icon Size', 'mbsocial');
							$field_size->name = 'font_icon_size';
							$field_size->id= $field_size->name;
							$field_size->inputclass = 'tiny';
							$field_size->min = 8;
							$field_size->value = maxUtils::strip_px($this->getValue('font_icon_size'));
							$admin->addField($field_size, 'start');

							// Font style checkboxes
					 		$fweight = new maxField('checkbox');
					 		$fweight->icon = 'dashicons-editor-bold';
					 		$fweight->title = __("Bold",'mbsocial');
					 		$fweight->id = 'check_icon_fweight';
					 		$fweight->name = 'font_icon_weight';
					 		$fweight->value = 'bold';
					 		$fweight->inputclass = 'check_button icon';
					 		$fweight->checked = checked( $this->getValue('font_icon_weight'), 'bold', false);
							$admin->addField($fweight, 'group_start');

					 		$fstyle = new maxField('checkbox');
					 		$fstyle->icon = 'dashicons-editor-italic';
					 		$fstyle->title = __("Italic",'mbsocial');
					 		$fstyle->id = 'check_icon_fstyle';
					 		$fstyle->name = 'font_icon_style';
					 		$fstyle->value = 'italic';
					 		$fstyle->inputclass = 'check_button icon';
					 		$fstyle->checked = checked( $this->getValue('font_icon_style'), 'italic', false);
							$admin->addField($fstyle, '', array('group_end', 'end'));


			// Margins
			$icon_url = MB()->get_plugin_url() . 'images/icons/' ;

	 		$mtop = new maxField('number');
	 		$mtop->publish = false;
	 		$mtop->label = __('Margin top', 'mbsocial');
	 		$mtop->id = 'margin_top';
	 		$mtop->name = $mtop->id;
			$mtop->min = 0;
	 		$mtop->inputclass = 'tiny';
	 		$mtop->before_input = '<img src="' . $icon_url . 'p_top.png" title="' . __("Margin Top","mbsocial") . '" >';
	 		$mtop->value = maxUtils::strip_px($this->getValue('margin_top'));

	 		//$mtop->output('start');
			$admin->addField($mtop, 'start','', false);

			$mbottom = new maxField('number');
			$mbottom->label = __('Margin Bottom', 'mbsocial');
			$mbottom->id = 'margin_bottom';
			$mbottom->name = $mbottom->id;
			$mbottom->min = 0;
			$mbottom->inputclass = 'tiny';
			$mbottom->before_input = '<img src="' . $icon_url . 'p_bottom.png" title="' . __("Margin Bottom","mbsocial") . '" >';
			$mbottom->value = maxUtils::strip_px($this->getValue('margin_bottom') );

			$admin->addField( $mbottom, '', 'end', false );

			$mleft = new maxField('number');
			$mleft->label = __('Margin Left', 'mbsocial');
			$mleft->id = 'margin_left';
			$mleft->name = $mleft->id;
			$mleft->min = 0;
			$mleft->inputclass = 'tiny';
			$mleft->before_input = '<img src="' . $icon_url . 'p_left.png" title="' . __("Margin Left","mbsocial") . '" >';
			$mleft->value = maxUtils::strip_px($this->getValue('margin_left'));

			$admin->addField( $mleft,'start', '', false);

			$mright = new maxField('number');
			$mright->label = __('Margin Right', 'mbsocial');
			$mright->id = 'margin_right';
			$mright->name = $mright->id;
			$mright->min = 0;
			$mright->inputclass = 'tiny';
			$mright->before_input = '<img src="' . $icon_url . 'p_right.png" title="' . __("Margin Right","mbsocial") . '" >';
			$mright->value = maxUtils::strip_px($this->getValue('margin_right'));

			$admin->addField( $mright, '', 'end', false);

			// Spacing

			$spacing = new maxField('number');
			$spacing->label = __('Button Spacing', 'mbsocial');
			$spacing->id = 'button_spacing';
			$spacing->name = $spacing->id;
			$spacing->min = 0;
			$spacing->inputclass = 'tiny';
			$spacing->value = maxUtils::strip_px($this->getValue('button_spacing'));

			$admin->addUpdate($spacing);
			$admin->addField($spacing, 'start', 'end');

			$admin->display_fields();
		?>
		</div> <!-- inside -->
	</div>	 <!-- option container -->


	<?php $this->fontModal(); ?>

<?php
} // admin

}
