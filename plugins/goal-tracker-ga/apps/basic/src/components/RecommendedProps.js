import React, { useState, useEffect } from "react";


export default function PropsEdit({ pKey, pValue, setProp, required, type, placeholder, deleteProp, deleteButton }) {
  return (
    <>
      {pKey != "items" ?
        <fieldset className="flex flex-1 w-full sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-gray-200 sm:pt-5">
          <label htmlFor="selectorType" className={"block text-sm font-medium text-gray-700 sm:mt-px sm:pt-2" + (required == true ? ' required' : '')} >
            {pKey}
          </label>

          <input
            type={type}
            required={required}
            name={pKey}
            placeholder={placeholder}
            id={pKey}
            value={pValue}
            onChange={e => setProp(pKey, pKey == 'items' ? '[{}]' : e.target.value)}
            className="flex-1 block col-span-2 w-full focus:ring-indigo-500 focus:border-indigo-500 min-w-0 rounded-none rounded-r-md sm:text-sm border-gray-300" />
        </fieldset>
        : ""
      }
    </>

  )
}
