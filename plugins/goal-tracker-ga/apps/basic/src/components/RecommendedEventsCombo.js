import React, { useState, useContext, useEffect } from "react";
import { Combobox } from '@headlessui/react'
import { CheckIcon, SelectorIcon } from '@heroicons/react/solid'
import EventFormContext from "../context/EventsFormContext";
import RecommendedEventsListContext from "../context/RecommendedEventsList";

function classNames(...classes) {
  return classes.filter(Boolean).join(' ')
}


export default function RecommendedEventsCombo() {
  const recommendedEvents = useContext(RecommendedEventsListContext);
  const eventsList = Object.keys(recommendedEvents)
  const { eventNameContext, setEventContext } = useContext(EventFormContext);
  const [setEvent] = setEventContext;
  const [eventName, setEventName] = eventNameContext;
  const [query, setQuery] = useState('')


  const filterEvents =
    query === ''
      ? eventsList
      : eventsList.filter((eventName) => {
        return eventName.toLowerCase().includes(query.toLowerCase())
      })


  useEffect(() => {
    if (!eventName) {
      setEventName("generate_lead")
      setEvent("generate_lead")
    }
  }, [])



  return (
    <Combobox className="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start" as="fieldset" value={eventName} onChange={setEvent}>
      <Combobox.Label className="block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2">Recommended Event</Combobox.Label>
      <div className="relative mt-1 sm:mt-0 sm:col-span-2">
        <div className="max-w-sm_ w-full flex rounded-md shadow-sm">
          <Combobox.Input
            className="w-full flex-1 block min-w-0 rounded-md border border-gray-300 bg-white py-2 pl-3 pr-10 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 sm:text-sm"
            onChange={(event) => setQuery(event.target.value)}
            displayValue={(eventName) => eventName}
          />
          <Combobox.Button className="absolute inset-y-0 right-0 flex items-center rounded-r-md px-2 focus:outline-none">
            <SelectorIcon className="h-5 w-5 text-gray-400" aria-hidden="true" />
          </Combobox.Button>
        </div>

        {filterEvents.length > 0 && (
          <Combobox.Options className="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
            {filterEvents.map((eventName) => (
              <Combobox.Option
                key={eventName}
                value={eventName}
                className={({ active }) =>
                  classNames(
                    'relative cursor-default select-none py-2 pl-3 pr-9',
                    active ? 'bg-indigo-600 text-white' : 'text-gray-900'
                  )
                }
              >
                {({ active, selected }) => (
                  <>
                    <span className={classNames('block truncate', selected && 'font-semibold')}>{eventName}</span>

                    {selected && (
                      <span
                        className={classNames(
                          'absolute inset-y-0 right-0 flex items-center pr-4',
                          active ? 'text-white' : 'text-indigo-600'
                        )}
                      >
                        <CheckIcon className="h-5 w-5" aria-hidden="true" />
                      </span>
                    )}
                  </>
                )}
              </Combobox.Option>
            ))}
          </Combobox.Options>
        )}
      </div>
    </Combobox>
  )
}