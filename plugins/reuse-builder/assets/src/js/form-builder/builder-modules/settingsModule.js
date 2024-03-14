import { createArray, createEffectField, createLogic, searchObjectIndex } from './helperModule';
import clone from 'clone';
import { http } from 'utility/helper';

const init = {
    response: {
      postType: null,
      meta_keys: null,
      taxonomies: null,
    },
    settings:{
      postType: null,
      trigger: null,
      allowPost: false,
      fieldSettings: [],
    }
};

export default function settingsReducer(state = init, action) {
  let { settings } = state;
  let { fieldSettings } = settings;
  switch (action.type) {
    case 'POST_TYPE_SELECTED':
      settings.postType = action.postType;
      settings.trigger= null;
      settings.fieldSettings = [];
      return {
        response: clone(action.allData),
        settings,
      }
    case 'UPDATE_FIELD_SETTINGS':
      let tempSettings = clone(settings);
      let inserted = false;
      // console.log(action.data);
      fieldSettings.map(function(singleField, index){
        if (singleField.fieldKey === action.data.fieldKey) {
          tempSettings.fieldSettings[index] = clone(action.data);
          inserted = true;
        }
      });
      if (inserted === false) {
        tempSettings.fieldSettings.push(action.data);
      }
      return {
        ...state,
        settings: clone(tempSettings),
      }
    case 'TRIGGER_TYPE_CHANGE':
      settings.trigger = action.triggerType;
      return {
        settings,
        ...state,
      }
    case 'ALLOW_POST_TYPE':
      settings.allowPost = action.allowPost;
      return {
        settings,
        ...state,
      }
    case 'INIT_SETTINGS':
      const { fields, data } = action;
      const settingsArray = [];
      fields.map(function(field){
        const fieldSettings = clone(data.settings.fieldSettings);
        let isThere = false;
        fieldSettings.map(function(setting){
          if (setting.fieldKey === field.id) {
            settingsArray.push(setting);
          }
        });
      });
      data.settings.fieldSettings = clone(settingsArray);
      return {
        ...data,
      }
    default:
      return state;
  }
}


export function postChangeType(data) {
  return (dispatch, getState) => {
    let ajaxData = {};
    switch(data) {
      case 'user':
        ajaxData.action_type = 'fetch_user_data';
        break;
      default:
        ajaxData.action_type = 'fetch_post_data';
    }
    ajaxData.action = REUSEB_AJAX_DATA.action;
    ajaxData.nonce = REUSEB_AJAX_DATA.nonce;
    ajaxData.postType = data;
    http.post(ajaxData)
      .end(function(err, res){
        return dispatch(postChangeResponse(res, data));
    });
  }
}

export function postChangeResponse(data, postType) {
  return {
    type: 'POST_TYPE_SELECTED',
    allData: JSON.parse(data.text),
    postType,
  }
}

export function triggerTypeChange(triggerType) {
  return {
    type: 'TRIGGER_TYPE_CHANGE',
    triggerType,
  }
}

export function updateFieldSettings(data) {
  return {
    type: 'UPDATE_FIELD_SETTINGS',
    data,
  }
}

export function allowPostChange(allowPost) {
  return {
    type: 'ALLOW_POST_TYPE',
    allowPost,
  }
}

export function setGlobalSettings(data, fields) {
  return {
    type: 'INIT_SETTINGS',
    data,
    fields,
  }
}
