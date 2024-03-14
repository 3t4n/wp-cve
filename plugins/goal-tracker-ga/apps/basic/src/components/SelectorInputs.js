import React from 'react';
import EventFormContext from '../context/EventsFormContext'

const SelectorInputs = () => {

  const { selectorContext } = React.useContext(EventFormContext);
  const [selector, setSelector] = selectorContext;


  return (
    <>
      {/* <div className="mt-6 sm:mt-5 space-y-6 sm:space-y-5"> */}
      <fieldset className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
        <label htmlFor="selector" className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2 required">
          Class or ID Selector
        </label>
        <div className="mt-1 sm:mt-0 sm:col-span-2">
          <div className="w-full flex rounded-md shadow-sm">
            <input
              required
              type="text"
              name="selector"
              id="selector"
              placeholder="Full CSS selector (including . and #)"
              value={selector || ''}
              onChange={e => setSelector(e.target.value)}
              className="flex-1 block w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-none rounded-r-md sm:text-sm border-gray-300" />
          </div>
        </div>
      </fieldset>
      {/* </div> */}
    </>
  )
}

export default SelectorInputs