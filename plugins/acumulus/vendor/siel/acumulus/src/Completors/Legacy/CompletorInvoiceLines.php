<?php
/**
 * Although we would like to use strict equality, i.e. including type equality,
 * unconditionally changing each comparison in this file will lead to problems
 * - API responses return each value as string, even if it is an int or float.
 * - The shop environment may be lax in its typing by, e.g. using strings for
 *   each value coming from the database.
 * - Our own config object is type aware, but, e.g, uses string for a vat class
 *   regardless the type for vat class ids as used by the shop itself.
 * So for now, we will ignore the warnings about non strictly typed comparisons
 * in this code, and we won't use strict_types=1.
 * @noinspection TypeUnsafeComparisonInspection
 * @noinspection PhpMissingStrictTypesDeclarationInspection
 * @noinspection PhpStaticAsDynamicMethodCallInspection
 * @noinspection DuplicatedCode  During the transition to Collectors, duplicate code will exist.
 */

namespace Siel\Acumulus\Completors\Legacy;

use Siel\Acumulus\Api;
use Siel\Acumulus\Config\Config;
use Siel\Acumulus\Data\Invoice;
use Siel\Acumulus\Data\Line;
use Siel\Acumulus\Helpers\Number;
use Siel\Acumulus\Meta;
use Siel\Acumulus\Tag;

use function count;

/**
 * The invoice lines completor class provides functionality to correct and
 * complete invoice lines before sending them to Acumulus.
 *
 * This class:
 * - Validates (and correct rounding errors of) vat rates using the VAT rate
 *   lookup webservice call.
 * - Adds required but missing fields on the invoice lines.
 * - Adds vat rates to 0 price lines (with a 0 price and thus 0 vat, not all
 *   web shops can fill in a vat rate).
 * - Completes metadata that may be used in the strategy phase or just for
 *   support purposes.
 */
class CompletorInvoiceLines
{
    /**
     * @var int[]
     *   The list of possible vat types, initially filled with possible vat
     *   types based on client country, invoiceHasLineWithVat(), is_company(),
     *   and the EU vat setting.
     */
    protected array $possibleVatTypes;
    /**
     * @var array[]
     *   The list of possible vat rates, based on the possible vat types and
     *   extended with the zero rates (0 and -1 (vat-free)) if they might be
     *   applicable.
     */
    protected array $possibleVatRates;
    protected Config $config;
    /** @var \Siel\Acumulus\Invoice\Completor|\Siel\Acumulus\Completors\Legacy\Completor  */
    protected $completor;
    protected FlattenerInvoiceLines $invoiceLineFlattener;

    public function __construct(FlattenerInvoiceLines $invoiceLinesFlattener, Config $config)
    {
        $this->invoiceLineFlattener = $invoiceLinesFlattener;
        $this->config = $config;
    }

    /**
     * Sets the completor (just so we can call some convenience methods).
     */
    public function setCompletor($completor): void
    {
        $this->completor = $completor;
    }

    /**
     * Completes the invoice with default settings that do not depend on shop
     * specific data.
     *
     * @param Invoice $invoice
     *   The invoice to complete.
     * @param int[] $possibleVatTypes
     * @param array[] $possibleVatRates
     *
     * @return Invoice
     *   The completed invoice.
     */
    public function complete(Invoice $invoice, array $possibleVatTypes, array $possibleVatRates): Invoice
    {
        $this->possibleVatTypes = $possibleVatTypes;
        $this->possibleVatRates = $possibleVatRates;

        $invoice = $this->completeInvoiceLinesRecursive($invoice);
        $lines = $invoice[Tag::Line];
        $lines = $this->invoiceLineFlattener->complete($lines);
        $invoice->removeLines();
        Converter::getInvoiceLinesFromArray($lines, $invoice);
        $this->completeInvoiceLines($lines);
        return $invoice;
    }

