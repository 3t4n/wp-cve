// #region [Imports] ===================================================================================================

// Types
import {
  ICouponTemplate,
  ICouponTemplateCategory,
  ICouponTemplateFormData,
  ICreateCouponFromTemplateResponse,
} from '../../types/couponTemplates';

// #endregion [Imports]

// #region [Action Payloads] ===========================================================================================

export interface IReadCouponTemplatesPayload {
  isReview?: boolean;
  processingCB?: () => void;
  successCB?: (arg: any) => void;
  failCB?: (arg: any) => void;
}

export interface IReadCouponTemplatePayload {
  id: number;
  isReview?: boolean;
  processingCB?: () => void;
  successCB?: (arg: any) => void;
  failCB?: (arg: any) => void;
}

export interface IReadRecentCouponTemplatesPayload {
  processingCB?: () => void;
  successCB?: (arg: any) => void;
  failCB?: (arg: any) => void;
}

export interface IReadCouponTemplateCategoriesPayload {
  processingCB?: () => void;
  successCB?: (arg: any) => void;
  failCB?: (arg: any) => void;
}

export interface IReadCouponTemplateCategoryPayload {
  slug: string;
  processingCB?: () => void;
  successCB?: (arg: any) => void;
  failCB?: (arg: any) => void;
}

export interface ICreateCouponFromTemplatePayload {
  data: ICouponTemplateFormData;
  processingCB?: () => void;
  successCB?: (arg: any) => void;
  failCB?: (arg: any) => void;
}

export interface IDeleteRecentCouponTemplatePayload {
  id: number;
  processingCB?: () => void;
  successCB?: (arg: any) => void;
  failCB?: (arg: any) => void;
}

export interface ISetCouponTemplatesPayload {
  data: ICouponTemplate[];
  type?: 'main' | 'recent' | 'review';
}

export interface ISetCouponTemplatesCategoriesPayload {
  data: ICouponTemplateCategory[];
}

export interface IUnsetRecentCouponTemplatePayload {
  id: number;
}

export interface ISetCouponTemplatesLoadingPayload {
  loading: boolean;
}

export interface ISetEditCouponTemplatePayload {
  data: ICouponTemplate | null;
}

export interface ISetEditCouponTemplateFieldValuePayload {
  field: string;
  value: any;
}

export interface ISetCreatedCouponResponseDataPayload {
  data: ICreateCouponFromTemplateResponse | null;
}

export interface ITogglePremiumModalPayload {
  show: boolean;
}

// #endregion [Action Payloads]

// #region [Action Types] ==============================================================================================

export enum ECouponTemplatesActionTypes {
  READ_COUPON_TEMPLATES = 'READ_COUPON_TEMPLATES',
  READ_COUPON_TEMPLATE = 'READ_COUPON_TEMPLATE',
  READ_RECENT_COUPON_TEMPLATES = 'READ_RECENT_COUPON_TEMPLATES',
  READ_COUPON_TEMPLATE_CATEGORIES = 'READ_COUPON_TEMPLATE_CATEGORIES',
  READ_COUPON_TEMPLATE_CATEGORY = 'READ_COUPON_TEMPLATE_CATEGORY',
  CREATE_COUPON_FROM_TEMPLATE = 'CREATE_COUPON_FROM_TEMPLATE',
  DELETE_RECENT_COUPON_TEMPLATE = 'DELETE_RECENT_COUPON_TEMPLATE',
  SET_COUPON_TEMPLATES = 'SET_COUPON_TEMPLATES',
  SET_COUPON_TEMPLATE_CATEGORIES = 'SET_COUPON_TEMPLATE_CATEGORIES',
  UNSET_RECENT_COUPON_TEMPLATE = 'UNSET_RECENT_COUPON_TEMPLATE',
  SET_COUPON_TEMPLATES_LOADING = 'SET_COUPON_TEMPLATES_LOADING',
  SET_EDIT_COUPON_TEMPLATE = 'SET_EDIT_COUPON_TEMPLATE',
  SET_EDIT_COUPON_TEMPLATE_FIELD_VALUE = 'SET_EDIT_COUPON_TEMPLATE_FIELD_VALUE',
  VALIDATE_EDIT_COUPON_TEMPLATE_DATA = 'VALIDATE_EDIT_COUPON_TEMPLATE_DATA',
  SET_COUPON_CREATED_RESPONSE_DATA = 'SET_COUPON_CREATED_RESPONSE_DATA',
  CLEAR_COUPON_CREATED_RESPONSE_DATA = 'CLEAR_COUPON_CREATED_RESPONSE_DATA',
  TOGGLE_PREMIUM_MODAL = 'TOGGLE_PREMIUM_MODAL',
}

