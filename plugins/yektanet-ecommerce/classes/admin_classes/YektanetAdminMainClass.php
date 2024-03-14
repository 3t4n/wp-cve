<?php

class YektanetAdminMainClass
{

    public function __construct()
    {
        if (!defined('count_of_products_to_show_in_charts')) define('count_of_products_to_show_in_charts', 10);
        $this->deleteOldData();
    }

    protected function sendErrorMessage($text)
    {
        echo "<h4 class='yektanet__setting__error__text'>$text</h4>";
    }

    protected function sendSuccessMessage($text)
    {
        echo "<h4 class='yektanet__setting__success__text'>$text</h4>";
    }

    protected function calculateOneDayForGetData(): int
    {
        return time() - 86400;
    }

    protected function calculateOneWeekForGetData(): int
    {
        return time() - 604800;
    }

    protected function calculateOneMonthForGetData(): int
    {
        return time() - 2629743;
    }

    protected function calculateThreeMonthForGetData(): int
    {
        return time() - 7889229;
    }

    protected function deleteOldData()
    {
        $three_month = $this->calculateThreeMonthForGetData();
        global $wpdb;
        $table = $wpdb->prefix . 'yektanet_products_views';
        $wpdb->query("DELETE FROM $table WHERE last_updated_time < $three_month");
    }
}