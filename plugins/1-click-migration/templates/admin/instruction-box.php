
<div id="ocm-instruction-box-accordion">
  <h3>How to Back Up?</h3>
  <div>
    <ul>
        <li>
          <h4>1. Cleanup Site</h4>
          <p>Remove any unwanted Themes, Plugins & Media Files. Database cleanup is also recommended. If you don't know how to do this manually three are many cleaner plugins you can try.</p>
          <p>Please deactivate all plugins except for '1 Click Migration' if possible</p>
        </li>
        <li>
          <h4>2. Backup Site</h4>
          <p>Enter your email and choose a password then click Backup Site. Backup can take up to 30 minutes to complete.</p>
           <p>
            The password will be used to encrypt your files while they are being backed up, streamed,
            and stored on our servers. A strong password is recommended. Do not use your WordPress
            password.
          </p>
        </li>
        <li>
          <h4>3. Troubleshooting</h4>
        <p>
          If backup fails repeatedly, press 'Stop & Reset' and use the advanced options dropdown to backup section by section.
          You can use the same username and password each time and we will append the existing backup.
        </p>
          <p>
		  The timer displayed under the progress bar is the max execution time your server has set. We try to increase it if possible and if a process runs out of time we try to automatically restart it. In some cases the site is simply too large for the plugin to finish so we recommend either cleaning up more files, increasing server memory and execution time. If these are not possible you may need to find an alternative solution or perform a manual migration.
          </p>
        </li>
        <li>
          <h4>4.Important Info</h4>
          <p>We do not have access to your unencrypted files and cannot recover the password for you.</p>
          <p>If you use the same email/pass combination repeatedly we will overwrite the backup data each time</p>
          <p>You have 24 hrs after the backup was created to execute the restore.</p>
        </li>
      </ul>
  </div>

  <h3>How to Restore?</h3>
  <div>
    <ul>
        <li>
          <h4>1. Restore Guide</h4>
          <p>When you are ready to restore your site install Wordpress and 1 Click Migration on the new host.</p>
          <p>Please delete all other pre-installed plugins</p>
          <p>Enter your email and password and click <strong>Restore</strong>. Restore can take up to 30 minutes to complete.</p>
        </li>
        <li>
          <h4>2. Troubleshooting</h4>
         <p>
          If your backup is large and restoring fails, press 'Stop & Reset', then use the advanced options dropdown to restore one section at a time. Please restore the Database last.
        </p>
        <p>
          If restore did not work you should enable Wordpress debug mode https://wordpress.org/support/article/debugging-in-wordpress/, purge the DB and try again.
        </p>
        </li>
        <li>
          <h4>4.Important Info</h4>
          <p>Upon restore, <strong><?php echo esc_url(get_site_url()) ?></strong> will be used as your new primary domain and will be updated everywhere in the database.</p>
          <p>Old WordPress users and passwords are also restored in this process.</p>
          <p>After 24 hrs the backup files will be deleted from our servers. If you did not finish the migration at this time, you will need to restart the backup process.</p>      
          <p>Please be considerate. The small fee we charge helps us with hosting and development costs. Migration is a very complex task due to WordPress being a dynamic and pluggable system combined with the various hosting environments available.</p>      
          <p>If you run into issues please contact us via <a target="_blank" href="https://1clickmigration.com/contact-us/">this Contact Form</a>. Please include the email you used with the plugin so we can locate your log files and try to help you. If we are not able to help you will refund your charge guaranteed.</p>
        </li>
     </ul>
  </div>

</div>
