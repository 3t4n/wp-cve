<?php
namespace MBSocial;
defined('ABSPATH') or die('No direct access permitted');

$collectionBlock["count"] = array('class' => "countBlock",
				 'order' => 50);

use \MaxButtons\maxField as maxField;
use \MaxButtons\maxBlocks as maxBlocks;
use \MaxButtons\maxUtils as maxUtils;

class countBlock extends block
{

	protected $blockname = "count";
	protected $fields = array( 'count_active' => array('default' => 0),
				   'share_min_count' => array('default' => '5'),
				   'show_total_count' => array('default' => '0'),
				   'total_count_label' => array('default' => 'Shares'),

					 'font_count_size' => array('default' => '15px',
				 															'css' => 'font-size',
																			'csspart' => 'mb-share-count',
																		),
					 "font_count_style" => array("default" => "normal",
										  "css" => "font-style",
										  "csspart" => 'mb-share-count'),
					"font_count_weight" => array("default" => "normal",
										  "css" => "font-weight",
										  "csspart" => 'mb-share-count'),
					"total_count_color" => array('default' => '',
											'css' => 'color',
											'csspart' => 'mb-icon-total-icon,mb-count-total,mb-label-total'),
					);

	protected static $total = 0;

	protected $ajax_remote_count = 0;


	public function parse($itemObj, $args = array() )
	{
		$blockdata = $this->data[$this->blockname];
		$w = MBSocial()->whistle();

		$active = isset($blockdata['count_active']) ? $blockdata['count_active'] : false;
		$share_min_count = isset($blockdata['share_min_count']) ? $blockdata['share_min_count'] : 0;

		// this can use some streamlining
		//$network_block = $this->collection->getBlock('networkBlock');
		//$share_data = $network_block->getShareData();
		$share_url = $w->ask('display/vars/url'); //esc_url($share_data['url']);

		if ( ( $active == 1 || $this->showTotal() )  && $this->network->isCountable() )
		{

			if ($args['preview'] == true)
			{
				$share_min_count = 0;
				$share_count = round(rand(1,9));

			}

			else
				$share_count = $this->network->getShareCount(array(
							'url' => $share_url));

			if ($active && $share_count > $share_min_count)
			{
				$anchor = $itemObj->find('.mb-social',0);

				$anchor->class .= ' ';
				$anchor->innertext .= "<span class='mb-share-count'>" . $this->format_count($share_count) . "</span>";
				$this->collection->getStyle()->addDisplay('count');

			}
			elseif ( $share_count === false)  // request remote count
			{
				$tag = "data-onload";

				$json = array("network" => $this->network->get('network') ,
							  "share_url" => esc_url($share_url),
							  "count_threshold" => $share_min_count,
							 );
				$item = $itemObj->find('.mb-item', 0);
				$item->$tag =  htmlentities(json_encode($json), ENT_QUOTES, 'UTF-8');

			}

			if (is_numeric($share_count) && $share_count > 0)
			{

				self::$total += $share_count;
			}
		}

		$itemObj->load($itemObj->save());
		return $itemObj;
	}


	// create a block for total shares
	public function createTotal($collectionObj, $args = array() )
	{
		$w = MBSocial()->whistle();

		if (! $this->showTotal() )
		{
				$w->tell('display/env/has_totals', false);
				return;
		}
		$total = $this->get_total();
		$min_count = $this->getValue('share_min_count');

		if ($total < $min_count)
		{
			return $collectionObj;
		}

		$total_label = $this->getValue('total_count_label'); //$count_block->get_total_label();

		$total_html = '<span class="mb-total-container">
				<div class="maxbutton-social-total">
					<div class="mb-icon-total"><i class="mb-icon-total-icon dashicons dashicons-share"></i></div>
						<div class="mb-totals">
					<div class="mb-count-total">' . $total . '</div>
					<div class="mb-label-total">' . $total_label .'</div>
					 </div>
				</div>
			</span>';

		$w->tell('display/env/has_totals', true);

		$collectionObj->find('.maxsocial',0)->innertext = $total_html;
		$collectionObj->load($collectionObj->save());
		return $collectionObj;
	}

	public function get_total($format = true)
	{
		if ($format)
		{
				return $this->format_count(self::$total);
		}
		else
			return self::$total;
	}

	public function showTotal()
	{

		$show_total = isset($this->data[$this->blockname]['show_total_count']) ? $this->data[$this->blockname]['show_total_count'] : false;
		if ($show_total == 1)
			return true;

		return false;
	}

	public function set($data)
	{
		$data = parent::set($data);

		$this->resetTotal(); // reset total counter

		return true;

	}

	/* Format share counts to proper values for display
	* About 10,000 => 10K
	*/
	public function format_count($count)
	{
			// must be a number
		if ( ! is_numeric($count) )
			return $count;

		// must be significant
		if ($count < 10000)
				return $count;


		if ($count >= 1000000)
			 	$count = round($count / 1000000) . __("M",'mbsocial');
		elseif ($count >= 10000)
		 	$count = round($count/1000) . __("K", 'mbsocial');

			$count = apply_filters('mbsocial/count/format', $count);
			return $count;
	}