// #endregion [Action Types]

// #region [Action Creators] ===========================================================================================

export const CouponTemplatesActions = {
  readCouponTemplates: (payload: IReadCouponTemplatesPayload) => ({
    type: ECouponTemplatesActionTypes.READ_COUPON_TEMPLATES,
    payload,
  }),
  readCouponTemplate: (payload: IReadCouponTemplatePayload) => ({
    type: ECouponTemplatesActionTypes.READ_COUPON_TEMPLATE,
    payload,
  }),
  readRecentCouponTemplates: (payload: IReadRecentCouponTemplatesPayload) => ({
    type: ECouponTemplatesActionTypes.READ_RECENT_COUPON_TEMPLATES,
    payload,
  }),
  readCouponTemplateCategories: (payload: IReadCouponTemplateCategoriesPayload) => ({
    type: ECouponTemplatesActionTypes.READ_COUPON_TEMPLATE_CATEGORIES,
    payload,
  }),
  readCouponTemplateCategory: (payload: IReadCouponTemplateCategoryPayload) => ({
    type: ECouponTemplatesActionTypes.READ_COUPON_TEMPLATE_CATEGORY,
    payload,
  }),
  createCouponFromTemplate: (payload: ICreateCouponFromTemplatePayload) => ({
    type: ECouponTemplatesActionTypes.CREATE_COUPON_FROM_TEMPLATE,
    payload,
  }),
  deleteRecentCouponTemplate: (payload: IDeleteRecentCouponTemplatePayload) => ({
    type: ECouponTemplatesActionTypes.DELETE_RECENT_COUPON_TEMPLATE,
    payload,
  }),
  setCouponTemplates: (payload: ISetCouponTemplatesPayload) => ({
    type: ECouponTemplatesActionTypes.SET_COUPON_TEMPLATES,
    payload,
  }),
  setCouponTemplateCategories: (payload: ISetCouponTemplatesCategoriesPayload) => ({
    type: ECouponTemplatesActionTypes.SET_COUPON_TEMPLATE_CATEGORIES,
    payload,
  }),
  unsetRecentCouponTemplate: (payload: IUnsetRecentCouponTemplatePayload) => ({
    type: ECouponTemplatesActionTypes.UNSET_RECENT_COUPON_TEMPLATE,
    payload,
  }),
  setCouponTemplatesLoading: (payload: ISetCouponTemplatesLoadingPayload) => ({
    type: ECouponTemplatesActionTypes.SET_COUPON_TEMPLATES_LOADING,
    payload,
  }),
  setEditCouponTemplate: (payload: ISetEditCouponTemplatePayload) => ({
    type: ECouponTemplatesActionTypes.SET_EDIT_COUPON_TEMPLATE,
    payload,
  }),
  setEditCouponTemplateFieldValue: (payload: ISetEditCouponTemplateFieldValuePayload) => ({
    type: ECouponTemplatesActionTypes.SET_EDIT_COUPON_TEMPLATE_FIELD_VALUE,
    payload,
  }),
  validateEditCouponTemplateData: () => ({
    type: ECouponTemplatesActionTypes.VALIDATE_EDIT_COUPON_TEMPLATE_DATA,
  }),
  setCreatedCouponResponseData: (payload: ISetCreatedCouponResponseDataPayload) => ({
    type: ECouponTemplatesActionTypes.SET_COUPON_CREATED_RESPONSE_DATA,
    payload,
  }),
  clearCreatedCouponResponseData: () => ({
    type: ECouponTemplatesActionTypes.CLEAR_COUPON_CREATED_RESPONSE_DATA,
  }),
  togglePremiumModal: (payload: ITogglePremiumModalPayload) => ({
    type: ECouponTemplatesActionTypes.TOGGLE_PREMIUM_MODAL,
    payload,
  }),
};

// #endregion [Action Creators]
