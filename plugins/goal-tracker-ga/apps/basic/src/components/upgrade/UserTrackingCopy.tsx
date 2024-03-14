import classNames from 'classnames';
import { ArrowRightIcon } from '@heroicons/react/solid';
import illustrationManager from 'ui/src/assets/images/Logo-flag-white-manager.svg';

declare var wpGoalTrackerGa: any;

export default function UserTrackingCopy() {
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
          src={illustrationManager}
        />
        User Tracking
      </header>
      <div className="flex-1 w-full justify-self-end mt-4 xl:mt-32 mb-8">
        <p className="text-xl font-semibold tracking-tight text-white">
          Introducing User Tracking for a Deeper Understanding of Your Audience
        </p>
        <div className="mt-2 text-base leading-7 text-white/70">
          <div className="text-lg text-gray-300">
            Upgrade to Goal Tracker Pro and harness the power of User Tracking:
          </div>
          <ul className="px-2 space-y-2 divide-y divide-white/10 my-4  text-gray-300">
            <li>
              Gain valuable insights into individual user behavior across
              multiple sessions and devices.
            </li>
            <li>
              Enhance your marketing strategies, user experience, and overall
              website performance with data-driven decisions.
            </li>
            <li>
              Ensure user privacy protection by using hashed identifiers that
              comply with data privacy regulations.
            </li>
            <li>
              Unlock the full potential of your website's analytics capabilities
              for a more comprehensive view of your audience.
            </li>
          </ul>
          <div className="text-white">
            Upgrade now to elevate your analytics experience with User Tracking
            in Goal Tracker Pro!
          </div>
        </div>
      </div>
      <footer className="flex items-center">
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
