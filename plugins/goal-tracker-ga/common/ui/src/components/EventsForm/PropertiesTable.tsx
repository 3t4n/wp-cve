import React from 'react';
import PropsEdit from './PropsEdit';  // Assuming the path is correct

interface PropertiesTableProps {
  properties: { [key: string]: any };  // A dictionary structure for the properties
  isRecommended: boolean;
  recommendedEvents?: any;  // Replace 'any' with the specific type if available
  eventName: string;
  setProp: (key: string, value: any) => void;
  deleteProp: (key: string) => void;
}

const PropertiesTable: React.FC<PropertiesTableProps> = ({ 
  properties, 
  isRecommended, 
  recommendedEvents, 
  eventName, 
  setProp, 
  deleteProp 
}) => {
  return (
    <div data-component="props-table" className="flex flex-col bg-gray-50 rounded-50 mt-6 ">
      <div data-component="props-list" className="bg-gray-100 p-2 flex flex-col items-center rounded pb-4">
        <table className="min-w-full table-fixed divide-y divide-gray-300">
          <thead className="bg-gray-50">
            <tr>
              <th scope="col" className="min-w-[12rem] py-3.5 pr-2 pl-4 text-left text-sm font-semibold text-gray-900">
                Property
              </th>
              <th scope="col" className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">
                Value
              </th>
              <th scope="col" className="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"></th>
              <th scope="col" className="relative py-3.5 pl-3 pr-4 sm:pr-6">
                <span className="sr-only">Edit</span>
              </th>
            </tr>
          </thead>
          <tbody className="bg-white">
            {Object.entries(properties).map(([key, value]) => (
              <PropsEdit
                key={key}
                pKey={key}
                pValue={value}
                setProp={setProp}
                required={isRecommended && recommendedEvents && recommendedEvents[eventName][key]?.required}
                type={isRecommended && recommendedEvents ? recommendedEvents[eventName][key]?.type : 'text'}
                placeholder={isRecommended && recommendedEvents ? recommendedEvents[eventName][key]?.placeholder : ''}
                deleteProp={deleteProp}
                deleteButton={true}
              />
            ))}
          </tbody>
        </table>
        {Object.keys(properties).length === 0 && (
          <div data-component="no-items-placeholder" className="p-6 w-full flex-1 text-center items-center opacity-90 bg-gray-200 rounded my-4">
            No Items
          </div>
        )}
      </div>
    </div>
  );
}

export default PropertiesTable;
