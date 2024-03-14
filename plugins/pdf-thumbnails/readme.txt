=== PDF Thumbnails ===
Contributors: stianlik, mirgcire
Tags: pdf,thumbnail,generator
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Stable tag: 2.2.0
Tested up to: 4.6

This plugin generates a thumbnail everytime you upload a PDF attachment.
Generated thumbnail is an image of the first page in uploaded document.

== Description ==

This plugin hooks into the media manager and generates a thumbnail everytime a
PDF is uploaded. Generated thumbnail is an image of the first page in the
uploaded document and is named `PDFNAME-thumbnail`, where `PDFNAME` is replaced
by uploaded document filename.

Generated thumbnails are equivalent to [featured
images](https://codex.wordpress.org/Post_Thumbnails) so that common thumbnail
functions like `get_post_thumbnail_id()` can be used for PDF attachments. See
[Post Thumbnails](https://codex.wordpress.org/Post_Thumbnails) for information
on how you can use thumbnails efficiently.

Integration with the javascript media API is not yet implemented, therefore, you
may need to reload the page before you can see generated thumbnail after an
upload.

= Shortcodes =

It is possible to display a thumbnail linking to an uploaded PDF using the `[pdf_thumbnails_link]`
shortcode. The following attributes are supported:

* `id` - Attachment ID (required)
* `size` - [Thumbnail size](https://codex.wordpress.org/Post_Thumbnails#Thumbnail_Sizes) (optional)
* `title` - [Anchor title attribute](https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes#attr-title) (optional)
* `target` - [Anchor target attribute](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a#attr-target) (optional)
* `download` - [Anchor download attribute](https://developer.mozilla.org/en-US/docs/Web/HTML/Element/a#attr-download) (optional)

Example 1: Display link to PDF with ID = 172 using [default thumbnail size](https://codex.wordpress.org/Post_Thumbnails#Thumbnail_Sizes)

    [pdf_thumbnails_link id="172"]

Example 2: Display link to PDF with ID = 172 using thumbnail size (default 150x150)

    [pdf_thumbnails_link id="172" size="thumbnail"]

Thanks to [mirgcire](https://wordpress.org/support/profile/mirgcire) for providing the first
version of the `[pdf_thumbnails_link]` shortcode.

= Developer API =

In most cases it should be sufficient to use built-in thumbnail functions from
the WordPress API (`get_post_thumbnail` and similar). If you need to modify the
way thumbnails are generated, you can override image generation with
the `pdf_thumbnails_generate_image_blob` filter.

Example 1: Increase resolution for all generated PDF thumbnails

    // $blob is the current image blob (defaults to null, can be used for chaining)
    // $filename is the PDF filename
    add_action('pdf_thumbnails_generate_image_blob', function ($blob, $filename) {
        $imagick = new Imagick();
        $imagick->setResolution(200,200);
        $imagick->readImage($filename);
        $imagick->setIteratorIndex(0);
        $imagick->setImageFormat('jpg');
        return $imagick->getImageBlob();
    }, 10, 2);

It is possible to modify generated thumbnail links using the `pdf_thumbnails_link_shortcode`
filter. The following attributes are available:

* `$html` - Generated HTML code to be displayed
* `$attachmentId` - Sanitized ID of the PDF attachment
* `$size` - Sanitized thumbnail size
* `$atts` - [Shortcode attributes (not sanitized)](https://codex.wordpress.org/Shortcode_API#Handling_Attributes)
* `$content` - Shortcode content (not sanitized)

Example 2: Wrap thumbnail link in figure and append caption

    add_filter('pdf_thumbnails_link_shortcode', function ($html, $attachmentId, $size, $atts, $content) {
        return "<figure>$html <caption>Click to open image $attachmentId</caption></figure>";
    }, 10, 5);

== Installation ==

PDF Thumbnails requires ImageMagick with GhostScript support. If you are lucky,
this is already installed on your system, otherwise, installation can be done
with the following steps:

1. Install ghostscript
2. Install imagemagick with ghostscript support
3. Install PHP extension for imagemagick (can use pecl)
4. Restart web server for changes to take effect

Details may differ based on which operating system you are running, see
[Support](https://wordpress.org/support/topic/nothing-but-error-messages) for
more resources and tips on how this can be done in Windows, Linux and OSX.

= Debian / Ubuntu =

`
sudo apt-get install ghostscript php5-imagick
sudo service apache2 restart
`

== TODO ==

Add generated image to media browser after upload. 

Outline of an implementation based on the javascript media API:

`
// New uploads
wp.Uploader.queue.on('add', function (attachment) {

    if (attachment.subtype !== 'pdf') {
        return;
    }

    findThumbnailFor(attachment.ID).then(function (data) {

        // Add attachment thumbnail to browser
        var attachment = wp.media.model.Attachment.get(id)
        attachment.fetch().done(function () {
            wp.media.editor.open().state().get('library').add(generated attachment)
        });

    });
});
`

Filter: ajax_query_attachments_args

== Changelog ==

This plugin use [semantic versioning](http://semver.org/), i.e. breaking changes
increase the MAJOR version.

= 2.2.0 =
* Support title, target, and download attributes in `pdf_thumbnails_link` shortcode

= 2.1.0 =
* Support thumbnail links with the `pdf_thumbnails_link` shortcode
* Support link customization using the `pdf_thumbnails_link_shortcode` filter

= 2.0.0 =
* Replaced `pdf_thumbnails_before_get_image_blob` hook with `pdf_thumbnails_generate_image_blob` filter

= 1.0.2 =
* Introduced `pdf_thumbnails_before_get_image_blob` hook
