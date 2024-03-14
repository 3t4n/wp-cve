/**
 * @constructor
 *
 * @param {HTMLElement} element HTML Element to attach the timer on
 * @param {string} endTime The end time
 * @param {Function} [onFinished] Callback to run when timer is finished
 */
var CountdownTimer = function (element, endTime, onFinished) {
  var that = this;
  this.clock = element;
  this.clock = element;
  this.updateClock(endTime);
  this.interval = setInterval(function () {
    that.updateClock(endTime, onFinished);
  }, 1000);
};

/**
 * Calculates remaining time from the present moment to `endDate`
 *
 * @static
 * @param {string} endTime The end time
 * @returns {{total: number, days: number, hours: number, minutes: number, seconds: number}} Total time remaining
 */
CountdownTimer.prototype.getRemainingTime = function (endTime) {
  var t = Date.parse(endTime) - Date.parse(new Date());
  var seconds = Math.floor((t / 1000) % 60);
  var minutes = Math.floor((t / 1000 / 60) % 60);
  var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
  var days = Math.floor(t / (1000 * 60 * 60 * 24));

  return {
    total: t,
    days: days <= 0 ? 0 : days,
    hours: hours <= 0 ? 0 : hours,
    minutes: minutes <= 0 ? 0 : minutes,
    seconds: seconds <= 0 ? 0 : seconds,
  };
};

/**
 * Updates the HTML element
 *
 * @param {string} endTime The end time
 * @param {Function} [onFinished] Callback to run when timer is finished
 * @returns {undefined}
 */
CountdownTimer.prototype.updateClock = function (endTime, onFinished) {
  var daysSpan = this.clock.querySelector('.elements-plus-countdown-days');
  var hoursSpan = this.clock.querySelector('.elements-plus-countdown-hours');
  var minutesSpan = this.clock.querySelector('.elements-plus-countdown-minutes');
  var secondsSpan = this.clock.querySelector('.elements-plus-countdown-seconds');
  var t = this.getRemainingTime(endTime);

  if (daysSpan) {
    daysSpan.innerHTML = t.days;
  }

  if (hoursSpan) {
    hoursSpan.innerHTML = `0${t.hours}`.slice(-2);
  }

  if (minutesSpan) {
    minutesSpan.innerHTML = `0${t.minutes}`.slice(-2);
  }

  if (secondsSpan) {
    secondsSpan.innerHTML = `0${t.seconds}`.slice(-2);
  }

  if (t.total <= 0) {
    this.stop();

    if (onFinished) {
      onFinished();
    }
  }
};

/**
 * Stops the countdown
 *
 * @returns {undefined}
 */
CountdownTimer.prototype.stop = function () {
  clearInterval(this.interval);
};

/**
 * "Destroys" the instance
 *
 * @returns {undefined}
 */
CountdownTimer.prototype.destroy = function () {
  this.stop();
  this.clock = null;
  this.interval = null;
};

(function($) {
	$(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction('frontend/element_ready/ep_countdown.default', function($scope) {

			var $countdown = $($scope).find('.elements-plus-countdown');

			$countdown.each(function() {
				var $this = $(this);
				var date = $this.data('date');

				new CountdownTimer($this.get(0), date);
			});

		});
	});
})( jQuery );
