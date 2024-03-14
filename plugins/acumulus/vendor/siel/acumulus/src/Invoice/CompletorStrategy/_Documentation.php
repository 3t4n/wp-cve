<?php
/**
 * @noinspection PhpUnused
 */

namespace Siel\Acumulus\Invoice\CompletorStrategy;

/**
 * Documentation for the Invoice\CompletorStrategy namespace
 *
 * The CompletorStrategy namespace contains implementations for several
 * strategies that can be used to correct/complete invoice lines that cannot be
 * easily corrected/completed.
 *
 * These can be the following types of lines:
 * - Discount lines. These are often stored without tax amounts or rates.
 *   Moreover, they may apply to several eligible products that have different
 *   vat rates, so to get it right in Acumulus, the discount line may have to be
 *   split over the various applicable vat rates.
 * - Other subtotal lines, e.g. shipping costs or payment fees. Some webshops do
 *   not store the tax amount or rate with these type of lines, meaning that,
 *   knowing the total vat amount for the order and the vat amount found so far,
 *   the missing vat amount must be divided over the lines that do not have this
 *   information.
 *
 * Some strategies are used for multiple webshops, others for only 1.
 *
 * ### Note to developers
 * When implementing a new extension, you probably should not have to override
 * or add a strategy, as most cases are already handled by this set.
 *
 * @todo: list all strategies including how it works and which plugins use it.
 */
interface _Documentation {}
