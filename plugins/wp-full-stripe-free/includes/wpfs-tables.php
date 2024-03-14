<?php

abstract class WPFS_Transactions_Table extends WPFS_List_Table {
    use MM_WPFS_Logger_AddOn;
    use MM_WPFS_StaticContext_AddOn;

    const ITEMS_PER_PAGE = 10;

    /** @var MM_WPFS_Database */
    protected $db = null;

    /** @var MM_WPFS_Options */
    protected $options = null;

    /** @var array */
    protected  $formDisplayNameCache;

    public function __construct( $loggerService, $args  ) {
        parent::__construct( $args );

        $this->initLogger( $loggerService,  MM_WPFS_LoggerService::MODULE_ADMIN);
        $this->options = new MM_WPFS_Options();
        $this->db = new MM_WPFS_Database();

        $this->initStaticContext();

        $this->initFormDisplayNameCache();
    }

    abstract protected function getForms() : array;

    protected function initFormDisplayNameCache() {
        $this->formDisplayNameCache = array();

        $forms = $this->getForms();
        foreach ( $forms as $form ) {
            $this->formDisplayNameCache[ $form->name ] = $form->displayName;
        }
    }

    /**
     * @throws Exception
     */
    public function prepare_items() {
        $query = $this->getQuery();
        $numItems = $this->db->runQuery($query);

        $this->configurePagination($numItems);
        $this->addPageOffsetToQuery($query);

        $this->configureColumnHeaders();

        $this->items = $this->db->getResults($query);
    }

    /**
     * @return array
     */
    protected function getOrderParameters() : array {
        $orderBy = ! empty( $_REQUEST[ MM_WPFS_Admin_Menu::PARAM_NAME_ORDER_BY ] ) ? trim( $_REQUEST[ MM_WPFS_Admin_Menu::PARAM_NAME_ORDER_BY ] ) : 'created';
        $order   = ! empty( $_REQUEST[ MM_WPFS_Admin_Menu::PARAM_NAME_ORDER ] ) ? trim( $_REQUEST[ MM_WPFS_Admin_Menu::PARAM_NAME_ORDER ] ) : 'desc';

        return array( $orderBy, $order );
    }

    protected function addOrderByStatementToQuery(& $query ) {
        list( $orderBy, $order ) = $this->getOrderParameters();

        if ( ! empty( $orderBy ) && !empty( $order )) {
            $query .= ' ORDER BY ' . $orderBy . ' ' . $order;
        }
    }

    /**
     * @param $whereStatement
     * @param $condition
     */
    protected function extendWhereStatement( & $whereStatement, $condition ) {
        if ( ! isset( $whereStatement ) ) {
            $whereStatement = ' WHERE ';
        } else {
            $whereStatement .= ' AND ';
        }

        $whereStatement .= $condition;
    }

    /**
     * @param $numItems
     */
    protected function configurePagination( $numItems ) {
        $totalPages = ceil( $numItems / self::ITEMS_PER_PAGE );

        $this->set_pagination_args( array(
            "total_items" => $numItems,
            "total_pages" => $totalPages,
            "per_page"    => self::ITEMS_PER_PAGE,
        ) );
    }

    protected function addPageOffsetToQuery( & $query ) {
        $currentPage = $this->get_pagenum();
        if ( !empty( $currentPage )) {
            $offset = ( $currentPage - 1 ) * self::ITEMS_PER_PAGE;
            $query .= ' LIMIT ' . (int)$offset . ',' . (int)self::ITEMS_PER_PAGE;
        }
    }

    /**
     *
     */
    protected function configureColumnHeaders() {
        $columns               = $this->get_columns();
        $hidden                = array();
        $sortable              = $this->get_sortable_columns();

        $this->_column_headers = array( $columns, $hidden, $sortable );
    }

    protected function createCompositeColumnLabel($column1, $column2 ) {
        return "{$column1} / {$column2}";
    }

    public function display() {
        ?>
        <table class="<?php echo implode( ' ', $this->get_table_classes() ); ?>">
            <thead>
            <?php $this->print_column_headers(); ?>
            </thead>

            <tbody id="the-list">
            <?php $this->display_rows_or_placeholder(); ?>
            </tbody>
        </table>
        <?php
        $this->pagination( 'bottom' );
    }

    /**
     * @param string $which
     */
    protected function pagination( $which ) {
        if ( empty( $this->_pagination_args ) ) {
            return;
        }
        if ( $this->_pagination_args['total_items'] == 0 ) {
            return;
        }

        $removableQueryArgs  = wp_removable_query_args();
        $currentUrl          = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
        $currentUrl          = remove_query_arg( $removableQueryArgs, $currentUrl );

        $totalItems  = $this->_pagination_args['total_items'];
        $totalPages  = $this->_pagination_args['total_pages'];
        $currentPage = $this->get_pagenum();

        $firstItem = ( $currentPage - 1 ) * self::ITEMS_PER_PAGE + 1;
        $lastItem  = $firstItem + self::ITEMS_PER_PAGE - 1 < $totalItems ? $firstItem + self::ITEMS_PER_PAGE - 1 : $totalItems;

        $disableFirst = $disableLast = $disablePrev = $disableNext = false;

        if ( $currentPage == 1 ) {
            $disableFirst = true;
            $disablePrev = true;
        }
        if ( $currentPage == 2 ) {
            $disableFirst = true;
        }
        if ( $currentPage == $totalPages ) {
            $disableLast = true;
            $disableNext = true;
        }
        if ( $currentPage == $totalPages - 1 ) {
            $disableLast = true;
        }

        $showingLabel = sprintf(
                /* translators: Paging label indicating which items are displayed
                 * p1: First item displayed
                 * p2: Last item displayed
                 * p3: Total number of items
                 */
                _n( 'Showing %3$d item',
                    'Showing %1$d-%2$d of %3$d items',
                    $totalItems,
                    'wp-full-stripe-admin'),
                $firstItem,
                $lastItem,
                $totalItems
        );

        $output  = "<div class='wpfs-data-table-foot'>\n";
        $output .= "  <div class='wpfs-data-table-foot__show'>{$showingLabel}</div>\n";
        $output .= "  <div class='wpfs-data-table-foot__pagination'>\n";
        $output .= "    <div class='wpfs-pagination'>\n";
        $output .= "      <ul class='wpfs-pagination__list'>\n";

        $output .= "        <li class='wpfs-pagination__item'>\n";
        if ( $disableFirst ) {
            $output .= "          ←←";
        } else {
            $firstPageUrl   = esc_url( remove_query_arg( 'paged', $currentUrl ) );
            $firstPageTitle =
                /* translators: Label of the button showing the first page of the list */
                __( 'First page', 'wp-full-stripe-admin' );
            $output .= "          <a class='wpfs-pagination__link' href='{$firstPageUrl}'>←←</a>";
        }
        $output .= "        </li>\n";

        $output .= "        <li class='wpfs-pagination__item'>\n";
        if ( $disablePrev ) {
            $output .= "          ←";
        } else {
            $prevPageUrl   = esc_url( add_query_arg( 'paged', max( 1, $currentPage-1 ), $currentUrl ) );
            $prevPageTitle =
                /* translators: Label of the button showing the previous page of the list */
                __( 'Previous page', 'wp-full-stripe-admin' );
            $output .= "          <a class='wpfs-pagination__link' href='{$prevPageUrl}'>←</a>";
        }
        $output .= "        </li>\n";

        $currentPageTitle =
            /* translators: Label of the button showing the current page of the list */
            __( 'Current Page', 'wp-full-stripe-admin' );
        $output .= "        <li class='wpfs-pagination__item wpfs-pagination__item--active'>\n";
        $output .= "          {$currentPage}\n";
        $output .= "        </li>\n";

        $output .= "        <li class='wpfs-pagination__item'>\n";
        if ( $disableNext ) {
            $output .= "          →";
        } else {
            $nextPageUrl   = esc_url( add_query_arg( 'paged', min( $totalPages, $currentPage+1 ), $currentUrl ) );
            $nextPageTitle =
                /* translators: Label of the button showing the next page of the list */
                __( 'Next page', 'wp-full-stripe-admin' );
            $output .= "          <a class='wpfs-pagination__link' href='{$nextPageUrl}'>→</a>";
        }
        $output .= "        </li>\n";

        $output .= "        <li class='wpfs-pagination__item'>\n";
        if ( $disableLast ) {
            $output .= "          →→";
        } else {
            $lastPageUrl   = esc_url( add_query_arg( 'paged', $totalPages, $currentUrl ) );
            $lastPageTitle =
                /* translators: Label of the button showing the last page of the list */
                __( 'Last page', 'wp-full-stripe-admin' );
            $output .= "          <a class='wpfs-pagination__link' href='{$lastPageUrl}'>→→</a>";
        }
        $output .= "        </li>\n";

        $output .= "      </ul>\n";
        $output .= "    </div>\n";
        $output .= "  </div>\n";
        $output .= "</div>\n";

        $this->__set('_pagination', $output);

        echo $output;
    }

