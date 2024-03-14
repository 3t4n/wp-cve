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

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfigGlobalSystem". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('GlobalSystem');

?>

<!-- SYSTEM -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGGLOBTITLE1'); ?></h3>
	</div>

	<div class="config-fieldset-body">
	
		<!-- COMPANY NAME - Text -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG0')); ?>
			<input type="text" name="agencyname" value="<?php echo $this->escape($params['agencyname']); ?>" size="40" />
		<?php echo $vik->closeControl(); ?>
		
		<!-- LOGO IMAGE - Media Select -->
		
		<?php
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG4'));
		echo JHtml::fetch('vaphtml.mediamanager.field', 'companylogo', $params['companylogo']);
		echo $vik->closeControl();
		?>
		
		<!-- ENABLE MULTILANGUAGE - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['ismultilang'] == 1);
		$no  = $vik->initRadioElement('', '', $params['ismultilang'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG67'),
			'content' => JText::translate('VAPMANAGECONFIG67_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG67') . $help, 'multilingual-setting');
		echo $vik->radioYesNo('ismultilang', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<?php
		/**
		 * Display router setting only in case we are running Joomla!
		 *
		 * @since 1.6.3
		 */
		if (VersionListener::getPlatform() == 'joomla')
		{
			?>
			<!-- ENABLE ROUTER - Checkbox -->
			
			<?php
			$yes = $vik->initRadioElement('', '', $params['router'] == 1);
			$no  = $vik->initRadioElement('', '', $params['router'] == 0);

			$help = $vik->createPopover(array(
				'title'   => JText::translate('VAPMANAGECONFIG70'),
				'content' => JText::translate('VAPMANAGECONFIG70_DESC'),
			));
			
			echo $vik->openControl(JText::translate('VAPMANAGECONFIG70') . $help);
			echo $vik->radioYesNo('router', $yes, $no);
			echo $vik->closeControl();
		}
		?>
		
		<!-- DISPLAY FOOTER - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['showfooter'] == 1);
		$no  = $vik->initRadioElement('', '', $params['showfooter'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG14'),
			'content' => JText::translate('VAPMANAGECONFIG14_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG14') . $help);
		echo $vik->radioYesNo('showfooter', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- GOOGLE API KEY - Number -->
		
		<?php echo $vik->openControl('Google API Key'); ?>
			<div class="input-append">
				<input type="text" name="googleapikey" value="<?php echo $params['googleapikey']; ?>" size="44" <?php echo (strlen($params['googleapikey']) ? 'readonly' : ''); ?> />
			
				<?php
				if (strlen($params['googleapikey']))
				{
					?>
					<button type="button" class="btn" onClick="lockUnlockInput(this);">
						<i class="fas fa-lock"></i>
					</button>
					<?php
				}
				?>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- SITE THEME - Select -->
		
		<?php
		$themes   = glob(VAPBASE . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . '*.css');
		$elements = array();

		foreach ($themes as $theme)
		{
			$theme = basename($theme);
			$theme = substr($theme, 0, strrpos($theme, '.'));

			$elements[] = JHtml::fetch('select.option', $theme, ucwords($theme));
		}

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG117'),
			'content' => JText::translate('VAPMANAGECONFIG117_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG117') . $help); ?>
			<select name="sitetheme" class="small-medium">
				<?php echo JHtml::fetch('select.options', $elements, 'value', 'text', $params['sitetheme']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- DASHBOARD REFRESH TIME - Number -->
		
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG48'),
			'content' => JText::translate('VAPMANAGECONFIG48_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG48') . $help); ?>
			<div class="input-append">
				<input type="number" name="refreshtime" value="<?php echo $params['refreshtime']; ?>" size="40" min="15" max="9999">

				<span class="btn"><?php echo JText::translate('VAPSHORTCUTSEC'); ?></span>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalSystem","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Global > System > System fieldset.
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

		<?php
		if (isset($params['wizardstate']) && (int) $params['wizardstate'])
		{
			?>
			<!-- RESTORE WIZARD - Button -->

			<?php echo $vik->openControl(''); ?>
				<a href="index.php?option=com_vikappointments&task=wizard.restore" target="_blank" class="btn">
					<?php echo JText::translate('VAPWIZARDBTNREST'); ?>
				</a>
			<?php echo $vik->closeControl();
		}
		?>

	</div>

</div>

<!-- DATES & TIMES -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGGLOBTITLE17'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<!-- DATE FORMAT - Select -->
		
		<?php
		$elements = array(
			JHtml::fetch('select.option', 'Y/m/d', 'VAPCONFIGDATEFORMAT1'),
			JHtml::fetch('select.option', 'm/d/Y', 'VAPCONFIGDATEFORMAT2'),
			JHtml::fetch('select.option', 'd/m/Y', 'VAPCONFIGDATEFORMAT3'),
			JHtml::fetch('select.option', 'Y-m-d', 'VAPCONFIGDATEFORMAT4'),
			JHtml::fetch('select.option', 'm-d-Y', 'VAPCONFIGDATEFORMAT5'),
			JHtml::fetch('select.option', 'd-m-Y', 'VAPCONFIGDATEFORMAT6'),
			JHtml::fetch('select.option', 'Y.m.d', 'VAPCONFIGDATEFORMAT7'),
			JHtml::fetch('select.option', 'm.d.Y', 'VAPCONFIGDATEFORMAT8'),
			JHtml::fetch('select.option', 'd.m.Y', 'VAPCONFIGDATEFORMAT9'),
		);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG5')); ?>
			<select name="dateformat" class="medium-large">
				<?php echo JHtml::fetch('select.options', $elements, 'value', 'text', $params['dateformat'], true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- TIME FORMAT - Select -->
		
		<?php
		$elements = array(
			JHtml::fetch('select.option', 'h:i A', 'VAPCONFIGTIMEFORMAT1'),
			JHtml::fetch('select.option',   'H:i', 'VAPCONFIGTIMEFORMAT2'),
			JHtml::fetch('select.option', 'g:i A', 'VAPCONFIGTIMEFORMAT3'),
			JHtml::fetch('select.option',   'G:i', 'VAPCONFIGTIMEFORMAT4'),
		);
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG6')); ?>
			<select name="timeformat" class="medium-large">
				<?php echo JHtml::fetch('select.options', $elements, 'value', 'text', $params['timeformat'], true); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- FORMAT DURATION - Checkboxs -->
		
		<?php
		$yes = $vik->initRadioElement('fd1', '', $params['formatduration'] == 1);
		$no  = $vik->initRadioElement('fd0', '', $params['formatduration'] == 0);

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG55'),
			'content' => JText::translate('VAPMANAGECONFIG55_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG55') . $help);
		echo $vik->radioYesNo('formatduration', $yes, $no);
		echo $vik->closeControl();
		?>
		
		<!-- MINUTES INTERVALS - Select -->
		
		<?php 
		$options = array();

		foreach (array(5, 10, 15, 20, 30, 60) as $min)
		{
			$options[] = JHtml::fetch('select.option', $min, $min);
		}

		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG10'),
			'content' => JText::translate('VAPMANAGECONFIG10_DESC'),
		));
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG10') . $help); ?>
			<select name="minuteintervals" class="short">
				<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $params['minuteintervals']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- OPENING HOUR - Select -->
		
		<?php 
		$times = JHtml::fetch('vikappointments.times', array('step' => 5));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG15')); ?>
			<select name="openingtime" class="small-medium">
				<?php echo JHtml::fetch('select.options', $times, 'value', 'text', $params['openingtime']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>
		
		<!-- CLOSING HOUR - Select -->
		
		<?php echo $vik->openControl(JText::translate('VAPMANAGECONFIG16')); ?>
			<select name="closingtime" class="small-medium">
				<?php echo JHtml::fetch('select.options', $times, 'value', 'text', $params['closingtime']); ?>
			</select>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalSystem","key":"datetime","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Global > System > Date & Time fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['datetime']))
		{
			echo $forms['datetime'];

			// unset details form to avoid displaying it twice
			unset($forms['datetime']);
		}
		?>

	</div>

