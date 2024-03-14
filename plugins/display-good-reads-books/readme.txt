=== Good Reads Books ===
Contributors:      davidsword
Donate link:       http://www.redcross.ca/donate/
Tags:              Goodreads, goodreads.com, Good Reads, goodread, show books, kindle, currently reading, bookshelf, bookshelves, books read, amazon, paperwhite, oasis, voyage, e-reader, readers, bookies
Requires at least: 4.0
Tested up to:      4.9.1
Stable tag:        1.2

Showcase currently reading and recently read Goodreads books on your website.


== Description ==

= Showcase reading and read Goodreads shelves on your website. =

* Fully Responsive
* Select how many books to show in a row
  * Will display all currently reading
  * Rest of space filled with recently read
* Updates shelves daily
* Colour, or, Black & White covers (for the true Kindle experience)

= Usage =

* After setting up, use `[goodreads]` shortcode to display

= Please Note =

* 🍺 This is a new plugin, please [open a support request](https://wordpress.org/support/plugin/display-good-reads-books) before summiting a negative review, *I'm happy to help,* please provide as much information as possible (books that are causing an issue, theme currently used, your GoodReads profile URL, your GoodReads API key, Wordpress and PHP version).

---


== Installation ==

1. Install the plugin from your Plugin browser, or download the plugin and extract the files and upload `good-reads-books` to your `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. View the *Good Reads Books* interface under *Settings* in your Wordpress Admin
1. Use the `[goodreads]` shortcode where you want to show your shelves


== Frequently Asked Questions ==

= Why are some covers cropped? =

The books are currently being displayed at a `1:1.55` ratio. Not all book covers are this ratio, so minor clipping may occur as the image is set to fill the space the plugin provides for it.

= What is this warning about `CURL` about? =

`CURL` is a library of PHP that is used to access data from other websites. For this plugin, we need to access Goodreads API and retrieve the data for your shelves. Without `CURL` this plugin cannot work.

= The Books are really small on smart phone? =

The displaying of the books is lateral, allocating as many slots as selected in the admin settings page. This allocation shrinks equally as the container width lowers, and the covers ratios are maintained. If you've selected a high number of books, this may result in the thumbnails looking too small on smaller phones.

This is by design, but I'm open to changing it in future version if the request is there. In the mean time, try reducing your "Show x Total Books" numbers.

= Some books are blank, just the "G" logo? =

Due to licensing, sadly some image covers are not available through Goodreads API. If a books image is blank, you can define it in the settings page under "Exceptions" with your own cover.

To get the ID of the book, hover the cover of the book with your cursor on your website.

= Why do you call it "Good Reads Books" instead of "Goodreads Books" ? =

The fine fellows at Goodreads in their API agreement state that no one can't use "Goodreads" in an app title. Yet "Good Reads" is fine.. legal stuff is so silly sometimes.


== Screenshots ==

1. Running out of the box on Twenty-Fifteen
2. Running out of the box iPhone 6s emulator on Twenty-Fifteen theme
3. Settings page
4. As a sticky post in a custom theme


== Changelog ==

= 1.2 =
* Dec 6, 2017
* added: user ID parser logic to better extract member/authors who have custom usernames (thanks Andy E!)
* added: a "Refresh" AJAX button in admin. Though saving page refreshes plugin, this button makes it easier. 
* added: new error handler for api returning a 404 (odd, but it can happen!)
* changed: rewrote most inline field description instructions
* changed: B&W covers now optional
* fixed: properly localized text that contained variables/html

= 1.1 =
* July 6, 2016
* fixed use of php shorttag that caused undefined var for credit on some server setups

= 1.0 =
* July 1, 2017
* Initial build


== Upgrade Notice ==

= 1.2 =

* Black & White books covers will show as colour now. Theres a new option in settings to make black and white

= 1.1 =

* All good

== Road Map ==

= The current todo list: =

* Make the **Select Media Item** input with upload instead of text input for cover exemptions
* Better default CSS for headings

Please add any additional requests into [the Support tab](https://wordpress.org/support/plugin/display-good-reads-books).