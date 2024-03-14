import React from 'react';
import { useHelpSliderContext } from '../context/HelpSliderContext';
import classNames from 'classnames';
import QuestionMarkCircleIcon from '@heroicons/react/solid/QuestionMarkCircleIcon';
import ProLabel from './buttons/ProLabel';
import BetaLabel from './buttons/BetaLabel';
import InlineCTA from './buttons/InlineCTA';

export type HeaderTitleProperties = {
  children?: React.ReactNode;
  title: string;
  titleHelper: string;
  helpComponent?: React.ComponentType;
  helpTitle?: string;
  beta?: boolean;
  proLabel?: boolean;
  ctaURL?: string;
};

export const HeaderTitle: React.FC<HeaderTitleProperties> = props => {
  const {
    children,
    title,
    titleHelper,
    helpComponent,
    helpTitle,
    beta,
    proLabel,
    ctaURL,
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
    <header data-component="HeaderTitle">
      <div className="flex items-center">
        <h3 className="text-3xl leading-6 font-medium text-gray-900">
          {title}
        </h3>
        {helpComponent && (
          <button onClick={handleClick}>
            <QuestionMarkCircleIcon
              className="h-8 w-8 ml-2 inline hover:text-brand-primary"
              aria-hidden="true"
            />
          </button>
        )}
        {beta && <BetaLabel />}
        {proLabel && <ProLabel />}
      </div>
      <p className="mt-2 max-w-2xl text-base text-gray-600">{titleHelper}</p>
      {proLabel && (
        <InlineCTA className={'mt-4'} isTitle={true} ctaURL={ctaURL || ''} />
      )}

      {children}
    </header>
  );
};
