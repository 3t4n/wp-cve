import { Transition } from '@headlessui/react';
import React, { useEffect, useState } from 'react';

import { XIcon } from '@heroicons/react/outline';

const { apiFetch } = wp;

export function GeneralSettingsTutorial({ showTutorial, showCloseButton }) {
  const [showTutorialSection, setShowTutorialSection] = useState(showTutorial);

  useEffect(() => {
    setShowTutorialSection(showTutorial);
  }, [showTutorial]);

  const handleCloseClick = async () => {
    setShowTutorialSection(false);
    let data = await apiFetch({
      path:
        wpGoalTrackerGa.rest.namespace +
        wpGoalTrackerGa.rest.version +
        '/hide_gs_tutorial_section',
      method: 'POST',
      data: { hideGeneralSettingsTutorial: true },
    });
  };

  return (
    <>
      {/* {showTutorialSection && */}

      <Transition
        show={showTutorialSection}
        appear={true}
        enter="transition-opacity duration-75"
        enterFrom="opacity-0"
        enterTo="opacity-100"
        leave="transition-opacity duration-500"
        leaveFrom="opacity-100"
        leaveTo="opacity-0"
      >
        <div
          data-component="GeneralSettingsTutorial"
          className="bg-white py-4 rounded-md border border-gray-200 my-10 mx-4 shadow-xl"
        >
          <div className="">
            <div className="px-4 pb-5">
              <div className="mt-2 sm:flex sm:items-start sm:justify-between">
                <div className="max-w-xl text-sm text-gray-500">
                  <h3 className="text-3xl mb-6 leading-6 font-medium text-gray-900">
                    Using the plugin for the first time?
                  </h3>

                  <div className="mt-2 text-xl">
                    Watch our getting started guide video to get the plugin up
                    and running on your website.
                  </div>
                  <div className="pt-3">
                    <iframe
                      width="576"
                      height="360"
                      src="https://www.youtube.com/embed/X35iJBkwQeU"
                      title="YouTube video player"
                      frameBorder="0"
                      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                      allowFullScreen
                    ></iframe>
                  </div>
                </div>
                {showCloseButton && (
                  <div className="ml-3 flex h-7 items-center">
                    <button
                      type="button"
                      className="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                      onClick={() => handleCloseClick()}
                    >
                      <span className="sr-only">Close panel</span>
                      <XIcon className="h-6 w-6" aria-hidden="true" />
                    </button>
                  </div>
                )}
              </div>
            </div>
          </div>
        </div>
      </Transition>
      {/* } */}
    </>
  );
}
