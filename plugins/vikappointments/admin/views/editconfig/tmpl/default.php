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

JHtml::fetch('bootstrap.tooltip', '.hasTooltip');
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.toast', 'bottom-right');

$params = $this->params;

$vik = VAPApplication::getInstance();

/**
 * Prepares CodeMirror editor scripts for being used
 * via Javascript/AJAX.
 *
 * @wponly
 */
$vik->prepareEditor('codemirror');

/**
 * Trigger event to display custom HTML.
 * In case it is needed to include any additional fields,
 * it is possible to create a plugin and attach it to an event
 * called "onDisplayViewConfig". The event method receives the
 * view instance as argument.
 *
 * @since 1.6.6
 */
$forms = $this->onDisplayView();

/**
 * Recover selected tab from the browser cookie.
 *
 * @since 1.7
 */
$this->selectedTab = JFactory::getApplication()->input->cookie->get('vikappointments_config_tab', 1, 'uint');

$tabs = $custTabs = array();

// build default tabs: global, e-mail, currency, shop, listings
$tabs[] = JText::translate('VAPCONFIGTABNAME1');
$tabs[] = JText::translate('VAPCONFIGGLOBTITLE5');
$tabs[] = JText::translate('VAPCONFIGGLOBTITLE6');
$tabs[] = JText::translate('VAPCONFIGGLOBTITLE8');
$tabs[] = JText::translate('VAPCONFIGGLOBTITLE15');

/**
 * Iterate all form items to be displayed as custom tabs within the nav bar.
 *
 * @since 1.6.6
 */
foreach ($forms as $tabName => $tabForms)
{
	// include tab
	$custTabs[] = JText::translate($tabName);
}

// make sure the selected tab is still available
if ($this->selectedTab > count($tabs) + count($custTabs))
{
	// reset to first tab
	$this->selectedTab = 1;
}

/**
 * Render modal before the configuration because the
 * media manager might be used by fields located in
 * different sections.
 *
 * @since 1.7
 */
echo JHtml::fetch('vaphtml.mediamanager.modal');
?>

<div class="configuration-panel">

	<div id="configuration-navbar">
		<ul>
			<?php
			foreach (array_merge($tabs, $custTabs) as $i => $tab)
			{
				$key = $i + 1;
				?>
				<li id="vaptabli<?php echo $key; ?>" class="vaptabli<?php echo ($this->selectedTab == $key ? ' vapconfigtabactive' : ''); ?>" data-id="<?php echo $key; ?>">
					<a href="javascript: void(0);"><?php echo $tab; ?></a>
				</li>
				<?php
			}
			?>
		</ul>
	</div>

	<?php
	// print config search bar
	// VAPLoader::import('libraries.widget.layout');
	// echo UIWidgetLayout::getInstance('searchbar')->display();
	?>

	<div id="configuration-body">

		<form name="adminForm" id="adminForm" action="index.php" method="post" enctype="multipart/form-data">
			
			<?php
			// display default tab panes
			echo $this->loadTemplate('global');
			echo $this->loadTemplate('email');
			echo $this->loadTemplate('currency');
			echo $this->loadTemplate('shop');
			echo $this->loadTemplate('listings');
			
			$i = 0;

			/**
			 * Iterate all form items to be displayed as new panels of custom tabs.
			 *
			 * @since 1.6.6
			 */
			foreach ($forms as $formName => $formHtml)
			{
				// sanitize form name
				$key = count($tabs) + (++$i);

				?>
				<div id="vaptabview<?php echo $key; ?>" class="vaptabview" style="<?php echo ($this->selectedTab != $key ? 'display: none;' : ''); ?>">
					<?php echo $formHtml; ?>
				</div>
				<?php
			}
			?>

			<?php echo JHtml::fetch('form.token'); ?>
			
			<input type="hidden" name="option" value="com_vikappointments" />
			<input type="hidden" name="task" value=""/>
		</form>

	</div>

</div>

<!-- Define role to detect the supported hook -->
<!-- {"rule":"customizer","event":"onDisplayViewConfig","type":"tab"} -->

<?php
// email template modal
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-managetmpl',
	array(
		'title'       => JText::translate('VAPJMODALEMAILTMPL'),
		'closeButton' => true,
		'keyboard'    => false, 
		'bodyHeight'  => 80,
		'url'		  => '',
		'footer'      => '<button type="button" class="btn" data-role="file.savecopy">' . JText::translate('VAPSAVEASCOPY') . '</button>'
					   . '<button type="button" class="btn btn-success" data-role="file.save">' . JText::translate('JAPPLY') . '</button>',
	)
);
?>