    /**
     * @param $name
     *
     * @return string
     */
    protected function lookupFormDisplayName( $name ) {
        return array_key_exists($name, $this->formDisplayNameCache) ? $this->formDisplayNameCache[$name] : $name;
    }

    /**
     * @param $transaction
     *
     * @return string
     */
    protected function renderPaymentMethodCell( $transaction ): string {
        $html = "
        <td class='wpfs-data-table__td wpfs-data-table__td--w40'>
            <div class='wpfs-credit-card wpfs-credit-card--generic'></div>
        </td>";

        return $html;
    }

    /**
     * @return string[]
     */
    protected function get_table_classes() {
        return array('wpfs-data-table');
    }

}

class WPFS_Subscriptions_Table extends WPFS_Transactions_Table {
    const COLUMN_PAYMENT_METHOD = 'paymentMethod';
    const COLUMN_ID_DATE = 'idDate';
    const COLUMN_SUBSCRIBER_FORM = 'subscriberForm';
    const COLUMN_AMOUNT_FREQUENCY = 'amountFrequency';
    const COLUMN_STATUS_MODE = 'statusMode';
    const COLUMN_ACTIONS = 'actions';

    /** @var $stripeDev MM_WPFS_Stripe */
    private $stripeDev = null;
    /** @var $stripeLive MM_WPFS_Stripe */
    private $stripeLive = null;

    /**
     * @param $loggerService MM_WPFS_LoggerService
     */
    public function __construct( $loggerService ) {
        parent::__construct( $loggerService, array(
            'singular' =>
                /* translators: Singular version of the word 'subscription', used on the subscription list page */
                __('Subscription', 'wp-full-stripe-admin'),
            'plural' =>
                /* translators: Plural version of the word 'subscription', used on the subscription list page */
                __('Subscriptions', 'wp-full-stripe-admin'),
            'ajax' => false
        ));
    }

    /**
     * @return array
     */
    protected function getForms() : array {
        return $this->db->getSubscriptionFormNames();
    }