    /**
     * Completes the invoice lines before they are flattened.
     *
     * This means that the lines have to be walked recursively.
     *
     * The actions that can be done this way are those who operate on a line in
     * isolation and thus do not need totals, maximums or things like that.
     *
     * @param Invoice $invoice
     *   The invoice with the lines to complete recursively.
     *
     * @return Invoice
     *   The invoice with the completed invoice lines.
     */
    protected function completeInvoiceLinesRecursive(Invoice $invoice): Invoice
    {
        $lines = &$invoice[Tag::Line];

        if ($this->completor->shouldConvertCurrency($invoice)) {
            $this->convertToEuro($lines, $invoice[Meta::CurrencyRate]);
        }
        // @todo: we could combine all completor phase methods of getting the
        //   correct vat rate:
        //     - possible vat rates
        //     - filter by range
        //     - filter by tax class = EU vat (or not)
        //     - filter by lookup vat
        //   Why? addVatRateUsingLookupData() actually already does so, but will
        //   not work when we do have a lookup vat class but not a lookup vat
        //   rate.
        //   This would allow to combine VatRateSource_Calculated and
        //   VatRateSource_Completor.
        // correctCalculatedVatRates() only uses 'vatrate', 'meta-vatrate-min',
        // and 'meta-vatrate-max' and may lead to more (required) data filled
        // in, so should be called before completeLineRequiredData().
        $this->correctCalculatedVatRates($lines);
        // addVatRateUsingLookupData() only uses 'meta-vat-rate-lookup' and may
        // lead to more (required) data filled in, so should be called before
        // completeLineRequiredData().
        $this->addVatRateUsingLookupData($lines);
        $this->completeLineRequiredData($lines);
        // Completing the required data may lead to new lines that contain
        // calculated VAT rates and thus can be corrected with
        // correctCalculatedVatRates(): call again.
        $this->correctCalculatedVatRates($lines);

        return $invoice;
    }

    /**
     * Completes the invoice lines after they have been flattened.
     *
     * @param Line[] $lines
     *   The invoice lines to complete.
     */
    protected function completeInvoiceLines(array &$lines): void
    {
        $this->addNatureToNonItemLines($lines);
        $this->addVatRateTo0PriceLines($lines);
        $this->recalculateLineData($lines);
        $this->completeLineMetaData($lines);
    }

    /**
     * Converts amounts to euro if another currency was used.
     * This method only converts amounts at the line level. The invoice level
     * is handled by the completor and already has been converted.
     *
     * @param Line[] $lines
     *   The invoice lines to convert recursively.
     * @param float $conversionRate
     */
    protected function convertToEuro(array &$lines, float $conversionRate): void
    {
        // @error: use new meta structure
        foreach ($lines as &$line) {
            $this->completor->convertAmount($line, Tag::UnitPrice, $conversionRate);
            // Cost price may well be in purchase currency, let's assume it already is in euros ...
            //$this->completor->convertAmount($line, Tag::CostPrice, $conversionRate);
            $this->completor->convertAmount($line, Meta::UnitPriceInc, $conversionRate);
            $this->completor->convertAmount($line, Meta::VatAmount, $conversionRate);
            $this->completor->convertAmount($line, Meta::LineAmount, $conversionRate);
            $this->completor->convertAmount($line, Meta::LineAmountInc, $conversionRate);
            $this->completor->convertAmount($line, Meta::LineVatAmount, $conversionRate);
            $this->completor->convertAmount($line, Meta::LineDiscountAmount, $conversionRate);
            $this->completor->convertAmount($line, Meta::LineDiscountAmountInc, $conversionRate);
            $this->completor->convertAmount($line, Meta::LineDiscountVatAmount, $conversionRate);

            // Recursively convert any amount.
            if (!empty($line[Meta::ChildrenLines])) {
                $this->convertToEuro($line[Meta::ChildrenLines], $conversionRate);
            }
        }
    }

    /**
     * Corrects 'calculated' vat rates.
     *
     * Tries to correct 'calculated' vat rates for rounding errors by matching
     * them with possible vatRates obtained from the vat lookup service.
     *
     * @param Line[] $lines
     *   The invoice lines to correct.
     */
    protected function correctCalculatedVatRates(array &$lines): void
    {
        foreach ($lines as &$line) {
            if (!empty($line[Meta::VatRateSource]) && $line[Meta::VatRateSource] === Creator::VatRateSource_Calculated) {
                $this->correctVatRateByRange($line);
            }
            if (!empty($line[Meta::ChildrenLines])) {
                $this->correctCalculatedVatRates($line[Meta::ChildrenLines]);
            }
        }
    }

