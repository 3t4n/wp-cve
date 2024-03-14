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

$params = $this->params;

$vik = VAPApplication::getInstance();

$languages = VikAppointments::getKnownLanguages();

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigShopReviews". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('ShopReviews');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
		
		<!-- ENABLE REVIEWS - Radio Button -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['enablereviews'] == 1, 'onClick="reviewsValueChanged(1);"');
		$no  = $vik->initRadioElement('', '', $params['enablereviews'] == 0, 'onClick="reviewsValueChanged(0);"');
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG74'));
		echo $vik->radioYesNo('enablereviews', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- SERVICES REVIEWS - Radio Button -->
		
		<?php
		$control = array();
		$control['style'] = $params['enablereviews'] == 0 ? 'display:none;' : '';

		$yes = $vik->initRadioElement('', '', $params['revservices'] == 1);
		$no  = $vik->initRadioElement('', '', $params['revservices'] == 0);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG75'), 'vapreviewstr', $control);
		echo $vik->radioYesNo('revservices', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- EMPLOYEES REVIEWS - Radio Button -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['revemployees'] == 1);
		$no  = $vik->initRadioElement('', '', $params['revemployees'] == 0);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG76'), 'vapreviewstr', $control);
		echo $vik->radioYesNo('revemployees', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- REVIEW COMMENT REQUIRED - Radio Button -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['revcommentreq'] == 1);
		$no  = $vik->initRadioElement('', '', $params['revcommentreq'] == 0);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG77'), 'vapreviewstr', $control);
		echo $vik->radioYesNo('revcommentreq', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- MIN COMMENT LENGTH - Number -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG78'), 'vapreviewstr', $control); ?>
			<div class="input-append">
				<input type="number" name="revminlength" value="<?php echo $params['revminlength']; ?>" min="0" />
			
				<span class="btn"><?php echo JText::translate('VAPCHARS'); ?></span>
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- MAX COMMENT LENGTH - Number -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG79'), 'vapreviewstr', $control); ?>
			<div class="input-append">
				<input type="number" name="revmaxlength" value="<?php echo $params['revmaxlength']; ?>" min="32" />
			
				<span class="btn"><?php echo JText::translate('VAPCHARS'); ?></span>
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- REVIEWS LIST LIMIT - Number -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG80'), 'vapreviewstr', $control); ?>
			<input type="number" name="revlimlist" value="<?php echo $params['revlimlist']; ?>" min="1" />
		<?php echo $vik->closeControl(); ?>
		
		<!-- AUTO PUBLISHED - Radio Button -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['revautopublished'] == 1);
		$no  = $vik->initRadioElement('', '', $params['revautopublished'] == 0);
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG82'),
			'content' => JText::translate('VAPMANAGECONFIG82_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG82') . $help, 'vapreviewstr', $control);
		echo $vik->radioYesNo('revautopublished', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- FILTER BY LANGUAGE - Radio Button -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['revlangfilter'] == "1");
		$no  = $vik->initRadioElement('', '', $params['revlangfilter'] == "0");
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG81'), 'vapreviewstr', $control);
		echo $vik->radioYesNo('revlangfilter', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- REVIEWS LOAD MODE - Dropdown -->
		
		<?php
		$options = array(
			JHtml::fetch('select.option', 1, 'VAPCONFIGREVLOADMODE1'),
			JHtml::fetch('select.option', 2, 'VAPCONFIGREVLOADMODE2'),
		);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG85'),
			'content' => JText::translate('VAPMANAGECONFIG85_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG85') . $help, 'vapreviewstr', $control); ?>
			<select name="revloadmode" class="medium">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['revloadmode'], true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigShopReviews","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Shop > Reviews > Details fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['basic']))
		{
			echo $forms['basic'];

			// unset details form to avoid displaying it twice
			unset($forms['basic']);
		}
		?>

	</div>

</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigShopReviews","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Shop > Reviews tab.
 *
 * @since 1.7
 */
foreach ($forms as $formTitle => $formHtml)
{
	?>
	<div class="config-fieldset">
		
		<div class="config-fieldset-head">
			<h3><?php echo JText::translate($formTitle); ?></h3>
		</div>

		<div class="config-fieldset-body">
			<?php echo $formHtml; ?>
		</div>
		
	</div>
	<?php
}
?>

<script>

	function reviewsValueChanged(is) {
		if (is) {
			jQuery('.vapreviewstr').show();
		} else {
			jQuery('.vapreviewstr').hide();
		}
	}

</script>
