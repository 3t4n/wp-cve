import axios from 'axios';
import classNames from 'classnames';
import React, { useState } from 'react';
import { GeneralSettingsTutorial } from 'ui';
import Smirk from '../assets/images/Smirk.png';
// import './main.css';

const featuresList = [
  'Installing and activating the plugin with ease',
  'Connecting the plugin to Google Analytics',
  'Disabling page_view tracking when using Google Tag Manager',
  'Using Debug View for effective event testing and debugging',
  'Simple email and link tracking with just a few clicks',
  'Enabling email link tracking in General Settings and creating a Custom Dimension in Google Analytics',
  'Tracking button clicks with class or ID attributes and custom events',
  'Distinguishing between custom and recommended events',
  "Applying visibility tracking to monitor your call-to-action buttons' exposure",
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

export const GettingStartedGuide = () => {
  const [email, setEmail] = useState('');
  const [formSubmitted, setFormSubmitted] = useState(false);
  const [showTutorial, setShowTutorial] = useState(true);

  async function subscribeToGettingStartedGuide(event) {
    event.preventDefault();
    let formData = new FormData();
    formData.append('fields[email]', email);

    let data = await axios({
      url: 'https://assets.mailerlite.com/jsonp/31991/forms/70109343150769330/subscribe',
      method: 'POST',
      data: formData,
      headers: { 'Content-Type': 'multipart/form-data' },
    });
    setFormSubmitted(true);
  }

  return (
    <section className="bg-white/50 py-4">
      <GeneralSettingsTutorial
        showTutorial={showTutorial}
        showCloseButton={false}
      />

      {!formSubmitted && (
        <div className="bg-white py-4 rounded-md border border-gray-200 my-10 mx-4 shadow-xl">
          <div className="">
            <div className="px-4 py-5 sm:p-6">
              <h3 className="text-3xl mb-6 leading-10 text-gray-900 max-w-xl">
                Download the Free Goal Tracker for Google Analytics Getting
                Started Guide
              </h3>
              <div className="mt-2 max-w-xl text-gray-500 p-2">
                <p className="text-xl">
                  Get the most out of the Goal Tracker for Google Analytics
                  WordPress plugin with our straightforward Getting Started
                  Guide.
                </p>
                <p className="text-brand-primary text-lg">
                  Learn these essential skills to improve your website tracking:
                </p>
                <ul role="list" className="list-inside p-1 mt-2">
                  {featuresList.map((feature, index) => (
                    <ListItem key={index} feature={feature} index={index + 1} />
                  ))}
                </ul>
              </div>

              <form className="mt-5 sm:items-center max-w-xl px-4 py-2">
                <div className="text-brand-primary text-lg mb-2">
                  👉 Download your FREE PDF guide here:
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
                      'text-sm font-medium text-white',
                      'shadow-sm',
                      'hover:bg-brand-600',
                      'focus:outline-none focus:ring-2 focus:ring-brand-primary-focus focus:ring-offset-2',
                      'transform active:scale-75 hover:scale-105 transition-transform',
                    )}
                  >
                    Download
                  </button>
                </fieldset>
              </form>
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
                  Check your inbox for the Getting Started Guide
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
