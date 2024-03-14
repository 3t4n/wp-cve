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
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');

$employees = $this->employees;
$sel_emp   = $this->selEmployee;

?>

<!-- employees toolbar  -->
	
<?php
// Register some vars within a property of this class 
// to make it available also for the sublayout.
$this->displayData = array();

// get toolbar template, used for filters
echo $this->loadTemplate('toolbar');
?>
	
<!-- employees list -->
	
<div class="vapempallblocks">

	<?php
	foreach ($employees as $e)
	{
		// Register some vars within a property of this class 
		// to make it available also for the sublayout.
		$this->employee = $e;

		if ($this->ajaxSearch)
		{
			// AJAX search enabled, use a minified layout to include
			// also the availability table
			$tmpl = 'employee_search';
		}
		else
		{
			// otherwise use the default layout
			$tmpl = 'employee';
		}

		// get employee template, used to display profile information
		echo $this->loadTemplate($tmpl);
	}
	?>

</div>

<form action="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=employeeslist' . ($this->itemid ? '&Itemid=' . $this->itemid : '')); ?>" method="post">
	<?php echo JHtml::fetch('form.token'); ?>
	<div class="vap-list-pagination"><?php echo $this->navbut; ?></div>
	<input type="hidden" name="option" value="com_vikappointments" />
	<input type="hidden" name="view" value="employeeslist" />
</form>

<!-- employees contact form -->

<?php
// Register some vars within a property of this class 
// to make it available also for the sublayout.
$this->displayData = array();

// get quick contact template
echo $this->loadTemplate('contact');
?>

<script>

	jQuery(function($) {
		<?php
		if ($this->selEmployee)
		{
			?>
			jQuery('html,body').animate({
				scrollTop: (jQuery('#vapempblock<?php echo $this->selEmployee; ?>').offset().top - 5),
			}, {
				duration: 'normal',
			});
			<?php
		}
		?>
	});
	
</script>
