<?php

trait MM_WPFS_InlineFormValidator {

	/**
	 * @param MM_WPFS_BindingResult $bindingResult
	 * @param MM_WPFS_Public_FormModel $formModelObject
	 */
	protected function validateInlineFields( $bindingResult, $formModelObject ) {
		if ( $formModelObject instanceof MM_WPFS_Public_InlineForm ) {
            if ( empty( $formModelObject->getCardHolderName() ) ) {
                $fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_CARD_HOLDER_NAME;
                $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                $error     = __( 'Please enter the cardholder\'s name', 'wp-full-stripe' );
                $bindingResult->addFieldError( $fieldName, $fieldId, $error );
            }
			if ( ! filter_var( $formModelObject->getCardHolderEmail(), FILTER_VALIDATE_EMAIL ) ) {
				$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_CARD_HOLDER_EMAIL;
				$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
				$error     = __( 'Please enter a valid email address', 'wp-full-stripe' );
				$bindingResult->addFieldError( $fieldName, $fieldId, $error );
			}
			if ( $this->showBillingAddress( $formModelObject ) ) {
				if ( empty( $formModelObject->getBillingName() ) ) {
					$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_NAME;
					$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
					$error     = __( 'Please enter a billing name', 'wp-full-stripe' );
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				}
				if ( empty( $formModelObject->getBillingAddressLine1() ) ) {
					$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_LINE_1;
					$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
					$error     = __( 'Please enter a billing address', 'wp-full-stripe' );
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				}
				if ( empty( $formModelObject->getBillingAddressCity() ) ) {
					$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_CITY;
					$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
					$error     = __( 'Please enter a city', 'wp-full-stripe' );
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				}
				if ( empty( $formModelObject->getBillingAddressCountry() ) ) {
					$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_COUNTRY;
					$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
					$error     = __( 'Please select a country', 'wp-full-stripe' );
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				}
				if ( $formModelObject->getBillingAddressCountry() === MM_WPFS::COUNTRY_CODE_UNITED_STATES ) {
				    $states = MM_WPFS_States::getAvailableStates();

				    if ( ! array_key_exists( $formModelObject->getBillingAddressState(), $states ) ) {
                        $fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_STATE_SELECT;
                        $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                        $error     = __( 'Please select a state', 'wp-full-stripe' );
                        $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                    }
                }
				// tnagy WPFS-886 fix: some countries do not have or do not use postcodes
				$validateBillingAddressZip = null;
				if ( empty( $formModelObject->getBillingAddressCountryComposite() ) ) {
					$validateBillingAddressZip = false;
					if ( ! $bindingResult->hasFieldErrors( $formModelObject::PARAM_WPFS_BILLING_ADDRESS_COUNTRY ) ) {
						$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_COUNTRY;
						$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
						$error     = __( 'Please select a country', 'wp-full-stripe' );
						$bindingResult->addFieldError( $fieldName, $fieldId, $error );
					}
				} else {
					$billingAddressCountryComposite = $formModelObject->getBillingAddressCountryComposite();
					if ( true === $billingAddressCountryComposite['usePostCode'] ) {
						$validateBillingAddressZip = true;
					} else {
						$validateBillingAddressZip = false;
					}
				}
				if ( $validateBillingAddressZip ) {
					if ( empty( $formModelObject->getBillingAddressZip() ) ) {
						$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_BILLING_ADDRESS_ZIP;
						$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
						$error     = __( 'Please enter a zip/postal code', 'wp-full-stripe' );
						$bindingResult->addFieldError( $fieldName, $fieldId, $error );
					}
				}
			}
			if ( 0 == $formModelObject->getSameBillingAndShippingAddress() ) {
				if ( $this->showShippingAddress( $formModelObject ) ) {
					if ( empty( $formModelObject->getShippingName() ) ) {
						$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_NAME;
						$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
						$error     = __( 'Please enter a shipping name', 'wp-full-stripe' );
						$bindingResult->addFieldError( $fieldName, $fieldId, $error );
					}
					if ( empty( $formModelObject->getShippingAddressLine1() ) ) {
						$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_LINE_1;
						$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
						$error     = __( 'Please enter a shipping address', 'wp-full-stripe' );
						$bindingResult->addFieldError( $fieldName, $fieldId, $error );
					}
					if ( empty( $formModelObject->getShippingAddressCity() ) ) {
						$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_CITY;
						$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
						$error     = __( 'Please enter a city', 'wp-full-stripe' );
						$bindingResult->addFieldError( $fieldName, $fieldId, $error );
					}
					if ( empty( $formModelObject->getShippingAddressCountry() ) ) {
						$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_COUNTRY;
						$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
						$error     = __( 'Please select a country', 'wp-full-stripe' );
						$bindingResult->addFieldError( $fieldName, $fieldId, $error );
					}
                    if ( $formModelObject->getShippingAddressCountry() === MM_WPFS::COUNTRY_CODE_UNITED_STATES ) {
                        $states = MM_WPFS_States::getAvailableStates();

                        if ( ! array_key_exists( $formModelObject->getShippingAddressState(), $states ) ) {
                            $fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_STATE_SELECT;
                            $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                            $error     = __( 'Please select a state', 'wp-full-stripe' );
                            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                        }
                    }
					// tnagy WPFS-886 fix: some countries do not have or do not use postcodes
					$validateShippingAddressZip = null;
					if ( empty( $formModelObject->getShippingAddressCountryComposite() ) ) {
						$validateShippingAddressZip = false;
						if ( ! $bindingResult->hasFieldErrors( $formModelObject::PARAM_WPFS_SHIPPING_ADDRESS_COUNTRY ) ) {
							$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_COUNTRY;
							$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
							$error     = __( 'Please select a country', 'wp-full-stripe' );
							$bindingResult->addFieldError( $fieldName, $fieldId, $error );
						}
					} else {
						$shippingAddressCountryComposite = $formModelObject->getShippingAddressCountryComposite();
						if ( true === $shippingAddressCountryComposite['usePostCode'] ) {
							$validateShippingAddressZip = true;
						} else {
							$validateShippingAddressZip = false;
						}
					}
					if ( $validateShippingAddressZip ) {
						if ( empty( $formModelObject->getShippingAddressZip() ) ) {
							$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_SHIPPING_ADDRESS_ZIP;
							$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
							$error     = __( 'Please enter a zip/postal code', 'wp-full-stripe' );
							$bindingResult->addFieldError( $fieldName, $fieldId, $error );
						}
					}
				}
			}
		}
	}

