<?php
/**
 * Template for displaying the sign-up form header
 *
 * This template can be overridden by copying it to yourtheme/fdsus/sign-up-form-header.php
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.2.11 (plugin version)
 * @version     1.0.0 (template file version)
 */

/** @var array $args */
/** @var FDSUS\Model\Sheet $sheet */
/** @var string $signup_titles_str */
extract($args);

if (!is_admin()): ?>
    <h3><?php esc_html_e('Sign-up below', 'fdsus'); ?></h3>
    <p>
        <?php esc_html_e('You are signing up for...', 'fdsus'); ?>
        <em class="dls-sus-task-title"><?php echo wp_kses_post($signup_titles_str); ?></em>
    </p>
<?php endif; ?>
