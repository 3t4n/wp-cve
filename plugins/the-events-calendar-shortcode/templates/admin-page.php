<div class="wrap">
  <div class="w-full max-w-7xl">
    <h2><?php _e( 'The Events Calendar Shortcode &amp; Block', 'the-events-calendar-shortcode' ); ?></h2>

    <p class="text-base"><?php echo sprintf( esc_html__( 'The Events Calendar Shortcode &amp; Block displays lists of your events wherever you want them to appear on your site. For example the shortcode to show next 8 events in the category "%s" in ASC order with date showing:', 'the-events-calendar-shortcode' ), 'festival' ); ?></p>

    <code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events cat='festival' limit='8']</code>

    <div class="flex flex-row py-8 mt-4 justify-center items-center">
      <div class="mr-4">
        <a class="min-w-[240px] bg-[#EB6924] rounded p-4 flex-none text-white shadow-lg text-center no-underline hover:text-white font-medium text-base" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode-short-walk-through-video/?utm_source=plugin&utm_medium=link&utm_campaign=full-documentation&utm_content=top" target="_blank"><?php echo esc_html( __( 'Watch a Short Walk Through Video', 'the-events-calendar-shortcode' ) ); ?></a></p>
      </div>
      <div>
        <a class="min-w-[240px] rounded p-4 flex-none shadow-lg text-center no-underline font-medium text-base text-gray-700 bg-gray-100 hover:text-gray-700" href="https://eventcalendarnewsletter.com/events-calendar-shortcode-pro-options/?<?php echo apply_filters( 'ecs_show_upgrades', true ) ? 'free=1&' : ''; ?>utm_source=plugin&utm_medium=link&utm_campaign=full-documentation&utm_content=top" target="_blank"><?php echo esc_html( __( 'View Full Documentation', 'the-events-calendar-shortcode' ) ); ?></a></p>
      </div>
    </div>
  </div>
	<div class="flex w-full max-w-7xl mt-10">
     <div class="w-<?php echo apply_filters( 'ecs_show_upgrades', true ) ? '1/2' : 'full'; ?> pr-4">
  			<div class="text-base">
          <?php do_action( 'ecs_admin_page_options_top' ); ?>

          <h2><?php echo esc_html( __( 'Using the Block', 'the-events-calendar-shortcode' ) ); ?></h2>
          <div class="flex py-6">
            <div class="flex-1">
              <img class="object-cover max-w-full h-auto" src="<?php echo esc_attr( plugins_url( 'static/images/the-events-calendar-block.png', TECS_CORE_PLUGIN_FILE ) ); ?>">
            </div>
            <div class="flex-1 px-6">
              <div class="text-gray-500 text-base"><?php echo esc_html( __( 'When using the WordPress editor, select The Events Calendar Block. After saving, the list of your events will display.', 'the-events-calendar-shortcode' ) ); ?></div>
            </div>
          </div>

          <div class="mt-4 mb-12"><?php echo esc_html( __( 'The block contains most of the options below. Otherwise, you can use the Advanced/Other option to enter a shortcode option below manually.', 'the-events-calendar-shortcode' ) ); ?></div>

          <h2><?php echo esc_html( __( 'Basic shortcode', 'the-events-calendar-shortcode' ) ); ?></h2>
						<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events]</code>

					<h2><?php echo esc_html( __( 'Shortcode Options', 'the-events-calendar-shortcode' ) ); ?></h2>
					<?php do_action( 'ecs_admin_page_options_before' ); ?>

					<h3>cat</h3>
          <div class="mb-2"><?php echo esc_html( __( 'Filter by events in a category. Use commas when you want multiple categories.', 'the-events-calendar-shortcode' ) ); ?></div>
						<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events cat='festival']</code>
						<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events cat='festival, workshops']</code>

					<?php do_action( 'ecs_admin_page_options_after_cat' ); ?>

					<h3>limit</h3>
					<div class="mb-2"><?php echo esc_html( __( 'Total number of events to show. Default is 5.', 'the-events-calendar-shortcode' ) ); ?></div>
					<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events limit='3']</code>
					<h3>order</h3>
					<div class="mb-2"><?php echo esc_html( __( "Order of the events to be shown. Value can be 'ASC' or 'DESC'. Default is 'ASC'. Order is based on event date.", 'the-events-calendar-shortcode' ) ); ?></div>
						<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events order='DESC']</code>
					<h3>date</h3>
					<div class="mb-2"><?php echo esc_html( __( "To show or hide date. Value can be 'true' or 'false'. Default is true.", 'the-events-calendar-shortcode' ) ); ?></div>
						<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events eventdetails='false']</code>
					<h3>venue</h3>
					<div class="mb-2"><?php echo esc_html( __( "To show or hide the venue. Value can be 'true' or 'false'. Default is false.", 'the-events-calendar-shortcode' ) ); ?></div>
						<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events venue='true']</code>
					<h3>excerpt</h3>
					<div class="mb-2"><?php echo esc_html( __( 'To show or hide the excerpt and set excerpt length. Default is false.', 'the-events-calendar-shortcode' ) ); ?></div>
					<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events excerpt='true']</code>
					<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events excerpt='300']</code>
					<h3>thumb</h3>
					<div class="mb-2"><?php echo esc_html( __( 'To show or hide thumbnail/featured image. Default is false.', 'the-events-calendar-shortcode' ) ); ?></div>
					<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events thumb='true']</code>
					<div class="my-2"><?php echo sprintf( esc_html( __( 'You can use 2 other attributes: %s and %s to customize the thumbnail size', 'the-events-calendar-shortcode' ) ), 'thumbwidth', 'thumbheight' ); ?></div>
					<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events thumb='true' thumbwidth='150' thumbheight='150']</code>
					<div class="my-2"><?php echo sprintf( esc_html( __( 'or use %s to specify the pre-set size to use, for example:', 'the-events-calendar-shortcode' ) ), 'thumbsize' ); ?></div>
					<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events thumb='true' thumbsize='large']</code>
					<h3>message</h3>
					<div class="mb-2"><?php echo esc_html( sprintf( __( "Message to show when there are no events. Defaults to '%s'", 'the-events-calendar-shortcode' ), translate( 'There are no upcoming events at this time.', 'tribe-events-calendar' ) ) ); ?></div>
          <code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events message='No events at this time.']</code>
					<h3>viewall</h3>
					<?php if ( function_exists( 'tribe_get_event_label_plural' ) ): ?>
						<div class="mb-2"><?php echo esc_html( sprintf( __( "Determines whether to show '%s' or not. Values can be 'true' or 'false'. Default to 'true'", 'the-events-calendar-shortcode' ), sprintf( __( 'View All %s', 'the-events-calendar' ), tribe_get_event_label_plural() ) ) ); ?></div>
					<?php endif; ?>
          <code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events cat='festival' limit='3' order='DESC' viewall='false']</code>
					<h3>contentorder</h3>
					<div class="mb-2"><?php echo esc_html( sprintf( __( 'Manage the order of content with commas. Defaults to %s', 'the-events-calendar-shortcode' ), 'title, thumbnail, excerpt, date, venue' ) ); ?> </div>
          <code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events cat='festival' limit='3' order='DESC' viewall='false' contentorder='title, thumbnail, excerpt, date, venue']</code>
					<h3>month</h3>
					<div class="mb-2"><?php echo esc_html( sprintf( __( "Show only specific Month. Type '%s' for displaying current month only or '%s' for next month, ie:", 'the-events-calendar-shortcode' ), 'current', 'next' ) ); ?></div>
          <code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events cat='festival' month='2024-06']</code>
					<h3>past</h3>
					<div class="mb-2"><?php echo esc_html( __( 'Show outdated events (ie. events that have already happened)', 'the-events-calendar-shortcode' ) ); ?></div>
          <code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events cat='festival' past='yes']</code>
					<h3>key</h3>
					<div class="mb-2"><?php echo esc_html( __( 'Use to hide events when the start date has passed, rather than the end date.  Will also change the order of events by start date instead of end date.', 'the-events-calendar-shortcode' ) ); ?></div>
						<code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events cat='festival' key='start date']</code>
					<h3>orderby</h3>
					<div class="mb-2"><?php echo esc_html( __( 'Used to order by the end date instead of the start date.', 'the-events-calendar-shortcode' ) ); ?></div>
          <code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events orderby='enddate']</code>
          <div class="my-2"><?php echo esc_html( __( 'You can also use this to order by title if you wish:', 'the-events-calendar-shortcode' ) ); ?></div>
          <code class="text-sm sm:text-base inline-flex text-left items-center space-x-4 bg-gray-800 text-white rounded-lg p-4 pl-6">[ecs-list-events orderby='title']</code>
					<?php do_action( 'ecs_admin_page_options_after' ); ?>
          <?php if ( apply_filters( 'ecs_show_upgrades', true ) ): ?>
              <div id="ecs-link-display" class="p-4 bg-white mt-8">
                  <?php wp_nonce_field( 'ecs-link-nonce', 'ecs-link-nonce' ); ?>
                  <h2>We hope this plugin is helping you out!</h2>
                  <div class="mb-4">Would you like to show your thanks for the plugin? Add a small link underneath your events pointing to The Events Calendar Shortcode project.</div>
                  <div class="mb-4"><label><input type="checkbox" value="1" id="show-ecs-link"<?php echo  get_option( 'ecs-show-link' ) ? ' checked' : ''; ?>> Show small link to The Events Calendar Shortcode</label></div>
                  <div class="small toggle-message" style="display:none;">Value saved</div>
              </div>
          <?php endif; ?>
        </div>
     </div>
     <?php if ( apply_filters( 'ecs_show_upgrades', true ) ): ?>
     <div class="w-1/2">
          <div class="p-4 bg-white">

            <div class="text-base">
              <h3>Styling/Design</h3>

              <?php do_action( 'ecs_admin_page_styling_before' ); ?>


              <div class="mb-2"><?php echo esc_html( __( 'By default the plugin does not include styling. Events are listed in ul li tags with appropriate classes for styling (with CSS):', 'the-events-calendar-shortcode' ) ); ?></div>

              <ul class="p-4 list-disc">
                <li>ul class="ecs-event-list"</li>
                <li>li class="ecs-event" &amp; li class="ecs-featured-event" <?php echo esc_html( __( '(if featured)', 'the-events-calendar-shortcode' ) ); ?></li>
                <li><?php echo esc_html( sprintf( __( 'event title link is %s', 'the-events-calendar-shortcode' ), 'H4 class="entry-title summary"' ) ); ?> </li>
                <li><?php echo esc_html( sprintf( __( 'date class is %s', 'the-events-calendar-shortcode' ), 'time' ) ); ?></li>
                <li><?php echo esc_html( sprintf( __( 'venue class is %s', 'the-events-calendar-shortcode' ), 'venue' ) ); ?></li>
                <li>span .ecs-all-events</li>
                <li>p .ecs-excerpt</li>
              </ul>

              <div class="pb-8">
                <a class="min-w-[240px] p-4 flex-none shadow-lg text-center no-underline font-medium text-base text-gray-700 bg-gray-100 hover:text-gray-700" href="https://eventcalendarnewsletter.com/getting-started/?utm_source=plugin&utm_medium=link&utm_campaign=full-documentation&utm_content=top#css" target="_blank"><?php echo esc_html( __( 'More Information on Customizing the Style', 'the-events-calendar-shortcode' ) ); ?></a></p>
              </div>

              <div id="ecs-pro-description" class="text-base">
                <h3><?php echo esc_html__( 'Want more designs?', 'the-events-calendar-shortcode' ); ?></h3>
                <div class="mb-2"><?php echo sprintf( esc_html__( 'Check out %sThe Events Calendar Shortcode & Block Pro%s! Some examples of the designs:', 'the-events-calendar-shortcode' ), '<a class="text-[#EB6924] hover:text-[#EB6924]" target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design&utm_content=description">', '</a>' ); ?></div>
                <div id="ecs-pro-designs" class="flex flex-row flex-wrap justify-around mt-6">
                  <div class="mb-8 text-center"><a class="text-[#EB6924] hover:text-[#EB6924]" target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-1&utm_content=description"><img alt="" style="width: 300px;" src="<?php echo plugins_url( '/static/images/the-events-calendar-shortcode-pro-default.png', TECS_CORE_PLUGIN_FILE ); ?>"><br><?php echo esc_html( __( 'Pro version default design example', 'the-events-calendar-shortcode' ) ); ?></a></div>
                  <div class="mb-8 text-center"><a class="text-[#EB6924] hover:text-[#EB6924]" target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-2&utm_content=description"><img alt="" style="width: 300px;" src="<?php echo plugins_url( '/static/images/the-events-calendar-shortcode-pro-compact.png', TECS_CORE_PLUGIN_FILE ); ?>"><br><?php echo esc_html( __( 'Pro version compact design example', 'the-events-calendar-shortcode' ) ); ?></a></div>
                  <div class="mb-8 text-center"><a class="text-[#EB6924] hover:text-[#EB6924]" target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-calendar&utm_content=description"><img alt="" style="width: 300px;" src="<?php echo plugins_url( '/static/images/the-events-calendar-shortcode-pro-calendar.png', TECS_CORE_PLUGIN_FILE ); ?>"><br><?php echo esc_html( __( 'Pro version calendar design example', 'the-events-calendar-shortcode' ) ); ?></a></div>
                  <div class="mb-8 text-center"><a class="text-[#EB6924] hover:text-[#EB6924]" target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-columns&utm_content=description"><img alt="" style="width: 300px;" src="<?php echo plugins_url( '/static/images/the-events-calendar-shortcode-pro-columns.png', TECS_CORE_PLUGIN_FILE ); ?>"><br><?php echo esc_html( __( 'Pro version horizontal/columns/photos design example', 'the-events-calendar-shortcode' ) ); ?></a></div>
                  <div class="mb-8 text-center"><a class="text-[#EB6924] hover:text-[#EB6924]" target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-table&utm_content=description"><img alt="" style="width: 300px;" src="<?php echo plugins_url( '/static/images/the-events-calendar-shortcode-pro-table.png', TECS_CORE_PLUGIN_FILE ); ?>"><br><?php echo esc_html( __( 'Pro version table design example', 'the-events-calendar-shortcode' ) ); ?></a></div>
                  <div class="mb-8 text-center"><a class="text-[#EB6924] hover:text-[#EB6924]" target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-grouped&utm_content=description"><img alt="" style="width: 300px;" src="<?php echo plugins_url( '/static/images/the-events-calendar-shortcode-pro-grouped.png', TECS_CORE_PLUGIN_FILE ); ?>"><br><?php echo esc_html( __( 'Pro version grouped design example', 'the-events-calendar-shortcode' ) ); ?></a></div>
                  <div class="mb-8 text-center"><a class="text-[#EB6924] hover:text-[#EB6924]" target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-filter-bar&utm_content=description"><img alt="" style="width: 300px;" src="<?php echo plugins_url( '/static/images/the-events-calendar-shortcode-filter-bar.png', TECS_CORE_PLUGIN_FILE ); ?>"><br><?php echo esc_html( __( 'Pro version filter bar example', 'the-events-calendar-shortcode' ) ); ?></a></div>
                  <div class="mb-8 text-center"><a class="text-[#EB6924] hover:text-[#EB6924]" target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-design-image-pagination&utm_content=description"><img alt="" style="width: 300px;" src="<?php echo plugins_url( '/static/images/the-events-calendar-shortcode-pagination.png', TECS_CORE_PLUGIN_FILE ); ?>"><br><?php echo esc_html( __( 'Pro version pagination example', 'the-events-calendar-shortcode' ) ); ?></a></div>
                </div>

                <h3 class="additional-options"><?php echo esc_html__( "In addition to designs, you'll get more options, including:", 'the-events-calendar-shortcode' ); ?></h3>
                <div class="flex flex-row flex-wrap justify-around">
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Number of days', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Choose how many days to show events from, ie. 1 day or a week', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Tag', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Filter events listed by one or more tags', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Location', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Display events by city, state/province, or country', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Single Event', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'List the details of a single event by ID, for example on a blog post', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Featured', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Show only events marked as "featured"', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Button', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Add an easy to see button link to your event, and customize the colors/text', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Date', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Show only events for a specific day (ie. 2024-04-16), great for conferences', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Year', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Show only events for a specific year', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Offset', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Skip a certain number of events from the beginning, useful for using multiple shortcodes on the same page or splitting into columns.', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Full Description', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Use the full description instead of the excerpt (short description) of an event in the listing', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Future Only', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Only show events in the future even when using the month or year option.', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Custom Design', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Use the new default or compact designs, or create your own using one or more templates in your theme folder', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Filter Bar', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Allow visitors to change what events are displayed wherever you put a calendar view on your site', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                  <div class="w-5/12 mb-4">
                    <h4><?php echo esc_html__( 'Pagination', 'the-events-calendar-shortcode' ); ?></h4>
                    <div class="mb-2"><?php echo esc_html__( 'Allow visitors to view additional pages of events', 'the-events-calendar-shortcode' ); ?></div>
                  </div>
                </div>

                <div class="my-10 text-center">
                    <?php echo sprintf( esc_html__( '%sGet The Plugin%s', 'the-events-calendar-shortcode' ), '<a class="min-w-[240px] bg-[#EB6924] rounded p-4 flex-none text-white shadow-lg text-center no-underline hover:text-white font-medium text-base" target="_blank" href="https://eventcalendarnewsletter.com/the-events-calendar-shortcode?utm_source=plugin&utm_medium=link&utm_campaign=tecs-help-after-options&utm_content=description">', '</a>' ); ?> or <a href="https://demo.eventcalendarnewsletter.com/the-events-calendar-shortcode/">see it in action</a></div>
                </div>
            </div>
          </div>
     </div>
		 <?php endif; ?>

   </div>

  <div class="w-full">
    <p><small><?php echo sprintf( esc_html__( 'This plugin is not developed by or affiliated with The Events Calendar or %s in any way.', 'the-events-calendar-shortcode' ), 'Modern Tribe' ); ?></small></p>
  </div>
</div>