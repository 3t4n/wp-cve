<?php

namespace S2WPImporter\Traits;

trait ErrorTrait
{
    /**
     * @var array Hard error that should stop the execution
     */
    protected $errors = [];

    /**
     * @var array Soft errors that should not stop the execution, but inform the user.
     */
    protected $softErrors = [];

    /**
     * @param $message
     */
    public function addError($message)
    {
        $this->errors[] = $message;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        return count($this->errors) > 0;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param $message
     */
    public function addSoftError($message)
    {
        $this->softErrors[] = $message;
    }

    /**
     * @return bool
     */
    public function hasSoftErrors()
    {
        return count($this->softErrors) > 0;
    }

    /**
     * @return array
     */
    public function getSoftErrors()
    {
        return $this->softErrors;
    }

}
