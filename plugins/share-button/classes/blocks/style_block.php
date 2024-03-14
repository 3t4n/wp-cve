<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;
use \MaxButtons\maxButton as maxButton;
use \MaxButtons\maxUtils as maxUtils;


$collectionBlock["style"] = array('class' => "styleBlock",
								  'order' => 30);

class styleBlock extends block
{
	protected $blockname = "style";
	protected $fields = array(
						'mbs-style' => array('default' => 'square'), // shapes of button, sets a bunch of css
						'mbs-width' => array('default' => '55px',
														  'css' => 'width',
														  'csspart' => 'mb-social'),
						'mbs-height' => array('default' => '55px',
															'css' => 'height',
															'csspart' => 'mb-social'),
					 //	'icon_size' => array('default' => 0),
					/* 'font_size' => array('default' => '15px',
																 'css' => 'font-size',
															 		'csspart' => 'mb-label'), */
						//'count_font_size' => array('default' => '15px'),
						//'share_font_size' => array('default' => '15px'),



	);

	public $add_preview_style = false;

//	public $preview_style = false;

	public function set($data)
	{
		parent::set($data);
		$blockdata = $this->data[$this->blockname];
		if (isset($blockdata['mbs-style']) && ! empty($blockdata['mbs-style']) )
		{
			$styleObj = styles::getStyle($blockdata['mbs-style']);

			if ($styleObj)
				$this->collection->setStyle( $styleObj );
		}
		else // default to first style - default option
		{
			//$styles = styles::getStyles();
			$def_style = $this->fields['mbs-style']['default'];
			$style = styles::getStyle($def_style);
			$this->collection->setStyle($style);


			/*if (count($styles) > 0)
			{
				$first_ar  = array_shift($styles);
				$style_obj = array_shift($first_ar);
				$data_style = $style_obj->name;
				$style = styles::getStyle($data_style);
				$this->collection->setStyle($style);
			} */
		}
	}

	public function parse($itemObj, $args = array() )
	{
		$block_data = isset($this->data[$this->blockname]) ? $this->data[$this->blockname] : array();
		$style = $this->collection->getStyle();

		if ($style->has_label)
		{

			$label = $this->network->get_label();
			if ($label)
			{

					$anchor = $itemObj->find('.mb-social', 0);
					if (is_object($anchor))
					{
							$anchor->class .= '  ';
							$label_text = '<span class="mb-label">' . $label . '</span>';
			 				$anchor->innertext .= $label_text;
			 	//	$itemObj->load($itemObj->save() );
							$this->collection->getStyle()->addDisplay('label');
					}
		 	}
		}

		if ($args['preview'] == true && $this->add_preview_style)
		{
			$style = $this->add_preview_style;
		}

		return $itemObj;
	}

	public function ajax_get_all_styles()
	{
		$available_styles = styles::getStyles();

	}

	public function admin()
	{
		//wp_enqueue_style('mbsocial-buttons');

		$admin = MBSocial()->admin();

 		$blockdata = isset($this->data[$this->blockname]) ? $this->data[$this->blockname] : array();

		$data_style = isset($blockdata['mbs-style']) ? $blockdata['mbs-style'] : false; //

		$icon_url = MBSocial()->get_plugin_url() . 'images/icons/';
		$icons = array(
				'shape_round' => 'shape_circle.png',
				'shape_square' => 'shape_square.png',
			//	'shape_stretch' => 'shape_stretch.png',
		);

?>
<div class='help-side'>
	<h3><?php _e("Style Options", 'mbsocial'); ?></h3>
	<p>Use the <strong>Presets</strong> to set multiple style options at once. This will overwrite some current settings : Shape, Width and the items under Customization. It will not change your network selections. Once you have selected a preset you can use Shape, Width and Height to customize it.</p>

	<p><strong>Shapes</strong> - Choose between circle and rectangle.</p>

</div>

		<div class='options option-container style' id='styleBlock' data-refresh='previewBlock' >
			<div class='title'><?php _e('Style', 'mbsocial'); ?></div>
			<div class='inside'>
<?php
				$preset = new maxField('button');
				$preset->id = 'open_presets';
				$preset->name = 'preset';
				$preset->modal = 'modal_styles';
				$preset->inputclass = 'button button-primary maxmodal';
				$preset->label= '&nbsp;';
				$preset->button_label = __('Select Preset', 'mbsocial');

				$admin->addField($preset, 'start', 'end', false);

		    $spacey = new maxField('spacer');
				$spacey->name = 'label';
				$spacey->label = __("Shape", 'mb-social');

				$admin->addField($spacey, 'start', '', false);

				$shape_round = new maxField('radio');
				$shape_round->id = 'radio_shape_round';
				$shape_round->name = 'mbs-style';
				$shape_round->value = 'round';
				$shape_round->title = __('Circle', 'mbsocial');
				$shape_round->inputclass = 'check_button icon';
				$shape_round->custom_icon = 'mbsocial-shape circle';
				$shape_round->checked = checked($this->getValue('mbs-style'), $shape_round->value, false);

				$admin->addField($shape_round);

				$shape_square = new maxField('radio');
				$shape_square->id = 'radio_shape_square';
				$shape_square->name = 'mbs-style';
				$shape_square->value = 'square';
				$shape_square->title = __('Rectangle', 'mbsocial');
				$shape_square->inputclass = 'check_button icon';
				//$shape_square->image = $icon_url . $icons['shape_square'];
				$shape_square->custom_icon = 'mbsocial-shape square';
				$shape_square->checked = checked($this->getValue('mbs-style'), $shape_square->value, false);
				$admin->addField($shape_square, '', 'end');

		/*		$shape_stretch = new maxField('radio');
				$shape_stretch->id = 'radio_shape_stretch';
				$shape_stretch->name = 'mbs-style';
				$shape_stretch->title = __('Stretch', 'mbsocial');
				$shape_stretch->value = 'stretch';
				$shape_stretch->inputclass = 'check_button icon';
				$shape_stretch->custom_icon = 'mbsocial-shape stretch';
				$shape_stretch->checked = checked($this->getValue('mbs-style'), $shape_stretch->value, false);
				$admin->addField($shape_stretch, '', 'end');
*/
				$width = new maxField('number');
				$width->id = 'mbs-width';
				$width->name = $width->id;
				$width->label = __('Width', 'mbsocial');
				$width->inputclass = 'tiny';
				$width->min = 0;
				$width->value = maxUtils::strip_px($this->getValue('mbs-width'));

				$admin->addField($width, 'start');

				$height = new maxField('number');
				$height->id = 'mbs-height';
				$height->name = $height->id;
				$height->label = __('Height', 'mbsocial');
				$height->inputclass = 'tiny';
				$height->min = 0;
				$height->value = maxUtils::strip_px($this->getValue('mbs-height'));

				$admin->addField($height, '','end');


				$admin->display_fields();

?>

			</div> <!-- inside-->
		</div> <!-- option container -->

<div id='modal_styles' class='maxmodal-data'  data-load='window.maxFoundry.maxSocial.loadPresetsPopup'>
		<div class='title'><?php _e('Presets', 'mbsocial'); ?></div>

		<div class='content'>
				<div class='warning top-note hidden'><?php _e('By selecting a different Preset, your settings for Style and Customization will be overwritten', 'mbsocial'); ?> </div>
				<div class='welcome top-note hidden'><?php _e('Welcome to WordPress share buttons. Get started fast by selecting a preset!','mbsocial'); ?></div>


			<div class='style_modal style-row wrapper'>


			</div>

		</div>  <!-- content -->

	</div> <!-- modal -->



<?php
	} // admin


} // class
