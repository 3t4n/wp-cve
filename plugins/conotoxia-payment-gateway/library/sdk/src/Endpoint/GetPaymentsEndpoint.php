<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Exception\Endpoint\GetPaymentsEndpointException;
use CKPL\Pay\Exception\PayloadException;
use CKPL\Pay\Model\Collection\PaymentResponseModelCollection;
use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;
use CKPL\Pay\Model\Request\GetPaymentsRequestModel;
use CKPL\Pay\Model\Response\PaginationResponseModel;
use CKPL\Pay\Model\Response\PaymentResponseModel;
use function count;
use function sprintf;

/**
 * Class GetPaymentsEndpoint.
 *
 * @package CKPL\Pay\Endpoint
 */
class GetPaymentsEndpoint implements EndpointInterface
{
    /**
     * @type string
     */
    const PARAMETER_PAYMENTS_IDS = 'payments_ids';

    /**
     * @type string
     */
    const PARAMETER_EXTERNAL_PAYMENT_ID = 'external_payment_id';

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
    const RESPONSE_EXTERNAL_PAYMENT_ID = 'externalPaymentId';

    /**
     * @type string
     */
    const RESPONSE_PAYMENT_ID = 'paymentId';

    /**
     * @type string
     */
    const RESPONSE_STATUS = 'status';

    /**
     * @type string
     */
    const RESPONSE_DATA = 'data';

    /**
     * @type string
     */
    const RESPONSE_PAGINATION = 'pagination';

    /**
     * @type string
     */
    protected const ENDPOINT = 'payments';

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
            ->signRequest()
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
            $result = new GetPaymentsRequestModel();

