<?php
$form_id = get_the_ID();
$form = new Give_Donate_Form($form_id);

$goal_option = give_get_meta($form->ID, '_give_goal_option', true);
$goal_progress_stats = give_goal_progress_stats($form);
$goal_format = $goal_progress_stats['format'];
$show_goal = $this->get_settings_for_display('show_form_goal');
$show_progress_bar = $this->get_settings_for_display('show_progress_bar');

$income = $form->get_earnings();
$goal = $goal_progress_stats['raw_goal'];

switch ($goal_format) {
    case 'donation':
        $progress = $goal ? round(($form->get_sales() / $goal) * 100, 2) : 0;
        $progress_bar_value = $form->get_sales() >= $goal ? 100 : $progress;
        break;

    case 'donors':
        $progress = $goal ? round((give_get_form_donor_count($form->ID) / $goal) * 100, 2) : 0;
        $progress_bar_value = give_get_form_donor_count($form->ID) >= $goal ? 100 : $progress;
        break;

    case 'percentage':
        $progress = $goal ? round(($income / $goal) * 100, 2) : 0;
        $progress_bar_value = $income >= $goal ? 100 : $progress;
        break;

    default:
        $progress = $goal ? round(($income / $goal) * 100, 2) : 0;
        $progress_bar_value = $income >= $goal ? 100 : $progress;
        break;
}

if($show_goal === 'yes'){
    ?>
    <div class="lakit-goal-progress">
        <?php
        if($show_progress_bar){
            echo sprintf('<div class="progress-percent">%1$s</div>', round($progress) . '%');
            echo sprintf('<div class="give-progress-bar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="%1$s"><span style="width:%2$s;"></span></div>', $progress_bar_value, $progress_bar_value . '%');
        }
        ?>
        <div class="raised">
                <?php
                if ('amount' === $goal_format) :

                    /**
                     * Filter the give currency.
                     *
                     * @since 1.8.17
                     */
                    $form_currency = apply_filters(
                        'give_goal_form_currency',
                        give_get_currency($form_id),
                        $form_id
                    );

                    /**
                     * Filter the income formatting arguments.
                     *
                     * @since 1.8.17
                     */
                    $income_format_args = apply_filters(
                        'give_goal_income_format_args',
                        [
                            'sanitize' => false,
                            'currency' => $form_currency,
                            'decimal' => false,
                        ],
                        $form_id
                    );

                    /**
                     * Filter the goal formatting arguments.
                     *
                     * @since 1.8.17
                     */
                    $goal_format_args = apply_filters(
                        'give_goal_amount_format_args',
                        [
                            'sanitize' => false,
                            'currency' => $form_currency,
                            'decimal' => false,
                        ],
                        $form_id
                    );

                    /**
                     * This filter will be used to convert the goal amounts to different currencies.
                     *
                     * @since 2.5.4
                     *
                     * @param array $amounts List of goal amounts.
                     * @param int $form_id Donation Form ID.
                     */
                    $goal_amounts = apply_filters(
                        'give_goal_amounts',
                        [
                            $form_currency => $goal,
                        ],
                        $form_id
                    );

                    /**
                     * This filter will be used to convert the income amounts to different currencies.
                     *
                     * @since 2.5.4
                     *
                     * @param array $amounts List of goal amounts.
                     * @param int $form_id Donation Form ID.
                     */
                    $income_amounts = apply_filters(
                        'give_goal_raised_amounts',
                        [
                            $form_currency => $income,
                        ],
                        $form_id
                    );

                    // Get human readable donation amount.
                    $income = give_human_format_large_amount(
                        give_format_amount($income, $income_format_args), ['currency' => $form_currency]
                    );
                    $goal = give_human_format_large_amount(
                        give_format_amount($goal, $goal_format_args),
                        ['currency' => $form_currency]
                    );

                    // Format the human readable donation amount.
                    $formatted_income = give_currency_filter(
                        $income,
                        [
                            'form_id' => $form_id,
                        ]
                    );

                    $formatted_goal = give_currency_filter(
                        $goal,
                        [
                            'form_id' => $form_id,
                        ]
                    );
                    echo sprintf(
                    /* translators: 1: amount of income raised 2: goal target amount. */
                        __('<span class="amount" data-amounts="%1$s">%2$s</span> of <span class="goal" data-amounts="%3$s">%4$s</span>','lastudio-kit'),
                        esc_attr(wp_json_encode($income_amounts, JSON_PRETTY_PRINT)),
                        esc_attr($formatted_income),
                        esc_attr(wp_json_encode($goal_amounts, JSON_PRETTY_PRINT)),
                        esc_attr($formatted_goal)
                    );

                elseif ('percentage' === $goal_format) :
                    echo sprintf( /* translators: %s: percentage of the amount raised compared to the goal target */
                        __('<span class="amount">%s%%</span> of <span class="goal">100&#37;</span>','lastudio-kit'),
                        round($progress)
                    );
                elseif ('donation' === $goal_format) :?>
                    <span class="amount"><?php echo give_format_amount($form->get_sales(), ['decimal' => false]) ?></span>
                    <span class="goal"><?php echo sprintf(
                            _n('of %s donation', 'of %s donations', $goal, 'give'),
                            give_format_amount($goal, ['decimal' => false])
                        ); ?></span>
                <?php
                elseif ('donors' === $goal_format) : ?>
                    <span class="amount"><?php echo give_get_form_donor_count($form->ID) ?></span>
                    <span class="goal"><?php
                        echo sprintf(
                            _n('of %s donor', 'of %s donors', $goal, 'give'),
                            give_format_amount($goal, ['decimal' => false])
                        ); ?></span>
                <?php
                endif ?>
        </div>
    </div>
    <?php
}