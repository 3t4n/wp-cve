<?php
/** 
 * @package     VikAppointments - Libraries
 * @subpackage  html.license
 * @author      E4J s.r.l.
 * @copyright   Copyright (C) 2021 E4J s.r.l. All Rights Reserved.
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * @link        https://vikwp.com
 */

// No direct access
defined('ABSPATH') or die('No script kiddies please!');

$view = $displayData['view'];

$lookup = array(
    'acl' => array(
        'title' => JText::translate('VAPACLMENUTITLE'),
        'desc'  => __('Define the permissions for each user role to allow or deny the access to certain pages and the actions they can perform.', 'vikappointments'),
    ),

    'customers' => array(
        'title' => JText::translate('VAPMAINTITLEVIEWCUSTOMERS'),
        'desc'  => __('Here you can manage all of your customers information, their documents and send specific SMS notifications.', 'vikappointments'),
    ),

    'editconfigemp' => array(
        'title' => JText::translate('VAPMAINTITLECONFIG'),
        'desc'  => __('Configure your website as a portal of employees, which will be able to manage all their stuff through a private area in the front-end.', 'vikappointments'),
    ),

    'editconfigcron' => array(
        'title' => JText::translate('VAPMAINTITLEVIEWCRONJOBS'),
        'desc'  => __('Schedule some procedures to be executed periodically, such as SMS and E-mail reminders.', 'vikappointments'),
    ),

    'invoices' => array(
        'title' => JText::translate('VAPMAINTITLEVIEWARCHIVE'),
        'desc'  => __('Manage all the invoices generated for the various appointments, packages and subscriptions.', 'vikappointments'),
    ),

    'locations' => array(
        'title' => JText::translate('VAPMAINTITLEVIEWLOCATIONS'),
        'desc'  => __('Manage here the locations in which your employees perform their services.', 'vikappointments'),
    ),

    'options' => array(
        'title' => JText::translate('VAPMAINTITLEVIEWOPTIONS'),
        'desc'  => __('Offer additional options to be purchased during the booking process of the appointments.', 'vikappointments'),
    ),

    'payments' => array(
        'title' => JText::translate('VAPMAINTITLEVIEWPAYMENTS'),
        'desc'  => __('Allow your guests to pay their appointments online through your preferred bank gateway. The Pro version comes with an integration for PayPal Standard and two more payment solutions, but the framework could be extended by installing apposite payment plugins for VikAppointments for your preferred bank.', 'vikappointments'),
    ),

    'rates' => array(
        'title' => JText::translate('VAPMAINTITLEVIEWSPECIALRATES'),
        'desc'  => __('Apply automated surcharges or discounts based on several conditions, such as the number of participants, the check-in date/time, the service type and so on.', 'vikappointments'),
    ),

    'restrictions' => array(
        'title' => JText::translate('VAPMAINTITLEVIEWSPECIALRESTR'),
        'desc'  => __('Restrict the total number of appointments that a customer can book within an established range of time, such as at most 3 appointments per week.', 'vikappointments'),
    ),

    'reviews' => array(
        'title' => JText::translate('VAPMAINTITLEVIEWREVIEWS'),
        'desc'  => __('Start collecting your own customers reviews for your services and employees.', 'vikappointments'),
    ),
);

if (!isset($lookup[$view]))
{
    return;
}

// set up toolbar title
JToolbarHelper::title($lookup[$view]['title']);

if (empty($lookup[$view]['image']))
{
    // use the default logo image
    $lookup[$view]['image'] = 'vikwp-lite-logo.png';
}

?>

<div class="vap-free-nonavail-wrap">

    <div class="vap-free-nonavail-inner">

        <div class="vap-free-nonavail-logo">
            <img src="<?php echo VIKAPPOINTMENTS_CORE_MEDIA_URI . 'images/' . $lookup[$view]['image']; ?>">
        </div>

        <div class="vap-free-nonavail-expl">
            <h3><?php echo preg_replace("/Vik\s*Appointments - /i", '', $lookup[$view]['title']); ?></h3>

            <p class="vap-free-nonavail-descr"><?php echo $lookup[$view]['desc']; ?></p>

            <p class="vap-free-nonavail-footer-descr">
                <a href="admin.php?page=vikappointments&amp;view=gotopro" class="btn vap-free-nonavail-gopro">
                    <i class="fas fa-rocket"></i>
                    <span><?php _e('Upgrade to PRO', 'vikappointments'); ?></span>
                </a>
            </p>
        </div>

    </div>

</div>
