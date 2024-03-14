<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Handler;

use WPPayVendor\JMS\Serializer\GraphNavigatorInterface;
use WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface;
use WPPayVendor\JMS\Serializer\XmlSerializationVisitor;
use WPPayVendor\Symfony\Component\Form\Form;
use WPPayVendor\Symfony\Component\Form\FormError;
use WPPayVendor\Symfony\Component\Form\FormInterface;
use WPPayVendor\Symfony\Component\Translation\TranslatorInterface;
use WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface as TranslatorContract;
use function get_class;
final class FormErrorHandler implements \WPPayVendor\JMS\Serializer\Handler\SubscribingHandlerInterface
{
    /**
     * @var TranslatorInterface|TranslatorContract|null
     */
    private $translator;
    /**
     * @var string
     */
    private $translationDomain;
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        $methods = [];
        foreach (['xml', 'json'] as $format) {
            $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'type' => \WPPayVendor\Symfony\Component\Form\Form::class, 'format' => $format];
            $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'type' => \WPPayVendor\Symfony\Component\Form\FormInterface::class, 'format' => $format, 'method' => 'serializeFormTo' . \ucfirst($format)];
            $methods[] = ['direction' => \WPPayVendor\JMS\Serializer\GraphNavigatorInterface::DIRECTION_SERIALIZATION, 'type' => \WPPayVendor\Symfony\Component\Form\FormError::class, 'format' => $format];
        }
        return $methods;
    }
    public function __construct(?object $translator = null, string $translationDomain = 'validators')
    {
        if (null !== $translator && (!$translator instanceof \WPPayVendor\Symfony\Component\Translation\TranslatorInterface && !$translator instanceof \WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface)) {
            throw new \InvalidArgumentException(\sprintf('The first argument passed to %s must be instance of %s or %s, %s given', self::class, \WPPayVendor\Symfony\Component\Translation\TranslatorInterface::class, \WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface::class, \get_class($translator)));
        }
        $this->translator = $translator;
        $this->translationDomain = $translationDomain;
    }
    /**
     * @param array $type
     */
    public function serializeFormToXml(\WPPayVendor\JMS\Serializer\XmlSerializationVisitor $visitor, \WPPayVendor\Symfony\Component\Form\FormInterface $form, array $type) : \DOMElement
    {
        $formNode = $visitor->getDocument()->createElement('form');
        $formNode->setAttribute('name', $form->getName());
        $formNode->appendChild($errorsNode = $visitor->getDocument()->createElement('errors'));
        foreach ($form->getErrors() as $error) {
            $errorNode = $visitor->getDocument()->createElement('entry');
            $errorNode->appendChild($this->serializeFormErrorToXml($visitor, $error, []));
            $errorsNode->appendChild($errorNode);
        }
        foreach ($form->all() as $child) {
            if ($child instanceof \WPPayVendor\Symfony\Component\Form\Form) {
                if (null !== ($node = $this->serializeFormToXml($visitor, $child, []))) {
                    $formNode->appendChild($node);
                }
            }
        }
        return $formNode;
    }
    /**
     * @param array $type
     */
    public function serializeFormToJson(\WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface $visitor, \WPPayVendor\Symfony\Component\Form\FormInterface $form, array $type) : \ArrayObject
    {
        return $this->convertFormToArray($visitor, $form);
    }
    /**
     * @param array $type
     */
    public function serializeFormErrorToXml(\WPPayVendor\JMS\Serializer\XmlSerializationVisitor $visitor, \WPPayVendor\Symfony\Component\Form\FormError $formError, array $type) : \DOMCdataSection
    {
        return $visitor->getDocument()->createCDATASection($this->getErrorMessage($formError));
    }
    /**
     * @param array $type
     */
    public function serializeFormErrorToJson(\WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface $visitor, \WPPayVendor\Symfony\Component\Form\FormError $formError, array $type) : string
    {
        return $this->getErrorMessage($formError);
    }
    private function getErrorMessage(\WPPayVendor\Symfony\Component\Form\FormError $error) : ?string
    {
        if (null === $this->translator) {
            return $error->getMessage();
        }
        if (null !== $error->getMessagePluralization()) {
            if ($this->translator instanceof \WPPayVendor\Symfony\Contracts\Translation\TranslatorInterface) {
                return $this->translator->trans($error->getMessageTemplate(), ['%count%' => $error->getMessagePluralization()] + $error->getMessageParameters(), $this->translationDomain);
            } else {
                return $this->translator->transChoice($error->getMessageTemplate(), $error->getMessagePluralization(), $error->getMessageParameters(), $this->translationDomain);
            }
        }
        return $this->translator->trans($error->getMessageTemplate(), $error->getMessageParameters(), $this->translationDomain);
    }
    private function convertFormToArray(\WPPayVendor\JMS\Serializer\Visitor\SerializationVisitorInterface $visitor, \WPPayVendor\Symfony\Component\Form\FormInterface $data) : \ArrayObject
    {
        /** @var \ArrayObject{errors?:array<string>,children?:array<string,\ArrayObject>} $form */
        $form = new \ArrayObject();
        $errors = [];
        foreach ($data->getErrors() as $error) {
            $errors[] = $this->getErrorMessage($error);
        }
        if ($errors) {
            $form['errors'] = $errors;
        }
        $children = [];
        foreach ($data->all() as $child) {
            if ($child instanceof \WPPayVendor\Symfony\Component\Form\FormInterface) {
                $children[$child->getName()] = $this->convertFormToArray($visitor, $child);
            }
        }
        if ($children) {
            $form['children'] = $children;
        }
        return $form;
    }
}
