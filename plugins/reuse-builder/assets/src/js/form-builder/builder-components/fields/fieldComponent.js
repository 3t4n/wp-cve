import React from 'react';
import { default as swal } from 'sweetalert';
import clone from 'clone';
import FieldsComponent from './index';
import Styles from './fieldComponent.less';

export default function FieldComponent(props) {
  const { dragItem, changeView, editField, deleteComponent, deleteField, id, tempField } = props;
  let clicked = false;
  const DeleteButton = deleteComponent;
  const onFieldClick = () => {
    if (!clicked) {
      changeView('edit_field');
      editField(dragItem);
    }
  };
  const handleDelete = () => {
    const title = dragItem.label ? dragItem.label : dragItem.id;
    swal({
      title: `Are you sure to delete field ${title}?`,
      text: "You won't be able to revert this!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    },
    () => {
      swal(
        ` ${title} Deleted!`,
        `${title} has been deleted.`,
        'success'
      );
      clicked = true;
      deleteField(dragItem);
    });
  };
  let bundleItem = '';
  if (dragItem.type === 'bundle') {
    let parentId = clone(dragItem.parentId);
    parentId.push(dragItem.id);
    const fieldsComponentOptions = {
      ...props,
      fields: dragItem.fields,
      parentId,
    };
    bundleItem = <div className={"scwpFormAddFieldsBundle"}>
      <FieldsComponent {...fieldsComponentOptions}/>
    </div>;
  }
  return (<div className="scwpFormFieldsNameDel">
    <span className="scwpFormFieldsName"  onClick={onFieldClick}>{dragItem.id}</span>
    <button type ="button"  onClick={handleDelete} className="scwpDeleteFieldsBtn"/>
    {bundleItem}
  </div>);
}