    /**
     * Checks and corrects a 'calculated' vat rate to an allowed vat rate.
     *
     * The 'meta-vatrate-source' must be Creator::VatRateSource_Calculated.
     *
     * The check is done on comparing allowed vat rates with the
     * 'meta-vatrate-min' and 'meta-vatrate-max' values. If only 1 match is
     * found that will be used.
     *
     * If multiple matches are found with all equal rates - e.g. Dutch and
     * Belgium 21% - the vat rate will be corrected, but the VAT Type will
     * remain undecided, unless the vat class could be looked up and thus used
     * to differentiate between national and foreign vat.
     *
     * This method is public to allow a 2nd call to just this method for a
     * single line (a missing amount line) added after a 1st round of
     * correcting. Do not use unless $this->possibleVatRates has been
     * initialized.
     *
     * @param Line $line
     *   An invoice line with a calculated vat rate.
     */
    public function correctVatRateByRange(Line $line): void
    {
        $line[Meta::VatRateRangeMatches] = $this->filterVatRateInfosByRange($line[Meta::VatRateMin], $line[Meta::VatRateMax]);
        $vatRate = $this->getUniqueVatRate($line[Meta::VatRateRangeMatches]);

        if ($vatRate === null) {
            // No match at all.
            unset($line[Tag::VatRate]);
            if (!empty($line[Meta::StrategySplit])) {
                // If this line may be split, we make it a strategy line (even
                // though 2 out of the 3 fields ex, inc, and vat are known).
                // This way the strategy phase may be able to correct this line.
                $line[Tag::VatRate] = null;
                $line[Meta::VatRateSource] = Creator::VatRateSource_Strategy;
            } else {
                // Set vat rate to null and try to use lookup data to get a vat
                // rate. It will be invalid but may be better than the "setting
                // to standard 21%".
                $line[Tag::VatRate] = null;
                $line[Meta::VatRateSource] = Creator::VatRateSource_Completor;
                $this->completor->changeInvoiceToConcept($line, 'message_warning_no_vatrate', 821);
                // @todo: this can also happen with exact or looked up vat rates
                //   add a checker in the Completor that checks all lines for
                //   no or an incorrect vat rate and changes the invoice into a
                //   concept.
            }
        } elseif ($vatRate === false) {
            // Multiple matches: set vat rate to null and try to use lookup data.
            $line[Tag::VatRate] = null;
            $line[Meta::VatRateSource] = Creator::VatRateSource_Completor;
        } else {
            // Single match: fill it in as the vat rate for this line.
            $line[Tag::VatRate] = $vatRate;
            $line[Meta::VatRateSource] = Completor::VatRateSource_Completor_Range;
        }
    }

