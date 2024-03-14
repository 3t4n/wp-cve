<?php
/**
 * Template for display the sign-up sheet listing
 *
 * This template can be overridden by copying it to yourtheme/fdsus/sheet-task-list.php
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.2 (plugin version)
 * @version     1.0.0 (template file version)
 */

/** @var array $args */
?>

<?php echo $args['above_title']; ?>

<header>
    <h2><?php echo wp_kses_post($args['list_title']); ?></h2>
</header>

<?php if (empty($args['sheets'])) : ?>

    <p><?php esc_html_e('No sheets available at this time.', 'fdsus'); ?></p>

<?php else: ?>

<?php fdsus_the_signup_form_response(); ?>

    <table class="dls-sus-sheets">
        <thead>
            <tr>
                <th class="column-title"><?php esc_html_e('Title', 'fdsus'); ?></th>
                <th class="column-date"><?php esc_html_e('Date', 'fdsus'); ?></th>
                <th class="column-open_spots"><?php esc_html_e('Open Spots', 'fdsus'); ?></th>
                <th class="column-view_link">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php
            /** @var FDSUS\Model\Sheet $sheet */
            foreach ($args['sheets'] AS $sheet):
                $openSpots = $sheet->getOpenSpots();
                $endDate = null;
                if (empty($sheet->dlssus_end_date)) {
                    $displayDate = esc_html__('N/A', 'fdsus');
                } else {
                    $displayDate = (($sheet->dlssus_start_date == $sheet->dlssus_end_date) ? null : date(get_option('date_format'), strtotime($sheet->dlssus_start_date)) . ' - ');
                    $displayDate .= date(get_option('date_format'), strtotime($sheet->dlssus_end_date));
                    $endDate = strtotime($sheet->dlssus_end_date) + 86400;
                }
                if (!empty($endDate) && $endDate < time()) continue;
                ?>
                <tr<?php echo(($openSpots === 0) ? ' class="filled"' : '') ?>>
                    <td class="column-title">
                        <a href="<?php echo get_the_permalink($sheet->ID); ?>"><?php echo wp_kses_post($sheet->post_title); ?></a>
                    </td>
                    <td class="column-date"><?php echo esc_html($displayDate); ?></td>
                    <td class="column-open_spots"><?php echo (int)$openSpots; ?></td>
                    <td class="column-view_link">
                        <?php if ($openSpots > 0): ?>
                            <a href="<?php echo get_the_permalink($sheet->ID); ?>" class="fdsus-signup-cta"><?php esc_html_e('View &amp; sign-up', 'fdsus'); ?><span class="sr-only"> <?php
                                /* translators: %s is replaced with the sheet title */
                                echo esc_html(sprintf(__('for %s', 'fdsus'), $sheet->post_title)); ?></span></a>
                        <?php else: ?>
                            <?php esc_html_e('&#10004; Filled', 'fdsus'); ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php endif; ?>
