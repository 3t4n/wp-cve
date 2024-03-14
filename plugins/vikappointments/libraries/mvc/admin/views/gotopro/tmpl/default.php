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
<div class="viwppro-cnt">

	<div class="vikwppro-header">
		<div class="vikwppro-header-inner">
			<div class="vikwppro-header-text">
				<h2><?php _e('Why Upgrade to Pro?', 'vikappointments'); ?></h2>
				<h3><?php _e('The true VikAppointments is Pro. A professional tool for managing any kind of appointments.', 'vikappointments'); ?></h3>
				<a href="javascript: void(0);" id="vikwpgotoget" class="vikwp-btn-link"><i class="fas fa-rocket"></i> <?php _e('Get your License Key and Upgrade to PRO', 'vikappointments'); ?></a>
			</div>
			<div class="vikwppro-header-img">
				<img src="<?php echo VIKAPPOINTMENTS_CORE_MEDIA_URI; ?>images/dashboard-en-us.jpg" alt="VikAppointments Pro" />
			</div>
		</div>
	</div>

	<div class="viwppro-feats-cnt">
		<div class="viwppro-feats-row vikwppro-even">
			<div class="viwppro-feats-img">
				<img src="<?php echo VIKAPPOINTMENTS_CORE_MEDIA_URI; ?>images/user-notes-management-en-us.jpg" alt="Users Notes Management" />
			</div>
			<div class="viwppro-feats-text">
				<h4><?php _e('Users Notes Management'); ?></h4>
				<p><?php _e('Collect notes and documents for your customer. The system lets you upload any kind of documents, such as images, videos, PDF, text files and so on. The notes also support a visibility parameter, which let you choose whether the created record and files can be seen by the customer or not.', 'vikappointments'); ?></p>
			</div>
		</div>

		<div class="viwppro-feats-row vikwppro-odd">
			<div class="viwppro-feats-text">
				<h4><?php _e('Create and Modify Bookings via back-end', 'vikappointments'); ?></h4>
				<p><?php _e('The Reservations page will let you create and modify existing appointments, maybe to register walk-in customers or offline reservations. Modify the dates of certain appointments, switch employee or service, add or remove options and even more.', 'vikappointments'); ?></p>
			</div>
			<div class="viwppro-feats-img">
				<img src="<?php echo VIKAPPOINTMENTS_CORE_MEDIA_URI; ?>images/edit-booking-en-us.jpg" alt="Appointments Management" />
			</div>
		</div>

		<div class="viwppro-feats-row vikwppro-even">
			<div class="viwppro-feats-img">
				<img src="<?php echo VIKAPPOINTMENTS_CORE_MEDIA_URI; ?>images/employees-area-en-us.jpg" alt="Employees Area" />
			</div>
			<div class="viwppro-feats-text">
				<h4><?php _e('Front-end Employees Area', 'vikappointments'); ?></h4>
				<p><?php _e('This is an area that the employees can access to manage all their details, such as profile information, services, working days, custom fields, locations, coupons and generic settings. The Employees Area, combined to the subscriptions, is the core to set up a portal of employees.', 'vikappointments'); ?></p>
			</div>
		</div>
	</div>

	<div class="viwppro-extra">
		<h3><?php _e('Unlock over 50 must-have features', 'vikappointments'); ?></h3>

		<div class="viwppro-extra-inner">
			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-users"></i>
						<h4><?php _e('Customers Management', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-font"></i>
						<h4><?php _e('Custom Fields', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-dollar-sign"></i>
						<h4><?php _e('Special Rates', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-calendar-times"></i>
						<h4><?php _e('Booking Restrictions', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-gift"></i>
						<h4><?php _e('Packages', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-file-pdf"></i>
						<h4><?php _e('Invoices', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-puzzle-piece"></i>
						<h4><?php _ex('Options', 'services extras', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-street-view"></i>
						<h4><?php _e('Locations', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-globe-americas"></i>
						<h4><?php _e('Countries Management', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-ticket-alt"></i>
						<h4><?php _e('Subscriptions', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-star"></i>
						<h4><?php _e('Reviews', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-history"></i>
						<h4><?php _e('Waiting List', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-shopping-basket"></i>
						<h4><?php _e('Appointments Cart', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-window-maximize"></i>
						<h4><?php _e('Additional Widgets', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-envelope"></i>
						<h4><?php _e('Mail Custom Texts', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-low-vision"></i>
						<h4><?php _e('Access Permissions', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-stopwatch"></i>
						<h4><?php _e('Scheduled Cron Jobs', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-mobile-alt"></i>
						<h4><?php _e('SMS Gateways', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-credit-card"></i>
						<h4><?php _e('Payment Gateways', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>

			<div class="viwppro-extra-item">
				<div class="viwppro-extra-item-inner">
					<div class="viwppro-extra-item-text">
						<i class="fas fa-language"></i>
						<h4><?php _e('Multilingual Contents', 'vikappointments'); ?></h4>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="vikwppro-licensecnt">
		<div class="col col-md-6 col-sm-12 vikwppro-licensetext">
			<div>
				<h3><?php _e('Ready to upgrade?', 'vikappointments'); ?></h3>

				<?php
				if ($this->licenseDate)
				{
					?>
					<h4 class="vikwppro-lickey-expired">
						<?php
						echo sprintf(
							__('Your license key expired on %s', 'vikappointments'),
							JHtml::fetch('date', $this->licenseDate, VAPFactory::getConfig()->get('dateformat'))
						);
						?>
					</h4>
					<?php
				}
				?>

				<h4 class="vikwppro-licensecnt-get"><?php _e('Get your License Key from VikWP.com', 'vikappointments'); ?></h4>
				<a href="https://vikwp.com/" class="vikwp-btn-link" target="_blank"><i class="fas fa-rocket"></i> <?php _e('Get your License Key', 'vikappointments'); ?></a>
			</div>
			<span class="icon-background"><i class="fas fa-rocket"></i></span>
		</div>
		
		<div class="col col-md-6 col-sm-12 vikwppro-licenseform">
			<form>
				<div class="vikwppro-licenseform-inner">
					<h4><?php _e('Already have your key? Enter it here', 'vikappointments'); ?></h4>
					<span class="vikwppro-inputspan"><i class="fas fa-rocket"></i><input type="text" name="key" id="lickey" value="" class="license-input" autocomplete="off" /></span>
					<button type="button" class="btn btn-primary" id="vikwpvalidate" onclick="vikWpValidateLicenseKey();"><?php _e('Validate and Install', 'vikappointments'); ?></button>
				</div>
			</form>
		</div>
	</div>

</div>

<script>

	(function($) {
		'use strict';

		$(function() {
			$('#vikwpgotoget').click(function() {
				$('html,body').animate({
					scrollTop: $('.vikwppro-licensecnt').offset().top - 50,
				}, {
					duration: 'fast',
				});
			});
		});
	})(jQuery);

</script>

<?php
// load common scripts
echo $this->loadTemplate('js');