</div>

<!-- BOOKING -->

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPBOOKING'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<!-- MINUTES RESTRICTIONS - Number -->
		
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG22'),
			'content' => JText::translate('VAPMANAGECONFIG22_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG22') . $help); ?>
			<div class="input-append">
				<input type="number" name="minrestr" value="<?php echo $params['minrestr']; ?>" size="10" min="0" max="9999"/>

				<span class="btn"><?php echo JText::translate('VAPSHORTCUTMINUTE'); ?></span>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- MIN DATE - Number -->
		
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG122'),
			'content' => JText::translate('VAPMANAGECONFIG122_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG122') . $help); ?>
			<div class="input-append">
				<input type="number" name="mindate" value="<?php echo $params['mindate']; ?>" size="10" min="0" max="9999" />

				<span class="btn"><?php echo JText::translate('VAPDAYSLABEL'); ?></span>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- MAX DATE - Number -->
		
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG123'),
			'content' => JText::translate('VAPMANAGECONFIG123_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG123') . $help); ?>
			<div class="input-append">
				<input type="number" name="maxdate" value="<?php echo $params['maxdate']; ?>" size="10" min="0" max="9999" />

				<span class="btn"><?php echo JText::translate('VAPDAYSLABEL'); ?></span>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- KEEP APPOINTMENTS LOCKED - Number -->
		
		<?php
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG20'),
			'content' => JText::translate('VAPMANAGECONFIG20_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG20') . $help); ?>
			<div class="input-append">
				<input type="number" name="keepapplock" value="<?php echo $params['keepapplock']; ?>" size="40" min="5" max="9999">

				<span class="btn"><?php echo JText::translate('VAPSHORTCUTMINUTE'); ?></span>
			</div>
		<?php echo $vik->closeControl(); ?>
		
		<!-- SHOW PHONE PREFIX - Checkbox -->
		
		<?php 
		$yes = $vik->initRadioElement('', '', $params['showphprefix'] == "1");
		$no  = $vik->initRadioElement('', '' , $params['showphprefix'] == "0");
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG58'),
			'content' => JText::translate('VAPMANAGECONFIG58_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG58') . $help);
		echo $vik->radioYesNo('showphprefix', $yes, $no);		
		echo $vik->closeControl();
		?>

		<!-- CONVERSION TRACK - Checkbox -->
		
		<?php
		$yes = $vik->initRadioElement('', '', $params['conversion_track'] == 1, 'onclick="jQuery(\'.manage-conversion-track a\').show();"');
		$no  = $vik->initRadioElement('', '' ,$params['conversion_track'] == 0, 'onclick="jQuery(\'.manage-conversion-track a\').hide();"');
		
		echo $vik->openControl(JText::translate('VAPMANAGECONFIG111'), 'conversions-setting');
		echo $vik->radioYesNo('conversion_track', $yes, $no);
		?>
			<span style="display: inline-block;vertical-align: top;margin-left: 20px;" class="manage-conversion-track">
				<a href="index.php?option=com_vikappointments&amp;view=conversions" 
					style="<?php echo $params['conversion_track'] == '0' ? "display:none;" : ""; ?>" 
					class="btn" target="_blank"><?php echo JText::translate('VAPMANAGECONFIGEMP9'); ?></a>
			</span>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalSystem","key":"booking","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Global > System > Booking fieldset.
		 *
		 * @since 1.7
		 */
		if (isset($forms['booking']))
		{
			echo $forms['booking'];

			// unset details form to avoid displaying it twice
			unset($forms['booking']);
		}
		?>

	</div>

</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigGlobalSystem","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Global > System tab.
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
