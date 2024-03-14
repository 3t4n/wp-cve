<?php

namespace CODNetwork\Services;

class CODN_Cookie_Service
{
    protected const MAX_ATTEMPT_REPORT_PER_DAYS = 3;

    protected const MAX_ATTEMPT_REPORT_PER_DAYS_FIELD = 'codn_A284B765773A7';

    /** @var CODN_Logger_Service */
    protected $codnLoggerService;

    public function __construct()
    {
        $this->codnLoggerService = new CODN_Logger_Service();
    }

    public function setAttempts(int $attempts = self::MAX_ATTEMPT_REPORT_PER_DAYS): bool
    {
        return setcookie(
            self::MAX_ATTEMPT_REPORT_PER_DAYS_FIELD,
            $attempts,
            time() + 86400
        );
    }

    public function getCurrentAttempts(): int
    {
        return (int) $_COOKIE[self::MAX_ATTEMPT_REPORT_PER_DAYS_FIELD];
    }

    public function doesNotHaveCookie(): bool
    {
        return !isset($_COOKIE[self::MAX_ATTEMPT_REPORT_PER_DAYS_FIELD]);
    }

    /**
     *  This function to check reached report limit
     *   return true when have ability to report
     *   return false when don't have ability to report
     *  Step
     *  - Generate new Cookie if don't exist
     *  - When MaxReport=0 show notification "Reached the limit of report per day"
     *  - When click report button increase value of Cookie MaxReport -1
     */
    public function hasReachedReportLimit(): bool
    {
        if ($this->doesNotHaveCookie()) {
            $this->setAttempts();

            return false;
        }

       return $this->getCurrentAttempts() <= 0;
    }

    public function decrementAttempts(): bool
    {
        $attempts = $this->getCurrentAttempts();

        return $this->setAttempts($attempts - 1);
    }
}