    /**
     * Completes lines that have 'meta-vatrate-lookup(-...)' data.
     *
     * Vat rate lookup metadata is added by the Creator class using vat rates
     * from the product info. However, as VAT rates may have changed between the
     * date of the order and now, we cannot fully rely on it and use it only as
     * a(n almost) last resort.
     *
     * We filter the looked up vat rate(s) against:
     * - The possible vat rates (given the possible vat types).
     * - The vat rate range (using the value of Meta::VatRateRangeMatches, if
     *   set).
     * - If still multiple vat rate (infos) remains, we filter by national
     *   versus EU vat (e.g. to distinguish between NL and BE 21%).
     *
     * In the following cases it may be used:
     *   1. The calculated vat rate range is so wide that it contains multiple
     *      possible vat rates. If the looked up vat rate is one of them, we use
     *      it.
     *   2. 0 price: With free products we cannot calculate a vat rate, so we
     *      have to rely on lookup. However, if reversed vat or another 0-vat
     *      vat type is possible, we cannot blindly choose the lookup rate and
     *      should rely on the strategy phase.
     *
     * @param Line[] $lines
     *   The invoice lines to correct using lookup data.
     */
    protected function addVatRateUsingLookupData(array &$lines): void
    {
        foreach ($lines as &$line) {
            if ($line[Meta::VatRateSource] === Creator::VatRateSource_Completor) {
                // Do we have lookup data and not the exception for situation 2?
                // Required data is not guaranteed to be available at this
                // stage, so use the price that is available: both will be zero
                // or both will be not zero.
                $price = $line[Tag::UnitPrice] ?? $line[Meta::UnitPriceInc];
                if (!empty($line[Meta::VatRateLookup])
                    && (!Number::isZero($price) || !$this->completor->is0VatVatTypePossible())
                ) {
                    // Filter lookup rate(s) by the rates of the possible vat types.
                    $line[Meta::VatRateLookupMatches] = $this->filterVatRateInfosByVatRates($line[Meta::VatRateLookup]);
                    $vatRateSource = Completor::VatRateSource_Completor_Lookup;

                    // Try to reduce the set by intersecting with the vat rate
                    // range matches.
                    if (!$this->getUniqueVatRate($line[Meta::VatRateLookupMatches]) && !empty($line[Meta::VatRateRangeMatches])) {
                        $line[Meta::VatRateLookupMatches] = $this->filterVatRateInfosByVatRates(
                            $line[Meta::VatRateRangeMatches],
                            $line[Meta::VatRateLookupMatches]);
                        $vatRateSource = Completor::VatRateSource_Completor_Range_Lookup;
                    }

                    if ($this->getUniqueVatRate($line[Meta::VatRateLookupMatches])) {
                        // Only a single vat rate remains: take that one.
                        $vatRateInfo = reset($line[Meta::VatRateLookupMatches]);
                        $line[Tag::VatRate] = !is_scalar($vatRateInfo) ? $vatRateInfo[Tag::VatRate] : $vatRateInfo;
                        $line[Meta::VatRateSource] = $vatRateSource;
                    }
                }

                if ($line[Meta::VatRateSource] === Creator::VatRateSource_Completor) {
                    // We either do not have lookup data, the looked up vat rate
                    // is not possible, or we have a 0-price line with multiple
                    // vat rates possible: give the strategy phase a chance to
                    // resolve.
                    //
                    // Note: if this is not a 0-price line we may still have a
                    // chance by using the vat range tactics on the line totals
                    // (as that can be more precise with small prices and large
                    // quantities). However, for now, I am not going to use the
                    // line totals as they are hardly available.
                    $line[Meta::VatRateSource] = Creator::VatRateSource_Strategy;
                }
            }

            // Recursively complete lines using lookup data.
            if (!empty($line[Meta::ChildrenLines])) {
                $this->addVatRateUsingLookupData($line[Meta::ChildrenLines]);
            }
        }
    }

