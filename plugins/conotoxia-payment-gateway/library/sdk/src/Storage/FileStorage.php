<?php

declare(strict_types=1);

namespace CKPL\Pay\Storage;

use CKPL\Pay\Exception\IncompatibilityException;
use CKPL\Pay\Exception\JsonFunctionException;
use CKPL\Pay\Exception\StorageException;
use function array_key_exists;
use function CKPL\Pay\json_decode_array;
use function CKPL\Pay\json_encode_array;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function is_readable;
use function is_writable;
use function sprintf;

/**
 * Class FileStorage.
 *
 * Stores received data in JSON file.
 *
 * @package CKPL\Pay\Storage
 */
class FileStorage extends AbstractStorage
{
    /**
     * @var string
     */
    protected $path;

    /**
     * FileStorage constructor.
     *
     * @param string $path path to valid JSON file
     *
     * @throws StorageException         storage-level related problem e.g. read/write permission problem.
     * @throws IncompatibilityException compatibility-level related problem e.g. missing extension.
     */
    public function __construct(string $path)
    {
        $this->path = $path;

        $this->load();
    }

    /**
     * Checks whether the item with specified key
     * exists in storage or does not exist.
     *
     * @param string $key item key
     *
     * @return bool `true` if item exist; `false` if item does not exist
     */
    public function hasItem(string $key): bool
    {
        return array_key_exists($key, $this->items);
    }

    /**
     * Changes value of item with specified key.
     * Remember that item value must be save as correct type.
     * If storage set `string` but value is expected to be `integer`
     * then `TypeMismatchException` will be thrown at the time when
     * library get this value.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @throws IncompatibilityException compatibility-level related problem e.g. missing extension.
     * @throws StorageException         storage-level related problem e.g. read/write permission problem.
     *
     * @return void
     */
    public function setItem(string $key, $value): void
    {
        $this->items[$key] = $value;

        $this->save();
    }

    /**
     * Clears entire storage.
     *
     * @throws IncompatibilityException compatibility-level related problem e.g. missing extension.
     * @throws StorageException         storage-level related problem e.g. read/write permission problem.
     *
     * @return void
     */
    public function clear(): void
    {
        $this->items = [];

        $this->save();
    }

    /**
     * @throws StorageException
     * @throws IncompatibilityException
     *
     * @return void
     */
    protected function load(): void
    {
        if (!file_exists($this->path)) {
            throw new StorageException(sprintf('File %s does not exist.', $this->path));
        }

        if (!is_readable($this->path)) {
            throw new StorageException(sprintf('File %s is not readable.', $this->path));
        }

        if (!is_writable($this->path)) {
            throw new StorageException(sprintf('File %s is not writable.', $this->path));
        }

        $content = file_get_contents($this->path);

        try {
            $content = json_decode_array($content, true);
        } catch (JsonFunctionException $e) {
            throw new StorageException(sprintf('Invalid data format in %s.', $this->path), 0, $e);
        }

        $this->items = $content;
    }

    /**
     * @throws StorageException
     * @throws IncompatibilityException
     *
     * @return void
     */
    protected function save(): void
    {
        if (!file_exists($this->path)) {
            throw new StorageException(sprintf('File %s does not exist.', $this->path));
        }

        if (!is_readable($this->path)) {
            throw new StorageException(sprintf('File %s is not readable.', $this->path));
        }

        if (!is_writable($this->path)) {
            throw new StorageException(sprintf('File %s is not writable.', $this->path));
        }

        file_put_contents($this->path, json_encode_array($this->items, true));
    }
}
