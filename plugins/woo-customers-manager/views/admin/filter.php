<?php
if ( ! defined('WPINC')) {
    die;
}
?>
<br>
<div class="alignleft actions">

    <div class="prm-users-filter">

        <div class="prm-users-filter__item">
            <div class="prm-users-filter__label">
                <?php _e('Filter by date', 'woo-customers-manager'); ?>
            </div>
            <div class="prm-users-filter__fields-group">
                <input class="prm-users-filter__field"
                       type="date"
                       name="registered_from"
                       id="registered_from"
                       value="<?= $registered_from; ?>"
                       placeholder="<?= __('Registered from', 'woo-customers-manager'); ?>"
                />
                <input class="prm-users-filter__field"
                       type="date"
                       name="registered_to"
                       id="registered_to"
                       value="<?= $registered_to; ?>"
                       placeholder="<?= __('Registered to', 'woo-customers-manager'); ?>"
                />
            </div>
        </div>

        <div class="prm-users-filter__item">
            <div class="prm-users-filter__label">
                <?= __('Filter by price', 'woo-customers-manager'); ?>
            </div>
            <div class="prm-users-filter__fields-group">
                <input class="prm-users-filter__field"
                       type="number"
                       name="money_spent_from"
                       id="money_spent_from"
                       step="any"
                       value="<?= $money_spent_from; ?>"
                       placeholder="<?= __('Money spent from', 'woo-customers-manager'); ?>"
                />

                <input class="prm-users-filter__field"
                       type="number"
                       name="money_spent_to"
                       id="money_spent_to"
                       step="any"
                       value="<?= $money_spent_to; ?>"
                       placeholder="<?= __('Money spent to', 'woo-customers-manager'); ?>"
                />
            </div>
        </div>

        <div class="prm-users-filter__item">
            <div class="prm-users-filter__fields-group">
                <input class="prm-users-filter__field button" type="submit"
                       value="<?= __('Filter', 'woo-customers-manager'); ?>">
                <a class="button prm-users-filter__field" href="users.php"><?= __('Reset filter', 'woo-customers-manager'); ?></a>
            </div>
        </div>

    </div>
</div>