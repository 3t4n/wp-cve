<?php

namespace Modular\ConnectorDependencies\Illuminate\Database\Eloquent\Factories;

use Modular\ConnectorDependencies\Illuminate\Database\Eloquent\Model;
use Modular\ConnectorDependencies\Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modular\ConnectorDependencies\Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Modular\ConnectorDependencies\Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
/** @internal */
class Relationship
{
    /**
     * The related factory instance.
     *
     * @var \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected $factory;
    /**
     * The relationship name.
     *
     * @var string
     */
    protected $relationship;
    /**
     * Create a new child relationship instance.
     *
     * @param  \Illuminate\Database\Eloquent\Factories\Factory  $factory
     * @param  string  $relationship
     * @return void
     */
    public function __construct(Factory $factory, $relationship)
    {
        $this->factory = $factory;
        $this->relationship = $relationship;
    }
    /**
     * Create the child relationship for the given parent model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $parent
     * @return void
     */
    public function createFor(Model $parent)
    {
        $relationship = $parent->{$this->relationship}();
        if ($relationship instanceof MorphOneOrMany) {
            $this->factory->state([$relationship->getMorphType() => $relationship->getMorphClass(), $relationship->getForeignKeyName() => $relationship->getParentKey()])->create([], $parent);
        } elseif ($relationship instanceof HasOneOrMany) {
            $this->factory->state([$relationship->getForeignKeyName() => $relationship->getParentKey()])->create([], $parent);
        } elseif ($relationship instanceof BelongsToMany) {
            $relationship->attach($this->factory->create([], $parent));
        }
    }
}
