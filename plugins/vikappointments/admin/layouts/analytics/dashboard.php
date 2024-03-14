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

/**
 * Layout variables
 * -----------------
 * @var  array  $dashboard  An array 
 */
extract($displayData);

$vik = VAPApplication::getInstance();

?>

<!-- WIDGETS -->

<div class="row-fluid" id="statistics-wrapper" style="margin-top: 20px;">

	<?php
	foreach ($dashboard as $position => $widgets)
	{
		?>
		<div class="dashboard-widgets-container" data-position="<?php echo $position; ?>">

			<?php
			foreach ($widgets as $i => $widget)
			{
				$id = $widget->getID();

				// widen or shorten the widget
				switch ($widget->getSize())
				{
					// force EXTRA SMALL width : (100% / N) - (100% / (N * 2))
					case 'extra-small':
						$width = 'width: calc((100% / ' . count($widgets) . ') - (100% / ' . (count($widgets) * 2) . '));';
						break;

					// force SMALL width : (100% / N) - (100% / (N * 4))
					case 'small':
						$width = 'width: calc((100% / ' . count($widgets) . ') - (100% / ' . (count($widgets) * 4) . '));';
						break;

					// force NORMAL width : (100% / N)
					case 'normal':
						$width = 'width: calc(100% / ' . count($widgets) . ');';
						break;

					// force LARGE width : (100% / N) + (100% / (N * 4))
					case 'large':
						$width = 'width: calc((100% / ' . count($widgets) . ') + (100% / ' . (count($widgets) * 4) . '));';
						break;

					// force EXTRA LARGE width : (100% / N) + (100% / (N * 2))
					case 'extra-large':
						$width = 'width: calc((100% / ' . count($widgets) . ') + (100% / ' . (count($widgets) * 2) . '));';
						break;

					// fallback to flex basis to take the remaining space
					default:
						$width = 'flex: 1;';
				}
				?>
				<div
					class="dashboard-widget"
					id="widget-<?php echo $id; ?>"
					data-widget="<?php echo $widget->getName(); ?>"
					style="<?php echo $width; ?>"
				>

					<div class="widget-wrapper">
						<div class="widget-head">
							<h3><?php echo $widget->getTitle(); ?></h3>

							<div class="widget-actions">
								<?php
								// check whether the widget supports export functions
								$exportable = $widget->isExportable();

								if ($exportable)
								{
									foreach ((array) $exportable as $func)
									{
										// build export URL
										$url = 'index.php?option=com_vikappointments&task=analytics.export&id=' . $id . '&widget=' . $widget->getName();

										if (!is_bool($func))
										{
											// append export rule to URL
											$url .= '&rule=' . $func;
										}

										// switch export function to find the best icon
										switch ($func)
										{
											case 'html':
												$icon = 'fas fa-file-code';
												break;

											case 'print':
												$icon = 'fas fa-print';
												break;

											case 'raw':
												$icon = 'fas fa-file-alt';
												break;

											default:
												$icon = 'fas fa-download';
										}
										?>
										<a href="<?php echo $vik->addUrlCSRF($url, $xhtml = true); ?>" target="_blank" class="widget-export-btn no-underline">
											<i class="<?php echo $this->escape($icon); ?>"></i>
										</a>
										<?php
									}
								}
								?>

								<a href="javascript:void(0)" data-id="<?php echo $id; ?>" class="widget-config-btn no-underline">
									<i class="fas fa-ellipsis-h"></i>
								</a>
							</div>
						</div>

						<div class="widget-body">
							<?php echo $widget->display(); ?>
						</div>

						<div class="widget-error-box" style="display: none;">
							<?php echo $vik->alert(JText::translate('VAP_AJAX_GENERIC_ERROR'), 'error'); ?>
						</div>
					</div>

				</div>
				<?php
			}
			?>

		</div>
		<?php
	}
	?>

</div>
