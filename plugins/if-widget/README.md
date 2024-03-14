If Widget
=========

**If Widget** is a WordPress plugin which adds extra functionality for widgets, making it easy to hide or display them based on visibility rules. Examples:

* Display a widget only if current `User is logged in`
* Hide widget if `visiting from mobile device`
* Display widgets only for `Admins and Editors`

The plugin is easy to use, each widget will have a “Show widget only if” option which will enable the selection of visibility rules.

> This repo is used only for development, downloading & installing from here won't work as expected. Install from [WordPress.org plugin page](https://wordpress.org/plugins/if-widget/)


## Features

* Basic set of visibility rules
  * User state `User is logged in`
  * User roles `Admin` `Editor` `Author` etc
  * Page type `Front page` `Blog page`
  * Post type `Post` `Page` `Product` etc
  * Device `Is Mobile`
* Multiple rules - mix multiple rules for a widget visibility
  * show if `User is logged in` AND `Device is mobile`
  * show if `User is Admin` AND `Is Front Fage`
* Support for adding custom visibility rules



## Adding custom visibility rules in a plugin or theme

Custom visibility rules can be added easily by any plugin or theme.
Example of adding a new rule for displaying/hiding a widget when current page is a custom-post-type.

```
// theme's functions.php or plugin file
add_filter('if_visibility_rules', 'my_new_visibility_rule');

function my_new_visibility_rule($rules) {

  $rules['single-my-custom-post-type'] = array(
    'name'      =>  __('Single my-CPT', 'i18n-domain'),     // name of the condition
    'callback'  =>  function() {                            // callback - must return Boolean
      return is_singular('my-custom-post-type');
    }
  );

  return $rules;
}
```


## WordPress.org

Head over to [plugin's page on WordPress.org](https://wordpress.org/plugins/if-widget/) for more info, reviews and support.
