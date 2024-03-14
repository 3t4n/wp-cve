<?php

class RsisValidationUtils {
    
    /**
     *
     *  Validates $input array against validation rules defined in $validators array.
     *
     *  Any key found in $input array that does not have a corresponding key in $validators array
     *  will be considered invalid.
     *
     *  Example:
     *
     *    $input = array(
     *        'name'  => 'Bob Jones',
     *        'age'   => 35,
     *        'email' => 'bob@jones.egg',
     *    );
     *
     *    $errorMessage = ValidationUtils::applyValidators($input, array(
     *        'name'  => array('type' => 'string'),
     *        'age'   => array('type' => 'integer'),
     *        'email' => array('regex' => '^[^@]+@[^.@]+\\.[^@]+$'),
     *        'phone' => array('regex' => '/\d+/', 'optional' => true),
     *    ));
     *
     *    if (isset($errorMessage)) {
     *       throw new \Exception($errorMessage);
     *    }
     *   
     *  Possible validation rules:
     *
     *      type     - checks result of gettype() on value
     *      class    - checks result of get_class() on value
     *      regex    - checks that value is string and that string matches provided regex or at least one of an array of regexes
     *      func     - passes value to function, function may return NULL for valid or a string error message for invalid
     *      optional - key can be missing from input array
     *      nullable - value can be null
     *
    */
    
    const EMAIL          = '/^[^@]+@[^.@]+(\.[^.@]+)+$/'; // was: '/\@.+/'  must be at least m@m.c  something before the @, after the @, and after the .
    const PHONE          = '/^[\d\(\)\-]{10,}$/';
    const MONEY          = '/^\d+\.\d\d$/';
    const NEGATIVE_MONEY = '/^-\d+\.\d\d$/';
    const FLEXIBLE_MONEY = '/^(\.\d{1,2}|\d+(\.\d{1,2})?)$/'; // ".10" or "1" or "1.23"
    const FLEXIBLE_MONEY_ALLOW_NEGATIVE = '/^-?(\.\d{1,2}|\d+(\.\d{1,2})?)$/'; // ".10" or "1" or "1.23"
    const URL            = '/^http/';
    const DATE           = '/^(\d{4})-(\d\d)-(\d\d)$/';
    const DATETIME       = '/^(\d{4})-(\d\d)-(\d\d) (\d\d):(\d\d):(\d\d)$/';
    const MD5            = '/^[a-f0-9]{32}$/i';
    const NUMBER         = '/^\d+(\.\d+)?$/';
    const NUMBER_INTEGER = '/^\d+$/';
    const EMPTY_STRING   = '/^$/';
    const NON_EMPTY_STRING = '/^(?!\s*$).+/';

    const ISO_COUNTRY_TWO_DIGIT = '/^[A-Z][A-Z]$/';

    // REMS constants
    const INVOICE_NUMBER    = '/^([A-Z])*(([0-9a-zA-Z]){3})([0-9A-Z]{5})([a-zA-Z]{2})([0-9]{2})(-[A-Z]+)*$/';
    const CUSTOMER_ID       = '/^([A-Za-z0-9]){8}$/';
    const SERVICE_CODE      = '/^([A-Za-z0-9]){3}$/';

    const UK_MAIL_POSTAL_CODE_SPACES_REQUIRED = '/^([A-Za-z][A-Ha-hJ-Yj-y]?[0-9][A-Za-z0-9]? [0-9][A-Za-z]{2}|[Gg][Ii][Rr] 0[Aa]{2})$/';
    const UK_MAIL_POSTAL_CODE_SPACES_OPTIONAL = '/^([A-Za-z][A-Ha-hJ-Yj-y]?[0-9][A-Za-z0-9]? ?[0-9][A-Za-z]{2}|[Gg][Ii][Rr] ?0[Aa]{2})$/';
    
