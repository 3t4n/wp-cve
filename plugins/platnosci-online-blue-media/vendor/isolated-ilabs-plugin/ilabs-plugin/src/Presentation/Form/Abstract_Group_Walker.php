<?php

declare (strict_types=1);
namespace Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form;

use Exception;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Form\Fields\Group;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Field_Interface;
use Isolated\BlueMedia\Ilabs\Ilabs_Plugin\Presentation\Interfaces\Group_Interface;
abstract class Abstract_Group_Walker
{
    /**
     * @var int
     */
    private $level;
    /**
     * @var Group_Interface
     */
    protected $group;
    public function __construct(Group_Interface $group)
    {
        $this->level = 0;
        $this->group = new Group();
        $this->group->set_items([$group]);
    }
    protected abstract function begin_group_callback(Group_Interface &$group);
    protected abstract function end_group_callback(Group_Interface &$group);
    protected abstract function group_field_callback(Field_Interface &$field);
    /**
     * @throws Exception
     */
    public function walk(Group_Interface &$group = null) : void
    {
        if (null === $group) {
            $group = $this->group;
        }
        if ($group_items = $group->get_items()) {
            foreach ($group_items as $group_item) {
                $group = $this->try_extract_group_interface($group_item);
                $group_cache = $group;
                if ($field = $this->try_extract_field_interface($group_item)) {
                    $this->group_field_callback($field);
                } elseif ($group) {
                    $this->begin_group_callback($group);
                    $this->walk($group);
                    $this->level++;
                } else {
                    throw new Exception('Invalid group item type');
                }
                if ($group_cache) {
                    $this->end_group_callback($group_cache);
                }
            }
        }
    }
    private function try_extract_field_interface($object) : ?Field_Interface
    {
        if ($object instanceof Field_Interface) {
            return $object;
        }
        return null;
    }
    private function try_extract_group_interface($object) : ?Group_Interface
    {
        if ($object instanceof Group_Interface) {
            return $object;
        }
        return null;
    }
}