	/**
	 * @param MM_WPFS_Public_FormModel $formModelObject
	 *
	 * @return bool
	 */
	protected function showBillingAddress( $formModelObject ) {
		$showBillingAddress = false;
		if ( isset( $formModelObject->getForm()->showAddress ) ) {
			$showBillingAddress = 1 == $formModelObject->getForm()->showAddress;

			return $showBillingAddress;
		} elseif ( isset( $formModelObject->getForm()->showBillingAddress ) ) {
			$showBillingAddress = 1 == $formModelObject->getForm()->showBillingAddress;

			return $showBillingAddress;
		}

		return $showBillingAddress;
	}

	/**
	 * @param MM_WPFS_Public_FormModel $formModelObject
	 *
	 * @return bool
	 */
	protected function showShippingAddress( $formModelObject ) {
		$showShippingAddress = false;
		if ( isset( $formModelObject->getForm()->showShippingAddress ) ) {
			$showShippingAddress = 1 == $formModelObject->getForm()->showShippingAddress;

			return $showShippingAddress;
		}

		return $showShippingAddress;
	}

}

/**
 * Used by WPFSM, WPFP_Mailchimp
 */
abstract class MM_WPFS_Validator {
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    protected $options;

    public function __construct( $loggerService ) {
        $this->initLogger( $loggerService, MM_WPFS_LoggerService::MODULE_RUNTIME );
        $this->options = new MM_WPFS_Options();

        $this->initStaticContext();
    }

    /**
	 * @param MM_WPFS_BindingResult $bindingResult
	 * @param MM_WPFS_Binder $formModelObject
	 */
	public abstract function validate( $bindingResult, $formModelObject );

}

class MM_WPFS_FormValidator extends MM_WPFS_Validator {

    /**
	 * @var array
	 */
	protected $fieldsToIgnore;

	/**
	 * MM_WPFS_FormValidator constructor.
	 */
	public function __construct( $loggerService ) {
        parent::__construct( $loggerService );

		$this->fieldsToIgnore = array();
	}

	public final function validate( $bindingResult, $formModelObject ) {
		$this->validateForm( $bindingResult, $formModelObject );
		if ( ! $bindingResult->hasErrors() ) {
			$this->validateFields( $bindingResult, $formModelObject );
		}
	}

