<?php

namespace UpsFreeVendor\Octolize\Tracker\OptInNotice;

/**
 * Should display OR conditions.
 */
class ShouldDisplayOrConditions implements \UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplay
{
    /**
     * @var ShouldDisplay[]
     */
    private $conditions = [];
    /**
     * @param ShouldDisplay $should_display
     *
     * @return void
     */
    public function add_should_diaplay_condition(\UpsFreeVendor\Octolize\Tracker\OptInNotice\ShouldDisplay $should_display)
    {
        $this->conditions[] = $should_display;
    }
    /**
     * @inheritDoc
     */
    public function should_display()
    {
        foreach ($this->conditions as $condition) {
            if ($condition->should_display()) {
                return \true;
            }
        }
        return \false;
    }
}
