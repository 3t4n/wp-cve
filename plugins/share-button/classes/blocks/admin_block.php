<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;
use \MaxButtons\maxButton as maxButton;
use \MaxButtons\maxUtils as maxUtils;



$collectionBlock["admin"] = array('class' => "adminBlock",
								  'order' => 999);

class adminBlock extends block
{

	public function admin()
	{

		return;

		$data = $this->data;
		$preset['style'] = $data['style'];
		$preset['layout'] = $data['layout'];
		$preset['effect']  = $data['effect'];
		$preset['count']= array('font_count_size' => $data['count']['font_count_size']);
	?>
		<div class='current'>
				<p>Current Preset: </p>

				<p><?php echo json_encode($preset); ?></p>
		</div>
	<?php

		$presets = new presets();
		$sets = $presets->get();

		$set = json_decode($sets['round'], ARRAY_A);

	echo "<PRE>"; print_R($set); echo "</pre>";

		$args = array('preview' => true,
					 'load_type' => 'inline',
					 'echo' => false,
					 'compile' => true,
					);

		$stylename = $set['style']['mbs-style'];
		$style = styles::getStyle($stylename);

		$collection = new collection(0 );
		$collection->setStyle($style);

		foreach($set as $blockname => $blockdata)
		{
			$collection->setData($blockname, $blockdata);
		}

		echo $collection->display($args);


	}

}
?>
