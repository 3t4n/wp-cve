<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Validation;

use Modular\ConnectorDependencies\Egulias\EmailValidator\EmailLexer;
use Modular\ConnectorDependencies\Egulias\EmailValidator\Exception\InvalidEmail;
use Modular\ConnectorDependencies\Egulias\EmailValidator\Warning\Warning;
/** @internal */
interface EmailValidation
{
    /**
     * Returns true if the given email is valid.
     *
     * @param string     $email      The email you want to validate.
     * @param EmailLexer $emailLexer The email lexer.
     *
     * @return bool
     */
    public function isValid($email, EmailLexer $emailLexer);
    /**
     * Returns the validation error.
     *
     * @return InvalidEmail|null
     */
    public function getError();
    /**
     * Returns the validation warnings.
     *
     * @return Warning[]
     */
    public function getWarnings();
}
