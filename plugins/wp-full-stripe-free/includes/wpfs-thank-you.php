<?php
/** @noinspection PhpIllegalPsrClassPathInspection */

class MM_WPFS_ThankYou {
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    const SHORTCODE_FULLSTRIPE_THANKYOU = 'fullstripe_thankyou';
    const SHORTCODE_FULLSTRIPE_THANKYOU_SUCCESS = 'fullstripe_thankyou_success';
    const SHORTCODE_FULLSTRIPE_THANKYOU_DEFAULT = 'fullstripe_thankyou_default';

    const FILTER_NAME_THANK_YOU_URL_PARAMETERS = 'fullstripe_thank_you_url_parameters';
    const FILTER_NAME_THANK_YOU_POST_TYPES = 'fullstripe_thank_you_post_types';

    const PARAM_NAME_TRANSACTION_DATA = 'transaction_data';

    /** @var MM_WPFS_Database */
    private $database = null;
    /** @var MM_WPFS_TransactionDataService */
    private $transactionDataService = null;
    /** @var MM_WPFS_Options */
    private $options = null;

    public function __construct( $loggerService ) {
        $this->initLogger( $loggerService, MM_WPFS_LoggerService::MODULE_RUNTIME );
        $this->options = new MM_WPFS_Options();

        $this->initStaticContext();

        add_shortcode( self::SHORTCODE_FULLSTRIPE_THANKYOU, array( $this, 'thankYouShortCode') );
        add_shortcode( self::SHORTCODE_FULLSTRIPE_THANKYOU_SUCCESS, array( $this, 'thankYouSuccessShortCode') );
        add_shortcode( self::SHORTCODE_FULLSTRIPE_THANKYOU_DEFAULT, array( $this, 'thankYouDefaultShortCode') );

        $this->database                  = new MM_WPFS_Database();
        $this->transactionDataService    = new MM_WPFS_TransactionDataService();
    }

    function thankYouShortCode($attributes, $content = null ) {
        $transactionDataKey = isset( $_REQUEST[ MM_WPFS_TransactionDataService::REQUEST_PARAM_NAME_WPFS_TRANSACTION_DATA_KEY ] ) ? $_REQUEST[ MM_WPFS_TransactionDataService::REQUEST_PARAM_NAME_WPFS_TRANSACTION_DATA_KEY ] : null;
        $transactionData    = $this->transactionDataService->retrieve( $transactionDataKey );

        if ( $transactionData !== false ) {
            $_REQUEST[self::PARAM_NAME_TRANSACTION_DATA] = $transactionData;
        }

        return do_shortcode( $content );
    }

    function thankYouDefaultShortCode($attributes, $content = null ) {
        if ( isset( $_REQUEST[self::PARAM_NAME_TRANSACTION_DATA] ) ) {
            return '';
        } else {
            return do_shortcode( $content );
        }
    }

    function thankYouSuccessShortCode($attributes, $content = null ) {
        if ( isset( $_REQUEST[self::PARAM_NAME_TRANSACTION_DATA] ) ) {
            $transactionData = $_REQUEST[self::PARAM_NAME_TRANSACTION_DATA];
        } else {
            $transactionData = null;
        }

        if ( ! is_null( $transactionData ) && $transactionData instanceof MM_WPFS_FormTransactionData ) {

            /* @var $procesor MM_WPFS_ThankYouPostProcessor */
            $processor = MM_WPFS_ThankYouPostProcessorFactory::create( $this->database, $transactionData, $this->loggerService );
            return do_shortcode( $processor->process( $content ));

        } else {
            return '';
        }
    }

    /**
     * @param $form
     * @return array
     */
    public static function getPagesAndPosts( $form ) : array {
        $result = array();

        $filterParams = array(
            'formName'  => $form->name,
            'formType'  => MM_WPFS_Utils::getFormType( $form )
        );
        $query = new WP_Query( array(
            'nopaging' => true,
            'update_post_meta_cache' => false,
            'post_type' => apply_filters( self::FILTER_NAME_THANK_YOU_POST_TYPES, array( 'page' ), $filterParams )
        ) );

        foreach ( $query->posts as $pageOrPost ) {
            $item = new \StdClass;
            $item->id    = $pageOrPost->ID;
            $item->title = $pageOrPost->post_title;

            array_push( $result, $item );
        }

        return $result;
    }

    /**
     * @param $redirectUrl
     * @param $transactionDataKey
     * @param $filterParams
     * @return string
     */
    public static function getRedirectUrl( $redirectUrl, $transactionDataKey, $filterParams ) {
           $filteredResult = apply_filters( self::FILTER_NAME_THANK_YOU_URL_PARAMETERS, [], $filterParams );
           if ( !empty( $transactionDataKey ) ) {
               $filteredResult[ MM_WPFS_TransactionDataService::REQUEST_PARAM_NAME_WPFS_TRANSACTION_DATA_KEY ] = $transactionDataKey;
           }

           return add_query_arg( $filteredResult, $redirectUrl );
    }
}

