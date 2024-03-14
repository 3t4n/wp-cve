<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator;

use Modular\ConnectorDependencies\Egulias\EmailValidator\Exception\InvalidEmail;
use Modular\ConnectorDependencies\Egulias\EmailValidator\Validation\EmailValidation;
/** @internal */
class EmailValidator
{
    /**
     * @var EmailLexer
     */
    private $lexer;
    /**
     * @var Warning\Warning[]
     */
    protected $warnings = [];
    /**
     * @var InvalidEmail|null
     */
    protected $error;
    public function __construct()
    {
        $this->lexer = new EmailLexer();
    }
    /**
     * @param string          $email
     * @param EmailValidation $emailValidation
     * @return bool
     */
    public function isValid($email, EmailValidation $emailValidation)
    {
        $isValid = $emailValidation->isValid($email, $this->lexer);
        $this->warnings = $emailValidation->getWarnings();
        $this->error = $emailValidation->getError();
        return $isValid;
    }
    /**
     * @return boolean
     */
    public function hasWarnings()
    {
        return !empty($this->warnings);
    }
    /**
     * @return array
     */
    public function getWarnings()
    {
        return $this->warnings;
    }
    /**
     * @return InvalidEmail|null
     */
    public function getError()
    {
        return $this->error;
    }
}