    /**
     * Completes fields that are required by the rest of this completor phase.
     *
     * The creator filled in the fields that are directly available from the
     * shops' data store. This method completes (if not filled in):
     * - 'unitprice'
     * - 'vatamount'
     * - 'unitpriceinc'
     *
     * @param Line[] $lines
     *   The invoice lines to complete with required data.
     * @param Line|null $parent
     *   The parent line for this set of lines or null if this is the set of
     *   lines at the top level.
     *
     * @noinspection PhpFunctionCyclomaticComplexityInspection
     */
    protected function completeLineRequiredData(array &$lines, ?Line $parent = null): void
    {
        foreach ($lines as &$line) {
            $fieldsCalculated = [];
            // Easy gains first. Known usages: Magento.
            if (!isset($line[Meta::VatAmount]) && isset($line[Meta::LineVatAmount])) {
                // Known usages: Magento.
                $line[Meta::VatAmount] = $line[Meta::LineVatAmount] / $line[Tag::Quantity];
                $fieldsCalculated[] = Meta::VatAmount . ' (from ' . Meta::LineVatAmount . ')';
            }
            if (!isset($line[Meta::LineType]) && $parent !== null) {
                // Known usages: WooCommerce TM Extra Product Options that adds
                // child lines.
                $line[Meta::LineType] = $parent[Meta::LineType];
            }


            if (!isset($line[Tag::UnitPrice])) {
                // With margin scheme, the unit price should be known but may
                // have ended up in the unit price inc.
                if (isset($line[Tag::CostPrice])) {
                    if (isset($line[Meta::UnitPriceInc])) {
                        $line[Tag::UnitPrice] = $line[Meta::UnitPriceInc];
                    }
                } elseif (isset($line[Meta::UnitPriceInc])) {
                    if (Number::isZero($line[Meta::UnitPriceInc])) {
                        // Free products are free with and without VAT.
                        $line[Tag::UnitPrice] = 0;
                    } elseif (isset($line[Tag::VatRate]) && Completor::isCorrectVatRate($line[Meta::VatRateSource])) {
                         $line[Tag::UnitPrice] = $line[Meta::UnitPriceInc] / (100.0 + $line[Tag::VatRate]) * 100.0;
                    } elseif (isset($line[Meta::VatAmount])) {
                        $line[Tag::UnitPrice] = $line[Meta::UnitPriceInc] - $line[Meta::VatAmount];
                    } // else {
                        // We cannot fill in unit price reliably, so better to
                        // leave it empty and fail clearly.
                    // }
                    $fieldsCalculated[] = Tag::UnitPrice;
                }
            }

            if (!isset($line[Meta::UnitPriceInc])) {
                // With margin scheme, the unit price inc equals unit price.
                if (isset($line[Tag::CostPrice])) {
                    if (isset($line[Tag::UnitPrice])) {
                        $line[Meta::UnitPriceInc] = $line[Tag::UnitPrice];
                    }
                } elseif (isset($line[Tag::UnitPrice])) {
                    if (Number::isZero($line[Tag::UnitPrice])) {
                        // Free products are free with and without VAT.
                        $line[Meta::UnitPriceInc] = 0;
                    } elseif (isset($line[Tag::VatRate]) && Completor::isCorrectVatRate($line[Meta::VatRateSource])) {
                        $line[Meta::UnitPriceInc] = $this->completor->isNoVat($line[Tag::VatRate])
                            ? $line[Tag::UnitPrice]
                            : $line[Tag::UnitPrice] * (100.0 + $line[Tag::VatRate]) / 100.0;
                    } elseif (isset($line[Meta::VatAmount])) {
                        $line[Meta::UnitPriceInc] = $line[Tag::UnitPrice] + $line[Meta::VatAmount];
                    } // else {
                        // We cannot fill in unit price inc reliably, so we
                        // leave it empty as it is metadata after all.
                    // }
                    $fieldsCalculated[] = Meta::UnitPriceInc;
                }
            }

            if (!isset($line[Tag::VatRate])) {
                // Can we copy it from the parent?
                if ($line[Meta::VatRateSource] === Creator::VatRateSource_Parent && $parent !== null) {
                    if (Completor::isCorrectVatRate($parent[Meta::VatRateSource])) {
                        $line[Tag::VatRate] = $parent[Tag::VatRate];
                        $line[Meta::VatRateSource] = Completor::VatRateSource_Copied_From_Parent;
                    } else {
                        // Allow strategy phase to also add a vat rate to the
                        // child lines.
                        $line[Meta::VatRateSource] = Creator::VatRateSource_Strategy;
                    }
                } elseif (isset($line[Meta::VatAmount], $line[Tag::UnitPrice])) {
                    // This may use the easy gain, so known usages: Magento.
                    // Set (overwrite the tag vatrate-source) 'vatrate' and
                    // accompanying tags.
                    $precision = 0.01;
                    // If the amounts are the sum of amounts taken from
                    // children products, the precision may be lower.
                    if (!empty($line[Meta::ChildrenLines])) {
                        $precision *= count($line[Meta::ChildrenLines]);
                    }
                    $vatRangeTags = Creator::getVatRangeTags($line[Meta::VatAmount], $line[Tag::UnitPrice], $precision, $precision);
                    foreach ($vatRangeTags as $key => $value) {
                        $line[$key] = $value;
                    }

                    $fieldsCalculated[] = Tag::VatRate;
                }
            }

            if (count($fieldsCalculated) > 0) {
                $line[Meta::FieldsCalculated] = $fieldsCalculated;
            }

            // Recursively complete the required data.
            if (!empty($line[Meta::ChildrenLines])) {
                $this->completeLineRequiredData($line[Meta::ChildrenLines], $line);
            }
        }
    }

    /**
     * Determines if all (matched) vat rates are equal.
     *
     * @param array[] $vatRateInfos
     *   Array of vat rate infos.
     *
     * @return float|false|null
     *   If all vat rate in $vatRates are equal,that vat rate, null if
     *   $matchedVatRates is empty, false otherwise (multiple but different vat
     *   rates).
     */
    protected function getUniqueVatRate(array $vatRateInfos)
    {
        return array_reduce($vatRateInfos, static function ($carry, $matchedVatRate) {
            if ($carry === null) {
                // 1st item: return its vat rate.
                return $matchedVatRate[Tag::VatRate];
            } elseif ($carry == $matchedVatRate[Tag::VatRate]) {
                // Note that in PHP: '21' == '21.0000' returns true. So using ==
                // works. Vat rate equals all previous vat rates: return that
                // vat rate.
                return $carry;
            } else {
                // Vat rate does not match previous vat rates or carry is
                // already false: return false.
                return false;
            }
        }, null);
    }

