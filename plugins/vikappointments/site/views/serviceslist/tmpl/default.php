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

JHtml::fetch('vaphtml.assets.fancybox');

$vik = VAPApplication::getInstance();

?>	
		
<form action="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=servicesearch' . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>" method="post" name="servicesform">
	
	<div class="vapserallblocks">
	
		<?php
		foreach ($this->groups as $group)
		{
			?>
			<div class="vapsergroup <?php echo $vik->getThemeClass('background'); ?>">

				<?php
				// Register the group details within a property of this class 
				// to make it available also for the sublayout.
				$this->group = $group;

				// get group template
				echo $this->loadTemplate('group');
				?>

				<div class="vapservicescont">

					<?php
					foreach ($group->services as $service)
					{
						// Register the service details within a property of this class 
						// to make it available also for the sublayout.
						$this->service = $service;
						
						// get service template
						echo $this->loadTemplate('service');
					}
					?>

				</div>

			</div>
			<?php
		}
		?>
	
	</div>
		
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="view" value="servicesearch" />	
</form>
