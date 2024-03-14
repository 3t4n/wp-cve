<?php

namespace Baqend\SDK\Resource;

use Baqend\SDK\Exception\NeedsAuthorizationException;
use Baqend\SDK\Exception\ResponseException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

/**
 * Class CodeResource created on 14.12.17.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Resource
 */
class CodeResource extends AbstractRestResource
{

    /**
     * Sets the contents of a code bucket.
     *
     * @param string $bucket The bucket to set the content of.
     * @param string $code The bucket's content to set.
     * @throws NeedsAuthorizationException When the operation could not be performed.
     */
    public function putModule($bucket, $code) {
        $path = '/code/'.$bucket.'/module';
        $request = $this
            ->sendString('PUT', $path, $code)
            ->withHeader('content-type', 'text/plain;charset=UTF-8');

        try {
            $response = $this->execute($request);
        } catch (ResponseException $e) {
            throw new NeedsAuthorizationException('PUT', $path);
        }

        if ($response->getStatusCode() >= 400) {
            throw new NeedsAuthorizationException('PUT', $path);
        }
    }

    /**
     * Retrieves the contents of a code bucket.
     *
     * @param string $bucket The code bucket to get.
     * @return string The bucket's content.
     * @throws NeedsAuthorizationException When the module could not be accessed.
     */
    public function getModule($bucket) {
        $path = '/code/'.$bucket.'/module';
        $request = $this
            ->sendQuery('GET', $path)
            ->withHeader('accept', 'text/*');
        try {
            $response = $this->execute($request);
        } catch (ResponseException $e) {
            throw new NeedsAuthorizationException('GET', $path);
        }

        if ($response->getStatusCode() >= 400) {
            throw new NeedsAuthorizationException('GET', $path);
        }

        return $response->getBody()->getContents();
    }

    /**
     * Deletes a code bucket.
     *
     * @param string $bucket The code bucket to delete.
     * @throws NeedsAuthorizationException When the operation could not be performed.
     */
    public function deleteModule($bucket) {
        $path = '/code/'.$bucket.'/module';
        $request = $this
            ->sendQuery('DELETE', $path);
        try {
            $response = $this->execute($request);
        } catch (ResponseException $e) {
            throw new NeedsAuthorizationException('DELETE', $path);
        }

        if ($response->getStatusCode() >= 400) {
            throw new NeedsAuthorizationException('DELETE', $path);
        }
    }

    /**
     * Calls a code bucket with a GET request.
     *
     * @param string $bucket The bucket to GET.
     * @param array|\JsonSerializable $data Data to send to the code bucket.
     * @return array|string  Data returned from the code bucket.
     * @throws ResponseException When the module call does not succeed.
     */
    public function get($bucket, $data = []) {
        $request = $this->sendQuery('GET', '/code/'.$bucket, $data);
        $response = $this->execute($request);

        return $this->receiveModuleCallResponse($response);
    }

    /**
     * Calls a code bucket with a POST request.
     *
     * @param string $bucket The bucket to POST.
     * @param array|\JsonSerializable $data Data to send to the code bucket.
     * @return array|string  Data returned from the code bucket.
     * @throws ResponseException When the module call does not succeed.
     */
    public function post($bucket, $data = []) {
        $request = $this->sendJson('POST', '/code/'.$bucket, $data);
        $response = $this->execute($request);

        return $this->receiveModuleCallResponse($response);
    }

    /**
     * Handles the response from a module call.
     *
     * @param ResponseInterface $response The response to receive.
     * @return array|string An unserialized JSON or a string, if unserialization was not possible.
     * @throws ResponseException When the module call does not succeed.
     */
    private function receiveModuleCallResponse(ResponseInterface $response) {
        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            throw new ResponseException($response);
        }

        $body = $response->getBody()->getContents();
        try {
            // Return unserialized data if possible
            return $this->getSerializer()->decode($body, 'json');
        } catch (UnexpectedValueException $e) {
            // Return data as string if could not unserialize JSON.
            return $body;
        }
    }
}
