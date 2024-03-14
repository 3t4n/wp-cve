# TODO

The following list comprises ideas, suggestions, and known issues, all of which are in consideration for possible implementation in future releases.

***This is not a roadmap or a task list.*** Just because something is listed does not necessarily mean it will ever actually get implemented. Some might be bad ideas. Some might be impractical. Some might either not benefit enough users to justify the effort or might negatively impact too many existing users. Or I may not have the time to devote to the task.

* Add constants to facilitate use of plugin in network mode (+ filter to allow custom overrides)
* Add setting to restrict extended duration settings to admins only (or, rather those enabled via filter or capability - `user_can( 'extended_remember_me_duration' )`
  See: https://wordpress.org/support/topic/feature-request-only-for-admins/
* Allow admin to select display and input units for remember me duration.
  * Always save in hours, but also save the units selected by the user in the UI.
  * Based on the chosen unit, convert accordingly to hours when saving value, and convert from hours to that unit when displaying in the settings form.
  * Units should be hours (default) and days. Maybe also weeks, months, years.
* Maybe: Add a setting checkbox to allow forcing login duration changes to take effect immediately. Note that this will immediately log out all existing users, including current user.
  ```
  public static function logout_all_users() {
		$users = get_users();
		foreach ( $users as $user ) {
			$sessions = WP_Session_Tokens::get_instance( $user->ID );
			$sessions->destroy_all();
		}
	}
  ```
* As duration input value is changed, provide an inline human-readable conversion of the value via JS.
* Add ability for admins to allow users to set length of login sessions?
  * Add a setting to toggle feature
  * Add ability to define preset options (e.g. "1 month", "3 months", "6 months" ) for users to choose and which is to be the default
  * Add setting to user profiles
  * Add filter to allow filtering per-user setting value
* For settings page banner summarizing current login session duration, should it also indicate how that value is set? (E.g. WP default, WP default due to remember me being disabled, from being remembered forever, from configured duration).

Feel free to make your own suggestions or champion for something already on the list (via the [plugin's support forum on WordPress.org](https://wordpress.org/support/plugin/remember-me-controls/) or on [GitHub](https://github.com/coffee2code/remember-me-controls/) as an issue or PR).