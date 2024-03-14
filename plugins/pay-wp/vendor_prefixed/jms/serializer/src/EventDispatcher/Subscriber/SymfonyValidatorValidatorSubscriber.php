<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\EventDispatcher\Subscriber;

use WPPayVendor\JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use WPPayVendor\JMS\Serializer\EventDispatcher\ObjectEvent;
use WPPayVendor\JMS\Serializer\Exception\ValidationFailedException;
use WPPayVendor\Symfony\Component\Validator\Validator\ValidatorInterface;
final class SymfonyValidatorValidatorSubscriber implements \WPPayVendor\JMS\Serializer\EventDispatcher\EventSubscriberInterface
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    public function __construct(\WPPayVendor\Symfony\Component\Validator\Validator\ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [['event' => 'serializer.post_deserialize', 'method' => 'onPostDeserialize']];
    }
    public function onPostDeserialize(\WPPayVendor\JMS\Serializer\EventDispatcher\ObjectEvent $event) : void
    {
        $context = $event->getContext();
        if ($context->getDepth() > 0) {
            return;
        }
        $validator = $this->validator;
        $groups = $context->hasAttribute('validation_groups') ? $context->getAttribute('validation_groups') : null;
        if (!$groups) {
            return;
        }
        $constraints = $context->hasAttribute('validation_constraints') ? $context->getAttribute('validation_constraints') : null;
        $list = $validator->validate($event->getObject(), $constraints, $groups);
        if ($list->count() > 0) {
            throw new \WPPayVendor\JMS\Serializer\Exception\ValidationFailedException($list);
        }
    }
}
