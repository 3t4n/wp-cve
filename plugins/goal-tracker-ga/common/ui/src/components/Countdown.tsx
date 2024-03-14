import React, { useEffect, useState } from 'react';

const Countdown: React.FC = () => {
  const [time, setTime] = useState({
    days: 0,
    hours: 0,
    minutes: 0,
  });

  useEffect(() => {
    const countdown = () => {
      const now = new Date();
      const targetDate = new Date('2023-07-01T00:00:00');
      const diff = targetDate.getTime() - now.getTime();

      if (diff > 0) {
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
        const minutes = Math.floor((diff / 1000 / 60) % 60);

        setTime({
          days,
          hours,
          minutes,
        });
      } else {
        setTime({
          days: 0,
          hours: 0,
          minutes: 0,
        });
      }
    };

    countdown();
    const interval = setInterval(countdown, 60000);

    return () => clearInterval(interval);
  }, []);

  return (
    <div className="space-x-1 inline-flex mx-1">
      {/* {` (`} */}
      <div className="">
        <span className="underline">{time.days}</span>
        {` days`}
      </div>
      {/* <div className="">
        <span className="underline">{time.hours}</span>
        {``}
      </div>
      <div className="">
        {':'}
        <span className="underline">{time.minutes}</span>
        {` min`}
      </div> */}
      {/* {`) `} */}
    </div>
  );
};

export default Countdown;
