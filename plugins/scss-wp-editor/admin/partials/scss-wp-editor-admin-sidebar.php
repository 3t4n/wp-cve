<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.itpathsolutions.com/
 * @since      1.0.0
 *
 * @package    Scss_Wp_Editor
 * @subpackage Scss_Wp_Editor/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="accordion-container">
    <h3>SCSS Quick Guide: <span class="dashicons dashicons-book"></span></h3>
    <div class="set">
        <a href="javascript:void(0);" class="ac-title">Nesting <span class="dashicons dashicons-arrow-right"></span></a>
        <div class="content">
            <p>SCSS lets you nest CSS selectors in the same way as HTML.</p>
            <hr>
<pre class="scss-pre">
<code>.text {
    p {
        font-size: 18px;
        a {
            color: #FF0000;

            &:hover {
                color: #0000FF;
            }
        }
    }
}</code>
</pre>
        </div>
    </div>
    <div class="set">
        <a href="javascript:void(0);" class="ac-title">Variables <span class="dashicons dashicons-arrow-right"></span></a>
        <div class="content">
<pre class="scss-pre">
<code>$red: #FF0000; // Variable

body {
    color: $red;
}</code>
</pre>
        </div>
    </div>
    <div class="set">
        <a href="javascript:void(0);" class="ac-title">@extend <span class="dashicons dashicons-arrow-right"></span></a>
        <div class="content">
        <p>The @extend directive lets you share a set of CSS properties from one selector to another.</p>
        <hr>
<pre class="scss-pre">
<code>.button {
  ···
}

.button-red {
    @extend .button;
}</code>
</pre>
        </div>
    </div>
    <div class="set">
        <a href="javascript:void(0);" class="ac-title">@mixin & @include <span class="dashicons dashicons-arrow-right"></span></a>
        <div class="content">
        <p>The @mixin directive lets you create CSS code that is to be reused throughout the website.</p>
        <p>The @include directive is created to let you use (include) the mixin.</p>
        <hr>
<pre class="scss-pre">
<code>@mixin heading-font {
  font-family: sans-serif;
  font-weight: bold;
}
h1 {
  @include heading-font;
}
</code>
</pre>

<p>With Parameter</p>
<hr>


<pre class="scss-pre">
<code>@mixin font-size($n) {
  font-size: $n * 1.2em;
}
body {
  @include font-size(2);
}
</code>
</pre>

<p>With default values</p>
<hr>

<pre class="scss-pre">
<code>@mixin pad($n: 10px) {
  padding: $n;
}
body {
  @include pad(15px);
}
</code>
</pre>

<p>With default variable</p>
<hr>

<pre class="scss-pre">
<code>$default-padding: 10px;

@mixin pad($n: $default-padding) {
  padding: $n;
}
body {
  @include pad(15px);
}</code>
</pre>
        </div>
    </div>
    <div class="set">
        <a href="javascript:void(0);" class="ac-title">Advanced Features <span class="dashicons dashicons-arrow-right"></span></a>
        <div class="content">
            <p>There are some more advanced features of SCSS. Please check the official <a href="https://sass-lang.com/documentation" target="_blank"> document.</a></p>
        </div>
    </div>
</div>