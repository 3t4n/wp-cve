import React, { Component } from 'react';
const ReuseForm = __REUSEFORM__;
import { randomNumber } from 'utility/helper';

export default class PostTypeSelect extends Component {
  constructor(props) {
    super(props);
  }

  render() {
    const { props } = this;
    let options = {};
    switch(props.postType) {
      case 'user':
        options.registration_user = 'registration_user';
        options.user_profile_update = 'user_profile_update';
        options.login_user = 'login_user';
        options.reset_password = 'reset_password';
        options.set_new_password = 'set_new_password';
        options.change_password = 'change_password';
        break;
      default:
        options = { save_post: 'save_post' };
    }
    const handleTriggerType = (data) => {
      if (data === undefined)
        return;
      if (data['global_setting_trigger_type'])
        props.updateData('triggerType', data['global_setting_trigger_type']);
    }
    const reuseOption = {
      reuseFormId: `builder-globalSettings-${randomNumber()}`,
      fields: [
      {
        id: 'global_setting_trigger_type',
        type: 'select',
        label: 'Trigger Type',
        param: 'select',
        multiple: false,
        clearable: false,
        subtitle: '',
        options,
        value: props.preValue,
      }],
      getUpdatedFields: handleTriggerType,
      preValue: {},
      errorMessages: {},
    };
    return (<div className="">
      <ReuseForm { ...reuseOption } />
    </div>);
  }
}