    /**
     * Adds the nature tag to the non-item lines.
     *
     * The nature tag indicates the nature of the order line: product or
     * service. However, for accompanying services like shipping or payment
     * fees, the nature should follow the major part of the "real" order items.
     *
     * @param Line[] $lines
     */
    protected function addNatureToNonItemLines(array &$lines): void
    {
        $nature = $this->getMaxAppearingNature($lines);
        if (!empty($nature)) {
            foreach ($lines as &$line) {
                if (isset($line[Meta::LineType]) && $line[Meta::LineType] !== Creator::LineType_OrderItem && !isset($line[Tag::Nature])) {
                    $line[Tag::Nature] = $nature;
                }
            }
        }
    }

    /**
     * Returns the nature that forms the major part of the invoice amount.
     *
     * Notes:
     * - We take the abs value to correctly cover credit invoices. This won't
     *   disturb discount lines, see the following note.
     * - If discounts appear on separate lines, they won't have a nature field.
     *   If such a discount was meant for certain lines only, it should get the
     *   nature of these lines (and subsequently be used to calculate the major
     *   part). However, we do not know for which lines it was meant, so we
     *   treat them like the other extra lines.
     *
     * @param Line[] $lines
     *   The invoice lines to search.
     *
     * @return string
     *   The nature that forms the major part of the amount of all order item
     *   lines (hoofdbestanddeel). Can be the empty string to indicate that no
     *   nature is known for the major part.
     */
    protected function getMaxAppearingNature(array $lines): string
    {
        $amountPerNature = ['' => 0.0, Api::Nature_Product => 0.0, Api::Nature_Service => 0.0];
        foreach ($lines as $line) {
            if (isset($line[Meta::LineType]) && $line[Meta::LineType] === Creator::LineType_OrderItem) {
                $nature = !empty($line[Tag::Nature]) ? $line[Tag::Nature] : '';
                $amount = abs($line[Tag::Quantity] * $line[Tag::UnitPrice]);
                $amountPerNature[$nature] += $amount;
            }
        }
        arsort($amountPerNature, SORT_NUMERIC);
        return key($amountPerNature);
    }

    /**
     * Completes lines with free items (price = 0) by giving them the maximum
     * tax rate that appears in the other lines.
     *
     * These lines already have gone through the addVatRateUsingLookupData()
     * method, but either no lookup vat data is available or the looked up vat
     * rate is not a possible vat rate.
     *
     * @param Line[] $lines
     *   The invoice lines to correct by adding a vat rate to 0 amounts.
     */
    protected function addVatRateTo0PriceLines(array &$lines): void
    {
        // Get the highest appearing vat rate. We could get the most often
        // appearing vat rate, but IMO the highest vat rate will be more likely
        // to be correct.
        $maxVatRate = $this->getMaxAppearingVatRate($lines);

        foreach ($lines as &$line) {
            $price = $line[Tag::UnitPrice] ?? $line[Meta::UnitPriceInc];
            if ($line[Meta::VatRateSource] === Creator::VatRateSource_Completor && Number::isZero($price)) {
                if ($maxVatRate !== null) {
                    $line[Tag::VatRate] = $maxVatRate;
                    $line[Meta::VatRateSource] = Completor::VatRateSource_Completor_Max_Appearing;
                } else {
                    $line[Meta::VatRateSource] = Creator::VatRateSource_Strategy;
                }
            }
        }
    }