<!-- SCRIPT -->

<?php
echo JLayoutHelper::render('configuration.script', array());
?>

<script>

	var SELECTED_MAIL_TMPL_FIELD = null;
	
	jQuery(function($) {
		$('select.short').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 100,
		});

		$('select.small-medium').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 150,
		});

		$('select.medium').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 200,
		});

		$('select.medium-large').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 250,
		});

		$('select.large').select2({
			minimumResultsForSearch: -1,
			allowClear: false,
			width: 300,
		});

		$('button[data-role="file.save"]').on('click', () => {
			// trigger click of save button contained in managefile view
			window.modalFileSaveButton.click();
		});

		$('button[data-role="file.savecopy"]').on('click', () => {
			// trigger click of savecopy button contained in managefile view
			window.modalFileSaveCopyButton.click();
		});

		$('#jmodal-managetmpl').on('hidden', () => {
			// check if the file was saved
			if (window.modalSavedFile) {
				let selector = $('select[name="mailtmpl"],select[name="adminmailtmpl"],select[name="empmailtmpl"],select[name="cancmailtmpl"],select[name="waitlistmailtmpl"],select[name="packmailtmpl"]');

				// insert file in all template dropdowns
				if (addTemplateFileIntoSelect(window.modalSavedFile, selector)) {
					// auto-select new option for the related select
					$(SELECTED_MAIL_TMPL_FIELD).select2('val', window.modalSavedFile.name);
				}
			}
		});

		const addTemplateFileIntoSelect = (file, selector) => {
			if (selector.find('option[value="' + file.name + '"]').length) {
				// file already in list
				return false;
			}

			// prettify name
			let name = file.name.replace(/\.php$/, '');
			name = name.replace(/[_-]+/g, ' ');
			name = name.split(' ').map((s) => {
				return s.charAt(0).toUpperCase() + s.slice(1);
			}).join(' ');

			// insert new option within the select
			$(selector).each(function() {
				$(this).append('<option value="' + file.name + '" data-path="' + file.path + '">' + name + '</option>');
			});

			return true;
		}
	});

	// lock/unlock an input starting from the specified link

	function lockUnlockInput(link) {
		var input = jQuery(link).prev();

		if (input.prop('readonly')) {
			input.prop('readonly', false);

			jQuery(link).find('i').removeClass('fa-lock');
			jQuery(link).find('i').addClass('fa-unlock-alt');
		} else {
			input.prop('readonly', true);

			jQuery(link).find('i').removeClass('fa-unlock-alt');
			jQuery(link).find('i').addClass('fa-lock');
		}
	}

	// MAIL TEMPLATE

	function vapOpenMailTemplateModal(id) {
		// register related dropdown
		SELECTED_MAIL_TMPL_FIELD = jQuery('select[name="' + id + '"]');

		// get file name
		var file = SELECTED_MAIL_TMPL_FIELD.val();
		// build path of selected file
		var path = '<?php echo addslashes(VAPHELPERS . DIRECTORY_SEPARATOR . 'mail_tmpls' . DIRECTORY_SEPARATOR); ?>' + file;

		// create management URL
		let url = 'index.php?option=com_vikappointments&tmpl=component&task=file.edit&cid[]=' + btoa(path);

		vapOpenJModal('managetmpl', url, true);
	}

	// MAIL PREVIEW

	/**
	 * Opens a new browser page to display a preview of the selected mail template.
	 *
	 * @param 	string  id     The unique ID of the e-mail template (select name).
	 * @param 	string 	alias  The template alias (e.g. customer).
	 *
	 * @since 	1.7
	 */
	function goToMailPreview(id, alias) {
		// define base URL
		var url = '<?php echo $vik->addUrlCsrf('index.php?option=com_vikappointments&task=configuration.mailpreview&tmpl=component'); ?>';
		// append template alias
		url += '&alias=' + alias;
		// extract mail template from select
		url += '&file=' + jQuery('select[name="' + id + '"]').val();
		// always use current language
		url += '&langtag=<?php echo JFactory::getLanguage()->getTag(); ?>';

		// open URL in a blank tab of the browser
		window.open(url, '_blank');
	}
	
	// MODAL BOXES

	function vapOpenJModal(id, url, jqmodal) {
		<?php echo $vik->bootOpenModalJS(); ?>
	}
	
</script>
