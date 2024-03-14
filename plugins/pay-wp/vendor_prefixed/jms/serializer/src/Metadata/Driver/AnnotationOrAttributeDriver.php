<?php

declare (strict_types=1);
namespace WPPayVendor\JMS\Serializer\Metadata\Driver;

use WPPayVendor\Doctrine\Common\Annotations\Reader;
use WPPayVendor\JMS\Serializer\Annotation\Accessor;
use WPPayVendor\JMS\Serializer\Annotation\AccessorOrder;
use WPPayVendor\JMS\Serializer\Annotation\AccessType;
use WPPayVendor\JMS\Serializer\Annotation\Discriminator;
use WPPayVendor\JMS\Serializer\Annotation\Exclude;
use WPPayVendor\JMS\Serializer\Annotation\ExclusionPolicy;
use WPPayVendor\JMS\Serializer\Annotation\Expose;
use WPPayVendor\JMS\Serializer\Annotation\Groups;
use WPPayVendor\JMS\Serializer\Annotation\Inline;
use WPPayVendor\JMS\Serializer\Annotation\MaxDepth;
use WPPayVendor\JMS\Serializer\Annotation\PostDeserialize;
use WPPayVendor\JMS\Serializer\Annotation\PostSerialize;
use WPPayVendor\JMS\Serializer\Annotation\PreSerialize;
use WPPayVendor\JMS\Serializer\Annotation\ReadOnlyProperty;
use WPPayVendor\JMS\Serializer\Annotation\SerializedName;
use WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute;
use WPPayVendor\JMS\Serializer\Annotation\Since;
use WPPayVendor\JMS\Serializer\Annotation\SkipWhenEmpty;
use WPPayVendor\JMS\Serializer\Annotation\Type;
use WPPayVendor\JMS\Serializer\Annotation\Until;
use WPPayVendor\JMS\Serializer\Annotation\VirtualProperty;
use WPPayVendor\JMS\Serializer\Annotation\XmlAttribute;
use WPPayVendor\JMS\Serializer\Annotation\XmlAttributeMap;
use WPPayVendor\JMS\Serializer\Annotation\XmlDiscriminator;
use WPPayVendor\JMS\Serializer\Annotation\XmlElement;
use WPPayVendor\JMS\Serializer\Annotation\XmlKeyValuePairs;
use WPPayVendor\JMS\Serializer\Annotation\XmlList;
use WPPayVendor\JMS\Serializer\Annotation\XmlMap;
use WPPayVendor\JMS\Serializer\Annotation\XmlNamespace;
use WPPayVendor\JMS\Serializer\Annotation\XmlRoot;
use WPPayVendor\JMS\Serializer\Annotation\XmlValue;
use WPPayVendor\JMS\Serializer\Exception\InvalidMetadataException;
use WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface;
use WPPayVendor\JMS\Serializer\Metadata\ClassMetadata;
use WPPayVendor\JMS\Serializer\Metadata\ExpressionPropertyMetadata;
use WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata;
use WPPayVendor\JMS\Serializer\Metadata\VirtualPropertyMetadata;
use WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface;
use WPPayVendor\JMS\Serializer\Type\Parser;
use WPPayVendor\JMS\Serializer\Type\ParserInterface;
use WPPayVendor\Metadata\ClassMetadata as BaseClassMetadata;
use WPPayVendor\Metadata\Driver\DriverInterface;
use WPPayVendor\Metadata\MethodMetadata;
class AnnotationOrAttributeDriver implements \WPPayVendor\Metadata\Driver\DriverInterface
{
    use ExpressionMetadataTrait;
    /**
     * @var ParserInterface
     */
    private $typeParser;
    /**
     * @var PropertyNamingStrategyInterface
     */
    private $namingStrategy;
    /**
     * @var Reader
     */
    private $reader;
    public function __construct(\WPPayVendor\JMS\Serializer\Naming\PropertyNamingStrategyInterface $namingStrategy, ?\WPPayVendor\JMS\Serializer\Type\ParserInterface $typeParser = null, ?\WPPayVendor\JMS\Serializer\Expression\CompilableExpressionEvaluatorInterface $expressionEvaluator = null, ?\WPPayVendor\Doctrine\Common\Annotations\Reader $reader = null)
    {
        $this->typeParser = $typeParser ?: new \WPPayVendor\JMS\Serializer\Type\Parser();
        $this->namingStrategy = $namingStrategy;
        $this->expressionEvaluator = $expressionEvaluator;
        $this->reader = $reader;
    }
    public function loadMetadataForClass(\ReflectionClass $class) : ?\WPPayVendor\Metadata\ClassMetadata
    {
        $configured = \false;
        $classMetadata = new \WPPayVendor\JMS\Serializer\Metadata\ClassMetadata($name = $class->name);
        $fileResource = $class->getFilename();
        if (\false !== $fileResource) {
            $classMetadata->fileResources[] = $fileResource;
        }
        $propertiesMetadata = [];
        $propertiesAnnotations = [];
        $exclusionPolicy = \WPPayVendor\JMS\Serializer\Annotation\ExclusionPolicy::NONE;
        $excludeAll = \false;
        $classAccessType = \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata::ACCESS_TYPE_PROPERTY;
        $readOnlyClass = \false;
        foreach ($this->getClassAnnotations($class) as $annot) {
            $configured = \true;
            if ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\ExclusionPolicy) {
                $exclusionPolicy = $annot->policy;
            } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\XmlRoot) {
                $classMetadata->xmlRootName = $annot->name;
                $classMetadata->xmlRootNamespace = $annot->namespace;
                $classMetadata->xmlRootPrefix = $annot->prefix;
            } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\XmlNamespace) {
                $classMetadata->registerNamespace($annot->uri, $annot->prefix);
            } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\Exclude) {
                if (null !== $annot->if) {
                    $classMetadata->excludeIf = $this->parseExpression($annot->if);
                } else {
                    $excludeAll = \true;
                }
            } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\AccessType) {
                $classAccessType = $annot->type;
            } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\ReadOnlyProperty) {
                $readOnlyClass = \true;
            } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\AccessorOrder) {
                $classMetadata->setAccessorOrder($annot->order, $annot->custom);
            } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\Discriminator) {
                if ($annot->disabled) {
                    $classMetadata->discriminatorDisabled = \true;
                } else {
                    $classMetadata->setDiscriminator($annot->field, $annot->map, $annot->groups);
                }
            } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\XmlDiscriminator) {
                $classMetadata->xmlDiscriminatorAttribute = (bool) $annot->attribute;
                $classMetadata->xmlDiscriminatorCData = (bool) $annot->cdata;
                $classMetadata->xmlDiscriminatorNamespace = $annot->namespace ? (string) $annot->namespace : null;
            } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\VirtualProperty) {
                $virtualPropertyMetadata = new \WPPayVendor\JMS\Serializer\Metadata\ExpressionPropertyMetadata($name, $annot->name, $this->parseExpression($annot->exp));
                $propertiesMetadata[] = $virtualPropertyMetadata;
                $propertiesAnnotations[] = $annot->options;
            }
        }
        foreach ($class->getMethods() as $method) {
            if ($method->class !== $name) {
                continue;
            }
            $methodAnnotations = $this->getMethodAnnotations($method);
            foreach ($methodAnnotations as $annot) {
                $configured = \true;
                if ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\PreSerialize) {
                    $classMetadata->addPreSerializeMethod(new \WPPayVendor\Metadata\MethodMetadata($name, $method->name));
                    continue 2;
                } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\PostDeserialize) {
                    $classMetadata->addPostDeserializeMethod(new \WPPayVendor\Metadata\MethodMetadata($name, $method->name));
                    continue 2;
                } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\PostSerialize) {
                    $classMetadata->addPostSerializeMethod(new \WPPayVendor\Metadata\MethodMetadata($name, $method->name));
                    continue 2;
                } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\VirtualProperty) {
                    $virtualPropertyMetadata = new \WPPayVendor\JMS\Serializer\Metadata\VirtualPropertyMetadata($name, $method->name);
                    $propertiesMetadata[] = $virtualPropertyMetadata;
                    $propertiesAnnotations[] = $methodAnnotations;
                    continue 2;
                }
            }
        }
        if (!$excludeAll) {
            foreach ($class->getProperties() as $property) {
                if ($property->class !== $name || isset($property->info) && $property->info['class'] !== $name) {
                    continue;
                }
                $propertiesMetadata[] = new \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata($name, $property->getName());
                $propertiesAnnotations[] = $this->getPropertyAnnotations($property);
            }
            foreach ($propertiesMetadata as $propertyKey => $propertyMetadata) {
                $isExclude = \false;
                $isExpose = $propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\VirtualPropertyMetadata || $propertyMetadata instanceof \WPPayVendor\JMS\Serializer\Metadata\ExpressionPropertyMetadata;
                $propertyMetadata->readOnly = $propertyMetadata->readOnly || $readOnlyClass;
                $accessType = $classAccessType;
                $accessor = [null, null];
                $propertyAnnotations = $propertiesAnnotations[$propertyKey];
                foreach ($propertyAnnotations as $annot) {
                    $configured = \true;
                    if ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\Since) {
                        $propertyMetadata->sinceVersion = $annot->version;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\Until) {
                        $propertyMetadata->untilVersion = $annot->version;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\SerializedName) {
                        $propertyMetadata->serializedName = $annot->name;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\SkipWhenEmpty) {
                        $propertyMetadata->skipWhenEmpty = \true;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\Expose) {
                        $isExpose = \true;
                        if (null !== $annot->if) {
                            $propertyMetadata->excludeIf = $this->parseExpression('!(' . $annot->if . ')');
                        }
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\Exclude) {
                        if (null !== $annot->if) {
                            $propertyMetadata->excludeIf = $this->parseExpression($annot->if);
                        } else {
                            $isExclude = \true;
                        }
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\Type) {
                        $propertyMetadata->setType($this->typeParser->parse($annot->name));
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\XmlElement) {
                        $propertyMetadata->xmlAttribute = \false;
                        $propertyMetadata->xmlElementCData = $annot->cdata;
                        $propertyMetadata->xmlNamespace = $annot->namespace;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\XmlList) {
                        $propertyMetadata->xmlCollection = \true;
                        $propertyMetadata->xmlCollectionInline = $annot->inline;
                        $propertyMetadata->xmlEntryName = $annot->entry;
                        $propertyMetadata->xmlEntryNamespace = $annot->namespace;
                        $propertyMetadata->xmlCollectionSkipWhenEmpty = $annot->skipWhenEmpty;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\XmlMap) {
                        $propertyMetadata->xmlCollection = \true;
                        $propertyMetadata->xmlCollectionInline = $annot->inline;
                        $propertyMetadata->xmlEntryName = $annot->entry;
                        $propertyMetadata->xmlEntryNamespace = $annot->namespace;
                        $propertyMetadata->xmlKeyAttribute = $annot->keyAttribute;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\XmlKeyValuePairs) {
                        $propertyMetadata->xmlKeyValuePairs = \true;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\XmlAttribute) {
                        $propertyMetadata->xmlAttribute = \true;
                        $propertyMetadata->xmlNamespace = $annot->namespace;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\XmlValue) {
                        $propertyMetadata->xmlValue = \true;
                        $propertyMetadata->xmlElementCData = $annot->cdata;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\AccessType) {
                        $accessType = $annot->type;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\ReadOnlyProperty) {
                        $propertyMetadata->readOnly = $annot->readOnly;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\Accessor) {
                        $accessor = [$annot->getter, $annot->setter];
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\Groups) {
                        $propertyMetadata->groups = $annot->groups;
                        foreach ((array) $propertyMetadata->groups as $groupName) {
                            if (\false !== \strpos($groupName, ',')) {
                                throw new \WPPayVendor\JMS\Serializer\Exception\InvalidMetadataException(\sprintf('Invalid group name "%s" on "%s", did you mean to create multiple groups?', \implode(', ', $propertyMetadata->groups), $propertyMetadata->class . '->' . $propertyMetadata->name));
                            }
                        }
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\Inline) {
                        $propertyMetadata->inline = \true;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\XmlAttributeMap) {
                        $propertyMetadata->xmlAttributeMap = \true;
                    } elseif ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\MaxDepth) {
                        $propertyMetadata->maxDepth = $annot->depth;
                    }
                }
                if ($propertyMetadata->inline) {
                    $classMetadata->isList = $classMetadata->isList || \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata::isCollectionList($propertyMetadata->type);
                    $classMetadata->isMap = $classMetadata->isMap || \WPPayVendor\JMS\Serializer\Metadata\PropertyMetadata::isCollectionMap($propertyMetadata->type);
                    if ($classMetadata->isMap && $classMetadata->isList) {
                        throw new \WPPayVendor\JMS\Serializer\Exception\InvalidMetadataException('Can not have an inline map and and inline map on the same class');
                    }
                }
                if (!$propertyMetadata->serializedName) {
                    $propertyMetadata->serializedName = $this->namingStrategy->translateName($propertyMetadata);
                }
                foreach ($propertyAnnotations as $annot) {
                    if ($annot instanceof \WPPayVendor\JMS\Serializer\Annotation\VirtualProperty && null !== $annot->name) {
                        $propertyMetadata->name = $annot->name;
                    }
                }
                if (\WPPayVendor\JMS\Serializer\Annotation\ExclusionPolicy::NONE === $exclusionPolicy && !$isExclude || \WPPayVendor\JMS\Serializer\Annotation\ExclusionPolicy::ALL === $exclusionPolicy && $isExpose) {
                    $propertyMetadata->setAccessor($accessType, $accessor[0], $accessor[1]);
                    $classMetadata->addPropertyMetadata($propertyMetadata);
                }
            }
        }
        // if (!$configured) {
        // return null;
        // uncomment the above line afetr a couple of months
        // }
        return $classMetadata;
    }
    /**
     * @return list<SerializerAttribute>
     */
    protected function getClassAnnotations(\ReflectionClass $class) : array
    {
        $annotations = [];
        if (\PHP_VERSION_ID >= 80000) {
            $annotations = \array_map(static fn(\ReflectionAttribute $attribute): object => $attribute->newInstance(), $class->getAttributes(\WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute::class, \ReflectionAttribute::IS_INSTANCEOF));
        }
        if (null !== $this->reader) {
            $annotations = \array_merge($annotations, $this->reader->getClassAnnotations($class));
        }
        return $annotations;
    }
    /**
     * @return list<SerializerAttribute>
     */
    protected function getMethodAnnotations(\ReflectionMethod $method) : array
    {
        $annotations = [];
        if (\PHP_VERSION_ID >= 80000) {
            $annotations = \array_map(static fn(\ReflectionAttribute $attribute): object => $attribute->newInstance(), $method->getAttributes(\WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute::class, \ReflectionAttribute::IS_INSTANCEOF));
        }
        if (null !== $this->reader) {
            $annotations = \array_merge($annotations, $this->reader->getMethodAnnotations($method));
        }
        return $annotations;
    }
    /**
     * @return list<SerializerAttribute>
     */
    protected function getPropertyAnnotations(\ReflectionProperty $property) : array
    {
        $annotations = [];
        if (\PHP_VERSION_ID >= 80000) {
            $annotations = \array_map(static fn(\ReflectionAttribute $attribute): object => $attribute->newInstance(), $property->getAttributes(\WPPayVendor\JMS\Serializer\Annotation\SerializerAttribute::class, \ReflectionAttribute::IS_INSTANCEOF));
        }
        if (null !== $this->reader) {
            $annotations = \array_merge($annotations, $this->reader->getPropertyAnnotations($property));
        }
        return $annotations;
    }
}
