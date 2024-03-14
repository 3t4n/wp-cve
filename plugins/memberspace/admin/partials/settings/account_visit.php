<section id="account-tab" class="<?php echo $this->class_for_tab( 'account' ); ?> memberspace-tab-content">
	<h3><?php _ex('MemberSpace Account', 'account visit tab header', 'memberspace'); ?></h3>
	<p><?php _ex('Need to make changes to your plans, pages, members, or account in MemberSpace?', 'account visit tab description', 'memberspace'); ?></p>

	<a href="<?php echo esc_url( $this->memberspace_backend_site_url() ); ?>" target="_blank" class="button button-primary">
	  <?php _ex('Go to your MemberSpace backend', 'account visit tab button label', 'memberspace'); ?>&nbsp;&rarr;
  </a>
</section>
