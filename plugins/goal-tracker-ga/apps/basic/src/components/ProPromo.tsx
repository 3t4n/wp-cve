import React, { useState, useEffect, ReactElement } from 'react';
import { ArrowRightIcon } from '@heroicons/react/solid';
import classNames from 'classnames';
import VideoCopy from './upgrade/VideoCopy';
import ContactForm7Copy from './upgrade/ContactForm7Copy';
import UserTrackingCopy from './upgrade/UserTrackingCopy';
import PlaceholdersCopy from './upgrade/PlaceholdersCopy';
import WooCopy from './upgrade/WooCopy';

declare var wpGoalTrackerGa: any;

const ProPromo: React.FC = () => {
  const renderVideoCopy = () => <VideoCopy />;
  const renderWooCopy = () => <WooCopy />;
  const renderPlaceholdersCopy = () => <PlaceholdersCopy />;
  const renderContactForm7Copy = () => <ContactForm7Copy />;
  const renderUserTrackingCopy = () => <UserTrackingCopy />;

  const defaultComponents = [
    renderVideoCopy,
    renderPlaceholdersCopy,
    renderUserTrackingCopy,
  ];

  const [hasContactForm7, setHasContactForm7] = useState(false);
  const [hasWooCommerce, setHasWooCommerce] = useState(false);
  const [componentFunctions, setComponentFunctions] = useState<
    (() => ReactElement)[]
  >(defaultComponents);

  useEffect(() => {
    const elementContactForm7 = document.querySelector('.toplevel_page_wpcf7');
    const elementWooCommerce = document.querySelector(
      '.toplevel_page_woocommerce',
    );
    let newComponents = [...defaultComponents];

    if (elementWooCommerce) {
      newComponents = [renderWooCopy, ...newComponents];
    }

    if (elementContactForm7) {
      newComponents = [renderContactForm7Copy, ...newComponents];
    }

    setComponentFunctions(newComponents);
  }, []);

  return (
    <div data-component="ProPromo" className="">
      <header className="px-6 pt-6 pb-4 rounded-b-xl bg-white">
        <aside
          className={classNames(
            'flex flex-col flex-1 justify-end w-full',
            'justify-between',
            // 'gap-x-16 gap-y-8',
            'rounded-2xl',
            'p-8',
            // 'sm:w-3/4 lg:w-72 sm:max-w-md',
            // 'sm:flex-row-reverse sm:items-end',
            'lg:max-w-none',
            'lg:flex-none lg:flex-col lg:items-start w-full',
            'bg-white',
          )}
        >
          <div>
            <span
              data-component="tag"
              className={classNames(
                'bg-slate-500',
                'text-white',
                'text-xs px-2 py-1',
                'rounded uppercase',
              )}
            >{`PRO`}</span>
          </div>
          <div>
            <p className="text-6xl font-semibold tracking-tight text-gray-900">
              Track More
            </p>
            <p className="mt-2 text-6xl text-gray-600">Get More</p>
          </div>
          <p className="text-base mt-2">
            Upgrade to Goal Tracker Pro to unlock more features and become a
            Google Analytics power user.
            <a
              href={wpGoalTrackerGa.upgradeUrl}
              type="button"
              className={classNames(
                'capitalize inline-flex',
                'items-center justify-center',
                'rounded-full',
                'border border-transparent ',
                'bg-brand-primary text-white',
                'px-4 py-2',
                'text-sm font-medium',
                'shadow hover:shadow-xl',
                'transform active:scale-75 hover:scale-110 transition-transform',
                'hover:ring-2 hover:ring-white hover:ring-offset-2',
                'focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2',
                'ml-4',
              )}
            >
              <span className="mx-2">{`Go Pro`}</span>
              <ArrowRightIcon className="h-5 w-5" aria-hidden="true" />
            </a>
          </p>
        </aside>
      </header>

      <div
        className={classNames(
          'p-10',
          'mx-auto mt-16',
          'flex flex-col xl:flex-row gap-4 xl:gap-6 2xl:gap-8',
          'lg:mx-0 lg:mt-10 lg:max-w-none lg:flex-row lg:items-end',
        )}
      >
        {componentFunctions.slice(0, 3).map((renderFunction, index) => (
          <React.Fragment key={index}>{renderFunction()}</React.Fragment>
        ))}
      </div>
    </div>
  );
};

export default ProPromo;
