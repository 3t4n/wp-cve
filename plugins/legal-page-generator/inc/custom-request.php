<?php
	defined('ABSPATH') or die();
?>
<div class="wrap">
	<h2 class="nav-tab-wrapper">
		<a href="?page=legal-page-generator" class="nav-tab"><?php _e( 'Main', 'legal-page-generator' ); ?></a>
		<a href="?page=legal-page-generator-pages" class="nav-tab"><?php _e( 'Manage Pages', 'legal-page-generator' ); ?></a>
		<a href="?page=legal-page-generator-cr" class="nav-tab nav-tab-active"><?php _e( 'Custom Request', 'legal-page-generator' ); ?></a>
	</h2>
	<h1><?php _e( 'Legal Pages Generator - Custom Request', 'legal-page-generator' ); ?></h1>
	<p><?php _e( 'Dear User, In addition to the website related legal documents, we also provide the following legal documents at a concessional rate of Rs. 1000/- per document to our users except for the matters relating to Intellectual property rights. You may avail the same.', 'legal-page-generator' ); ?></p>
	<form action="<?php echo admin_url( 'admin-post.php?action=lpg_extra_request' ); ?>" method="post">
		<div style="width: 33%; float:left;">
			<h4><?php _e( 'Transactional Agreements', 'legal-page-generator' ); ?></h4>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Employment Contract', 'legal-page-generator' ); ?>"> <?php _e( 'Employment Contract', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Consultancy Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Consultancy Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Freelancer Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Freelancer Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Non-Disclosure Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Non-Disclosure Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Non-compete Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Non-compete Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Non- Solicitation Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Non- Solicitation Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Agreement for Development of Mobile Application', 'legal-page-generator' ); ?>"> <?php _e( 'Agreement for Development of Mobile Application', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Franchise Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Franchise Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Joint Venture', 'legal-page-generator' ); ?>"> <?php _e( 'Joint Venture', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Service Level Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Service Level Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Master Service Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Master Service Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Vendor Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Vendor Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="transactional_agreements[]" value="<?php _e( 'Agreement for development of website', 'legal-page-generator' ); ?>"> <?php _e( 'Agreement for development of website', 'legal-page-generator' ); ?>
			</div>
		</div>
		<div style="width: 33%; float:left;">
			<h4><?php _e( 'Formation Documents', 'legal-page-generator' ); ?></h4>
			<div class="checkbox">
				<input type="checkbox" name="formation_documents[]" value="<?php _e( 'Limited liability Partnership Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Limited liability Partnership Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="formation_documents[]" value="<?php _e( 'Partnership Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Partnership Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="formation_documents[]" value="<?php _e( 'Franchise Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Franchise Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="formation_documents[]" value="<?php _e( 'Trust Deed', 'legal-page-generator' ); ?>"> <?php _e( 'Trust Deed', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="formation_documents[]" value="<?php _e( 'Founders Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Founders Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="formation_documents[]" value="<?php _e( 'Term Sheet', 'legal-page-generator' ); ?>"> <?php _e( 'Term Sheet', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="formation_documents[]" value="<?php _e( 'JV Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'JV Agreement', 'legal-page-generator' ); ?>
			</div>
		</div>
		<div style="width: 33%; float:left;">
			<h4><?php _e( 'Property Related Documents', 'legal-page-generator' ); ?></h4>
			<div class="checkbox">
				<input type="checkbox" name="property_related_documents[]" value="<?php _e( 'Rental Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Rental Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="property_related_documents[]" value="<?php _e( 'Sale Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Sale Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="property_related_documents[]" value="<?php _e( 'Agreement for sale', 'legal-page-generator' ); ?>"> <?php _e( 'Agreement for sale', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="property_related_documents[]" value="<?php _e( 'Hire Purchase Agreement', 'legal-page-generator' ); ?>"> <?php _e( 'Hire Purchase Agreement', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="property_related_documents[]" value="<?php _e( 'Mortgage Deed', 'legal-page-generator' ); ?>"> <?php _e( 'Mortgage Deed', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="property_related_documents[]" value="<?php _e( 'Lease', 'legal-page-generator' ); ?>"> <?php _e( 'Lease', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="property_related_documents[]" value="<?php _e( 'Gift Deed', 'legal-page-generator' ); ?>"> <?php _e( 'Gift Deed', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="property_related_documents[]" value="<?php _e( 'Will', 'legal-page-generator' ); ?>"> <?php _e( 'Will', 'legal-page-generator' ); ?>
			</div>
		</div>
		<div style="width: 33%; float:left;">
			<h4><?php _e( 'Intellectual Property Rights (Rs. 3000/- per Document)', 'legal-page-generator' ); ?></h4>
			<div class="checkbox">
				<input type="checkbox" name="intellectual_property_rights[]" value="<?php _e( 'Trademark License', 'legal-page-generator' ); ?>"> <?php _e( 'Trademark License', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="intellectual_property_rights[]" value="<?php _e( 'Trademark Assignment', 'legal-page-generator' ); ?>"> <?php _e( 'Trademark Assignment', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="intellectual_property_rights[]" value="<?php _e( 'Copyright License', 'legal-page-generator' ); ?>"> <?php _e( 'Copyright License', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="intellectual_property_rights[]" value="<?php _e( 'Copyright Assignment', 'legal-page-generator' ); ?>"> <?php _e( 'Copyright Assignment', 'legal-page-generator' ); ?>
			</div>
			<div class="checkbox">
				<input type="checkbox" name="intellectual_property_rights[]" value="<?php _e( 'Patent License', 'legal-page-generator' ); ?>"> <?php _e( 'Patent License', 'legal-page-generator' ); ?>
			</div>
		</div>
		<br>
		<table style="clear:both;">
			<tbody>
				<tr>
					<td>
						<label for="sendername"><?php _e( 'Name:', 'legal-page-generator' ); ?></label>
					</td>
					<td>
						<input type="text" name="sendername" placeholder="<?php _e( 'Your Name', 'legal-page-generator' ); ?>" required>
					</td>
				</tr>
				<tr>
					<td>
						<label for="senderemail"><?php _e( 'Email:', 'legal-page-generator' ); ?></label>
					</td>
					<td>
						<input type="email" name="senderemail" placeholder="<?php _e( 'Your Email', 'legal-page-generator' ); ?>" required title="<?php _e( 'Enter a valid email address', 'legal-page-generator' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<label for="senderphone"><?php _e( 'Phone:', 'legal-page-generator' ); ?></label>
					</td>
					<td>
						<input type="tel" pattern="\d{10}" name="senderphone" placeholder="<?php _e( 'Your Phone', 'legal-page-generator' ); ?>" title="<?php _e( 'Enter a valid 10 digits phone number', 'legal-page-generator' ); ?>">
					</td>
				</tr>
				<tr>
					<td>
						<input type="submit" class="button button-primary" name="submitrequest" value="<?php _e( 'Send Request', 'legal-page-generator' ); ?>">
					</td>
				</tr>
			</tbody>
		</table>
		<br>
	</form>
	<div>
		<p><?php _e( 'Do you have any other legal document in your mind? Please let us know. Kindly e-mail us to', 'legal-page-generator' ); ?> <a href="mailTo:<?php echo $this->mail_address; ?>"><?php echo $this->mail_address; ?></a> <?php _e( 'and we will respond to you within 48 hours.', 'legal-page-generator' ); ?></p>
		<p><?php _e( 'You can also choose one or more of the above mentioned document(s) and mail us to', 'legal-page-generator' ); ?> <a href="mailTo:<?php echo $this->mail_address; ?>"><?php echo $this->mail_address; ?></a> <?php _e( 'along with the details. Don’t worry, we will contact you and get the required details /documents from you including specifications, if any.', 'legal-page-generator' ); ?></p>
		<p><?php _e( 'Upon request for any legal document, we will send a mail requesting certain details along with the payment. Upon receipt of the details required and the payment, document will be delivered within 7 days. The user can also make TWO ROUNDS OF REVISION to the document which will be done free of cost.', 'legal-page-generator' ); ?></p>
	</div>
</div>