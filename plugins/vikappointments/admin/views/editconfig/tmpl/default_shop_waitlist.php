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
 * called "onDisplayViewConfigShopWaitlist". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('ShopWaitlist');

?>

<div class="config-fieldset">

	<div class="config-fieldset-body">
		
		<!-- ENABLE WAITING LIST - Checkbox -->

		<?php
		$yes = $vik->initRadioElement('', '', $params['enablewaitlist'] == 1, 'onClick="waitlistValueChanged(1);"');
		$no  = $vik->initRadioElement('', '', $params['enablewaitlist'] == 0, 'onClick="waitlistValueChanged(0);"');
		
		$help = $vik->createPopover(array(
			'title'   => JText::translate('VAPMANAGECONFIG100'),
			'content' => JText::translate('VAPMANAGECONFIG100_DESC'),
		));

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG100') . $help);
		echo $vik->radioYesNo('enablewaitlist', $yes, $no);
		echo $vik->closeControl();
		?>

		<!-- WAITING LIST EMAIL TEMPLATE -->

		<?php
		$control = array();
		$control['style'] = $params['enablewaitlist'] == 0 ? 'display:none;' : '';

		$templates = JHtml::fetch('vaphtml.admin.mailtemplates');

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG102'), 'vapwaitlistrow', $control); ?>
			<div class="inline-fields">
				<select name="waitlistmailtmpl" class="medium-large" id="vap-wlemailtmpl-sel">
					<?php echo JHtml::fetch('select.options', $templates, 'value', 'text', $params['waitlistmailtmpl']); ?>
				</select>

				<div class="btn-group flex-auto">
					<button type="button" class="btn" onclick="vapOpenMailTemplateModal('waitlistmailtmpl', null, true); return false;">
						<i class="fas fa-pen"></i>
					</button>

					<button type="button" class="btn" onclick="goToMailPreview('waitlistmailtmpl', 'waitlist');">
						<i class="fas fa-eye"></i>
					</button>
				</div>
			</div>
		<?php echo $vik->closeControl(); ?>

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigShopWaitlist","key":"basic","type":"field"} -->

		<?php	
		/**
		 * Look for any additional fields to be pushed within
		 * the Shop > Waiting List > Details fieldset.
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

		<!-- SMS CONTENT - Form -->
		
		<?php
		$languages = VikAppointments::getKnownLanguages();

		echo $vik->openControl(JText::translate('VAPMANAGECONFIG101'), 'vapwaitlistrow', $control); ?>

			<!-- SUPPORTED TAGS -->

			<div style="display: inline-block; width: 100%;">
				<div class="btn-group pull-left">
					<button type="button" class="btn wl-put-tag">{checkin_day}</button>
					<button type="button" class="btn wl-put-tag">{checkin_time}</button>
					<button type="button" class="btn wl-put-tag">{service}</button>
					<button type="button" class="btn wl-put-tag">{company}</button>
					<button type="button" class="btn wl-put-tag">{details_url}</button>
				</div>
			</div>

			<!-- CONTENTS -->
			
			<div>
				<?php
				$sms_tmpl_cust = json_decode($params['waitlistsmscont'], true);

				foreach ($languages as $k => $lang)
				{ 
					for ($i = 0; $i < 2; $i++)
					{ 
						$content = '';

						if (!empty($sms_tmpl_cust[$i][$lang]))
						{
							$content = $sms_tmpl_cust[$i][$lang];
						}

						$_langtag = preg_replace("/[\-_]+/", '', $lang);
						?>
						<textarea
							name="waitlistsmscont[<?php echo $i; ?>][]"
							class="vap-smswlcont"
							id="vapsmswlcont<?php echo $_langtag; ?>-<?php echo ($i + 1); ?>" 
							style="width: 100%; height: 200px; resize: vertical;<?php echo ($k != 0 || $i == 1 ? 'display:none;' : ''); ?>"
						><?php echo $content; ?></textarea>
						<?php
					}
				}
				?>
			</div>  

			<!-- SUPPORTED LANGUAGES -->

			<div style="display: inline-block; width: 100%;">
				<div class="btn-group pull-left">
					<button type="button" class="btn active vap-smswl-type" data-type="0"><?php echo JText::translate('VAPSMSCONTSWITCHSINGLE'); ?></button>
					<button type="button" class="btn vap-smswl-type" data-type="1"><?php echo JText::translate('VAPSMSCONTSWITCHMULTI'); ?></button>
				</div>

				<div class="btn-group pull-right">
					<?php
					foreach ($languages as $k => $lang)
					{
						$_langtag = preg_replace("/[\-_]+/", '', $lang);
						?>
						<button type="button" class="vap-smswl-langtag btn <?php echo ($k == 0 ? 'active' : ''); ?>" data-lang="<?php echo $_langtag; ?>">
							<?php echo JHtml::fetch('vaphtml.site.flag', $lang); ?>
						</button>
						<?php
					}
					?>
				</div>
			</div> 
		<?php echo $vik->closeControl(); ?>

	</div>
	
</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfigShopWaitlist","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the Shop > Waiting List tab.
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
			$('.vap-smswlcont').each(function() {
				if ($(this).is(':visible')) {
					area = $(this);
				}
			});

			return area;
		};

		// put clicked tag within the active textarea
		$('.wl-put-tag').on('click', function() {
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
		$('.vap-smswl-type').on('click', function() {
			if ($(this).hasClass('active')) {
				return false;
			}

			$('.vap-smswl-type').removeClass('active');
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

		// switch content according to the selected language
		$('.vap-smswl-langtag').on('click', function() {
			if ($(this).hasClass('active')) {
				return false;
			}

			$('.vap-smswl-langtag').removeClass('active');
			$(this).addClass('active');
			
			const area = getActiveArea();
			
			if (area == null) {
				return;
			}

			const langtag = $(this).data('lang');
			
			$('.vap-smswlcont').hide();
			$('#vapsmswlcont' + langtag + '-' + area.attr('id').split('-')[1]).show();
		});
	});

	function waitlistValueChanged(is) {
		if (is) {
			jQuery('.vapwaitlistrow').show();
		} else {
			jQuery('.vapwaitlistrow').hide();
		}
	}

</script>
