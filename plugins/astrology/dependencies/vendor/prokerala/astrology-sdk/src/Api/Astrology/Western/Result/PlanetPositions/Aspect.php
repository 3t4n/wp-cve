<?php

declare (strict_types=1);
namespace Prokerala\Api\Astrology\Western\Result\PlanetPositions;

use Prokerala\Api\Astrology\Result\ResultInterface;
use Prokerala\Api\Astrology\Traits\Result\RawResponseTrait;
class Aspect implements ResultInterface
{
    use RawResponseTrait;
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
    public function getId() : int
    {
        return $this->id;
    }
    public function getName() : string
    {
        return $this->name;
    }
}
