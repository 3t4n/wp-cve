<?php
/**
 * Task Base Model
 */

namespace FDSUS\Model;

/**
 * Class Signup Base
 *
 * @package FDSUS\Model
 */
class SignupBase extends Base
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Can the current user edit the signup?
     * (placeholder for Pro method for any calls within free version)
     *
     * @return bool
     */
    public function canCurrentUserEdit()
    {
        return false;
    }
}
