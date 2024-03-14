<?php

namespace FluentSupport\App\Api\Classes;

use FluentSupport\App\Models\TicketTag;

/**
 *  Tags class for PHP API
 *
 * Example Usage: $ticketTagsApi = FluentSupportApi('tags');
 *
 * @package FluentSupport\App\Api\Classes
 *
 * @version 1.0.0
 */
class Tags
{
    private $instance = null;

    private $allowedInstanceMethods = [
        'all',
        'get',
        'find',
        'first',
        'paginate'
    ];

    public function __construct(TicketTag $instance)
    {
        $this->instance = $instance;
    }

    /**
     * getTags method will returns all available tags
     */
    public function getTags()
    {
        TicketTag::paginate();
    }

    /**
     * getTag method returns a specific tag by id
     * @param int $id
     */
    public function getTag(int $id)
    {
        if (!$id) {
            return;
        }

        TicketTag::findOrFail($id);
    }

    /**
     * createTag method will create a new tag
     * @param array $data
     */
    public function createTag(array $data)
    {
        if (empty($data['title'])) {
            return;
        }
        TicketTag::create(wp_unslash($data));
    }

    /**
     * updateTag method will update tag by id
     * @param int $id
     * @param array $data
     */
    public function updateTag(int $id, array $data)
    {
        if (!$id || !$data) {
            return;
        }
        TicketTag::findOrFail($id)->update($data);
    }

    /**
     * deleteTag method will delete tag by id
     * @param int $id
     */
    public function deleteTag(int $id)
    {
        if (!$id) {
            return;
        }
        TicketTag::findOrFail($id)->delete();
    }

    public function getInstance()
    {
        return $this->instance;
    }

    public function __call($method, $params)
    {
        if (in_array($method, $this->allowedInstanceMethods)) {
            return call_user_func_array([$this->instance, $method], $params);
        }

        throw new \Exception("Method {$method} does not exist.");
    }
}
