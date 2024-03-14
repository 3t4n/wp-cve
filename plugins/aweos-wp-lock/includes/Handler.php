<?php
use Carbon\Carbon;

/**
 * Handles frontend display based on current optios
 */
class Handler {
	/**
	 * @var array $options (from FrontendMenu::getOptions())
	 */
	private $options;

	/**
	 * Constructor
	 *
	 * @var array $options Options to set at the beginning (from Frontendmenu::getOptions())
	 */
	public function __construct($options) {
		$this->options = $options;
	}

	public function isActive() {
		switch ($this->options['mode']) {
			case 0:
				//Wp-Lock is disabled permanently
				return false;
			case 1:
				//Wp-Lock is enabled permanently
				return true;
			case 2:
				//Wp-Lock is disabled for a specified time+unit (e.g. 3 weeks) - and will then be enabled
				if ($this->forIsOver($this->options['lastUpdated'], $this->options['dFor'], $this->options['dForI'])) {
					FrontendMenu::enablePermanently();
					return true;
				}
				return false;
			case 3:
				//Wp-Lock is enabled for a specified time+unit (e.g. 3 weeks) - and will then be disabled
				if ($this->forIsOver($this->options['lastUpdated'], $this->options['eFor'], $this->options['eForI'])) {
					FrontendMenu::disablePermanently();
					return false;
				}
				return true;
			case 4:
				if (!$this->timespanIsReached($this->options['dFrom'], $this->options['dTo'])) {
					//Vor dem Startzeitpunkt - normaler Status ist an
					return true;
				} elseif($this->timespanIsOver($this->options['dFrom'], $this->options['dTo'])) {
					//Außerhalb der Zeitzone nach dem Endzeitpunkt - normaler Status ist an, und Status wird gewechselt
					FrontendMenu::enablePermanently();
					return true;
				}
				//Inerhalb der Zeitzome - spezieller Status: aus
				return false;
			case 5:
				if (!$this->timespanIsReached($this->options['eFrom'], $this->options['eTo'])) {
					//Vor dem Startzeitpunkt - normaler Status ist aus
					return false;
				} elseif($this->timespanIsOver($this->options['eFrom'], $this->options['eTo'])) {
					//Außerhalb der Zeitzone nach dem Endzeitpunkt - normaler Status ist aus, und Status wird gewechselt
					FrontendMenu::disablePermanently();
					return false;
				}

				//Inerhalb der Zeitzome - spezieller Status: an
				return true;

		}
	}


	/**
	 * Handles redirection based on current settings
	 */
	public function handle() {
        $isNotAdmin = !current_user_can('install_plugins');

		if ($isNotAdmin && $this->isActive()) {
			$this->redirect();
		}
	}

	/**
	 * Checks if the until date is over - so Wp-Lock will change its state
	 *
	 * @param string $date - any date string ('YYY-MM-DD')
	 * @return bool
	 */
	public function untilIsOver($date) {
		$untilDate = new Carbon($date, 'Europe/Berlin');
		$currentDate = new Carbon('now', 'Europe/Berlin');
		return $untilDate->lt($currentDate);
	}

	/**
	 * Checks if the for date is over - so Wp-Lock will change its state
	 *
	 * @param string $lastUpdatedDate Date when the last update of settings occured in the backend
	 * @param int $value Value of current setting
	 * @param int $unit Unit of current setting (0=Minutes, 1=Hours, 2=Days, 3=Weeks)
	 *
	 * @return bool
	 */
	public function forIsOver($lastUpdatedDate, $value, $unit) {
		$currentDate = new Carbon('now', 'Europe/Berlin');
		$lastUpdatedDate = new Carbon($lastUpdatedDate, 'Europe/Berlin');
		switch($unit) {
			case 0: return $lastUpdatedDate->diffInMinutes($currentDate) > $value;
			case 1: return $lastUpdatedDate->diffInHours($currentDate) > $value;
			case 2: return $lastUpdatedDate->diffInDays($currentDate) > $value;
			case 3: return $lastUpdatedDate->diffInWeeks($currentDate) > $value;
		}
	}

	/**
	 * Checks if the timespan is reached
	 *
	 * @param string $lastUpdatedDate Date when the last update of settings occured in the backend
	 * @param int $value Value of current setting
	 * @param int $unit Unit of current setting (0=Minutes, 1=Hours, 2=Days, 3=Weeks)
	 *
	 * @return bool
	 */
	public function timespanIsReached($from, $to) {
		$currentDate = new Carbon('now', 'Europe/Berlin');
		$from = Carbon::parse($from, 'Europe/Berlin');
		return $currentDate->gte($from);
	}

	/**
	 * Checks if the timespan is over
	 *
	 * @param string $lastUpdatedDate Date when the last update of settings occured in the backend
	 * @param int $value Value of current setting
	 * @param int $unit Unit of current setting (0=Minutes, 1=Hours, 2=Days, 3=Weeks)
	 *
	 * @return bool
	 */
	public function timespanIsOver($from, $to) {
		$currentDate = new Carbon('now', 'Europe/Berlin');
		$from = Carbon::parse($from, 'Europe/Berlin');
		$to = Carbon::parse($to, 'Europe/Berlin');
		return $currentDate->gte($to);
	}

	/**
	 * Get the style url for frontend display
	 *
	 * @return string the url
	 */
	private function getStyleUrl() {
		return plugins_url('aweos-wp-lock', 'aweos-wp-lock') . '/styles/style.css';
	}

	/**
	 * Do final redirect
	 */
	private function redirect() {
		?>
		<!doctype html>
		<html>
			<head>
				<title>This site is currently offline.</title>
				<link rel="stylesheet" href="<?php echo $this->getStyleUrl(); ?>" type="text/css">
			</head>
			<body>
				<div id="wplog-message">
					<?php echo nl2br($this->options['message']); ?>
					<?php
						$logo = get_option('wpLockLogo');
						if ($logo) {
					?>
					<br>
					<br>
					<img alt='logo' src='<?php echo $logo ?>' style='width: 200px; heigth: auto;'>

				<?php } ?>
				</div>
			</body>
		</html>
		<?php
		exit;
	}
}