	/**
	 * @param MM_WPFS_BindingResult $bindingResult
	 * @param MM_WPFS_Public_FormModel $formModelObject
	 */
	protected function validateForm( $bindingResult, $formModelObject ) {
		if ( is_null( $formModelObject->getFormName() ) ) {
            // This is an internal error, no need to localize it
			$error = 'Invalid form name';
			$bindingResult->addGlobalError( $error );
		} else {
			$formObject = $formModelObject->getForm();

			if ( is_null( $formObject ) ) {
                if ( $formModelObject instanceof MM_WPFS_Public_InlinePaymentFormModel ) {
                    $formObject = $formModelObject->getDb()->getInlinePaymentFormByName( $formModelObject->getFormName() );
                }
                if ( $formModelObject instanceof MM_WPFS_Public_CheckoutPaymentFormModel ) {
                    $formObject = $formModelObject->getDb()->getCheckoutPaymentFormByName( $formModelObject->getFormName() );
                }
                if ( $formModelObject instanceof MM_WPFS_Public_InlineDonationFormModel ) {
                    $formObject = $formModelObject->getDb()->getInlineDonationFormByName( $formModelObject->getFormName() );
                }
                if ( $formModelObject instanceof MM_WPFS_Public_CheckoutDonationFormModel ) {
                    $formObject = $formModelObject->getDb()->getCheckoutDonationFormByName( $formModelObject->getFormName() );
                }
                if ( $formModelObject instanceof MM_WPFS_Public_CheckoutDonationFormModel ) {
                    $formObject = $formModelObject->getDb()->getCheckoutDonationFormByName( $formModelObject->getFormName() );
                }
                if ( $formModelObject instanceof MM_WPFS_Public_InlineSubscriptionFormModel ) {
                    $formObject = $formModelObject->getDb()->getInlineSubscriptionFormByName( $formModelObject->getFormName() );
                }
                if ( $formModelObject instanceof MM_WPFS_Public_CheckoutSubscriptionFormModel ) {
                    $formObject = $formModelObject->getDb()->getCheckoutSubscriptionFormByName( $formModelObject->getFormName() );
                }

                if ( is_null( $formObject ) ) {
                    // This is an internal error, no need to localize it
                    $bindingResult->addGlobalError( 'Invalid form name or form not found' );
                } else {
                    $formModelObject->setForm( $formObject );
                }
            }

			$bindingResult->setFormHash( $formModelObject->getFormHash() );
		}
	}

