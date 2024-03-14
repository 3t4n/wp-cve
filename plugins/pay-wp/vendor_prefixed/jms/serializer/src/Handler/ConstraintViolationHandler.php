<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Handler;

use WPPayVendor\JMS\Serializer\GraphNavigatorInterface;
use WPPayVendor\JMS\Serializer\SerializationContext;
use WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface;
use WPPayVendor\JMS\Serializer\XmlSerializationVisitor;
use WPPayVendor\Symfony\Component\Validator\ConstraintViolation;
use WPPayVendor\Symfony\Component\Validator\ConstraintViolationList;
final class ConstraintViolationHandler implements \WPPayVendor\JMS\Serializer\Handler\SubscribingHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = [];
        $formats = ['xml', 'json'];
        $types = [\WPPayVendor\Symfony\Component\Validator\ConstraintViolationList::class => 'serializeList', \WPPayVendor\Symfony\Component\Validator\ConstraintViolation::class => 'serializeViolation'];
        foreach ($types as $type => $method) {
            foreach ($formats as $format) {
                $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'type' => $type, 'format' => $format, 'method' => $method . 'To' . $format];
            }
        }
        return $methods;
    }
    public function serializeListToXml(\WPPayVendor\JMS\Serializer\XmlSerializationVisitor $visitor, \WPPayVendor\Symfony\Component\Validator\ConstraintViolationList $list, array $type) : void
    {
        $currentNode = $visitor->getCurrentNode();
        if (!$currentNode) {
            $visitor->createRoot();
        }
        foreach ($list as $violation) {
            $this->serializeViolationToXml($visitor, $violation);
        }
    }
    /**
     * @return array|\ArrayObject
     */
    public function serializeListToJson(\WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface $visitor, \WPPayVendor\Symfony\Component\Validator\ConstraintViolationList $list, array $type, \WPPayVendor\JMS\Serializer\SerializationContext $context)
    {
        return $visitor->visitArray(\iterator_to_array($list), $type);
    }
    public function serializeViolationToXml(\WPPayVendor\JMS\Serializer\XmlSerializationVisitor $visitor, \WPPayVendor\Symfony\Component\Validator\ConstraintViolation $violation, ?array $type = null) : void
    {
        $violationNode = $visitor->getDocument()->createElement('violation');
        $parent = $visitor->getCurrentNode();
        if (!$parent) {
            $visitor->setCurrentAndRootNode($violationNode);
        } else {
            $parent->appendChild($violationNode);
        }
        $violationNode->setAttribute('property_path', $violation->getPropertyPath());
        $violationNode->appendChild($messageNode = $visitor->getDocument()->createElement('message'));
        $messageNode->appendChild($visitor->getDocument()->createCDATASection($violation->getMessage()));
    }
    public function serializeViolationToJson(\WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface $visitor, \WPPayVendor\Symfony\Component\Validator\ConstraintViolation $violation, ?array $type = null) : array
    {
        return ['property_path' => $violation->getPropertyPath(), 'message' => $violation->getMessage()];
    }
}
