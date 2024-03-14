import React, { Component } from 'react';
import FieldComponent from './fieldComponent';
import MoveComponent from './moveComponent';
import { randomNumber } from 'utility/helper';
import { StickyContainer, Sticky } from 'react-sticky';

const ReuseForm = __REUSEFORM__;
const  Redrag  = __REUSEFORM_COMPONENT__['redrag'];

export default function FieldsComponent(props) {
  const { fields, tempField, parentId,
    updateField, editField, changeView, moveFields, deleteField } = props;

  const options = {
    dragItems: fields,
    componentName: FieldComponent,
    moveComponent:MoveComponent,
    componentStyle: {},
    item: { id: 'builderFields' },
    styles: {},
    className: 'scwpFormFieldsWrapper',
    singleItemClassName: 'scwpFormSingleFields',
    updateData: (item, data) => {
      changeView('preview');
      moveFields(data, data[0]);
    },
    editField,
    changeView,
    deleteField,
    tempField,
    moveFields,
  }
  const addField = () => {
    changeView('add_field');
    editField({parentId});
  }

  const addBundle = () => {
    changeView('edit_field');
    const id = `bundle_${randomNumber(10, 99)}`;
    editField({
      id,
      label: id,
      type: 'bundle',
      fields: [],
      parentId,
    });
  }
  return (
    <StickyContainer style={{ height: '100%' }}>

      {parentId.length > 0 ? '' :
        <Sticky topOffset={-32}>
          {
            ({ isSticky, wasSticky, style, distanceFromTop, distanceFromBottom, calculatedHeight }) => {
              return (
                <div className="scwpAddFieldsBundleWrapper" style={{
                  ...style,
                  backgroundColor: '#fafafa',
                  marginTop: 32,
                  zIndex: 10,
                }}>
                  <button type="button" className="scwpAddBtn scwpAddFormFields" onClick={addField}>Field</button>
                  <button type="button" className="scwpAddBtn scwpAddFormBundle" onClick={addBundle}>Bundle</button>
                </div>
              )
            }
          }
        </Sticky>
      }

      {fields.length > 0 ? <Redrag {...options} /> : ''}
      {parentId.length === 0 ? '' : <div className="scwpAddFieldsBundleWrapper">
        <button type="button" className="scwpAddBtn scwpAddFormFields" onClick={addField}>Field</button>
      </div>}
    </StickyContainer>);
}
