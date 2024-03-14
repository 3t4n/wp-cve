<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity;

use InvalidArgumentException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Entity\Traits\EntityUpdater;
/**
 * Class Import, import entity.
 *
 * @package WPDesk\Library\DropshippingXmlCore\Entity
 */
class Import
{
    use EntityUpdater;
    const POST_TYPE_SLUG = 'dropshipping_import';
    const STATUS_WAITING = 'waiting';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_DOWNLOADING = 'downloading';
    const STATUS_CONVERTING = 'converting';
    const STATUS_CLEANING = 'cleaning';
    const STATUS_STOPPED = 'stopped';
    const STATUS_ERROR = 'error';
    /**
     * @var int
     */
    private $id = 0;
    /**
     * @var int
     */
    private $user_id = 0;
    /**
     * @var string
     */
    private $uid = '';
    /**
     * @var string
     */
    private $url = '';
    /**
     * @var int
     */
    private $start_date = 0;
    /**
     * @var int
     */
    private $end_date = 0;
    /**
     * @var int
     */
    private $next_import = 0;
    /**
     * @var int
     */
    private $last_position = 0;
    /**
     * @var int
     */
    private $products_count = 0;
    /**
     * @var string
     */
    private $node_element = '';
    /**
     * @var string
     */
    private $status = self::STATUS_WAITING;
    /**
     * @var int
     */
    private $created = 0;
    /**
     * @var int
     */
    private $updated = 0;
    /**
     * @var int
     */
    private $skipped = 0;
    /**
     * @var string
     */
    private $date_created = '';
    /**
     * @var string
     */
    private $cron_schedule = '';
    /**
     * @var string
     */
    private $error_message = '';
    /**
     * @var string
     */
    private $import_name = '';
    public function get_user_id() : int
    {
        return $this->user_id;
    }
    public function set_user_id(int $user_id)
    {
        $this->user_id = $user_id;
    }
    public function is_cleaner_started() : bool
    {
        return $this->status === self::STATUS_CLEANING;
    }
    public function is_cleaner_finished() : bool
    {
        return $this->status !== self::STATUS_CLEANING;
    }
    public function get_next_import() : int
    {
        return $this->next_import;
    }
    public function set_next_import(int $next_import)
    {
        $this->next_import = $next_import;
    }
    public function get_start_date() : int
    {
        return $this->start_date;
    }
    public function set_start_date(int $start_date)
    {
        $this->start_date = $start_date;
    }
    public function get_end_date() : int
    {
        return $this->end_date;
    }
    public function set_end_date(int $end_date)
    {
        $this->end_date = $end_date;
    }
    public function get_id() : int
    {
        return $this->id;
    }
    public function set_id(int $id)
    {
        $this->id = $id;
    }
    public function get_uid() : string
    {
        return $this->uid;
    }
    public function set_uid(string $uid)
    {
        $this->uid = $uid;
    }
    public function get_url() : string
    {
        return $this->url;
    }
    public function set_url(string $url)
    {
        $this->url = $url;
    }
    public function get_last_position() : int
    {
        return $this->last_position;
    }
    public function set_last_position(int $last_position)
    {
        $this->last_position = $last_position;
    }
    public function get_status() : string
    {
        return $this->status;
    }
    public function set_status(string $status)
    {
        if (!\in_array($status, $this->get_statuses())) {
            throw new \InvalidArgumentException('Error, wrong status given');
        }
        $this->status = $status;
    }
    public function set_node_element(string $element)
    {
        $this->node_element = $element;
    }
    public function get_node_element() : string
    {
        return $this->node_element;
    }
    public function set_products_count(int $count)
    {
        $this->products_count = $count;
    }
    public function get_products_count() : int
    {
        return $this->products_count;
    }
    public function set_updated(int $count)
    {
        $this->updated = $count;
    }
    public function get_updated() : int
    {
        return $this->updated;
    }
    public function set_created(int $count)
    {
        $this->created = $count;
    }
    public function get_created() : int
    {
        return $this->created;
    }
    public function set_skipped(int $count)
    {
        $this->skipped = $count;
    }
    public function get_skipped() : int
    {
        return $this->skipped;
    }
    public function add_to_created(int $number)
    {
        $this->created += $number;
    }
    public function add_to_updated(int $number)
    {
        $this->updated += $number;
    }
    public function add_to_skipped(int $number)
    {
        $this->skipped += $number;
    }
    public function add_to_last_position(int $number)
    {
        $this->last_position += $number;
    }
    public function get_post_type() : string
    {
        return self::POST_TYPE_SLUG;
    }
    public function get_progress() : int
    {
        $result = 0;
        if (!empty($this->get_products_count())) {
            return \intval($this->get_last_position() / $this->get_products_count() * 100);
        }
        return $result;
    }
    public function get_formated_progress() : string
    {
        return \strval(\number_format($this->get_progress(), 0)) . '%';
    }
    public function set_date_created(string $date)
    {
        $this->date_created = $date;
    }
    public function get_date_created() : string
    {
        return $this->date_created;
    }
    public function set_cron_schedule(string $schedule)
    {
        $this->cron_schedule = $schedule;
    }
    public function get_cron_schedule() : string
    {
        return $this->cron_schedule;
    }
    public function set_error_message(string $message)
    {
        $this->error_message = $message;
    }
    public function get_error_message() : string
    {
        return $this->error_message;
    }
    public function set_import_name(string $name)
    {
        $this->import_name = $name;
    }
    public function get_import_name() : string
    {
        return $this->import_name;
    }
    private function get_statuses() : array
    {
        return [self::STATUS_WAITING, self::STATUS_IN_PROGRESS, self::STATUS_DOWNLOADING, self::STATUS_CONVERTING, self::STATUS_CLEANING, self::STATUS_STOPPED, self::STATUS_ERROR];
    }
}
