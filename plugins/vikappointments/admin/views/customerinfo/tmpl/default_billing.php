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

$customer = $this->customer;

$vik = VAPApplication::getInstance();

$canEdit = JFactory::getUser()->authorise('core.edit', 'com_vikappointments');

?>

<style>
	.area-draft-wrapper {
		margin-top: 0;
		position: relative;
		display: flex;
	}
	.area-draft-wrapper textarea {
		width: 100%;
		padding-right: 24px;
	}
	.area-draft-wrapper .draft-tip {
		position: absolute;
		top: 0px;
		right: 8px;
	}
	.vap-customer-image {
		cursor: default;
	}
</style>

<div class="row-fluid">
	
	<div class="span6">
		<?php echo $vik->openFieldset(JText::translate('VAPMANAGECUSTOMERTITLE2'), 'form-horizontal'); ?>

			<div class="order-fields extended">

				<!-- BILLING NAME -->

				<div class="order-field">
					<label><?php echo JText::translate('VAPMANAGECUSTOMER2'); ?></label>
					<div class="order-field-value">
						<b><?php echo $customer->billing_name; ?></b>

						<?php
						// plugins can use the "billing.name" key to introduce custom
						// HTML next to the billing name
						if (isset($this->addons['billing.name']))
						{
							echo $this->addons['billing.name'];

							// unset details form to avoid displaying it twice
							unset($this->addons['billing.name']);
						}
						?>
					</div>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.name","type":"field"} -->
				</div>
				
				<!-- BILLING MAIL -->

				<div class="order-field">
					<label><?php echo JText::translate('VAPMANAGECUSTOMER3'); ?></label>
					<div class="order-field-value">
						<b><?php echo $customer->billing_mail; ?></b>

						<?php
						if ($customer->billing_mail)
						{
							?>
							<a href="mailto:<?php echo $customer->billing_mail; ?>" style="margin-left:4px;">
								<i class="fas fa-envelope"></i>
							</a>
							<?php
						}

						// plugins can use the "billing.mail" key to introduce custom
						// HTML next to the billing mail
						if (isset($this->addons['billing.mail']))
						{
							echo $this->addons['billing.mail'];

							// unset details form to avoid displaying it twice
							unset($this->addons['billing.mail']);
						}
						?>
					</div>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.mail","type":"field"} -->
				</div>
				
				<!-- BILLING PHONE -->

				<div class="order-field">
					<label><?php echo JText::translate('VAPMANAGECUSTOMER4'); ?></label>
					<div class="order-field-value">
						<b><?php echo $customer->billing_phone; ?></b>

						<?php
						if ($customer->billing_phone)
						{
							?>
							<a href="tel:<?php echo $customer->billing_phone; ?>" style="margin-left:4px;">
								<i class="fas fa-phone"></i>
							</a>
							<?php
						}

						// plugins can use the "billing.phone" key to introduce custom
						// HTML next to the billing phone
						if (isset($this->addons['billing.phone']))
						{
							echo $this->addons['billing.phone'];

							// unset details form to avoid displaying it twice
							unset($this->addons['billing.phone']);
						}
						?>
					</div>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.phone","type":"field"} -->
				</div>
				
				<!-- BILLING COUNTRY -->

				<div class="order-field">
					<label><?php echo JText::translate('VAPMANAGECUSTOMER5'); ?></label>
					<div class="order-field-value">
						<b><?php echo $customer->country; ?></b>

						<?php
						// plugins can use the "billing.country" key to introduce custom
						// HTML next to the billing country
						if (isset($this->addons['billing.country']))
						{
							echo $this->addons['billing.country'];

							// unset details form to avoid displaying it twice
							unset($this->addons['billing.country']);
						}
						?>
					</div>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.country","type":"field"} -->
				</div>
				
				<!-- BILLING STATE -->

				<div class="order-field">
					<label><?php echo JText::translate('VAPMANAGECUSTOMER6'); ?></label>
					<div class="order-field-value">
						<b><?php echo $customer->billing_state; ?></b>

						<?php
						// plugins can use the "billing.state" key to introduce custom
						// HTML next to the billing state
						if (isset($this->addons['billing.state']))
						{
							echo $this->addons['billing.state'];

							// unset details form to avoid displaying it twice
							unset($this->addons['billing.state']);
						}
						?>
					</div>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.state","type":"field"} -->
				</div>
				
				<!-- BILLING CITY -->

				<div class="order-field">
					<label><?php echo JText::translate('VAPMANAGECUSTOMER7'); ?></label>
					<div class="order-field-value">
						<b><?php echo $customer->billing_city; ?></b>

						<?php
						// plugins can use the "billing.city" key to introduce custom
						// HTML next to the billing city
						if (isset($this->addons['billing.city']))
						{
							echo $this->addons['billing.city'];

							// unset details form to avoid displaying it twice
							unset($this->addons['billing.city']);
						}
						?>
					</div>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.city","type":"field"} -->
				</div>
				
				<!-- BILLING ADDRESS -->

				<div class="order-field">
					<label><?php echo JText::translate('VAPMANAGECUSTOMER8'); ?></label>
					<div class="order-field-value">
						<b>
							<?php
							echo $customer->billing_address;

							if ($customer->billing_address_2)
							{
								echo ' (' . $customer->billing_address_2 . ')';
							}
							?>
						</b>

						<?php
						// plugins can use the "billing.address" key to introduce custom
						// HTML next to the billing address
						if (isset($this->addons['billing.address']))
						{
							echo $this->addons['billing.address'];

							// unset details form to avoid displaying it twice
							unset($this->addons['billing.address']);
						}
						?>
					</div>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.address","type":"field"} -->
				</div>
				
				<!-- BILLING ZIP CODE -->

				<div class="order-field">
					<label><?php echo JText::translate('VAPMANAGECUSTOMER9'); ?></label>
					<div class="order-field-value">
						<b><?php echo $customer->billing_zip; ?></b>

						<?php
						// plugins can use the "billing.zip" key to introduce custom
						// HTML next to the billing zip
						if (isset($this->addons['billing.zip']))
						{
							echo $this->addons['billing.zip'];

							// unset details form to avoid displaying it twice
							unset($this->addons['billing.zip']);
						}
						?>
					</div>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.zip","type":"field"} -->
				</div>
				
				<!-- BILLING COMPANY -->

				<div class="order-field">
					<label><?php echo JText::translate('VAPMANAGECUSTOMER10'); ?></label>
					<div class="order-field-value">
						<b><?php echo $customer->company; ?></b>

						<?php
						// plugins can use the "billing.company" key to introduce custom
						// HTML next to the billing company
						if (isset($this->addons['billing.company']))
						{
							echo $this->addons['billing.company'];

							// unset details form to avoid displaying it twice
							unset($this->addons['billing.company']);
						}
						?>
					</div>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.company","type":"field"} -->
				</div>
				
				<!-- BILLING VAT NUMBER -->

				<div class="order-field">
					<label><?php echo JText::translate('VAPMANAGECUSTOMER11'); ?></label>
					<div class="order-field-value">
						<b><?php echo $customer->vatnum; ?></b>

						<?php
						// plugins can use the "billing.vatnum" key to introduce custom
						// HTML next to the billing vatnum
						if (isset($this->addons['billing.vatnum']))
						{
							echo $this->addons['billing.vatnum'];

							// unset details form to avoid displaying it twice
							unset($this->addons['billing.vatnum']);
						}
						?>
					</div>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.vatnum","type":"field"} -->
				</div>
				
				<!-- BILLING SSN -->

				<div class="order-field">
					<label><?php echo JText::translate('VAPMANAGECUSTOMER20'); ?></label>
					<div class="order-field-value">
						<b><?php echo $customer->ssn; ?></b>

						<?php
						// plugins can use the "billing.ssn" key to introduce custom
						// HTML next to the billing ssn
						if (isset($this->addons['billing.ssn']))
						{
							echo $this->addons['billing.ssn'];

							// unset details form to avoid displaying it twice
							unset($this->addons['billing.ssn']);
						}
						?>
					</div>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.ssn","type":"field"} -->
				</div>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.custom","type":"field"} -->

				<?php
				// plugins can use the "billing.custom" key to introduce custom
				// HTML at the end of the block
				if (isset($this->addons['billing.custom']))
				{
					echo $this->addons['billing.custom'];

					// unset details form to avoid displaying it twice
					unset($this->addons['billing.custom']);
				}
				?>

			</div>
			
		<?php echo $vik->closeFieldset(); ?>
	</div>

	<div class="span6">

		<div class="row-fluid">

			<div class="span12">
				<?php echo $vik->openFieldset(JText::translate('VAPMANAGECUSTOMERTITLE1')); ?>
			
					<!-- JOOMLA AVATAR -->

					<a href="javascript:void(0);" id="avatar-handle">
						<?php
						if (empty($customer->image))
						{
							?>
							<img src="<?php echo VAPASSETS_URI . 'css/images/default-profile.png'; ?>" class="vap-customer-image" />
							<?php
						}
						else
						{
							?>
							<img src="<?php echo VAPCUSTOMERS_AVATAR_URI . $customer->image; ?>" class="vap-customer-image" />
							<?php
						}
						?>
					</a>

					<?php
					if ($customer->user->id)
					{
						?>
						<div class="order-fields extended">

							<!-- CMS ACCOUNT NAME -->

							<div class="order-field">
								<label><?php echo JText::translate('VAPMANAGECUSTOMER2'); ?></label>
								<div class="order-field-value">
									<b><?php echo $customer->user->name; ?></b>

									<?php
									// plugins can use the "account.name" key to introduce custom
									// HTML next to the account name
									if (isset($this->addons['account.name']))
									{
										echo $this->addons['account.name'];

										// unset details form to avoid displaying it twice
										unset($this->addons['account.name']);
									}
									?>
								</div>

								<!-- Define role to detect the supported hook -->
								<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"account.name","type":"field"} -->
							</div>
							
							<!-- CMS ACCOUNT USERNAME -->

							<div class="order-field">
								<label><?php echo JText::translate('JGLOBAL_USERNAME'); ?></label>
								<div class="order-field-value">
									<b><?php echo $customer->user->username; ?></b>

									<?php
									// plugins can use the "account.username" key to introduce custom
									// HTML next to the account username
									if (isset($this->addons['account.username']))
									{
										echo $this->addons['account.username'];

										// unset details form to avoid displaying it twice
										unset($this->addons['account.username']);
									}
									?>
								</div>

								<!-- Define role to detect the supported hook -->
								<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"account.username","type":"field"} -->
							</div>
							
							<!-- CMS ACCOUNT MAIL -->

							<div class="order-field">
								<label><?php echo JText::translate('VAPMANAGECUSTOMER3'); ?></label>
								<div class="order-field-value">
									<b><?php echo $customer->user->email; ?></b>

									<?php
									// plugins can use the "account.mail" key to introduce custom
									// HTML next to the account mail
									if (isset($this->addons['account.mail']))
									{
										echo $this->addons['account.mail'];

										// unset details form to avoid displaying it twice
										unset($this->addons['account.mail']);
									}
									?>
								</div>

								<!-- Define role to detect the supported hook -->
								<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"account.mail","type":"field"} -->
							</div>

						</div>
						<?php
					}
					else
					{
						?>
						<div class="customer-info-guest" style="max-width: calc(100% - 64px);">
							<?php echo $vik->alert(JText::translate('VAPMANAGECUSTOMER15')); ?>
						</div>
						<?php
					}
					?>

					<!-- Define role to detect the supported hook -->
					<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"account.custom","type":"field"} -->

					<?php
					// plugins can use the "account.custom" key to introduce custom
					// HTML at the end of the "User Account" block
					if (isset($this->addons['account.custom']))
					{
						echo $this->addons['account.custom'];

						// unset details form to avoid displaying it twice
						unset($this->addons['account.custom']);
					}
					?>

					<div class="order-fields extended">

						<!-- CREDIT -->

						<?php
						if ($customer->credit > 0)
						{
							?>
							<div class="order-field">
								<label><?php echo JText::translate('VAPUSERCREDIT'); ?></label>
								<div class="order-field-value">
									<b><?php echo VAPFactory::getCurrency()->format($customer->credit); ?></b>

									<?php
									// plugins can use the "account.credit" key to introduce custom
									// HTML next to the account credit
									if (isset($this->addons['account.credit']))
									{
										echo $this->addons['account.credit'];

										// unset details form to avoid displaying it twice
										unset($this->addons['account.credit']);
									}
									?>
								</div>

								<!-- Define role to detect the supported hook -->
								<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"account.credit","type":"field"} -->
							</div>
							<?php
						}
						?>

						<!-- SUBSCRIPTION ACTIVATION DATE -->

						<?php
						if (!VAPDateHelper::isNull($customer->active_since))
						{
							?>
							<div class="order-field">
								<label><?php echo JText::translate('VAPMANAGEEMPLOYEE28'); ?></label>
								<div class="order-field-value">
									<b><?php echo JHtml::fetch('date', $customer->active_since, JText::translate('DATE_FORMAT_LC5')); ?></b>

									<?php
									// plugins can use the "account.activesince" key to introduce custom
									// HTML next to the account activation date
									if (isset($this->addons['account.activesince']))
									{
										echo $this->addons['account.activesince'];

										// unset details form to avoid displaying it twice
										unset($this->addons['account.activesince']);
									}
									?>
								</div>

								<!-- Define role to detect the supported hook -->
								<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"account.activesince","type":"field"} -->
							</div>
							<?php
						}
						?>

						<!-- SUBSCRIPTION EXPIRATION DATE -->

						<?php
						if (!VAPDateHelper::isNull($customer->active_to_date) || $customer->lifetime)
						{
							?>
							<div class="order-field">
								<label><?php echo JText::translate('VAPMANAGEEMPLOYEE27'); ?></label>
								<div class="order-field-value">
									<?php
									if ($customer->lifetime)
									{
										?>
										<span class="vapreservationstatusconfirmed"><?php echo JText::translate('VAPSUBSCRTYPE5'); ?></span>
										<?php
									}
									else
									{
										if ($customer->daysLeft <= 0)
										{
											// expired
											$class = 'vapreservationstatusremoved';
										}
										else if ($customer->daysLeft < 7)
										{
											// close to expire
											$class = 'vapreservationstatuspending';
										}
										else
										{
											// active
											$class = 'vapreservationstatusconfirmed';
										}

										?>
										<span class="<?php echo $class; ?>"><?php echo JHtml::fetch('date', $customer->active_to_date, JText::translate('DATE_FORMAT_LC5')); ?></span>
										<?php
									}

									$subscr_info = array();

									if ($customer->subscription)
									{
										// display subscription plan
										$subscr_info[] = $customer->subscription['name'];
									}

									if ($customer->daysLeft > 0)
									{
										// display number of remaining days
										$subscr_info[] = JText::plural('VAP_N_DAYS_LEFT', $customer->daysLeft);
									}
									
									if ($subscr_info)
									{
										?>
										<i class="fas fa-info-circle hasTooltip" title="<?php echo $this->escape(implode(' : ', $subscr_info)); ?>"></i>
										<?php
									}
									
									// plugins can use the "account.expdate" key to introduce custom
									// HTML next to the account expiration date
									if (isset($this->addons['account.expdate']))
									{
										echo $this->addons['account.expdate'];

										// unset details form to avoid displaying it twice
										unset($this->addons['account.expdate']);
									}
									?>
								</div>

								<!-- Define role to detect the supported hook -->
								<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"account.expdate","type":"field"} -->
							</div>
							<?php
						}
						?>

						<!-- REDEEMED PACKAGES -->

						<?php if ($this->packUserCount->num_app): ?>
							<div class="order-field">
								<label><?php echo JText::translate('VAPMENUPACKAGES'); ?></label>
								<div class="order-field-value">
									<span style="text-transform: lowercase;" class="<?php echo $this->packUserCount->used_app < $this->packUserCount->num_app ? 'vapreservationstatusconfirmed' : 'vapreservationstatuspending'; ?>">
										<?php echo JText::sprintf('VAPREDEEMEDPACKAGES', $this->packUserCount->used_app, $this->packUserCount->num_app); ?>
									</span>

									<?php
									// plugins can use the "account.packages" key to introduce custom
									// HTML next to the number of redeemed/total packages
									if (isset($this->addons['account.packages']))
									{
										echo $this->addons['account.packages'];

										// unset details form to avoid displaying it twice
										unset($this->addons['account.packages']);
									}
									?>
								</div>
							</div>
						<?php endif; ?>

					</div>
					
				<?php echo $vik->closeFieldset(); ?>
			</div>

		</div>

		<div class="row-fluid">

			<div class="span12">
				<?php
				echo $vik->openFieldset(JText::translate('VAPMANAGECUSTOMERTITLE4'));
				echo $this->loadTemplate('draft');
				?>

				<!-- Define role to detect the supported hook -->
				<!-- {"rule":"customizer","event":"onDisplayViewCustomerinfo","key":"billing.notes","type":"field"} -->

				<?php
				// plugins can use the "billing.notes" key to introduce custom
				// HTML at the end of the "Notes" block
				if (isset($this->addons['billing.notes']))
				{
					echo $this->addons['billing.notes'];

					// unset details form to avoid displaying it twice
					unset($this->addons['billing.notes']);
				}
				?>

				<?php echo $vik->closeFieldset(); ?>
			</div>

		</div>

	</div>

</div>
