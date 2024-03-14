<?php
/**
 * Represents the view for the administration dashboard.
 *
 * @package   Book a Place
 * @author    ArtkanMedia
 * @license   GPL-2.0+
 * @copyright 2018 ArtkanMedia
 */
?>
<div class="wrap">

    <?php screen_icon('options-general'); ?>
    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>

    <div class="bap-description-left">

        <p>
            Create a scheme of your theater, cinema, restaurant etc. Use our event manager. Online booking!
        </p>

        <p>
            Booking places, seats, tickets… In theaters, cinemas, restaurants etc. It’s really convenient, when people are able to book a place online. With our plugin it’s possible.
        </p>

        <p>
            Book a Place plugin is very easy to use by both parties: users, who want to book a place, and administrators, who create a scheme, add places, set prices etc. Moreover, there you will find powerful event manager. We created our plugin as simple as possible. And we are ready to continue working on it to make
            it better.
        </p>

    </div>

    <div class="bap-description-right">

        <h3>We are happy to propose you our Book a Place Pro plugin:</h3>

        <a target="_blank" class="codecanyon-thumb" href="http://codecanyon.net/item/book-a-place-pro-wordpress-plugin/7127218">
            <img src="<?php echo BAP_DIR_URL; ?>/img/codecanyon-thumb.jpg" alt="Book a Place Pro - Wordpress Plugin" width="80" height="80"/>
        </a>

        <ol>
            <li>Paypal payments.</li>
            <li> Plugin permissions.</li>
            <li>Coupon/discount system.</li>
            <li>Custom scheme numbering.</li>
            <li>Photo gallery for each place.</li>
            <li>Bulk actions in scheme editor.</li>
        </ol>

        <div style="clear: both;"></div>

        <p>
            You can find the Pro version <a target="_blank" href="http://codecanyon.net/item/book-a-place-pro-wordpress-plugin/7127218">here</a>.
        </p>

    </div>

    <div class="bap-description">

        <h3>How to</h3>

        <p>
            There are two possible variants of using our plugin:
        </p>

        <p>
            <strong>If you just want to show the booking scheme </strong>
        </p>

        <ol>
            <li>
                You should go to <strong>Schemes</strong> section and create a new scheme there. Scheme is just a scheme of your theater, cinema or restaurant. There you should input name, description, width and height. <br/> <br/>
                <strong>Name</strong> is just a name. <br/>
                In <strong>description</strong> you should describe the event, mention the date and time and other important info. <br/>
                <strong>Width</strong> – number of cells horizontally. In each cell you will be able to set a place. Each cell is like a 1 square meter. <br/>
                <strong>Height</strong> – number of cells vertically. <br/> <br/>
                Are you confused a bit? Don’t worry, we are almost there :)
            </li>
            <li>
                Now you should find your scheme in listing and click on <strong>View</strong>.
            </li>
            <li>
                Here you can set <strong>places</strong>, <strong>prices</strong> etc. Use colors to group similar things. Create different places using several cells.
            </li>
            <li>
                You can find a <strong>shortcode</strong> for each scheme in Schemes section. This shortcode can be embedded to any post or page.
            </li>
        </ol>

        <p>
            <strong>If you want to use the event manager</strong>
        </p>

        <ol>
            <li>
                You should create a <strong>scheme</strong> as we described above.
            </li>
            <li>
                Then go to <strong>Events</strong> section and create an event there. There are three possible types of great calendars: month, week, day. You can choose one or several cells to create an event for. We created three color options for each event: background, border and text. Enjoy!
            </li>
            <li>
                When you added, just click on event to find a <strong>shortcode</strong>.
            </li>
        </ol>

        <p>
            As you understand, if you use event manager, then your booking will be closed automatically.
        </p>

        <p>
            That’s it! Now everybody can book a place. You will get an email about this as administrator and user will get an email too. Email templates can be edited in <strong>Settings</strong> section (‘<strong>E-mail templates</strong>’ tab). In section <strong>Orders</strong> administrator will find information
            about all orders.
        </p>

    </div>

</div>
