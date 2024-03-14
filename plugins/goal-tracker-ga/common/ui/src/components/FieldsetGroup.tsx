import classNames from 'classnames';
import React from 'react';

export type FieldsetGroupProps = {
  className?: string;
  children: React.ReactNode;
};

export const FieldsetGroup: React.FC<FieldsetGroupProps> = props => {
  const children = props.children;
  return (
    <div
      data-component="FieldsetGroup"
      className={classNames(
        'mt-6 sm:mt-5',
        'space-y-6 sm:space-y-5',
        'divide-y divide-gray-200',
        props.className,
      )}
    >
      {children}
    </div>
  );
};
