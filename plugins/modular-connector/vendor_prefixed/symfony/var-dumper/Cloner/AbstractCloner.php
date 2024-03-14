<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Modular\ConnectorDependencies\Symfony\Component\VarDumper\Cloner;

use Modular\ConnectorDependencies\Symfony\Component\VarDumper\Caster\Caster;
use Modular\ConnectorDependencies\Symfony\Component\VarDumper\Exception\ThrowingCasterException;
/**
 * AbstractCloner implements a generic caster mechanism for objects and resources.
 *
 * @author Nicolas Grekas <p@tchwork.com>
 * @internal
 */
abstract class AbstractCloner implements ClonerInterface
{
    public static $defaultCasters = ['__PHP_Incomplete_Class' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\Caster', 'castPhpIncompleteClass'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\CutStub' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'castStub'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\CutArrayStub' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'castCutArray'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ConstStub' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'castStub'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\EnumStub' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'castEnum'], 'Fiber' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\FiberCaster', 'castFiber'], 'Closure' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castClosure'], 'Generator' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castGenerator'], 'ReflectionType' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castType'], 'ReflectionAttribute' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castAttribute'], 'ReflectionGenerator' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castReflectionGenerator'], 'ReflectionClass' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castClass'], 'ReflectionClassConstant' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castClassConstant'], 'ReflectionFunctionAbstract' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castFunctionAbstract'], 'ReflectionMethod' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castMethod'], 'ReflectionParameter' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castParameter'], 'ReflectionProperty' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castProperty'], 'ReflectionReference' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castReference'], 'ReflectionExtension' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castExtension'], 'ReflectionZendExtension' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ReflectionCaster', 'castZendExtension'], 'Modular\\ConnectorDependencies\\Doctrine\\Common\\Persistence\\ObjectManager' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'cutInternals'], 'Modular\\ConnectorDependencies\\Doctrine\\Common\\Proxy\\Proxy' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DoctrineCaster', 'castCommonProxy'], 'Modular\\ConnectorDependencies\\Doctrine\\ORM\\Proxy\\Proxy' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DoctrineCaster', 'castOrmProxy'], 'Modular\\ConnectorDependencies\\Doctrine\\ORM\\PersistentCollection' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DoctrineCaster', 'castPersistentCollection'], 'Modular\\ConnectorDependencies\\Doctrine\\Persistence\\ObjectManager' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'cutInternals'], 'DOMException' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castException'], 'DOMStringList' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castLength'], 'DOMNameList' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castLength'], 'DOMImplementation' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castImplementation'], 'DOMImplementationList' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castLength'], 'DOMNode' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castNode'], 'DOMNameSpaceNode' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castNameSpaceNode'], 'DOMDocument' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castDocument'], 'DOMNodeList' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castLength'], 'DOMNamedNodeMap' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castLength'], 'DOMCharacterData' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castCharacterData'], 'DOMAttr' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castAttr'], 'DOMElement' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castElement'], 'DOMText' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castText'], 'DOMTypeinfo' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castTypeinfo'], 'DOMDomError' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castDomError'], 'DOMLocator' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castLocator'], 'DOMDocumentType' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castDocumentType'], 'DOMNotation' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castNotation'], 'DOMEntity' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castEntity'], 'DOMProcessingInstruction' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castProcessingInstruction'], 'DOMXPath' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DOMCaster', 'castXPath'], 'XMLReader' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\XmlReaderCaster', 'castXmlReader'], 'ErrorException' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ExceptionCaster', 'castErrorException'], 'Exception' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ExceptionCaster', 'castException'], 'Error' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ExceptionCaster', 'castError'], 'Modular\\ConnectorDependencies\\Symfony\\Bridge\\Monolog\\Logger' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'cutInternals'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\DependencyInjection\\ContainerInterface' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'cutInternals'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\EventDispatcher\\EventDispatcherInterface' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'cutInternals'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\HttpClient\\AmpHttpClient' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SymfonyCaster', 'castHttpClient'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\HttpClient\\CurlHttpClient' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SymfonyCaster', 'castHttpClient'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\HttpClient\\NativeHttpClient' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SymfonyCaster', 'castHttpClient'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\HttpClient\\Response\\AmpResponse' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SymfonyCaster', 'castHttpClientResponse'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\HttpClient\\Response\\CurlResponse' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SymfonyCaster', 'castHttpClientResponse'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\HttpClient\\Response\\NativeResponse' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SymfonyCaster', 'castHttpClientResponse'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\HttpFoundation\\Request' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SymfonyCaster', 'castRequest'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\Uid\\Ulid' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SymfonyCaster', 'castUlid'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\Uid\\Uuid' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SymfonyCaster', 'castUuid'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Exception\\ThrowingCasterException' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ExceptionCaster', 'castThrowingCasterException'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\TraceStub' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ExceptionCaster', 'castTraceStub'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\FrameStub' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ExceptionCaster', 'castFrameStub'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Cloner\\AbstractCloner' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'cutInternals'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\ErrorHandler\\Exception\\SilencedErrorContext' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ExceptionCaster', 'castSilencedErrorContext'], 'Modular\\ConnectorDependencies\\Imagine\\Image\\ImageInterface' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ImagineCaster', 'castImage'], 'Modular\\ConnectorDependencies\\Ramsey\\Uuid\\UuidInterface' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\UuidCaster', 'castRamseyUuid'], 'Modular\\ConnectorDependencies\\ProxyManager\\Proxy\\ProxyInterface' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ProxyManagerCaster', 'castProxy'], 'PHPUnit_Framework_MockObject_MockObject' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'cutInternals'], 'Modular\\ConnectorDependencies\\PHPUnit\\Framework\\MockObject\\MockObject' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'cutInternals'], 'Modular\\ConnectorDependencies\\PHPUnit\\Framework\\MockObject\\Stub' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'cutInternals'], 'Modular\\ConnectorDependencies\\Prophecy\\Prophecy\\ProphecySubjectInterface' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'cutInternals'], 'Modular\\ConnectorDependencies\\Mockery\\MockInterface' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\StubCaster', 'cutInternals'], 'PDO' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\PdoCaster', 'castPdo'], 'PDOStatement' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\PdoCaster', 'castPdoStatement'], 'AMQPConnection' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\AmqpCaster', 'castConnection'], 'AMQPChannel' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\AmqpCaster', 'castChannel'], 'AMQPQueue' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\AmqpCaster', 'castQueue'], 'AMQPExchange' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\AmqpCaster', 'castExchange'], 'AMQPEnvelope' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\AmqpCaster', 'castEnvelope'], 'ArrayObject' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SplCaster', 'castArrayObject'], 'ArrayIterator' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SplCaster', 'castArrayIterator'], 'SplDoublyLinkedList' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SplCaster', 'castDoublyLinkedList'], 'SplFileInfo' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SplCaster', 'castFileInfo'], 'SplFileObject' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SplCaster', 'castFileObject'], 'SplHeap' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SplCaster', 'castHeap'], 'SplObjectStorage' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SplCaster', 'castObjectStorage'], 'SplPriorityQueue' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SplCaster', 'castHeap'], 'OuterIterator' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SplCaster', 'castOuterIterator'], 'WeakReference' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\SplCaster', 'castWeakReference'], 'Redis' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RedisCaster', 'castRedis'], 'RedisArray' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RedisCaster', 'castRedisArray'], 'RedisCluster' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RedisCaster', 'castRedisCluster'], 'DateTimeInterface' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DateCaster', 'castDateTime'], 'DateInterval' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DateCaster', 'castInterval'], 'DateTimeZone' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DateCaster', 'castTimeZone'], 'DatePeriod' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DateCaster', 'castPeriod'], 'GMP' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\GmpCaster', 'castGmp'], 'MessageFormatter' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\IntlCaster', 'castMessageFormatter'], 'NumberFormatter' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\IntlCaster', 'castNumberFormatter'], 'IntlTimeZone' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\IntlCaster', 'castIntlTimeZone'], 'IntlCalendar' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\IntlCaster', 'castIntlCalendar'], 'IntlDateFormatter' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\IntlCaster', 'castIntlDateFormatter'], 'Memcached' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\MemcachedCaster', 'castMemcached'], 'Modular\\ConnectorDependencies\\Ds\\Collection' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DsCaster', 'castCollection'], 'Modular\\ConnectorDependencies\\Ds\\Map' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DsCaster', 'castMap'], 'Modular\\ConnectorDependencies\\Ds\\Pair' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DsCaster', 'castPair'], 'Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DsPairStub' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\DsCaster', 'castPairStub'], 'mysqli_driver' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\MysqliCaster', 'castMysqliDriver'], 'CurlHandle' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castCurl'], ':curl' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castCurl'], ':dba' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castDba'], ':dba persistent' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castDba'], 'GdImage' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castGd'], ':gd' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castGd'], ':mysql link' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castMysqlLink'], ':pgsql large object' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\PgSqlCaster', 'castLargeObject'], ':pgsql link' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\PgSqlCaster', 'castLink'], ':pgsql link persistent' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\PgSqlCaster', 'castLink'], ':pgsql result' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\PgSqlCaster', 'castResult'], ':process' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castProcess'], ':stream' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castStream'], 'OpenSSLCertificate' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castOpensslX509'], ':OpenSSL X.509' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castOpensslX509'], ':persistent stream' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castStream'], ':stream-context' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\ResourceCaster', 'castStreamContext'], 'XmlParser' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\XmlResourceCaster', 'castXml'], ':xml' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\XmlResourceCaster', 'castXml'], 'RdKafka' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster', 'castRdKafka'], 'Modular\\ConnectorDependencies\\RdKafka\\Conf' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster', 'castConf'], 'Modular\\ConnectorDependencies\\RdKafka\\KafkaConsumer' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster', 'castKafkaConsumer'], 'Modular\\ConnectorDependencies\\RdKafka\\Metadata\\Broker' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster', 'castBrokerMetadata'], 'Modular\\ConnectorDependencies\\RdKafka\\Metadata\\Collection' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster', 'castCollectionMetadata'], 'Modular\\ConnectorDependencies\\RdKafka\\Metadata\\Partition' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster', 'castPartitionMetadata'], 'Modular\\ConnectorDependencies\\RdKafka\\Metadata\\Topic' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster', 'castTopicMetadata'], 'Modular\\ConnectorDependencies\\RdKafka\\Message' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster', 'castMessage'], 'Modular\\ConnectorDependencies\\RdKafka\\Topic' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster', 'castTopic'], 'Modular\\ConnectorDependencies\\RdKafka\\TopicPartition' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster', 'castTopicPartition'], 'Modular\\ConnectorDependencies\\RdKafka\\TopicConf' => ['Modular\\ConnectorDependencies\\Symfony\\Component\\VarDumper\\Caster\\RdKafkaCaster', 'castTopicConf']];
    protected $maxItems = 2500;
    protected $maxString = -1;
    protected $minDepth = 1;
    /**
     * @var array<string, list<callable>>
     */
    private $casters = [];
    /**
     * @var callable|null
     */
    private $prevErrorHandler;
    private $classInfo = [];
    private $filter = 0;
    /**
     * @param callable[]|null $casters A map of casters
     *
     * @see addCasters
     */
    public function __construct(?array $casters = null)
    {
        if (null === $casters) {
            $casters = static::$defaultCasters;
        }
        $this->addCasters($casters);
    }
    /**
     * Adds casters for resources and objects.
     *
     * Maps resources or objects types to a callback.
     * Types are in the key, with a callable caster for value.
     * Resource types are to be prefixed with a `:`,
     * see e.g. static::$defaultCasters.
     *
     * @param callable[] $casters A map of casters
     */
    public function addCasters(array $casters)
    {
        foreach ($casters as $type => $callback) {
            $this->casters[$type][] = $callback;
        }
    }
    /**
     * Sets the maximum number of items to clone past the minimum depth in nested structures.
     */
    public function setMaxItems(int $maxItems)
    {
        $this->maxItems = $maxItems;
    }
    /**
     * Sets the maximum cloned length for strings.
     */
    public function setMaxString(int $maxString)
    {
        $this->maxString = $maxString;
    }
    /**
     * Sets the minimum tree depth where we are guaranteed to clone all the items.  After this
     * depth is reached, only setMaxItems items will be cloned.
     */
    public function setMinDepth(int $minDepth)
    {
        $this->minDepth = $minDepth;
    }
    /**
     * Clones a PHP variable.
     *
     * @param mixed $var    Any PHP variable
     * @param int   $filter A bit field of Caster::EXCLUDE_* constants
     *
     * @return Data
     */
    public function cloneVar($var, int $filter = 0)
    {
        $this->prevErrorHandler = \set_error_handler(function ($type, $msg, $file, $line, $context = []) {
            if (\E_RECOVERABLE_ERROR === $type || \E_USER_ERROR === $type) {
                // Cloner never dies
                throw new \ErrorException($msg, 0, $type, $file, $line);
            }
            if ($this->prevErrorHandler) {
                return ($this->prevErrorHandler)($type, $msg, $file, $line, $context);
            }
            return \false;
        });
        $this->filter = $filter;
        if ($gc = \gc_enabled()) {
            \gc_disable();
        }
        try {
            return new Data($this->doClone($var));
        } finally {
            if ($gc) {
                \gc_enable();
            }
            \restore_error_handler();
            $this->prevErrorHandler = null;
        }
    }
    /**
     * Effectively clones the PHP variable.
     *
     * @param mixed $var Any PHP variable
     *
     * @return array
     */
    protected abstract function doClone($var);
    /**
     * Casts an object to an array representation.
     *
     * @param bool $isNested True if the object is nested in the dumped structure
     *
     * @return array
     */
    protected function castObject(Stub $stub, bool $isNested)
    {
        $obj = $stub->value;
        $class = $stub->class;
        if (\PHP_VERSION_ID < 80000 ? "\x00" === ($class[15] ?? null) : \str_contains($class, "@anonymous\x00")) {
            $stub->class = \get_debug_type($obj);
        }
        if (isset($this->classInfo[$class])) {
            [$i, $parents, $hasDebugInfo, $fileInfo] = $this->classInfo[$class];
        } else {
            $i = 2;
            $parents = [$class];
            $hasDebugInfo = \method_exists($class, '__debugInfo');
            foreach (\class_parents($class) as $p) {
                $parents[] = $p;
                ++$i;
            }
            foreach (\class_implements($class) as $p) {
                $parents[] = $p;
                ++$i;
            }
            $parents[] = '*';
            $r = new \ReflectionClass($class);
            $fileInfo = $r->isInternal() || $r->isSubclassOf(Stub::class) ? [] : ['file' => $r->getFileName(), 'line' => $r->getStartLine()];
            $this->classInfo[$class] = [$i, $parents, $hasDebugInfo, $fileInfo];
        }
        $stub->attr += $fileInfo;
        $a = Caster::castObject($obj, $class, $hasDebugInfo, $stub->class);
        try {
            while ($i--) {
                if (!empty($this->casters[$p = $parents[$i]])) {
                    foreach ($this->casters[$p] as $callback) {
                        $a = $callback($obj, $a, $stub, $isNested, $this->filter);
                    }
                }
            }
        } catch (\Exception $e) {
            $a = [(Stub::TYPE_OBJECT === $stub->type ? Caster::PREFIX_VIRTUAL : '') . '⚠' => new ThrowingCasterException($e)] + $a;
        }
        return $a;
    }
    /**
     * Casts a resource to an array representation.
     *
     * @param bool $isNested True if the object is nested in the dumped structure
     *
     * @return array
     */
    protected function castResource(Stub $stub, bool $isNested)
    {
        $a = [];
        $res = $stub->value;
        $type = $stub->class;
        try {
            if (!empty($this->casters[':' . $type])) {
                foreach ($this->casters[':' . $type] as $callback) {
                    $a = $callback($res, $a, $stub, $isNested, $this->filter);
                }
            }
        } catch (\Exception $e) {
            $a = [(Stub::TYPE_OBJECT === $stub->type ? Caster::PREFIX_VIRTUAL : '') . '⚠' => new ThrowingCasterException($e)] + $a;
        }
        return $a;
    }
}
