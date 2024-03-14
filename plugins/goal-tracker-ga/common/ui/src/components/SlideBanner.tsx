import { Transition } from '@headlessui/react';
import { ArrowRightIcon } from '@heroicons/react/solid';
import classNames from 'classnames';
import React, { useEffect, useState } from 'react';

import charManager from '../assets/images/Logo-flag-white-manager.svg';
import charOctobotty from '../assets/images/Logo-flag-white-octobotty.svg';
import charMedia from '../assets/images/Logo-track-video-white.svg';
import charContactForm7 from '../assets/images/Logo-track-email-white.svg';
import charWoo from '../assets/images/Logo-track-ecom2.svg';

declare const wpGoalTrackerGa: any;

interface Slide {
  id: number;
  title: string;
  text: string;
  class: string;
  buttonAction: string;
  imageSrc: string;
}

let slides: Slide[] = [
  {
    id: 1,
    title: 'Track Video & Audio',
    text: 'Experience Advanced Video and Audio Tracking for Comprehensive Analytics Insights',
    class: 'bg-slate-800',
    buttonAction: 'Get Pro Features',
    imageSrc: charMedia,
  },
  {
    id: 2,
    title: 'Placeholders',
    text: 'Access Dynamic Placeholders for Advanced Data Capture and Improved Event Tracking',
    class: 'bg-slate-800',
    buttonAction: 'Get Pro Features',
    imageSrc: charOctobotty,
  },
  {
    id: 3,
    title: 'Track Logged-In Users',
    text: 'Gain valuable insights into your audience and improve your marketing strategies, user experience, and overall website performance.',
    class: 'bg-slate-800',
    buttonAction: 'Get Pro Features',
    imageSrc: charManager,
  },
];

export function SliderBanner() {
  const [activeSlide, setActiveSlide] = useState<number>(
    Math.floor(Math.random() * 3) + 1,
  );

  useEffect(() => {
    const elementContactForm7 = document.querySelector('.toplevel_page_wpcf7');
    const elementWooCommerce = document.querySelector(
      '.toplevel_page_woocommerce',
    );

    if (elementContactForm7) {
      slides.unshift({
        id: 0,
        title: 'Contact Form 7 Tracking',
        text: 'Our Contact Form 7 integration allows you to track your forms and set conversions.',
        class: 'bg-slate-800',
        buttonAction: 'Get Pro Features',
        imageSrc: charContactForm7,
      });
      setActiveSlide(0);
    }

    if (elementWooCommerce) {
      slides.unshift({
        id: 0,
        title: 'WooCommerce Tracking',
        text: 'Add Google Analytics tracking to your WooCommerce store and start getting actionable insights.',
        class: 'bg-slate-800',
        buttonAction: 'Get Pro Features',
        imageSrc: charWoo,
      });
      setActiveSlide(0);
    }
  }, []);

  useEffect(() => {
    const intervalId = setInterval(() => {
      const newSlideId = activeSlide === slides.length ? 1 : activeSlide + 1;
      setActiveSlide(newSlideId);
    }, 45000);

    return () => {
      clearInterval(intervalId);
    };
  }, [activeSlide]);

  const handleSlideChange = (slideId: number) => {
    setActiveSlide(slideId);
  };

  return (
    <div
      data-component="SliderBanner"
      className="relative h-32 bg-slate-800 overflow-hidden"
    >
      <div className="overflow-hidden h-full">
        {slides.map(slide => (
          <Transition
            key={slide.id}
            show={activeSlide === slide.id}
            enter="transition-transform duration-500"
            enterFrom="translate-x-full"
            enterTo="translate-x-0"
            leave="transition-transform duration-500"
            leaveFrom="translate-x-0"
            leaveTo="-translate-x-full"
          >
            <div
              className={classNames(
                'flex items-center px-4 text-white pt-5 overflow-hidden',
                slide.class,
              )}
            >
              {slide.imageSrc && (
                <img
                  className="object-cover w-auto h-24 mr-2"
                  src={slide.imageSrc}
                  alt={slide.title}
                />
              )}
              <div className="flex-1 flex flex-col ml-4">
                {slide.title && (
                  <div className="text-2xl mt-2">{slide.title}</div>
                )}
                {slide.text && (
                  <div className="text-base opacity-80">{slide.text}</div>
                )}
              </div>
              <a
                href={wpGoalTrackerGa.upgradeUrl}
                // onClick={toggleAddCustomEventForm}
                type="button"
                className={classNames(
                  'capitalize inline-flex',
                  'items-center justify-center',
                  'rounded-full',
                  'border border-transparent',
                  'bg-white text-brand-primary',
                  'px-4 py-2',
                  'text-sm font-medium',
                  'shadow hover:shadow-xl',
                  'transform active:scale-75 hover:scale-110 transition-transform',
                  'hover:ring-2 hover:ring-white hover:ring-offset-2',
                  'focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2',
                )}
              >
                <span className="mx-2">{slide.buttonAction}</span>
                <ArrowRightIcon className="h-5 w-5" aria-hidden="true" />
              </a>
            </div>
          </Transition>
        ))}
      </div>
      <div className="absolute bottom-1 left-0 right-0 flex items-center justify-center mb-2 mt-0.5 space-x-2">
        {slides.map(slide => (
          <React.Fragment key={slide.id}>
            <button
              onClick={() => handleSlideChange(slide.id)}
              className={`${
                activeSlide === slide.id
                  ? 'bg-white w-3 h-3'
                  : 'bg-gray-200 w-2 h-2'
              } rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500`}
            />
          </React.Fragment>
        ))}
      </div>
    </div>
  );
}

export default SliderBanner;
