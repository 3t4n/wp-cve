<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.wpdash
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

/**
 * Layout variables
 * -----------------
 * @var  JRegistry         $config  The configuration registry
 * @var  JDashboardWidget  $widget  The widget instance.
 * @var  array             $feeds   A list of feeds.
 */
extract($displayData);

$document = JFactory::getDocument();

$internalFilesOptions = array('version' => VIKAPPOINTMENTS_SOFTWARE_VERSION);

$document->addScript(VIKAPPOINTMENTS_CORE_MEDIA_URI . 'js/system.js', $internalFilesOptions, array('id' => 'vap-sys-script'));
$document->addScript(VIKAPPOINTMENTS_CORE_MEDIA_URI . 'js/admin.js', $internalFilesOptions, array('id' => 'vap-admin-script'));
$document->addScript(VIKAPPOINTMENTS_CORE_MEDIA_URI . 'js/bootstrap.min.js', $internalFilesOptions, array('id' => 'bootstrap-script'));
$document->addStyleSheet(VIKAPPOINTMENTS_CORE_MEDIA_URI . 'css/system.css', $internalFilesOptions, array('id' => 'vap-sys-style'));
$document->addStyleSheet(VIKAPPOINTMENTS_CORE_MEDIA_URI . 'css/bootstrap.lite.css', $internalFilesOptions, array('id' => 'bootstrap-lite-style'));

// prepare modal to display opt-in
echo JHtml::fetch(
	'bootstrap.renderModal',
	'jmodal-vap-rss-feed',
	array(
		'title'       => '',
		'closeButton' => true,
		'keyboard'    => true,
		'top'         => true,
		'width'       => 70,
		'height'      => 80,
	),
	'{placeholder}'
);

?>

<style>
	#vik_appointments_rss .inside {
		padding: 0 !important;
		margin: 0 !important;
	}
	#vik_appointments_rss .modal-header h3 {
		margin: 0;
		line-height: 50px;
		font-weight: normal;
		font-size: 22px;
	}
	#vik_appointments_rss .modal-header h3 .dashicons-before:before {
		line-height: 50px;
	}
	#vik_appointments_rss img {
		max-width: 100%;
	}

	.vap-rss-widget ul {
		margin: 0;
		padding: 0;
	}
	.vap-rss-widget ul li {
		list-style: none;
		display: flex;
		align-items: center;
		justify-content: space-between;
		flex-wrap: wrap;
		margin: 0;
		padding: 8px 12px;
		border-bottom: 1px solid #eee;
	}
	.vap-rss-widget ul li:last-child {
		border-bottom: 0;
	}
	.vap-rss-widget ul li:nth-child(odd) {
		background: #fafafa;
	}

	.vap-rss-widget ul li .feed-icon {
		width: 32px;
	}
	.vap-rss-widget ul li .feed-details {
		flex: 1;
	}
	.vap-rss-widget ul li .feed-date-time {
		text-align: right;
	}

	.vap-rss-widget .rss-missing-optin {
		padding: 10px 10px 0 10px;
	}
</style>

<div class="vap-rss-widget">

	<?php
	// make sure the RSS service is enabled
	if (!$config->get('optin'))
	{
		// service not enabled
		?>
		<div class="rss-missing-optin">
			<div class="notice notice-error inline">
				<p>
					<?php _e('<b>You haven\'t opted in the RSS service!</b><br />Click the following button to start receiving RSS feeds.', 'vikappointments'); ?>
				</p>

				<p>
					<a href="admin.php?page=vikappointments&view=editconfig#rss" class="button button-primary">
						<?php _e('Activate RSS Feeds', 'vikappointments'); ?>
					</a>
				</p>
			</div>
		</div>
		<?php
	}
	else if (!$feeds)
	{
		// no feeds to display
		?>
		<div class="rss-missing-optin">
			<div class="notice notice-warning inline">
				<p>
					<?php _e('No feeds to display', 'vikappointments'); ?>
				</p>
			</div>
		</div>
		<?php
	}
	else
	{
		?>
		<ul>
			<?php
			foreach ($feeds as $i => $feed)
			{
				switch (strtolower($feed->category))
				{
					case 'promo':
						$icon = 'star-filled';
						break;

					case 'tips':
						$icon = 'welcome-learn-more';
						break;

					case 'news':
						$icon = 'megaphone';
						break;

					default:
						$icon = 'rss';
				}

				?>
				<li data-id="<?php echo $feed->id; ?>">
					<div class="feed-icon">
						<span class="dashicons-before dashicons-<?php echo $icon; ?>"></span>
					</div>

					<div class="feed-details" data-title="<?php echo $this->escape($feed->title); ?>" data-category="<?php echo $this->escape($feed->category); ?>">
						<div class="feed-title">
							<a href="javascript: void(0);">
								<b><?php echo $feed->title; ?></b>
							</a>
						</div>
						<div class="feed-category"><?php echo $feed->category; ?></div>
					</div>

					<div class="feed-date-time">
						<div class="feed-date">
							<?php echo JHtml::fetch('date', $feed->date, JText::translate('DATE_FORMAT_LC3')); ?>
						</div>
						<div class="feed-time">
							<?php echo JHtml::fetch('date', $feed->date, get_option('time_format')); ?>
						</div>
					</div>

					<div style="display: none;" class="rss-content">
						<?php echo $feed->content; ?>
					</div>
				</li>
				<?php
			}
			?>
		</ul>
		<?php
	}
	?>

</div>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('#vik_appointments_rss .feed-details a').on('click', function() {
				// get parent <li>
				var li = $(this).closest('li');
				// find feed details
				var details = li.find('.feed-details');
				// find feed content
				var content = li.find('.rss-content').html();

				// get modal
				var modal = $('#jmodal-vap-rss-feed');

				// register feed ID
				modal.attr('data-feed-id', li.data('id'));

				// update modal title
				modal.find('.modal-header h3').html(
					li.find('.feed-icon').html() + ' ' +
					details.data('category') + ' - ' +
					details.data('title')
				);

				// update modal content
				modal.find('.modal-body').html(content);

				// manually adjust the links that specifiy a data attribute with a plain href
				modal.find('.modal-body a[data-href-plain]').each(function() {
					$(this).attr('href', $(this).data('href-plain'));
					$(this).attr('data-href-plain', '');
				});

				// display modal
				wpOpenJModal('vap-rss-feed');
			});
		});
	})(jQuery);

</script>
