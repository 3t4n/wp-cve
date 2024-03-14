// #region [Imports] ===================================================================================================

import { useState } from 'react';
import { Checkbox } from 'antd';
import { IFieldComponentProps } from '../../../../types/couponTemplates';

// #endregion [Imports]

// #region [Variables] =================================================================================================
// #endregion [Variables]

// #region [Interfaces]=================================================================================================
// #endregion [Interfaces]

// #region [Component] =================================================================================================

const CheckboxField = (props: IFieldComponentProps) => {
  const { defaultValue, editable, fixtures, onChange } = props;
  const [checked, setChecked] = useState(defaultValue === 'yes');
  return (
    <div>
      <Checkbox
        checked={checked}
        disabled={!editable}
        onChange={(e: any) => {
          setChecked(e.target.checked);
          onChange(e.target.checked ? 'yes' : 'no');
        }}
      />
      <span>{fixtures.description}</span>
    </div>
  );
};

export default CheckboxField;

// #endregion [Component]
