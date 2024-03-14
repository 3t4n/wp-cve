import classNames from 'classnames';
import React from 'react';

export type SectionProperties = {
  children?: React.ReactNode
}

export const Section: React.FC<SectionProperties> = (props) => {
  const children = props.children
  return (
    <section
      data-component="Section"
      className={classNames(
        // 'max-w-5xl',
        'w-full',
        'py-10 px-6',
        'space-y-8',
        'divide-y divide-gray-200 rounded-md',
        'border border-gray-200',
        // 'mt-10 ml-5 mb-10',
        'bg-white shadow',
      )}
    >
      {children}
    </section>
  );
}