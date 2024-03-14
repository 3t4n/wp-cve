import React, { Component } from 'react';
const ReuseForm = __REUSEFORM__;
import { randomNumber } from 'utility/helper';

export default class AllowPostTypeSelect extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { props } = this;
    const handleAllowPostType = (data) => {
      if (data === undefined)
        return;
      if (data['global_setting_allowPost_type'])
        this.props.updateData('allowPost', data['global_setting_allowPost_type']);
    }
    const reuseOption = {
      reuseFormId: `builder-globalSettings-${randomNumber()}`,
      fields: [
      {
        id: 'global_setting_allowPost_type',
        type: 'switch',
        label: 'Allow Post',
        param: 'has_icon',
        subtitle: '',
        value: props.preValue,
      }],
      getUpdatedFields: handleAllowPostType,
      preValue: {},
      errorMessages: {},
    };
    return (<div className="">
      <ReuseForm { ...reuseOption } />
    </div>);
  }
}