    /**
     * @return array
     */
    protected function getFilterParameters(): array {
        $searchText = !empty($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_SUBSCRIPTIONS_TEXT_FILTER]) ? trim($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_SUBSCRIPTIONS_TEXT_FILTER]) : null;
        $status = !empty($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_SUBSCRIPTIONS_STATUS_FILTER]) ? trim($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_SUBSCRIPTIONS_STATUS_FILTER]) : null;
        $mode = !empty($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_SUBSCRIPTIONS_MODE_FILTER]) ? trim($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_SUBSCRIPTIONS_MODE_FILTER]) : null;

        return array($searchText, $status, $mode);
    }

    protected function getQuery() {
        $dbPrefix = $this->db->getDatabasePrefix();
        $whereStatement = null;

        $query = "
    		SELECT
    		    subscriberID,
    		    stripeCustomerID,
    		    stripeSubscriptionID,
    		    chargeMaximumCount,
    		    chargeCurrentCount,
    		    status,
    		    email,
    		    planID,
    		    quantity,
    		    created,
    		    cancelled,
    		    livemode,
    		    formId,
    		    formName
    		FROM
    		    {$dbPrefix}fullstripe_subscribers
        ";

        list($searchText, $subscriptionStatus, $apiMode) = $this->getFilterParameters();

        if (isset($searchText)) {
            $escapedSearchText = esc_sql($searchText);

            $this->extendWhereStatement(
                $whereStatement,
                sprintf("(LOWER(name) LIKE LOWER('%s') OR LOWER(email) LIKE LOWER('%s') OR LOWER(stripeSubscriptionID) like LOWER('%s') OR stripeCustomerID LIKE LOWER('%s'))", "%{$escapedSearchText}%", "%{$escapedSearchText}%", "%{$escapedSearchText}%", "%{$escapedSearchText}%")
            );
        }

        if (isset($subscriptionStatus) && $subscriptionStatus !== MM_WPFS_Admin_Menu::PARAM_VALUE_SUBSCRIPTION_STATUS_ALL ) {
            $condition = sprintf( "(status = '%s')", esc_sql( $subscriptionStatus ) );

            $this->extendWhereStatement($whereStatement, $condition);
        }

        if (isset($apiMode) && $apiMode !== MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_ALL) {
            $this->extendWhereStatement(
                $whereStatement,
                sprintf('(livemode = %d)', $apiMode === MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_LIVE ? 1 : 0)
            );
        }

        if (isset($whereStatement)) {
            $query .= $whereStatement;
        }

        $this->addOrderByStatementToQuery($query);

        return $query;
    }

    /**
     * @return array
     */
    public function get_columns() {
        return array(
            self::COLUMN_PAYMENT_METHOD => '',
            self::COLUMN_ID_DATE => $this->createCompositeColumnLabel(
                /* translators: Name of the 'ID' column */
                __('ID', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Date' column */
                __('Date', 'wp-full-stripe-admin')
            ),
            self::COLUMN_SUBSCRIBER_FORM => $this->createCompositeColumnLabel(
                /* translators: Name of the 'Subscriber' column */
                __('Subscriber', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Form' column */
                __('Form', 'wp-full-stripe-admin')
            ),
            self::COLUMN_AMOUNT_FREQUENCY => $this->createCompositeColumnLabel(
                /* translators: Name of the 'Amount' column */
                __('Amount', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Frequency' column */
                __('Frequency', 'wp-full-stripe-admin')
            ),
            self::COLUMN_STATUS_MODE => $this->createCompositeColumnLabel(
                /* translators: Name of the 'Status' column */
                __('Status', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Mode' column */
                __('Mode', 'wp-full-stripe-admin')
            ),
            self::COLUMN_ACTIONS => '',
        );
    }

    /**
     * @return array
     */
    protected function get_sortable_columns() {
        return array();
    }

    /**
     *
     */
    public function no_items() {
        _e('No subscriptions found.', 'wp-full-stripe-admin');
    }

    /**
     * @param bool $withId
     */
    public function print_column_headers( $withId = true ) {
        list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

        $columnHeader = '<tr class="wpfs-data-table__tr">';
        foreach ( $columns as $key => $displayName ) {
            $cssClasses = array( 'wpfs-data-table__th' );
            switch ( $key ) {
                case self::COLUMN_PAYMENT_METHOD:
                    array_push( $cssClasses, 'wpfs-data-table__th--w40' );
                    break;

                case self::COLUMN_AMOUNT_FREQUENCY:
                    array_push( $cssClasses, 'wpfs-data-table__th--right' );
            }

            $classAttributeValue = implode( " ", $cssClasses );
            $columnHeader .= "<th class='{$classAttributeValue}'>{$displayName}</th>";
        }
        $columnHeader .= '</tr>';

        echo $columnHeader;
    }

    /**
     * @param $subscription
     *
     * @return string
     */
    protected function renderIdDateCell($subscription ) : string {
        $dateLabel = MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat( strtotime( $subscription->created ) );

        $html = "
         <td class='wpfs-data-table__td'>
            <a class='wpfs-btn wpfs-btn-link js-open-subscription-details'>{$subscription->stripeSubscriptionID}</a>
            <br>
            <div class='wpfs-typo-body wpfs-typo-body--sm'>{$dateLabel}</div>
        </td>";

        return $html;
    }


    /**
     * @param $subscription
     * @return string
     */
    protected function renderSubscriberFormCell( $subscription ) {
        $formDisplayName = $this->lookupFormDisplayName( $subscription->formName );

        $html = "
        <td class='wpfs-data-table__td'>
            <div class='wpfs-typo-body wpfs-typo-body--gunmetal'>{$subscription->email}</div>
            <div class='wpfs-typo-body wpfs-typo-body--sm'>{$formDisplayName}</div>
        </td>";

        return $html;
    }

    /**
     * @param $subscription
     * @param $stripePlan
     *
     * @return string
     * @throws Exception
     */
    protected function renderAmountFrequencyCell( $subscription, $stripePlan ) : string {
        $frequencyLabel = '';
        $amountLabel    = MM_WPFS_Admin::getSubscriptionAmountLabel( $this->staticContext, $subscription, $stripePlan );

        if ( !is_null( $stripePlan ) ) {
            $frequencyLabel = MM_WPFS_Admin::getSubscriptionIntervalLabel( $stripePlan->recurring->interval, $stripePlan->recurring->interval_count );
        }

        $html = "
            <td class='wpfs-data-table__td wpfs-data-table__td--right'>
              <strong>{$amountLabel}</strong>
              <div class='wpfs-typo-body wpfs-typo-body--sm'>{$frequencyLabel}</div>
            </td>";

        return $html;
    }

    protected function determineSubscriptionStatusCss( $subscriptionStatus ) {
        $cssStyle = '';

        switch( $subscriptionStatus) {
            case MM_WPFS::SUBSCRIBER_STATUS_RUNNING:
                $cssStyle = 'wpfs-tag--filled-green';
                break;

            case MM_WPFS::SUBSCRIBER_STATUS_CANCELLED:
            case MM_WPFS::SUBSCRIBER_STATUS_ENDED:
            $cssStyle = 'wpfs-tag--filled-blue';
                break;

            case MM_WPFS::SUBSCRIBER_STATUS_INCOMPLETE:
            default:
                $cssStyle = 'wpfs-tag--filled-grey';
                break;
        }

        return $cssStyle;
    }

    /**
     * @param $subscription
     * @return string
     */
    protected function renderStatusModeCell( $subscription ) : string {
        $statusCssStyle = $this->determineSubscriptionStatusCss( $subscription->status );
        $modeLabel      = MM_WPFS_Admin::getApiModeLabelFromInteger( $subscription->livemode );
        $statusLabel    = MM_WPFS_Admin::getSubscriberStatusLabelByForm( $subscription );

        $html = "
        <td class='wpfs-data-table__td'>
            <div class='wpfs-tags'>
                <span class='wpfs-tag {$statusCssStyle}'>{$statusLabel}</span>
                <span class='wpfs-tag wpfs-tag--outline'>{$modeLabel}</span>
            </div>
        </td>
        ";

        return $html;
    }

    /**
     * @param $subscription
     *
     * @return string
     */
    protected function renderActionsCell($subscription ) : string {
        $html = "<td class='wpfs-data-table__td wpfs-data-table__td--right wpfs-data-table__td--actions'>";

        if ( MM_WPFS::SUBSCRIBER_STATUS_RUNNING == $subscription->status ) {
            $cancelLabel =
                /* translators: 'Cancel' action label for subscriptions */
                __( 'Cancel', 'wp-full-stripe-admin' );

            $html .= "<a class='wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-cancel-subscription' data-tooltip-content='cancel-subscription-tooltip'>
                <span class='wpfs-icon-cancel'></span>
            </a>
            <div class='wpfs-tooltip-content' data-tooltip-id='cancel-subscription-tooltip'>
                <div class='wpfs-info-tooltip'>{$cancelLabel}</div>
            </div>";
        }

        $deleteLabel =
            /* translators: 'Delete' action label for subscriptions */
            __( 'Delete subscription', 'wp-full-stripe-admin' );
        $html .= "<a class='wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-delete-subscription' data-tooltip-content='delete-subscription-tooltip'>
                <span class='wpfs-icon-trash'></span>
            </a>
            <div class='wpfs-tooltip-content' data-tooltip-id='delete-subscription-tooltip'>
                <div class='wpfs-info-tooltip'>{$deleteLabel}</div>
            </div>";

        $html .= '</td>';

        return $html;
    }

    /**
     * @param $planId string
     * @param $liveMode bool
     *
     * @return \StripeWPFS\Price|null
     * @throws Exception
     */
    protected function retrievePlanByMode( $planId, $liveMode ) {
        $plan = null;

        if ( $liveMode ) {
            if ( is_null( $this->stripeLive ) ) {
                $this->stripeLive = new MM_WPFS_Stripe( MM_WPFS_Stripe::getStripeAuthenticationTokenByMode($this->staticContext, $liveMode), $this->loggerService );
            }
            $plan = $this->stripeLive->retrievePlan( $planId );
        } else {
            if ( is_null( $this->stripeDev ) ) {
                $this->stripeDev = new MM_WPFS_Stripe( MM_WPFS_Stripe::getStripeAuthenticationTokenByMode($this->staticContext, $liveMode), $this->loggerService );
            }
            $plan = $this->stripeDev->retrievePlan( $planId );
        }

        return $plan;
    }

    /**
     * @throws Exception
     */
    public function display_rows() {
        $subscriptions              = $this->items;
        list( $columns, $hidden )   = $this->get_column_info();

        if ( ! empty( $subscriptions ) ) {
            foreach ( $subscriptions as $subscription ) {
                $stripePlan = $this->retrievePlanByMode( $subscription->planID, $subscription->livemode );

                $rowHtml  = "<tr class='wpfs-data-table__tr' data-db-id='{$subscription->subscriberID}' data-stripe-id='{$subscription->stripeSubscriptionID}'>";

                foreach ( $columns as $key => $displayName ) {
                    switch ( $key ) {
                        case self::COLUMN_PAYMENT_METHOD:
                            $rowHtml .= $this->renderPaymentMethodCell( $subscription );
                            break;

                        case self::COLUMN_ID_DATE:
                            $rowHtml .= $this->renderIdDateCell( $subscription );
                            break;

                        case self::COLUMN_SUBSCRIBER_FORM:
                            $rowHtml .= $this->renderSubscriberFormCell( $subscription );
                            break;

                        case self::COLUMN_AMOUNT_FREQUENCY:
                            $rowHtml .= $this->renderAmountFrequencyCell( $subscription, $stripePlan );
                            break;

                        case self::COLUMN_STATUS_MODE:
                            $rowHtml .= $this->renderStatusModeCell( $subscription );
                            break;

                        case self::COLUMN_ACTIONS:
                            $rowHtml .= $this->renderActionsCell( $subscription );
                            break;
                    }
                }

                $rowHtml .= "</tr>";
                echo $rowHtml;
            }
        }
    }
}

class WPFS_Donations_Table extends WPFS_Transactions_Table {
    const COLUMN_PAYMENT_METHOD = 'paymentMethod';
    const COLUMN_ID_DATE = 'idDate';
    const COLUMN_DONOR_FORM = 'donorForm';
    const COLUMN_AMOUNT_FREQUENCY = 'amountFrequency';
    const COLUMN_STATUS_MODE = 'statusMode';
    const COLUMN_ACTIONS = 'actions';

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService, array(
            'singular' =>
                /* translators: Singular version of the word 'Donation', used on the donation list page */
                __('Donation', 'wp-full-stripe-admin'),
            'plural' =>
                /* translators: Plural version of the word 'Donation', used on the donation list page */
                __('Donations', 'wp-full-stripe-admin'),
            'ajax' => false
        ));
    }

    /**
     * @return array
     */
    protected function getForms() : array {
        return $this->db->getDonationFormNames();
    }

    /**
     * @return array
     */
    protected function getFilterParameters(): array {
        $searchText = !empty($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_DONATIONS_TEXT_FILTER]) ? trim($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_DONATIONS_TEXT_FILTER]) : null;
        $mode = !empty($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_DONATIONS_MODE_FILTER]) ? trim($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_DONATIONS_MODE_FILTER]) : null;

        return array( $searchText, $mode );
    }

    protected function getQuery() {
        $dbPrefix = $this->db->getDatabasePrefix();
        $whereStatement = null;

        $query = "
            SELECT
                donationID,
                stripeCustomerID,
                stripeSubscriptionID,
                stripePaymentIntentID,
                paid,
                captured,
                refunded,
                expired,
                lastChargeStatus,
                subscriptionStatus,
                currency,
                amount,
                donationFrequency,
                email,
                created,
                cancelled,
                livemode,
                formId,
                formName
            FROM
               {$dbPrefix}fullstripe_donations
        ";

        list($searchText, $apiMode) = $this->getFilterParameters();

        if (isset($searchText)) {
            $escapedSearchText = esc_sql($searchText);

            $this->extendWhereStatement(
                $whereStatement,
                sprintf("(LOWER(name) LIKE LOWER('%s') OR LOWER(email) LIKE LOWER('%s') OR LOWER(stripeSubscriptionID) like LOWER('%s') OR LOWER(stripePaymentIntentID) like LOWER('%s') OR stripeCustomerID LIKE LOWER('%s'))", "%{$escapedSearchText}%", "%{$escapedSearchText}%", "%{$escapedSearchText}%", "%{$escapedSearchText}%", "%{$escapedSearchText}%")
            );
        }

        if (isset($apiMode) && $apiMode !== MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_ALL) {
            $this->extendWhereStatement(
                $whereStatement,
                sprintf('(livemode = %d)', $apiMode === MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_LIVE ? 1 : 0)
            );
        }

        if (isset($whereStatement)) {
            $query .= $whereStatement;
        }

        $this->addOrderByStatementToQuery($query);

        return $query;
    }

    /**
     * @return array
     */
    public function get_columns() {
        return array(
            self::COLUMN_PAYMENT_METHOD => '',
            self::COLUMN_ID_DATE => $this->createCompositeColumnLabel(
                /* translators: Name of the 'ID' column */
                __('ID', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Date' column */
                __('Date', 'wp-full-stripe-admin')
            ),
            self::COLUMN_DONOR_FORM => $this->createCompositeColumnLabel(
                /* translators: Name of the 'Donor' column */
                __('Donor', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Form' column */
                __('Form', 'wp-full-stripe-admin')
            ),
            self::COLUMN_AMOUNT_FREQUENCY => $this->createCompositeColumnLabel(
                /* translators: Name of the 'Amount' column */
                __('Amount', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Frequency' column */
                __('Frequency', 'wp-full-stripe-admin')
            ),
            self::COLUMN_STATUS_MODE => $this->createCompositeColumnLabel(
                /* translators: Name of the 'Status' column */
                __('Status', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Mode' column */
                __('Mode', 'wp-full-stripe-admin')
            ),
            self::COLUMN_ACTIONS => '',
        );
    }

    /**
     * @return array
     */
    protected function get_sortable_columns() {
        return array();
    }

    /**
     *
     */
    public function no_items() {
        _e('No donation found.', 'wp-full-stripe-admin');
    }

    /**
     * @param bool $withId
     */
    public function print_column_headers( $withId = true ) {
        list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

        $columnHeader = '<tr class="wpfs-data-table__tr">';
        foreach ( $columns as $key => $displayName ) {
            $cssClasses = array( 'wpfs-data-table__th' );
            switch ( $key ) {
                case self::COLUMN_PAYMENT_METHOD:
                    array_push( $cssClasses, 'wpfs-data-table__th--w40' );
                    break;

                case self::COLUMN_AMOUNT_FREQUENCY:
                    array_push( $cssClasses, 'wpfs-data-table__th--right' );
            }

            $classAttributeValue = implode( " ", $cssClasses );
            $columnHeader .= "<th class='{$classAttributeValue}'>{$displayName}</th>";
        }
        $columnHeader .= '</tr>';

        echo $columnHeader;
    }

    /**
     * @param $donation
     *
     * @return string
     */
    protected function renderIdDateCell( $donation ) : string {
        $dateLabel = MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat( strtotime( $donation->created ) );

        $html = "
         <td class='wpfs-data-table__td'>
            <a class='wpfs-btn wpfs-btn-link js-open-donation-details'>{$donation->stripePaymentIntentID}</a>
            <br>
            <div class='wpfs-typo-body wpfs-typo-body--sm'>{$dateLabel}</div>
        </td>";

        return $html;
    }


    /**
     * @param $donation
     *
     * @return string
     */
    protected function renderDonorFormCell( $donation ) {
        $formDisplayName = $this->lookupFormDisplayName( $donation->formName );

        $html = "
        <td class='wpfs-data-table__td'>
            <div class='wpfs-typo-body wpfs-typo-body--gunmetal'>{$donation->email}</div>
            <div class='wpfs-typo-body wpfs-typo-body--sm'>{$formDisplayName}</div>
        </td>";

        return $html;
    }

    /**
     * @param $donationFrequency
     *
     * @return string
     */
    protected function generateDonationFrequencyLabel( $donationFrequency ) {
        $label = "Unknown";
        switch ( $donationFrequency ) {
            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_ONE_TIME:
                $label =
                    /* translators: Donation frequency 'one-time' displayed in the donation list */
                    __( "one-time", 'wp-full-stripe-admin' );
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_DAILY:
                $label =
                    /* translators: Donation frequency 'daily' displayed in the donation list */
                    __( "daily", 'wp-full-stripe-admin' );
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_WEEKLY:
                $label =
                    /* translators: Donation frequency 'weekly' displayed in the donation list */
                    __( "weekly", 'wp-full-stripe-admin' );
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_MONTHLY:
                $label =
                    /* translators: Donation frequency 'monthly' displayed in the donation list */
                    __( "monthly", 'wp-full-stripe-admin' );
                break;

            case MM_WPFS_DonationFormViewConstants::FIELD_VALUE_DONATION_FREQUENCY_ANNUAL:
                $label =
                    /* translators: Donation frequency 'annual' displayed in the donation list */
                    __( "annual", 'wp-full-stripe-admin' );
                break;
        }

        return $label;
    }

    /**
     * @param $donation
     *
     * @return string
     * @throws Exception
     */
    protected function renderAmountFrequencyCell( $donation ) : string {
        $amountLabel    = MM_WPFS_Currencies::formatAndEscape( $this->staticContext, $donation->currency, $donation->amount );
        $frequencyLabel = $this->generateDonationFrequencyLabel( $donation->donationFrequency );

        $html = "
            <td class='wpfs-data-table__td wpfs-data-table__td--right'>
              <strong>{$amountLabel}</strong>
              <div class='wpfs-typo-body wpfs-typo-body--sm'>{$frequencyLabel}</div>
            </td>";

        return $html;
    }


    /**
     * @param $donationStatus
     *
     * @return string
     */
    protected function determineDonationStatusCss( $donationStatus ) : string {
        $cssStyle = '';

        switch( $donationStatus ) {
            case MM_WPFS::DONATION_STATUS_PAID:
            case MM_WPFS::DONATION_STATUS_RUNNING:
                $cssStyle = 'wpfs-tag--filled-green';
                break;

            case MM_WPFS::DONATION_STATUS_REFUNDED:
            case MM_WPFS::DONATION_STATUS_UNKNOWN:
            default:
                $cssStyle = 'wpfs-tag--filled-grey';
                break;
        }

        return $cssStyle;
    }

    /**
     * @param $donation
     * @return string
     */
    protected function renderStatusModeCell( $donation ) : string {
        $status         = MM_WPFS_Utils::getDonationStatus( $donation );

        $statusLabel    = MM_WPFS_Admin::getDonationStatusLabel( $status );
        $statusCssStyle = $this->determineDonationStatusCss( $status );
        $modeLabel      = MM_WPFS_Admin::getApiModeLabelFromInteger( $donation->livemode );

        $html = "
        <td class='wpfs-data-table__td'>
            <div class='wpfs-tags'>
                <span class='wpfs-tag {$statusCssStyle}'>{$statusLabel}</span>
                <span class='wpfs-tag wpfs-tag--outline'>{$modeLabel}</span>
            </div>
        </td>
        ";

        return $html;
    }

    /**
     * @param $donation
     *
     * @return string
     */
    protected function renderActionsCell( $donation ) : string {
        $status = MM_WPFS_Utils::getDonationPaymentStatus( $donation );

        $html = "<td class='wpfs-data-table__td wpfs-data-table__td--right wpfs-data-table__td--actions'>";

        if ( MM_WPFS::PAYMENT_STATUS_PAID === $status ) {
            $refundLabel =
                /* translators: 'Refund' action label for donations */
                __( 'Refund', 'wp-full-stripe-admin' );

            $html .= "<a class='wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-refund-donation' data-tooltip-content='refund-donation-tooltip'>
                <span class='wpfs-icon-sync'></span>
            </a>
            <div class='wpfs-tooltip-content' data-tooltip-id='refund-donation-tooltip'>
                <div class='wpfs-info-tooltip'>{$refundLabel}</div>
            </div>";

        }

        if ( \StripeWPFS\Subscription::STATUS_ACTIVE === $donation->subscriptionStatus ) {
            $cancelLabel =
                /* translators: 'Cancel' action label for donations */
                __( 'Cancel', 'wp-full-stripe-admin' );

            $html .= "<a class='wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-cancel-donation' data-tooltip-content='cancel-donation-tooltip'>
                <span class='wpfs-icon-cancel'></span>
            </a>
            <div class='wpfs-tooltip-content' data-tooltip-id='cancel-donation-tooltip'>
                <div class='wpfs-info-tooltip'>{$cancelLabel}</div>
            </div>";
        }

        $deleteLabel =
            /* translators: 'Delete' action label for donations */
            __( 'Delete donation', 'wp-full-stripe-admin' );
        $html .= "<a class='wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-delete-donation' data-tooltip-content='delete-donation-tooltip'>
                <span class='wpfs-icon-trash'></span>
            </a>
            <div class='wpfs-tooltip-content' data-tooltip-id='delete-donation-tooltip'>
                <div class='wpfs-info-tooltip'>{$deleteLabel}</div>
            </div>";

        $html .= '</td>';

        return $html;
    }

    /**
     * @throws Exception
     */
    public function display_rows() {
        $donations              = $this->items;
        list( $columns, $hidden )   = $this->get_column_info();

        if ( ! empty( $donations ) ) {
            foreach ( $donations as $donation ) {
                $rowHtml  = "<tr class='wpfs-data-table__tr' data-db-id='{$donation->donationID}' data-stripe-id='{$donation->stripePaymentIntentID}'>";

                foreach ( $columns as $key => $displayName ) {
                    switch ( $key ) {
                        case self::COLUMN_PAYMENT_METHOD:
                            $rowHtml .= $this->renderPaymentMethodCell( $donation );
                            break;

                        case self::COLUMN_ID_DATE:
                            $rowHtml .= $this->renderIdDateCell( $donation );
                            break;

                        case self::COLUMN_DONOR_FORM:
                            $rowHtml .= $this->renderDonorFormCell( $donation );
                            break;

                        case self::COLUMN_AMOUNT_FREQUENCY:
                            $rowHtml .= $this->renderAmountFrequencyCell( $donation );
                            break;

                        case self::COLUMN_STATUS_MODE:
                            $rowHtml .= $this->renderStatusModeCell( $donation );
                            break;

                        case self::COLUMN_ACTIONS:
                            $rowHtml .= $this->renderActionsCell( $donation );
                            break;
                    }
                }

                $rowHtml .= "</tr>";
                echo $rowHtml;
            }
        }
    }
}

class WPFS_SavedCards_Table extends WPFS_Transactions_Table {
    const COLUMN_PAYMENT_METHOD = 'paymentMethod';
    const COLUMN_ID_DATE = 'idDate';
    const COLUMN_CUSTOMER_FORM = 'customerForm';
    const COLUMN_MODE = 'mode';
    const COLUMN_ACTIONS = 'actions';

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService, array(
            'singular' =>
                /* translators: Singular version of the expression 'Saved card', used on the save card list page */
                __('Saved card', 'wp-full-stripe-admin'),
            'plural' =>
               /* translators: Plural version of the expression 'Saved card', used on the save card list page */
                __('Saved cards', 'wp-full-stripe-admin'),
            'ajax' => false
        ));
    }

    /**
     * @return array
     */
    protected function getForms() : array {
        return $this->db->getSaveCardFormNames();
    }

    /**
     * @return array
     */
    protected function getFilterParameters(): array {
        $searchText = !empty($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_SAVED_CARDS_TEXT_FILTER]) ? trim($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_SAVED_CARDS_TEXT_FILTER]) : null;
        $mode = !empty($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_SAVED_CARDS_MODE_FILTER]) ? trim($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_SAVED_CARDS_MODE_FILTER]) : null;

        return array( $searchText, $mode );
    }

    /**
     * @return string
     */
    protected function getQuery() {
        $dbPrefix = $this->db->getDatabasePrefix();
        $whereStatement = null;

        $query = "
            SELECT
                captureID,
                stripeCustomerID,
                email,
                created,
                livemode,
                formId,
                formName
            FROM
               {$dbPrefix}fullstripe_card_captures
        ";

        list($searchText, $apiMode) = $this->getFilterParameters();

        if (isset($searchText)) {
            $escapedSearchText = esc_sql($searchText);

            $this->extendWhereStatement(
                $whereStatement,
                sprintf("(LOWER(name) LIKE LOWER('%s') OR LOWER(email) LIKE LOWER('%s') OR stripeCustomerID LIKE LOWER('%s'))", "%{$escapedSearchText}%", "%{$escapedSearchText}%", "%{$escapedSearchText}%" )
            );
        }

        if (isset($apiMode) && $apiMode !== MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_ALL) {
            $this->extendWhereStatement(
                $whereStatement,
                sprintf('(livemode = %d)', $apiMode === MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_LIVE ? 1 : 0)
            );
        }

        if (isset($whereStatement)) {
            $query .= $whereStatement;
        }

        $this->addOrderByStatementToQuery($query);

        return $query;
    }

    /**
     * @return array
     */
    public function get_columns() {
        return array(
            self::COLUMN_PAYMENT_METHOD => '',
            self::COLUMN_ID_DATE => $this->createCompositeColumnLabel(
                /* translators: Name of the 'ID' column */
                __('ID', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Date' column */
                __('Date', 'wp-full-stripe-admin')
            ),
            self::COLUMN_CUSTOMER_FORM => $this->createCompositeColumnLabel(
                /* translators: Name of the 'Customer' column */
                __('Customer', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Form' column */
                __('Form', 'wp-full-stripe-admin')
            ),
            self::COLUMN_MODE =>
                /* translators: Name of the 'Mode' column */
                __('Mode', 'wp-full-stripe-admin'),
            self::COLUMN_ACTIONS => ''
        );
    }

    /**
     * @return array
     */
    protected function get_sortable_columns() {
        return array();
    }

    /**
     *
     */
    public function no_items() {
        _e( 'No saved card found.', 'wp-full-stripe-admin');
    }

    /**
     * @param bool $withId
     */
    public function print_column_headers( $withId = true ) {
        list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

        $columnHeader = '<tr class="wpfs-data-table__tr">';
        foreach ( $columns as $key => $displayName ) {
            $cssClasses = array( 'wpfs-data-table__th' );
            switch ( $key ) {
                case self::COLUMN_PAYMENT_METHOD:
                    array_push( $cssClasses, 'wpfs-data-table__th--w40' );
                    break;
            }

            $classAttributeValue = implode( " ", $cssClasses );
            $columnHeader .= "<th class='{$classAttributeValue}'>{$displayName}</th>";
        }
        $columnHeader .= '</tr>';

        echo $columnHeader;
    }

    /**
     * @param $savedCard
     *
     * @return string
     */
    protected function renderIdDateCell($savedCard ) : string {
        $dateLabel = MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat( strtotime( $savedCard->created ) );

        $html = "
         <td class='wpfs-data-table__td'>
            <a class='wpfs-btn wpfs-btn-link js-open-saved-card-details'>{$savedCard->stripeCustomerID}</a>
            <br>
            <div class='wpfs-typo-body wpfs-typo-body--sm'>{$dateLabel}</div>
        </td>";

        return $html;
    }

    /**
     * @param $savedCard
     * @return string
     */
    protected function renderCustomerFormCell( $savedCard ) {
        $formDisplayName = $this->lookupFormDisplayName( $savedCard->formName );

        $html = "
        <td class='wpfs-data-table__td'>
            <div class='wpfs-typo-body wpfs-typo-body--gunmetal'>{$savedCard->email}</div>
            <div class='wpfs-typo-body wpfs-typo-body--sm'>{$formDisplayName}</div>
        </td>";

        return $html;
    }

    /**
     * @param $savedCard
     * @return string
     */
    protected function renderModeCell( $savedCard ) : string {
        $modeLabel = MM_WPFS_Admin::getApiModeLabelFromInteger( $savedCard->livemode );

        $html = "
        <td class='wpfs-data-table__td'>
            <div class='wpfs-tags'>
                <span class='wpfs-tag wpfs-tag--outline'>{$modeLabel}</span>
            </div>
        </td>
        ";

        return $html;
    }

    protected function renderActionsCell( $payment ) {
        $html = "<td class='wpfs-data-table__td wpfs-data-table__td--right wpfs-data-table__td--actions'>";

        $deleteLabel =
            /* translators: 'Delete' action label for saved cards */
            __( 'Delete saved card', 'wp-full-stripe-admin' );
        $html .= "<a class='wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-delete-saved-card' data-tooltip-content='delete-saved-card-tooltip'>
                <span class='wpfs-icon-trash'></span>
            </a>
            <div class='wpfs-tooltip-content' data-tooltip-id='delete-saved-card-tooltip'>
                <div class='wpfs-info-tooltip'>{$deleteLabel}</div>
            </div>";

        $html .= '</td>';

        return $html;
    }

    public function display_rows() {
        $savedCards                 = $this->items;
        list( $columns, $hidden )   = $this->get_column_info();

        if ( ! empty( $savedCards ) ) {
            foreach ( $savedCards as $savedCard ) {
                $rowHtml  = "<tr class='wpfs-data-table__tr' data-db-id='{$savedCard->captureID}' data-stripe-id='{$savedCard->stripeCustomerID}'>";

                foreach ( $columns as $key => $displayName ) {
                    switch ( $key ) {
                        case self::COLUMN_PAYMENT_METHOD:
                            $rowHtml .= $this->renderPaymentMethodCell( $savedCard );
                            break;

                        case self::COLUMN_ID_DATE:
                            $rowHtml .= $this->renderIdDateCell( $savedCard );
                            break;

                        case self::COLUMN_CUSTOMER_FORM:
                            $rowHtml .= $this->renderCustomerFormCell( $savedCard );
                            break;

                        case self::COLUMN_MODE:
                            $rowHtml .= $this->renderModeCell( $savedCard );
                            break;

                        case self::COLUMN_ACTIONS:
                            $rowHtml .= $this->renderActionsCell( $savedCard );
                            break;
                    }
                }

                $rowHtml .= "</tr>";
                echo $rowHtml;
            }
        }
    }

}

class WPFS_OneTimePayments_Table extends WPFS_Transactions_Table {
    const COLUMN_PAYMENT_METHOD = 'paymentMethod';
    const COLUMN_ID_DATE = 'idDate';
    const COLUMN_CUSTOMER_FORM = 'customerForm';
    const COLUMN_AMOUNT = 'amount';
    const COLUMN_STATUS_MODE = 'statusMode';
    const COLUMN_ACTIONS = 'actions';

    public function __construct( $loggerService ) {
        parent::__construct( $loggerService,  array(
            'singular' =>
                /* translators: Singular version of the word 'Payment', used on the payment list page */
                __('Payment', 'wp-full-stripe-admin'),
            'plural' =>
                /* translators: Plural version of the word 'Payment', used on the payment list page */
                __('Payments', 'wp-full-stripe-admin'),
            'ajax' => false
        ));
    }

    /**
     * @return array
     */
    protected function getForms() : array {
        return $this->db->getOneTimePaymentFormNames();
    }

    /**
     * @return array
     */
    protected function getFilterParameters(): array {
        $searchText = !empty($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_PAYMENTS_TEXT_FILTER]) ? trim($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_PAYMENTS_TEXT_FILTER]) : null;
        $status = !empty($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_PAYMENTS_STATUS_FILTER]) ? trim($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_PAYMENTS_STATUS_FILTER]) : null;
        $mode = !empty($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_PAYMENTS_MODE_FILTER]) ? trim($_REQUEST[MM_WPFS_Admin_Menu::PARAM_NAME_PAYMENTS_MODE_FILTER]) : null;

        return array($searchText, $status, $mode);
    }

    protected function getQuery() {
        $dbPrefix = $this->db->getDatabasePrefix();
        $whereStatement = null;

        $query = "
            SELECT
                paymentID,
                eventID,
                stripeCustomerID,
                payment_method,
                paid,
                captured,
                refunded,
                expired,
                failure_code,
                failure_message,
                livemode,
                last_charge_status,
                currency,
                amount,
                name,
                email,
                formId,
                formType,
                formName,
                created
            FROM
                {$dbPrefix}fullstripe_payments
        ";

        list($searchText, $paymentStatus, $apiMode) = $this->getFilterParameters();

        if (isset($searchText)) {
            $escapedSearchText = esc_sql($searchText);

            $this->extendWhereStatement(
                $whereStatement,
                sprintf("(LOWER(name) LIKE LOWER('%s') OR LOWER(email) LIKE LOWER('%s') OR LOWER(eventID) like LOWER('%s') OR stripeCustomerID LIKE LOWER('%s'))", "%{$escapedSearchText}%", "%{$escapedSearchText}%", "%{$escapedSearchText}%", "%{$escapedSearchText}%")
            );
        }

        if (isset($paymentStatus) && $paymentStatus !== MM_WPFS_Admin_Menu::PARAM_VALUE_PAYMENT_STATUS_ALL ) {
            $condition = '';

            if (MM_WPFS::PAYMENT_STATUS_FAILED === $paymentStatus) {
                $condition .= sprintf("last_charge_status='%s'", MM_WPFS::STRIPE_CHARGE_STATUS_FAILED);
            } elseif (MM_WPFS::PAYMENT_STATUS_PENDING === $paymentStatus) {
                $condition .= sprintf("last_charge_status='%s'", MM_WPFS::STRIPE_CHARGE_STATUS_PENDING);
            } elseif (MM_WPFS::PAYMENT_STATUS_EXPIRED === $paymentStatus) {
                $condition .= sprintf("expired=%d", 1);
            } elseif (MM_WPFS::PAYMENT_STATUS_REFUNDED === $paymentStatus) {
                $condition .= sprintf("refunded=%d", 1);
            } elseif (MM_WPFS::PAYMENT_STATUS_RELEASED === $paymentStatus) {
                $condition .= sprintf("(refunded=%d AND captured=%d)", 1, 0);
            } elseif (MM_WPFS::PAYMENT_STATUS_PAID === $paymentStatus) {
                $condition .= sprintf("(last_charge_status='%s' AND paid=%d AND captured=%d AND expired=%d AND refunded=%d)", MM_WPFS::STRIPE_CHARGE_STATUS_SUCCEEDED, 1, 1, 0, 0);
            } elseif (MM_WPFS::PAYMENT_STATUS_AUTHORIZED === $paymentStatus) {
                $condition .= sprintf("(last_charge_status='%s' AND paid=%d AND captured=%d AND expired=%d AND refunded=%d)", MM_WPFS::STRIPE_CHARGE_STATUS_SUCCEEDED, 1, 0, 0, 0);
            }

            $this->extendWhereStatement($whereStatement, $condition);
        }

        if (isset($apiMode) && $apiMode !== MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_ALL) {
            $this->extendWhereStatement(
                $whereStatement,
                sprintf('(livemode = %d)', $apiMode === MM_WPFS_Admin_Menu::PARAM_VALUE_API_MODE_LIVE ? 1 : 0)
            );
        }

        if (isset($whereStatement)) {
            $query .= $whereStatement;
        }

        $this->addOrderByStatementToQuery($query);

        return $query;
    }

    /**
     * @return array
     */
    public function get_columns() {
        return array(
            self::COLUMN_PAYMENT_METHOD => '',
            self::COLUMN_ID_DATE => $this->createCompositeColumnLabel(
                /* translators: Name of the 'ID' column */
                __('ID', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Date' column */
                __('Date', 'wp-full-stripe-admin')
            ),
            self::COLUMN_CUSTOMER_FORM => $this->createCompositeColumnLabel(
                /* translators: Name of the 'Customer' column */
                __('Customer', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Form' column */
                __('Form', 'wp-full-stripe-admin')
            ),
            self::COLUMN_AMOUNT =>
                /* translators: Name of the 'Amount' column */
                __('Amount', 'wp-full-stripe-admin'),
            self::COLUMN_STATUS_MODE => $this->createCompositeColumnLabel(
                /* translators: Name of the 'Status' column */
                __('Status', 'wp-full-stripe-admin'),
                /* translators: Name of the 'Mode' column */
                __('Mode', 'wp-full-stripe-admin')
            ),
            self::COLUMN_ACTIONS => '',
        );
    }

    /**
     * @return array
     */
    protected function get_sortable_columns() {
        return array();
    }

    /**
     *
     */
    public function no_items() {
        _e( 'No payment found.', 'wp-full-stripe-admin' );
    }

    /**
     * @param bool $withId
     */
    public function print_column_headers( $withId = true ) {
        list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

        $columnHeader = '<tr class="wpfs-data-table__tr">';
        foreach ( $columns as $key => $displayName ) {
            $cssClasses = array( 'wpfs-data-table__th' );
            switch ( $key ) {
                case self::COLUMN_PAYMENT_METHOD:
                    array_push( $cssClasses, 'wpfs-data-table__th--w40' );
                    break;

                case self::COLUMN_AMOUNT:
                    array_push( $cssClasses, 'wpfs-data-table__th--right' );
            }

            $classAttributeValue = implode( " ", $cssClasses );
            $columnHeader .= "<th class='{$classAttributeValue}'>{$displayName}</th>";
        }
        $columnHeader .= '</tr>';

        echo $columnHeader;
    }

    /**
     * @param $payment
     *
     * @return string
     */
    protected function renderIdDateCell( $payment ) : string {
        $dateLabel = MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat( strtotime( $payment->created ) );

        $html = "
         <td class='wpfs-data-table__td'>
            <a class='wpfs-btn wpfs-btn-link js-open-payment-details'>{$payment->eventID}</a>
            <br>
            <div class='wpfs-typo-body wpfs-typo-body--sm'>{$dateLabel}</div>
        </td>";

        return $html;
    }

    /**
     * @param $payment
     * @return string
     */
    protected function renderCustomerFormCell( $payment ) {
        $formDisplayName = $this->lookupFormDisplayName( $payment->formName );

        $html = "
        <td class='wpfs-data-table__td'>
            <div class='wpfs-typo-body wpfs-typo-body--gunmetal'>{$payment->email}</div>
            <div class='wpfs-typo-body wpfs-typo-body--sm'>{$formDisplayName}</div>
        </td>";

        return $html;
    }

    protected function renderAmountCell( $payment ) {
        $amountLabel = MM_WPFS_Currencies::formatAndEscape( $this->staticContext, $payment->currency, $payment->amount );

        $html = "
        <td class='wpfs-data-table__td wpfs-data-table__td--right'>
            <strong>{$amountLabel}</strong>
        </td>";

        return $html;
    }

    protected function determinePaymentStatusCss( $paymentStatus ) {
        $cssStyle = '';

        switch( $paymentStatus) {
            case MM_WPFS::PAYMENT_STATUS_PAID:
                $cssStyle = 'wpfs-tag--filled-green';
                break;

            case MM_WPFS::PAYMENT_STATUS_AUTHORIZED:
                $cssStyle = 'wpfs-tag--filled-blue';
                break;

            case MM_WPFS::PAYMENT_STATUS_FAILED:
            case MM_WPFS::PAYMENT_STATUS_UNKNOWN:
               $cssStyle = 'wpfs-tag--filled-red';
                break;

            case MM_WPFS::PAYMENT_STATUS_PENDING:
            case MM_WPFS::PAYMENT_STATUS_EXPIRED:
            case MM_WPFS::PAYMENT_STATUS_REFUNDED:
            case MM_WPFS::PAYMENT_STATUS_RELEASED:
            default:
                $cssStyle = 'wpfs-tag--filled-grey';
                break;
        }

        return $cssStyle;
    }

    /**
     * @param $payment
     * @return string
     */
    protected function renderStatusModeCell( $payment ) {
        $paymentStatus = MM_WPFS_Utils::getPaymentStatus( $payment );

        $statusCssStyle = $this->determinePaymentStatusCss( $paymentStatus );
        $statusLabel    = MM_WPFS_Admin::getPaymentStatusLabel( $paymentStatus );
        $modeLabel      = MM_WPFS_Admin::getApiModeLabelFromInteger( $payment->livemode );

        $html = "
        <td class='wpfs-data-table__td'>
            <div class='wpfs-tags'>
                <span class='wpfs-tag {$statusCssStyle}'>{$statusLabel}</span>
                <span class='wpfs-tag wpfs-tag--outline'>{$modeLabel}</span>
            </div>
        </td>
        ";

        return $html;
    }

    protected function renderActionsCell( $payment ) {
        $paymentStatus = MM_WPFS_Utils::getPaymentStatus( $payment );

        $html = "<td class='wpfs-data-table__td wpfs-data-table__td--right wpfs-data-table__td--actions'>";

        if ( MM_WPFS::PAYMENT_STATUS_AUTHORIZED == $paymentStatus ) {
            $captureLabel =
                /* translators: 'Capture' action label for payments */
                __( 'Capture', 'wp-full-stripe-admin' );

            $html .= "<a class='wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-capture-payment' data-tooltip-content='authorize-tooltip'>
                <span class='wpfs-icon-card'></span>
            </a>
            <div class='wpfs-tooltip-content' data-tooltip-id='authorize-tooltip'>
                <div class='wpfs-info-tooltip'>{$captureLabel}</div>
            </div>";
        }

        if ( MM_WPFS::PAYMENT_STATUS_PAID === $paymentStatus ||
             MM_WPFS::PAYMENT_STATUS_AUTHORIZED === $paymentStatus ) {
            $refundLabel =
                /* translators: 'Refund' action label for payments */
                __( 'Refund', 'wp-full-stripe-admin' );

            $html .= "<a class='wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-refund-payment' data-tooltip-content='refund-tooltip'>
                <span class='wpfs-icon-sync'></span>
            </a>
            <div class='wpfs-tooltip-content' data-tooltip-id='refund-tooltip'>
                <div class='wpfs-info-tooltip'>{$refundLabel}</div>
            </div>";
        }

        $deleteLabel =
            /* translators: 'Delete' action label for payments */
            __( 'Delete payment', 'wp-full-stripe-admin' );
        $html .= "<a class='wpfs-btn wpfs-btn-icon wpfs-btn-icon--20 js-tooltip js-delete-payment' data-tooltip-content='delete-payment-tooltip'>
                <span class='wpfs-icon-trash'></span>
            </a>
            <div class='wpfs-tooltip-content' data-tooltip-id='delete-payment-tooltip'>
                <div class='wpfs-info-tooltip'>{$deleteLabel}</div>
            </div>";

        $html .= '</td>';

        return $html;
    }

    public function display_rows() {
        $payments                   = $this->items;
        list( $columns, $hidden )   = $this->get_column_info();

        if ( ! empty( $payments ) ) {
            foreach ( $payments as $payment ) {
                $rowHtml  = "<tr class='wpfs-data-table__tr' data-db-id='{$payment->paymentID}' data-stripe-id='{$payment->eventID}'>";

                foreach ( $columns as $key => $displayName ) {
                    switch ( $key ) {
                        case self::COLUMN_PAYMENT_METHOD:
                            $rowHtml .= $this->renderPaymentMethodCell( $payment );
                            break;

                        case self::COLUMN_ID_DATE:
                            $rowHtml .= $this->renderIdDateCell( $payment );
                            break;

                        case self::COLUMN_CUSTOMER_FORM:
                            $rowHtml .= $this->renderCustomerFormCell( $payment );
                            break;

                        case self::COLUMN_AMOUNT:
                            $rowHtml .= $this->renderAmountCell( $payment );
                            break;

                        case self::COLUMN_STATUS_MODE:
                            $rowHtml .= $this->renderStatusModeCell( $payment );
                            break;

                        case self::COLUMN_ACTIONS:
                            $rowHtml .= $this->renderActionsCell( $payment );
                            break;
                    }
                }

                $rowHtml .= "</tr>";
                echo $rowHtml;
            }
        }
    }
}


class WPFS_Base_Table extends WPFS_List_Table {

    const HTTPS_DASHBOARD_STRIPE_COM = "https://dashboard.stripe.com/";
    const PATH_TEST = "test/";
    const PATH_CUSTOMERS = 'customers/';
    const PATH_CHARGES = 'charges/';
    const PATH_PAYMENTS = 'payments/';
    const PATH_SUBSCRIPTIONS = 'subscriptions/';

    public function print_column_headers( $with_id = true ) {
        list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

        $current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
        $current_url = remove_query_arg( 'paged', $current_url );

        if ( isset( $_GET['orderby'] ) ) {
            $current_orderby = $_GET['orderby'];
        } else {
            $current_orderby = '';
        }

        if ( isset( $_GET['order'] ) && 'desc' === $_GET['order'] ) {
            $current_order = 'desc';
        } else {
            $current_order = 'asc';
        }

        if ( ! empty( $columns['cb'] ) ) {
            static $cb_counter = 1;
            $columns['cb'] = '<label class="screen-reader-text" for="cb-select-all-' . $cb_counter . '">' .
                /* translators: Label for the 'Select all' option of status filter on list pages */
                __( 'Select All', 'wp-full-stripe-admin' ) . '</label>'
                . '<input id="cb-select-all-' . $cb_counter . '" type="checkbox" />';
            $cb_counter ++;
        }

        foreach ( $columns as $column_key => $column_display_name ) {
            $class = array( 'manage-column', "column-$column_key" );

            if ( in_array( $column_key, $hidden ) ) {
                $class[] = 'hidden';
            }

            if ( 'cb' === $column_key ) {
                $class[] = 'check-column';
            } elseif ( in_array( $column_key, array( 'posts', 'comments', 'links' ) ) ) {
                $class[] = 'num';
            }

            if ( $column_key === $primary ) {
                $class[] = 'column-primary';
            }

            if ( isset( $sortable[ $column_key ] ) ) {
                list( $orderby, $desc_first ) = $sortable[ $column_key ];

                if ( $current_orderby === $orderby ) {
                    $order   = 'asc' === $current_order ? 'desc' : 'asc';
                    $class[] = 'sorted';
                    $class[] = $current_order;
                } else {
                    $order   = $desc_first ? 'desc' : 'asc';
                    $class[] = 'sortable';
                    $class[] = $desc_first ? 'asc' : 'desc';
                }

                if ( strpos( $column_display_name, '<br>' ) ) {
                    $column_display_name_parts = explode( '<br>', $column_display_name );
                    foreach ( $column_display_name_parts as $i => $part ) {
                        if ( $i == 0 ) {
                            $column_display_name = '<a href="' . esc_url( add_query_arg( compact( 'orderby', 'order' ), $current_url ) ) . '"><span>' . $part . '</span><span class="sorting-indicator"></span></a>';
                        } else {
                            $column_display_name .= '<span class="wpfs-table-sub-header">' . $part . '</span>';
                        }
                    }
                } else {
                    $column_display_name = '<a href="' . esc_url( add_query_arg( compact( 'orderby', 'order' ), $current_url ) ) . '"><span>' . $column_display_name . '</span><span class="sorting-indicator"></span></a>';
                }
            }

            $tag   = ( 'cb' === $column_key ) ? 'td' : 'th';
            $scope = ( 'th' === $tag ) ? 'scope="col"' : '';
            $id    = $with_id ? "id='$column_key'" : '';

            if ( ! empty( $class ) ) {
                $class = "class='" . join( ' ', $class ) . "'";
            }

            echo "<$tag $scope $id $class>$column_display_name</$tag>";
        }
    }

    /**
     * @param $title
     *
     * @param $aggregated_columns
     *
     * @return string
     */
    protected function format_column_header_title( $title, array $aggregated_columns = null ) {
        $column_label = "<b>{$title}</b>";
        if ( ! empty( $aggregated_columns ) ) {
            $size = sizeof( $aggregated_columns );
            $column_label .= '<br>';
            foreach ( $aggregated_columns as $key => $value ) {
                $column_label .= $value;
                if ( $key < $size - 1 ) {
                    $column_label .= ' / ';
                }
            }
        }

        return $column_label;
    }

    /**
     * @param $stripe_customer_id
     * @param $live_mode
     *
     * @return string
     */
    protected function build_stripe_customer_link( $stripe_customer_id, $live_mode ) {
        $href = $this->build_stripe_base_url( $live_mode );
        $href .= self::PATH_CUSTOMERS . $stripe_customer_id;

        return $href;
    }

    /**
     * @param $live_mode
     *
     * @return string
     */
    protected function build_stripe_base_url( $live_mode ) {
        $href = self::HTTPS_DASHBOARD_STRIPE_COM;
        if ( $live_mode == 0 ) {
            $href .= self::PATH_TEST;
        }

        return $href;
    }

    /**
     * @param $stripe_subscription_id
     * @param $live_mode
     *
     * @return string
     */
    protected function build_stripe_subscription_link( $stripe_subscription_id, $live_mode ) {
        $href = $this->build_stripe_base_url( $live_mode );
        $href .= self::PATH_SUBSCRIPTIONS . $stripe_subscription_id;

        return $href;
    }

    /**
     * @param $stripe_charge_id
     * @param $live_mode
     *
     * @return string
     */
    protected function build_stripe_payments_link( $stripe_charge_id, $live_mode ) {
        $href = $this->build_stripe_base_url( $live_mode );
        $href .= self::PATH_PAYMENTS . $stripe_charge_id;

        return $href;
    }

    /**
     * Add extra markup in the toolbars before or after the list
     *
     * @param string $which , helps you decide if you add the markup after (bottom) or before (top) the list
     */
    protected function extra_tablenav( $which ) {
        if ( $which == "top" ) {
            echo '<div class="wrap">';
        }
        if ( $which == "bottom" ) {
            echo '</div>';
        }
    }

}

class WPFS_Log_Table extends WPFS_Base_Table {

	public function __construct() {
		parent::__construct( array(
			'singular' => __( 'Log entry', 'wp-full-stripe-admin' ),
			'plural'   => __( 'Log entries', 'wp-full-stripe-admin' ),
			'ajax'     => false
		) );
	}

	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	public function prepare_items() {
		global $wpdb;

		$query = "SELECT * FROM {$wpdb->prefix}fullstripe_log";

		$whereStatement = null;

		$module = ! empty( $_REQUEST['module'] ) ? esc_sql( trim( $_REQUEST['module'] ) ) : null;
		$level  = ! empty( $_REQUEST['level'] ) ? esc_sql( trim( $_REQUEST['level'] ) ) : null;

		if ( isset( $module ) ) {
			if ( ! isset( $whereStatement ) ) {
				$whereStatement = ' WHERE ';
			} else {
				$whereStatement .= ' AND ';
			}
			$whereStatement .= sprintf( "( LOWER( `module` ) = LOWER( '%s' ) )", $module );
		}

		if ( isset( $level ) ) {
			if ( ! isset( $whereStatement ) ) {
				$whereStatement = ' WHERE ';
			} else {
				$whereStatement .= ' AND ';
			}
			$whereStatement .= sprintf( '(`level` = %s)', $level );
		}

		if ( isset( $whereStatement ) ) {
			$query .= $whereStatement;
		}

		$orderBy = ! empty( $_REQUEST['orderby'] ) ? esc_sql( $_REQUEST['orderby'] ) : 'created';
		$order   = ! empty( $_REQUEST['order'] ) ? esc_sql( $_REQUEST['order'] ) : ( empty( $_REQUEST['orderby'] ) ? 'DESC' : 'ASC' );
		if ( ! empty( $orderBy ) && ! empty( $order ) ) {
			$query .= ' ORDER BY ' . $orderBy . ' ' . $order;
		}

		$total_items = $wpdb->query( $query );
		$per_page    = 50;
		$total_pages = ceil( $total_items / $per_page );
		$this->set_pagination_args( array(
			"total_items" => $total_items,
			"total_pages" => $total_pages,
			"per_page"    => $per_page,
		) );
		$current_page = $this->get_pagenum();
		if ( ! empty( $current_page ) && ! empty( $per_page ) ) {
			$offset = ( $current_page - 1 ) * $per_page;
			$query .= ' LIMIT ' . (int) $offset . ',' . (int) $per_page;
		}

		$columns               = $this->get_columns();
		$hidden                = array();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, $hidden, $sortable );

		$this->items = $wpdb->get_results( $query );
	}

	/**
	 * Decide which columns to activate the sorting functionality on
	 * @return array $sortable, the array of columns that can be sorted by the user
	 */
	protected function get_sortable_columns() {
		return array(
			'created' => array( 'created', false )
		);
	}


	/**
	 * Display the rows of records in the table
	 * @return string, echo the markup of the rows
	 */
	public function display_rows() {
		$items = $this->items;

		list( $columns, $hidden ) = $this->get_column_info();

		if ( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				$row = '';
				$row .= "<tr id=\"record_{$item->id}\">";
				foreach ( $columns as $column_name => $column_display_name ) {
					$class = "class=\"$column_name column-$column_name\"";
					$style = "";
					if ( in_array( $column_name, $hidden ) ) {
						$style = " style=\"display:none;\"";
					}
					$attributes = "{$class} {$style}";

					switch ( $column_name ) {
						case 'created':
							$dateLabel = MM_WPFS_Utils::formatTimestampWithWordpressDateTimeFormat( strtotime( $item->created ) );
							$row .= "<td {$attributes}>{$dateLabel}</td>";
							break;
						case 'module':
							$module = $item->module;
							$row .= "<td {$attributes}>{$module}</td>";
							break;
						case 'class':
							$class = $item->class;
							$row .= "<td {$attributes}>{$class}</td>";
							break;
						case 'function':
							$function = $item->function;
							$row .= "<td {$attributes}>{$function}</td>";
							break;
						case 'level':
							$level = $item->level;
							$row .= "<td {$attributes}>{$level}</td>";
							break;
						case 'message':
							$message    = $item->message;
							$stackTrace = $item->exception;
							$row .= "<td {$attributes} data-toggle=\"{$stackTrace}\">{$message}</td>";
							break;
					}
				}

				$row .= '</tr>';

				echo $row;
			}
		}
	}

	public function no_items() {
		_e( 'No log entries found.', 'wp-full-stripe-admin' );
	}

}
