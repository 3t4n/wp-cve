<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  RequestEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\RequestEntity;

use Payever\Sdk\Core\Http\RequestEntity;

/**
 * This class represents Claim Payment RequestInterface Entity
 *
 * @method ClaimContextInfoEntity     getClaimContextInfo()
 * @method ClaimDocumentsInfoEntity[] getClaimDocumentsInfo()
 */
class ClaimPaymentRequest extends RequestEntity
{
    const UNDERSCORE_ON_SERIALIZATION = false;

    /** @var ClaimContextInfoEntity $claimContextInfo */
    protected $claimContextInfo;

    /** @var ClaimDocumentsInfoEntity[] $claimDocumentsInfo */
    protected $claimDocumentsInfo = [];

    /**
     * {@inheritdoc}
     */
    public function __construct($data = null)
    {
        $this->claimContextInfo = new ClaimContextInfoEntity();

        parent::__construct($data);
    }

    /**
     * Sets claim context info
     *
     * @param ClaimContextInfoEntity|string $claimContextInfo
     *
     * @return $this
     */
    public function setClaimContextInfo($claimContextInfo)
    {
        if (!$claimContextInfo) {
            return $this;
        }

        if (is_string($claimContextInfo)) {
            $claimContextInfo = json_decode($claimContextInfo);
        }

        if (!is_array($claimContextInfo) && !is_object($claimContextInfo)) {
            return $this;
        }

        $this->claimContextInfo = new ClaimContextInfoEntity($claimContextInfo);

        return $this;
    }

    /**
     * Sets claim documents info
     *
     * @param array|string $claimDocumentsInfo
     *
     * @return $this
     */
    public function setClaimDocumentsInfo($claimDocumentsInfo)
    {
        if (!$claimDocumentsInfo) {
            return $this;
        }

        if (is_string($claimDocumentsInfo)) {
            $claimDocumentsInfo = json_decode($claimDocumentsInfo);
        }

        if (!is_array($claimDocumentsInfo)) {
            return $this;
        }

        $this->claimDocumentsInfo = [];

        foreach ($claimDocumentsInfo as $item) {
            $this->claimDocumentsInfo[] = new ClaimDocumentsInfoEntity($item);
        }

        return $this;
    }

    /**
     * Add claim document info
     *
     * @param ClaimDocumentsInfoEntity $document
     *
     * @return $this
     */
    public function addClaimDocumentEntity($document)
    {
        if (!($document instanceof ClaimDocumentsInfoEntity)) {
            return $this;
        }

        $this->claimDocumentsInfo[] = $document;

        return $this;
    }

    /**
     * Set context info insolvency proceeding
     *
     * @param bool $isInsolvencyProceeding
     *
     * @return $this
     */
    public function setIsInsolvencyProceeding($isInsolvencyProceeding)
    {
        $this->claimContextInfo->setIsInsolvencyProceeding($isInsolvencyProceeding);

        return $this;
    }

    /**
     * Set context info invoice disputed
     *
     * @param bool $isInvoiceDisputed
     *
     * @return $this
     */
    public function setIsInvoiceDisputed($isInvoiceDisputed)
    {
        $this->claimContextInfo->setIsInvoiceDisputed($isInvoiceDisputed);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        if (!$this->claimDocumentsInfo || !is_array($this->claimDocumentsInfo)) {
            return false;
        }

        if (!$this->claimContextInfo->isValid()) {
            return false;
        }

        foreach ($this->claimDocumentsInfo as $item) {
            if (!($item instanceof ClaimDocumentsInfoEntity) || !$item->isValid()) {
                return false;
            }
        }

        return parent::isValid();
    }
}
