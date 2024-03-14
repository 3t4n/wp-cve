<?php

namespace Modular\ConnectorDependencies\Illuminate\Contracts\Validation;

/** @internal */
interface ValidatorAwareRule
{
    /**
     * Set the current validator.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return $this
     */
    public function setValidator($validator);
}