	/**
	 * @param MM_WPFS_BindingResult $bindingResult
	 * @param MM_WPFS_Public_FormModel $formModelObject
	 */
	protected function validateFields( $bindingResult, $formModelObject ) {
		if ( $formModelObject instanceof MM_WPFS_Public_FormModel ) {
			if ( isset( $formModelObject->getForm()->showCouponInput ) && 1 == $formModelObject->getForm()->showCouponInput ) {
				if ( $formModelObject->getStripeCoupon() ) {
					if ( false === $formModelObject->getStripeCoupon()->valid ) {
						$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_COUPON;
						$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
						$error     = MM_WPFS_Utils::getDefaultCouponInvalidErrorMessage();
						$bindingResult->addFieldError( $fieldName, $fieldId, $error );
					} else {
						$appliesTo = true;
						if ( isset( $formModelObject->getStripeCoupon()->applies_to ) && isset( $formModelObject->getStripeCoupon()->applies_to->products ) ) {
							$form = $formModelObject->getForm();
							if ( isset( $form->decoratedProducts ) ) {
								$savedProducts = MM_WPFS_Utils::decodeJsonArray( $form->decoratedProducts );
							} elseif ( isset( $form->decoratedPlans ) ) {
								$savedProducts = MM_WPFS_Utils::decodeJsonArray( $form->decoratedPlans );
							} else {
								$savedProducts = array();
							}

							if ( isset( $savedProducts ) && ! empty( $savedProducts ) ) {
								$priceIds = MM_WPFS_Pricing::extractPriceIdsFromProductsStatic( $savedProducts );

								if ( isset( $priceIds ) && ! empty( $priceIds ) ) {
									$productIds = $formModelObject->getStripe()->retrieveProductIdsByPriceIds( $priceIds );
									$intersect = array_intersect( $formModelObject->getStripeCoupon()->applies_to->products, $productIds );
									if ( count( $intersect ) === 0 ) {
										$appliesTo = false;
									} else {
										$appliesTo = true;
									}
								}
							}
						}

						if ( false === $appliesTo ) {
							$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_COUPON;
							$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
							$error     = MM_WPFS_Utils::getDefaultCouponDoesNotApplyToErrorMessage();
							$bindingResult->addFieldError( $fieldName, $fieldId, $error );
						}
					}

					if ( $formModelObject instanceof MM_WPFS_Public_PaymentFormModel ) {
                        if ( ! is_null( $formModelObject->getStripeCoupon()->amount_off ) && $formModelObject->getForm()->currency != $formModelObject->getStripeCoupon()->currency ) {
                            $fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_COUPON;
                            $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                            $error     = MM_WPFS_Utils::getDefaultInvalidCouponCurrencyErrorMessage();
                            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                        }
                    }
				}
			} else {
				$formModelObject->setStripeCoupon( null );
			}
			if ( isset( $formModelObject->getForm()->showTermsOfUse ) && 1 == $formModelObject->getForm()->showTermsOfUse ) {
				if ( 0 == $formModelObject->getTermsOfUseAccepted() ) {
					$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_TERMS_OF_USE_ACCEPTED;
					$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
					$error     = MM_WPFS_Utils::getDefaultTermsOfUseNotCheckedErrorMessage();
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				}
			}
			if ( ! $this->isIgnored( MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT ) ) {
				if ( 1 == $formModelObject->getForm()->showCustomInput ) {
					if ( 1 == $formModelObject->getForm()->customInputRequired ) {
						if ( is_null( $formModelObject->getForm()->customInputs ) ) {
							if ( is_null( $formModelObject->getCustomInputvalues() ) || ( false == trim( $formModelObject->getCustomInputvalues() ) ) ) {
								$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT;
								$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
								$error     = sprintf(
                                /* translators: Error message for required fields when empty.
                                * p1: custom input field label
                                */
                                __( "Please enter a value for '%s'", 'wp-full-stripe' ), MM_WPFS_Localization::translateLabel($formModelObject->getForm()->customInputTitle));
								$bindingResult->addFieldError( $fieldName, $fieldId, $error );
							}
						} else {
							$customInputLabels = MM_WPFS_Utils::decodeCustomFieldLabels( $formModelObject->getForm()->customInputs );
							foreach ( $customInputLabels as $index => $label ) {
								if ( is_null( $formModelObject->getCustomInputvalues()[ $index ] ) || ( false == trim( $formModelObject->getCustomInputvalues()[ $index ] ) ) ) {
									$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT;
									$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash(), $index );
                                    /* translators: Error message for required fields when empty.
                                    * p1: custom input field label
                                    */
									$error     = sprintf( __( "Please enter a value for '%s'", 'wp-full-stripe' ), MM_WPFS_Localization::translateLabel($label));
									$bindingResult->addFieldError( $fieldName, $fieldId, $error );
								}
							}
						}
					}
					if ( is_null( $formModelObject->getForm()->customInputs ) ) {
						if ( is_string( $formModelObject->getCustomInputvalues() ) && strlen( $formModelObject->getCustomInputvalues() ) > MM_WPFS_Utils::STRIPE_METADATA_VALUE_MAX_LENGTH ) {
							$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT;
							$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
							$error     = sprintf(
							    /* translators: Form field validation error for custom fields */
							    __( "The value for '%s' is too long", 'wp-full-stripe' ), MM_WPFS_Localization::translateLabel($formModelObject->getForm()->customInputTitle));
							$bindingResult->addFieldError( $fieldName, $fieldId, $error );
						}
					} else {
						$customInputLabels = MM_WPFS_Utils::decodeCustomFieldLabels( $formModelObject->getForm()->customInputs );
						foreach ( $customInputLabels as $index => $label ) {
							if ( is_string( $formModelObject->getCustomInputvalues()[ $index ] ) && strlen( $formModelObject->getCustomInputvalues()[ $index ] ) > MM_WPFS_Utils::STRIPE_METADATA_VALUE_MAX_LENGTH ) {
								$fieldName = MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT;
								$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash(), $index );
								$error     = sprintf(
                                    /* translators: Form field validation error for custom fields */
								    __( "The value for '%s' is too long", 'wp-full-stripe' ), MM_WPFS_Localization::translateLabel($label));
								$bindingResult->addFieldError( $fieldName, $fieldId, $error );
							}
						}
					}
				}
			}
		}
	}

	protected function isIgnored( $fieldName ) {
		return array_key_exists( $fieldName, $this->fieldsToIgnore );
	}

	protected function ignore( $fieldName ) {
		if ( ! array_key_exists( $fieldName, $this->fieldsToIgnore ) ) {
			$this->fieldsToIgnore[ $fieldName ] = true;
		}
	}

	protected function unIgnore( $fieldName ) {
		if ( array_key_exists( $fieldName, $this->fieldsToIgnore ) ) {
			unset( $this->fieldsToIgnore[ $fieldName ] );
		}
	}

	/**
	 * @param MM_WPFS_BindingResult $bindingResult
	 * @param MM_WPFS_Public_FormModel $formModelObject
	 */
	protected function validateGoogleReCaptcha( $bindingResult, $formModelObject ) {
		$validateGoogleReCaptcha = false;
		if ( $formModelObject instanceof MM_WPFS_Public_InlineForm ) {
			if (MM_WPFS_ReCaptcha::getSecureInlineForms( $this->staticContext )) {
				$validateGoogleReCaptcha = true;
			}
		} elseif ( $formModelObject instanceof MM_WPFS_Public_PopupForm ) {
			if (MM_WPFS_ReCaptcha::getSecureCheckoutForms( $this->staticContext )) {
				$validateGoogleReCaptcha = true;
			}
		}
		if ( $validateGoogleReCaptcha ) {
			$fieldName = $formModelObject::PARAM_GOOGLE_RECAPTCHA_RESPONSE;
			$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
			$error     =
                /* translators: Captcha validation error message displayed when the form is submitted without completing the captcha challenge */
                __( "Please prove that you are not a robot. ", 'wp-full-stripe' );
			if ( is_null( $formModelObject->getGoogleReCaptchaResponse() ) ) {
				$bindingResult->addFieldError( $fieldName, $fieldId, $error );
			} else {
				if ( empty( $formModelObject->getNonce() ) ) {
					$googleReCaptchaVerificationResult = MM_WPFS_ReCaptcha::verifyReCAPTCHA( $this->staticContext, $formModelObject->getGoogleReCaptchaResponse());
					if ( $googleReCaptchaVerificationResult === false ) {
						$bindingResult->addFieldError( $fieldName, $fieldId, $error );
					} elseif ( ! isset( $googleReCaptchaVerificationResult->success ) || $googleReCaptchaVerificationResult->success === false ) {
						$bindingResult->addFieldError( $fieldName, $fieldId, $error );
					} else {
						$formModelObject->setNonce( MM_WPFS_Utils::encrypt( MM_WPFS_Utils::generateFormNonce( $formModelObject ) ) );
					}
				} else {
					$this->validateFormNonce( $bindingResult, $formModelObject );
				}
			}
		}
	}

	/**
	 * @param MM_WPFS_BindingResult $bindingResult
	 * @param MM_WPFS_Public_FormModel $formModelObject
	 */
	private function validateFormNonce( $bindingResult, $formModelObject ) {
		$decryptedText = MM_WPFS_Utils::decrypt( $formModelObject->getNonce() );
		// This is an internal error, no need to localize it
		$error = 'Invalid form data';
		if ( false === $decryptedText ) {
			$bindingResult->addGlobalError( $error );
		} else {
			$nonceObject = MM_WPFS_Utils::decodeFormNonce( $decryptedText );
			if ( false === $nonceObject ) {
                $this->logger->debug(__FUNCTION__, 'NonceObject is false');

				$bindingResult->addGlobalError( $error );
			} else {
				if ( ! isset( $nonceObject->formHash ) || $formModelObject->getFormHash() !== $nonceObject->formHash ) {
                    $this->logger->debug(__FUNCTION__, 'FormHash error');
					$bindingResult->addGlobalError( $error );
				} elseif ( ! isset( $nonceObject->created ) || $this->olderThan( $nonceObject->created, 10 ) ) {
                    $this->logger->debug(__FUNCTION__, 'Creation time error');
					$bindingResult->addGlobalError( $error );
				} elseif ( ! isset( $nonceObject->fieldHash ) || md5( json_encode( $formModelObject ) ) !== $nonceObject->fieldHash ) {
                    $this->logger->debug(__FUNCTION__, 'FieldHash error');
					$bindingResult->addGlobalError( $error );
				}
			}
		}
	}

	private function olderThan( $aTime, $minutes ) {
		$expiration = time() - $minutes * 60;

        $this->logger->debug(__FUNCTION__, 'olderThan(): time=' . print_r( $aTime, true ) . ', expiration=' . print_r( $expiration, true ));

		return $aTime < $expiration;
	}

}

