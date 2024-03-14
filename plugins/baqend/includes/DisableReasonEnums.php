<?php

namespace Baqend\WordPress;

use MyCLabs\Enum\Enum;

/**
 * A class representing reasons for Speed Kit disabled state.
 *
 * Created on 2020-03-12.
 *
 * @author Kevin Twesten
 */
class DisableReasonEnums extends Enum
{
    const MANUAL = 'manual';
    const NONE = 'none';
    const INITIAL = 'inital';
    const REVALIDATION_ERROR = 'revalidation_error';
}
