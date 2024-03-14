<?php

namespace Baqend\SDK\Resource;

use Baqend\SDK\Exception\ResponseException;
use Baqend\SDK\Model\Entity;

/**
 * Class CrudResource created on 30.01.2018.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Resource
 */
class CrudResource extends AbstractRestResource
{

    /**
     * Lists all bucket names.
     *
     * @return string[] Data returned from the code bucket.
     * @throws ResponseException When the module call does not succeed.
     */
    public function listAll() {
        $request = $this->sendQuery('GET', '/db');
        $response = $this->execute($request);

        return $this->receiveJson($response);
    }

    /**
     * Loads an object by its ID.
     *
     * @param string $class The class to read an object from.
     * @param string|int $id The ID of the object to load.
     * @return mixed An object with the instance of $class containing the object's data
     *               or null, if object does not exist.
     * @throws ResponseException When the response was not successful.
     */
    public function load($class, $id) {
        $path = $this->referenceObject($class, $id);
        $request = $this->sendJson('GET', $path);
        $response = $this->execute($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $this->receiveJson($response, $class);
            case 404:
                return null;
            default:
                throw new ResponseException($response);
        }
    }

    /**
     * Loads an object by its ID.
     *
     * @param string $class The class to read an object from.
     * @param string[]|int[] $ids The IDs of the objects to load.
     * @return mixed[] An array of objects with the instance of $class containing the object's data
     *                 or null, if object does not exist.
     */
    public function loadMany($class, $ids) {
        return array_map(
            function ($id) use ($class) {
                return $this->load($class, $id);
            },
            $ids
        );
    }

    /**
     * Creates an object.
     *
     * @param Entity $entity The entity to create.
     * @return Entity|null The created entity which has the same reference or null, if the insertion failed.
     * @throws ResponseException When the insert command threw an error.
     */
    public function insert(Entity $entity) {
        if (!$this->isNew($entity)) {
            throw new \InvalidArgumentException('The given entity has an ID and is not new.');
        }
        $path = $this->referenceEntity($entity);
        $request = $this->sendJson('POST', $path, $entity);
        $response = $this->execute($request);

        switch ($response->getStatusCode()) {
            case 201:
                return $this->receiveJson($response, $entity);
            case 404:
                return null;
            default:
                throw new ResponseException($response);
        }
    }

    /**
     * Updates an object.
     *
     * @param Entity $entity The entity to update.
     * @param bool $force True, if the update should be forced.
     * @return Entity The updated entity which has the same reference.
     * @throws ResponseException When the update command threw an error.
     */
    public function update(Entity $entity, $force = true) {
        if ($this->isNew($entity)) {
            throw new \InvalidArgumentException('The given entity has no ID and is new.');
        }
        $path = $this->referenceEntity($entity);
        $request = $this->sendJson('PUT', $path, $entity);

        if (!$force) {
            $request = $request->withHeader('if-match', '"'.$entity->getVersion().'"');
        }

        $response = $this->execute($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $this->receiveJson($response, $entity);
            default:
                throw new ResponseException($response);
        }
    }

    /**
     * Deletes an object.
     *
     * @param Entity $entity The entity to attempt to delete.
     * @return bool True, if the object verifiably no longer exists after the call.
     * @throws ResponseException When the delete command threw an error.
     */
    public function delete(Entity $entity) {
        if ($this->isNew($entity)) {
            throw new \InvalidArgumentException('The given entity has no ID and is new.');
        }
        $path = $this->referenceEntity($entity);
        $request = $this->sendJson('DELETE', $path, $entity);
        $response = $this->execute($request);

        return $response->getStatusCode() === 204;
    }

    /**
     * Returns whether the given entity is new.
     *
     * @param Entity $entity The entity to check.
     * @return bool True, if the given entity is new.
     */
    public function isNew(Entity $entity) {
        return $entity->retrieveKey() === null;
    }

    /**
     * Builds a reference to an object by class name and ID.
     *
     * @param string $class The class to build the reference for.
     * @param int|string $id The ID of the object.
     * @return string An object reference.
     */
    public function referenceObject($class, $id) {
        $entity = substr($class, strrpos($class, '\\') + 1);
        $prefix = "/db/$entity";
        if (strpos($id, $prefix) === 0) {
            return $id;
        }

        return "$prefix/$id";
    }

    /**
     * Builds a reference to an object by an Entity instance.
     *
     * @param Entity $entity The entity to create a reference for.
     * @return string An object reference.
     */
    public function referenceEntity(Entity $entity) {
        $class = get_class($entity);
        $className = substr($class, strrpos($class, '\\') + 1);
        $key = $entity->retrieveKey();
        $id = $key ? "/$key" : '';

        return "/db/$className$id";
    }
}
