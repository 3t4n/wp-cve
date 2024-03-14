import React, { Component } from 'react';
import Collapse from 'react-collapse';
const ReuseForm = __REUSEFORM__;
import { randomNumber } from 'utility/helper';

export default class PostTypeSelect extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { props } = this;
    const options = REUSEB_ADMIN.postTypes ? REUSEB_ADMIN.postTypes : {};
    options.user = 'user';
    options.option = 'option';
    options.contact_form = 'contact_form';
    const handlePostType = (data) => {
      if (data === undefined)
        return;
      if (data['global_setting_postTypes'])
        props.updateData('postType', data['global_setting_postTypes']);
    }
    const reuseOption = {
      reuseFormId: `builder-globalSettings-${randomNumber()}`,
      fields: [
      {
        id: 'global_setting_postTypes',
        type: 'select',
        label: 'Post Type',
        param: 'select',
        multiple: false,
        clearable: false,
        subtitle: '',
        options,
        value: props.preValue,
      }],
      getUpdatedFields: handlePostType,
      preValue: {},
      errorMessages: {},
    };
    return (<div className="">
      <ReuseForm { ...reuseOption } />
    </div>);
  }
}
