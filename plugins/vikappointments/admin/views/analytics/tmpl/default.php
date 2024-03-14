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

JHtml::fetch('formbehavior.chosen');
JHtml::fetch('vaphtml.assets.chartjs');
JHtml::fetch('vaphtml.assets.fontawesome');

$vik = VAPApplication::getInstance();

?>

<script>

	/**
	 * A lookup of preflights to be used before refreshing
	 * the contents of the widgets.
	 *
	 * If needed, a widget can register its own callback
	 * to be executed before the AJAX request is started.
	 *
	 * The property name MUST BE equals to the ID of 
	 * the widget that is registering its callback.
	 *
	 * @var object
	 */
	var WIDGET_PREFLIGHTS = {};

	/**
	 * A lookup of callbacks to be used when refreshing
	 * the contents of the widgets.
	 *
	 * If needed, a widget can register its own callback
	 * to be executed once the AJAX request is completed.
	 *
	 * The property name MUST BE equals to the ID of 
	 * the widget that is registering its callback.
	 *
	 * @var object
	 */
	var WIDGET_CALLBACKS = {};

</script>

<form action="index.php?option=com_vikappointments" method="post" name="adminForm" id="adminForm">

	<?php
	if ($this->location == 'customers')
	{
		// display toolbar used by customers location
		echo $this->loadTemplate('customers_toolbar');
	}
	
	// Display widgets dashboard through the apposite layout, since the same contents
	// are used also by the analytics page.
	echo JLayoutHelper::render('analytics.dashboard', ['dashboard' => $this->dashboard]);
	?>
	
	<input type="hidden" name="location" value="<?php echo $this->escape($this->location); ?>" />
	<input type="hidden" name="view" value="analytics" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
</form>

<?php
// render inspector to manage widgets configuration
echo JHtml::fetch(
	'vaphtml.inspector.render',
	'widget-config-inspector',
	array(
		'title'       => JText::translate('VAPMENUCONFIG'),
		'closeButton' => true,
		'keyboard'    => false,
		'footer'      => '<button type="button" class="btn btn-success" id="widget-save-config" data-role="save">' . JText::translate('JAPPLY') . '</button>',
		'width'       => 400,
	),
	// render inspector model through the apposite layout
	JLayoutHelper::render('analytics.config', ['dashboard' => $this->dashboard])
);

// Register scripts through the apposite layout, since the same functions
// are used also by the analytics page.
echo JLayoutHelper::render('analytics.script', ['dashboard' => $this->dashboard]);
