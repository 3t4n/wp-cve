<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Service\Scheduler;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields;
use DateTime;
use InvalidArgumentException;
/**
 * Class ImportSchedulerService
 * @package WPDesk\Library\DropshippingXmlCore\Service\Scheduler
 */
class ImportSchedulerService
{
    const DAYS_IN_WEEK = 7;
    /**
     * @var DataProviderFactory
     */
    private $data_provider_factory;
    /**
     * @var array
     */
    private $hours = array();
    /**
     * @var array
     */
    private $weekdays = array();
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\DataProviderFactory $data_provider_factory)
    {
        $this->data_provider_factory = $data_provider_factory;
    }
    public function get_formated_schedule(string $uid) : string
    {
        $weekdays = $this->get_weekdays($uid);
        $hours = $this->get_hours($uid);
        $weekdays_strings = \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::get_week_days();
        $new_weekdays = [];
        foreach ($weekdays as $day) {
            $new_weekdays[] = $weekdays_strings[$day];
        }
        return \implode(', ', $new_weekdays) . ' ' . \implode(', ', $hours);
    }
    public function estimate_time(string $uid) : int
    {
        $result = $this->get_default_estimated_time();
        $time_array = array();
        $weekdays = $this->get_weekdays($uid);
        $hours = $this->get_hours($uid);
        if (!empty($weekdays) && !empty($hours)) {
            foreach ($weekdays as $day) {
                foreach ($hours as $hour) {
                    $time_array[] = $this->calculate_estimated_time($day, $hour);
                }
            }
            if (!empty($time_array)) {
                \asort($time_array);
                $result = \reset($time_array);
            }
        }
        return $result;
    }
    private function calculate_estimated_time(int $day, string $hour)
    {
        $time = \explode(':', $hour);
        if (!isset($time[0]) || !isset($time[1]) || !\is_numeric($day)) {
            throw new \InvalidArgumentException('Error, invalid time parameters');
        }
        $dateTime = new \DateTime();
        $dateTime->setTimestamp(\current_time('timestamp'));
        $day_number = $dateTime->format('N');
        if ($day_number > $day) {
            $day_diff = self::DAYS_IN_WEEK - $day_number + $day;
        } elseif ($day_number < $day) {
            $day_diff = $day - $day_number;
        } else {
            $time_int = (float) $time[0] . $time[1];
            $now_int = (float) $dateTime->format('Hi');
            $day_diff = $time_int > $now_int ? 0 : 7;
        }
        $dateTime->modify('+ ' . $day_diff . ' days');
        $dateTime->setTime($time[0], $time[1]);
        return $dateTime->getTimestamp();
    }
    private function get_default_estimated_time() : int
    {
        $date_time = new \DateTime();
        $date_time->setTimestamp(\current_time('timestamp'));
        $date_time->modify('+ ' . self::DAYS_IN_WEEK . ' days');
        return $date_time->getTimestamp();
    }
    private function get_hours(string $uid) : array
    {
        if (isset($this->hours[$uid])) {
            return $this->hours[$uid];
        }
        $options_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider::class, ['postfix' => $uid]);
        $hours = $options_data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::CRON_HOURS) ? $options_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::CRON_HOURS) : array();
        $this->hours[$uid] = $hours;
        return $hours;
    }
    private function get_weekdays(string $uid) : array
    {
        if (isset($this->weekdays[$uid])) {
            return $this->weekdays[$uid];
        }
        $options_data_provider = $this->data_provider_factory->create_by_class_name(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DataProvider\ImportOptionsDataProvider::class, ['postfix' => $uid]);
        $weekdays = $options_data_provider->has(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::CRON_WEEK_DAY) ? $options_data_provider->get(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Form\Fields\ImportOptionsFormFields::CRON_WEEK_DAY) : array();
        $this->weekdays[$uid] = $weekdays;
        return $weekdays;
    }
}
