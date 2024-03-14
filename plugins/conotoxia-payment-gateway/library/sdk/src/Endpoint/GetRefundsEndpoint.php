<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Exception\Endpoint\GetRefundsEndpointException;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Model\Collection\RefundResponseModelCollection;
use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\Request\GetRefundsRequestModel;
use CKPL\Pay\Model\Response\PaginationResponseModel;
use CKPL\Pay\Model\Response\RefundResponseModel;
use function count;
use function sprintf;

/**
 * Class GetRefundsEndpoint.
 *
 * @package CKPL\Pay\Endpoint
 */
class GetRefundsEndpoint implements EndpointInterface
{
    /**
     * @type string
     */
    const PARAMETER_PAYMENTS_IDS = 'payments_ids';

    /**
     * @type string
     */
    const PARAMETER_REFUNDS_IDS = 'refunds_ids';

    /**
     * @type string
     */
    const PARAMETER_EXTERNAL_REFUND_ID = 'external_refund_id';

    /**
     * @type string
     */
    const PARAMETER_CREATION_DATE_FROM = 'creation_date_from';

    /**
     * @type string
     */
    const PARAMETER_CREATION_DATE_TO = 'creation_date_to';

    /**
     * @type string
     */
    const PARAMETER_BOOKED_DATE_FROM = 'booked_date_from';

    /**
     * @type string
     */
    const PARAMETER_BOOKED_DATE_TO = 'booked_date_to';

    /**
     * @type string
     */
    const PARAMETER_PAGE_NUMBER = 'page_number';

    /**
     * @type string
     */
    const PARAMETER_PAGE_SIZE = 'page_size';

    /**
     * @type string
     */
    const PARAMETER_SORT = 'sort';

    /**
     * @type string
     */
    const RESPONSE_DATA = 'data';

    /**
     * @type string
     */
    const RESPONSE_REFUND_ID = 'refundId';

    /**
     * @type string
     */
    const RESPONSE_EXTERNAL_REFUND_ID = 'externalRefundId';

    /**
     * @type string
     */
    const RESPONSE_STATUS = 'status';

    /**
     * @type string
     */
    const RESPONSE_PAGINATION = 'pagination';

    /**
     * @type string
     */
    const RESPONSE_PAGINATION_FIRST = 'first';

    /**
     * @type string
     */
    const RESPONSE_PAGINATION_LAST = 'last';

    /**
     * @type string
     */
    const RESPONSE_PAGINATION_CURRENT_PAGE_NUMBER = 'currentPageNumber';

    /**
     * @type string
     */
    const RESPONSE_PAGINATION_CURRENT_PAGE_ELEMENT_COUNT = 'currentPageElementsCount';

    /**
     * @type string
     */
    const RESPONSE_PAGINATION_PAGE_SIZE = 'pageSize';

    /**
     * @type string
     */
    const RESPONSE_PAGINATION_TOTAL_PAGES = 'totalPages';

    /**
     * @type string
     */
    const RESPONSE_PAGINATION_TOTAL_ELEMENTS = 'totalElements';

    /**
     * @type string
     */
    const RESPONSE_PAGINATION_PAGE_LIMIT_EXCEEDED = 'pageLimitExceeded';

    /**
     * @type string
     */
    protected const ENDPOINT = 'refunds';

    /**
     * @param EndpointConfigurationFactoryInterface $configurationFactory
     *
     * @return void
     */
    public function configuration(EndpointConfigurationFactoryInterface $configurationFactory): void
    {
        $configurationFactory
            ->url(static::ENDPOINT)
            ->asGet()
            ->toPayments()
            ->encodeWithJson()
            ->expectSignedResponse()
            ->plainRequest()
            ->authorized();
    }

    /**
     * @param array $parameters
     *
     * @return ProcessedInputInterface|null
     */
    public function processRawInput(array $parameters): ?ProcessedInputInterface
    {
        $result = null;

        if (count($parameters) > 0) {
            $result = new GetRefundsRequestModel();

            $this->assignInput($parameters, $result);
        }

        return $result;
    }

    /**
     * @param RawOutputInterface $rawOutput
     *
     * @throws GetRefundsEndpointException
     *
     * @return ProcessedOutputInterface
     */
    public function processRawOutput(RawOutputInterface $rawOutput): ProcessedOutputInterface
    {
        try {
            $payload = $rawOutput->getPayload();
            $refundCollection = new RefundResponseModelCollection();

            $pagination = $payload->expectArrayOrNull(static::RESPONSE_PAGINATION);
            $data = $payload->expectArrayOrNull(static::RESPONSE_DATA);

            $this->validatePagination($pagination);
            $this->assignPagination($refundCollection, $pagination);
            $this->assignData($refundCollection, $data);

            return $refundCollection;
        } catch (PayloadException $e) {
            throw new GetRefundsEndpointException('Unable to get refunds list.', 0, $e);
        }
    }

