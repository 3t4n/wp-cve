=== Form to Post ===
Contributors: msimpson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=NEVDJ792HKGFN&lc=US&item_name=Wordpress%20Plugin&item_number=cf7%2dto%2ddb%2dextension&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags:
Requires at least: 3.2.1
Tested up to: 4.4.1
Stable tag: 0.9

Create a WP Post from a Form Submission.

== Description ==

Create a WP Post from a Form Submission. Create a form using Contact Form 7, Fast Secure Contact Form, or just a plain
form, be sure to name your fields correctly, then the form submission will be sent to a post.

Very limited.

* Only accepts text, no images, videos, etc.
* No error handling.

Essentially you can think of this as a form wrapper around the <a href="http://codex.wordpress.org/Function_Reference/wp_insert_post">wp_insert_post function</a>.
The field name-value pairs of the form become inputs to a wp_insert_post call.

WARNING: using this plugin provides spammers the opportunity to send you automated spam form submissions.

<strong>How To</strong>

1. Create a form using Contact Form 7 (CF7), Fast Secure Contact Form (FSCF), or create your own form HTML.
1. Name your fields according to the parameters of the <a href="http://codex.wordpress.org/Function_Reference/wp_insert_post">wp_insert_post function</a>.

Minimally, your form must have the following two fields. A post will not be created if one or both is missing.

* post_title
* post_content

<strong>Special Fields</strong>

* post_category_name can be used to set the category of the post


As hidden field: If you want to make all the posts be of the same category, create a "post_category_name" hidden field
whose value is the category name. If using CF7, it does not provide
hidden fields. But you can add them by adding the plugin <a href="http://wordpress.org/extend/plugins/contact-form-7-modules/">Contact Form 7 Modules</a>.

As checkboxes: to make the category choosable by the user via a checkbox,
create a checkbox form field named "post_category_name" with each having a value that is a category name.
(If writing your own HTML directly, use "post_category_name[]", see "Plain Form Example" below).

CF7 Form Definition Example:

<pre><code>
Post Title [text* post_title] &lt;br/&gt;

Post Content (required) &lt;br/&gt;
   [textarea* post_content] &lt;br/&gt;

Categories (required) &lt;br/&gt;
  [checkbox* post_category_name "Uncategorized" "Cat1" "Cat2" "Cat3"] &lt;br/&gt;

[submit "Post"] &lt;br/&gt;
</code></pre>


Aside: if you are familiar with wp_insert_post, then you will know that there is a "post_category" parameter but no
"post_category_name". The problem with "post_category" is it requires category ids (the numbers). But what you really
want in a form are the category names. So this plug allows for "post_category_name" which can be one or more
category names. It looks up the associated category numbers and sets "post_category" for wp_insert_post.
But you can use "post_category" instead if you like. But do not use both in the same form.

* post_author_name

Whereas post_author requires an user id number, you can alternatively use post_author_name takes the login name.
(Same idea as post_category_name an an alternative to post_category).

* post_author_default

A weaker form of post_author_name, takes a login name. When not using post_author_name or post_author_default,
then an author will only be set on the post if a person is logged in. In that case his login is used. If you use post_author_name
then that ignores the user's login and sets the author to the post_author_name value. If you use post_author_default instead,
then it will use the user's login id if he is logged in, but if he is not logged in it will set the author to the value of post_author_default.


<strong>Not using CF7 nor FSCF</strong>
You can define your own form naming fields as described above. But you need to do one extra step in this case.
You need to have the target page of your form insert the data in the post. You do this by means of a short code [capture-form-to-post].
Simply place the short code on your form's target page and it will capture the submission and create a post.

Plain Form Example:

In this example, we create our own form, that posts to the same page. So we put both the short code and the form
definition in the same page. The short code only does something when there are post parameters.
NOTE: your form must have method="post" not "get".

<pre><code>
[capture-form-to-post]
&lt;form action="" method="post"&gt;
   Post Title: &lt;input type="text" name="post_title" value=""/&gt;&lt;br/&gt;
   Post Content: &lt;br/&gt;
   &lt;textarea rows="10" name="post_content" cols="20"&gt;&lt;/textarea&gt;
   &lt;input type="checkbox" name="post_category_name[]" value="Uncategorized"&gt;Uncategorized&lt;br&gt;
   &lt;input type="checkbox" name="post_category_name[]" value="Cat1"&gt;Cat1&lt;br&gt;
   &lt;input type="checkbox" name="post_category_name[]" value="Cat2"&gt;Cat2&lt;br&gt;
   &lt;input type="checkbox" name="post_category_name[]" value="Cat3"&gt;Cat3&lt;br&gt;
   &lt;input type="submit" /&gt;
&lt;/form&gt;
</code></pre>

Remember: do NOT use [capture-form-to-post] if your form is a CF7 or FSCF form.

<strong>Advanced:</strong>

There are many more parameters to wp_insert_post that can be set simply by putting a form fields in your form
of the same name as the wp_insert_post parameter. Examples are:

