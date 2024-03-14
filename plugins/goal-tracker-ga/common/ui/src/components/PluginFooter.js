// import logoStar from '../assets/images/Smirk-Stars-row.svg';
import { ArrowRightIcon } from '@heroicons/react/solid';
import classNames from 'classnames';
import logoStarCloud from '../assets/images/Smirk-Stars-cloud.svg';
import RatingStars from './RatingStars';
import SliderTestimonials from './SliderTestimonials';

export function PluginFooter() {
  const isTestimonial = false;

  return (
    <footer
      data-component="PluginFooter"
      className={classNames('bg-gray-100/75 shadow-lg rounded-b-lg', {
        'pt-8 pb-4': !isTestimonial,
      })}
    >
      <div className="px-8 py-4">
        {isTestimonial && (
          <div className="flex items-center">
            <img className="h-20 w-auto" src={logoStarCloud}></img>
            <div className="flex-1 py-8">
              <SliderTestimonials />
            </div>
          </div>
        )}

        {!isTestimonial && (
          <div className="flex items-center text-base">
            <img className="h-20 w-auto mr-6" src={logoStarCloud}></img>
            <div className="flex-1">
              We'd be super grateful if you could help us spread the word about{' '}
              <strong>GoalTracker</strong> and give it a
              <a
                className="text-brand-primary px-1 py-0.5 border border-brand-primary/10 rounded mx-1"
                target="_blank"
                href="https://wordpress.org/support/plugin/goal-tracker-ga/reviews/#new-post"
              >
                <RatingStars /> star rating
              </a>
              on WordPress?
            </div>

            <a
              // onClick={toggleAddCustomEventForm}
              type="button"
              href="https://wordpress.org/support/plugin/goal-tracker-ga/reviews/#new-post"
              target="_blank"
              className={classNames(
                'ml-4',
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
              <span className="mx-2">
                {`Rate us`} <RatingStars />
              </span>
              <ArrowRightIcon className="h-5 w-5" aria-hidden="true" />
            </a>
          </div>
        )}
      </div>
    </footer>
  );
}
