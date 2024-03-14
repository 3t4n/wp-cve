<?php
/**
 * @license BSD-3-Clause
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare(strict_types=1);

namespace CoffeeCode\PhpParser\Builder;

use CoffeeCode\PhpParser;
use CoffeeCode\PhpParser\BuilderHelpers;
use CoffeeCode\PhpParser\Node;
use CoffeeCode\PhpParser\Node\Name;
use CoffeeCode\PhpParser\Node\Stmt;

class Class_ extends Declaration
{
    protected $name;

    protected $extends = null;
    protected $implements = [];
    protected $flags = 0;

    protected $uses = [];
    protected $constants = [];
    protected $properties = [];
    protected $methods = [];

    /** @var Node\AttributeGroup[] */
    protected $attributeGroups = [];

    /**
     * Creates a class builder.
     *
     * @param string $name Name of the class
     */
    public function __construct(string $name) {
        $this->name = $name;
    }

    /**
     * Extends a class.
     *
     * @param Name|string $class Name of class to extend
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function extend($class) {
        $this->extends = BuilderHelpers::normalizeName($class);

        return $this;
    }

    /**
     * Implements one or more interfaces.
     *
     * @param Name|string ...$interfaces Names of interfaces to implement
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function implement(...$interfaces) {
        foreach ($interfaces as $interface) {
            $this->implements[] = BuilderHelpers::normalizeName($interface);
        }

        return $this;
    }

    /**
     * Makes the class abstract.
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function makeAbstract() {
        $this->flags = BuilderHelpers::addClassModifier($this->flags, Stmt\Class_::MODIFIER_ABSTRACT);

        return $this;
    }

    /**
     * Makes the class final.
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function makeFinal() {
        $this->flags = BuilderHelpers::addClassModifier($this->flags, Stmt\Class_::MODIFIER_FINAL);

        return $this;
    }

    public function makeReadonly() {
        $this->flags = BuilderHelpers::addClassModifier($this->flags, Stmt\Class_::MODIFIER_READONLY);

        return $this;
    }

    /**
     * Adds a statement.
     *
     * @param Stmt|CoffeeCode\PhpParser\Builder $stmt The statement to add
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function addStmt($stmt) {
        $stmt = BuilderHelpers::normalizeNode($stmt);

        $targets = [
            Stmt\TraitUse::class    => &$this->uses,
            Stmt\ClassConst::class  => &$this->constants,
            Stmt\Property::class    => &$this->properties,
            Stmt\ClassMethod::class => &$this->methods,
        ];

        $class = \get_class($stmt);
        if (!isset($targets[$class])) {
            throw new \LogicException(sprintf('Unexpected node of type "%s"', $stmt->getType()));
        }

        $targets[$class][] = $stmt;

        return $this;
    }

    /**
     * Adds an attribute group.
     *
     * @param Node\Attribute|Node\AttributeGroup $attribute
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function addAttribute($attribute) {
        $this->attributeGroups[] = BuilderHelpers::normalizeAttribute($attribute);

        return $this;
    }

    /**
     * Returns the built class node.
     *
     * @return Stmt\Class_ The built class node
     */
    public function getNode() : CoffeeCode\PhpParser\Node {
        return new Stmt\Class_($this->name, [
            'flags' => $this->flags,
            'extends' => $this->extends,
            'implements' => $this->implements,
            'stmts' => array_merge($this->uses, $this->constants, $this->properties, $this->methods),
            'attrGroups' => $this->attributeGroups,
        ], $this->attributes);
    }
}
