export const ID = {
  id: 'id',
  type: 'text',
  label: ' Id',
  placeholder: 'enter id...',
  validation: {
    require: 'notNull',
  },
  value: '',
};
export const LABEL = {
  id: 'label',
  type: 'text',
  label: 'Enter label',
  validation: {
    require: 'notNull',
  },
  placeholder: 'enter param...',
  value: '',
};
export const PARAM = {
  id: 'param',
  type: 'text',
  label: 'Param',
  placeholder: 'enter param...',
  value: '',
};
export const SUBTITLE = {
  id: 'subtitle',
  type: 'text',
  placeholder: 'enter subtitle...',
  label: 'Enter Subtitle',
  value: '',
};
export const PLACEHOLDER = {
  id: 'placeholder',
  type: 'text',
  label: 'Enter Placeholder',
  placeholder: 'enter placeholder...',
  value: '',
};

export const PRESENT_TEXT = {
  id: 'presentText',
  type: 'text',
  label: 'Enter present text',
  placeholder: 'present text',
  value: '',
};

export const PRESENT_STATUS_TEXT = {
  id: 'presentStatusText',
  type: 'text',
  label: 'Enter present status text',
  placeholder: 'present status text',
  value: '',
};
export const REPEAT = {
  id: 'repeat',
  type: 'switch',
  label: 'repeat',
  placeholder: 'enter repeat...',
  value: 'false',
};
export const HIDDEN = {
  id: 'hidden',
  type: 'switch',
  label: 'Hide The field',
  value: 'false',
};
export const CLEARABLE = {
  id: 'clearable',
  type: 'switch',
  label: 'is clearable',
  placeholder: 'clearable...',
  value: 'false',
};
export const MULTIPLE = {
  id: 'multiple',
  type: 'switch',
  label: 'multiple',
  placeholder: 'enter multiple...',
  value: 'false',
};
export const FORMAT24HR = {
  id: 'format24hr',
  type: 'switch',
  label: 'format 24 hour',
  value: 'false',
};
export const ISHORIZONTAL = {
  id: 'isHorizontal',
  type: 'switch',
  label: 'Design Horizontal',
  value: 'false',
};
export const SEPARATOR = {
  id: 'separator',
  type: 'text',
  label: 'separator',
  placeholder: ':',
  value: '',
};
export const DELIMITER = {
  id: 'delimiter',
  type: 'text',
  label: 'delimiter',
    subtitle: 'Enter delimiter',
  value: '',
};

export const MIN = {
  id: 'min',
  type: 'text',
  label: 'Enter Minimum number',
  placeholder: '0',
  validation: {
    require: 'isNumeric',
  },
  value: 0,
}
export const MAX = {
  id: 'max',
  type: 'text',
  label: 'Enter Maximum Number',
  placeholder: '100',
  validation: {
    require: 'isNumeric',
  },
  value: 100,
}
export const STEP = {
  id: 'step',
  type: 'text',
  label: 'Enter Step',
  placeholder: '1',
  validation: {
    require: 'isNumeric',
  },
  value: 1,
}
export const SLIDER_FROM = {
  id: 'from',
  type: 'text',
  label: 'Enter from (for both slider)',
  placeholder: '100',
  validation: {
    require: 'isNumeric',
  },
  value: 100,
}

export const COLUMN_STATUS = {
  id: 'columnStatus',
  type: 'radio',
  label: 'Enter Column Status',
  options: {
    fullwidth: 'Full Width',
    start: 'start',
    middle: 'middle',
    end: 'end',
  },
  value: 'fullwidth',
};
export const COLUMN = {
  id: 'column',
  type: 'radio',
  label: 'Enter Column',
  options: {
    '100': '100% Width',
    '75': '75% Width',
    '66': '66.6% Width',
    '50': '50% Width',
    '33': '33.3% Width',
    '25': '25% Width',
  },
  value: '100',
};