class MM_WPFS_PaymentFormValidator extends MM_WPFS_FormValidator {
    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    protected function validateFields( $bindingResult, $formModelObject ) {
		parent::validateFields( $bindingResult, $formModelObject );
		if ( $formModelObject instanceof MM_WPFS_Public_PaymentFormModel ) {

			if ( $this->validateCustomAmount( $formModelObject ) ) {
				$fieldName = $formModelObject::PARAM_WPFS_CUSTOM_AMOUNT_UNIQUE;
				$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
				if ( empty( $formModelObject->getAmount() ) ) {
					$error =
                       /* translators: Form field validation error message when custom amount is empty */
                        __( 'Please enter an amount', 'wp-full-stripe' );
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				} elseif ( ! is_numeric( trim( $formModelObject->getAmount() ) ) || $formModelObject->getAmount() <= 0 ) {
					$error =
                        /* translators: Form field validation error message when custom amount is not a number */
                        __( 'Please enter a valid amount, use only digits and a decimal separator', 'wp-full-stripe' );
					$bindingResult->addFieldError( $fieldName, $fieldId, $error );
				} elseif ( $formModelObject->getAmount() < $formModelObject->getForm()->minimumPaymentAmount ) {
                    $error =
                        /* translators: Form field validation error message when custom amount is less than the minimum payment amount */
                        sprintf( __( 'The minimum payment amount is %s', 'wp-full-stripe' ), MM_WPFS_Currencies::formatByForm( $formModelObject->getForm(), $formModelObject->getForm()->currency, $formModelObject->getForm()->minimumPaymentAmount, false, true ) );
                    $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                }
            }
		}
	}

