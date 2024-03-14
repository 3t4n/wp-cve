# WP Term Icons

Pretty icons for categories, tags, and other taxonomy terms

WP Term Icons allows users to assign icons to any visible category, tag, or taxonomy term using a fancy icon picker, providing a customized look for their taxonomy terms.

# Installation

* Download and install using the built in WordPress plugin installer.
* Activate in the "Plugins" area of your admin by clicking the "Activate" link.
* No further setup or configuration is necessary.

# FAQ

### Does this plugin depend on any others?

Yes. Please install the WP Term Meta plugin.

### Does this create new database tables?

No. There are no new database tables with this plugin.

### Does this modify existing database tables?

No. All of WordPress's core database tables remain untouched.

### Can I query for terms by their `icon`?

Yes. Use a `meta_query` like:

```
$terms = get_terms( 'category', array(
	'depth'      => 1,
	'number'     => 100,
	'parent'     => 0,
	'hide_empty' => false,

	// Query by icon using the "wp-term-meta" plugin!
	'meta_query' => array( array(
		'key'   => 'icon',
		'value' => 'dashicons-networking'
	) )
) );
```

### Where can I get support?

The WordPress support forums: https://wordpress.org/tags/wp-term-icons/

### Can I contribute?

Yes, please! The number of users needing more robust taxonomy visuals is growing fast. Having an easy-to-use UI and powerful set of functions is critical to managing complex WordPress installations. If this is your thing, please help us out!
