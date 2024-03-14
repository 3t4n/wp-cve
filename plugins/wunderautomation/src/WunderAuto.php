<?php

namespace WunderAuto;

use WunderAuto\Settings\BaseSettings;
use WunderAuto\Types\Actions\BaseAction;
use WunderAuto\Types\BaseWorkflowEntity;
use WunderAuto\Types\Filters\BaseFilter;
use WunderAuto\Types\Parameters\BaseParameter;
use WunderAuto\Types\ReTrigger;
use WunderAuto\Types\Triggers\BaseTrigger;
use WunderAuto\Types\Workflow;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 */
class WunderAuto
{
    /**
     * The post types we keep track of
     *
     * @var array<int, String>
     */
    public $postTypes = ['automation-workflow', 'automation-retrigger'];

    /**
     * @var string
     */
    private $pluginName;

    /**
     * @var string
     */
    private $version;

    /**
     * @var Loader
     */
    private $loader;

    /**
     * @var array<string, string|BaseTrigger>
     */
    private $triggers;

    /**
     * @var array<string, string|BaseFilter>
     */
    private $filters;

    /**
     * @var array<string, string|BaseAction>
     */
    private $actions;

    /**
     * @var Resolver
     */
    private $currentResolver;

    /**
     * @var array<string, string|BaseParameter>
     */
    private $parameters;

    /**
     * @var array<string, BaseParameter>
     */
    private $parametersByTitle;

    /**
     * @var array<string, \stdClass>
     */
    private $objectTypes;

    /**
     * @var array<string, string|BaseSettings>
     */
    private $settings;

    /**
     * @var array<int, Workflow>
     */
    private $workflows;

    /**
     * @var Scheduler|null
     */
    private $scheduler;

    /**
     * @var bool
     */
    private $isPro = false;

    /**
     * @var array<int, string>
     */
    private $createdTypeObjects = [];

    /**
     * @param string $pluginName
     * @param string $version
     */
    public function __construct($pluginName, $version)
    {
        $this->pluginName  = $pluginName;
        $this->version     = $version;
        $this->workflows   = [];
        $this->objectTypes = [];
    }

    /**
     * @param Loader    $loader
     * @param Scheduler $scheduler
     * @param bool      $isPro
     *
     * @return void
     */
    public function register($loader, $scheduler, $isPro)
    {
        $this->loader    = $loader;
        $this->scheduler = $scheduler;
        $this->isPro     = $isPro;

        $this->loader->addAction('init', $this, 'pluginsLoaded');
        $this->loader->addAction('init', $this, 'afterPluginsLoaded', PHP_INT_MAX);
        $this->loader->addAction('wunderauto_remove_expired_links', $this, 'removeExpiredLinks');
    }

    /**
     * Handle plugin setup
     *
     * @return void
     */
    public function pluginsLoaded()
    {
        // Register all native classes
        $this->loader->addWunderAutoObjects();

        $this->loadAddOns();

        $this->addObjectType('post', 'A WordPress post', true);
        $this->addObjectType('user', 'A WordPress user', true);
        $this->addObjectType('comment', 'A WordPress comment', true);
        $this->addObjectType('order', 'A WooCommerce order', true);
        $this->addObjectType('webhook', 'Webhook object', false);

        // Let plugins register their objects
        do_action('wunderautomation_init');

        // Ask trigger that is used in an active workflow to
        // register hooks
        $workflows = $this->getWorkflows();
        foreach ($workflows as $workflow) {
            if ($workflow->isActive()) {
                $class = $workflow->getTriggerClass();
                // Is this a registered Trigger class?
                if (!isset($this->triggers[$class])) {
                    continue;
                }

                // Is it already instantiated?
                $obj = $this->triggers[$class];
                if ($obj instanceof BaseTrigger) {
                    continue;
                }

                // Create the object
                $obj = new $class();
                if (!($obj instanceof BaseTrigger)) {
                    continue;
                }

                // Store it and initialize
                $this->triggers[$class] = $obj;
                $obj->initialize();
                $obj->setWorkflow($workflow);
                $obj->registerHooks();
            }
        }
    }

    /**
     * Register known object types and their classes.
     * These are the objects that we use a parameter object
     * to access during runtime.
     *
     * @param string      $type
     * @param string      $description
     * @param bool        $transfer
     * @param string|null $parent
     *
     * @return void
     */
    public function addObjectType($type, $description, $transfer = true, $parent = null)
    {
        $this->objectTypes[$type] = (object)[
            'type'        => $type,
            'description' => $description,
            'transfer'    => $transfer,
            'parent'      => $parent,
        ];
    }

    /**
     * Return all active Workflows
     *
     * @return array<int, Workflow>
     */
    public function getWorkflows()
    {
        if (empty($this->workflows)) {
            $posts = $this->getWorkflowPosts();
            foreach ($posts as $post) {
                $this->workflows[] = $this->createWorkflowObject($post->ID);
            }
        }

        return $this->workflows;
    }