	/**
	 * @param MM_WPFS_Public_PaymentFormModel $formModelObject
	 *
	 * @return bool
	 */
	private function validateCustomAmount( $formModelObject ) {
		$validateCustomAmount = false;

		if ( MM_WPFS::PAYMENT_TYPE_CUSTOM_AMOUNT == $formModelObject->getForm()->customAmount ) {
			$validateCustomAmount = true;
		} elseif (
			MM_WPFS::PAYMENT_TYPE_LIST_OF_AMOUNTS == $formModelObject->getForm()->customAmount
			&& 1 == $formModelObject->getForm()->allowListOfAmountsCustom
			&& MM_WPFS_Public_PaymentFormModel::INITIAL_CUSTOM_AMOUNT_INDEX == $formModelObject->getCustomAmountIndex()
		) {
			$validateCustomAmount = true;
		}

		return $validateCustomAmount;
	}

}

trait MMP_WPFS_FormValidator_TaxAddOn {
    protected function validateInlineTaxFields($bindingResult, $formModelObject ) {
        if ( $formModelObject->getForm()->vatRateType !== MM_WPFS::FIELD_VALUE_TAX_RATE_NO_TAX) {
            if ( $formModelObject->getBuyingAsBusiness() == 1 ) {
                if ( empty( $formModelObject->getTaxId() ) ) {
                    $fieldName = MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_TAX_ID;
                    $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                    $error =
                        __( 'Please enter your business\'s tax id', 'wp-full-stripe' );
                    $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                }

                if ( $formModelObject->getForm()->showAddress == 0 &&
                    empty( $formModelObject->getBusinessName() ) ) {
                    $fieldName = MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_BUSINESS_NAME;
                    $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                    $error =
                        __( 'Please enter your business\'s name', 'wp-full-stripe' );
                    $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                }
            }

            if ( $formModelObject->getForm()->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX ||
                 $formModelObject->getForm()->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_DYNAMIC ||
                ( $formModelObject->getForm()->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_FIXED && $formModelObject->getForm()->collectCustomerTaxId == 1 )) {
                if ( $formModelObject->getForm()->showAddress == 0 ) {
                    $countries = MM_WPFS_Countries::getAvailableCountries();

                    if ( ! array_key_exists( $formModelObject->getTaxCountry(), $countries ) ) {
                        $fieldName = MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_TAX_COUNTRY;
                        $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                        $error =
                            __( 'Please select the country your business\'s seat is in', 'wp-full-stripe' );
                        $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                    }

                    if ( $formModelObject->getForm()->vatRateType === MM_WPFS::FIELD_VALUE_TAX_RATE_STRIPE_TAX ) {
                        if ( false !== array_search( $formModelObject->getTaxCountry(), MM_WPFS_Pricing::getCountryCodesRequiringPostalCodeForTax() ) &&
                            empty( $formModelObject->getTaxZip() )) {
                            $fieldName = MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_TAX_ZIP;
                            $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                            $error =
                                __( 'Please enter your postal code', 'wp-full-stripe' );
                            $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                        }
                    } else {
                        if ( $formModelObject->getTaxCountry() === MM_WPFS::COUNTRY_CODE_UNITED_STATES ) {
                            $states = MM_WPFS_States::getAvailableStates();

                            if ( ! array_key_exists( $formModelObject->getTaxState(), $states ) ) {
                                $fieldName = MM_WPFS_FormView_InlineTaxAddOnConstants::FIELD_TAX_STATE;
                                $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                                $error =
                                    __( 'Please select the state your business\'s seat is in', 'wp-full-stripe' );
                                $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                            }
                        }
                    }

                }
            }
        }
    }
}

