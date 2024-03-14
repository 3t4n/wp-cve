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

JHtml::fetch('vaphtml.assets.fancybox');
JHtml::fetch('vaphtml.assets.select2');
JHtml::fetch('vaphtml.assets.fontawesome');
JHtml::fetch('vaphtml.assets.intltel', '#billing-phone');

$customer = $this->customer;
$itemid   = $this->itemid;

?>

<div class="vap-userprofile-toolbar">

	<div class="vap-userprofile-title">
		<h2><?php echo JText::translate('VAPUSERPROFILETITLE'); ?></h2>
	</div>

	<div class="vap-userprofile-controls">
		<button class="vap-btn blue" data-role="userprofile.save">
			<?php echo JText::translate('VAPSAVE'); ?>
		</button>
		
		<button class="vap-btn blue" data-role="userprofile.saveclose">
			<?php echo JText::translate('VAPSAVEANDCLOSE'); ?>
		</button>
		
		<a class="vap-btn blue" href="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=allorders' . ($itemid ? '&Itemid=' . $itemid : '')); ?>">
			<?php echo JText::translate('VAPCLOSE'); ?>
		</a>
	</div>
</div>

<form name="usersaveprofile" action="<?php echo JRoute::rewrite('index.php?option=com_vikappointments&view=userprofile' . ($itemid ? '&Itemid=' . $itemid : '')); ?>" method="post" enctype="multipart/form-data">
	
	<div class="vap-userprofile-container">
		
		<div class="vap-userprofile-leftwrapper">
	
			<!-- Full Name -->

			<div class="vap-userprofile-field">
				<div class="vap-userprofile-field-label">
					<label for="billing-name"><?php echo JText::translate('VAPUSERPROFILEFIELD1'); ?><sup>*</sup></label>
				</div>
				<div class="vap-userprofile-field-control">
					<input type="text" name="billing_name" class="required" id="billing-name" value="<?php echo $this->escape(isset($customer->billing_name) ? $customer->billing_name : ''); ?>" />
				</div>
			</div>
			
			<!-- E-Mail -->

			<div class="vap-userprofile-field">
				<div class="vap-userprofile-field-label">
					<label for="billing-mail"><?php echo JText::translate('VAPUSERPROFILEFIELD2'); ?><sup>*</sup></label>
				</div>
				<div class="vap-userprofile-field-control">
					<input type="text" name="billing_mail" class="required" id="billing-mail" value="<?php echo $this->escape(isset($customer->billing_mail) ? $customer->billing_mail : ''); ?>" />
				</div>
			</div>
			
			<!-- Phone Number -->

			<div class="vap-userprofile-field">
				<div class="vap-userprofile-field-label">
					<label for="billing-phone"><?php echo JText::translate('VAPUSERPROFILEFIELD3'); ?></label>
				</div>
				<div class="vap-userprofile-field-control">
					<input type="text" name="billing_phone" id="billing-phone" value="<?php echo $this->escape(isset($customer->billing_phone) ? $customer->billing_phone : ''); ?>" />
				</div>
			</div>
			
			<!-- Profile Image -->

			<?php
			if ($this->shouldDisplayField('avatar'))
			{
				?>
				<div class="vap-userprofile-field">
					<div class="vap-userprofile-field-label">
						<label for="user-image"><?php echo JText::translate('VAPUSERPROFILEFIELD13'); ?></label>
					</div>
					<div class="vap-userprofile-field-control">
						<input type="file" name="image" id="user-image" />
						<?php
						if (!empty($customer->image))
						{ 
							?>
							<a href="javascript:void(0)" class="vapmodal" onClick="vapOpenModalImage('<?php echo VAPCUSTOMERS_AVATAR_URI . $customer->image; ?>');">
								<i class="fas fa-image"></i>
							</a>
							<?php
						}
						?>
					</div>
				</div>
				<?php
			}
			?>
			
		</div>
		
		<div class="vap-userprofile-rightwrapper">
			
			<!-- Country -->

			<?php
			if ($this->shouldDisplayField('country'))
			{
				?>
				<div class="vap-userprofile-field">
					<div class="vap-userprofile-field-label">
						<label for="country-code"><?php echo JText::translate('VAPUSERPROFILEFIELD4'); ?></label>
					</div>
					<div class="vap-userprofile-field-control">
						<select name="country_code" id="country-code">
							<option></option>
							<?php
							foreach (VAPLocations::getCountries('country_name') as $country)
							{
								?>
								<option
									value="<?php echo $country['country_2_code']; ?>" 
									<?php echo $country['country_2_code'] == $customer->country_code ? 'selected="selected"' : ''; ?>
								><?php echo $country['country_name']; ?></option>
								<?php
							}
							?>
						</select>
					</div>
				</div>
				<?php
			}
			?>
			
			<!-- State -->

			<?php
			if ($this->shouldDisplayField('state'))
			{
				?>
				<div class="vap-userprofile-field">
					<div class="vap-userprofile-field-label">
						<label for="billing-state"><?php echo JText::translate('VAPUSERPROFILEFIELD5'); ?></label>
					</div>
					<div class="vap-userprofile-field-control">
						<input type="text" name="billing_state" id="billing-state" value="<?php echo $this->escape(isset($customer->billing_state) ? $customer->billing_state : ''); ?>" />
					</div>
				</div>
				<?php
			}
			?>
			
			<!-- City -->

			<?php
			if ($this->shouldDisplayField('city'))
			{
				?>
				<div class="vap-userprofile-field">
					<div class="vap-userprofile-field-label">
						<label for="billing-city"><?php echo JText::translate('VAPUSERPROFILEFIELD6'); ?></label>
					</div>
					<div class="vap-userprofile-field-control">
						<input type="text" name="billing_city" id="billing-city" value="<?php echo $this->escape(isset($customer->billing_city) ? $customer->billing_city : ''); ?>" />
					</div>
				</div>
				<?php
			}
			?>
			
			<!-- Address -->

			<?php
			if ($this->shouldDisplayField('address'))
			{
				?>
				<div class="vap-userprofile-field">
					<div class="vap-userprofile-field-label">
						<label for="billing-address"><?php echo JText::translate('VAPUSERPROFILEFIELD7'); ?></label>
					</div>
					<div class="vap-userprofile-field-control">
						<input type="text" name="billing_address" id="billing-address" value="<?php echo $this->escape(isset($customer->billing_address) ? $customer->billing_address : ''); ?>" />
					</div>
				</div>
				<?php
			}
			?>
			
			<!-- Address 2 -->

			<?php
			if ($this->shouldDisplayField('address2'))
			{
				?>
				<div class="vap-userprofile-field">
					<div class="vap-userprofile-field-label">
						<label for="billing-address-2"><?php echo JText::translate('VAPUSERPROFILEFIELD8'); ?></label>
					</div>
					<div class="vap-userprofile-field-control">
						<input type="text" name="billing_address_2" id="billing-address-2" value="<?php echo $this->escape(isset($customer->billing_address_2) ? $customer->billing_address_2 : ''); ?>" />
					</div>
				</div>
				<?php
			}
			?>
			
			<!-- Zip Code -->

			<?php
			if ($this->shouldDisplayField('zip'))
			{
				?>
				<div class="vap-userprofile-field">
					<div class="vap-userprofile-field-label">
						<label for="billing-zip"><?php echo JText::translate('VAPUSERPROFILEFIELD9'); ?></label>
					</div>
					<div class="vap-userprofile-field-control">
						<input type="text" name="billing_zip" id="billing-zip" value="<?php echo $this->escape(isset($customer->billing_zip) ? $customer->billing_zip : ''); ?>" />
					</div>
				</div>
				<?php
			}
			?>
			
			<!-- Company -->

			<?php
			if ($this->shouldDisplayField('company'))
			{
				?>
				<div class="vap-userprofile-field">
					<div class="vap-userprofile-field-label">
						<label for="billing-company"><?php echo JText::translate('VAPUSERPROFILEFIELD10'); ?></label>
					</div>
					<div class="vap-userprofile-field-control">
						<input type="text" name="company" id="billing-company" value="<?php echo $this->escape(isset($customer->company) ? $customer->company : ''); ?>" />
					</div>
				</div>
				<?php
			}
			?>
			
			<!-- Vat Num -->

			<?php
			if ($this->shouldDisplayField('vatnum'))
			{
				?>
				<div class="vap-userprofile-field">
					<div class="vap-userprofile-field-label">
						<label for="billing-vatnum"><?php echo JText::translate('VAPUSERPROFILEFIELD11'); ?></label>
					</div>
					<div class="vap-userprofile-field-control">
						<input type="text" name="vatnum" id="billing-vatnum" value="<?php echo $this->escape(isset($customer->vatnum) ? $customer->vatnum : ''); ?>" />
					</div>
				</div>
				<?php
			}
			?>
			
			<!-- SSN -->

			<?php
			if ($this->shouldDisplayField('ssn'))
			{
				?>
				<div class="vap-userprofile-field">
					<div class="vap-userprofile-field-label">
						<label for="billing-ssn"><?php echo JText::translate('VAPUSERPROFILEFIELD12'); ?></label>
					</div>
					<div class="vap-userprofile-field-control">
						<input type="text" name="ssn" id="billing-ssn" value="<?php echo $this->escape(isset($customer->ssn) ? $customer->ssn : ''); ?>" style="text-transform: uppercase;" />
					</div>
				</div>
				<?php
			}
			?>
		  
		  </div>
			
	</div>
	
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_vikappointments" />
	
	<input type="hidden" name="Itemid" value="<?php echo $itemid; ?>" />
</form>

<script>

	jQuery(function($) {
		const validator = new VikFormValidator('form[name="usersaveprofile"]');
		validator.setLabel($('#billing-name'), $('label[for="billing-name"]'));
		validator.setLabel($('#billing-mail'), $('label[for="billing-mail"]'));

		$('#country-code').select2({
			placeholder: '--',
			allowClear: true,
			width: 300,
		});

		$('button[data-role^="userprofile."]').on('click', function() {
			if (validator.validate()) {
				document.usersaveprofile.task.value = $(this).data('role');
				// submit form via jQuery in order to properly trigger the related event
				$('form[name="usersaveprofile"]').submit();
			}
		});
	});
	
</script>