            $this->assignInput($parameters, $result);
        }

        return $result;
    }

    /**
     * @param RawOutputInterface $rawOutput
     *
     * @return ProcessedOutputInterface
     * @throws GetPaymentsEndpointException
     *
     */
    public function processRawOutput(RawOutputInterface $rawOutput): ProcessedOutputInterface
    {
        $payload = $rawOutput->getPayload();

        try {
            $data = $payload->expectArrayOrNull(static::RESPONSE_DATA);
            $pagination = $payload->expectArrayOrNull(static::RESPONSE_PAGINATION);

            $paymentCollection = new PaymentResponseModelCollection();

            $this->validatePagination($pagination);

            $paymentCollection->setPagination(
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

            foreach ($data as $payment) {
                if (!isset($payment[static::RESPONSE_EXTERNAL_PAYMENT_ID])) {
                    throw new GetPaymentsEndpointException(
                        sprintf(
                            GetPaymentsEndpointException::MISSING_RESPONSE_PARAMETER,
                            static::RESPONSE_EXTERNAL_PAYMENT_ID
                        )
                    );
                }

                if (!isset($payment[static::RESPONSE_PAYMENT_ID])) {
                    throw new GetPaymentsEndpointException(
                        sprintf(
                            GetPaymentsEndpointException::MISSING_RESPONSE_PARAMETER,
                            static::RESPONSE_PAYMENT_ID
                        )
                    );
                }

                if (!isset($payment[static::RESPONSE_STATUS])) {
                    throw new GetPaymentsEndpointException(
                        sprintf(
                            GetPaymentsEndpointException::MISSING_RESPONSE_PARAMETER,
                            static::RESPONSE_STATUS
                        )
                    );
                }

                $paymentCollection->add(
                    (new PaymentResponseModel())
                        ->setExternalPaymentId($payment[static::RESPONSE_EXTERNAL_PAYMENT_ID])
                        ->setPaymentId($payment[static::RESPONSE_PAYMENT_ID])
                        ->setStatus($payment[static::RESPONSE_STATUS])
                );
            }

            return $paymentCollection;
        } catch (PayloadException $e) {
            throw new GetPaymentsEndpointException('Unable to get payments list.', 0, $e);
        }
    }

    /**
     * @param array $pagination
     *
     * @return void
     * @throws GetPaymentsEndpointException
     *
     */
    protected function validatePagination(array $pagination): void
    {
        if (!isset($pagination[static::RESPONSE_PAGINATION_FIRST])) {
            throw new GetPaymentsEndpointException(
                sprintf(
                    GetPaymentsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_FIRST
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_LAST])) {
            throw new GetPaymentsEndpointException(
                sprintf(
                    GetPaymentsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_LAST
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_CURRENT_PAGE_NUMBER])) {
            throw new GetPaymentsEndpointException(
                sprintf(
                    GetPaymentsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_CURRENT_PAGE_NUMBER
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_CURRENT_PAGE_ELEMENT_COUNT])) {
            throw new GetPaymentsEndpointException(
                sprintf(
                    GetPaymentsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_CURRENT_PAGE_ELEMENT_COUNT
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_PAGE_SIZE])) {
            throw new GetPaymentsEndpointException(
                sprintf(
                    GetPaymentsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_PAGE_SIZE
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_TOTAL_PAGES])) {
            throw new GetPaymentsEndpointException(
                sprintf(
                    GetPaymentsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_TOTAL_PAGES
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_TOTAL_ELEMENTS])) {
            throw new GetPaymentsEndpointException(
                sprintf(
                    GetPaymentsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_TOTAL_ELEMENTS
                )
            );
        }

        if (!isset($pagination[static::RESPONSE_PAGINATION_PAGE_LIMIT_EXCEEDED])) {
            throw new GetPaymentsEndpointException(
                sprintf(
                    GetPaymentsEndpointException::MISSING_REQUEST_PARAMETERS,
                    static::RESPONSE_PAGINATION_PAGE_LIMIT_EXCEEDED
                )
            );
        }
    }

    /**
     * @param array $parameters
     * @param GetPaymentsRequestModel $getPaymentsRequestModel
     *
     * @return void
     */
    protected function assignInput(array $parameters, GetPaymentsRequestModel $getPaymentsRequestModel): void
    {
        if (isset($parameters[static::PARAMETER_PAYMENTS_IDS])) {
            $getPaymentsRequestModel->setPaymentsIds($parameters[static::PARAMETER_PAYMENTS_IDS]);
        }

        if (isset($parameters[static::PARAMETER_EXTERNAL_PAYMENT_ID])) {
            $getPaymentsRequestModel->setExternalPaymentId($parameters[static::PARAMETER_EXTERNAL_PAYMENT_ID]);
        }

        if (isset($parameters[static::PARAMETER_CREATION_DATE_FROM])) {
            $getPaymentsRequestModel->setCreationDateFrom($parameters[static::PARAMETER_CREATION_DATE_FROM]);
        }

        if (isset($parameters[static::PARAMETER_CREATION_DATE_TO])) {
            $getPaymentsRequestModel->setCreationDateTo($parameters[static::PARAMETER_CREATION_DATE_TO]);
        }

        if (isset($parameters[static::PARAMETER_BOOKED_DATE_FROM])) {
            $getPaymentsRequestModel->setBookedDateFrom($parameters[static::PARAMETER_BOOKED_DATE_FROM]);
        }

        if (isset($parameters[static::PARAMETER_BOOKED_DATE_TO])) {
            $getPaymentsRequestModel->setBookedDateTo($parameters[static::PARAMETER_BOOKED_DATE_TO]);
        }

        if (isset($parameters[static::PARAMETER_PAGE_NUMBER])) {
            $getPaymentsRequestModel->setPageNumber($parameters[static::PARAMETER_PAGE_NUMBER]);
        }

        if (isset($parameters[static::PARAMETER_PAGE_SIZE])) {
            $getPaymentsRequestModel->setPageSize($parameters[static::PARAMETER_PAGE_SIZE]);
        }

        if (isset($parameters[static::PARAMETER_SORT])) {
            $getPaymentsRequestModel->setSort($parameters[static::PARAMETER_SORT]);
        }
    }
}
