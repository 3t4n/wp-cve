import React, { useState, useEffect } from "react";


export default function PropsEdit({ pKey, pValue, setProp, required, type, placeholder, deleteProp, deleteButton }) {
  return (
    <>
      {
        pKey != "items" ?
          <tr className='bg-gray-50' >
            <td
              className="whitespace-nowrap pr-2 pl-4 text-sm font-medium py-1  pt-2 text-gray-900">
              {pKey}
            </td>
            <td className="whitespace-nowrap px-3 text-sm text-gray-500">
              <input
                type={type}
                required={required}
                name={pKey}
                placeholder={placeholder}
                id={pKey}
                value={pValue}
                onChange={e => setProp(pKey, pKey == 'items' ? '[{}]' : e.target.value)}
                className="flex-1 block col-span-2 w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-none rounded-r-md sm:text-sm border-gray-300" />
            </td>
            <td className="whitespace-nowrap px-3 text-sm text-gray-500"></td>
            <td className="whitespace-nowrap  py-6 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
              <button
                type="button"
                onClick={e => deleteProp(pKey)}
                className="ml-3 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs font-medium rounded shadow-sm text-white bg-brand-danger hover:bg-brand-danger-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-danger-focus"
              >
                Delete
              </button>
            </td>
          </tr>
          : ""
      }
    </>

  )
}
