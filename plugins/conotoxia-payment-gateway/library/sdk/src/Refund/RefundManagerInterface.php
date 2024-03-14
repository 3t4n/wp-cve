<?php

declare(strict_types=1);

namespace CKPL\Pay\Refund;

use CKPL\Pay\Definition\Refund\Builder\RefundBuilderInterface;
use CKPL\Pay\Definition\Refund\RefundInterface;
use CKPL\Pay\Exception\ClientException;
use CKPL\Pay\Exception\Exception;
use CKPL\Pay\Model\Collection\RefundResponseModelCollection;
use CKPL\Pay\Model\Response\CreatedRefundResponseModel;

/**
 * Interface RefundManagerInterface.
 *
 * Refunds related features such as
 * ability to create refund, check refund status,
 * get list of all refunds related to client in service.
 *
 * @package CKPL\Pay\Refund
 */
interface RefundManagerInterface
{
    /**
     * Creates refund builder that can help with generating Refund definition.
     *
     * @return RefundBuilderInterface
     */
    public function createRefundBuilder(): RefundBuilderInterface;

    /**
     * Creates refund in Payment Service from definition and returns
     * refund ID given by service.
     *
     * @param RefundInterface $refund refund definition
     *
     * @throws ClientException request-level related problem e.g. HTTP errors, API errors.
     * @throws Exception       library-level related problem e.g. invalid data model.
     *
     * @return CreatedRefundResponseModel
     */
    public function makeRefund(RefundInterface $refund): CreatedRefundResponseModel;

    /**
     * Gets all refunds related to client from Payment Service.
     *
     * Entries can be filtered using following parameters:
     * * `refunds_ids` - IDs of refunds that will be fetched from Payment Service.
     * * `payments_ids` - IDs of payments related to refunds that will be fetched from Payment Service.
     * * `external_refund_id` - External (app) refund ID. Method will return only refunds with specified external ID.
     * * `creation_date_from` - creation time in Zulu format. Method will return only refunds created after
     *                          specified date.
     * * `creation_date_to` - creation time in Zulu format. Method will return only refunds created before
     *                          specified date.
     * * `booked_date_from` - time, in Zulu format, when refund was booked. Method will return only refunds booked
     *                        after specified date.
     * * `booked_date_to` - time, in Zulu format, when refund was booked. Method will return only refunds booked
     *                      before specified date.
     * * `page_number` - page number.
     * * `page_size` - number of refunds per page.
     *
     * @param array $parameters filter parameters
     *
     * @throws ClientException request-level related problem e.g. HTTP errors, API errors.
     * @throws Exception       library-level related problem e.g. invalid data model.
     *
     * @return RefundResponseModelCollection
     */
    public function getRefunds(array $parameters = []): RefundResponseModelCollection;
}
