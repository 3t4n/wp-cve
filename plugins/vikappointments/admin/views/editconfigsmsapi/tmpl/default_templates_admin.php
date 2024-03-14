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
 * called "onDisplayViewConfigsmsapiTemplatesAdmin". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('TemplatesAdmin');

?>

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGSMSTITLE3'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<!-- TOOLBAR -->

		<div style="display: inline-block; width: 100%;">
			<div class="btn-group pull-left">
				<button type="button" class="btn smsadmin-put-tag">{total_cost}</button>
				<button type="button" class="btn smsadmin-put-tag">{checkin}</button>
				<button type="button" class="btn smsadmin-put-tag">{service}</button>
				<button type="button" class="btn smsadmin-put-tag">{employee}</button>
				<button type="button" class="btn smsadmin-put-tag">{company}</button>
				<button type="button" class="btn smsadmin-put-tag">{created_on}</button>

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the Templates > Administrator > Template > Toolbar APIs fieldset.
				 *
				 * @since 1.7
				 */
				if (isset($forms['toolbar']))
				{
					echo $forms['toolbar'];

					// unset details form to avoid displaying it twice
					unset($forms['toolbar']);
				}
				?>
			</div>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigsmsapiTemplatesAdmin","key":"toolbar","type":"field"} -->

		<!-- CONTENTS -->

		<div>
			<?php 
			$sms_tmpl_cust = array($params['smstmpladmin'], $params['smstmpladminmulti']);
			$placeholders  = array(JText::translate('VAPSMSMESSAGEADMIN'), JText::translate('VAPSMSMESSAGEADMINMULTI'));

			for ($i = 0; $i < 2; $i++)
			{ 
				$content = '';

				if (!empty($sms_tmpl_cust[$i]))
				{
					$content = $sms_tmpl_cust[$i];
				}
				?>
				<textarea
					class="vap-smsadmincont"
					id="vapsmsadmincont-<?php echo ($i + 1); ?>"
					placeholder="<?php echo $placeholders[$i]; ?>"
					style="width: calc(100% - 15px); height: 200px; resize: vertical;<?php echo ($i != 0 ? 'display:none;' : ''); ?>"
					name="smstmpladmin[<?php echo $i; ?>]"
				><?php echo $content; ?></textarea>
				<?php
			}
			?>
		</div>
		
		<!-- FILTERS -->
		
		<div style="display: inline-block; width: 100%;">
			<div class="btn-group pull-left">
				<button type="button" class="btn active vap-smsadmin-type" data-type="0"><?php echo JText::translate('VAPSMSCONTSWITCHSINGLE'); ?></button>
				<button type="button" class="btn vap-smsadmin-type" data-type="1"><?php echo JText::translate('VAPSMSCONTSWITCHMULTI'); ?></button>
			</div>
		</div>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigsmsapiTemplatesAdmin","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Settings > Settings > SMS APIs fieldset.
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
<!-- {"rule":"customizer","event":"onDisplayViewConfigsmsapiTemplatesAdmin","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the SMS Templates > Admin tab.
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

	jQuery(function($) {
		// returns the active textarea
		const getActiveArea = () => {
			var area = null;

			// find active area
			$('.vap-smsadmincont').each(function() {
				if ($(this).is(':visible')) {
					area = $(this);
				}
			});

			return area;
		};

		// put clicked tag within the active textarea
		$('.smsadmin-put-tag').on('click', function() {
			const area = getActiveArea();
			
			if (!area) {
				return false;
			}
			
			let cont  = $(this).text().trim();
			let start = area.get(0).selectionStart;
			let end   = area.get(0).selectionEnd;

			area.val(area.val().substring(0, start) + cont + area.val().substring(end));
			area.get(0).selectionStart = area.get(0).selectionEnd = start + cont.length;
			area.focus();
		});

		// switch content according to selected type
		$('.vap-smsadmin-type').on('click', function() {
			if ($(this).hasClass('active')) {
				return false;
			}

			$('.vap-smsadmin-type').removeClass('active');
			$(this).addClass('active');
			
			const area = getActiveArea();
			
			if (area == null) {
				return;
			}
			
			var id = area.attr('id').split('-');
			area.hide();

			const section = parseInt($(this).data('type'));

			$('#' + id[0] + '-' + (section + 1)).show();
		});
	});

</script>
