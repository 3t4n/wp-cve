<?php

declare (strict_types=1);
namespace Modular\ConnectorDependencies\Dotenv\Repository\Adapter;

use Modular\ConnectorDependencies\PhpOption\Option;
use Modular\ConnectorDependencies\PhpOption\Some;
/** @internal */
final class ArrayAdapter implements AdapterInterface
{
    /**
     * The variables and their values.
     *
     * @var array<string,string>
     */
    private $variables;
    /**
     * Create a new array adapter instance.
     *
     * @return void
     */
    private function __construct()
    {
        $this->variables = [];
    }
    /**
     * Create a new instance of the adapter, if it is available.
     *
     * @return \PhpOption\Option<\Dotenv\Repository\Adapter\AdapterInterface>
     */
    public static function create()
    {
        /** @var \PhpOption\Option<AdapterInterface> */
        return Some::create(new self());
    }
    /**
     * Read an environment variable, if it exists.
     *
     * @param non-empty-string $name
     *
     * @return \PhpOption\Option<string>
     */
    public function read(string $name)
    {
        return Option::fromArraysValue($this->variables, $name);
    }
    /**
     * Write to an environment variable, if possible.
     *
     * @param non-empty-string $name
     * @param string           $value
     *
     * @return bool
     */
    public function write(string $name, string $value)
    {
        $this->variables[$name] = $value;
        return \true;
    }
    /**
     * Delete an environment variable, if possible.
     *
     * @param non-empty-string $name
     *
     * @return bool
     */
    public function delete(string $name)
    {
        unset($this->variables[$name]);
        return \true;
    }
}
