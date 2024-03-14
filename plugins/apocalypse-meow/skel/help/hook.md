## Actions ##

The following actions can be used to leverage Apocalypse Meow to track arbitrary login successes and failures in any custom form. The usual plugin settings for things like fail windows, etc., still apply.

### meow_log_ban ###

This action causes an IP address or subnet to be temporarily banned from logging in.

```
<?php
do_action('meow_log_ban', $args);
```

#### Parameters
 * (array) **$args**: an array containing the following arguments:
  * (string) (*optional*) 'date_expires'**: the ban's expiration in `YYYY-MM-DD HH:MM:SS` format. Default: `NOW + fail_window`
  * (string) (*optional*) 'ip'**: an IP address OR...
  * (string) (*optional*) 'subnet'**: a subnet

The arguments must contain an IP address *or* a subnet; you should not pass both. If neither are passed, the current visitor's IP will be used.

This does not need to be explicitly triggered if you are already triggering login successes and failures; if the critical number of failures is reached for a given network address, a ban will automatically be issued.

While this does record a ban in the database, it will not automatically prevent robots or evildoers from accessing a custom login form. Refer to the filters `meow_is_banned` and `meow_failures_remaining` for accessing the current ban state.

### meow_log_fail ###

This action adds a failure to the login records.

```
<?php
do_action('meow_log_fail', $args);
```

#### Parameters
 * (array) **$args**: an array containing the following arguments:
  * (string) (*optional*) 'ip'**: an IP address. Default: current visitor IP
  * (string) (*optional*) 'username'**: a username

### meow_log_success ###

This action adds a success to the login records.

```
<?php
do_action('meow_log_success', $args);
```

#### Parameters
 * (array) **$args**: an array containing the following arguments:
  * (string) (*optional*) 'ip'**: an IP address. Default: current visitor IP
  * (string) (*optional*) 'username'**: a username

---

The following actions are triggered by Apocalypse Meow, allowing you to perform further actions, such as event logging.

### meow_logged_ban ###

This action is fired when an IP address or subnet is temporarily banned from logging in.

This action comes with a single argument, an array, containing the following fields:

```
<?php
// Example arguments.
$args = array(
    'ip'=>'', // The IP address.
    'subnet'=>'', // The subnet.
    'date_created'=>'0000-00-00 00:00:00', // The creation datetime.
    'date_expires'=>'0000-00-00 00:00:00', // The expiration datetime.
    'type'=>'ban', // The type of action performed.
    'username'=>'', // The username submitted, if any; this does not apply to "ban".
    'count'=>1, // The number of times the ban error was shown to a blocked visitor, e.g. "persistence". This is only meaningful for "ban" actions.
);

// Use it like:
add_action('meow_logged_ban', 'my_callback_function', 10, 1);
function my_callback_function($args) { ... }
```

Because a ban applies to an IP address *or* a subnet, but not both, one will contain a proper value, the other will be set to `"0"`. For fail and success types, both values will be set.

### meow_logged_fail ###

This action is fired when someone has submitted an invalid login attempt.

This action comes with a single argument, an array. See `meow_logged_ban` for details.

### meow_logged_success ###

This action is fired when someone has successfully logged in.

This action comes with a single argument, an array. See `meow_logged_ban` for details.

---

## Filters ##

The following filters can be used to access Apocalypse Meow data in a clean way. They aren't necessarily intended to be hooked into via `add_filter()`, but I suppose you could. :)

### meow_failures_remaining ###

Returns the number of failures remaining for a given visitor. Both IP- and Subnet-based limits are checked; the lower of the two values (i.e. the one that will hit sooner) is returned.

```
<?php
// Usage example:
$remaining = apply_filters('meow_failures_remaining', 500, '44.44.44.44');
if ($remaining < 5) {
    echo sprintf('Be careful, you will be banned after %d more tries.', $remaining);
}
```

#### Parameters
 * (int) **$remaining**: the number of failures remaining
 * (string) (*optional*) **$ip**: the IP address to check. Default: current visitor IP

#### Returns

Returns an integer representing the remaining failures (before a visitor will be banned). If the IP address is whitelisted or otherwise not subject to a ban (because it is a local address, etc.), the value returned will always be the failure limit. 

### meow_is_banned ###

Check whether an IP address is currently banned or not.

```
<?php
// Usage example:
$banned = apply_filters('meow_is_banned', false, '44.44.44.44');
if ($banned) {
    wp_die('Sorry, you are not allowed to view this right now.');
}
```

#### Parameters
 * (bool) **$banned**: ban status
 * (string) (*optional*) **$ip**: the IP address to check. Default: current visitor IP

#### Returns

Returns `TRUE` if the IP is banned, `FALSE` if not. 

### meow_is_whitelisted ###

Check whether an IP address is part of the global whitelist.

```
<?php
// Usage example:
$whitelisted = apply_filters('meow_is_whitelisted', false, '44.44.44.44');
if ($whitelisted) {
    echo 'Fail as much as you want! Nobody can stop you!';
}
```

#### Parameters
 * (bool) **$whitelisted**: whitelist status
 * (string) (*optional*) **$ip**: the IP address to check. Default: current visitor IP

#### Returns

Returns `TRUE` if the IP is whitelisted or in any way not subject to bans, `FALSE` if not.
