export const MANUAL_DATA = {
  id: 'option',
  label: 'Enter Options',
  type: 'bundle',
  fields: [
    {
      id: 'key',
      type: 'text',
      label: 'Enter key',
      param: 'key',
      placeholder: 'key',
      value: '',
    },
    {
      id: 'value',
      type: 'text',
      label: 'Enter value',
      param: 'value',
      placeholder: 'value',
      value: '',
    },
  ],
}

export const POST_TYPE_SELECTION = {
  id: 'postTypes',
  type: 'select',
  label: 'Select Post Type',
  param: 'select',
  multiple: false,
  clearable: false,
  value: '',
}
export const PRELOAD = {
  id: 'preload',
  type: 'select',
  label: 'Preload Data',
  param: 'select',
  multiple: false,
  clearable: false,
  options: {
    meta_keys: 'Post Meta',
    taxonomies: 'Taxonomies',
  },
  value: 'meta_keys',
}
export const PRELOAD_ITEM = {
  id: 'preload_item',
  type: 'select',
  label: 'Preload Data Item',
  param: 'select',
  multiple: true,
  clearable: true,
  value: '',
}
