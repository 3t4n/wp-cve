<?php

class AdsTxt {

        public function __construct() {

                add_action( 'admin_menu', array ( $this, 'adstxtAdminPage' ) );

        }

        /**
         * Registration of Back-end page
         */
        public function adstxtAdminPage() {
                add_menu_page( 'ads.txt status', 'ads.txt', 'manage_options', 'adstxt-plugin', array ( $this, 'adstxtPageContent' ) );
        }

        /**
         * Back-end page for list of URLs
         */
        public function adstxtPageContent() {
          $user = wp_get_current_user();
          if ( in_array( 'administrator', (array) $user->roles ) ) {
                if (isset($_POST) && $_POST['adstxt']) {
                    $dir = wp_get_upload_dir();
                    $tempdate = date("y.m.d.h.i.s");
                    rename($dir['basedir'] . "/ads.txt" , $dir['basedir'] . "/ads.txt-" . $tempdate );
                    file_put_contents($dir['basedir'] . "/ads.txt",esc_textarea($_POST['adstxt']));
                    echo "<div class=\"updated fade\"><p>Your ads.txt records were updated. Previous ads.txt was saved as ads.txt-".esc_html($tempdate)."</p></div>";
                }

                $dir = wp_get_upload_dir();
                $ads = file_get_contents($dir['basedir'] . "/ads.txt");
                echo '<form method="POST">';
                echo "<textarea rows=\"35\" cols=\"60\"  name=\"adstxt\">" . esc_textarea($ads) ."</textarea>";
                submit_button();
                echo "</form>";

        }else{echo "<div class=\"updated fade\"><p>You need to be administrator to access this page.</p></div>";}

        }

}
