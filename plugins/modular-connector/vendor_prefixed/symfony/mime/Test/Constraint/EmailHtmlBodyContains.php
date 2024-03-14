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
use Modular\ConnectorDependencies\Symfony\Component\Mime\Message;
use Modular\ConnectorDependencies\Symfony\Component\Mime\RawMessage;
/** @internal */
final class EmailHtmlBodyContains extends Constraint
{
    private $expectedText;
    public function __construct(string $expectedText)
    {
        $this->expectedText = $expectedText;
    }
    /**
     * {@inheritdoc}
     */
    public function toString() : string
    {
        return \sprintf('contains "%s"', $this->expectedText);
    }
    /**
     * {@inheritdoc}
     *
     * @param RawMessage $message
     */
    protected function matches($message) : bool
    {
        if (RawMessage::class === \get_class($message) || Message::class === \get_class($message)) {
            throw new \LogicException('Unable to test a message HTML body on a RawMessage or Message instance.');
        }
        return \false !== \mb_strpos($message->getHtmlBody(), $this->expectedText);
    }
    /**
     * {@inheritdoc}
     *
     * @param RawMessage $message
     */
    protected function failureDescription($message) : string
    {
        return 'the Email HTML body ' . $this->toString();
    }
}