    /**
     * Returns the maximum vat rate that appears in the given set of lines.
     *
     * @param Line[] $lines
     *   The invoice lines to search.
     * @param ?int $index
     *   If passed, the index of the max vat rate is returned via this parameter.
     *
     * @return float|null
     *   The maximum vat rate in the given set of lines or null if no vat rates
     *   could be found.
     */
    public static function getMaxAppearingVatRate(array $lines, ?int &$index = null): ?float
    {
        $index = null;
        $maxVatRate = -1.0;
        foreach ($lines as $key => $line) {
            if (isset($line[Tag::VatRate]) && (float) $line[Tag::VatRate] > $maxVatRate) {
                $index = $key;
                $maxVatRate = (float) $line[Tag::VatRate];
            }
        }
        return $index !== null ? $maxVatRate : null;
    }

    /**
     * Returns the set of possible vat rates that fall in the given vat range.
     *
     * @param array|null $vatRateInfos
     *   The set of vat rate infos to filter. If not given, the property
     *   $this->possibleVatRates is used.
     *
     * @return array[]
     *   The, possibly empty, set of vat rate infos that have a vat rate that
     *   falls within the given vat range.
     */
    protected function filterVatRateInfosByRange(float $min, float $max, ?array $vatRateInfos = null): array
    {
        if ($vatRateInfos === null) {
            $vatRateInfos = $this->possibleVatRates;
        }

        $result = [];
        foreach ($vatRateInfos as $vatRateInfo) {
            $vatRate = !is_scalar($vatRateInfo) ? $vatRateInfo[Tag::VatRate] : $vatRateInfo;
            if ($min <= $vatRate && $vatRate <= $max) {
                $result[] = $vatRateInfo;
            }
        }
        return $result;
    }

    /**
     * Returns the subset of the vat rate infos that have a vat rate that
     * appears within the given set of vat rates.
     *
     * @param float|float[]|array|array[] $vatRates
     *   The vat rate(s) or vat rate info(s) to filter against.
     * @param array|null $vatRateInfos
     *   The set of vat rate infos to filter. If not given, the property
     *   $this->possibleVatRates is used.
     *
     * @return array[]
     *   The, possibly empty, set of $vatRateInfos that have a vat rate that
     *   appears within the set of $vatRates.
     */
    protected function filterVatRateInfosByVatRates($vatRates, ?array $vatRateInfos = null): array
    {
        $vatRates = (array) $vatRates;
        if ($vatRateInfos === null) {
            $vatRateInfos = $this->possibleVatRates;
        }

        $result = [];
        foreach ($vatRateInfos as $vatRateInfo) {
            $vatRate = $vatRateInfo[Tag::VatRate];
            foreach ($vatRates as $vatRateInfo2) {
                $vatRate2 = !is_scalar($vatRateInfo2) ? $vatRateInfo2[Tag::VatRate] : $vatRateInfo2;
                if (Number::floatsAreEqual($vatRate, $vatRate2)) {
                    $result[] = $vatRateInfo;
                }
            }
        }
        return $result;
    }

    /**
     * Recalculates the 'unitprice(inc)' for lines that indicate so.
     *
     * PRE: All non strategy invoice lines have 'unitprice' and 'vatrate' filled
     * in and should by now have correct(ed) VAT rates. In some shops the
     * 'unitprice' or 'unitpriceinc' is imprecise because they are returned
     * rounded to the cent.
     *
     * To prevent differences between the Acumulus and shop invoice (or between
     * the invoice and line totals) we recompute the 'unitprice' if:
     * - Vat rate is correct.
     * - 'meta-recalculate-price' is set to Tag::UnitPrice. (Shops should set
     *   so, if prices are entered inc vat and the price ex vat as obtained by
     *   this plugin is known to have a precision worse than 0.0001.
     * - Unit price inc is available.
     *
     * We recompute the unit price inc if:
     * - Vat rate is correct.
     * - 'meta-recalculate-price' is set to Meta:UnitPriceInc. (Shops should set
     *   so, if prices are entered ex vat and the price inc vat as obtained by
     *   this plugin is known to have a precision worse than 0.0001.
     * - Unit price is available.
     *
     * @param Line[] $lines
     *   The invoice lines to recalculate.
     */
    protected function recalculateLineData(array &$lines): void
    {
        foreach ($lines as &$line) {
            if (!empty($line[Meta::RecalculatePrice])
                && Completor::isCorrectVatRate($line[Meta::VatRateSource])
                && isset($line[Meta::UnitPriceInc])
            ) {
                if ($line[Meta::RecalculatePrice] === Tag::UnitPrice) {
                    $line[Meta::RecalculateOldPrice] = $line[Tag::UnitPrice];
                    $line[Tag::UnitPrice] = $line[Meta::UnitPriceInc] / (100 + $line[Tag::VatRate]) * 100;
                } else {
                    // $line[Meta::RecalculateUnitPrice] === Meta::UnitPriceInc
                    $line[Meta::RecalculateOldPrice] = $line[Meta::UnitPriceInc];
                    $line[Meta::UnitPriceInc] = (1 + $line[Tag::VatRate] / 100) * $line[Tag::UnitPrice];
                }
                $line[Meta::RecalculatedPrice] = true;
            }
        }
    }

