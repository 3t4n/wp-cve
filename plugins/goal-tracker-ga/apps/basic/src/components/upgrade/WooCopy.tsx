import classNames from 'classnames';
import wooIllustration from 'ui/src/assets/images/Logo-track-ecom2.svg';
import { ArrowRightIcon } from '@heroicons/react/solid';

declare var wpGoalTrackerGa: any;

export default function WooCopy() {
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
          src={wooIllustration}
        />
        WooCommerce
      </header>
      <div className="flex-1 w-full justify-self-end mt-3 xl:mt-32 mb-8">
        <p className="text-xl font-semibold tracking-tight text-white">
          Track your WooCommerce Store in Google Analytics{' '}
        </p>
        <div className="mt-2 text-base leading-7 text-white/70">
          <div className="text-lg text-gray-300">
            How familiar are you with your customer journey? How does the
            conversion rate drop between adding items to the cart to starting
            the checkout process? How much of your revenue can you attribute to
            that guest blog post?{' '}
          </div>
          <div className="text-lg text-gray-300 pt-2">
            If you want to start making data-driven decisions (instead of just
            guessing), then having a good Analytics system in place is where you
            should start.{' '}
          </div>
          <div className="text-lg text-gray-300 pt-2">
            Google Analytics is the default choice for most of us and makes
            perfect sense - you already track website visits so why not take it
            up a notch and use the advanced e-commerce features?{' '}
          </div>
          <div className="text-lg text-gray-300 pt-2">
            Goal Tracker for Google Analytics can help you start tracking
            valuable information with just a few clicks.
          </div>
          <ul className="px-2 space-y-2 divide-y divide-white/10 my-4  text-gray-300">
            <li>Track customer journey, from first click to final purchase.</li>
            <li>
              Seamlessly integrate your WooCommerce store with Google Analytics
              GA4.
            </li>
            <li>
              Goal Tracker will send out all the required events for the
              Purchase, and Checkout Journey reports.
            </li>
            <li>Enable Woo tracking with just a few clicks.</li>
          </ul>

          <div className="text-white">
            Upgrade now to unlock enhanced eCommerce tracking for WooCommerce.
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
          <span className="mx-2">{`Track WooCommerce`}</span>
          <ArrowRightIcon className="h-5 w-5" aria-hidden="true" />
        </a>
      </footer>
    </article>
  );
}
