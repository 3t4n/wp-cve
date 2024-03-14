<?php

namespace Modular\ConnectorDependencies\Egulias\EmailValidator\Validation;

use Modular\ConnectorDependencies\Egulias\EmailValidator\EmailLexer;
use Modular\ConnectorDependencies\Egulias\EmailValidator\Exception\InvalidEmail;
use Modular\ConnectorDependencies\Egulias\EmailValidator\Validation\Error\RFCWarnings;
/** @internal */
class NoRFCWarningsValidation extends RFCValidation
{
    /**
     * @var InvalidEmail|null
     */
    private $error;
    /**
     * {@inheritdoc}
     */
    public function isValid($email, EmailLexer $emailLexer)
    {
        if (!parent::isValid($email, $emailLexer)) {
            return \false;
        }
        if (empty($this->getWarnings())) {
            return \true;
        }
        $this->error = new RFCWarnings();
        return \false;
    }
    /**
     * {@inheritdoc}
     */
    public function getError()
    {
        return $this->error ?: parent::getError();
    }
}
