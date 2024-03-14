<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Wordpress\CustomField;

/** @internal */
abstract class RegisterFieldGroup implements RegisterFieldGroupInterface
{
    /**
     * Meta box ID (used in the 'id' attribute for the meta box).
     *
     * @var string
     * @link https://developer.wordpress.org/reference/functions/add_meta_box/
     */
    protected string $key;
    /**
     * The screen or screens on which to show the box
     * (such as a post type, 'link', or 'comment').
     *
     * @var string[]
     * @link https://developer.wordpress.org/reference/functions/add_meta_box/
     */
    protected array $screen = [];
    /**
     * Position where group is placed
     *
     * @var string
     * @link    https://developer.wordpress.org/reference/functions/add_meta_box/
     * @example 'normal', 'side' and 'advanced
     */
    protected string $context = 'normal';
    /**
     * Fields which is going to display
     * Please, use classes
     *
     * @see RegisterField
     * @var array
     */
    protected array $fields = [];
    /**
     * The priority within the context where the boxes should show.
     *
     * @var mixed
     * @example 'high', 'low'
     */
    protected string $priority = 'default';
    /**
     * Title of the meta box.
     *
     * @return string
     * @required
     */
    protected abstract function title() : string;
    /**
     * magic get for all params
     *
     * @param $key
     *
     * @return mixed
     */
    public final function __get($key)
    {
        return $this->{$key};
    }
    /**
     * RegisterFieldGroup constructor.
     */
    public function __construct()
    {
        $this->setFields();
    }
    /**
     * Make screens to recognize Class::class or 'post_type'
     *
     * @return array
     * @throws \Exception
     */
    protected final function screen() : array
    {
        if (\count($this->screen) === 0) {
            throw new \Exception('Screen is not defined in class: ' . __CLASS__);
        }
        foreach ($this->screen as $key => $screen) {
            if (\class_exists($screen)) {
                $this->screen[$key] = \Modular\ConnectorDependencies\app()->make($screen)->postType();
            }
        }
        return $this->screen;
    }
    /**
     * Merge all the data in an array
     *
     * @return $this
     */
    public final function setFields() : self
    {
        $fields = [];
        foreach ($this->fields as $field) {
            /**
             * @var RegisterField $field
             */
            $field = \Modular\ConnectorDependencies\app()->make($field);
            $field->fieldGroup = $this;
            $post = request()->get('post', null);
            if (request()->method() === 'POST' && !empty(request()->get('post_ID'))) {
                $post = request()->get('post_ID');
            }
            $field->post = \get_post($post);
            /**
             * @see RegisterField::setKey
             */
            $field->setKey();
            $fields[] = $field;
        }
        $this->fields = $fields;
        return $this;
    }
    /**
     * Display fields on view
     *
     * @param $post
     * @param array|mixed $options
     *
     * @return void
     * @throws \Throwable
     */
    public final function render($post, $options) : void
    {
        /**
         * @internal
         * @var $id
         * @var $title
         * @var $callback
         * @var $args
         */
        \extract($options);
        foreach ($args as $field) {
            /**
             * @see RegisterField::view
             */
            echo $field->view()->render();
        }
    }
    /**
     * Save data set on the custom fields
     */
    public final function saveFieldValue() : void
    {
        // Bail if we're doing an auto save
        if (\defined('DOING_AUTOSAVE') && \DOING_AUTOSAVE) {
            return;
        }
        if (request('action') === 'inline-save' && request('post_view') === 'list') {
            return;
        }
        if (\function_exists('get_current_screen') && isset(\get_current_screen()->post_type) && \in_array(\get_current_screen()->post_type, $this->screen())) {
            foreach ($this->fields as $field) {
                /**
                 * @see RegisterField::save
                 */
                $field->save();
            }
        }
    }
    /**
     * Add group to screen
     *
     * @return void
     * @throws \Exception
     */
    public final function load() : void
    {
        \add_meta_box($this->key, $this->title(), [$this, 'render'], $this->screen(), $this->context, $this->priority, $this->fields);
    }
    /**
     * Init process to add new group of custom fields
     *
     * @return void
     */
    public function register() : void
    {
        \add_action('add_meta_boxes', [$this, 'load']);
        \add_action('save_post', [$this, 'saveFieldValue']);
    }
}
