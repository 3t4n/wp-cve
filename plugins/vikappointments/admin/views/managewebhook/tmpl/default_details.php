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

$webhook = $this->webhook;

$vik = VAPApplication::getInstance();

?>
				
<!-- NAME - Text -->

<?php echo $vik->openControl(JText::translate('VAPMANAGESERVICE2') . '*'); ?>
	<input type="text" name="name" class="input-xxlarge input-large-text required" value="<?php echo $this->escape($webhook->name); ?>" size="40" />
<?php echo $vik->closeControl(); ?>

<!-- ACTION - Select -->

<?php
$found = false;

$options = array();

// add blank option
$options[] = JHtml::fetch('select.option', '', '');

// fetch list of supported web hooks to create a dropdown
foreach (VAPWebHook::getSupportedHooks() as $hook => $name)
{
	$options[] = JHtml::fetch('select.option', $hook, $name);

	$found = $found || $hook == $webhook->hook;
}

// append custom option at the end of the list
$options[] = JHtml::fetch('select.option', 'custom', JText::translate('VAP_CUSTOM_FIELDSET'));

$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPWEBHOOKACTION'),
	'content' => JText::translate('VAPWEBHOOKACTION_DESC'),
));

if ($webhook->hook)
{
	$action = $found && $webhook->hook ? $webhook->hook : 'custom';
}
else
{
	$action = '';
}

echo $vik->openControl(JText::translate('VAPWEBHOOKACTION') . '*' . $help); ?>
	<select name="action" id="vap-hook-sel" class="required">
		<?php echo JHtml::fetch('select.options', $options, 'value', 'text', $action); ?>
	</select>
<?php echo $vik->closeControl(); ?>

<!-- EVENT NAME - Text -->

<?php
$control = array();
$control['style'] = $found || !$webhook->hook ? 'display:none;' : '';

echo $vik->openControl(JText::translate('VAPWEBHOOKEVENTNAME') . '*', 'hook-event-name', $control); ?>
	<input type="text" name="hook" value="<?php echo $this->escape($webhook->hook); ?>" class="required" size="30" />
<?php echo $vik->closeControl(); ?>

<!-- URL - Text -->

<?php
$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPWEBHOOKURL'),
	'content' => JText::translate('VAPWEBHOOKURL_DESC'),
));

echo $vik->openControl(JText::translate('VAPWEBHOOKURL') . '*' . $help); ?>
	<input type="text" name="url" value="<?php echo $this->escape($webhook->url); ?>" class="required" size="30" />
<?php echo $vik->closeControl(); ?>

<!-- SECRET - Text -->

<?php
$help = $vik->createPopover(array(
	'title'   => JText::translate('VAPWEBHOOKSECRET'),
	'content' => JText::translate('VAPWEBHOOKSECRET_DESC'),
));

echo $vik->openControl(JText::translate('VAPWEBHOOKSECRET') . $help); ?>
	<input type="text" name="secret" value="<?php echo $this->escape($webhook->secret); ?>" size="30" />
<?php echo $vik->closeControl(); ?>

<!-- PUBLISHED - Checkbox -->

<?php
$yes = $vik->initRadioElement('', '', $webhook->published == 1);
$no  = $vik->initRadioElement('', '', $webhook->published == 0);

echo $vik->openControl(JText::translate('VAPMANAGESERVICE6'));
echo $vik->radioYesNo('published', $yes, $no, false);
echo $vik->closeControl();
?>

<?php
JText::script('JGLOBAL_SELECT_AN_OPTION');
?>

<script>

	jQuery(function($) {
		const loadHookParams = (hook) => {
			// destroy select2 
			$('.vikpayparamdiv select').select2('destroy');
			// unregister form fields
			validator.unregisterFields('.vikpayparamdiv .required');
			
			$('.vikpayparamdiv').html('');
			$('#vikparamerr').hide();

			UIAjax.do(
				'<?php echo $vik->ajaxUrl('index.php?option=com_vikappointments&task=webhook.paramsajax'); ?>',
				{
					hook: hook,
					id: <?php echo (int) $webhook->id; ?>,
				},
				(html) => {
					$('.vikpayparamdiv').html(html);

					// render select
					$('.vikpayparamdiv select').each(function() {
						$(this).select2({
							// disable search for select with 3 or lower options
							minimumResultsForSearch: $(this).find('option').length > 3 ? 0 : -1,
							allowClear: false,
							width: '90%',
						});
					});

					// register form fields for validation
					validator.registerFields('.vikpayparamdiv .required');

					// init helpers
					$('.vikpayparamdiv .vap-quest-popover').popover({sanitize: false, container: 'body', trigger: 'hover', html: true});

					$('.vikpayparamdiv').trigger('payment.load');
				},
				(err) => {
					$('#vikparamerr').show();
				}
			);
		};

		$('#vap-hook-sel').select2({
			placeholder: Joomla.JText._('JGLOBAL_SELECT_AN_OPTION'),
			allowClear: false,
			width: '90%',
		});

		$('#vap-hook-sel').on('change', function() {
			const hook = $(this).val();

			if (hook == 'custom') {
				$('input[name="hook"]').val('');
				$('.hook-event-name').show();
			} else {
				$('.hook-event-name').hide();
				$('input[name="hook"]').val(hook);
			}

			loadHookParams(hook);
		});

		<?php
		if ($webhook->hook)
		{
			?>
			loadHookParams('<?php echo addslashes($webhook->hook); ?>');
			<?php
		}
		?>
	});

</script>
