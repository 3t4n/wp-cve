<?php

namespace ShopWP\API\Admin\Orders;

if (!defined('ABSPATH')) {
    exit();
}

class Queries
{

    public static function default_schema() {
      return '
            app {
                name
                icon {
                    altText
                    height
                    width
                    id
                    url
                }
            }
            billingAddress {
                address1 
                address2
                city
                company
                country
                countryCodeV2
                firstName
                lastName
                formattedArea
                id
                lastName
                latitude
                longitude
                name
                phone
                province
                provinceCode
                zip
            }
            billingAddressMatchesShippingAddress
            canMarkAsPaid
            canNotifyCustomer
            cancelReason
            cancelledAt
            cartDiscountAmountSet {
                presentmentMoney {
                    amount
                    currencyCode
                }
                shopMoney {
                    amount
                    currencyCode
                }
            }
            clientIp
            closed
            closedAt
            confirmed
            createdAt
            currencyCode
            currentCartDiscountAmountSet {
                presentmentMoney {
                    amount
                    currencyCode
                }
                shopMoney {
                    amount
                    currencyCode
                }
            }
            currentSubtotalLineItemsQuantity
            currentTotalWeight
            customAttributes {
                key
                value
            }
            customer {
                email 
                firstName 
                lastName
                id
            }
            customerAcceptsMarketing
            customerLocale
            discountCode
            edited
            email
            estimatedTaxes
            hasTimelineComment
            id
            legacyResourceId
            merchantEditable
            merchantEditableErrors
            name
            netPaymentSet {
                presentmentMoney {
                    amount
                    currencyCode
                }
                shopMoney {
                    amount
                    currencyCode
                }
            } 
            note
            originalTotalPriceSet {
                presentmentMoney {
                    amount
                    currencyCode
                }
                shopMoney {
                    amount
                    currencyCode
                }
            } 
            paymentGatewayNames
            phone
            presentmentCurrencyCode
            processedAt
            publication {
                name 
                id 
            }
            refundDiscrepancySet {
                presentmentMoney {
                    amount
                    currencyCode
                }
                shopMoney {
                    amount
                    currencyCode
                }
            }
            refundable
            requiresShipping
            restockable
            riskLevel
            shippingAddress {
                address1 
                address2
                city
                company
                country
                countryCodeV2
                firstName
                lastName
                formattedArea
                id
                lastName
                latitude
                longitude
                name
                phone
                province
                provinceCode
                zip
            }
            sourceIdentifier
            subtotalLineItemsQuantity
            subtotalPriceSet {
                presentmentMoney {
                    amount
                    currencyCode
                }
                shopMoney {
                    amount
                    currencyCode
                }
            } 
            tags
            taxesIncluded
            test
            totalWeight
            unpaid
            updatedAt
            lineItems(first: 10) {
                edges {
                    node {
                        currentQuantity
                        id 
                        merchantEditable
                        name 
                        nonFulfillableQuantity
                        quantity
                        refundableQuantity
                        requiresShipping
                        restockable
                        sku
                        taxable
                        title
                        customAttributes {
                            key 
                            value 
                        }
                    }
                }
            }
      ';
   }

   public function graph_query_get_orders($params, $custom_schema = false) {

      if (empty($params['cursor'])) {
         unset($params['cursor']);
      }

      $schema = $custom_schema ? $custom_schema : self::default_schema();

      $final_vars = [
            'query' => $params['query'],
            'first' => $params['first'],
            'reverse' => isset($params['reverse']) ? $params['reverse'] : false,
            'sortKey' => isset($params['sortKey']) ? $params['sortKey'] : 'CREATED_AT',
      ];

      if (isset($params['cursor'])) {
         $final_vars['cursor'] = $params['cursor'];
      }

      // Docs: https://shopify.dev/api/admin-graphql/2022-07/objects/Order#queries

      return [
         "query" => 'query($query: String!, $first: Int!, $cursor: String, $sortKey: OrderSortKeys, $reverse: Boolean) {
            orders(first: $first, query: $query, after: $cursor, reverse: $reverse, sortKey: $sortKey) {
               pageInfo {
                  hasNextPage
                  hasPreviousPage
               }
               edges {
                  cursor
                  node {
                     ' . $schema . '
                  }
               }
            }
         }',
         "variables" => $final_vars
      ];
   }

}