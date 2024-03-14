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
 * This class represents Claim Documents Info RequestInterface Entity
 *
 * @method string getFileName()
 * @method self   setFileName(string $fileName)
 * @method string getDocumentType()
 * @method self   setDocumentType(string $documentType)
 * @method string getBase64Content()
 * @method self   setBase64Content(string $base64Content)
 */
class ClaimDocumentsInfoEntity extends RequestEntity
{
    const DOCUMENT_TYPE_INVOICE = 'Invoice';
    const DOCUMENT_TYPE_PROOF_OF_DELIVERY = 'ProofOfDelivery';

    /** @var string $fileName */
    protected $fileName;

    /** @var string $documentType */
    protected $documentType;

    /** @var string $base64Content */
    protected $base64Content;

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        return parent::isValid() &&
            ($this->fileName && is_string($this->fileName)) &&
            ($this->documentType && is_string($this->documentType)) &&
            ($this->base64Content && is_string($this->base64Content) && base64_decode($this->base64Content));
    }

    /**
     * {@inheritdoc}
     */
    public function getRequired()
    {
        return ['fileName', 'documentType', 'base64Content'];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray($object = null)
    {
        return $object ? get_object_vars($object) : get_object_vars($this);
    }
}
