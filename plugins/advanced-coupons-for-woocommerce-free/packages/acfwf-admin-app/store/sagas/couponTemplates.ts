// #region [Imports] ===================================================================================================

// Libraries
import 'cross-fetch/polyfill';
import { put, call, takeEvery } from 'redux-saga/effects';

// Actions
import {
  IReadCouponTemplatesPayload,
  IReadCouponTemplatePayload,
  IReadRecentCouponTemplatesPayload,
  IReadCouponTemplateCategoriesPayload,
  IReadCouponTemplateCategoryPayload,
  ICreateCouponFromTemplatePayload,
  IDeleteRecentCouponTemplatePayload,
  CouponTemplatesActions,
  ECouponTemplatesActionTypes,
} from '../actions/couponTemplates';

// Helpers
import axiosInstance, { getCancelToken } from '../../helpers/axios';

// #endregion [Imports]

// #region [Sagas] =====================================================================================================

export function* readCouponTemplatesSaga(action: { type: string; payload: IReadCouponTemplatesPayload }): any {
  const { isReview = false, processingCB, successCB, failCB } = action.payload;

  try {
    if (typeof processingCB === 'function') processingCB();

    const response = yield call(() =>
      axiosInstance.get(`coupons/v1/templates`, {
        cancelToken: getCancelToken('coupon_templates'),
        params: isReview ? { is_review: 1 } : {},
      })
    );

    if (response && response.data) {
      yield put(
        CouponTemplatesActions.setCouponTemplates({
          data: response.data,
          type: isReview ? 'review' : 'main',
        })
      );

      if (typeof successCB === 'function') successCB(response);
    }
  } catch (error) {
    if (typeof failCB === 'function') failCB({ error });
  }
}

export function* readCouponTemplateSaga(action: { type: string; payload: IReadCouponTemplatePayload }): any {
  const { id, isReview, processingCB, successCB, failCB } = action.payload;

  try {
    if (typeof processingCB === 'function') processingCB();

    const response = yield call(() =>
      axiosInstance.get(`coupons/v1/templates/${id}`, {
        cancelToken: getCancelToken('coupon_template'),
        params: isReview ? { is_review: 1 } : {},
      })
    );

    if (response && response.data) {
      response.data.fields = response.data.fields.map((field: any) => {
        field.value = field.pre_filled_value ?? '';
        return field;
      });

      if (typeof successCB === 'function') successCB(response);
    }
  } catch (error) {
    if (typeof failCB === 'function') failCB({ error });
  }
}

export function* readRecentCouponTemplatesSaga(action: {
  type: string;
  payload: IReadRecentCouponTemplatesPayload;
}): any {
  const { processingCB, successCB, failCB } = action.payload;

  try {
    if (typeof processingCB === 'function') processingCB();

    const response = yield call(() =>
      axiosInstance.get(`coupons/v1/templates/recent`, {
        cancelToken: getCancelToken('recent_coupon_templates'),
      })
    );

    if (response && response.data) {
      yield put(
        CouponTemplatesActions.setCouponTemplates({
          data: response.data,
          type: 'recent',
        })
      );

      if (typeof successCB === 'function') successCB(response);
    }
  } catch (error) {
    if (typeof failCB === 'function') failCB({ error });
  }
}

export function* readCouponTemplateCategoriesSaga(action: {
  type: string;
  payload: IReadCouponTemplateCategoriesPayload;
}): any {
  const { processingCB, successCB, failCB } = action.payload;

  try {
    if (typeof processingCB === 'function') processingCB();

    const response = yield call(() =>
      axiosInstance.get(`coupons/v1/templates/categories`, {
        cancelToken: getCancelToken('coupon_template_categories'),
      })
    );

    if (response && response.data) {
      yield put(
        CouponTemplatesActions.setCouponTemplateCategories({
          data: response.data,
        })
      );

      if (typeof successCB === 'function') successCB(response);
    }
  } catch (error) {
    if (typeof failCB === 'function') failCB({ error });
  }
}

export function* readCouponTemplateCategorySaga(action: {
  type: string;
  payload: IReadCouponTemplateCategoryPayload;
}): any {
  const { slug, processingCB, successCB, failCB } = action.payload;

  try {
    if (typeof processingCB === 'function') processingCB();

    const response = yield call(() =>
      axiosInstance.get(`coupons/v1/templates/categories/${slug}`, {
        cancelToken: getCancelToken('coupon_template_category'),
      })
    );

    if (response && response.data) {
      yield put(
        CouponTemplatesActions.setCouponTemplates({
          data: response.data,
        })
      );

      if (typeof successCB === 'function') successCB(response);
    }
  } catch (error) {
    if (typeof failCB === 'function') failCB({ error });
  }
}

export function* createCouponFromTemplateSaga(action: {
  type: string;
  payload: ICreateCouponFromTemplatePayload;
}): any {
  const { data, processingCB, successCB, failCB } = action.payload;

  try {
    if (typeof processingCB === 'function') processingCB();

    const response = yield call(() =>
      axiosInstance.post(`coupons/v1/templates`, data, {
        cancelToken: getCancelToken('coupon_template_create'),
      })
    );

    if (response && response.data) {
      if (typeof successCB === 'function') successCB(response);
    }
  } catch (error) {
    if (typeof failCB === 'function') failCB({ error });
  }
}

export function* deleteRecentCouponTemplateSaga(action: {
  type: string;
  payload: IDeleteRecentCouponTemplatePayload;
}): any {
  const { id, processingCB, successCB, failCB } = action.payload;

  try {
    if (typeof processingCB === 'function') processingCB();

    const response = yield call(() =>
      axiosInstance.delete(`coupons/v1/templates/recent/${id}`, {
        cancelToken: getCancelToken('coupon_template_delete'),
      })
    );

    if (typeof successCB === 'function') successCB(response);
  } catch (error) {
    if (typeof failCB === 'function') failCB({ error });
  }
}

// #endregion [Sagas]

// #region [Action Listeners] ==========================================================================================

export const actionListener = [
  takeEvery(ECouponTemplatesActionTypes.READ_COUPON_TEMPLATES, readCouponTemplatesSaga),
  takeEvery(ECouponTemplatesActionTypes.READ_COUPON_TEMPLATE, readCouponTemplateSaga),
  takeEvery(ECouponTemplatesActionTypes.READ_RECENT_COUPON_TEMPLATES, readRecentCouponTemplatesSaga),
  takeEvery(ECouponTemplatesActionTypes.READ_COUPON_TEMPLATE_CATEGORIES, readCouponTemplateCategoriesSaga),
  takeEvery(ECouponTemplatesActionTypes.READ_COUPON_TEMPLATE_CATEGORY, readCouponTemplateCategorySaga),
  takeEvery(ECouponTemplatesActionTypes.CREATE_COUPON_FROM_TEMPLATE, createCouponFromTemplateSaga),
  takeEvery(ECouponTemplatesActionTypes.DELETE_RECENT_COUPON_TEMPLATE, deleteRecentCouponTemplateSaga),
];

// #endregion [Action Listeners]
