<?php

namespace ShopWP\API\Storefront\Cart;

if (!defined('ABSPATH')) {
	exit;
}

class Queries {

   public function default_cart_schema() {
      return '
         id
         note
         checkoutUrl
         createdAt
         updatedAt
         totalQuantity
         buyerIdentity {
            countryCode
            email
            phone
         }
         attributes {
            key
            value
         }
         estimatedCost {
            subtotalAmount {
               amount
               currencyCode
            }
            totalAmount {
               amount
               currencyCode
            }
            totalDutyAmount {
               amount
               currencyCode
            }
            totalTaxAmount {
               amount
               currencyCode
            }
         }
         discountCodes {
            applicable
            code
         }
         lines(first: 250) {
            edges {
               node {
                  id
                  merchandise {
                     ... on ProductVariant {
                        product {
                           title
                        }
                        availableForSale
                        compareAtPriceV2 {
                           amount
                           currencyCode
                        }
                        currentlyNotInStock
                        id
                        image {
                           width
                           height
                           altText
                           id
                           originalSrc
                           transformedSrc
                        }
                        priceV2 {
                           amount
                           currencyCode
                        }
                        quantityAvailable
                        requiresShipping
                        selectedOptions {
                           name 
                           value
                        }
                        sku
                        title
                        weight
                        weightUnit
                     }
                  }
                  quantity
                  sellingPlanAllocation {
                     priceAdjustments {
                        price {
                           amount
                           currencyCode
                        }
                     }
                     sellingPlan {
                        description
                        id
                        name
                        recurringDeliveries
                        options {
                           name
                           value
                        }
                        priceAdjustments {
                           adjustmentValue 
                           orderCount
                        }
                        
                     }
                  }
                  attributes {
                     key
                     value
                  }
                  discountAllocations {
                     discountedAmount {
                        amount
                        currencyCode
                     }
                  }
                  estimatedCost {
                     subtotalAmount {
                        amount
                        currencyCode
                     }
                     totalAmount {
                        amount
                        currencyCode
                     }
                  }
               }
            }
         }
      ';
   }

