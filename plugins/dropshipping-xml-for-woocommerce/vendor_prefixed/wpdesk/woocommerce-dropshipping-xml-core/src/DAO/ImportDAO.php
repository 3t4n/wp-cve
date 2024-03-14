<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\DAO;

use DropshippingXmlFreeVendor\Monolog\Logger;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ImportFactory;
use WP_Post;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotFoundException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotSavedException;
/**
 * Class ImportDAO, data access object class for import settings.
 *
 * @package WPDesk\Library\DropshippingXmlCore\DAO
 */
class ImportDAO
{
    const UID = 'uid';
    const META_KEY_STATUS = 'status';
    const META_KEY_URL = 'url';
    const META_KEY_LAST_POSITION = 'last_position';
    const META_KEY_PRODUCTS_COUNT = 'products_count';
    const META_KEY_NEXT_IMPORT = 'next_import';
    const META_KEY_START_DATE = 'start_date';
    const META_KEY_END_DATE = 'end_date';
    const META_KEY_NODE_ELEMENT = 'node_element';
    const META_KEY_IMPORT_CREATED = 'created';
    const META_KEY_IMPORT_UPDATED = 'updated';
    const META_KEY_IMPORT_SKIPPED = 'skipped';
    const META_KEY_CRON_SCHEDULE = 'cron_schedule';
    const META_KEY_ERROR_MESSAGE = 'error_message';
    const META_KEY_IMPORT_NAME = 'import_name';
    /**
     * @var ImportFactory
     */
    private $file_import_factory;
    /**
     * @var Logger
     */
    private $logger;
    public function __construct(\DropshippingXmlFreeVendor\Monolog\Logger $logger, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Factory\ImportFactory $file_import_factory)
    {
        $this->logger = $logger;
        $this->file_import_factory = $file_import_factory;
    }
    public function add(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $entity)
    {
        \wc_transaction_query('start');
        $post_id = \wp_insert_post(['post_title' => \wp_strip_all_tags($entity->get_uid()), 'post_status' => 'publish', 'post_content' => ' ', 'post_author' => \get_current_user_id(), 'post_type' => $entity->get_post_type()], \true);
        if (!\is_wp_error($post_id)) {
            foreach ($this->get_meta_keys() as $key) {
                $method_name = 'get_' . \strtolower($key);
                if (\method_exists($entity, $method_name)) {
                    \update_post_meta($post_id, $key, $entity->{$method_name}());
                }
            }
            \wc_transaction_query('commit');
        } else {
            \wc_transaction_query('rollback');
            throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotSavedException('Error, imported file can\'t be saved');
        }
    }
    public function update(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import $entity)
    {
        foreach ($this->get_meta_keys() as $key) {
            $method_name = 'get_' . \strtolower($key);
            if (\method_exists($entity, $method_name)) {
                \update_post_meta($entity->get_id(), $key, $entity->{$method_name}());
            }
        }
    }
    public function is_uid_exists(string $uid) : bool
    {
        try {
            $this->find_by_uid($uid);
            return \true;
        } catch (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotFoundException $e) {
            return \false;
        }
    }
    public function find_by_id(int $id) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import
    {
        $post = $this->find_import_post_by_id($id);
        return $this->file_import_factory->create($this->fetch_properties_from_post($post));
    }
    public function find_by_uid(string $uid) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import
    {
        $posts = \get_posts(['post_type' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::POST_TYPE_SLUG, 'post_status' => 'publish', 'numberposts' => 1, 's' => $uid]);
        if (empty($posts)) {
            throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotFoundException('Import not exists');
        }
        return $this->file_import_factory->create($this->fetch_properties_from_post(\reset($posts)));
    }
    public function remove_by_id(int $id)
    {
        $post = $this->find_import_post_by_id($id);
        \wp_delete_post($post->ID, \true);
    }
    public function has_next_to_import() : bool
    {
        try {
            $this->find_next_to_import();
            return \true;
        } catch (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotFoundException $e) {
            $this->logger->notice($e->getMessage());
            return \false;
        }
    }
    public function find_next_to_import() : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import
    {
        $posts = \get_posts(['post_type' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::POST_TYPE_SLUG, 'post_status' => 'publish', 'numberposts' => 1, 'orderby' => self::META_KEY_START_DATE, 'order' => 'ASC', 'meta_query' => ['relation' => 'AND', ['key' => self::META_KEY_NEXT_IMPORT, 'value' => \current_time('timestamp'), 'compare' => '<'], ['key' => self::META_KEY_STATUS, 'value' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_WAITING, 'compare' => 'LIKE']]]);
        if (empty($posts)) {
            throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotFoundException('Import list is empty.');
        }
        return $this->file_import_factory->create($this->fetch_properties_from_post(\reset($posts)));
    }
    public function has_import_processing() : bool
    {
        try {
            $this->find_processing_import();
            return \true;
        } catch (\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotFoundException $e) {
            $this->logger->notice($e->getMessage());
            return \false;
        }
    }
    public function find_processing_import() : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import
    {
        $posts = \get_posts(['post_type' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::POST_TYPE_SLUG, 'post_status' => 'publish', 'numberposts' => 1, 'orderby' => self::META_KEY_START_DATE, 'order' => 'ASC', 'meta_query' => [['key' => self::META_KEY_STATUS, 'value' => [\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_WAITING, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::STATUS_STOPPED], 'compare' => 'NOT IN']]]);
        if (empty($posts)) {
            throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotFoundException('There is no import in progress.');
        }
        return $this->file_import_factory->create($this->fetch_properties_from_post(\reset($posts)));
    }
    /**
     * @param WP_Post $post
     *
     * @return array, assocciative array with meta keys (as keys) and meta values (as values).
     */
    private function fetch_properties_from_post(\WP_Post $post) : array
    {
        $post_values = ['id' => $post->ID, 'uid' => $post->post_title, 'date_created' => $post->post_date, 'user_id' => (int) $post->post_author];
        $meta_values = [];
        foreach ($this->get_meta_keys() as $key) {
            $value = \get_post_meta($post->ID, $key, \true);
            if (!empty($value)) {
                $meta_values[$key] = $value;
            }
        }
        return \array_merge($post_values, $meta_values);
    }
    /**
     * @return Import[]
     */
    public function get_all() : array
    {
        $result = [];
        $posts = \get_posts(['post_type' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::POST_TYPE_SLUG, 'post_status' => 'publish', 'numberposts' => -1]);
        foreach ($posts as $post) {
            $result[] = $this->file_import_factory->create($this->fetch_properties_from_post($post));
        }
        return $result;
    }
    private function get_meta_keys() : array
    {
        return [self::META_KEY_STATUS, self::META_KEY_URL, self::META_KEY_LAST_POSITION, self::META_KEY_PRODUCTS_COUNT, self::META_KEY_NEXT_IMPORT, self::META_KEY_START_DATE, self::META_KEY_END_DATE, self::META_KEY_NODE_ELEMENT, self::META_KEY_IMPORT_CREATED, self::META_KEY_IMPORT_UPDATED, self::META_KEY_IMPORT_SKIPPED, self::META_KEY_CRON_SCHEDULE, self::META_KEY_ERROR_MESSAGE, self::META_KEY_IMPORT_NAME];
    }
    private function find_import_post_by_id(int $id) : \WP_Post
    {
        $posts = \get_posts(['post__in' => [$id], 'post_type' => \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Entity\Import::POST_TYPE_SLUG, 'post_status' => 'publish', 'numberposts' => 1]);
        if (empty($posts)) {
            throw new \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception\NotFoundException('Import not exists');
        }
        return \reset($posts);
    }
}
