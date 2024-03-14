import React, { Dispatch, SetStateAction } from 'react';
import { useHelpSliderContext } from '../context/HelpSliderContext';
import classNames from 'classnames';
import QuestionMarkCircleIcon from '@heroicons/react/solid/QuestionMarkCircleIcon';
import { Switch } from '@headlessui/react';

export type HeaderTitleProperties = {
  children?: React.ReactNode;
  title: string;
  titleHelper: string;
  helpComponent?: React.ComponentType;
  helpTitle?: string;
  settingsState: { [key: string]: boolean };
  setSettingsState: Dispatch<SetStateAction<T>>;
  handleCheckboxChange: (
    checked: boolean,
    name: keyof T,
    setState: Dispatch<SetStateAction<T>>,
  ) => void;
  togglePropertyName: string;
};

export const HeaderTitleWithToggle: React.FC<HeaderTitleProperties> = props => {
  const {
    children,
    title,
    titleHelper,
    helpComponent,
    helpTitle,
    settingsState,
    handleCheckboxChange,
    setSettingsState,
    togglePropertyName,
  } = props;

  const { setOpenHelpSlider, setTitleHelpSlider, setComponent } =
    useHelpSliderContext();

  const handleClick = () => {
    if (helpComponent && helpTitle) {
      setTitleHelpSlider(helpTitle);
      setComponent(helpComponent);
      setOpenHelpSlider(true);
    }
  };

  return (
    <header
      data-component="HeaderTitle"
      className="flex items-center space-x-4"
    >
      <div className="flex-grow">
        <div className="flex items-center">
          <h3 className="text-2xl leading-6 font-medium text-gray-900">
            {title}
          </h3>
          {helpComponent && (
            <button onClick={handleClick} className="ml-2">
              <QuestionMarkCircleIcon
                className="h-8 w-8 inline hover:text-brand-primary"
                aria-hidden="true"
              />
            </button>
          )}
        </div>
        <p className="mt-2 max-w-2xl text-base text-gray-600">{titleHelper}</p>
        {children}
      </div>
      <Switch
        id="gaDebug"
        name="gaDebug"
        checked={settingsState[togglePropertyName]}
        onChange={checked =>
          handleCheckboxChange(checked, togglePropertyName, setSettingsState)
        }
        className={classNames(
          settingsState[togglePropertyName]
            ? 'bg-brand-primary'
            : 'bg-slate-500',
          'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
        )}
      >
        <span className="sr-only">GA4 Debug View</span>
        <span
          aria-hidden="true"
          className={classNames(
            settingsState[togglePropertyName]
              ? 'translate-x-5'
              : 'translate-x-0',
            'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
          )}
        />
      </Switch>
    </header>
  );
};
