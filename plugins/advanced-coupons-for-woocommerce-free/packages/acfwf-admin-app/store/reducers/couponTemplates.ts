// #region [Imports] ===================================================================================================

// Types
import { ICouponTemplate, ICouponTemplatesStore, ICouponTemplateCategory } from '../../types/couponTemplates';
import {
  ECouponTemplatesActionTypes,
  ISetCouponTemplatesCategoriesPayload,
  ISetCouponTemplatesPayload,
  IUnsetRecentCouponTemplatePayload,
  ITogglePremiumModalPayload,
} from '../actions/couponTemplates';

// #endregion [Imports]

// #region [Reducer] ===================================================================================================

const reducer = (
  state: ICouponTemplatesStore = {
    loading: false,
    templates: [],
    recent: [],
    review: [],
    edit: null,
    categories: [],
    formResponse: null,
    premiumModal: false,
  },
  action: { type: string; payload: any }
): ICouponTemplatesStore => {
  let index;
  switch (action.type) {
    case ECouponTemplatesActionTypes.SET_COUPON_TEMPLATES: {
      const { data: templates, type = 'main' } = action.payload as ISetCouponTemplatesPayload;

      switch (type) {
        case 'main':
          return { ...state, templates };
        case 'recent':
          return { ...state, recent: templates };
        case 'review':
          return { ...state, review: templates };
      }
    }

    case ECouponTemplatesActionTypes.SET_COUPON_TEMPLATE_CATEGORIES: {
      const { data: categories } = action.payload as ISetCouponTemplatesCategoriesPayload;
      return { ...state, categories: categories };
    }

    case ECouponTemplatesActionTypes.UNSET_RECENT_COUPON_TEMPLATE: {
      const { id } = action.payload as IUnsetRecentCouponTemplatePayload;
      const recentTemplates = [...state.recent];
      index = recentTemplates.findIndex((c) => c.id === id);
      recentTemplates.splice(index, 1);
      return { ...state, recent: recentTemplates };
    }

    case ECouponTemplatesActionTypes.SET_COUPON_TEMPLATES_LOADING: {
      const { loading } = action.payload;
      return { ...state, loading };
    }

    case ECouponTemplatesActionTypes.SET_EDIT_COUPON_TEMPLATE: {
      const { data: edit } = action.payload;
      return { ...state, edit };
    }

    case ECouponTemplatesActionTypes.SET_EDIT_COUPON_TEMPLATE_FIELD_VALUE: {
      const { field, value } = action.payload;
      const edit = { ...state.edit } as ICouponTemplate;

      if (!edit || !edit.fields) return state;

      const index = edit.fields.findIndex((f) => f.field === field);

      if (index > -1) {
        edit.fields[index].value = value;
      }

      return { ...state, edit: { ...edit } };
    }

    case ECouponTemplatesActionTypes.VALIDATE_EDIT_COUPON_TEMPLATE_DATA: {
      const edit = { ...state.edit } as ICouponTemplate;

      if (!edit || !edit.fields) return state;

      const fields = edit.fields.map((f) => {
        if (f.is_required && !f.value) {
          f.error = 'This field is required';
        } else {
          f.error = undefined;
        }
        return f;
      });

      return { ...state, edit: { ...edit, fields } };
    }

    case ECouponTemplatesActionTypes.SET_COUPON_CREATED_RESPONSE_DATA: {
      const { data } = action.payload;
      return { ...state, formResponse: data };
    }

    case ECouponTemplatesActionTypes.CLEAR_COUPON_CREATED_RESPONSE_DATA: {
      const edit = { ...state.edit } as ICouponTemplate;

      edit.fields = edit.fields.map((f) => {
        f.value = f.pre_filled_value ?? '';
        return f;
      });

      return { ...state, formResponse: null, edit: { ...edit } };
    }

    case ECouponTemplatesActionTypes.TOGGLE_PREMIUM_MODAL: {
      const { show } = action.payload as ITogglePremiumModalPayload;
      return { ...state, premiumModal: show };
    }
  }

  return state;
};

export default reducer;

// #endregion [Reducer]
