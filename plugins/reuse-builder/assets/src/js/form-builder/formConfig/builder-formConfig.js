import * as generalOptions from './builder-config-common';
import { validationFieldsText } from './errorConfig';

export const fieldLabel = {
  type:'label',
  label: 'Label',
  showHeader: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.LABEL_TYPE,
    generalOptions.LABEL_POSITION,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldATag = {
  type:'atag',
  label: 'A Tag',
  showHeader: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.A_HREF,
    generalOptions.A_TARGET,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldText = {
  type:'text',
  label: 'Text',
  validationRequire: validationFieldsText,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.PLACEHOLDER,
    generalOptions.DELIMITER,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldTextRepeat = {
  type:'text-repeat',
  label: 'Text Repeat',
  showHeader: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.PLACEHOLDER,
    generalOptions.DELIMITER,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldTextArea = {
  type:'textarea',
  label: 'Text Area',
  validationRequire: validationFieldsText,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.PLACEHOLDER,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};

export const fieldTextEditor = {
  type:'texteditor',
  label: 'Text Editor',
  validationRequire: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.PLACEHOLDER,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldIconPicker = {
  type:'iconpicker',
  label: 'Icon Picker',
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.PLACEHOLDER,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldFileUpload = {
  type:'fileupload',
  label: 'File Upload',
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.MULTIPLE,
    generalOptions.FILE_TYPE,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldImageUpload = {
  type:'imageupload',
  label: 'Image Upload',
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.MULTIPLE,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldSwitch = {
  type:'switchalt',
  label: 'Switch',
  validationRequire: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldSwitchAlert = {
  type:'switchalt',
  label: 'Switch Alter',
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
  //  generalOptions.VALUE_SWITCH,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldMapAutoComplete = {
  type:'mapautocomplete',
  label: 'Map Auto Complete',
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.PLACEHOLDER,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};

export const fieldDatePicker = {
  type:'datepicker',
  label: 'Date Picker',
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.DATE_FORMAT,
    generalOptions.PLACEHOLDER,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};

export const fieldDatePickerRange = {
  type:'datepickerrange',
  label: 'Date Picker  Range',
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.DATE_FORMAT,
    generalOptions.PLACEHOLDER,
    generalOptions.SEPARATOR,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldGoogleRecaptcha = {
  type:'recaptcha',
  label: 'Google Recaptcha',
  preValueRequire: false,
  fields: [
    generalOptions.ID,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
    {
      id: 'site_key',
      type: 'text',
      label: 'Enter Site Key',
      placeholder: 'site_key',
      validation: {
        require: 'notNull',
      }
    }
  ],
};
export const fieldMinMax = {
  type:'minmaxbutton',
  label: 'Min Max Button',
  validationRequire: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.MIN,
    generalOptions.MAX,
    generalOptions.STEP,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
};
export const fieldSelectBox = {
  type:'select',
  label: 'Select Box',
  data: true,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.MULTIPLE,
    generalOptions.CLEARABLE,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldCheckBox = {
  type:'checkbox',
  label: 'Check Box',
  data: true,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.GROUP_SELECTION_TYPE,
    generalOptions.GROUP_STEP,
    generalOptions.GROUP_COLUMNS,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldRadioBox = {
  type: 'radio',
  label: 'Radio',
  validationRequire: false,
  data: true,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.GROUP_SELECTION_TYPE,
    generalOptions.GROUP_STEP,
    generalOptions.GROUP_COLUMNS,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldSlider = {
  type: 'slideralt',
  label: 'Slider',
  validationRequire: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.SUBTITLE,
    generalOptions.SLIDER_RANGE,
    generalOptions.SLIDER_TO,
    generalOptions.SLIDER_FROM,
    generalOptions.MIN,
    generalOptions.MAX,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldColorPicker = {
  type: 'colorpicker',
  label: 'Color Picker',
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.SUBTITLE,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldGeoBox = {
  type:'geobox',
  label: 'Geo Box',
  validationRequire: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.PLACEHOLDER,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldTimePeriod = {
  type:'timePeriod',
  label: 'Time Period',
  validationRequire: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.SEPARATOR,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldOpeningHour = {
  type:'openingHour',
  label: 'Opening Hour',
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.FORMAT24HR,
    generalOptions.SEPARATOR,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldComboBox = {
  type:'combobox',
  label: 'Combo Box',
  preValueRequire: false,
  data: 'Load',
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldComboSelect = {
  type:'comboselect',
  label: 'Combo Select Box',
  preValueRequire: false,
  data: 'Load',
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.SUBTITLE,
    generalOptions.CLEARABLE,
    generalOptions.ISHORIZONTAL,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldPassword = {
  type: 'password',
  label: 'Password',
  action: 'login',
  preValueRequire: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.PASSWORD_ACTION,
    generalOptions.MIN,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldBundle = {
  type:'bundle',
  label: 'Combo Box',
  showHeader: false,
  preValueRequire: false,
  data: 'Load',
  validationRequire: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}
export const fieldButton = {
  type:'compoundbutton',
  label: 'Button',
  showHeader: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.BUTTON_GET_ALL_DATA,
    generalOptions.BUTTON_GET_FORM_DATA,
    generalOptions.BUTTON_FLUID_CONTROL,
    generalOptions.BUTTON_STYLE,
    generalOptions.HIDDEN,
    generalOptions.COLUMN_STATUS,
    generalOptions.COLUMN,
  ],
}

export const fieldPagination = {
  type:'pagination',
  label: 'Pagination',
  showHeader: false,
  fields: [
    generalOptions.ID,
    generalOptions.paginationTotal,
    generalOptions.defaultPageItem,
  ],
}
export const fieldTags = {
  type:'tags',
  label: 'Tags',
  showHeader: false,
  fields: [
    generalOptions.ID,
    generalOptions.LABEL,
    generalOptions.TAGS_RESTRICT_NEW,
    generalOptions.TAGS_REMOVE_TEXT_FIELD,
  ],
}
