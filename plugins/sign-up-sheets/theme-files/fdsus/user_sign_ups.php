<?php
/**
 * Template for display the user sign-ups listing
 *
 * This template can be overridden by copying it to yourtheme/fdsus/user_sign_ups.php
 *
 * @package     FetchDesigns
 * @subpackage  Sign_Up_Sheets
 * @see         https://www.fetchdesigns.com/sign-up-sheets-pro-overriding-templates-in-your-theme/
 * @since       2.2.11 (plugin version)
 * @version     1.0.0 (template file version)
 */

/** @var array $args */

/** @var array $rows */
$rows = $args['user_signup_rows'];

/** @var string $taskTitleLabel */
$taskTitleLabel = $args['task_title_label'];
?>

<?php fdsus_the_signup_form_response() ?>
<table class="fdsus-user-sign-ups-table">
    <thead>
    <tr>
        <th class="fdssus-column-date"><?php echo __('Date Added', 'fdsus') ?></th>
        <th class="fdssus-column-sheet-task">
            <?php echo esc_html($taskTitleLabel) ?>
        </th>
        <th class="fdssus-column-signup"><?php echo __('Sign-up Details', 'fdsus') ?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($rows as $row): ?>
        <tr class="<?php echo ($row['sheet']->isExpired() || $row['task']->isExpired()) ? 'dls-sus-task-expired' : '' ?>">
            <td class="fdsus-col-date-added">
                <?php echo $row['date_added']
                    ? date(get_option('date_format'), strtotime($row['date_added']))
                    : __('N/A', 'fdsus') ?>
            </td>
            <td class="fdsus-col-sheet">
                <a class="fdsus-sheet-title" href="<?php echo esc_url(get_permalink($row['sheet']->getData())) ?>"><?php
                    echo esc_html($row['sheet']->post_title) ?></a>
                <div class="fdsus-task-date"><?php echo $row['task_date']
                        ? date(get_option('date_format'), strtotime($row['task_date'])) : '' ?>
                </div>
                <?php if (empty($row['task_additional'])): ?>
                    <span class="fdsus-task-title"><?php echo esc_html($row['task']->post_title) ?></span>
                <?php else: ?>
                    <details>
                        <summary class="fdsus-task-title"><?php echo esc_html($row['task']->post_title) ?></summary>
                        <?php if (is_array($row['task_additional'])): ?>
                            <?php foreach ($row['task_additional'] as $additional): ?>
                                <dl class="fdsus-task-details">
                                    <dt><?php echo esc_html($additional['label']) ?></dt>
                                    <dd><?php echo esc_html($additional['value']) ?></dd>
                                </dl>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </details>
                <?php endif; ?>
            </td>
            <td class="fdsus-col-task-signup">
                <?php if (empty($row['signup_additional'])): ?>
                    <span class="fdsus-actions"><?php
                        fdsus_scode_user_sign_ups_actions($row);
                        ?></span><span class="fdsus-signup-name"><?php
                        echo esc_html($row['firstname'] . ' ' . $row['lastname']);
                        ?></span>
                <?php else: ?>
                    <details>
                        <summary><span class="fdsus-signup-summary-wrap"><span class="fdsus-actions"><?php
                                    fdsus_scode_user_sign_ups_actions($row);
                                    ?></span><span class="fdsus-signup-name"><?php
                                    echo esc_html($row['firstname'] . ' ' . $row['lastname'])
                                    ?></span></span></summary>
                        <?php if (!empty($row['signup_additional']) && is_array($row['signup_additional'])): ?>
                            <?php foreach ($row['signup_additional'] as $additional): ?>
                                <dl class="fdsus-signup-details">
                                    <dt><?php echo esc_html($additional['label']) ?></dt>
                                    <dd><?php echo esc_html($additional['value']) ?></dd>
                                </dl>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </details>
                <?php endif; ?>

                <?php if ($row['sheet']->isExpired() || $row['task']->isExpired()): ?>
                    <span class="fdsus-signups-closed"><?php _e('Sign-ups Closed', 'fdsus') ?></span>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
