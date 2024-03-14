import axios from 'axios';
import classNames from 'classnames';
import React, { useState } from 'react';
import Smirk from '../assets/images/Smirk.png';

// import './main.css';

const featuresList = [
  'Review the events you configured in WP Google Analytics Events. ',
  'Suggest a new structure for your events. ',
  'We will log and configure Goal Tracker for Google Analytics for you. ',
];

const ListItem = ({ feature, index }) => {
  return (
    <li className="p-2 shadow rounded flex border border-grey-100/10">
      {index && (
        <span className="rounded-full w-6 h-6 bg-brand-primary text-white justify-center flex items-center">
          {index}
        </span>
      )}

      <span className="ml-2 flex-1 text-base">{feature}</span>
    </li>
  );
};

export const MigrateToGoalTracker = () => {
  const [email, setEmail] = useState('');
  const [formSubmitted, setFormSubmitted] = useState(false);

  async function subscribeToGettingStartedGuide(event) {
    event.preventDefault();

    const website = window.location.protocol + '//' + window.location.host;

    let formData = new FormData();
    formData.append('fields[email]', email);
    formData.append('fields[website]', website);

    let data = await axios({
      url: 'https://assets.mailerlite.com/jsonp/31991/forms/92865853511435498/subscribe',
      method: 'POST',
      data: formData,
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    setFormSubmitted(true);
  }

  return (
    <section className="bg-white/50 py-4">
      {!formSubmitted && (
        <div className="bg-white py-4 rounded-md border border-gray-200 my-10 mx-4 shadow-xl">
          <div className="">
            <div className="px-4 py-5 sm:p-6">
              <h3 className="text-3xl mb-6 leading-10 text-gray-900 max-w-xl">
                Are you migrating your events from WP Google Analytics Events?
              </h3>
              <div className="mt-2 max-w-2xl text-gray-500 p-2 text-lg">
                <p className="text-lg">
                  I am guessing that moving to GA4 was quite an{' '}
                  <span className="italic">experience</span> for you.
                </p>
                <p className="text-lg mt-5">
                  But if you just migrated from our old plugin - WP Google
                  Analytics Events - there is one more step. And that is to
                  migrate your events.{' '}
                </p>
                <p className="text-lg mt-5">
                  Let me explain (overwhelmed? Skip to the next section right
                  after the video).
                </p>
                <p className="text-lg mt-5">
                  One of the big changes in GA4 is the event structure. Events
                  used to have three main fields - Category, Action, and Label.
                  Now, in GA4, events will have a name and then a set of
                  attributes that you can configure. This makes events much more
                  powerful in GA4, but it also means that you have to rethink
                  how you structure your events (It’s a good thing!).
                </p>

                <p className="text-lg mt-5">
                  <span className="mb-5">
                    Here is a short video explaining the changes:
                  </span>
                  <iframe
                    className="mt-5"
                    width="560"
                    height="315"
                    src="https://www.youtube.com/embed/Xn4NoJ0RkJU"
                    title="YouTube video player"
                    frameBorder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowFullScreen
                  ></iframe>
                </p>
                <p className="text-lg font-bold mt-10">
                  But what if you have a lot of events and very little time?{' '}
                  <br />
                  Or if this is just too overwhelming?{' '}
                </p>
                <p className="text-lg mt-5">
                  For our Pro plugin users and with a one-time fee, we offer a
                  done-for-you migration service from WP Google Analytics
                  Events/Pro to Goal Tracker for Google Analytics:
                </p>
                <ul role="list" className="list-inside p-1 mt-5">
                  {featuresList.map((feature, index) => (
                    <ListItem key={index} feature={feature} index={index + 1} />
                  ))}
                </ul>
                <p className="text-lg mt-5">
                  All you have to do is click the button below to open our
                  contact form and fill in your details. We will get in touch
                  with you to start the process.
                </p>
              </div>
              <form className="mt-5 sm:items-center max-w-xl px-4 py-2">
                <div className="text-brand-primary text-lg mb-2">
                  👉 Start your migration:
                </div>
                <fieldset className="flex">
                  <div className="w-full flex">
                    <label htmlFor="email" className="sr-only">
                      Email
                    </label>
                    <input
                      type="email"
                      name="email"
                      id="email"
                      value={email}
                      onChange={e => setEmail(e.target.value)}
                      className={classNames(
                        'shadow-sm ',
                        'focus:ring-indigo-500 focus:border-indigo-500 ',
                        'flex-1 text-lg',
                        'border-gray-300 rounded-l rounder-r-0 p-2',
                      )}
                      placeholder="you@example.com"
                    />
                  </div>
                  <button
                    type="submit"
                    onClick={subscribeToGettingStartedGuide}
                    className={classNames(
                      'capitalize inline-flex',
                      'items-center justify-center',
                      'rounded-r',
                      'border border-transparent bg-brand-primary',
                      'px-4 py-2',
                      'text-lg font-medium text-white',
                      'shadow-sm',
                      'hover:bg-brand-600',
                      'focus:outline-none focus:ring-2 focus:ring-brand-primary-focus focus:ring-offset-2',
                      'transform active:scale-75 hover:scale-105 transition-transform',
                    )}
                  >
                    Submit
                  </button>
                </fieldset>
              </form>
              <div className="max-w-2xl">
                <p className="text-lg mt-5 text-gray-500 italic">
                  This service is available to our Pro plugin users for a
                  one-time fee of $99.
                </p>
              </div>
            </div>
          </div>
        </div>
      )}
      {formSubmitted && (
        <div className="min-h-full pt-16 pb-12 flex flex-col bg-white">
          <main className="flex-grow flex flex-col justify-center max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8">
            <div className="flex-shrink-0 flex justify-center">
              <span className="sr-only">Workflow</span>
              <img className="h-28 w-auto" src={Smirk} alt="" />
            </div>
            <div className="py-16">
              <div className="text-center">
                <h1 className="mt-2 text-2xl font-extrabold text-gray-900 tracking-tight sm:text-4xl">
                  Our team is on it and will be in touch soon
                </h1>
                <div className="mt-6"></div>
              </div>
            </div>
          </main>
          <footer className="flex-shrink-0 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8"></footer>
        </div>
      )}
    </section>
  );
};

// export default GettingStartedGuide;
