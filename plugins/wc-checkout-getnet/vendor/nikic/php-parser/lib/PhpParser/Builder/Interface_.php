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

class Interface_ extends Declaration
{
    protected $name;
    protected $extends = [];
    protected $constants = [];
    protected $methods = [];

    /** @var Node\AttributeGroup[] */
    protected $attributeGroups = [];

    /**
     * Creates an interface builder.
     *
     * @param string $name Name of the interface
     */
    public function __construct(string $name) {
        $this->name = $name;
    }

    /**
     * Extends one or more interfaces.
     *
     * @param Name|string ...$interfaces Names of interfaces to extend
     *
     * @return $this The builder instance (for fluid interface)
     */
    public function extend(...$interfaces) {
        foreach ($interfaces as $interface) {
            $this->extends[] = BuilderHelpers::normalizeName($interface);
        }

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

        if ($stmt instanceof Stmt\ClassConst) {
            $this->constants[] = $stmt;
        } elseif ($stmt instanceof Stmt\ClassMethod) {
            // we erase all statements in the body of an interface method
            $stmt->stmts = null;
            $this->methods[] = $stmt;
        } else {
            throw new \LogicException(sprintf('Unexpected node of type "%s"', $stmt->getType()));
        }

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
     * Returns the built interface node.
     *
     * @return Stmt\Interface_ The built interface node
     */
    public function getNode() : CoffeeCode\PhpParser\Node {
        return new Stmt\Interface_($this->name, [
            'extends' => $this->extends,
            'stmts' => array_merge($this->constants, $this->methods),
            'attrGroups' => $this->attributeGroups,
        ], $this->attributes);
    }
}
