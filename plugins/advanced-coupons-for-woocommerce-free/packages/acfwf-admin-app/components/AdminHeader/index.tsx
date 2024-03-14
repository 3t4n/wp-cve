// #region [Imports] ===================================================================================================

// Libraries
import { useEffect, useState } from 'react';

// Components
import Logo from '../Logo';

// #endregion [Imports]

// #region [Interfaces]=================================================================================================

interface IProps {
  title?: string;
  className?: string;
  description?: string;
  hideUpgrade?: boolean;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const AdminHeader = (props: IProps) => {
  const { title, className, description } = props;
  const hideUpgrade = props.hideUpgrade ?? false;

  return (
    <div className={`page-header ${className ?? ''}`}>
      <Logo hideUpgrade />
      {!!title && <h1>{title}</h1>}
      {!!description && <p>{description}</p>}
    </div>
  );
};

export default AdminHeader;

// #endregion [Component]
