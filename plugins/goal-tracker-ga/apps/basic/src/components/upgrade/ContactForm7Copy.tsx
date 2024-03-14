import classNames from 'classnames';
import illustrationContactForm7 from 'ui/src/assets/images/Logo-track-email-white.svg';
import { ArrowRightIcon } from '@heroicons/react/solid';

declare var wpGoalTrackerGa: any;

export default function ContactForm7Copy() {
  return (
    <article
      className={classNames(
        'flex flex-col flex-1 justify-end w-full',
        'relative',
        // 'gap-y-8',
        // 'gap-x-16',
        'rounded-2xl p-6 lg:p-8',
        'sm:flex sm:items-end',
        'lg:items-end',
        // 'lg:gap-y-32',
        'shadow-xl hover:shadow-2xl',
        'bg-brand-900',
      )}
    >
      <header className="text-start text-3xl font-bold tracking-tight text-white w-full">
        <img
          className={classNames(
            'object-cover w-28 h-auto mr-2',
            // 'absolute -top-8 -left-8',
          )}
          src={illustrationContactForm7}
        />
        Contact Form 7
      </header>
      <div className="flex-1 w-full justify-self-end mt-4 xl:mt-32 mb-8">
        <p className="text-xl font-semibold tracking-tight text-white">
          Track your Contact Form 7 Submissions{' '}
        </p>
        <div className="mt-2 text-base leading-7 text-white/70">
          <div className="text-lg text-gray-300">
            In Goal Tracker for Google Analytics Pro we integrated with the
            Contact Form 7 plugin:
          </div>
          <ul className="px-2 space-y-2 divide-y divide-white/10 my-4  text-gray-300">
            <li>Track form submission conversions in Google Analytics.</li>
            <li>
              The plugin can track successful conversions and filter out non
              relevant clicks.
            </li>
            <li>Optimize your forms - Track form validation issues.</li>
            <li>Clean statistics - Get statistics on spam submissions.</li>
            <li>Track failed email attempts.</li>
          </ul>
          <div className="text-white">
            Upgrade now to elevate your analytics experience with Contact Form 7
            form tracking in Goal Tracker Pro!
          </div>
        </div>
      </div>
      <footer className="flex items-center">
        {/* <a
              className="text-brand-400 underline mr-8 text-sm hover:text-brand-300 underline-offset-4"
              href=""
            >{`still unsure?`}</a> */}
        <a
          href={wpGoalTrackerGa.upgradeUrl}
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
          <span className="mx-2">{`Get Pro Features`}</span>
          <ArrowRightIcon className="h-5 w-5" aria-hidden="true" />
        </a>
      </footer>
    </article>
  );
}
