import { Transition } from '@headlessui/react';
import { ArrowRightIcon } from '@heroicons/react/solid';
import classNames from 'classnames';
import React, { useEffect, useState } from 'react';
import RatingStars from './RatingStars';

const slides = [
  {
    id: 1,
    title: 'Great Plugin',
    text: 'Bob, A.',
    // class: 'bg-slate-800',
    buttonAction: (
      <>
        {`Rate us`} <RatingStars />
      </>
    ),
    // imageSrc: logo,
  },
  {
    id: 2,
    title: '5 Stars, finally I can properly track it',
    text: 'Juanna, S.',
    // class: 'bg-slate-800',
    buttonAction: (
      <>
        {`Rate us`} <RatingStars />
      </>
    ), // imageSrc: logo2,
  },
  {
    id: 3,
    title: 'Love it! this plugin is so helpful, managing the analytics',
    text: 'Greg, K.',
    // class: 'bg-slate-800',
    buttonAction: (
      <>
        {`Rate us`} <RatingStars />
      </>
    ), // imageSrc: logo,
  },
];

function SliderTestimonials() {
  const [activeSlide, setActiveSlide] = useState(
    Math.floor(Math.random() * 3) + 1,
  );

  useEffect(() => {
    const intervalId = setInterval(() => {
      const newSlideId = activeSlide === 3 ? 1 : activeSlide + 1;
      setActiveSlide(newSlideId);
    }, 45000);

    return () => {
      clearInterval(intervalId);
    };
  }, [activeSlide]);

  const handleSlideChange = slideId => {
    setActiveSlide(slideId);
  };

  const isTestimonial = false;

  return (
    <div
      data-component="SliderTestimonials"
      className="relative overflow-hidden"
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
                'flex items-center px-4 text-slate pt-5 overflow-hidden',
                slide.class,
              )}
            >
              {slide.imageSrc && (
                <img
                  className="object-cover w-20 h-18 mr-2"
                  src={slide.imageSrc}
                  alt={slide.title}
                />
              )}
              <span
                data-component="HeroQuote"
                className="text-10xl mb-4 text-brand-primary/50"
              >{`"`}</span>
              <div className="flex-1 flex flex-col border-l-4 border-brand-primary pl-4">
                {slide.title && <div className="text-3xl">{slide.title}</div>}
                {slide.text && <div className="text-sm mt-2">{slide.text}</div>}
              </div>
              <button
                // onClick={toggleAddCustomEventForm}
                type="button"
                className={classNames(
                  'capitalize inline-flex',
                  'items-center justify-center',
                  'rounded-full',
                  'border border-transparent ',
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
              </button>
            </div>
          </Transition>
        ))}
      </div>
      <div className="absolute bottom-1 left-0 right-0 flex items-center justify-center mb-2 mt-0.5 space-x-2">
        {slides.map(slide => (
          <React.Fragment key={slide.id}>
            <button
              key={slide.id}
              onClick={() => handleSlideChange(slide.id)}
              className={`${
                activeSlide === slide.id
                  ? 'bg-brand-primary w-3 h-3'
                  : 'bg-brand-primary/50 w-2 h-2'
              } rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500`}
            />
          </React.Fragment>
        ))}
      </div>
    </div>
  );
}

export default SliderTestimonials;
