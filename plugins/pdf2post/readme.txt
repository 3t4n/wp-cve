=== PDF 2 Post ===
Contributors: munger41
Tags: bulk, pdf, post, 2, to, convert, mass, automatic, pdf2post, pdftopost, multisite
Requires at least: 4.0
Tested up to: 5.2

Bulk convert PDF documents to posts (imports all text and images - and attach images automatically to newly created posts).

== Description ==

Mass convert PDF documents to WP posts, by:

* extracting all text data and adding it to post content
* extract all images included in PDF and attach them to post
* automatically add featured image
* create gallery inside post content from all images extracted

Be carefull, you NEED to have installed on your server the following:

* [ZipArchive](http://php.net/manual/fr/class.ziparchive.php "ZipArchive")
* [PDFMiner](http://www.unixuser.org/~euske/python/pdfminer/ "PDFMiner")
* [pdfimages](https://en.wikipedia.org/wiki/Pdfimages "pdfimages")

Works on multisite installs.

[>> Test your document here <<](https://www.indesign2wordpress.com/pdf-wp-post/ "Test")

If you need a more professional solution, we now have a premium InDesign to Wordpress plugin:
[>> InDesign to Wordpress <<](https://www.indesign2wordpress.com/convert-html-document-to-wordpress-post/ "Demonstration")

== ToDo ==

Will need to be upgraded with : https://github.com/pdfminer/pdfminer.six

== Installation ==

### Easy ###

1. Search via plugins > add new.
2. Find the plugin listed and click activate.
3. Use the Shortcode

### Usage ###

1. Go to Posts>New Post From PDF
2. Choose file to upload :

* single .pdf
* .zip containing a main folder with multiple .pdf files inside)

3. Clic "Create Post" and wait for post creation :)

[>> Test your document here <<](https://www.indesign2wordpress.com/pdf-wp-post/ "Test")

If you need a more professional solution, we now have a premium InDesign to Wordpress plugin:
[>> InDesign to Wordpress <<](https://www.indesign2wordpress.com/convert-html-document-to-wordpress-post/ "Demonstration")

== Changelog ==

2.4.0 - trying to work on bounding boxes

2.3.1 - better processing method first

2.3.0 - new demo shortcode and several bugfixes

2.2.3 - select type of post created among any of the site

2.2.2 - access given to editors and added for pages as well

2.2.1 - xml pdf2txt with paragraphs extraction

2.2.0 - others pdf2txt types added : html, xml, tag

2.1.4 - more debug to help users

2.1.1 - more checks on softs installed

2.0.0 - Totally refactored code because of bug uploading zip files

1.3.1 - few more check for more robustness

1.3 - check if zip installed

1.2 - check if python installed

1.1 - Automatically create gallery with all images of PDF file

1.0 - First stable release.