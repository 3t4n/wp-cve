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
 * called "onDisplayViewConfigsmsapiTemplatesCustomer". The event method
 * receives the view instance as argument.
 *
 * @since 1.7
 */
$forms = $this->onDisplayView('TemplatesCustomer');

?>

<div class="config-fieldset">

	<div class="config-fieldset-head">
		<h3><?php echo JText::translate('VAPCONFIGSMSTITLE2'); ?></h3>
	</div>

	<div class="config-fieldset-body">

		<!-- TOOLBAR -->

		<div style="display: inline-block; width: 100%;">
			<div class="btn-group pull-left">
				<button type="button" class="btn sms-put-tag">{total_cost}</button>
				<button type="button" class="btn sms-put-tag">{checkin}</button>
				<button type="button" class="btn sms-put-tag">{service}</button>
				<button type="button" class="btn sms-put-tag">{employee}</button>
				<button type="button" class="btn sms-put-tag">{company}</button>
				<button type="button" class="btn sms-put-tag">{created_on}</button>

				<?php	
				/**
				 * Look for any additional fields to be pushed within
				 * the Templates > Customer > Template > Toolbar APIs fieldset.
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
		<!-- {"rule":"customizer","event":"onDisplayViewConfigsmsapiTemplatesCustomer","key":"toolbar","type":"field"} -->

		<!-- CONTENTS -->

		<div>
			<?php 
			$sms_tmpl_cust = array(json_decode($params['smstmplcust'], true), json_decode($params['smstmplcustmulti'], true));
			$placeholders  = array(array(), array());

			// keep current language
			$default = JFactory::getLanguage()->getTag();

			foreach ($languages as $k => $lang)
			{
				$translator = JFactory::getLanguage();
				$translator->load('com_vikappointments', JPATH_ADMINISTRATOR, $lang, true);

				$placeholders[0][$lang] = $translator->_('VAPSMSMESSAGECUSTOMER');
				$placeholders[1][$lang] = $translator->_('VAPSMSMESSAGECUSTOMERMULTI');
				
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
						class="vap-smscont"
						id="vapsmscont<?php echo $_langtag; ?>-<?php echo ($i + 1); ?>"
						placeholder="<?php echo $placeholders[$i][$lang]; ?>"
						style="width: calc(100% - 15px); height: 200px; resize: vertical;<?php echo ($k != 0 || $i == 1 ? 'display:none;' : ''); ?>"
						name="smstmplcust[<?php echo $i; ?>][]"
					><?php echo $content; ?></textarea>
					<?php
				}
			}

			// restore default language
			if (end($languages) != $default)
			{
				JFactory::getLanguage()->load('com_vikappointments', JPATH_ADMINISTRATOR, $default, true);
			}
			?>
		</div>
		
		<!-- LANGUAGES -->
		
		<div style="display: inline-block; width: 100%;">
			<div class="btn-group pull-left">
				<button type="button" class="btn active vap-sms-type" data-type="0"><?php echo JText::translate('VAPSMSCONTSWITCHSINGLE'); ?></button>
				<button type="button" class="btn vap-sms-type" data-type="1"><?php echo JText::translate('VAPSMSCONTSWITCHMULTI'); ?></button>
			</div>

			<div class="btn-group pull-right">
				<?php
				foreach ($languages as $k => $lang)
				{
					$_langtag = preg_replace("/[\-_]+/", '', $lang);
					?>
					<button type="button" class="vap-sms-langtag btn <?php echo ($k == 0 ? 'active' : ''); ?>" data-lang="<?php echo $_langtag; ?>">
						<?php echo JHtml::fetch('vaphtml.site.flag', $lang); ?>
					</button>
					<?php
				}
				?>
			</div>
		</div> 

		<!-- Define role to detect the supported hook -->
		<!-- {"rule":"customizer","event":"onDisplayViewConfigsmsapiTemplatesCustomer","key":"basic","type":"field"} -->

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
<!-- {"rule":"customizer","event":"onDisplayViewConfigsmsapiTemplatesCustomer","type":"fieldset"} -->

<?php
/**
 * Iterate remaining forms to be displayed as new fieldsets
 * within the SMS Templates > Customer tab.
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
			$('.vap-smscont').each(function() {
				if ($(this).is(':visible')) {
					area = $(this);
				}
			});

			return area;
		};

		// put clicked tag within the active textarea
		$('.sms-put-tag').on('click', function() {
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
		$('.vap-sms-type').on('click', function() {
			if ($(this).hasClass('active')) {
				return false;
			}

			$('.vap-sms-type').removeClass('active');
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
		$('.vap-sms-langtag').on('click', function() {
			if ($(this).hasClass('active')) {
				return false;
			}

			$('.vap-sms-langtag').removeClass('active');
			$(this).addClass('active');
			
			const area = getActiveArea();
			
			if (area == null) {
				return;
			}

			const langtag = $(this).data('lang');
			
			$('.vap-smscont').hide();
			$('#vapsmscont' + langtag + '-' + area.attr('id').split('-')[1]).show();
		});
	});

</script>
