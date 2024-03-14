<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Parser;

class Import
{
    /** @var string */
    private $import;

    /** @var string|null */
    private $alias;

    public function __construct(string $import, ?string $alias = null)
    {
        $this->import = $import;
        $this->alias = $alias;
    }

    public function getImport(): string
    {
        return $this->import;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function hasAlias(): bool
    {
        return ! \is_null($this->alias);
    }
}
