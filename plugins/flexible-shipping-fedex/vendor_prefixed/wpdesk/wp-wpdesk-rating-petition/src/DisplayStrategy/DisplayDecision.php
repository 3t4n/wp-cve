<?php

namespace FedExVendor\WPDesk\RepositoryRating\DisplayStrategy;

/**
 * ShouldDisplay interface.
 */
interface DisplayDecision
{
    /**
     * Returns true when element should be displayed.
     *
     * @return bool
     */
    public function should_display() : bool;
}