class MM_WPFS_InlinePaymentFormValidator extends MM_WPFS_PaymentFormValidator {
	use MM_WPFS_InlineFormValidator;
    use MMP_WPFS_FormValidator_TaxAddOn;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    protected function validateFields( $bindingResult, $formModelObject ) {
		parent::validateFields( $bindingResult, $formModelObject );
		if ( $formModelObject instanceof MM_WPFS_Public_InlinePaymentFormModel ) {
			$this->validateInlineFields( $bindingResult, $formModelObject );
            $this->validateInlineTaxFields( $bindingResult, $formModelObject );
			$this->validateGoogleReCaptcha( $bindingResult, $formModelObject );
		}
	}

}

class MM_WPFS_PopupPaymentFormValidator extends MM_WPFS_PaymentFormValidator {

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    protected function validateFields( $bindingResult, $formModelObject ) {
		parent::validateFields( $bindingResult, $formModelObject );
		if ( $formModelObject instanceof MM_WPFS_Public_CheckoutPaymentFormModel ) {
			$this->validateGoogleReCaptcha( $bindingResult, $formModelObject );
		}
	}

}

class MM_WPFS_DonationFormValidator extends MM_WPFS_FormValidator {

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    protected function validateFields( $bindingResult, $formModelObject ) {
        parent::validateFields( $bindingResult, $formModelObject );
        if ( $formModelObject instanceof MM_WPFS_Public_DonationFormModel ) {
            if ( $this->validateCustomAmount( $formModelObject ) ) {
                $fieldName = $formModelObject::PARAM_WPFS_CUSTOM_AMOUNT_UNIQUE;
                $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                if ( empty( $formModelObject->getAmount() ) ) {
                    $error =
                        /* translators: Form field validation error message when custom amount is empty */
                        __( 'Please enter an amount', 'wp-full-stripe' );
                    $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                } elseif ( ! is_numeric( trim( $formModelObject->getAmount() ) ) || $formModelObject->getAmount() <= 0 ) {
                    $error =
                        /* translators: Form field validation error message when custom amount is not a number */
                        __( 'Please enter a valid amount, use only digits and a decimal separator', 'wp-full-stripe' );
                    $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                } elseif ( $formModelObject->getAmount() < $formModelObject->getForm()->minimumDonationAmount ) {
                    $minimumAmount = MM_WPFS_Currencies::formatAndEscapeByForm( $formModelObject->getForm(), $formModelObject->getForm()->currency, $formModelObject->getForm()->minimumDonationAmount, false, true );
                    $error = sprintf(
                        /* translators: Form field validation error message when custom amount is lower than the minimum donation amount */
                        __( 'The minimum donation amount is %s.', 'wp-full-stripe' ), $minimumAmount );
                    $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                }
            }

            // Validate donation frequency
            $donationFrequencies = array(
                MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_ONE_TIME,
                MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_DAILY,
                MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_WEEKLY,
                MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_MONTHLY,
                MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_ANNUAL );
            if ( false === array_search( $formModelObject->getDonationFrequency(), $donationFrequencies ) ) {
                $fieldName = $formModelObject::PARAM_WPFS_DONATION_FREQUENCY;
                $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                $error =
                    /* translators: Form field validation error message when no donation frequency is selected */
                    __( 'Please select a donation frequency', 'wp-full-stripe' );

                $bindingResult->addGlobalError( $error );
            }
        }
    }

    /**
     * @param MM_WPFS_Public_DonationFormModel $formModelObject
     *
     * @return bool
     */
    private function validateCustomAmount( $formModelObject ) {
        $validateCustomAmount = false;

        if ( 1 == $formModelObject->getForm()->allowCustomDonationAmount
            && MM_WPFS_Public_DonationFormModel::INITIAL_CUSTOM_AMOUNT_INDEX == $formModelObject->getCustomAmountIndex()
        ) {
            $validateCustomAmount = true;
        }

        return $validateCustomAmount;
    }

}

