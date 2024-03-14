<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Captcha;

use Serps\Exception\CaptchaSolver\CaptchaNotSolvableException;
use Serps\Exception\CaptchaSolver\UnknownCaptchaTypeException;
use Serps\Exception\TimeoutException;

interface CaptchaSolverInterface
{
    /**
     * Solve a captcha and returns data depending on the captcha type
     * @param CaptchaResponse $captchaResponse
     * @return mixed
     * @throws CaptchaNotSolvableException
     * @throws UnknownCaptchaTypeException
     * @throws TimeoutException
     */
    public function solve(CaptchaResponse $captchaResponse);

    /**
     * Submit a captcha for resolution and returns a helper to get the output on an async way
     * @param CaptchaResponse $captchaResponse
     * @return AsyncCaptchaSolvingInterface
     * @throws CaptchaNotSolvableException
     * @throws UnknownCaptchaTypeException
     */
    public function solveAsync(CaptchaResponse $captchaResponse);
}