    /**
     * Completes each (non-strategy) invoice line with missing (meta) info.
     *
     * All non strategy invoice lines have 'unitprice' and 'vatrate' filled in
     * and should by now have correct(ed) VAT rates. In some shops these non
     * strategy invoice lines may have a 'meta-line-discount-vatamount' or
     * 'meta-line-discount-amountinc' field, that can be used with the
     * SplitKnownDiscountLine strategy.
     *
     * Complete (if missing):
     * - 'unitpriceinc'
     * - 'vatamount'
     * - 'meta-line-discount-amountinc' (if 'meta-line-discount-vatamount' is
     *   available).
     *
     * For strategy invoice lines that may be split with the SplitNonMatching
     * line strategy, we need to know the line totals.
     *
     * Complete (if missing):
     * - 'meta-line-price'
     * - 'meta-line-priceinc'
     *
     * @param Line[] $lines
     *   The invoice lines to complete with metadata.
     */
    protected function completeLineMetaData(array &$lines): void
    {
        foreach ($lines as &$line) {
            $fieldsCalculated = [];
            if (Completor::isCorrectVatRate($line[Meta::VatRateSource])) {
                if (!isset($line[Meta::UnitPriceInc])) {
                    $line[Meta::UnitPriceInc] = $this->completor->isNoVat($line[Tag::VatRate])
                        ? $line[Tag::UnitPrice]
                        : $line[Tag::UnitPrice] * (100.0 + $line[Tag::VatRate]) / 100.0;
                    $fieldsCalculated[] = Meta::UnitPriceInc;
                }
                if (!isset($line[Meta::VatAmount])) {
                    $line[Meta::VatAmount] = $this->completor->isNoVat($line[Tag::VatRate])
                        ? 0.0
                        : $line[Tag::VatRate] / 100.0 * $line[Tag::UnitPrice];
                    $fieldsCalculated[] = Meta::VatAmount . ' (from ' . Tag::VatRate . ')';
                }
                if (isset($line[Meta::LineDiscountAmount]) && !isset($line[Meta::LineDiscountAmountInc])) {
                    $line[Meta::LineDiscountAmountInc] = $this->completor->isNoVat($line[Tag::VatRate])
                        ? $line[Meta::LineDiscountAmount]
                        : $line[Meta::LineDiscountAmount] * (100.0 + $line[Tag::VatRate]) / 100.0;
                    $fieldsCalculated[] = Meta::LineDiscountAmountInc;
                }
                elseif (isset($line[Meta::LineDiscountVatAmount]) && !isset($line[Meta::LineDiscountAmountInc])) {
                    $line[Meta::LineDiscountAmountInc] = $line[Meta::LineDiscountVatAmount]
                        / $line[Tag::VatRate] * (100 + $line[Tag::VatRate]);
                    $fieldsCalculated[] = Meta::LineDiscountAmountInc;
                }
            } elseif ($line[Meta::VatRateSource] == Creator::VatRateSource_Strategy && !empty($line[Meta::StrategySplit])) {
                if (isset($line[Tag::UnitPrice], $line[Meta::UnitPriceInc])) {
                    if (!isset($line[Meta::LineAmount])) {
                        $line[Meta::LineAmount] = $line[Tag::UnitPrice] * $line[Tag::Quantity];
                    }
                    if (!isset($line[Meta::LineAmountInc])) {
                        $line[Meta::LineAmountInc] = $line[Meta::UnitPriceInc] * $line[Tag::Quantity];
                    }
                }
            }
            if (count($fieldsCalculated) > 0) {
                $line[Meta::FieldsCalculated] = $fieldsCalculated;
            }
        }
    }
}