    /**
     * Return all active ReTriggers
     *
     * @return array<int, ReTrigger>
     */
    public function getReTriggers()
    {
        $args = [
            'post_type'   => 'automation-retrigger',
            'numberposts' => -1,
        ];

        $posts = array_filter(get_posts($args), function ($el) {
            return $el instanceof \WP_Post;
        });

        $ret = [];
        foreach ($posts as $post) {
            $ret[] = $this->createReTriggerObject($post->ID);
        }

        return $ret;
    }

    /**
     * @param bool|null   $active
     * @param string|null $class
     *
     * @return array<\WP_Post>
     */
    public function getWorkflowPosts($active = null, $class = null)
    {
        $args = [
            'post_type'   => 'automation-workflow',
            'numberposts' => -1,
            'order'       => 'asc',
            'orderby'     => ['order_clause' => 'ASC'],
            'meta_query'  => [
                'order_clause' => [
                    'key'     => 'sortorder',
                    'compare' => 'EXISTS',
                ],
            ],
        ];

        if (!is_null($active)) {
            $args['meta_query']['active_clause'] = [
                'key'   => 'active',
                'value' => $active ? 'active' : 'disabled',
            ];
        }

        if (!is_null($class)) {
            $args['meta_query']['class_clause'] = [
                'key'   => 'workflow_trigger',
                'value' => str_replace('\\', '|', $class),
            ];
        }

        $ret = array_filter(get_posts($args), function ($el) {
            return $el instanceof \WP_Post;
        });
        return $ret;
    }

    /**
     * Create a new Workflow object
     *
     * @param int $workflowId
     *
     * @return Workflow
     */
    public function createWorkflowObject($workflowId)
    {
        $workflow = new Workflow($workflowId);
        $workflow->getState();
        return $workflow;
    }

    /**
     * Create a new ReTrigger object
     *
     * @param int $reTriggerId
     *
     * @return ReTrigger
     */
    public function createReTriggerObject($reTriggerId)
    {
        $reTrigger = new ReTrigger($reTriggerId);
        $reTrigger->getState();
        return $reTrigger;
    }

    /**
     * Create a new Workflow object based on guid
     *
     * @param string $guid
     *
     * @return Workflow|null
     */
    public function getWorkflowObjectByGuid($guid)
    {
        $args = [
            'post_type'   => 'automation-workflow',
            'numberposts' => 1,
            'meta_query'  => [
                [
                    'key'     => 'guid',
                    'value'   => $guid,
                    'compare' => '='
                ],
            ],
        ];

        $posts = array_filter(get_posts($args), function ($el) {
            return $el instanceof \WP_Post;
        });

        if (empty($posts[0])) {
            return null;
        }

        return $this->createWorkflowObject($posts[0]->ID);
    }

