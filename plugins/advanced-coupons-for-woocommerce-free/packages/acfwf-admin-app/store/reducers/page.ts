// #region [Imports] ===================================================================================================

// Actions
import { ISetStorePageActionPayload, EPageActionTypes } from "../actions/page";

// #endregion [Imports]

// #region [Reducer] ===================================================================================================

const reducer = (page: string = "", action: { type: string; payload: any }) => {
  switch (action.type) {
    case EPageActionTypes.SET_STORE_PAGE: {
      const { data } = action.payload as ISetStorePageActionPayload;

      return data;
    }

    default:
      return page;
  }
};

export default reducer;

// #endregion [Reducer]
