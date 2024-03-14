<?php

namespace Baqend\SDK\Resource;

use Baqend\SDK\Exception\BadRequestException;
use Baqend\SDK\Exception\NeedsAuthorizationException;
use Baqend\SDK\Exception\ResponseException;
use Baqend\SDK\Model\User;
use Baqend\SDK\Model\UserInfo;

/**
 * Class UserResource created on 25.07.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Resource
 */
class UserResource extends AbstractRestResource
{

    const NO_LOGIN = false;
    const PERSIST_LOGIN = true;

    /**
     * Logs a user in.
     *
     * @param string|User $user The user's username or a User instance to populate.
     * @param string $password The user's password.
     * @return User|null The logged in user or null, if login was not successful.
     * @throws ResponseException When the login was not successful.
     */
    public function login($user, $password) {
        if (is_string($user)) {
            $userData = ['username' => $user, 'password' => $password];
            $user = User::class;
        } elseif ($user instanceof User) {
            $userData = ['username' => $user->getUsername(), 'password' => $password];
        } else {
            throw new \InvalidArgumentException('Login needs to be called with a username or a User instance.');
        }

        $request = $this->sendJson('POST', '/db/User/login', $userData);
        $response = $this->execute($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $this->receiveJson($response, $user);
            default:
                throw new ResponseException($response);
        }
    }

    /**
     * Registers a new user.
     *
     * @param string|User $user The username as a string or a <User> Object, which must contain the username.
     * @param string $password The new user's password.
     * @param bool $loginOption An option to handle the user's login state after registration. Defaults to NO_LOGIN.
     * @return User|null The new user which is not logged in or null, if one has not the access to the new object.
     * @throws ResponseException When the registration was unsuccessful.
     */
    public function register($user, $password, $loginOption = self::NO_LOGIN) {
        if (is_string($user)) {
            $userData = ['username' => $user];
            $user = User::class;
        } elseif ($user instanceof User) {
            $userData = $this->serializer->normalize($user);
        } else {
            throw new \InvalidArgumentException('Register needs to be called with a username or a User instance.');
        }

        $json = ['user' => $userData, 'password' => $password, 'login' => $loginOption];
        $request = $this->sendJson('POST', '/db/User/register', $json);
        $response = $this->execute($request);

        switch ($response->getStatusCode()) {
            case 200:
                return $this->receiveJson($response, $user);
            case 204:
                // When receiving a 204, the user is inactive
                return null;
            default:
                throw new ResponseException($response);
        }
    }

    /**
     * Deletes a given user.
     *
     * @param User|int $user The user to delete.
     * @throws ResponseException When the user cannot be deleted.
     */
    public function delete($user) {
        if (!is_int($user)) {
            $user = $user->retrieveKey();
        }
        $request = $this->sendQuery('DELETE', '/db/User/'.$user);
        $response = $this->execute($request);

        if ($response->getStatusCode() !== 204) {
            throw new ResponseException($response);
        }
    }

    /**
     * Logs a user out.
     *
     * @throws ResponseException When the logout was not successful.
     */
    public function logout() {
        $request = $this->sendJson('GET', '/db/User/logout');
        $response = $this->execute($request);
        if ($response->getStatusCode() !== 204) {
            throw new ResponseException($response);
        }

        // Remove authorization token
        $this->getClient()->setAuthorizationToken(null);
    }

    /**
     * Returns the logged in user.
     * @return User The logged in user.
     * @throws NeedsAuthorizationException When the logged in user could not be retrieved.
     * @throws ResponseException If an unexpected response was received
     */
    public function me() {
        $path = '/db/User/me';
        if (!$this->isAuthorized()) {
            throw new NeedsAuthorizationException('GET', $path);
        }

        $request = $this->sendJson('GET', $path);
        $response = $this->execute($request);

        if ($response->getStatusCode() !== 200) {
            throw new NeedsAuthorizationException('GET', $path);
        }

        return $this->receiveJson($response, User::class);
    }