    /**
     * Do actions that should takes place after all other plugins have
     * initialized
     *
     * @return void
     */
    public function afterPluginsLoaded()
    {
        do_action('wunderautomation_init_done');
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return string The name of the plugin.
     */
    public function getPluginName()
    {
        return $this->pluginName;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return Loader Orchestrates the hooks of the plugin.
     */
    public function getLoader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return string The version number of the plugin.
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Group objects based on their group property
     *
     * @param array<string, BaseWorkflowEntity> $objects
     *
     * @return array<string, array<int, \stdClass>>
     */
    public function getGroups($objects)
    {
        $ret = [];

        foreach ($objects as $key => $object) {
            $group = (string)$object->group;
            if (!isset($ret[$group])) {
                $ret[$group] = [];
            }

            $ret[$group][] = (object)['title' => $object->title, 'class' => $key];
        }

        return $ret;
    }

    /**
     * @param string $type
     * @param string $class
     *
     * @return void
     */
    public function addObject($type, $class)
    {
        switch ($type) {
            case 'trigger':
                $this->triggers[$class] = $class;
                break;
            case 'filter':
                $this->filters[$class] = $class;
                break;
            case 'action':
                $this->actions[$class] = $class;
                break;
            case 'parameter':
                $this->parameters[$class] = $class;
                break;
            case 'settings':
                $this->settings[$class] = $class;
                break;
        }
    }

    /**
     * @param string $type
     * @param string $class
     *
     * @return BaseWorkflowEntity|BaseSettings|null
     */
    public function getObject($type, $class)
    {
        $arr = [];
        switch ($type) {
            case 'trigger':
                $arr = &$this->triggers;
                break;
            case 'filter':
                $arr = &$this->filters;
                break;
            case 'action':
                $arr = &$this->actions;
                break;
            case 'parameter':
                $arr = &$this->parameters;
                break;
            case 'settings':
                $arr = &$this->settings;
                break;
        }

        if (!isset($arr[$class])) {
            return null;
        }

        if ($arr[$class] instanceof BaseWorkflowEntity || $arr[$class] instanceof BaseSettings) {
            return $arr[$class];
        }

        /** @var BaseWorkflowEntity|BaseSettings $obj */
        $obj = new $class();
        if (($obj instanceof BaseTrigger) || ($obj instanceof BaseFilter)) {
            $obj->initialize();
        }

        $arr[$class] = $obj;
        if ($obj instanceof BaseParameter) {
            $this->parametersByTitle[$obj->objects[0] . '.' . $obj->title] = $obj;
        }

        return $arr[$class];
    }

    /**
     * @param string $type
     *
     * @return array<string, BaseWorkflowEntity> Array of instantiated objects of $type
     */
    public function getObjects($type)
    {
        $ret = [];
        switch ($type) {
            case 'trigger':
                $this->ensureObjectTypes($type, $this->triggers);
                $ret = $this->triggers;
                break;
            case 'filter':
                $this->ensureObjectTypes($type, $this->filters);
                $ret = $this->filters;
                break;
            case 'action':
                $this->ensureObjectTypes($type, $this->actions);
                $ret = $this->actions;
                break;
            case 'parameter':
                $this->ensureObjectTypes($type, $this->parameters);
                $ret = $this->parameters;
                break;
        }

        return apply_filters('wunderauto/getobjects/' . $type, $ret);
    }

    /**
     * @return array<string, BaseSettings>
     */
    public function getSettings()
    {
        $this->ensureObjectTypes('settings', $this->settings);
        $settings = array_filter($this->settings, function ($el) {
            return $el instanceof BaseSettings;
        });

        usort($settings, function ($a, $b) {
            return $a->sortOrder < $b->sortOrder ? -1 : 1;
        });

        return $settings;
    }

    /**
     * Return all registered object types
     *
     * @return array<string, object>
     */
    public function getObjectTypes()
    {
        return $this->objectTypes;
    }

    /**
     * Reset / clear the internal workflows array
     * Forces subsequent calls to getWorkflows to reread
     * from DB
     *
     * @return void
     */
    public function resetWorkflows()
    {
        $this->workflows = [];
        wp_cache_flush();
    }

    /**
     * Create the resolver object
     *
     * @param array<string, \stdClass> $objects
     *
     * @return Resolver
     */
    public function createResolver($objects)
    {
        $this->ensureObjectTypes('parameter', $this->parameters);
        $this->currentResolver = new Resolver($objects, $this->parametersByTitle, $this->objectTypes);
        return $this->currentResolver;
    }

    /**
     * @return Resolver
     */
    public function getCurrentResolver()
    {
        return $this->currentResolver;
    }

    /**
     * Create an empty WP_User
     *
     * @return \WP_User
     */
    public function createEmptyWpUser()
    {
        return new \WP_User();
    }

    /**
     * Get the global scheduler object
     *
     * @return Scheduler
     */
    public function getScheduler()
    {
        if (is_null($this->scheduler)) {
            $this->scheduler = new Scheduler();
        }

        return $this->scheduler;
    }

    /**
     * Remove old expired links
     *
     * @return void
     */
    public function removeExpiredLinks()
    {
        $wpdb = wa_get_wpdb();

        $sql = "delete from {$wpdb->prefix}wa_confirmationlinks
                    WHERE expires > 0 AND expires < DATE_SUB(NOW(), INTERVAL 30 DAY);";
        $wpdb->query($sql);
    }

    /**
     * @param string $tagBase
     * @param object $filterObject
     *
     * @return array<int, string>
     */
    public function getWordPressFilterTags($tagBase, $filterObject)
    {
        $tags         = [];
        $classParents = class_parents($filterObject);
        $classes      = array_merge(
            [get_class($filterObject)],
            array_values($classParents ? $classParents : [])
        );
        foreach ($classes as $class) {
            if ($class === 'WunderAuto\\Types\\Filters\\BaseFilter') {
                continue;
            }
            // Some heuristics to get decent filter tags
            $class = str_replace('WunderAuto\\', '', $class);
            $class = str_replace('Types\\', '', $class);
            $class = str_replace('Filters\\', '', $class);
            $class = str_replace('\\', '/', $class);
            $class = strtolower($class);

            $tags[] = $tagBase . '/' . $class;
        }

        return $tags;
    }

    /**
     * Pro status
     *
     * @return bool
     */
    public function isPro()
    {
        return $this->isPro;
    }

    /**
     * Load addons
     *
     * @return void
     */
    private function loadAddOns()
    {
        // Check if we have any registered addons and let them initialize
        $addons = ['wunderautomation-test/wunderautomation-test.php'];
        foreach ($addons as $addon) {
            $addonFile = trailingslashit(dirname(__DIR__)) . "addons/$addon";

            if (!file_exists($addonFile)) {
                continue;
            }

            wa_require_once($addonFile);
        }
    }

    /**
     * Lazy load the various type objects.
     *
     * @param string                       $type
     * @param array<string, object|string> $arr
     *
     * @return void
     */
    private function ensureObjectTypes($type, &$arr)
    {
        if (empty($arr)) {
            return;
        }

        if (!in_array($type, $this->createdTypeObjects)) {
            foreach ($arr as $class => $obj) {
                $this->getObject($type, $class);
            }
            $this->createdTypeObjects[] = $type;
        }
    }
}
