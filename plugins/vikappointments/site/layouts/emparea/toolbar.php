<?php
/** 
 * @package     VikAppointments
 * @subpackage  core
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$mainmenu = isset($displayData['mainmenu'])	? $displayData['mainmenu'] : array();
$sidemenu = isset($displayData['sidemenu'])	? $displayData['sidemenu'] : array();
$auth 	  = isset($displayData['auth'])     ? $displayData['auth']     : VAPEmployeeAuth::getInstance();
$active   = isset($displayData['active'])   ? $displayData['active']   : true;
$itemid   = isset($displayData['itemid'])   ? $displayData['itemid']   : null;

$input = JFactory::getApplication()->input;

if (is_null($itemid))
{
	// item id not provided, get the current one (if set)
	$itemid = $input->getInt('Itemid');
}

?>
 
<div class="vapemplogintoolbardiv">
	
	<?php
	/**
	 * IMPORTANT.
	 * The following <div> tags must be stuck without spaces in order to avoid bad alignments
	 * while hovering with the mouse above the links.
	 *
	 * So, we must have this:
	 * </div><div>
	 *
	 * Instead of:
	 * </div>
	 * <div>
	 */
	foreach ($mainmenu as $alias => $item)
	{
		if ($active)
		{
			if (!isset($item['query']))
			{
				$item['query'] = array();
			}

			if ($itemid)
			{
				// inject Itemid within the query string
				$item['query']['Itemid'] = $itemid;
			}

			// build base URL
			$url = 'index.php?option=com_vikappointments';

			if ($item['query'])
			{
				// append query string to url
				$url .= '&' . http_build_query($item['query']);
			}
		}
		else
		{
			// always disable menu items
			$item['active'] = false;
		}
		?><div class="vapemploginactionlink<?php echo !$item['active'] ? 'disabled' : ''; ?> <?php echo !empty($item['selected']) ? 'item-active' : ''; ?>" data-id="<?php echo $this->escape($alias); ?>">
			<a href="<?php echo $item['active'] ? JRoute::rewrite($url) : 'javascript:void(0)'; ?>">
				<?php 
				if (!empty($item['icon']))
				{
					// check if the icon starts with a tag
					if (preg_match("/^</", $item['icon']))
					{
						// HTML tag found, display as is
						echo $item['icon'];
					}
					else
					{
						// FontAwesome icon assumed
						?>
						<i class="<?php echo $this->escape($item['icon']); ?>"></i>
						<?php
					}
				}

				// display item title
				echo @$item['title'];
				?>
			</a>
		</div><?php
	}
	?>
	
    <div class="vap-emplogin-rcont">

		<div class="vap-emplogin-rbox">
			<div class="vap-emplogin-rphoto">
				<?php
				if ($auth->image)
				{
					?>
					<a href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=emplogin' . ($itemid ? '&Itemid=' . $itemid : '')); ?>">
						<img src="<?php echo VAPMEDIA_SMALL_URI . $auth->image; ?>" />
					</a>
					<?php
				}
				?>
			</div>

			<div class="vap-emplogin-rtitle">
				<a href="javascript:void(0)"><?php echo $auth->nickname; ?></a>
			</div>
		</div>
		
		<?php
		if ($sidemenu)
		{
			?>
			<div class="vap-emplogin-modal" style="display: none;">
				<ul>
					<?php
					foreach ($sidemenu as $alias => $item)
					{
						if (!isset($item['query']))
						{
							$item['query'] = array();
						}

						if ($itemid)
						{
							// inject Itemid within the query string
							$item['query']['Itemid'] = $itemid;
						}

						// build base URL
						$url = 'index.php?option=com_vikappointments';

						if ($item['query'])
						{
							// append query string to url
							$url .= '&' . http_build_query($item['query']);
						}
						?>
						<li class="<?php echo $item['separator'] ? 'separator' : ''; ?> <?php echo !empty($item['selected']) ? 'item-active' : ''; ?>" data-id="<?php echo $this->escape($alias); ?>">
							<a href="<?php echo JRoute::rewrite($url); ?>">
								<?php 
								if (!empty($item['icon']))
								{
									// check if the icon starts with a tag
									if (preg_match("/^</", $item['icon']))
									{
										// HTML tag found, display as is
										echo $item['icon'];
									}
									else
									{
										// FontAwesome icon assumed
										?>
										<i class="<?php echo $this->escape($item['icon']); ?>"></i>
										<?php
									}
								}

								// display item title
								echo @$item['title'];
								?>
							</a>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
			<?php
		}
		?>

	</div>
	
</div>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('html').click(() => {
				$('.vap-emplogin-modal').hide();
			});

			$('.vap-emplogin-rtitle').click((event) => {
				event.stopPropagation();
				$('.vap-emplogin-modal').toggle();
			});
		});
	})(jQuery);

</script>
