import clone from 'clone';

const initialState = {
  fields:[],
  tempField: null,
};

export default function formBuilder(state = initialState, action) {
  const tempState = clone(state);
  let findIndex = -1;
  let fields = [];
  switch (action.type) {
    case 'CREATE_TEMP_FIELD':
      tempState.formBuilderPanel.tempField = action.field;
      tempState.formBuilderPanel.addFieldButton = false;
      return {
        ...tempState,
      };
    case 'DELETE_FIELD':
      return {
        ...tempState,
        fields: updateFieldTree(action, tempState.fields, 0),
      };
    case 'EDIT_FIELD':
      return {
        ...tempState,
        tempField: action.field,
      };
    case 'MOVE_FIELDS':
      return {
        ...tempState,
        fields: updateFieldTree(action, tempState.fields, 0),
      };
    case 'UPDATE_FIELD':
      return {
        ...state,
        fields: updateFieldTree(action, tempState.fields, 0),
        tempField: null,
      };
    case 'SET_FIELDS':
      fields = clone(action.fields);
      return {
        ...state,
        fields,
        tempField: null,
      };
    default: {
      return state;
    }
  }
}

export function setFields(fields) {
   return ({
     type: 'SET_FIELDS',
     fields,
   });
 }

export function createTempField(field) {
   return ({
     type: 'CREATE_TEMP_FIELD',
     field,
   });
 }

export function editField(field) {
   return ({
     type: 'EDIT_FIELD',
     field,
   });
 }

export function updateField(fieldId, field) {
   return ({
     type: 'UPDATE_FIELD',
     fieldId,
     field,
   });
 }
export function deleteField(field) {
   return ({
     type: 'DELETE_FIELD',
     field,
   });
 }
export function moveFields(fields, field) {
   return ({
     type: 'MOVE_FIELDS',
     fields,
     field,
   });
 }

 function updateFieldTree(action, fields, index) {
    const { fieldId, type, field } = action;
    const parentId = field.parentId;
    if (index === parentId.length) {
      if(type === 'UPDATE_FIELD') {
        const findIndex = _.findIndex(fields, { id: fieldId });
        if (findIndex !== -1) {
          fields[findIndex] = action.field;
        } else  {
          fields.push(action.field);
        }
      } else if (type === 'DELETE_FIELD') {
        let newFields = [];
        fields.forEach( newField => {
          if (field.id !== newField.id) {
            newFields.push(newField);
          }
        });
        fields = newFields;
      } else if (type === 'MOVE_FIELDS') {
        fields = action.fields;
      }
    } else {
      const findIndex = _.findIndex(fields, { id: parentId[index] });
      fields[findIndex].fields = updateFieldTree(action, fields[findIndex].fields, index + 1);
    }
    return fields;
 }
