// #region [Imports] ===================================================================================================

import { Select } from 'antd';
import { IFieldComponentProps } from '../../../../types/couponTemplates';
import useUserRoleOptions from '../hooks/useUserRoleOptions';

// #endregion [Imports]

// #region [Variables] =================================================================================================
// #endregion [Variables]

// #region [Interfaces]=================================================================================================
// #endregion [Interfaces]

// #region [Component] =================================================================================================

const UserRoles = (props: IFieldComponentProps) => {
  const { defaultValue, editable, fixtures, onChange } = props;
  const { options, loading } = useUserRoleOptions();

  return (
    <div>
      <Select
        mode="multiple"
        placeholder={fixtures?.placeholder}
        disabled={!editable}
        defaultValue={defaultValue.length ? defaultValue : undefined}
        options={options}
        loading={loading}
        onChange={(value) => onChange(value)}
      />
    </div>
  );
};

export default UserRoles;

// #endregion [Component]