    public static function applyValidators(array $input, array $validators) {
        // first, make sure that the validators themselves are valid
        foreach ($validators as $key => &$validationRule) {
            if (gettype($validationRule) !== 'array') {
                throw new \Exception('each validation rule should be an associative array()');
            }
            
            if (isset($validationRule['regex']) && isset($validationRule['type'])) {
                throw new \Exception('type cannot be specified when a regex is present - regex expects value to be a string');
            }
            
            if (isset($validationRule['regex']) && isset($validationRule['class'])) {
                throw new \Exception('class cannot be specified when a regex is present - regex expects value to be a string');
            }
            
            if (isset($validationRule['type']) && gettype($validationRule['type']) !== 'string') {
                throw new \Exception('"type" must be a string');
            }
            
            if (isset($validationRule['class']) && gettype($validationRule['class']) !== 'string') {
                throw new \Exception('"class" must be a string');
            }
            
            if (isset($validationRule['class']) && isset($validationRule['type'])) {
                throw new \Exception('validation rules cannot have both a type and class');
            }
            
            $countValueRules = 0;
            $countValueRules += isset($validationRule['func']) ? 1 : 0;
            $countValueRules += isset($validationRule['regex']) ? 1 : 0;
            $countValueRules += isset($validationRule['oneOf']) ? 1 : 0;
            
            if ($countValueRules > 1) {
                throw new \Exception('validation rules may only have one of regex, func, or oneOf');
            }
            
            if (isset($validationRule['func']) && !is_callable($validationRule['func'])) {
                throw new \Exception('"func" must be a function');
            }
            
            if (isset($validationRule['regex']) && (gettype($validationRule['regex']) !== 'string' && gettype($validationRule['regex']) !== 'array')) {
                throw new \Exception('"regex" must be a string or an array of regular expressions');
            }
            
            if (isset($validationRule['oneOf']) && gettype($validationRule['oneOf']) !== 'array') {
                throw new \Exception('"oneOf" must be an array');
            }
            
            if (isset($validationRule['optional'])) {
                if (gettype($validationRule['optional']) !== 'boolean') {
                    throw new \Exception('"optional" must be a boolean');
                }
            }
            else {
                // keys are not optional by default
                $validationRule['optional'] = false;
            }
            
            if (isset($validationRule['nullable'])) {
                if (gettype($validationRule['nullable']) !== 'boolean') {
                    throw new \Exception('"nullable" must be a boolean');
                }
            }
            else {
                // keys are not nullable by default
                $validationRule['nullable'] = false;
            }
        }
        
        // next, make sure that all of the input keys have a matching validator
        foreach ($input as $key => $value) {
            if (!isset($validators[$key])) {
                return $key . ' is not a valid key';
            }
        }
        
        // finally, apply the validators
        foreach ($validators as $key => &$validationRule) {
            if (!array_key_exists($key, $input)) {
                if (!$validationRule['optional']) {
                    return $key . ' is required';
                }
                else {
                    // the key is optional
                    // skip any other attempt to validate
                    continue;
                }
            }
            
            if (is_null($input[$key])) {
                if (!$validationRule['nullable']) {
                    return $key . ' cannot be null';
                }
                else {
                    // the key can be null
                    // skip any other attempt to validate
                    continue;
                }
            }
            
            if (isset($validationRule['regex']) && gettype($input[$key]) !== 'string') {
                return $key . ' must be a string not a ' . gettype($input[$key]);
            }
            
            if (isset($validationRule['regex'])) {
                if (gettype($validationRule['regex']) === 'string' && !preg_match($validationRule['regex'], $input[$key])) {
                    return $key . ' must match ' . $validationRule['regex'] . '; was "' . addslashes($input[$key]) . '"';
                }
                else if (gettype($validationRule['regex']) === 'array') {
                    $matchesOne = false;
                    foreach ($validationRule['regex'] as $regex) {
                        if (preg_match($regex, $input[$key])) {
                            $matchesOne = true;
                        }
                    }
                    
                    if (!$matchesOne) {
                        return $key . ' must match ' . implode(' or ', $validationRule['regex']) . '; was "' . addslashes($input[$key]) . '"';
                    }
                }
            }
            
            if (isset($validationRule['type']) && gettype($input[$key]) !== $validationRule['type']) {
                return $key . ' must be of type ' . $validationRule['type'] . '; was "' . gettype($input[$key]) . '"';
            }
            
            if (isset($validationRule['class']) && get_class($input[$key]) !== $validationRule['class']) {
                return $key . ' must be an instance of ' . $validationRule['class'];
            }
            
            if (isset($validationRule['func'])) {
                $funcError = call_user_func($validationRule['func'], $input[$key], $key);
                if ($funcError) {
                    return $funcError;
                }
            }
            
            if (isset($validationRule['oneOf']) && !in_array($input[$key], $validationRule['oneOf'], true)) {
                return $key . ' must be one of (' . implode(', ', $validationRule['oneOf']) . '); was "' . addslashes($input[$key]) . '"';
            }
        }
        
        // if we got here, everything was valid
        return null;
    }
    
    public static function assertValidators(array $input, array $validators) {
        $errorMessage = self::applyValidators($input, $validators);
        
        if (! is_null($errorMessage)) {
            throw new \Exception($errorMessage);
        }
    }
}