	public function resetTotal()
	{
		self::$total = 0;

	}

	public function ajax_get_count($result, $data)
	{
		$share_url = sanitize_text_field($data['share_url']);
		$network_name = sanitize_text_field($data['network']);
		$network = MBSocial()->networks()->get($network_name);

 		// check if share count appeared in the cache. If this is the case it's possible another process put it there.
 		$do_remote = true;
 		$share_count = $network->getShareCount(array("url" => $share_url));
 		if ($share_count !== false)
 		{
 			$count = $share_count;
 			$do_remote = false; // stop annoying other servers
 		}

 		if ($do_remote)
 		{
	 		// returns false, a number or 'locked' in case of locked.
	 		$count = $network->getRemoteShareCount($share_url);

			/* If the request is lock, another request is probably asking for this and shouldn't take long.
				Try a maximum of 5 times to prevent server process hanging until php times out ( we want to prevent that ) */
	 		if ($count == 'locked')
	 		{
	 			if ($this->ajax_remote_count < 5)
	 			{
	 				sleep(1); // in seconds please.
	 				// after retry result here will at some point have the count data, extract and return at the end.
	 				$result = $this->ajax_get_count($result,$data);
	 				$count = $result["data"]["count"];
	 			}
	 			else
	 				$count = "TIMEOUT";
	 			$this->ajax_remote_count++;
	 		}
 		}

		$count = $this->format_count($count);
 		$result["data"] = array('count' => intval($count) );

 		return $result;

	}

	public function admin()
	{
?>
	<div id='countBlock' class='options option-container count' data-refresh='previewBlock'>
		<div class='title'><?php _e('Share Count Options'); ?></div>
		<div class='inside'>
	<?php
		$blockdata = $this->data[$this->blockname];
		$admin = MBSocial()->admin();

		$active = new maxField('switch');
		$active->id = 'count_active';
		$active->name = $active->id;
		$active->value = 1;
		$active->label = __('Show Share Counts', 'mbsocial');
		$active->checked = checked( $this->getValue('count_active'), 1, false);
		$active->publish = false;

		$admin->addField( $active, 'start','end');

		$min_count = new maxField('number');
		$min_count->id = 'share_min_count';
		$min_count->min = 0;
		$min_count->name = $min_count->id;
		$min_count->inputclass = 'tiny';
		$min_count->value = $this->getValue('share_min_count');
 		$min_count->label = __('Minimum count to show shares','mbsocial');

		$admin->addField( $min_count, 'start', 'end', false);

		// *** COUNT SIZE *** //
		$field_size = new maxField('number');
		$field_size->label = __('Number Size', 'mbsocial');
		$field_size->name = 'font_count_size';
		$field_size->id= $field_size->name;
		$field_size->inputclass = 'tiny';
		$field_size->min = 8;
		$field_size->value = maxUtils::strip_px($this->getValue('font_count_size'));
		$admin->addField($field_size, 'start');

		// Font style checkboxes
		$fweight = new maxField('checkbox');
		$fweight->icon = 'dashicons-editor-bold';
		$fweight->title = __("Bold",'mbsocial');
		$fweight->id = 'check_count_fweight';
		$fweight->name = 'font_count_weight';
		$fweight->value = 'bold';
		$fweight->inputclass = 'check_button icon';
		$fweight->checked = checked( $this->getValue('font_count_weight'), 'bold', false);
		$admin->addField($fweight, 'group_start');

		$fstyle = new maxField('checkbox');
		$fstyle->icon = 'dashicons-editor-italic';
		$fstyle->title = __("Italic",'mbsocial');
		$fstyle->id = 'check_count_fstyle';
		$fstyle->name = 'font_count_style';
		$fstyle->value = 'italic';
		$fstyle->inputclass = 'check_button icon';
		$fstyle->checked = checked( $this->getValue('font_count_style'), 'italic', false);
		$admin->addField($fstyle, '', array('group_end', 'end'));

		$total_count = new maxField('switch');
		$total_count->id = 'show_total_count';
		$total_count->name = $total_count->id;
		$total_count->label = __('Show total count', 'mbsocial');
		$total_count->value = 1;
		$total_count->checked = checked ($this->getValue('show_total_count'), 1, false);
		$admin->addField($total_count, 'start', 'end');

		$tcount_label = new maxField();
		$tcount_label->id = 'total_count_label';
		$tcount_label->name = $tcount_label->id;
		$tcount_label->label = __('Total Count Label', 'mbsocial');
		$tcount_label->inputclass = 'medium';
		$tcount_label->value = $this->getValue('total_count_label');
		$admin->addField($tcount_label, 'start', 'end');

		$total_color = new maxField('color');
		$total_color->id = 'total_count_color';
		$total_color->name = $total_color->id;
		$total_color->label = __('Total Count Color', 'mbsocial');
		$total_color->value = '';

		$admin->addField($total_color, 'start', 'end');

		$admin->display_fields();
	?>
		</div> <!-- inside -->
	</div> <!-- option container -->
<?php

	}

} // class