class MM_WPFS_InlineDonationFormValidator extends MM_WPFS_DonationFormValidator {
    use MM_WPFS_InlineFormValidator;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    protected function validateFields( $bindingResult, $formModelObject ) {
        parent::validateFields( $bindingResult, $formModelObject );
        if ( $formModelObject instanceof MM_WPFS_Public_InlineDonationFormModel ) {
            $this->validateInlineFields( $bindingResult, $formModelObject );
            $this->validateGoogleReCaptcha( $bindingResult, $formModelObject );
        }
    }

}

class MM_WPFS_PopupDonationFormValidator extends MM_WPFS_DonationFormValidator {

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    protected function validateFields( $bindingResult, $formModelObject ) {
        parent::validateFields( $bindingResult, $formModelObject );
        if ( $formModelObject instanceof MM_WPFS_Public_CheckoutDonationFormModel ) {
            $this->validateGoogleReCaptcha( $bindingResult, $formModelObject );
        }
    }

}


class MM_WPFS_SubscriptionFormValidator extends MM_WPFS_FormValidator {

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    protected function validateFields( $bindingResult, $formModelObject ) {
		parent::validateFields( $bindingResult, $formModelObject );
		if ( $formModelObject instanceof MM_WPFS_Public_SubscriptionFormModel ) {

			if ( is_null( $formModelObject->getStripePlan() ) ) {
				$fieldName = $formModelObject::PARAM_WPFS_STRIPE_PLAN;
				$fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
				$error     = __( 'Invalid plan selected, check your plans and Stripe API mode.', 'wp-full-stripe' );
				$bindingResult->addFieldError( $fieldName, $fieldId, $error );
			}

            if ( $formModelObject->getForm()->allowMultipleSubscriptions == 1 ) {
                if ( isset( $formModelObject->getForm()->minimumQuantityOfSubscriptions )
                    && $formModelObject->getForm()->minimumQuantityOfSubscriptions > 0
                    && $formModelObject->getStripePlanQuantity() < $formModelObject->getForm()->minimumQuantityOfSubscriptions
                ) {
                    $fieldName = $formModelObject::PARAM_WPFS_STRIPE_PLAN_QUANTITY;
                    $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                    $error     = sprintf( __( 'Subscription quantity is less than the minimum quantity of %d.', 'wp-full-stripe' ), $formModelObject->getForm()->minimumQuantityOfSubscriptions );
                    $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                }
                if ( isset( $formModelObject->getForm()->maximumQuantityOfSubscriptions )
                    && $formModelObject->getForm()->maximumQuantityOfSubscriptions > 0
                    && $formModelObject->getStripePlanQuantity() > $formModelObject->getForm()->maximumQuantityOfSubscriptions
                ) {
                    $fieldName = $formModelObject::PARAM_WPFS_STRIPE_PLAN_QUANTITY;
                    $fieldId   = MM_WPFS_Utils::generateFormElementId( $fieldName, $formModelObject->getFormHash() );
                    $error     = sprintf( __( 'Subscription quantity is greater than the maximum quantity of %d.', 'wp-full-stripe' ), $formModelObject->getForm()->maximumQuantityOfSubscriptions );
                    $bindingResult->addFieldError( $fieldName, $fieldId, $error );
                }
            }
		}
	}

}

class MM_WPFS_InlineSubscriptionFormValidator extends MM_WPFS_SubscriptionFormValidator {
	use MM_WPFS_InlineFormValidator;
    use MMP_WPFS_FormValidator_TaxAddOn;

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    protected function validateFields( $bindingResult, $formModelObject ) {
		parent::validateFields( $bindingResult, $formModelObject );
		if ( $formModelObject instanceof MM_WPFS_Public_InlineSubscriptionFormModel ) {
			$this->validateInlineFields( $bindingResult, $formModelObject );
            $this->validateInlineTaxFields( $bindingResult, $formModelObject );
			$this->validateGoogleReCaptcha( $bindingResult, $formModelObject );
		}
	}
}

class MM_WPFS_PopupSubscriptionFormValidator extends MM_WPFS_SubscriptionFormValidator {

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService );
    }

    protected function validateFields( $bindingResult, $formModelObject ) {
		if ( $formModelObject instanceof MM_WPFS_Public_CheckoutSubscriptionFormModel ) {
			if ( 1 == $formModelObject->getForm()->simpleButtonLayout ) {
				$this->ignore( MM_WPFS_Public_FormModel::PARAM_WPFS_CUSTOM_INPUT );
			}
		}
		parent::validateFields( $bindingResult, $formModelObject );
		if ( $formModelObject instanceof MM_WPFS_Public_CheckoutSubscriptionFormModel ) {
			$this->validateGoogleReCaptcha( $bindingResult, $formModelObject );
		}
	}

}
