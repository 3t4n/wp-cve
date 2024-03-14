<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;
use \MaxButtons\maxButton as maxButton;

$collectionBlock["effect"] = array('class' => "effectBlock",
								  'order' => 55);

class effectBlock extends block
{

  protected $blockname = "effect";
  protected $fields = array(
                'effect_type' => array('default' => 'none'),
								'scale' => array('default' => '120'),
            );


	public function parseCSS($css,$args)
	{
			$blockdata = $this->data[$this->blockname];
			//$is_active = isset($blockdata['use_effect']) && $blockdata['use_effect'] == 1 ? true : false;

			//if (! $is_active)
			// 		return $css;
			if (false === Install::isPRO() ) {
					return $css;
			}

			$effect = $this->getValue('effect_type');
			$style = $this->collection->getStyle();

			// maybe this should be moved to style object in time(?)
			switch($effect)
			{
				case 'transform':
					$scale = $this->getvalue('scale');
					$scale = $scale / 10;
					$css = $style->addEffect($css, $effect, array('scale' => $scale));

				break;

				case 'drop':
				case 'shift':
				case 'lift' :

					$layoutBlock = $this->collection->getBlock('layoutBlock');
					$labelsize = $layoutBlock->getValue('font_label_size');

					$css = $style->addEffect($css, $effect, array('line_height' => $labelsize));
				break;
				case 'stretch':
					 $css = $style->addEffect($css, $effect, array('orientation' => $this->collection->orientation, 'is_static' => $this->collection->is_static));
				break;
				default:
					$css = $style->addEffect($css, $effect);
				break;
			}


			$css['mb-social']['normal']['transition'] = 'all 0.4s linear';


			return $css;
	}


  public function admin()
  {
		$admin = mbSocial()->admin();



		$types = array('none' => __('None', 'mbsocial'),
									 'hover' => __('Hover', 'mbsocial'),
									 'transform' => __('Enlarge', 'mbsocial'),
		 							 'drop' => __('Drop', 'mbsocial'),
									 'lift' => __('Lift from down', 'mbsocial'),
									 'shift' => __('Shift from left', 'mbsocial'),
									 'flip' => __('Flip', 'mbsocial'),
									 'stretch' => __('Stretch', 'mbsocial'),
						);

		$shape_icons = array();

?>
<div class='help-side'>
		<h3><?php _e('Effect Options', 'mbsocial'); ?></h3>

		<p><?php _e('These will modify the behavior when a user hovers the mouse cursor over the share buttons', 'mbsocial') ?></p>

</div>

<div class='options option-container style' id='effectBlock' data-refresh='previewBlock' >
	<?php if (! Install::isPRO() ) : ?>
	<div class='forpro overlay'><div><?php echo $admin->getProMessage(); ?></div></div>
<?php endif; ?>
  <div class='title'><?php _e('Hover Effects', 'mbsocial'); ?></div>
    <div class='inside'>
    <?php

			$etype = new maxField('option_select');
			$etype->id = 'effect_type';
			$etype->name = $etype->id;
			$etype->label = __('Type', 'mbsocial');
			$etype->options = $types;
			$etype->selected = $this->getValue($etype->id);


			$admin->addField($etype, 'start','end');

			$scale = new maxField('number');
			$scale->id = 'scale';
			$scale->name = $scale->id;
			$scale->label = __('Scale', 'mbsocial');
			$scale->inputclass = 'tiny';
			$scale->min = '100';
			$scale->default = 'Enlarge in %. 100% is same size';
			$scale->value = $this->getValue('scale');
			$scale->start_conditional = htmlentities(json_encode(array('target' => $etype->id, 'values' => array('transform') )));

			$admin->addField($scale, 'start','end');

			$admin->display_fields();
 ?>
      </div>
    </div>
  <?php
  } // admin




}
