# Davidson Domains Community Portal

## About

This is a Wordpress plugin that exposes metadata about sites to [Site Maker](https://github.com/woodwardtw/sites), a Wordpress plugin that grabs data about Davidson Domains sites and displays them on the [Davidson Domains Community Portal](http://domains.davidson.edu/community).

### How it works

#### Plugin 1: Davidson Domains Tagger

`Davidson Domains Tagger` is the plugin that users install to their Wordpress websites. It lets `Site Maker` know that the user's site should appear in the Community Portal. Further, this plugin allows users to and allows users to provide `Site Maker` with meta data about the site, like content type, student graduation year, etc.

##### To install the plugin on a Wordpress site:

- User logs into their Wordpress site.
- User uploads the [plugin .zip file](https://github.com/DavidsonCollege/davidson-domains-meta/archive/master.zip) to their Wordpress site and [enables](https://codex.wordpress.org/Managing_Plugins) the plugin.
- In the admin panel, user travels to `Tools->Davidson-Domains-Meta` and selects the applicatable tags.
- Once selected, the plugin exposes these tags to api via the `/wp-json/` route. (Example: myblog.com/wp-json/)
- User waits a day or two for Site Maker to add or update their site on the Domains Community Portal.

`Code Owners: John-Michael Murphy (plugin), JD Mills (AWS).`

##### To update the tags displayed in the plugin:

- Admin logs into Davidson's AWS instance and updates the database.
- The new (or removed) tag will appear across all instances of the plugin immediately.

#### Plugin 2: Site Maker

This is the plugin that runs on the Community Portal. It searches Davidson Domains for sites with `Davidson Domains Tagger` plugin installed. If it finds a site with the plugin installed, it adds it to the Community Portal and tags the site according to the plugin's specification. It searches for new sites and updates old sites every 24 hours, however, an admin can trigger an update by navigating to `https://domains.davidson.edu/community/load`.

`Code Owner: Tom Woodward.`

##### To Enable:

- Admin downloads [plugin .zip file](https://github.com/woodwardtw/sites/archive/master.zip).
- Admin logs into `domains.davidson.edu/community/wp-admin` and installs and enables the plugin.
- Admin ensures that the maximum script timeout is >15 minutes.
- Admin communicates with Reclaim Hosting to setup a cron job that dumps a list of websites registered in Davidson Domains into `/home/devreclaim/davidsoninstalls.json` of the Community Portal file system.
