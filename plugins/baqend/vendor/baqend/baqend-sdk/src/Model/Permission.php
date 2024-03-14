<?php

namespace Baqend\SDK\Model;

/**
 * Protects some operation on a resource with a rule set.
 *
 * Class Permission created on 12.11.18.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Model
 */
class Permission
{
    const ALLOWED = 'allow';
    const DENIED = 'deny';

    /** @var array */
    private $rules;

    /**
     * Validates whether the given parameter are valid rules.
     *
     * @param mixed $rules
     * @return bool
     */
    public static function validateRules($rules) {
        if (!is_array($rules)) {
            return false;
        }

        foreach ($rules as $scope => $allowOrDeny) {
            if ($scope !== '*' && preg_match('#^/db/(?:User|Role)/\w+$#', $scope) !== 1) {
                return false;
            }
            if (!in_array($allowOrDeny, [self::ALLOWED, self::DENIED], true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Permission constructor.
     *
     * @param array $rules
     */
    public function __construct($rules = []) {
        $this->setRules($rules);
    }

    /**
     * Returns the underlying rules.
     *
     * @return array
     */
    public function getRules() {
        return $this->rules;
    }

    /**
     * Sets the underlying rules.
     *
     * @param array $rules
     */
    public function setRules($rules) {
        if (!self::validateRules($rules)) {
            throw new \InvalidArgumentException('You have to init Permissions with a valid rule array.');
        }
        $this->rules = $rules;
    }

    /**
     * Returns whether these permissions are empty.
     *
     * @return bool
     */
    public function isEmpty() {
        return empty($this->rules);
    }

    /**
     * Checks whether a rule for the given scope exists.
     *
     * @param string $scope The user or role to check if a rule exists.
     * @return bool <code>true</code>, if a rule for the given scope exists.
     */
    public function has($scope) {
        return isset($this->rules[$scope]);
    }

    /**
     * Returns the rule for the given scope.
     *
     * @param string $scope The user or role to return the role for.
     * @return string|null Return <code>"allow"</code>, <code>"deny"</code> or
     *                      <code>null</code>, if the rule does not exist.
     */
    public function get($scope) {
        return $this->has($scope) ? $this->rules[$scope] : null;
    }

    /**
     * Returns whether the given scope is allowed in this permission.
     *
     * @param string $scope The user or role to check if access is allowed.
     * @return bool <code>true</code>, if the given scope is allowed.
     */
    public function isAllowed($scope) {
        $scope = $this->ref('isAllowed', $scope);
        return $this->has($scope) && $this->rules[$scope] === self::ALLOWED;
    }

    /**
     * Returns whether the given scope is denied in this permission.
     *
     * @param string $scope The user or role to check if access is denied.
     * @return bool <code>true</code>, if the given scope is denied.
     */
    public function isDenied($scope) {
        $scope = $this->ref('isDenied', $scope);
        return $this->has($scope) && $this->rules[$scope] === self::DENIED;
    }

    /**
     * Allows the access for the given scopes.
     *
     * @param string $scopes The scopes to allow the access for.
     * @return static This method is chainable.
     */
    public function allowAccess(/*...*/ $scopes) {
        $scopes = func_get_args();
        foreach ($scopes as $scope) {
            $this->rules[$this->ref('allowAccess', $scope)] = self::ALLOWED;
        }

        return $this;
    }

    /**
     * Denies the access for the given scopes.
     *
     * @param string $scopes The scopes to deny the access for.
     * @return static This method is chainable.
     */
    public function denyAccess(/*...*/ $scopes) {
        $scopes = func_get_args();
        foreach ($scopes as $scope) {
            $this->rules[$this->ref('denyAccess', $scope)] = self::DENIED;
        }

        return $this;
    }

    /**
     * Deletes the rules for the given scopes.
     *
     * @param string $scopes The scopes to delete the rules for.
     * @return static This method is chainable.
     */
    public function delete(/*...*/
        $scopes
    ) {
        $scopes = func_get_args();
        foreach ($scopes as $scope) {
            unset($this->rules[$scope]);
        }

        return $this;
    }

    /**
     * Clears all rules.
     *
     * @return static This method is chainable.
     */
    public function clear() {
        $this->rules = [];

        return $this;
    }

    /**
     * @param string $access
     * @param User|Role|string $object
     * @return null|string
     */
    private function ref($access, $object) {
        if ($object instanceof User || $object instanceof Role) {
            $object = $object->getId();
        }

        if (!is_string($object)) {
            $message = '"'.$access.'" expects an instance of User or Role with an ID or a string.';
            throw new \InvalidArgumentException($message);
        }

        return $object;
    }
}
