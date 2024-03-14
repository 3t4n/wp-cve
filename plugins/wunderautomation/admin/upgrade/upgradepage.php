<?php

$proPage = 'https://www.wundermatics.com/product/wunderautomation-pro/';
$utm     = '?utm_source=dashboard&utm_medium=upgradepage&utm_campaign=installed_users';

$features = [
    'Integrations'           => (object)[
        'title'  => 'Integrations',
        'desc'   => 'Integrations allows WunderAutomation to communicate with other WordPress plugins and external ' .
            'services to create even more powerful workflows.',
        'button' => true,
    ],
    'WooCommerce'            => (object)[
        'title' => 'WooCommerce',
        'image' => 'woo-600.png',
        'desc'  => 'Trigger workflows when orders are created, paid, cancelled or completed using the WooCommerce ' .
            'specific triggers. Add filters based on order content and create actions using parameters from ' .
            'WooCommerce orders',
        'avail' => (object)['free' => true, 'pro' => true],
    ],
    'Advanced Custom Fields' => (object)[
        'title' => 'Advanced Custom Fields',
        'image' => 'acf-600.png',
        'desc'  => 'Access fields created with Advanced Custom fields in workflow filters and parameters.',
        'avail' => (object)['free' => true, 'pro' => true],
    ],
    'BuddyPress / BuddyBoss' => (object)[
        'title' => 'BuddyPress / BuddyBoss',
        'image' => 'buddypress-600.png',
        'desc'  => 'Trigger workflows when users sign up and gets activated, joins or leaves groups. Add or remove ' .
            'users from groups and even create new groups using workflow actions.',
        'avail' => (object)['free' => false, 'pro' => true],
    ],
    'Slack'                  => (object)[
        'title' => 'Slack',
        'image' => 'slack-600.png',
        'desc'  => 'Send messages to any Slack channel.',
        'avail' => (object)['free' => false, 'pro' => true],
    ],
    'Twilio'                 => (object)[
        'title' => 'Twilio',
        'image' => 'twilio-600.png',
        'desc'  => 'Send SMS messages using Twilio\'s worldwide SMS service',
        'avail' => (object)['free' => false, 'pro' => true],
    ],
    'Telegram'               => (object)[
        'title' => 'Telegram',
        'image' => 'telegram-600.png',
        'desc'  => 'Send messages to Telegram groups.',
        'avail' => (object)['free' => false, 'pro' => true],
    ],
    'MailChimp'              => (object)[
        'title' => 'MailChimp',
        'image' => 'mailchimp-600.png',
        'desc'  => 'Trigger workflows when new subscribers are added to your MailChimp audience. Add new subscribers ' .
            'to your lists or update existing ones using workflow actions. Trigger MailChimp automation events as ' .
            'part of your WunderAutomation workflow',
        'avail' => (object)['free' => false, 'pro' => true],
    ],
    'MailPoet'               => (object)[
        'title' => 'MailPoet',
        'image' => 'mailpoet-600.png',
        'desc'  => 'Trigger workflows when new subscribers are added or removed to your lists or use the MailPoet ' .
            'actions to add or update existing subscribers. Also allows you to send transactional emails using your' .
            'existing MailPoet templates',
        'avail' => (object)['free' => false, 'pro' => true],
    ],
    'WPForms'                => (object)[
        'title' => 'WPForms',
        'image' => 'wpforms-600.png',
        'desc'  => 'Create beautiful forms using WPForms and trigger WunderAutomation workflows when users submit ' .
            'them. ',
        'avail' => (object)['free' => false, 'pro' => true],
    ],
    'CF7'                    => (object)[
        'title' => 'Contact Form 7',
        'image' => 'cf7-600.png',
        'desc'  => 'Integrates with one of the most popular WordPress plugins to date. Trigger workflows when users ' .
            'forms created with Contact Form 7',
        'avail' => (object)['free' => false, 'pro' => true],
    ],
    'Re-Triggers'            => (object)[
        'title'  => 'Re-Triggers',
        'desc'   => 'Use Re-Triggers to schedule recurring tasks. A re-trigger selects a subset of users, orders or ' .
            'posts and sends them to a workflow on a recurring schedule.',
        'button' => true,
    ],
    'rt-basic'               => (object)[
        'title' => 'Basic scheduling',
        'image' => 'sched-600.png',
        'desc'  => 'Allows you to run re-triggers manually or daily at any given time',
        'avail' => (object)['free' => true, 'pro' => true],
    ],
    'rt-adv'                 => (object)[
        'title' => 'Advanced scheduling',
        'image' => 'adv-sched-600.png',
        'desc'  => 'Allows more advanved scheduling to run re-triggers hourly, daily, weekly or monthly ',
        'avail' => (object)['free' => false, 'pro' => true],
    ],
    'Support'                => (object)[
        'title' => 'Support',
        'image' => 'support-600.png',
        'desc'  => 'Access to E-mail support ',
        'avail' => (object)['free' => false, 'pro' => true],
    ],
    'last-row'               => (object)[
        'title'  => '',
        'desc'   => '',
        'button' => true,
    ],
];

$imgBase = content_url('plugins/wunderautomation/admin/assets/images/upgrade')
?>

<div class="wunder_pro_upgrade">
    <!-- header -->
    <section>
        <h1>Upgrade to WunderAutomation Pro</h1>
    </section>

    <!-- compare -->
    <section>
        <table class="wunder_feat_table udpdraft__lifted">
            <tbody>
            <tr class="wunder_feat_table__header">
                <td></td>
                <td>Free</td>
                <td>Pro</td>
            </tr>

            <?php foreach ($features as $feature) : ?>
                <tr>
                    <td>
                        <div class="tw-flex tw-flex-row">
                            <?php if (isset($feature->image)) : ?>
                            <div>
                                <img src="<?php esc_attr_e($imgBase . '/' . $feature->image)?>"
                                     width="60" height="60" class="udp-premium-image">
                            </div>
                            <?php endif ?>
                            <div>
                                <h4><?php esc_html_e($feature->title)?></h4>
                                <p><?php esc_html_e($feature->desc)?></p>
                            </div>
                        </div>
                    </td>
                    <td style="text-align: center">
                        <?php  if (isset($feature->avail->free) && $feature->avail->free) : ?>
                            <span class="dashicons dashicons-yes" aria-label="Yes"></span>
                        <?php elseif (isset($feature->avail->free) && !$feature->avail->free) : ?>
                            <span class="dashicons dashicons-no-alt" aria-label="No"></span>
                        <?php endif ?>
                    </td>
                    <td style="text-align: center">
                        <?php if (isset($feature->avail->pro) && $feature->avail->pro) : ?>
                            <span class="dashicons dashicons-yes" aria-label="Yes"></span>
                        <?php elseif (isset($feature->avail->pro) && !$feature->avail->pro) : ?>
                            <span class="dashicons dashicons-no-alt" aria-label="No"></span>
                        <?php endif ?>
                        <br>
                        <?php if (isset($feature->button) && $feature->button) : ?>
                            <a class="button button-primary"
                               href="<?php echo wa_make_link('/product/wunderautomation-pro/', $utm)?>">
                                Upgrade to Pro
                            </a>
                        <?php endif ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </section>
</div>