trait MM_WPFS_ThankYou_AddOn {

    /**
     * @param $formModel MM_WPFS_Public_FormModel
     * @param $transactionData MM_WPFS_OneTimePaymentTransactionData|MM_WPFS_SubscriptionTransactionData|MM_WPFS_DonationTransactionData
     * @return MM_WPFS_FormMacroReplacer
     */
    protected function getMacroReplacer( $formModel, $transactionData ) {
        $result = null;

        $formType = MM_WPFS_Utils::getFormType( $formModel->getForm() );
        switch( $formType ) {
            case MM_WPFS::FORM_TYPE_INLINE_PAYMENT:
            case MM_WPFS::FORM_TYPE_CHECKOUT_PAYMENT:
                $result = new MM_WPFS_OneTimePaymentMacroReplacer( $formModel->getForm(), $transactionData, $this->loggerService );
                break;

            case MM_WPFS::FORM_TYPE_INLINE_SUBSCRIPTION:
            case MM_WPFS::FORM_TYPE_CHECKOUT_SUBSCRIPTION:
                $result = new MM_WPFS_SubscriptionMacroReplacer( $formModel->getForm(), $transactionData, $this->loggerService );
                break;

            case MM_WPFS::FORM_TYPE_INLINE_DONATION:
            case MM_WPFS::FORM_TYPE_CHECKOUT_DONATION:
                $result = new MM_WPFS_DonationMacroReplacer( $formModel->getForm(), $transactionData, $this->loggerService );
                break;

            case MM_WPFS::FORM_TYPE_INLINE_SAVE_CARD:
            case MM_WPFS::FORM_TYPE_CHECKOUT_SAVE_CARD:
                $result = new MM_WPFS_SaveCardMacroReplacer( $formModel->getForm(), $transactionData, $this->loggerService );
                break;

            default:
                throw new Exception( __CLASS__ . '.' . __FUNCTION__ . ": Unsupported form type: {$formType}" );
        }

        return $result;
    }

    /**
     * @param MM_WPFS_Public_FormModel $formModel
     * @param $transactionData MM_WPFS_OneTimePaymentTransactionData|MM_WPFS_SubscriptionTransactionData|MM_WPFS_DonationTransactionData
     * @param MM_WPFS_TransactionResult $transactionResult
     */
    protected function handleRedirect( $formModel, $transactionData, $transactionResult ) {
        if ( $transactionResult->isSuccess() ) {
            if ( 1 == $formModel->getForm()->redirectOnSuccess ) {
                $transactionDataKey = null;
                $redirectUrl = null;

                if ( 1 == $formModel->getForm()->redirectToPageOrPost ) {
                    if ( 0 != $formModel->getForm()->redirectPostID ) {
                        // store the transction data as a transient to be able to retrieve it again
                        // on the thank you page
                        $transactionDataKey = $this->transactionDataService->store( $transactionData );
                        $redirectUrl = get_page_link( $formModel->getForm()->redirectPostID );
                    } else {
                        MM_WPFS_Utils::log( "handleRedirect(): Inconsistent form data: formName={$formModel->getFormName()}, doRedirect={$formModel->getForm()->redirectOnSuccess}, redirectPostID={$formModel->getForm()->redirectPostID}" );
                    }
                } else {
                    $redirectUrl = $formModel->getForm()->redirectUrl;
                }

                if ( !empty( $redirectUrl ) ) {
                    // macro replacer handles checkout type forms to get additional data
                   $replacer = $this->getMacroReplacer( $formModel, $transactionData );

                    $params = array(
                        'formName'                => $formModel->getFormName(),
                        'formType'                => MM_WPFS_Utils::getFormType( $formModel->getForm() ),
                        'rawPlaceholders'         => $replacer->getRawKeyValuePairs(),
                        'decoratedPlaceholders'   => $replacer->getDecoratedKeyValuePairs()
                    );

                    $transactionResult->setRedirect( true );
                    $transactionResult->setRedirectURL( MM_WPFS_ThankYou::getRedirectUrl( $redirectUrl, $transactionDataKey, $params ) );
                }
            }
        }
    }
}

class MM_WPFS_ThankYouPostProcessorFactory {
    public static function create( $database, $transactionData, $loggerService) {
        $processor = null;

        if ($transactionData instanceof MM_WPFS_SubscriptionTransactionData) {
            $processor = new MM_WPFS_SubscriptionThankYouPostProcessor( $database, $transactionData, $loggerService);
        } else if ($transactionData instanceof MM_WPFS_DonationTransactionData) {
            $processor = new MM_WPFS_DonationThankYouPostProcessor( $database, $transactionData, $loggerService);
        } else if ($transactionData instanceof MM_WPFS_OneTimePaymentTransactionData) {
            $processor = new MM_WPFS_OneTimePaymentThankYouPostProcessor( $database, $transactionData, $loggerService);
        } else if ($transactionData instanceof MM_WPFS_SaveCardTransactionData) {
            $processor = new MM_WPFS_SaveCardThankYouPostProcessor( $database, $transactionData, $loggerService);
        } else {
            throw new Exception("Unknown thank you postprocessor class: " . get_class($transactionData));
        }

        return $processor;
    }
}

