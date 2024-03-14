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

AppointmentsHelper::printMenu();

?>
<div class="viwppro-cnt viwpro-procnt">
	<div class="viwpro-procnt-inner">

		<div class="vikwppro-header">
			<div class="vikwppro-header-inner">
				<div class="vikwppro-header-text">
					<h2><?php _e('Thanks for using the Pro version', 'vikappointments'); ?></h2>
					<h3><?php _e('The true VikAppointments is Pro. Make sure to keep your license key active to be able to install future updates.', 'vikappointments'); ?></h3>
				</div>
				<div class="vikwppro-header-img"></div>
			</div>
		</div>
		
		<div class="vikwppro-licensecnt">
			<div class="col col-md-6 col-sm-12 vikwppro-licensetext">
				<div>
					<h3>
						<?php
						echo sprintf(
							__('License key valid until %s', 'vikappointments'),
							JHtml::fetch('date', $this->licenseDate, VAPFactory::getConfig()->get('dateformat'))
						);
						?>
					</h3>
					<h4><?php _e('Get or renew your License Key from VikWP.com', 'vikappointments'); ?></h4>
					<a href="https://vikwp.com/" class="vikwp-btn-link" target="_blank"><i class="fas fa-rocket"></i> <?php _e('Get or renew your license', 'vikappointments'); ?></a>
				</div>
				<span class="icon-background"><i class="fas fa-rocket"></i></span>
			</div>
			
			<div class="col col-md-6 col-sm-12 vikwppro-licenseform">
				<form>				
					<div class="vikwppro-licenseform-inner">
						<h4><?php _e('Already have your key? Enter it here', 'vikappointments'); ?></h4>
						<div>
							<span class="vikwppro-inputspan"><i class="fas fa-key"></i>
								<input type="text" name="key" id="lickey" value="<?php echo htmlspecialchars($this->licenseKey); ?>" class="license-input" autocomplete="off" />
							</span>
							<button type="button" class="btn btn-primary" id="vikwpvalidate" onclick="vikWpValidateLicenseKey();"><?php _e('Validate and Update', 'vikappointments'); ?></button>
						</div>
					</div>
				</form>
			</div>
		</div>
		
	</div>
</div>

<?php
// load common scripts
echo $this->loadTemplate('js');
