import React, { Component } from 'react';
import swal from 'sweetalert';
import Styles from './reuseEditPanelView.less';

export default class EditPanelView extends Component {
  constructor(props) {
    super(props);
  }
  render() {
    const { editPanelAction, field } = this.props;
    const editClicked = () => {
      editPanelAction('edit', field);
    };
    const deleteClicked = () => {
      const title = field.label ? field.label : field.id;
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
          'success',
        );
        editPanelAction('delete', field);
      });
    };
    return(<div className={`${Styles.scwpFieldControlBtns} scwpFieldControlBtns___`}>
      <button type="button" onClick={editClicked}>Edit</button>
      <button type="button" onClick={deleteClicked}>Delete</button>
    </div>)
  }
};
