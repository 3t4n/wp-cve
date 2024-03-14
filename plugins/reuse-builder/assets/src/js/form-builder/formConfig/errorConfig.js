const validationFields = [
  {
    id: 'require',
    type: 'checkbox',
    label: 'Button Style',
    subtitle: 'Choose the button style',
    options: {
      noValidation: 'No Validitation',
      notNull: 'Field should not be null',
    },
    value: 'noValidation',
  },
  {
    'id': 'message',
    'type': 'text',
    'label': 'Type error message',
    'param': 'text',
    'value': '',
    'placeholder': 'enter error message...',
  }
];
const validationFieldsText = [
  {
    id: 'require',
    type: 'checkbox',
    label: 'Button Style',
    param: 'radio',
    subtitle: 'Choose the button style',
    options: {
      noValidation: 'No Validitation',
      notNull: 'Field should not be null',
      isEmail: 'Field should not be email',
      isURL: 'Field should not be a URL',
      isNumeric: 'Field should not be Numeric',
    },
    value: 'noValidation',
  },
  {
    'id': 'message',
    'type': 'text',
    'label': 'Type error message',
    'param': 'text',
    'value': '',
    'placeholder': 'enter error message...',
  }
];
export {
  validationFields,
  validationFieldsText,
}