   public function graph_query_apply_discount($data) {
      return [
         "query" => 'mutation cartDiscountCodesUpdate($cartId: ID!, $discountCodes: [String!], $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
               cartDiscountCodesUpdate(cartId: $cartId, discountCodes: $discountCodes) {
                  cart {
                     ' . $this->default_cart_schema() . '
                  }
                  userErrors {
                     field
                     message
                  }
               }
            }
         ',
         "variables" => [
            'cartId' => $data['cartId'],
            'discountCodes' => $data['discountCodes'],
            'language' => $data['language'],
            'country' => $data['country']
         ]
      ];
   }

   public function graph_query_create_checkout($cart_data) {
      return [
         "query" => 'mutation checkoutCreate($cartInput: CheckoutCreateInput!) {
            checkoutCreate(input: $cartInput) {
               checkout {
                  id
               }
               checkoutUserErrors {
                  code
                  field
                  message
               }
            }
          }',
         "variables" => [
            'cartInput' => [
               "lineItems" => $cart_data['lines']
            ]
         ]
      ];
   }

   public function graph_query_add_lineitems($data) {

      return [
         "query" => 'mutation cartLinesAdd($cartId: ID!, $lines: [CartLineInput!]!, $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
            cartLinesAdd(cartId: $cartId, lines: $lines) {
               cart {
                  ' . $this->default_cart_schema() . '
               }
               userErrors {
                  field
                  message
               }
            }
         }',
         "variables" => [
            'cartId' => $data['cartId'],
            'lines' => $data['lines'],
            'language' => $data['language'],
            'country' => $data['country']
         ]
      ];

   }

   public function graph_query_remove_lineitems($data) {

      return [
         "query" => 'mutation cartLinesRemove($cartId: ID!, $lineIds: [ID!]!, $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
               cartLinesRemove(cartId: $cartId, lineIds: $lineIds) {
                  cart {
                     ' . $this->default_cart_schema() . '
                  }
                  userErrors {
                     field
                     message
                  }
               }
            }
         ',
         "variables" => [
            'cartId' => $data['cartId'],
            'lineIds' => $data['lineIds'],
            'language' => $data['language'],
            'country' => $data['country']
         ]
      ];

   }

   public function graph_query_update_lineitems($data) {

      return [
         "query" => 'mutation cartLinesUpdate($cartId: ID!, $lines: [CartLineUpdateInput!]!, $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
               cartLinesUpdate(cartId: $cartId, lines: $lines) {
                  cart {
                     ' . $this->default_cart_schema() . '
                  }
                  userErrors {
                     field
                     message
                  }
               }
            }
         ',
         "variables" => [
            'cartId' => $data['cartId'],
            'lines' => $data['lines'],
            'language' => $data['language'],
            'country' => $data['country']
         ]
      ];

   }

   public function graph_query_update_note($data) {

      return [
         "query" => 'mutation cartNoteUpdate($cartId: ID!, $note: String!, $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
               cartNoteUpdate(cartId: $cartId, note: $note) {
                  cart {
                     ' . $this->default_cart_schema() . '
                  }
                  userErrors {
                     field
                     message
                  }
               }
            }
         ',
         "variables" => [
            'cartId' => $data['cartId'],
            'note' => $data['note'],
            'language' => $data['language'],
            'country' => $data['country']
         ]
      ];

   }   
   

   public function graph_query_get_cart($data) {
      return [
         "query" => 'query($id: ID!, $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
            cart(id: $id) {
               ' . $this->default_cart_schema() . '
            }
          }',
         "variables" => [
            'id' => $data['id'],
            'language' => $data['language'],
            'country' => $data['country'],
         ]
      ];
   }

   public function graph_query_create_cart($cart_data) {

      return [
         "query" => 'mutation cartCreate($lines: [CartLineInput!], $note: String, $attributes: [AttributeInput!]!, $buyerIdentity: CartBuyerIdentityInput
, $discountCodes: [String!], $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
            cartCreate(input: {
               lines: $lines, 
               note: $note, 
               attributes: $attributes, 
               discountCodes: $discountCodes, 
               buyerIdentity: $buyerIdentity
            }) 
            {
               cart {
                  ' . $this->default_cart_schema() . '
               }
               userErrors {
                  code
                  field
                  message
               }
            }
          }',
         "variables" => [
            "lines"           => empty($cart_data['lines']) ? [] : $cart_data['lines'],
            "note"            => empty($cart_data['note']) ? '' : $cart_data['note'],
            "attributes"      => empty($cart_data['attributes']) ? [] : $cart_data['attributes'],
            "discountCodes"   => empty($cart_data['discountCodes']) ? [] : $cart_data['discountCodes'],
            'buyerIdentity'   => empty($cart_data['buyerIdentity']) ? [
               'countryCode' => $cart_data['country']
            ] : $cart_data['buyerIdentity'],
            'language'        => $cart_data['language'],
            'country'         => $cart_data['country'],
         ]
      ];

   }

   public function graph_query_update_cart_attributes($cart_data) {
      return [
         "query" => 'mutation cartAttributesUpdate($attributes: [AttributeInput!]!, $cartId: ID!, $language: LanguageCode, $country: CountryCode) @inContext(country: $country, language: $language) {
            cartAttributesUpdate(cartId: $cartId, attributes: $attributes) 
            {
               cart {
                  ' . $this->default_cart_schema() . '
               }
               userErrors {
                  code
                  field
                  message
               }
            }
          }',
         "variables" => [
            "cartId"       => $cart_data['cartId'],
            "attributes"   => $cart_data['attributes'],
            'language'     => $cart_data['language'],
            'country'      => $cart_data['country'],
         ]
      ];

   }

   public function graph_query_update_buyer_identity($cart_data) {
      return [
         "query" => 'mutation cartBuyerIdentityUpdate($buyerIdentity: CartBuyerIdentityInput!, $cartId: ID!) {
               cartBuyerIdentityUpdate(buyerIdentity: $buyerIdentity, cartId: $cartId) {
                  cart {
                     ' . $this->default_cart_schema() . '
                  }
                  userErrors {
                     field
                     message
                  }
               }
            }
         ',
         "variables" => [
            "cartId"          => $cart_data['cartId'],
            "buyerIdentity"   => $cart_data['buyerIdentity']
         ]
      ];

   }   

}