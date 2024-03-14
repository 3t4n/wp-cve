import React, { FC } from 'react';

interface PropsTagProps {
  pKey: string;
  pValue: string;
  del: string;
}

const PropsTag: FC<PropsTagProps> = ({ pKey, pValue, del }) => {
  return (
    <div
      data-component="tag"
      className="mr-3 mb-2 whitespace-nowrap flex"
      key={pKey}
    >
      <span className=" items-center px-3 py-0.5 rounded-l-lg text-sm font-medium bg-gray-200 text-gray-600">
        {pKey}
      </span>
      <span className="items-center px-3 py-0.5 rounded-r-lg text-sm font-medium bg-gray-400 text-white truncate max-w-[10ch] text-ellipsis">
        {pValue}
      </span>
    </div>
  );
};

export default PropsTag;
