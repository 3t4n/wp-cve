<?php

/*
 * This file is part of Prokerala Astrology API PHP SDK
 *
 * © Ennexa Technologies <info@ennexa.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */
namespace Prokerala\Api\Astrology\Result\Panchang\Choghadiya;

final class Period
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $type;
    /**
     * @var string|null
     */
    private $vela;
    /**
     * @var bool
     */
    private $isDay;
    /**
     * @var \DateTimeInterface
     */
    private $start;
    /**
     * @var \DateTimeInterface
     */
    private $end;
    /**
     * Period constructor.
     */
    public function __construct(int $id, string $name, string $type, ?string $vela, bool $isDay, \DateTimeInterface $start, \DateTimeInterface $end)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->vela = $vela;
        $this->isDay = $isDay;
        $this->start = $start;
        $this->end = $end;
    }
    public function getId() : int
    {
        return $this->id;
    }
    public function getName() : string
    {
        return $this->name;
    }
    public function getType() : string
    {
        return $this->type;
    }
    public function getVela() : ?string
    {
        return $this->vela;
    }
    public function getIsDay() : bool
    {
        return $this->isDay;
    }
    public function getStart() : \DateTimeInterface
    {
        return $this->start;
    }
    public function getEnd() : \DateTimeInterface
    {
        return $this->end;
    }
}
