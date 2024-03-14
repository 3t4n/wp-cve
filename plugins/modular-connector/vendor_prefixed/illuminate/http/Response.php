<?php

namespace Modular\ConnectorDependencies\Illuminate\Http;

use ArrayObject;
use Modular\ConnectorDependencies\Illuminate\Contracts\Support\Arrayable;
use Modular\ConnectorDependencies\Illuminate\Contracts\Support\Jsonable;
use Modular\ConnectorDependencies\Illuminate\Contracts\Support\Renderable;
use Modular\ConnectorDependencies\Illuminate\Support\Traits\Macroable;
use InvalidArgumentException;
use JsonSerializable;
use Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\ResponseHeaderBag;
/** @internal */
class Response extends SymfonyResponse
{
    use ResponseTrait, Macroable {
        Macroable::__call as macroCall;
    }
    /**
     * Create a new HTTP response.
     *
     * @param  mixed  $content
     * @param  int  $status
     * @param  array  $headers
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($content = '', $status = 200, array $headers = [])
    {
        $this->headers = new ResponseHeaderBag($headers);
        $this->setContent($content);
        $this->setStatusCode($status);
        $this->setProtocolVersion('1.0');
    }
    /**
     * Set the content on the response.
     *
     * @param  mixed  $content
     * @return $this
     *
     * @throws \InvalidArgumentException
     */
    public function setContent($content)
    {
        $this->original = $content;
        // If the content is "JSONable" we will set the appropriate header and convert
        // the content to JSON. This is useful when returning something like models
        // from routes that will be automatically transformed to their JSON form.
        if ($this->shouldBeJson($content)) {
            $this->header('Content-Type', 'application/json');
            $content = $this->morphToJson($content);
            if ($content === \false) {
                throw new InvalidArgumentException(\json_last_error_msg());
            }
        } elseif ($content instanceof Renderable) {
            $content = $content->render();
        }
        parent::setContent($content);
        return $this;
    }
    /**
     * Determine if the given content should be turned into JSON.
     *
     * @param  mixed  $content
     * @return bool
     */
    protected function shouldBeJson($content)
    {
        return $content instanceof Arrayable || $content instanceof Jsonable || $content instanceof ArrayObject || $content instanceof JsonSerializable || \is_array($content);
    }
    /**
     * Morph the given content into JSON.
     *
     * @param  mixed  $content
     * @return string
     */
    protected function morphToJson($content)
    {
        if ($content instanceof Jsonable) {
            return $content->toJson();
        } elseif ($content instanceof Arrayable) {
            return \json_encode($content->toArray());
        }
        return \json_encode($content);
    }
}
