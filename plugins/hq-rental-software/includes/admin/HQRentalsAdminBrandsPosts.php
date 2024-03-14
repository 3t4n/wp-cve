<?php

namespace HQRentalsPlugin\HQRentalsAdmin;

use HQRentalsPlugin\HQRentalsModels\HQRentalsModelsBrand;

class HQRentalsAdminBrandsPosts
{
    public function __construct()
    {
        $this->brand = new HQRentalsModelsBrand();
        add_filter('manage_' . $this->brand->brandsCustomPostName . '_posts_columns', array($this, 'addNewColumnsOnBrandAdminScreen'));
        add_action('manage_' . $this->brand->brandsCustomPostName . '_posts_custom_column', array($this, 'displayBrandsDataOnAdminTable'), 10, 2);
    }

    public function addNewColumnsOnBrandAdminScreen($defaults)
    {
        return array(
            'title' => 'Name',
            'reservation_form_snippet' => 'Reservation Form Snippet',
            'reservation_snippet' => 'Reservation Snippet',
            'vehicle_class_calendar' => 'Vehicle Class Calendar',
            'date' => 'Date',
        );
    }

    public function displayBrandsDataOnAdminTable($columnName, $postId)
    {
        $currentBrand = new HQRentalsModelsBrand();
        $currentBrand->setBrandFromPostId($postId);
        switch ($columnName) {
            case ('reservation_form_snippet'):
                $this->resolveReservationFormSnippetShortcode($currentBrand);
                break;
            case ('reservation_snippet'):
                $this->resolveReservationSnippetShortcode($currentBrand);
                break;
            case ('vehicle_class_calendar'):
                $this->resolveCalendarShortcode($currentBrand);
                break;
            default:
                echo '';
                break;
        }
    }

    public function resolveShortcode($shortcodeContent)
    {
        ?>
        <div>
            <code>
                <?php echo $shortcodeContent; ?>
            </code>
        </div>
        <?php
    }

    public function resolveReservationFormSnippetShortcode($brand)
    {
        $this->resolveShortcode('[hq_rentals_reservation_form_snippet id=' . $brand->id . ']');
    }

    public function resolveReservationSnippetShortcode($brand)
    {
        $this->resolveShortcode('[hq_rentals_reservations_snippet id=' . $brand->id . ']');
    }

    public function resolveCalendarShortcode($brand)
    {
        ?>
        <div>
            <code>
                <?php echo '[hq_rentals_vehicle_calendar id=' . $brand->id . ']'; ?>
            </code>
        </div>
        <?php
    }

    public function resolveSnippets($brand)
    {
        ob_start();
        ?>
        <div>
            <div class="theme-actions">
                <a
                        id="hq-snippet-reservation-button"
                        class="hq-snippets"
                        data-brand="<?php echo $brand->id; ?>"
                        data-snippet="reservation"
                        data-tippy-content="Click to copy"
                >Reservations</a>
            </div>
            <div class="theme-actions">
                <a
                        id="hq-snippet-reservation-button"
                        class="hq-snippets"
                        data-brand="<?php echo $brand->id; ?>"
                        data-snippet="package"
                        data-tippy-content="Click to copy"
                >Package Quotes</a>
            </div>
            <div class="theme-actions">
                <a
                        id="hq-snippet-reservation-button"
                        class="hq-snippets"
                        data-brand="<?php echo $brand->id; ?>"
                        data-snippet="payment"
                        data-tippy-content="Click to copy"
                >Payment Requests</a>
            </div>
            <div class="theme-actions">
                <a
                        id="hq-snippet-reservation-button"
                        class="hq-snippets"
                        data-brand="<?php echo $brand->id; ?>"
                        data-snippet="quote"
                        data-tippy-content="Click to copy"
                >Quotes</a>
            </div>
        </div>
        <?php
        $content = ob_get_contents();
        ob_end_clean();
        echo $content;
    }
}