    /**
     * @param array $pagination
     *
     * @throws GetRefundsEndpointException
     *
     * @return void
     */
    protected function validatePagination(array $pagination): void
    {
        if (!isset($pagination[static::RESPONSE_PAGINATION_FIRST])) {
            throw new GetRefundsEndpointException(
                sprintf(
                    GetRefundsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_FIRST
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_LAST])) {
            throw new GetRefundsEndpointException(
                sprintf(
                    GetRefundsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_LAST
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_CURRENT_PAGE_NUMBER])) {
            throw new GetRefundsEndpointException(
                sprintf(
                    GetRefundsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_CURRENT_PAGE_NUMBER
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_CURRENT_PAGE_ELEMENT_COUNT])) {
            throw new GetRefundsEndpointException(
                sprintf(
                    GetRefundsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_CURRENT_PAGE_ELEMENT_COUNT
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_PAGE_SIZE])) {
            throw new GetRefundsEndpointException(
                sprintf(
                    GetRefundsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_PAGE_SIZE
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_TOTAL_PAGES])) {
            throw new GetRefundsEndpointException(
                sprintf(
                    GetRefundsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_TOTAL_PAGES
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_TOTAL_ELEMENTS])) {
            throw new GetRefundsEndpointException(
                sprintf(
                    GetRefundsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_TOTAL_ELEMENTS
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_PAGE_LIMIT_EXCEEDED])) {
            throw new GetRefundsEndpointException(
                sprintf(
                    GetRefundsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_PAGE_LIMIT_EXCEEDED
                )
            );
        }
    }

    /**
     * @param array                  $parameters
     * @param GetRefundsRequestModel $getRefundsRequestModel
     *
     * @return void
     */
    protected function assignInput(array $parameters, GetRefundsRequestModel $getRefundsRequestModel): void
    {
        if (isset($parameters[static::PARAMETER_PAYMENTS_IDS])) {
            $getRefundsRequestModel->setPaymentsIds($parameters[static::PARAMETER_PAYMENTS_IDS]);
        }

        if (isset($parameters[static::PARAMETER_REFUNDS_IDS])) {
            $getRefundsRequestModel->setRefundsIds($parameters[static::PARAMETER_REFUNDS_IDS]);
        }

        if (isset($parameters[static::PARAMETER_EXTERNAL_REFUND_ID])) {
            $getRefundsRequestModel->setExternalRefundId($parameters[static::PARAMETER_EXTERNAL_REFUND_ID]);
        }

        if (isset($parameters[static::PARAMETER_CREATION_DATE_FROM])) {
            $getRefundsRequestModel->setCreationDateFrom($parameters[static::PARAMETER_CREATION_DATE_FROM]);
        }

        if (isset($parameters[static::PARAMETER_CREATION_DATE_TO])) {
            $getRefundsRequestModel->setCreationDateTo($parameters[static::PARAMETER_CREATION_DATE_TO]);
        }

        if (isset($parameters[static::PARAMETER_BOOKED_DATE_FROM])) {
            $getRefundsRequestModel->setBookedDateFrom($parameters[static::PARAMETER_BOOKED_DATE_FROM]);
        }

        if (isset($parameters[static::PARAMETER_BOOKED_DATE_TO])) {
            $getRefundsRequestModel->setBookedDateTo($parameters[static::PARAMETER_BOOKED_DATE_TO]);
        }

        if (isset($parameters[static::PARAMETER_PAGE_NUMBER])) {
            $getRefundsRequestModel->setPageNumber($parameters[static::PARAMETER_PAGE_NUMBER]);
        }

        if (isset($parameters[static::PARAMETER_PAGE_SIZE])) {
            $getRefundsRequestModel->setPageSize($parameters[static::PARAMETER_PAGE_SIZE]);
        }

        if (isset($parameters[static::PARAMETER_SORT])) {
            $getRefundsRequestModel->setSort($parameters[static::PARAMETER_SORT]);
        }
    }

    /**
     * @param RefundResponseModelCollection $refundResponseModelCollection
     * @param array                         $pagination
     */
    protected function assignPagination(RefundResponseModelCollection $refundResponseModelCollection, array $pagination): void
    {
        $refundResponseModelCollection->setPagination(
            (new PaginationResponseModel())
                ->setFirst($pagination[static::RESPONSE_PAGINATION_FIRST])
                ->setLast($pagination[static::RESPONSE_PAGINATION_LAST])
                ->setCurrentPageNumber($pagination[static::RESPONSE_PAGINATION_CURRENT_PAGE_NUMBER])
                ->setCurrentPageElementsCount($pagination[static::RESPONSE_PAGINATION_CURRENT_PAGE_ELEMENT_COUNT])
                ->setPageSize($pagination[static::RESPONSE_PAGINATION_PAGE_SIZE])
                ->setTotalPages($pagination[static::RESPONSE_PAGINATION_TOTAL_PAGES])
                ->setTotalElements($pagination[static::RESPONSE_PAGINATION_TOTAL_ELEMENTS])
                ->setPageLimitExceeded($pagination[static::RESPONSE_PAGINATION_PAGE_LIMIT_EXCEEDED])
        );
    }

    /**
     * @param RefundResponseModelCollection $refundResponseModelCollection
     * @param array                         $data
     *
     * @throws GetRefundsEndpointException
     */
    protected function assignData(RefundResponseModelCollection $refundResponseModelCollection, array $data): void
    {
        $validate = [
            static::RESPONSE_REFUND_ID,
            static::RESPONSE_STATUS,
        ];

        foreach ($data as $refund) {
            foreach ($validate as $value) {
                if (!isset($refund[$value])) {
                    throw new GetRefundsEndpointException(
                        sprintf(
                            GetRefundsEndpointException::MISSING_RESPONSE_PARAMETER,
                            $value
                        )
                    );
                }
            }

            $refundModel = new RefundResponseModel();

            $refundResponseModelCollection->add(
                $refundModel
                    ->setRefundId($refund[static::RESPONSE_REFUND_ID])
                    ->setStatus($refund[static::RESPONSE_STATUS])
            );

            if (isset($refund[static::RESPONSE_EXTERNAL_REFUND_ID])) {
                $refundModel->setExternalRefundId($refund[static::RESPONSE_EXTERNAL_REFUND_ID]);
            }
        }
    }
}
