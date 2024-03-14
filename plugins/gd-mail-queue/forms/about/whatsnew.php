<?php include(GDMAQ_PATH.'forms/about/minor.php'); ?>

<div class="d4p-about-whatsnew">
    <div class="d4p-whatsnew-section d4p-whatsnew-heading">
        <div class="d4p-layout-grid">
            <div class="d4p-layout-unit whole align-center">
                <h2>Mail Sending Log</h2>
                <p class="lead-description">
                    Log all emails sent with wp_mail or queue
                </p>
                <p>
                    Major update brings a lot of smaller features and most importantly, the emails log that can log all emails sent using wp_mail or the queue, with powerful log overview panel and popup previews for individual emails.
                </p>
            </div>
        </div>
    </div>

    <div class="d4p-whatsnew-section">
        <div class="d4p-layout-grid">
            <div class="d4p-layout-unit half align-left">
                <h3>Powerful Mail Log</h3>
                <p>
                    The log can intercept all the emails sent through wp_mail and the queue, log into the database all available information, and using the Log panel, you can review all emails. Log has email filters, and search.
                </p>
                <p>
                    Preview popup panel can show individual emails, including the content, and preview the HTML (if available). You can delete emails, or plugin can auto clean up the log.
                </p>
            </div>
            <div class="d4p-layout-unit half align-left">
                <img src="https://dev4press.s3.amazonaws.com/plugins/gd-mail-queue/3.0/about/log.jpg" />
            </div>
        </div>
    </div>

    <div class="d4p-whatsnew-section">
        <div class="d4p-layout-grid">
            <div class="d4p-layout-unit half align-left">
                <h3>Intercept all wp_mail calls</h3>
                <p>
                    Plugin can intercept all emails sent through the wp_mail function and decide what needs to be done with it (turn to HTML or added to queue depending on the number of email recepients), based on the plugin settings.
                </p>
            </div>
            <div class="d4p-layout-unit half align-left">
                <h3>Turn plain emails to HTML</h3>
                <p>
                    If the email is plain text, plugin can take plain text and wrap into predefined (email safe and tested) or custom HTML template added through plugin settings. HTMLfy option works on all emails, regardless if they end in the queue.
                </p>
            </div>
        </div>
    </div>

    <div class="d4p-whatsnew-section">
        <div class="d4p-layout-grid">
            <div class="d4p-layout-unit half align-left">
                <h3>Add emails to queue</h3>
                <p>
                    If email has more then one TO recepient, CC and or BCC recepients, plugin will take that one email and generated multiple emails based on it where all recepients receive individual emails. Each new email is added to the database.
                </p>
            </div>
            <div class="d4p-layout-unit half align-left">
                <h3>Adjutable queue mailing</h3>
                <p>
                    Queue is processed in the background (using CRON job), and you can adjust the period for the queue processing (5 minutes default), number of emails to send in a batch, and timeout allowed for server not to break the exectuion.
                </p>
            </div>
        </div>
    </div>
</div>
