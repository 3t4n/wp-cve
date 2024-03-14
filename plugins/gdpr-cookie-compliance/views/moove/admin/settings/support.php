<?php
/**
 * Support Doc Comment
 *
 * @category  Views
 * @package   gdpr-cookie-compliance
 * @author    Moove Agency
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

?>
<h2><?php esc_html_e( 'Support', 'gdpr-cookie-compliance' ); ?></h2>
<hr />

<?php
	$forum_link = apply_filters( 'gdpr_cookie_compliance_forum_section_link', 'https://support.mooveagency.com/forum/gdpr-cookie-compliance/' );
?>


<h4><?php esc_html_e( 'Here are the best steps to find answers to your questions and resolve issues as fast as possible', 'gdpr-cookie-compliance' ); ?>:</h4>

<h4>
  <?php 
    echo sprintf(
      esc_html__( '1. Check the %s', 'gdpr-cookie-compliance' ),
      '<a href="' . esc_url( admin_url( 'admin.php?page=moove-gdpr&tab=help' ) ) . '" class="gdpr_admin_link">' . esc_html__( 'Documentation', 'gdpr-cookie-compliance' ) . '</a>'
    );
  ?>
</h4>

<p><?php 
  echo sprintf(
    esc_html__( 'Most issues can be resolved quickly and easily. We compiled the list of basic troubleshooting, hooks, filters, shortcodes and more in our %s section.', 'gdpr-cookie-compliance' ),
    '<a href="' . esc_url( admin_url( 'admin.php?page=moove-gdpr&tab=help' ) ) . '" class="gdpr_admin_link">' . esc_html__( 'Documentation', 'gdpr-cookie-compliance' ) . '</a>'
  );
?></p>


<hr>

<h4>
  <?php 
    echo sprintf(
      esc_html__( '2. Search our %s', 'gdpr-cookie-compliance' ),
      '<a href="' . $forum_link . '" class="gdpr_admin_link" target="_blank">' . esc_html__( 'Support Forum', 'gdpr-cookie-compliance' ) . '</a>'
    );
  ?>
</h4>

<p><?php 
  echo sprintf(
    esc_html__( 'Most questions have already been asked by other users so you can find answers quickly and resolve issues fast by searching for the problem on our %s. Search bar is located in the top right corner.', 'gdpr-cookie-compliance' ),
    '<a href="' . $forum_link . '" class="gdpr_admin_link" target="_blank">' . esc_html__( 'support forum', 'gdpr-cookie-compliance' ) . '</a>'
  );
?></p>
<hr>

<h4><?php esc_html_e( '3. Create a Support Ticket', 'gdpr-cookie-compliance' ); ?></h4>
<p>
  <?php 
    echo sprintf(
      esc_html__( 'If you still need support, you can create a %s in our Support Forum.', 'gdpr-cookie-compliance' ),
      '<a href="' . $forum_link . '#new-post" class="gdpr_admin_link" target="_blank">' . esc_html__( 'new support ticket', 'gdpr-cookie-compliance' ) . '</a>'
    );
  ?>
</p>

<p><?php esc_html_e( 'Please don’t forget to add screenshots or video recording of your screen that would help us see what issues you are experiencing.', 'gdpr-cookie-compliance' ); ?></p>