* post_status which will be set to 'publish' by default making the post published automatically. But you could set that in a hidden field to 'draft', 'publish', 'pending', 'future', 'private'
* comment_status which can be 'closed' or 'open'
* post_excerpt
* post_date (format: <a href="http://php.net/manual/en/function.date.php">Y-m-d H:i:s</a>, e.g. "2012-01-01 15:30:00")
* And many more, see <a href="http://codex.wordpress.org/Function_Reference/wp_insert_post">wp_insert_post function</a>
* page_template: set to the name of a page template file (e.g. "new_template.php").
* NOTE: tax_input is NOT supported.
* NOTE: If you would want to edit a form, you would need to get the post's ID and put it in a form 'ID' field.

<strong>Setting Post Meta (Custom Fields):</strong>

You can optionally set "post meta" (custom field) key-value pairs on your post. To do this, add fields to your form whose name start
with "meta_". For example, if you want to set the post meta key "my_key" then create a form field named "meta_my_key".
The "meta_" prefix is used to identify the field as a post meta field and the "meta_" gets stripped off. Then a call
to <a href="http://codex.wordpress.org/Function_Reference/update_post_meta">update_post_meta</a>
is made give that key and the field's value.

<strong>Setting Values on the Server Side Using a Filter:</strong>

Example Situation 1: you want to set the post_status set to 'pending' so that you can review posts before they are published.
However, you don't want to put that as a field in your form because you are concerned about a hacker changing its value 'published'.
You want to control that on the server side, not in the form in the user's browser.

Example Situation 2: you want to apply some logic about whether a post is automatically published (perhaps based on the user's login).

To address these situations, Form To Post provides a WordPress filter where you can add PHP code.

* The filter name is <code>form_to_post_before_create_post</code>
* A filter function takes 1 array parameter, which will be the $post array passed to the
<a href="http://codex.wordpress.org/Function_Reference/wp_insert_post">wp_insert_post function</a>

Example Situation 1 Solution:
In your theme or using
<a href="https://wordpress.org/plugins/add-actions-and-filters/">Add Shortcodes Actions And Filters</a>
plugin, add a filter function and register it to the
form_to_post_before_create_post hook.

<code>
function form_to_post_set_values($post) {
    $post['post_status'] = 'pending';
    return $post;
}

add_filter('form_to_post_before_create_post', 'form_to_post_set_values');
</code>

<strong>Setting Custom Fields Server Side Using a Filter:</strong>

You will need a different hook to set custom fields. Here is an example.

<code>
function form_to_post_set_meta($post) {
    $post['order-status'] = 'Order received';
    return $post;
}

add_filter('form_to_post_before_update_post_meta', 'form_to_post_set_meta');
</code>

Notice: you do <em>not</em> prefix the custom fields values with "meta_" in this case.
Only do that when you are setting them in the form itself.


<strong>Dynamically Manipulating the Form Data After Submission</strong>

An advanced application is to manipulate the raw form data just before the plugin uses it.
The hook form_to_post_form_data allows this. Functions subscribing to this hook take one argument
which is the form data. The data structure is the same as that in <a href="http://cfdbplugin.com/?page_id=646">CFDB Plugin</a>
(another one of my plugins).

Example: you don't have a "post-content" or "post-title" field in your form, but you want to set those values as a
combination of values from other form fields. In this simply example, our form has field "your-name" and "your-email".
The hook is used to create the "post-content" or "post-title" fields to be text with the value of fields
"your-name" and "your-email" embedded in them.

<code>
function f2p_consolidate_fields( $form_data ) { // change the function name as needed
    $form_title = 'F2P With Hook Example'; // Change this to your form name
    if ($form_data->title = $form_title) {

        // Next line sets the post_title value
        $form_data->posted_data['post_title'] = "Post from {$form_data->posted_data['your-name']}";

        // Next line sets the post_content value
        $form_data->posted_data['post_content'] =
            "This is a post from {$form_data->posted_data['your-name']} with email {$form_data->posted_data['your-email']}";
    }
    return $form_data;
}
add_filter( 'form_to_post_form_data', 'f2p_consolidate_fields', 10, 1 ); // make sure the function name matches above

</code>

== Installation ==


== Frequently Asked Questions ==


== Screenshots ==


== Changelog ==

= 0.10 =
* Added new hook form_to_post_form_data

= 0.9 =
* Added new hook form_to_post_before_update_post_meta

= 0.8 =
* Bug Fix: Radio and Checkbox values from CF7 were not being processed properly

= 0.7 =
* Added a filter 'form_to_post_before_create_post' to post-process form submission values prior to creating the post.

= 0.6 =
* If user is logged when submitting a form then it automatically makes him the post author

= 0.5 =
* Updated to work with changes in Contact Form 7 version 3.9

= 0.4 =
* Bug fixes
* Added post_author_default

= 0.3 =
* Added post_author_name
* Added page_template
* Added ability to set post meta tags

= 0.2 =
* Fixed issue related to date.

= 0.1 =
* Initial Revision
