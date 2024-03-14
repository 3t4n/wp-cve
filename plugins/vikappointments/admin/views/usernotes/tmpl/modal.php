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
JHtml::fetch('vaphtml.assets.fontawesome');

?>

<div class="vap-unotes">

	<h1><?php echo $this->headingTitle; ?></h1>

	<?php
	if (!count($this->rows))
	{
		echo VAPApplication::getInstance()->alert(JText::translate('JGLOBAL_NO_MATCHING_RESULTS'));
	}
	else
	{
		?>
		<ul class="alternating">
			<?php
			foreach ($this->rows as $note)
			{
				?>
				<li>
					<div class="usernote-title">

						<h4>
							<?php
							echo '#' . $note['id'] . ' ' . $note['title'];

							// load tag details
							$tags = $this->tagModel->readTags($note['tags'], '*');

							foreach ($tags as $tag)
							{
								// render tag
								echo JHtml::fetch('vikappointments.tag', $tag, 'icon', array('style' => 'margin-left: 2px;'));
							}
							?>
						</h4>

						<p class="usernote-date">
							<em>
								<?php
								if (VAPDateHelper::isNull($note['modifiedon']))
								{
									// display creation date
									$date = $note['createdon'];
								}
								else
								{
									// display modify date
									$date = $note['modifiedon'];
								}

								echo JHtml::fetch('date', $date, 'DATE_FORMAT_LC2');
								?>
							</em>
						</p>

					</div>
		
					<?php
					if ($note['content'])
					{
						?>
						<div class="usernote-content">
							<?php echo $note['content']; ?>
						</div>
						<?php
					}

					// decode note attachments
					$files = $note['attachments'] ? (array) json_decode($note['attachments'], true) : array();

					// get file information
					$files = array_filter(array_map(function($file)
					{
						return AppointmentsHelper::getFileProperties(VAPCUSTOMERS_DOCUMENTS . DIRECTORY_SEPARATOR . $file);
					}, $files));

					if ($files)
					{
						?>
						<div class="usernote-files">
							<?php
							foreach ($files as $file)
							{
								$pretty = $file['name'];

								if (strlen($pretty) > 24)
								{
									$pretty = substr($pretty, 0, 11) . '...' . substr($pretty, -10);
								}

								?>
								<a href="<?php echo $file['uri']; ?>" target="_blank" class="usernote-attachment">
									<i class="fas fa-paperclip"></i>
									<?php echo $pretty; ?>
								</a>
								<?php
							}
							?>
						</div>
						<?php
					}
					?>				
				</li>
				<?php
			}
			?>
		</ul>
		<?php
	}
	?>

</div>
