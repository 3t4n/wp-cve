<?php

namespace Modular\ConnectorDependencies\Ares\Framework\Wordpress\CustomField;

use Modular\ConnectorDependencies\Illuminate\Support\Facades\View;
/** @internal */
abstract class RegisterField implements RegisterFieldInterface
{
    /**
     * Field identifier
     *
     * @var string
     */
    protected string $key;
    /**
     * Field type
     *
     * @var string
     * @example text, date, dropdown, checkbox
     */
    protected string $type = 'text';
    /**
     * Set if the field must be completed
     *
     * @var bool
     */
    protected bool $required = \true;
    /**
     * Dynamic var to get group key and create unique name for field
     *
     * @internal
     * @var RegisterFieldGroupInterface|null
     */
    protected ?RegisterFieldGroupInterface $fieldGroup = null;
    /**
     * @var
     */
    protected ?\WP_Post $post = null;
    /**
     * Title of the field
     *
     * @return mixed
     */
    protected abstract function label() : string;
    /**
     * More information about the correct use of the field
     *
     * @return string
     */
    protected abstract function instructions() : string;
    /**
     * Values on multiple options type fields
     * like dropdown
     *
     * @return \Illuminate\Support\Collection|array
     */
    protected function options()
    {
        return [];
    }
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
     * @param $name
     * @param $value
     *
     * @return void
     */
    public final function __set($name, $value) : void
    {
        $this->{$name} = $value;
    }
    /**
     * Get last value saved on current post
     *
     * @param $post
     *
     * @return mixed
     */
    public function getValue($post)
    {
        if (isset($post)) {
            return \get_post_meta($post->ID, $this->key, \true);
        }
        return \false;
    }
    /**
     * Get view based on field type
     *
     * @return string
     */
    public function getView() : string
    {
        return 'ares::fields.' . $this->type;
    }
    /**
     * Set name field for HTML form
     *
     * @return $this
     */
    public function setKey() : self
    {
        $this->key = $this->key;
        return $this;
    }
    /**
     * Get view based on field type
     *
     * @return mixed
     */
    public function view()
    {
        $view = $this->getView();
        if (!View::exists($this->getView())) {
            throw new \InvalidArgumentException("View [{$view}] not found.");
        }
        return View::make($view, ['post' => $this->post, 'field' => $this->mergeData(), 'value' => $this->getValue($this->post)]);
    }
    /**
     * Save data, overwriting last data
     *
     * @return void
     */
    public function save() : void
    {
        $value = request()->input($this->key);
        if (isset($this->post->ID)) {
            \update_post_meta($this->post->ID, $this->key, $value, $this->getValue($this->post));
        }
    }
    /**
     * Merge all data in a single array
     *
     * @return array
     */
    public function mergeData() : array
    {
        return ['type' => $this->type, 'key' => $this->key, 'label' => $this->label(), 'instructions' => $this->instructions(), 'required' => $this->required, 'order' => 1, 'options' => $this->options()];
    }
}
