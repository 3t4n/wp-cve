<?php

namespace FluentSupport\App\Hooks\Handlers;

class ExceptionHandler
{
    protected $handlers = [
        'InvalidArgumentException' => 'handleInvalidArgumentException',
        'FluentSupport\Framework\Foundation\ForbiddenException' => 'handleForbiddenException',
        'FluentSupport\Framework\Validator\ValidationException' => 'handleValidationException',
        'FluentSupport\Framework\Foundation\UnAuthorizedException' => 'handleUnAuthorizedException',
        'FluentSupport\Framework\Database\Orm\ModelNotFoundException' => 'handleModelNotFoundException',
    ];

    public function handle($e)
    {
        foreach ($this->handlers as $key => $value) {
            if ($e instanceof $key) {
                return $this->{$value}($e);
            }
        }
    }

    public function handleInvalidArgumentException($e)
    {
        wp_send_json_error([
            'message' => $e->getMessage()
        ], $e->getCode() ?: 422);
    }

    public function handleModelNotFoundException($e)
    {
       wp_send_json_error([
            'message' => $e->getMessage()
        ], $e->getCode() ?: 404);
    }

    public function handleUnAuthorizedException($e)
    {
        wp_send_json_error([
            'message' => $e->getMessage()
        ], $e->getCode() ?: 401);
    }

    public function handleForbiddenException($e)
    {
        wp_send_json_error([
            'message' => $e->getMessage()
        ], $e->getCode() ?: 403);
    }

    public function handleValidationException($e)
    {
        wp_send_json_error([
            'message' => $e->getMessage(),
            'errors' => $e->errors()
        ], $e->getCode() ?: 422);
    }
}
