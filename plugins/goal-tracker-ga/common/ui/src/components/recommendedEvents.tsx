export const recommendedEvents: Record<
  string,
  Record<
    string,
    { type: string; required: boolean; coupon?: string; placeholder?: string }
  >
> = {
  ad_impression: {
    ad_platform: { type: 'text', required: true, placeholder: 'APPXXX' },
    ad_source: { type: 'text', required: true, placeholder: 'Network Name' },
    ad_unit_name: { type: 'text', required: true, placeholder: 'Unit Name' },
    ad_format: { type: 'text', required: true, placeholder: 'Format' },
    value: { type: 'number', required: true, placeholder: '7.77' },
    currency: { type: 'text', required: true, placeholder: 'USD' },
  },
  add_payment_info: {
    currency: { type: 'text', required: true, placeholder: 'USD' },
    value: { type: 'number', required: true, placeholder: '7.77' },
    coupon: { type: 'text', required: false, placeholder: 'SUMMER_FUN' },
    payment_type: { type: 'text', required: false, placeholder: 'Credit Card' },
    items: { type: 'text', required: true, placeholder: '' },
  },
  add_shipping_info: {
    currency: { type: 'text', required: true, placeholder: 'USD' },
    value: { type: 'number', required: true, placeholder: '7.77' },
    coupon: { type: 'text', required: false, placeholder: 'SUMMER_FUN' },
    shipping_tier: { type: 'text', required: false, placeholder: 'Ground' },
    items: { type: 'text', required: true, placeholder: '' },
  },
  add_to_cart: {
    currency: { type: 'text', required: true, placeholder: 'USD' },
    value: { type: 'number', required: true, placeholder: '7.77' },
    items: { type: 'text', required: true, placeholder: '' },
  },
  add_to_wishlist: {
    currency: { type: 'text', required: true, placeholder: 'USD' },
    value: { type: 'number', required: true, placeholder: '7.77' },
    items: { type: 'text', required: true, placeholder: '' },
  },
  begin_checkout: {
    currency: { type: 'text', required: true, placeholder: 'USD' },
    value: { type: 'number', required: true, placeholder: '7.77' },
    coupon: { type: 'text', required: false, placeholder: 'SUMMER_FUN' },
    items: { type: 'text', required: true, placeholder: '' },
  },
  earn_virtual_currency: {
    virtual_currency_name: {
      type: 'text',
      required: false,
      placeholder: 'Gems',
    },
    value: { type: 'number', required: false, placeholder: '5' },
  },
  generate_lead: {
    currency: { type: 'text', required: true, placeholder: 'USD' },
    value: { type: 'number', required: true, placeholder: '7.77' },
  },
  join_group: {
    group_id: { type: 'text', required: false, placeholder: 'G_12345' },
  },
  level_end: {
    level_name: {
      type: 'text',
      required: false,
      placeholder: 'The journey begins..',
    },
    success: { type: 'text', required: false, placeholder: 'true' },
  },
  level_start: {
    level_name: {
      type: 'text',
      required: false,
      placeholder: 'The journey begins..',
    },
  },
  level_up: {
    level: { type: 'number', required: false, placeholder: '5' },
    character: { type: 'text', required: false, placeholder: 'Player 1' },
  },
  login: { method: { type: 'text', required: false, placeholder: 'Google' } },
  post_score: {
    score: { type: 'number', required: true, placeholder: '10000' },
    level: { type: 'number', required: false, placeholder: '5' },
    character: { type: 'text', required: false, placeholder: 'Player 1' },
  },
  purchase: {
    currency: { type: 'text', required: true, placeholder: 'USD' },
    transaction_id: { type: 'text', required: true, placeholder: 'T_12345' },
    value: { type: 'number', required: true, placeholder: '12.21' },
    affiliation: { type: 'text', required: false, placeholder: 'Google Store' },
    coupon: { type: 'text', required: false, placeholder: 'SUMMER_FUN' },
    shipping: { type: 'number', required: false, placeholder: '3.33' },
    tax: { type: 'number', required: false, placeholder: '1.1' },
    items: { type: 'text', required: true, placeholder: '' },
  },
  refund: {
    currency: { type: 'text', required: true, placeholder: 'USD' },
    transaction_id: { type: 'text', required: true, placeholder: 'T_12345' },
    value: { type: 'number', required: true, placeholder: '7.77' },
    affiliation: { type: 'text', required: false, placeholder: 'Google Store' },
    coupon: { type: 'text', required: false, placeholder: 'SUMMER_FUN' },
    shipping: { type: 'number', required: false, placeholder: '3.33' },
    tax: { type: 'number', required: false, placeholder: '1.1' },
    items: { type: 'text', required: true, placeholder: '' },
  },
  remove_from_cart: {
    currency: { type: 'text', required: true, placeholder: 'USD' },
    value: { type: 'number', required: true, placeholder: '7.77' },
    items: { type: 'text', required: true, placeholder: '' },
  },
  search: {
    search_term: { type: 'text', required: true, placeholder: 't-shirts' },
  },
  select_content: {
    content_type: { type: 'text', required: false, placeholder: 'product' },
    item_id: { type: 'text', required: false, placeholder: 'I_12345' },
  },
  select_item: {
    item_list_id: { type: 'text', required: false, placeholder: 'SKU_12345' },
    item_list_name: {
      type: 'text',
      required: false,
      placeholder: 'Stan and Friends Tee',
    },
    items: { type: 'text', required: true, placeholder: '' },
  },
  select_promotion: {
    creative_name: {
      type: 'text',
      required: false,
      placeholder: 'summer_banner2',
    },
    creative_slot: {
      type: 'text',
      required: false,
      placeholder: 'featured_app_1',
    },
    location_id: { type: 'text', required: false, placeholder: 'L_12345' },
    promotion_id: { type: 'text', required: false, placeholder: 'P_12345' },
    promotion_name: {
      type: 'text',
      required: false,
      placeholder: 'Summer Sale',
    },
    items: { type: 'text', required: false, placeholder: '' },
  },
  share: {
    method: { type: 'text', required: false, placeholder: 'Twitter' },
    content_type: { type: 'text', required: false, placeholder: 'image' },
    item_id: { type: 'text', required: false, placeholder: 'C_12345' },
  },
  sign_up: { method: { type: 'text', required: false, placeholder: 'Google' } },
  spend_virtual_currency: {
    value: { type: 'number', required: true, placeholder: '5' },
    virtual_currency_name: {
      type: 'text',
      required: true,
      placeholder: 'Gems',
    },
    item_name: { type: 'text', required: false, placeholder: 'Starter Boost' },
  },
  tutorial_begin: {},
  tutorial_complete: {},
  unlock_achievement: {
    achievement_id: { type: 'text', required: true, placeholder: 'A_12345' },
  },
  view_cart: {
    currency: { type: 'text', required: true, placeholder: 'USD' },
    value: { type: 'number', required: true, placeholder: '7.77' },
    items: { type: 'text', required: true, placeholder: '' },
  },
  view_item: {
    currency: { type: 'text', required: true, placeholder: 'USD' },
    value: { type: 'number', required: true, placeholder: '7.77' },
    items: { type: 'text', required: true, placeholder: '' },
  },
  view_item_list: {
    item_list_id: {
      type: 'text',
      required: false,
      placeholder: 'related_products',
    },
    item_list_name: {
      type: 'text',
      required: false,
      placeholder: 'Related products',
    },
    items: { type: 'text', required: true, placeholder: '' },
  },
  view_promotion: {
    creative_name: {
      type: 'text',
      required: false,
      placeholder: 'summer_banner2',
    },
    creative_slot: {
      type: 'text',
      required: false,
      placeholder: 'featured_app_1',
    },
    location_id: { type: 'text', required: false, placeholder: 'L_12345' },
    promotion_id: { type: 'text', required: false, placeholder: 'P_12345' },
    promotion_name: {
      type: 'text',
      required: false,
      placeholder: 'Summer Sale',
    },
    items: { type: 'text', required: true, placeholder: '' },
  },
};