    /**
     * Checks whether a user with the given username does exists.
     * @param $username string The username to find the user by.
     * @param $class \ClassName The fully qualified name of the user class to be used.
     * @return User|null The received user from the JSON.
     * @throws NeedsAuthorizationException When the logged in user could not be retrieved.
     * @throws ResponseException If an unexpected response was received
     */
    public function findByUsername($username, $class = User::class) {
        $path = '/db/User/query';
        $query = ['q' => '{"username":"'.$username.'"}','start' => 0, 'count' => -1];

        if (!$this->isAuthorized()) {
            throw new NeedsAuthorizationException('GET', $path);
        }

        $request = $this->sendQuery('GET', $path, $query);
        $response = $this->execute($request);

        if ($response->getStatusCode() !== 200) {
            throw new NeedsAuthorizationException('GET', $path);
        }

        $json = $this->receiveJson($response);
        $userFound = count($json) > 0;

        return $userFound ? $this->serializer->denormalize($json[0], $class) : null;
    }

    /**
     * Checks whether a user with the given username does exists.
     * @return bool
     * @throws NeedsAuthorizationException When the logged in user could not be retrieved.
     * @throws ResponseException If an unexpected response was received
     */
    public function isExisting($username) {
        $path = '/db/User/query';
        $query = ['q' => '{"username":"'.$username.'"}','start' => 0, 'count' => -1];

        if (!$this->isAuthorized()) {
            throw new NeedsAuthorizationException('GET', $path);
        }

        $request = $this->sendQuery('GET', $path, $query);
        $response = $this->execute($request);

        if ($response->getStatusCode() !== 200) {
            throw new NeedsAuthorizationException('GET', $path);
        }

        $content = $this->receiveJson($response);
        return count($content) > 0;
    }

    /**
     * Request an API token for the given user. Note that admin permission are required to execute this call
     * @param $user User|int The user id for which an api token should be requested
     * @return UserInfo
     * @throws NeedsAuthorizationException When the logged in user could not be retrieved.
     * @throws ResponseException If an unexpected response was received
     */
    public function requestApiToken($user) {
        if (!is_int($user)) {
            $user = $user->retrieveKey();
        }

        $path = "/db/User/$user/token";
        if (!$this->isAuthorized()) {
            throw new NeedsAuthorizationException('POST', $path);
        }

        $request = $this->sendJson('POST', $path);
        $response = $this->execute($request);

        if ($response->getStatusCode() !== 200) {
            throw new ResponseException($response);
        }

        return $this->receiveJson($response, UserInfo::class);
    }

    /**
     * Changes the password of a user.
     *
     * @param $user User|string The user instance or username of which the password should be changed.
     * @param string $oldPassword The user's old password.
     * @param string $newPassword The user's new password to replace the old with.
     * @return User The user with the changed password.
     * @throws NeedsAuthorizationException When the logged in user could not be retrieved.
     * @throws BadRequestException When the password was invalid.
     */
    public function newPassword($user, $oldPassword, $newPassword) {
        if ($user instanceof User) {
            $username = $user->getUsername();
        } elseif (is_string($user)) {
            $username = $user;
        } else {
            throw new \InvalidArgumentException('Please provide either an instance of User or a username.');
        }

        $path = '/db/User/password';
        if (!$this->isAuthorized()) {
            throw new NeedsAuthorizationException('POST', $path);
        }

        $request = $this->sendJson('POST', $path, [
            'username' => $username,
            'password' => $oldPassword,
            'newPassword' => $newPassword,
        ]);

        try {
            $response = $this->execute($request);
        } catch (ResponseException $exception) {
            throw $this->createRuntimeError($exception->getMessage(), $exception);
        }

        if ($response->getStatusCode() !== 200) {
            throw new BadRequestException($response);
        }

        return $this->receiveJson($response, User::class);
    }
}
