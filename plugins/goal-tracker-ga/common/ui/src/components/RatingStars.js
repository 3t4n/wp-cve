import { StarIcon } from '@heroicons/react/solid';
import React from 'react';

const RatingStars = () => {
  return (
    <span className="whitespace-pre">
      <StarIcon className="h-3 w-3 inline" aria-hidden="true" />
      <StarIcon className="h-3 w-3 inline" aria-hidden="true" />
      <StarIcon className="h-3 w-3 inline" aria-hidden="true" />
      <StarIcon className="h-3 w-3 inline" aria-hidden="true" />
      <StarIcon className="h-3 w-3 inline" aria-hidden="true" />
    </span>
  );
};

export default RatingStars;
