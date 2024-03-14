import classNames from 'classnames';
import React from 'react';
import ProLabel from './buttons/ProLabel';

export type FieldsetProps = {
  children?: React.ReactNode;
  id: string;
  label: string;
  description: string;
  isPrimary: boolean;
  isPro?: boolean;
};

export const Fieldset: React.FC<FieldsetProps> = props => {
  const { children, id, label, description, isPrimary, isPro } = props;
  return (
    <fieldset
      data-component="Fieldset"
      role="group"
      aria-labelledby="label-track-Links"
      className={classNames(
        'sm:grid sm:grid-cols-3 sm:gap-4',
        'sm:items-start',
        'sm:border-t sm:border-gray-200 sm:pt-5',
      )}
    >
      <label
        htmlFor={id}
        className={classNames(
          'font-medium text-gray-900',
          isPrimary ? 'text-base md:text-2xl' : 'text-sm md:text-base',
        )}
        id={`label-${id}`}
      >
        {label}
        {isPro && <ProLabel />}
        <p className="text-gray-500 mt-4 text-sm">{description}</p>
      </label>

      {children && <div className="mt-4 sm:mt-0 sm:col-span-2">{children}</div>}
    </fieldset>
  );
};
