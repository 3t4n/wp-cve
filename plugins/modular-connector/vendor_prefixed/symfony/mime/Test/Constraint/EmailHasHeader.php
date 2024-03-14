<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\Mime\Test\Constraint;

use Modular\ConnectorDependencies\PHPUnit\Framework\Constraint\Constraint;
use Modular\ConnectorDependencies\Symfony\Component\Mime\RawMessage;
/** @internal */
final class EmailHasHeader extends Constraint
{
    private $headerName;
    public function __construct(string $headerName)
    {
        $this->headerName = $headerName;
    }
    /**
     * {@inheritdoc}
     */
    public function toString() : string
    {
        return \sprintf('has header "%s"', $this->headerName);
    }
    /**
     * @param RawMessage $message
     *
     * {@inheritdoc}
     */
    protected function matches($message) : bool
    {
        if (RawMessage::class === \get_class($message)) {
            throw new \LogicException('Unable to test a message header on a RawMessage instance.');
        }
        return $message->getHeaders()->has($this->headerName);
    }
    /**
     * @param RawMessage $message
     *
     * {@inheritdoc}
     */
    protected function failureDescription($message) : string
    {
        return 'the Email ' . $this->toString();
    }
}