export const SLIDER_TO = {
  id: 'to',
  type: 'text',
  label: 'Enter to (for both slider)',
  placeholder: '1',
  validation: {
    require: 'isNumeric',
  },
  value: 1,
}
export const SLIDER_RANGE = {
  id: 'range',
  type: 'radio',
  label: 'Enter slider Option',
    options: {
    single: 'Single',
    double: 'Double',
  },
  value: 'single',
}
export const FILE_TYPE = {
  id: 'file_type',
  type: 'checkbox',
  label: 'Enter file types',
    placeholder: 'pdf,xml',
  options: {
    pdf: 'pdf',
    docs: 'docs',
  },
  value: 'pdf',
  validation: {
    require: 'notNull',
  }
};
export const DATE_FORMAT = {
  id: 'date_format',
  type: 'text',
  label: 'Enter date format',
  placeholder: 'YYYY-MM-DD',
};
export const LABEL_TYPE = {
  id: 'label_type',
  type: 'radio',
  label: 'Enter Label Type',
    options: {
    h1: 'h1',
    h2: 'h2',
    h3: 'h3',
    h4: 'h4',
    h5: 'h5',
    h6: 'h6',
    p: 'p',
  },
  value: 'h1',
}
export const PASSWORD_ACTION = {
  id: 'action',
  type: 'radio',
  label: 'Enter Action',
  options: {
    login: 'Login',
    registration: 'Registration',
  },
  value: 'login',
  validation: {
    require: 'notNull',
  }
};
export const BUTTON_GET_ALL_DATA = {
  id: 'getallData',
  type: 'switch',
  label: 'Get All Data',
  value: 'false',
};
export const BUTTON_GET_FORM_DATA = {
  id: 'getFormData',
  type: 'switch',
  label: 'Get Form Data',
  value: 'false',
};
export const BUTTON_FLUID_CONTROL = {
  id: 'fullWidthControl',
  type: 'checkbox',
  options: {
    reuseFluidButton: 'Enable',
  },
  label: 'Enable Fluid Button',
  value: '',
};
export const BUTTON_STYLE = {
  id: 'className',
  type: 'radio',
  label: 'Enter Style',
  options: {
    reuseButton: 'Normal button',
    reuseFlatButton: 'Flat Button',
    reuseOutlineButton: 'Outline Button',
    reuseOutlineFlatButton: 'Flat Outline Button',
  },
  value: 'reuseButton',
};
export const A_HREF = {
  id: 'href',
  type: 'text',
  label: 'Enter Href',
  validation: {
    require: 'isURL',
  },
  placeholder: 'enter Href...',
  value: '',
};
export const A_TARGET = {
  id: 'target',
  type: 'radio',
  label: 'Enter Target',
  options: {
    _blank: 'Blank',
    _self: 'Self',
    _parent: 'Parent',
  },
  value: '_blank',
};
export const GROUP_SELECTION_TYPE = {
  id: 'selectionType',
  type: 'radio',
  label: 'Enter type of show',
  options: {
    showAll: 'Show All Items',
    showAllButton: 'show All Button',
    showMore: 'show More Button',
  },
  value: 'showAll',
};
export const GROUP_STEP = {
  id: 'step',
  type: 'minmaxbutton',
  label: 'Select Steps',
  value: 1000,
  step: 1,
  min: 1,
  max: 1000,
};
export const GROUP_COLUMNS = {
  id: 'columns',
  type: 'minmaxbutton',
  label: 'No of options in columns',
  value: 1,
  step: 1,
  min: 1,
  max: 4,
};

export const paginationTotal = {
  id: 'countTotal',
  type: 'minmaxbutton',
  label: 'Total items',
  value: 1,
  step: 1,
  min: 1,
  max: 100,
}

export const defaultPageItem = {
  id: 'countPageItem',
  type: 'minmaxbutton',
  label: 'items in Page',
  value: 1,
  step: 1,
  min: 1,
  max: 100,
}
export const TAGS_RESTRICT_NEW = {
  id: 'restrictNew',
  type: 'switch',
  label: 'Restrict New Data',
  value: 'false',
}
export const TAGS_REMOVE_TEXT_FIELD = {
  // id: 'restrictNew',
  id: 'restrictInput',
  type: 'switch',
  label: 'Hide Tag Search Field',
  value: 'false',
}
export const LABEL_POSITION = {
  id: 'labelPosition',
  type: 'radio',
  label: 'Select Text Alignment',
  options: {
    posLeft: 'Left',
    posCenter: 'Center',
    posRight: 'Right',
  },
  value: 'posLeft',
};
