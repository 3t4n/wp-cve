<!--usage documentation-->

<div id="tgp" class="wrap">
	<h2 class="tgp-head"><? _e('TPG Get Posts Usage','tpg-get-posts')?></h2>
	<!--<p class="tgp-warn">WARNING: If you copy/paste the commands, the function may not work. If this happens, type the entire command in to avoid introducing hidden characters.</p>-->
	<p>&nbsp;</p>

	<div id="gp-tabbed-area">
		<ul class="gp-tabs">
			<li><a href="#gp-overview"><? _e('Overview','tpg-get-posts')?></a></li>
			<li><a href="#gp-options"><? _e('Options','tpg-get-posts')?></a></li>
			<li><a href="#gp-styling"><? _e('Styling','tpg-get-posts')?></a></li>
            <li><a href="#gp-filters"><? _e('Filters','tpg-get-posts')?></a></li>
			<li><a href="#gp-examples"><? _e('Examples','tpg-get-posts')?></a></li>
			<li><a href="#gp-settings"><? _e('Settings','tpg-get-posts')?></a></li>
		</ul>

	<div id="gp-overview">
		<h3><? _e('Overview','tpg-get-posts')?></h3>
        {donate}
		<p><? _e('To use it, just put the following into the HTML of any page or post, use as many times as you like on the same page:','tpg-get-posts')?></p>

		<p class="tgp-warn"><? _e('WARNING: If you copy/paste the commands, the function may not work.  Often the hidden &lt;code&gt; and &lt;pre&gt; are copied into the page and only show in the html edit view of the WP editor.  If this happens, type the entire command in to avoid introducing hidden characters.','tpg-get-posts')?></p>

		<blockquote><pre>[tpg_get_posts]</pre></blockquote>
		<p><? _e('this is equivalent to:','tpg-get-posts')?></p>

		<blockquote><pre>[tpg_get_posts fields="title ,byline,content,metadata" numberposts=5
field_classes="post_title=tpg-title-class,post_content=tpg-content-class,
post_metadata=tpg-metadata-class, post_byline=tpg-byline-class" ]</pre></blockquote>

		<p><? _e('This default usage will return the last 5 posts in reverse chronological order. It will display the post similarly to a standard post, honoring the more tag to produce a teaser. Meta data showing post date, author, modified date, comments, categories and tags is also displayed.','tpg-get-posts')?></p>

		<p><? _e('If the post was created with a more tag inserted, the teaser is shown with a link to "read more".   See the option show entire if the entire post is to be shown.  The excerpt is can be shown with the show_excerpt option.','tpg-get-posts')?></p>

		<p><? _e('A common usage is to show post on a page that have a common tag(s):','tpg-get-posts')?></p>

		<blockquote><pre>[tpg_get_posts tag="tag1, tag2,tag3"]</pre></blockquote>
		<p><? _e('or to show specific posts on the home page:','tpg-get-posts')?></p>
		<blockquote><pre>[tpg_get_posts cat="homepage" numberposts=2]</pre></blockquote>
		{donate}
		<h3><? _e('To Do:','tpg-get-posts')?></h3>
		<ol>
		<li><? _e('add template tag...until then to incorporate TPG Get Posts into a template try using','tpg-get-posts')?>
&lt;?php echo do_shortcode('[tpg_get_posts]'); ?&gt;   </li>
		</ol>
		<h3><? _e('As of version 3.6, all functions are available in the free pluging.  The premium version is no longer needed.  But please consider making a donation.','tpg-get-posts')?></h3>
		<li><? _e('magazine layout option which displays the post header and text next to the post thumbnail','tpg-get-posts')?></li>
		<li><? _e('the short code can be added to a text widget','tpg-get-posts')?></li>
		<li><? _e('extended cat__and, cat__not_in and cat__in and other taxonmy','tpg-get-posts')?></li>
		<li><? _e('extensive formatting of the by line and meta-data line','tpg-get-posts')?></li>
		<li><? _e('allow pagination ','tpg-get-posts')?></li>
		<li><? _e('sticky post support','tpg-get-posts')?></li>

	</div>
	<div id="gp-options">
		<h3><? _e('Options','tpg-get-posts')?></h3>
		<p><? _e('Based on the <a href="http://codex.wordpress.org/Template_Tags/get_posts" target="_blank">get_posts</a> template tag, all of the possible parameters associated with this plugin are documented in the WP_Query class to which fetches posts. See the <a href="http://codex.wordpress.org/Class_Reference/WP_Query#Parameters" target="_blank">parameters section</a> of the WP_Query documentation for a list of parameters that this function accepts. </p><p>Note that if the parameter expects an array, then it must be handled explicitly in the parameter interface.  If that is not listed here, then it is not accepted.','tpg-get-posts')?></p>

		<dl>

		<h4><? _e('Selection parameters are:','tpg-get-posts')?></h4>

			<dt>tag</dt><dd><? _e('This allows for the selection of posts by tag (slug).','tpg-get-posts')?></dd>

			<dt>tag_id</dt><dd><? _e('This allows for the selection of posts by tag id.','tpg-get-posts')?></dd>

			<dt class="tpg-prem">author__in</dt><dd><? _e('This allows for the selection by authors by id. With the premimun extension, you may select multiple authors by entering author__in="12,34,6".','tpg-get-posts')?></dd>

			<dt class="tpg-prem">author__not_in</dt><dd><? _e('This allows for the exclusion of authors by id. With the premimun extension, you may select multiple authors by entering author__not_in="12,34,6".','tpg-get-posts')?></dd>

			<dt>cat</dt><dd><? _e("A comma separated list of cateory names,slugs or ids.  <br />These are OR logic comparisons.  Examples:  cat='4', cat='5,3,4', cat='news,events,5' cat='news'.  The category name is the name and not the slug. The coversion routine will look for id, slug and then category.  To accomodate complex taxonomonies and where a category name is repeated as a sub category use either slug or id.  Slugs and id''s are unique where a category name can be repeated.",'tpg-get-posts')?></dd>

			<dt class="tpg-prem">cat_link</dt><dd><? _e('Setting this option to cat_link="false" will suppress the wrapping of the categories with the hyperlink tag and the category will not be a link. Default is "true".','tpg-get-posts')?></dd>

			<dt>numberposts</dt><dd><? _e('Specify the maximum number of posts to select.  The default is 5.','tpg-get-posts')?></dd>

			<dt>offset</dt><dd><? _e('Specify the number of posts to skip.  This is a standard option to WP_Query.  If offset=3 is entered, the post will start showing at the 4 post.  The default is 0, show all.','tpg-get-posts')?></dd>

			<dt class="tpg-prem">posts_per_page</dt><dd><? _e('Specify the number of posts to show on page and enable pagination.  The numberposts option is ignored when this is specified. See the page_next_text and page_prev_text to set the text.  If paging on front page, see fp_pagination option.','tpg-get-posts')?></dd>

			<dt class="tpg-prem">fp_pagination</dt><dd><? _e("This option is used in conjunction with the post_per_page option.  If the pagination is on a static front page, set fp_pagination='true'.",'tpg-get-posts')?></dd>

			<dt class="tpg-prem">post_parent__in</dt><dd><? _e('This allows for the selection of posts by parent id. With the premimun extension, you may select multiple parent ids by entering post_parent__in="12,34,6".','tpg-get-posts')?></dd>

			<dt class="tpg-prem">post_parent__not_in</dt><dd><? _e('This allows for the exclusion of posts by parent id. With the premimun extension, you may exclude multiple parent ids by entering post_parent__not_in="12,34,6".','tpg-get-posts')?></dd>

			<dt class="tpg-prem">post__in</dt><dd><? _e('This allows for the selection of posts by id. With the premimun extension, you may select multiple ids by entering post__in="12,34,6".','tpg-get-posts')?></dd>

			<dt class="tpg-prem">post__not_in</dt><dd><? _e('This allows for the exclusion of posts by id. With the premimun extension, you may exclude multiple ids by entering post__not_in="12,34,6".','tpg-get-posts')?></dd>


			<dt>post_type</dt><dd><? _e('This allows for the selection custom post types. <span class="tpg-prem">With the premimun extension, you may select multiple posts types by entering post_type="CustTyp1,CustTyp2".  The post type can be found by editing a custom post and checking the permalink which is below the post title.  The custom post type immediately follows the domain name.</span>','tpg-get-posts')?></dd>

			<dt>post_status</dt><dd><? _e('This allows for the selection by post status. <span class="tpg-prem">With the premimun extension, you may select multiple posts statuses by entering post_status="published,draft".</span>','tpg-get-posts')?></dd>

			<dt class="tpg-prem">ignore_sticky_posts</dt><dd><? _e("Set to false, ignore_sticky_posts='false', to show sticky posts in selection.  By default the sticky posts only show on the home page.  By setting the option, the plugin will show sticky posts on any page.  The sticky posts are added to the beginning of the post list.  If you have requested 4 posts and you have 2 sticky, then 6 posts will be shown on the first page.",'tpg-get-posts')?></dd>

			<dt class="tpg-prem">category__and</dt><dd><? _e(' A comma separated list of either categories or category_names may be used in this paramter.  The values passed here will replace any value in the cat_name or cat tag. Posts must have be in all the categories (AND logic).','tpg-get-posts')?></dd>
			<dt class="tpg-prem">category__in </dt><dd><? _e('A comma separated list of either categories or category_names may be used in this paramter.  The values passed here will replace any value in the cat_name or cat tag. Post may be in any of the categories (OR logic) and child posts are not pulled.','tpg-get-posts')?></dd>
			<dt class="tpg-prem">category__not_in</dt><dd><? _e('A comma separated list of either categories or category_names may be used in this paramter.  The values passed here will replace any value in the cat_name or cat tag.  Excludes posts in the listed categories.','tpg-get-posts')?></dd>

			<dt class="tpg-prem">tag__and</dt><dd><? _e('A comma separated list of tag_ids may be used in this paramter. Posts must have be in all the tag_ids (AND logic).','tpg-get-posts')?></dd>
			<dt class="tpg-prem">tag__in </dt><dd><? _e('A comma separated list of tag_ids may be used in this paramter. Posts may be in any of the tag_ids (OR logic).','tpg-get-posts')?></dd>
			<dt class="tpg-prem">'tag__not_in</dt><dd><? _e('A comma separated list of tag_ids may be used in this paramter. Excludes posts with the listed tag_ids.','tpg-get-posts')?></dd>

			<dt class="tpg-prem">tag_slug__and</dt><dd><? _e('A comma separated list of slugs may be used in this paramter. Posts must have be in all the slugs (AND logic).','tpg-get-posts')?></dd>
			<dt class="tpg-prem">tag_slug__in</dt><dd><? _e('A comma separated list of slugs may be used in this paramter. Posts may be in any of the slugs (OR logic).','tpg-get-posts')?></dd>

            <dt class="tpg-prem">query_by_date</dt><dd><? _e('Boolean value: true activates this option. This allows for the selection of post for the last x (a number) periods (day, month, year).  It has two sub-commands: qbd_num and qbd_period.  Example: query_by_date="true" qbd_num"3" qbd_period="month" This will select the posts enter in the previous 3 months.','tpg-get-posts')?></dd>

			<p><? _e('At this time, the following two commands have had limited testing.','tpg-get-posts')?></p>
			<dt class="tpg-prem">tax_query</dt><dd><? printf(__('Used to pass the custom taxonomy selection to the plugin.  Because the tax_query accepts nested arrays, the syntax is more complex than other parms and values are passed in json format.  An example using json format: <br><br>%s
 <br><br>**Note: that json requires the strings to be enclosed with double quotes, so single quotes are required to wrap the tag value.  Also json uses [] to define arrays without keys, but that conflicts with the shortcode syntax.  To work around this, () are substitued in the json string and converted before decoding to an array.','tpg-get-posts'),'tax_query=\'{"relation":"AND","0":{"taxonomy":"movie_genre","field":"slug","terms":("action","comedy")},"1":{"taxonomy":"actor","field":"id","terms":(103,115,206),"operator":"NOT IN"}}\'')?></dd>
			<dt class="tpg-prem">meta_query </dt><dd><? printf(__('Because the meta_query accepts nested arrays, the syntax is more complex than other parms and values are passed in json format.  An example using json format: <br><br>%s
 <br><br>**Note: that json requires the strings to be enclosed with double quotes, so single quotes are required to wrap the tag value. Also json uses [] to define arrays without keys, but that conflicts with the shortcode syntax.  To work around this, () are substitued in the json string and converted before decoding to an array.','tpg-get-posts'),'meta_query=\'({"key":"color","value":"blue","compare":"NOT LIKE"},{"key":"price","value":(20,100),"type":"numeric","compare":"BETWEEN"})\'')?></dd>


		<h4><? _e('Layout/format control parameters:','tpg-get-posts')?></h4>

			<dt>fields</dt><dd><? _e('This is a comma separated list of fields to be displayed. The default is "title, byline, content, metadata".  If only a list of titles is desired, remove the othe parms from the list and no content will be returned, ie fields="title".','tpg-get-posts')?></dd>

			<dt>field_classes</dt><dd><? _e(' This is a special list which assigns a class to a post field.  It is formatted in a key=value sequence separated by a comma.  The key defines a section of the post while the value is the name of a class to which will be provided via a tag wrapped around the field. The default classes are listed below.  The class can be assigned any value and the css set up in a user defined style sheet.  The key fields cannot be changed.	  <br /><br />The following is a list of the default field classes which can be overridden:','tpg-get-posts')?>
			<ul style="padding-left:2em"> <br />
			<li>post_title=tpg-title-class</li>
			<li>post_content=tpg-content-class</li>
			<li>post_metadata=tpg-metadata-class</li>
			<li>post_byline=tpg-byline-class</li>
			<li>post_thumbnail=tpg-thumbnail-class</li>
			<li>thumbnail_align=alignleft</li>
			<li>post_excerpt=tpg-excerpt-class</li>
			<li>mag_content=tpg-mag-class</li>
			<li>fi_content=tpg-fi-class</li>
			<li>ul_class=tpg-ul-class</li>
			<li>pagination=tpg-pagination-class</li>
			<li>page-next=tpg-next</li>
			<li>page-prev=tpg-prev</li>
			<li>more_link=more-link</li>
			</ul>

			</dd>

			<dt class="tpg-prem">fi_layout</dt><dd><? _e('This option fi_layout="true" in conjunction with the thumbnail_size="medium" option display the post in a "featured image" format where the featured image is placed above the title.  The fields option can control what text to display.<br> The thumbnail_size is required when displaying the thumbnail/featured image.','tpg-get-posts')?></dd>

			<dt class="tpg-prem">mag_layout</dt><dd><? _e('This option mag_layout="true" in conjunction with the thumbnail_size="thumbnail" option places the thumbnail at the beginning of the post items so it can float left and have the title and content beside the image.  The standard "post layout" puts the title above the image and only the content wraps around the the image. <br> The thumbnail_size is required when displaying the thumbnail/featured image.','tpg-get-posts')?></dd>

			<dt>more_link_text</dt><dd><? _e('This option changes the text to display when the more tag is used to produce a teaser.  Enter as more_link_text="My Custom Text". Default is "(read more...)".','tpg-get-posts')?></dd>

			<dt class="tpg-prem">page_next</dt><dd><? _e('The option page_next="true" will show just the Previous and Next link instead of the pagination index.  The default is false.','tpg-get-posts')?></dd>
		    <dt class="tpg-prem">page_prev_text</dt><dd><? _e('Set the text for the previous link. Default is "&laquo; Previous".','tpg-get-posts')?></dd>
		    <dt class="tpg-prem">page_next_text</dt><dd><? _e('Set the text for the next link. Defautl is "Next &raquo;".','tpg-get-posts')?></dd>

			<dt>shorten_title</dt><dd><? _e('This option shorten_title="c15" or shorten_title="w15" specifies that the title will be shortened to 15 characters. The "c" indicates to cut at the character while the "w" indicates that only whole words in the first 15 characters are included.','tpg-get-posts')?></dd>

			<dt>shorten_content</dt><dd><? _e('Using the more tag is generally a better option, but is is provided for consistency. This option shorten_content="c150" or shorten_content="w150" specifies that the content will be shortened to 150 characters, excluding the "read more..." text. The "c" indicates to cut at the character while the "w" indicates that only whole words in the first 150 characters are included. The "read more" tag is processed first, then this process is applied, so a read more tag can cause the text to be shorter than the specified length if placed in the post before the first x characters.  <br />Also note that this command does not check for embedded tags.  It trims the content when the limit is reached and is not aware of html tags. Thus tags can be broken. It should only be be used with content that is very controlled. Using the read-more tag is safer.','tpg-get-posts')?></dd>


			<dt>show_entire</dt><dd><? _e('This option show_entire="true" will show the entire post, not just the teaser.It ignores the more tag in the post content.  Default is "false".','tpg-get-posts')?></dd>

			<dt>show_excerpt</dt><dd><? _e('This option show_excerpt="true" will use the custom excerpt, if it exists, instead of the post content.  It will use the entire excerpt entry. Default is "false".','tpg-get-posts')?></dd>

			<dt class="tpg-prem">tag_link</dt><dd><? _e('Setting this option to tag_link="false" will suppress the wrapping of the tags with the hyperlink tag and the tag will not be a link. Default is "true".','tpg-get-posts')?></dd>

			<dt>text_ellipsis</dt><dd><? _e("This parameter allows you to set the ellipsis displayed after shortened text. it defaults to text_ellipsis=' ...' but can be set to anything or nothing text_ellipsis=''.",'tpg-get-posts')?></dd>

			<dt>thumbnail_size</dt><dd><? _e('Enter "none", thumbnail", "medium", "large" or"full" as the option value and if the thumbnail has been entered, it is used.  Example thumbnail_size="medium". Default is "thumbnail".  Your theme may provide additional options for thumbnail sizes.','tpg-get-posts')?></dd>

			<dt>thumbnail_only</dt><dd><? _e('This option thumbnail_only="true" will only show the thumbnail as a link to the post page.  Use in conjunction with the thumbnail_size to set the size of the image.   Default is "false"','tpg-get-posts')?></dd>

			<dt>thumbnail_link</dt><dd><? _e('This option thumbnail_link="true" is the default and will wrap a thumbnail with the link to the post.  Setting to false will remove the link assigned to the image and selecting on the image will do nothing.','tpg-get-posts')?></dd>

			<dt class="tpg-prem">thumbnail_title</dt><dd><? _e('For this option thumbnail_title="false" is the default.  To have a text attritue set for the thumbnail enter "post" or "image".  The "post" value will pull the post title as the image text attribute and "image" will pull the image title for the value of the text attribute.','tpg-get-posts')?></dd>

			<dt>title_tag</dt><dd><? _e('This parameter controls the formatting of the title line. The default is to make post titles h2, which is consistent with the regular post markup. title_tag="p" will apply the paragraph markup to the title instead of the h2 markup. Note: do not include the <>.','tpg-get-posts')?></dd>

			<dt>title_link</dt><dd><? _e('Setting this option to title_link="false" will suppress the wrapping of the title with the hyperlink tag and the title will not be a link. Default is "true".','tpg-get-posts')?></dd>

			<dt>ul-class</dt><dd><? _e('This is the class assigned to the bullet list. When this class is provided, the output is returned as an unordered list.','tpg-get-posts')?></dd>

            <p><? _e('To exclude posts, see examples.','tpg-get-posts')?></p>

            <div class="deprecated" style="background:yellow;">
                <h4 ><? _e('Deprecated - use filters','tpg-get-posts')?></h4>
                <dt class="tpg-prem">cf</dt><dd><? _e("Invoke the user Custom Functions by passing the codes for each exit to be invoked, ie cf='ppre,ppst,pre,pst,t,b,c,m'. The model function php file is provided in the /ext files folder and instructions are in the comments.",'tpg-get-posts')?>
                ppre => <? _e('before the invoking of the plugin','tpg-get-posts')?>
                ppst => <? _e('upon completion of plugin, just before returning results','tpg-get-posts')?>
                pre  => <? _e('before each post','tpg-get-posts')?>
                pst  => <? _e('after each post','tpg-get-posts')?>
                t,b,c,m => <? _e('after title,byline,content,metadata -- this allows final editing
                ','tpg-get-posts')?></dd>
                <dt class="tpg-prem">cfp</dt><dd><? _e('Custom functions parameters.  This is an optional string that is passed to the custom functions routine.  It must be parsed by the custom function and is defined by the user.','tpg-get-posts')?></dd>
            </div>

		</dl>


	</div>
	<div id="gp-styling">
		<h3><? _e('Styling','tpg-get-posts')?></h3>
		<p><? _e('To over-ride the default styling, create a css file in your theme root folder named <b>user-get-posts-style.css</b>.  When the plugin is loaded, the standard style sheet is loaded and then the user defined style sheet is loaded, if it exists.  Any tags in the user style-sheet will override the standard style.','tpg-get-posts')?></p>
		<p><em><b><? _e('By saving your custom user defined style sheet in the theme folder it will not be deleted with an upgrade to the plugin.  This is a change from version 1.x','tpg-get-posts')?></b></em></p>
		<p><? _e('There are two ways to alter the styling: ','tpg-get-posts')?>
		<ol>
		<li><? _e('In the user stylesheet, redefine the styles which are listed below.  The simplest approach is to copy the styles from tpg_get_posts-style.css and modify them as needed. ','tpg-get-posts')?>
		<p>
		<dt>tpg-title-class</dt><dd><? _e('class of the post title division','tpg-get-posts')?></dd>
		<dt>tpg-byline-class</dt><dd><? _e('class of the post byline','tpg-get-posts')?></dd>
		<dt>tpg-content-class</dt><dd><? _e('class for the body of the post','tpg-get-posts')?></dd>
		<dt>tpg-metadata-class</dt><dd><? _e('class for the metadata','tpg-get-posts')?></dd>
		<dt>tpg-excerpt-class</dt><dd><? _e('class for the excerpt','tpg-get-posts')?></dd>
		<dt>tpg-thumbnail-class</dt><dd><? _e('class for the thumbnail division','tpg-get-posts')?></dd>

		</p>
		</li>
		<li><? _e('If you need to pass different formatting to different pages, then the short-code must include the list of new classes.  The list must include all the default parameters, even if not altered:','tpg-get-posts')?>
			<p><? printf( __('The default classes are %s as shown in the following short-code:','tpg-get-posts'),'post_title=tpg-title-class, post_content=tpg-content-class, post_metadata=tpg-metadata-class, post_byline=tpg-byline-class')?>
		<blockquote><pre>[tpg_get_posts show_entire="false" fields="title, content, metadata" numberposts=5
field_classes="post_title=tpg-title-class, post_content=tpg-content-class,
 post_metadata=tpg-metadata-class,post_byline=tpg-byline-class"  ]</pre></blockquote></p></li>
		 </ol></p>
		 <ul>
		  <dt class="tpg-prem"><? _e('Premium Formatting Control Template','tpg-get-posts')?></dt><dd><? _e('The Premium plugin allows for the formatting of the by line and the meta line.  For example, you can change the byline from showing the default of author, post date to just author or author, last maint date, last maint time.   <br /><br />Within each line, there are several post tags which can be displayed and each tag can be formatted.','tpg-get-posts')?></dd>
		<dt class="tpg-prem"><? _e('Format Line Parms','tpg-get-posts') ?></dt><dd><? _e('The format line is a comma separated string with the first value being the separator for the formatted string and then a list of the tags or tokens. The general format is: sep,meta_tag,meta_tag....','tpg-get-posts')?></dd>
		<dt class="tpg-prem"><? _e('Valid tags:','tpg-get-posts') ?></dt><dd>
		<li>auth - <? _e('author name','tpg-get-posts')?></li>
		<li>cat  - <? _e('list of categories assigned to post','tpg-get-posts')?></li>
		<li>cmt  - <? _e('comment, with format control for no, 1 or many comments','tpg-get-posts')?></li>
		<li>dp   - <? _e('date posted','tpg-get-posts')?></li>
		<li>dm   - <? _e('date of last maintenance','tpg-get-posts')?></li>
		<li>tag  - <? _e('list of tags assigned to post','tpg-get-posts')?></li>
		<li>tm   - <? _e('time of last maintenance','tpg-get-posts')?></li>
		<li>tp   - <? _e('time of posting','tpg-get-posts')?></li>
		</dd>
		<dt class="tpg-prem">byline_fmt</dt><dd><? _e('default byline_fmt=" ,auth,dp" ','tpg-get-posts')?></dd>
		<dt class="tpg-prem">metaline_fmt</dt><dd><? _e('default metaline_fmt="&nbsp;&nbsp;|&nbsp;&nbsp;,cmt,cat,tag"','tpg-get-posts')?></dd>

		<p><? _e('The following format options allow customization of the individual fields in the byline or metaline.','tpg-get-posts')?></p>
		<p><? _e('<b>Note</b>:  if you wish to use a comma in the the formatting, use the code <code>&amp;#44;</code>  The parsing routine uses commas to parse the format options, so the html code must be used to circumvent the parser. ','tpg-get-posts')?></p>

		<dt class="tpg-prem">auth_fmt</dt><dd><? _e('default: auth_fmt=",By ,"   (separator,b4 text,after text)','tpg-get-posts')?></dd>
		<dt class="tpg-prem">cat_fmt</dt><dd><? _e('default: cat_fmt="&amp;#44; ,Filed under: ,"   (separator,b4 text,after text)','tpg-get-posts')?></dd>
		<dt class="tpg-prem">cmt_fmt</dt><dd><? _e('default: cmt_fmt=" No Comments &#187;, 1 Comment &#187;, Comments &#187;"   (nocmt,1-cmt, multi-cmt)','tpg-get-posts')?></dd>
		<dt class="tpg-prem">dp_fmt</dt><dd><? _e('default: dp_fmt="F j&amp;#44; Y, on ,"   (date format,b4 text,after text)','tpg-get-posts')?></dd>
		<dt class="tpg-prem">dm_fmt</dt><dd><? _e('default: dm_fmt="F j&amp;#44; Y, on: ,"   (date format,b4 text,after text)','tpg-get-posts')?></dd>
		<dt class="tpg-prem">tag_fmt</dt><dd><? _e('default: tag_fmt="&amp;#44; ,<b>Tags:</b> ,"    (separator,b4 text,after text)','tpg-get-posts')?></dd>
		<dt class="tpg-prem">tm_fmt</dt><dd><? _e('default: tm_fmt="H:m:s, ,"    (time format,b4 text,after text)','tpg-get-posts')?></dd>
		<dt class="tpg-prem">tp_fmt</dt><dd><? _e('default: tp_fmt="H:m:s, ,"    (time format,b4 text,after text)','tpg-get-posts')?></dd>
		 </ul>


	</div>
    <div id="gp-filters">
        <h3><?php _e('Filters:','tpg-get-posts')?></h3>
        <p><?php _e('The filter process allows for the filters to be implemented in 2 ways:','tpg-get-posts') ?></p>
        <p><ol><?php _e('
                <li>Globally as a standard filter</li>
                <li>Individual call in tpg_get_posts</li>
            </ol>','tpg-get-posts') ?></p>
        <p><?php _e('The global filter is given a priority value of 10 and the call through the short-code is given a priority value of 15.  This allows the short-code call to override the global call, it that it is called after the global call.','tpg-get-posts') ?></p>
        <h4><?php _e('Example code','tpg-get-posts') ?></h4>
        <p><?php _e('Create a custom function.  This can be placed in the theme functions file or it can be put in a file named <br /><b>"user-get-posts-custom-functions"</b> which is placed in the root of the theme.','tpg-get-posts') ?></p>
        <p><pre><code>function my_custom_title_filter( $title, $post) {
        $title = "Custom Title Filter Works";
        return $title;
    }</code></pre></p>
        <p>To set the function to process on all titles (global title filter) use the following code and add below the function definition: <br /><code>'add_filter( "tgp_filter_title", "my_custom_title_filter", 10, 2 );</code></p>
        <p>To run the filter for a specific call of tpg_get_posts add the filter parameter to the short-code: <br /><code>&#91;tpg_get_posts filter_title="my_custom_title_filter"&#93;></code></p>

        <p>The query filter only passes one argument, the query arguments array:</p>
        <p><pre><code>function my_custom_filter_query( $args) {
         *** set the $args array ***
        return $args;
    }</code></pre>
         <code>&#91;tpg_get_posts filter_title="my_custom_filter_query"&#93;></code></p>

        <p><?php _e('The available filters are:<br /> (replace &lt;function_name&gt; with your custom function name)','tpg-get-posts') ?></p>
        <ul>
            <dt class="tpg-prem">filter_query</dt><dd><?php _e('filter_query="&lt;function_name&gt;" - this function only accepts one argument, the query parameters array.  This filter allows the wp_query parameters to be modified before the query is run.','tpg-get-posts')?></dd>
            <dt class="tpg-prem">filter_title</dt><dd><?php _e('filter_title="&lt;function_name&gt;"','tpg-get-posts')?></dd>
            <dt class="tpg-prem">filter_byline</dt><dd><?php _e('filter_byline="&lt;function_name&gt;"','tpg-get-posts')?></dd>
            <dt class="tpg-prem">filter_content</dt><dd><?php _e('filter_content="&lt;function_name&gt;"','tpg-get-posts')?></dd>
            <dt class="tpg-prem">filter_metadata</dt><dd><?php _e('filter_metadata="&lt;function_name&gt;"','tpg-get-posts')?></dd>
            <dt class="tpg-prem">filter_thumbnail</dt><dd><?php _e('filter_thumbnail="&lt;function_name&gt;"','tpg-get-posts')?></dd>
        </ul>
    </div>

    <div id="gp-examples">
		<h3><? _e('Examples:','tpg-get-posts')?></h3>

		<p><? _e('Shows 5 posts with the tag "tag1" or "tag2" ordered by title. Display the post title and content teaser.','tpg-get-posts')?></p>

		<blockquote><pre>[tpg_get_posts tag="tag1,tag2" numberposts=5 orderby="title"]</pre></blockquote>

		<p><? _e('Shows 2 posts with the category name of "Events" or "News" ordered by title. Display the post title and the entire content.','tpg-get-posts')?></p>

		<blockquote><pre>[tpg_get_posts cat="Events,News" numberposts=2 orderby="title" show_entire="true"]</pre></blockquote>

		<p><? _e('Shows a bullet list of post titles. The title will be wrapped in a tag with a class of "tpg-ul-class".  The title will provide a link to the post. The title can be formatted with a css style .tpg-ul-class h2 {}.','tpg-get-posts')?></p>

		<blockquote><pre>[tpg_get_posts tag="tag5" fields="title"  ul_class="tpg-ul-class"]</pre></blockquote>

		<p><? _e('To exclude a category within a selected category, you must use the category id for the selection.  The minus sign in front of the cateory id tells the query to exlude a category.  So this shortcode will select all the posts in category 15 and then eliminate all the post that are also in category 4.','tpg-get-posts')?></p>

		<blockquote><pre>[tpg_get_posts cat="15,-4" ]</pre></blockquote>

		<p class="tpg-prem"><? _e('The magazine layout is available in the premium version.  The layout will float the thumbnail to the left and position the title and byline to the right.  The fields parm controls which elements to show.','tpg-get-posts')?></p>

		<blockquote><pre>[tpg_get_posts cat="25" fields="title, byline"  numberposts=1 mag_layout="true" thumbnail_size="thumbnail"]</pre></blockquote>

		<p class="tpg-prem"><? _e('Pagination.  Show 5 posts per page with the pagination index at the end of the posts.','tpg-get-posts')?></p>

		<blockquote><pre>[tpg_get_posts cat="cat1" posts_per_page=5
		 field_classes="pagination=tpg-pagination-class my-theme-pagination-class"]</pre></blockquote>

		<p><? _e('If paging on a static front page use:','tpg-get-posts')?></p>
		<blockquote><pre>[tpg_get_posts cat="cat1" posts_per_page=5
		 field_classes="pagination=tpg-pagination-class
		 my-theme-pagination-class" fp_pagination="true"]</pre></blockquote>

		<p class="tpg-prem"><? _e("Formats the byline with author, date and time and the date format is defined in the dm_fmt option to produce 'last changed on: Month day, YYYY'.",'tpg-get-posts')?></p>

		<blockquote><pre>[tpg_get_posts category__in="Events" byline_fmt"auth,dm,tm", dm_fmt="F j&amp;#44; Y, last changed on: ,"]</pre></blockquote>


		<h3><? _e('How to Use:','tpg-get-posts')?></h3>{donate}
		<p><? _e('If you wish to have a subset of posts appear on a specific page.  The simplest approach is to specify a unique category and select it for the post you want to appear.  You can have multiple categories for a post, so this will not alter your existing website structure.','tpg-get-posts')?></p>

		<p><? _e(' For instance, suppose you want to show 2 posts on your home page, but not everything you post, only those dealing with high profile events. The following steps should do this:','tpg-get-posts')?></p>
		<ol>
			<li><? _e('Create a new category "homepage"','tpg-get-posts')?></li>
			<li><? _e('For each post that is to appear on the home page, select the category "homepage"','tpg-get-posts')?></li>
			<li><? _e('On your home page, enter the following shortcode where you want the posts inserted:','tpg-get-posts')?>
			<blockquote><pre>[tpg_get_posts cat="homepage" numberposts=2]</pre></blockquote></li>
		</ol>
		<p><? _e('That should do it!','tpg-get-posts')?></p>
	</div>
	<div id="gp-settings">
        {settings}
    </div>
  </div>
</div>
