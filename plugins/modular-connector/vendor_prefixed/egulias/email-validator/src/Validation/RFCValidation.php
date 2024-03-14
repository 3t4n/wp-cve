<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Validation;

use Modular\ConnectorDependencies\Egulias\EmailValidator\EmailLexer;
use Modular\ConnectorDependencies\Egulias\EmailValidator\EmailParser;
use Modular\ConnectorDependencies\Egulias\EmailValidator\Exception\InvalidEmail;
/** @internal */
class RFCValidation implements EmailValidation
{
    /**
     * @var EmailParser|null
     */
    private $parser;
    /**
     * @var array
     */
    private $warnings = [];
    /**
     * @var InvalidEmail|null
     */
    private $error;
    public function isValid($email, EmailLexer $emailLexer)
    {
        $this->parser = new EmailParser($emailLexer);
        try {
            $this->parser->parse((string) $email);
        } catch (InvalidEmail $invalid) {
            $this->error = $invalid;
            return \false;
        }
        $this->warnings = $this->parser->getWarnings();
        return \true;
    }
    public function getError()
    {
        return $this->error;
    }
    public function getWarnings()
    {
        return $this->warnings;
    }
}
