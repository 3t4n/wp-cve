<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

declare(strict_types=1);

namespace CoffeeCode\JsonMapper\Helpers;

use CoffeeCode\JsonMapper\Enums\ScalarType;
use CoffeeCode\JsonMapper\Parser\Import;

class NamespaceHelper
{
    /** @param Import[] $imports */
    public static function resolveNamespace(string $type, string $contextNamespace, array $imports): string
    {
        if (ScalarType::isValid($type)) {
            return $type;
        }

        $matches = \array_filter(
            $imports,
            static function (Import $import) use ($type) {
                $nameSpacedType = "\\{$type}";
                if ($import->hasAlias() && $import->getAlias() === $type) {
                    return true;
                }

                return $nameSpacedType === \substr($import->getImport(), -strlen($nameSpacedType));
            }
        );

        $firstMatch = array_shift($matches);
        if (! \is_null($firstMatch)) {
            return $firstMatch->getImport();
        }

        if (class_exists($contextNamespace . '\\' . $type)) {
            return $contextNamespace . '\\' . $type;
        }

        return $type;
    }
}
