GUI is for suckers. The essential system adminy tools of Apocalypse Meow are available through the command line via [WP-CLI](https://wp-cli.org/).

You can access examples and documentation for every function the usual CLI way, e.g.
```
wp meow --help
```



## Activity

Apocalypse Meow records every login attempt, successful or not, in order to detect and mitigate brute-force attacks conducted against the web site. The "activity" subcommand broadly encompasses activity-related items, with the notable exception of jail contents, which falls under [jail](#wp-cli--jail).



### wp meow activity list

Display and/or export the login activity records.

```
wp meow activity list [--from=<mindate>] [--to=<maxdate>] [--type=<type>] [--reverse] [--limit=<num>] [--export=<path>] [--overwrite]
```

#### Arguments

 * (string) **--from**: Earliest date.
 * (string) **--to**: Latest date.
 * (string) **--type**: Filter records by type, either `"ban"`, `"fail"`, or `"success"`. Default is to show everything.
 * (bool) **--reverse**: Reverse the display order (i.e. newest to oldest).
 * (int) **--limit**: Limit the number of records returned to X.
 * (string) **--export**: Dump the records to a CSV or XLS at this location.
 * (bool) **--overwrite**: Overwrite the export file if it exists.



### wp meow activity prune

Apocalypse Meow supports automatic database pruning to help keep WordPress from drowning in login failure data. You can, however, manually prune the database via CLI.

```
wp meow activity prune <limit|all>
```

**Limit**, here, is a cutoff age, either a date or a number of days. Anything older than that will be deleted. Passing the word `"all"` will remove everything.

```
# Example usage:
wp meow activity prune 2015-01-01
wp meow activity prune 60
wp meow activity prune all
```



### wp meow activity sessions

WordPress generates a unique Session ID each time a user logs into the site. Aside from providing some useful diagnostic information, such as browser and network information, it also provides a server-side mechanism for continually revalidating the session (i.e. regardless of whether or not the user's computer has the right cookie).

This tool allows session records to be displayed and/or exported.

See also: [wp meow security revoke-session](#wp-cli--wp-meow-security-revoke-session)

```
wp meow activity sessions [--user_login=<id|login|email>] [--relative] [--export=<path>] [--overwrite]
```

#### Arguments

 * (int|string) **--user_login**: Only show sessions belonging to a particular user, identified by ID, login/username, or email address.
 * (bool) **--relative**: Display session expiration times relative to now.
 * (string) **--export**: Dump the records to a CSV or XLS at this location.
 * (bool) **--overwrite**: Overwrite the export file if it exists.



## Jail

The primary function of Apocalypse Meow is to detect and mitigate brute-force login attacks being conducted against the site. This is accomplished by tracking failed login attempts and temporarily banning offending network addresses.

The jail is where offenders go to wait out their sentence.



### wp meow jail list

Display and/or export the jail's current occupants.

```
wp meow jail list [--relative] [--export=<path>] [--overwrite]
```

#### Arguments

 * (bool) **--relative**: Display session expiration times relative to now.
 * (string) **--export**: Dump the records to a CSV or XLS at this location.
 * (bool) **--overwrite**: Overwrite the export file if it exists.



### wp meow jail add

Ban one or more network addresses for a specified period of time.

```
wp meow jail add <IP|Subnet>... [--expires=<datetime>]
```

Specify one or more IP addresses or subnets, along with an optional ban expiration time (**--expires**). If no expiration is passed, the ban will last for the duration of the fail window.



### wp meow jail remove

Remove one or more network addresses from the jail, i.e. pardon them.

```
wp meow jail remove <IP|Subnet>...
```



### wp meow jail whitelist

Add or remove one or more network addresses, ranges, etc., to/from the global whitelist. The login history for whitelisted entries is still tracked, but they'll never be banned, no matter how bad they get. :)

```
wp meow jail whitelist <IP|Subnet>...
```

To remove a network, prefix the entry with `"-"`; to add an entry, enter it normally or prefix it with `"+"`.



### wp meow jail blacklist

Add or remove one or more network addresses, ranges, etc., to/from the global blacklist. Blacklisted network addresses are never allowed to login. :)

```
wp meow jail blacklist <IP|Subnet>...
```

To remove a network, prefix the entry with `"-"`; to add an entry, enter it normally or prefix it with `"+"`.



## Security

This subcommand more or less comprises the contents of the "Tools" admin page. This section will likely be expanded quite a bit with future plugin releases. So much to do!



### wp meow security rename-user

WordPress usernames are set in stone. Historically, to workaround this limitation it was necessary to create a new user, login as that user, delete the original user, and reassign all content to the new one.

But that's stupid.

This tool simply renames the login for an existing user, preserving the original user ID, etc.

```
wp meow security rename-user <old_username> <new_username>
```

Note: this may or may not invalidate any active sessions associated with **old_username**.



### wp meow security reset-passwords

This will immediately reset all user passwords site-wide. To regain account access, each user will need to complete the "Forgot Password" process.

If your site or database has been breached, or you suspect it has, run this tool immediately.

Note: this operation does not trigger any email notifications to affected users. (Email functionality is not always available in CLI mode.)

If you need to communicate the change to your users, please use the web-based, wp-admin version instead.

```
# This does not take any arguments. Just run it:
wp meow security reset-passwords
```



### wp meow security revoke-md5-passwords

For historical reasons, WordPress has retained backward compatibility with the outdated MD5 hashing algorithm. Should a hacker obtain a copy of your users table, any user with an MD5-hashed password could be in serious trouble.

This tool will override any insecure MD5 password hash with securely-hashed gibberish. This will lock affected users out of their account (until they reset their passwords), however these users have likely been absent from the site for many years.

```
# This does not take any arguments. Just run it:
wp meow security revoke-md5-passwords
```



### wp meow security revoke-session

WordPress generates a unique Session ID each time a user logs into the site. Any given user might have one or more active sessions if they have accessed the site from multiple devices.

Use this tool to revoke a single user session, or all sessions for a given user. Any devices connected to those sessions will then be immediately logged out.

```
wp meow security revoke-session <user_id|user_login|user_email> [<session_id>]
```

As with the [wp meow activity sessions](#wp-cli--wp-meow-activity-sessions) function, the user can be identified by an ID, username/login, or email address. To remove a single session, pass that session's ID, otherwise all sessions for the user will be removed.



## Settings

For the most part, plugin settings are best managed through the wp-admin interface (every feature is thoroughly documented there). Nonetheless, there are a few fun settings bits that can be achieved through CLI:



### wp meow settings list

List the current plugin settings and whether or not they are readonly (i.e. defined via PHP constants.)

```
# This does not take any arguments. Just run it:
wp meow settings list
```



### wp meow settings export

This will export the current plugin settings to a JSON file which you can either keep as a handy backup or use to pre-configure another instance.

```
wp meow settings export [<path>] [--overwrite]
```

#### Arguments

 * (string) **path**: The export path. This defaults to `domain.com-apocalypse-meow.json`.
 * (bool) **--overwrite**: Overwrite the export path if it exists.



### wp meow settings import

This will import a settings export file, overwriting any settings stored in the database. For best results, the export and import should be run from the same version of the plugin.

Note: settings are always re-validated at load time; changes in environment, etc., – including hard-coded constants – might result in some variation.

```
wp meow settings import [<path>]
```

Just feed it the path to the JSON export. If left blank, it will assume the same `domain.com-apocalypse-meow.json` default.



## Other

For lack of a better filing system, here's the rest!



### wp meow version

Display information about the Apocalypse Meow plugin, namely version and upgrade information.

```
# This does not take any arguments. Just run it:
wp meow version
```
