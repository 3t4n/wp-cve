<?php

declare(strict_types=1);

namespace Siel\Acumulus\Completors;

use Siel\Acumulus\Data\AcumulusObject;

/**
 * CompletorInterface defines the interface for each Completor.
 *
 * A Completor is a class that in isolation completes a single aspect of an
 * {@see \Siel\Acumulus\Data\AcumulusObject}. For example, this may be 1 field,
 * a few related fields, e.g. all amount fields, or all
 * {@see \Siel\Acumulus\Data\Line lines} of an
 * {@see \Siel\Acumulus\Data\Invoice}.
 *
 * Each Completor should be independent of other ones, though the order of
 * execution may be important to meet the preconditions (availability of other
 * information).
 */
interface CompletorTaskInterface
{
    /**
     * Performs the completion task.
     *
     * Upon returning, the object should be "completed" w.r.t. the task at hand.
     * If the Completor encounters problems:
     * - Warnings: should be added as metadata.
     * - Errors: a runtime error should be thrown.
     *
     * @param AcumulusObject $acumulusObject
     *   The object to complete. It is expected that, on returning, the object
     *   has been changed w.r.t. the task at hand.
     * @param mixed ...$args
     *   Any additional information needed to complete the task at hand.
     */
    public function complete(AcumulusObject $acumulusObject, ... $args): void;
}
