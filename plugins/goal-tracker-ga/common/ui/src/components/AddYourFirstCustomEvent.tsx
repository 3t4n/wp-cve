import ArrowRightIcon from '@heroicons/react/solid/ArrowRightIcon';
import classNames from 'classnames';
import React from 'react';
import ClickToAddTop from '../assets/images/ClickToAddTop.svg';

interface AddYourFirstCustomEventProps {
  type: string;
  setOpenHelpSlider: (state: boolean) => void;
}

const AddYourFirstCustomEvent: React.FC<AddYourFirstCustomEventProps> = ({
  type,
  setOpenHelpSlider,
}) => {
  return (
    <>
      <div className="min-h-full pt-16 pb-12 flex flex-col bg-white">
        <main className="flex-grow flex flex-col justify-center max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
          {/* <div className="flex-shrink-0 flex justify-center">
            <a href="/" className="inline-flex">
              <span className="sr-only">Workflow</span>
              <img className="h-28 w-auto" src={figure} alt="" />
            </a>
          </div> */}
          <img className="h-full w-auto" src={ClickToAddTop} alt="" />
          <div></div>
          <div className="pt-4 border-t border-gray-300 w-full items-center text-center">
            <span className="text-2xl">Not sure how to get started?</span>
            <button
              type="button"
              onClick={() => setOpenHelpSlider(true)}
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
                'hover:text-white',
              )}
            >
              <span className="mx-2">{`Get Help`}</span>
              <ArrowRightIcon className="h-5 w-5" aria-hidden="true" />
            </button>
          </div>
          <div className="hidden py-16">
            <div className="text-center">
              <h1 className="mt-2 text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl">
                Add your first custom event.
              </h1>
              <div className="mt-6">
                <button
                  type="button"
                  className={classNames(
                    'capitalize inline-flex',
                    'items-center justify-center',
                    'rounded-full',
                    'border border-transparent bg-brand-primary',
                    'px-4 py-2',
                    'text-sm font-medium text-white',
                    'shadow-sm',
                    'hover:bg-brand-600',
                    'focus:outline-none focus:ring-2 focus:ring-brand-primary-focus focus:ring-offset-2',
                    'transform active:scale-75 hover:scale-105 transition-transform',
                  )}
                >
                  Add Custom Event
                </button>
              </div>
            </div>
          </div>
        </main>
        <footer className="flex-shrink-0 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8"></footer>
      </div>
    </>
  );
};

export default AddYourFirstCustomEvent;
