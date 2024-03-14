<?php

namespace HQRentalsPlugin\HQRentalsApi;

use HQRentalsPlugin\HQRentalsSettings\HQRentalsSettings;
use HQRentalsPlugin\HQRentalsTransformers\HQRentalsTransformersBrands;
use HQRentalsPlugin\HQRentalsTransformers\HQRentalsTransformersCarRentalSettings;
use HQRentalsPlugin\HQRentalsTransformers\HQRentalsTransformersGoogle;
use HQRentalsPlugin\HQRentalsTransformers\HQRentalsTransformersLocations;
use HQRentalsPlugin\HQRentalsTransformers\HQRentalsTransformersSettings;

class HQRentalsApiCallResolver
{
    public function __construct()
    {
        $this->settings = new HQRentalsSettings();
    }
    protected function resolveWPResponse($response, $data): HQRentalsApiResponse
    {
        if (is_wp_error($response)) {
            return new HQRentalsApiResponse(
                $this->resolveErrorMessageFromResponse($response),
                false,
                null
            );
        } else {
            return new HQRentalsApiResponse(
                null,
                true,
                $data
            );
        }
    }
    protected function resolveHQAPIv1Call($response, $data): HQRentalsApiResponse
    {
        if ($this->isErrorOnApiInteraction($response)) {
            return new HQRentalsApiResponse(
                $this->resolveErrorMessageFromResponse($response),
                false,
                null
            );
        } else {
            return new HQRentalsApiResponse(
                null,
                true,
                $data
            );
        }
    }

    public function resolveErrorMessageFromResponse($wpResponse)
    {
        if (is_wp_error($wpResponse)) {
            return $wpResponse->get_error_message();
        } else {
            $errorResponse = json_decode($wpResponse['body']);
            return $errorResponse->errors->error_message;
        }
    }

    public function isErrorOnApiInteraction($responseWP)
    {
        if (is_wp_error($responseWP)) {
            return true;
        }
        $responseData = json_decode($responseWP['body']);
        if (isset($responseData->errors) and $responseData->errors) {
            return true;
        }
        return false;
    }

    public function resolveApiCallAvailability($response)
    {
        if ($this->isErrorOnApiInteraction($response)) {
            return new HQRentalsApiResponse($this->resolveErrorMessageFromResponse($response), false, null);
        } else {
            return new HQRentalsApiResponse(null, true, json_decode($response['body']));
        }
    }

    public function resolveApiCallBrands($response): HQRentalsApiResponse
    {
        if ($this->isErrorOnApiInteraction($response)) {
            return new HQRentalsApiResponse(
                $this->resolveErrorMessageFromResponse($response),
                false,
                null
            );
        } else {
            return new HQRentalsApiResponse(
                null,
                true,
                HQRentalsTransformersBrands::transformDataFromApi(json_decode($response['body'])->fleets_brands)
            );
        }
    }

    public function resolveApiCallVehicleClasses($response)
    {
        if ($this->isErrorOnApiInteraction($response)) {
            return new HQRentalsApiResponse($this->resolveErrorMessageFromResponse($response), false, null);
        } else {
            return new HQRentalsApiResponse(null, true, json_decode($response['body'])->data);
        }
    }

    public function resolveApiCallLocations($response)
    {
        if ($this->isErrorOnApiInteraction($response)) {
            return new HQRentalsApiResponse($this->resolveErrorMessageFromResponse($response), false, null);
        } else {
            $parseData = json_decode($response['body']);
            if (isset($parseData->fleets_locations)) {
                return new HQRentalsApiResponse(null, true, HQRentalsTransformersLocations::transformDataFromApi($parseData->fleets_locations));
            }
            return new HQRentalsApiResponse(null, true, HQRentalsTransformersLocations::transformDataFromApi([]));
        }
    }

    public function resolveApiCallAdditionalCharges($response)
    {
        if ($this->isErrorOnApiInteraction($response)) {
            return new HQRentalsApiResponse($this->resolveErrorMessageFromResponse($response), false, null);
        } else {
            return new HQRentalsApiResponse(null, true, json_decode($response['body'])->fleets_additional_charges);
        }
    }

    public function resolveVehicleTypes($response): HQRentalsApiResponse
    {
        if ($this->isErrorOnApiInteraction($response)) {
            return new HQRentalsApiResponse($this->resolveErrorMessageFromResponse($response), false, null);
        } else {
            return new HQRentalsApiResponse(null, true, json_decode($response['body'])->fleets_vehicle_types);
        }
    }

    public function resolverApiCallSystemAssets($response): HQRentalsApiResponse
    {
        return $this->resolveWPResponse($response, json_decode($response['body']));
    }

    public function resolveApiCallForCustomFields($response): HQRentalsApiResponse
    {
        return $this->resolveWPResponse($response, json_decode($response['body'])->data);
    }

    public function resolveVehicleForm($response): HQRentalsApiResponse
    {
        return $this->resolveWPResponse($response, json_decode($response['body'])->data);
    }

    public function resolveApiCallTenantsSettings($response): HQRentalsApiResponse
    {
        return $this->resolveWPResponse(
            $response,
            HQRentalsTransformersSettings::transformDataFromApi(json_decode($response['body'])->data)
        );
    }
    public function resolveApiCallCarRentalSettings($response): HQRentalsApiResponse
    {
        return $this->resolveWPResponse(
            $response,
            HQRentalsTransformersCarRentalSettings::transformDataFromApi(json_decode($response['body'])->data)
        );
    }

    public function resolveGoogleAutocomplete($response): HQRentalsApiResponse
    {
        return $this->resolveWPResponse(
            $response,
            HQRentalsTransformersGoogle::transformGoogleAutocompleteData(json_decode($response['body']))
        );
    }

    public function resolveGooglePlaceDetails($response): HQRentalsApiResponse
    {
        return $this->resolveWPResponse(
            $response,
            HQRentalsTransformersGoogle::transformGooglePlaceData(json_decode($response['body']))
        );
    }

    public function resolveActivation($response): HQRentalsApiResponse
    {
        return $this->resolveWPResponse($response, json_decode($response['body']));
    }

    public function resolveApiCallForAuth($response): HQRentalsApiResponse
    {
        return $this->resolveWPResponse($response, json_decode($response['body']));
    }
    public function resolveSendQuote($response): HQRentalsApiResponse
    {
        return $this->resolveWPResponse($response, is_wp_error($response) ? $response : json_decode($response['body']));
    }
    public function resolveDates($response): HQRentalsApiResponse
    {
        return $this->resolveWPResponse($response, json_decode($response['body'])->data);
    }
    public function resolveCaptchaResponse($response): HQRentalsApiResponse
    {
        $data = $this->resolveWPResponse($response, json_decode($response['body']));
        return new HQRentalsApiResponse(
            $data->data->{"error-codes"},
            $data->data->success,
            null
        );
    }
}