abstract class MM_WPFS_ThankYouPostProcessor {
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    /* @var $database MM_WPFS_Database */
    protected $database;
    /* @var $form array */
    protected $form;
    /* @var $transactionData MM_WPFS_FormTransactionData */
    protected $transactionData;
    /* @var $replacer MM_WPFS_FormMacroReplacer */
    protected $replacer;

    protected $options;

    public function __construct( $database, $transactionData, $loggerService) {
        $this->initLogger( $loggerService, MM_WPFS_LoggerService::MODULE_RUNTIME );
        $this->options = new MM_WPFS_Options();

        $this->initStaticContext();

        $this->database         = $database;
        $this->transactionData = $transactionData;
    }

    /**
     * Returns a form from database identified by a name.
     *
     * @param $formName
     *
     * @return mixed|null
     */
    function getFormByName( $formName ) {
        $form = null;

        if ( is_null( $form ) ) {
            $form = $this->database->getInlinePaymentFormByName( $formName );
        }
        if ( is_null( $form ) ) {
            $form = $this->database->getInlineSubscriptionFormByName( $formName );
        }
        if ( is_null( $form ) ) {
            $form = $this->database->getCheckoutPaymentFormByName( $formName );
        }
        if ( is_null( $form ) ) {
            $form = $this->database->getCheckoutSubscriptionFormByName( $formName );
        }
        if ( is_null( $form ) ) {
            $form = $this->database->getInlineDonationFormByName( $formName );
        }
        if ( is_null( $form ) ) {
            $form = $this->database->getCheckoutDonationFormByName( $formName );
        }

        return $form;
    }

    public function process( $content) {
        $params = array(
            'formType'                => $this->getFormType(),
            'rawPlaceholders'         => $this->replacer->getRawKeyValuePairs(),
            'decoratedPlaceholders'   => $this->replacer->getDecoratedKeyValuePairs()
        );

        $content = apply_filters( 'fullstripe_thank_you_output', $content, $params );
        $result = $this->replacer->replaceMacrosWithHtmlEscape( $content );

        return $result;
    }

    abstract protected function getFormType();
}

class MM_WPFS_SubscriptionThankYouPostProcessor extends MM_WPFS_ThankYouPostProcessor {
    public function __construct( $database, $transactionData, $loggerService ) {
        parent::__construct( $database, $transactionData, $loggerService );

        $this->form = $this->getFormByName( $this->transactionData->getFormName() );
        $this->replacer = new MM_WPFS_SubscriptionMacroReplacer( $this->form, $transactionData, $this->loggerService );
    }

    protected function getFormType() {
        return MM_WPFS::FORM_TYPE_SUBSCRIPTION;
    }
}

class MM_WPFS_DonationThankYouPostProcessor extends MM_WPFS_ThankYouPostProcessor {
    public function __construct( $database, $transactionData, $loggerService ) {
        parent::__construct( $database, $transactionData, $loggerService );

        $this->form = $this->getFormByName( $this->transactionData->getFormName() );
        $this->replacer = new MM_WPFS_DonationMacroReplacer( $this->form, $transactionData, $this->loggerService );
    }

    protected function getFormType() {
        return MM_WPFS::FORM_TYPE_DONATION;
    }
}

class MM_WPFS_OneTimePaymentThankYouPostProcessor extends MM_WPFS_ThankYouPostProcessor {
    public function __construct( $database, $transactionData, $loggerService ) {
        parent::__construct( $database, $transactionData, $loggerService );

        $this->form = $this->getFormByName( $this->transactionData->getFormName() );
        $this->replacer = new MM_WPFS_OneTimePaymentMacroReplacer( $this->form, $transactionData, $this->loggerService );
    }

    protected function getFormType() {
        return MM_WPFS::FORM_TYPE_PAYMENT;
    }
}

class MM_WPFS_SaveCardThankYouPostProcessor extends MM_WPFS_ThankYouPostProcessor {
    public function __construct( $database, $transactionData, $loggerService ) {
        parent::__construct( $database, $transactionData, $loggerService );

        $this->form = $this->getFormByName( $this->transactionData->getFormName() );
        $this->replacer = new MM_WPFS_SaveCardMacroReplacer( $this->form, $transactionData, $loggerService );
    }

    protected function getFormType() {
        return MM_WPFS::FORM_TYPE_SAVE_CARD;
    }
}

