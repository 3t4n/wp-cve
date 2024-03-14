<?php
/*
    Exit if accessed directly
*/
    if ( ! defined( 'ABSPATH' ) ) exit;
    if ( ! class_exists( 'LCW_Live_Score' ) ) {

      class LCW_Live_Score extends LCW_Base_Class{

        /**
         * Setup the plugin data
         *
         * @since 1.0.0
        */
        public function __construct() {

          $this->add_action( 'admin_menu', 'lcw_score_admin_menus');

          $this->add_action( 'wp_loaded', 'lcw_score_front_reg_scripts');

          $this->add_action( 'admin_enqueue_scripts', 'lcw_score_admin_scripts');

          $this->add_action( 'admin_enqueue_scripts', 'lcw_score_admin_styles');

          $this->add_action( 'wp_enqueue_scripts', 'lcw_score_front_scripts');

          $this->add_action( 'wp_enqueue_scripts', 'lcw_score_front_styles');

          $this->add_action( 'wp_footer', 'lcw_add_js_code');

          $this->add_action( 'wp_head', 'lcw_add_css_code');

          $this->add_action( 'init', 'lcw_score_mapping'  );

          $this->add_action( 'init', 'lcw_score_matches_mapping'  );

          $this->add_filter( 'lcw_fixture_text', 'lcw_add_fixture_text');
          
          $this->add_filter( 'lcw_stats_tab_text' , 'lcw_add_stats_tab_text');

          $this->add_filter( 'lcw_completed_tab_text' , 'lcw_add_completed_tab_text');

          $this->add_filter( 'lcw_upcomming_tab_text' , 'lcw_add_upcomming_tab_text');

          $this->add_filter( 'lcw_series_text'        , 'lcw_add_series_text');

          $this->add_filter( 'lcw_completed_widget_text', 'lcw_add_completed_widget_text');

          $this->add_filter( 'lcw_upcomming_widget_text', 'lcw_add_upcomming_widget_text');

          $this->add_filter( 'lcw_live_widget_text', 'lcw_add_live_widget_text');

          $this->add_ajax  ( 'lcw_update_score', 'lcw_update_score');

          $this->add_ajax  ( 'lcw_update_live_score', 'lcw_update_live_score');

          $this->add_ajax  ( 'lcw_update_score_cards', 'lcw_update_score_cards');
          $this->add_ajax  ( 'lcw_update_psl_score', 'lcw_update_psl_score');
          $this->add_ajax  ( 'lcw_update_psl_score_shortcode', 'lcw_update_psl_score_shortcode');
          $this->register_shortcode( 'series', 'lcw_score_series_shortcode' );

          $this->register_shortcode( 'series-matches', 'lcw_score_series_matches_shortcode' );

          $this->register_shortcode( 'match-detail', 'lcw_match_detail_shortcode' );

          $this->register_shortcode( 'player-stats', 'lcw_player_stats_shortcode' );
          $this->register_shortcode( 'psl-match', 'lcw_psl_match_shortcode' );
          $this->add_action( 'init', 'lcw_feed');
          $this->add_filter( 'document_title_parts', 'lcw_change_document_title_parts' );
          $this->add_filter( 'pre_get_document_title', 'lcw_change_document_title', 999,1 );
          $this->add_action( 'init','lcw_rewrite_rules');
          $this->add_action( 'admin_init', 'lcw_flush_rewrite');
          $this->add_ajax  ( 'lcw_series_list', 'lcw_list_ajax');
          $this->add_action( 'admin_footer'    , 'lcw_series_list' );
          $this->add_action( 'admin_head'      , 'lcw_mce_button');

        }
        public function lcw_update_psl_score_shortcode( ){

           die();
        }
        public function lcw_flush_rewrite() {

            if ( !get_option('plugin_settings_have_changed') ) {
                flush_rewrite_rules();
                update_option('plugin_settings_have_changed', false);
            }
        }
        /**
         * Add widget to  Admin  
         *
         * @access public
         * @param void
         * @return void
         * @since 1.0.0
        */
        public function lcw_score_widget( ){
          register_widget( 'lcw_live_score_widget' );
        }
        /**
         * Add Admin Menu 
         *
         * @access public
         * @param void
         * @return void
         * @since 1.0.0
        */
        public static function lcw_score_admin_menus() {

          add_menu_page( LCW_LIVE_SCORE_PLUGIN_NAME, __( LCW_LIVE_SCORE_PLUGIN_NAME , 'emoji-reaction-settings' ), 'manage_options', 'lcw-score-settings', array(
           __CLASS__,
           'lcw_score_plugin_settings_page'

         ), plugins_url( 'images/live-score.png', dirname(__FILE__)));


        }
        /**
         * Add Settings Page
         *
         * @access public
         * @param void
         * @return void
         * @since 1.0.0
        */
        public static function lcw_score_plugin_settings_page() {

            require_once LCW_LIVE_SCORE_ROOT_PATH . '/admin-pages/live-score-settings.php';
        }
        public function lcw_feed(){
            
            add_feed('lcw-feed', array($this,'lcw_feed_content'));
        }
        
      /**
       * Function to output button list ajax script
       * @since  1.6
       * @return string
       */
      public function lcw_series_list() {
        // create nonce
        global $pagenow;
        if( $pagenow != 'admin.php' ){
          $nonce = wp_create_nonce( 'twd-nonce' );
          ?>
          <script type="text/javascript">
          jQuery( document ).ready( function( $ ) {
            var data = {
              'action'  : 'lcw_series_list',             // wp ajax action
              'security'  : '<?php echo $nonce; ?>'   // nonce value created earlier
            };
            // fire ajax
              jQuery.post( ajaxurl, data, function( response ) {
                // if nonce fails then not authorized else settings saved
                if( response === '-1' ){
                  // do nothing
                  console.log('error');
                } else {
                  if (typeof(tinyMCE) != 'undefined') {
                    if (tinyMCE.DOM != null) {
                    tinyMCE.DOM.cptPostsList = response;
                  }
                }
                }
              });
          });
        </script>
        <?php
      }
    }
    // Hooks your functions into the correct filters
    public function lcw_mce_button() {
      // check user permissions
      if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) ) {
        return;
      }
      // check if WYSIWYG is enabled
      if ( 'true' == get_user_option( 'rich_editing' ) ) {
        $this->add_filter( 'mce_external_plugins', 'add_mce_plugin' );
        $this->add_filter( 'mce_buttons', 'register_mce_button'  );
      }
    }

    // Script for our mce button
    public function add_mce_plugin( $plugin_array ) {
      $plugin_array['twd_mce_button'] = plugins_url('js/mce.js',dirname(__FILE__));
      return $plugin_array;
    }

    // Register our button in the editor
    public function register_mce_button( $buttons ) {
      array_push( $buttons, 'twd_mce_button' );
      return $buttons;
    }
    public function lcw_list_ajax( ){
            $content = '';
            $content        = $this->lcw_get_content('series');
            $contents       = utf8_encode($content); 
            $series         = json_decode($contents);
            $arr            = array();

            if( !empty( $series ) ){

              $series_list    = $series->seriesList->series;
              $arr[ 'World Cup 2019' ] = 2181;
              foreach ($series_list as $single_series) {

                
                $list[] = array(
                              'text' => $single_series->name,
                              'value' =>  $single_series->id
                            );
              }
              $list[]   = array(

                              'text' => 'World Cup 2019',
                              'value' =>  2181
                            );

              $json = wp_send_json( $list );
              return $json;
        }
      }
      public function lcw_update_psl_score( ){
          $match_id = $_POST['match_id'];
          $psl_content            = $this->lcw_get_content_psl( 'https://cricket.yahoo.net/sifeeds/cricket/live/json/'.$match_id.'.json' );
          $psl_contents       = utf8_encode( $psl_content ); 
          $pslmatches_list    = json_decode( $psl_contents);
           ob_start();
        ?>
           <div class="lcw-match-info">
                <div class="row">
                    <div class="col-md-6">
                            
                        <?php 
                          if(!empty($pslmatches_list->Innings)){

                            foreach ( $pslmatches_list->Innings as $Inning ) { 

                                $team  = $Inning->Battingteam;
                                
                            ?>
                               
                            <div class="lcw-home-team">
                               
                                <?php echo $pslmatches_list->Teams->{$team}->Name_Full  ?>
                               
                                <span>  <?php echo $Inning->Total  ?>/<?php echo $Inning->Wickets  ?> (<?php echo $Inning->Overs  ?>) </span>

                            </div>
                       <?php } ?>
                       <?php }else{ ?>
                        <div class="lcw-home-team">
                            <?php echo $pslmatches_list->Teams->{ $pslmatches_list->Matchdetail->Team_Home }->Name_Full ?> 
                        </div>
                        <div class="lcw-home-team">
                            <?php echo $pslmatches_list->Teams->{ $pslmatches_list->Matchdetail->Team_Away }->Name_Full ?> 
                        </div>
                    <?php } ?>
                        <div class="lcw-match-msg">
                            <?php echo $pslmatches_list->Matchdetail->Result; ?>
                            <?php echo $pslmatches_list->Matchdetail->Status; ?>
                            
                            
                        </div>
                        
                    </div>
                    <div class="col-md-6">
                        <p><?php echo $pslmatches_list->Matchdetail->Series->Name; ?></p>
                        <p><?php echo $pslmatches_list->Matchdetail->Venue->Name; ?></p>
                        <p>Start Date : <?php echo date('d F Y ',strtotime( $pslmatches_list->Matchdetail->Match->Date )) ?></p>
                    </div>
                </div>
            </div>    
         
        <?php if($pslmatches_list->Matchdetail->Status_Id == 117): ?>
            <div class="lcw-table lcw-batsmen" id="lcw-sm-table">
                        
                        <div class="lcw-tbody">
                            <?php foreach ( $pslmatches_list->Innings as $Inning ) { 

                                $team   = $Inning->Battingteam;
                                
                            ?>
                            
                            <div class="lcw-thead">
                            <div class="lcw-tr" style="background: #3e3e3e !important;">
                              <div class="lcw-td" style="text-align: center;"><?php echo $pslmatches_list->Teams->{$team}->Name_Full  ?>( Run Rate : <?php echo $Inning->Runrate  ?> )</div>
                              
                            </div>
                        </div>
                        <div class="lcw-thead">
                            <div class="lcw-tr">
                              <div class="lcw-td">Batsmens</div>
                              <div class="lcw-td">R</div>
                              <div class="lcw-td">B</div>
                              <div class="lcw-td">4S</div>
                              <div class="lcw-td">6S</div>
                              <div class="lcw-td">SR</div>
                            </div>
                        </div>
                            <?php foreach ($Inning->Batsmen as $batsmen ) {


                                if(!isset($batsmen->Isbatting) && !$batsmen->Isbatting){

                                    continue;
                                }
                                if(isset($batsmen->Isonstrike) && $batsmen->Isonstrike){

                                    $strike = '<em style="color: red;">*</em>';
                                }else{

                                    $strike = "";
                                }
                            ?>
                            <div class="lcw-tr">
                                <div class="lcw-td">
                                    
                                    <?php 
                                        
                                        echo $pslmatches_list->Teams->{$team}->Players->{ $batsmen->Batsman }->Name_Full.$strike;
                                    ?>
                                   <p><?php echo $batsmen->Howout ?></p>
                                </div>
                                <div class="lcw-td"><?php echo $batsmen->Runs ?></div>
                                <div class="lcw-td"><?php echo $batsmen->Balls ?></div>
                                <div class="lcw-td hidden-xs hidden-sm"><?php echo $batsmen->Fours ?></div>
                                <div class="lcw-td hidden-xs hidden-sm"><?php echo $batsmen->Sixes ?></div>
                                <div class="lcw-td hidden-xs hidden-sm"><?php echo $batsmen->Strikerate ?></div>
                            </div>
                        <?php } ?>
                        <div class="lcw-thead">
                            <div class="lcw-tr">
                                <div class="lcw-td">Bowler</div>
                                <div class="lcw-td">O</div>
                                <div class="lcw-td">R</div>
                                <div class="lcw-td">W</div>
                                <div class="lcw-td hidden-xs hidden-sm">Econ</div>
                                <div class="lcw-td hidden-xs hidden-sm">WD</div>
                            </div>
                        </div>
                         <?php foreach ($Inning->Bowlers as $bowler ) {

                             
                                if(!isset($bowler->Isbowlingtandem) && !$bowler->Isbowlingtandem){

                                    continue;
                                }
                                if(isset($bowler->Isbowlingnow) && $bowler->Isbowlingnow){

                                    $strike = '<em style="color: red;">*</em>';
                                }else{

                                    $strike = "";
                                }
                            ?>
                            <div class="lcw-tr">
                                <div class="lcw-td">
                                    
                                    <?php 
                                        //echo $bowler->Bowler;
                                    if($pslmatches_list->Teams->{$pslmatches_list->Matchdetail->Team_Home}->Players->{$bowler->Bowler}->Name_Full){

                                       echo $pslmatches_list->Teams->{$pslmatches_list->Matchdetail->Team_Home}->Players->{$bowler->Bowler}->Name_Full.$strike;

                                    }elseif( $pslmatches_list->Teams->{$pslmatches_list->Matchdetail->Team_Away}->Players->{$bowler->Bowler}->Name_Full ){


                                        echo $pslmatches_list->Teams->{$pslmatches_list->Matchdetail->Team_Away}->Players->{$bowler->Bowler}->Name_Full.$strike;
                                    }else{


                                    }
                                    ?>
                                </div>
                                <div class="lcw-td"><?php echo $bowler->Overs ?></div>
                                <div class="lcw-td"><?php echo $bowler->Runs ?></div>
                                <div class="lcw-td"><?php echo $bowler->Wickets ?></div>
                                <div class="lcw-td hidden-xs hidden-sm"><?php echo $bowler->Economyrate ?></div>
                                <div class="lcw-td hidden-xs hidden-sm"><?php echo $bowler->Wides ?></div>
                            </div>
                        <?php } ?>

                        <?php } ?>
                        </div>
                    </div>
                    <?php endif; ?>
           <?php
           $content = ob_get_clean();

           echo $content;
           die();
      }
      public function lcw_psl_match_shortcode( $atts ){
           
            /*extract(shortcode_atts(
              array(

                'match_id'       => -1,

              ), $atts));
            require_once LCW_LIVE_SCORE_ROOT_PATH . '/templates/psl-matches.php';
            $content = lcw_load_psl_match( $this, $match_id );*/
            return false;
      }
      public function lcw_get_content_psl( $segment ){
          if ( function_exists('curl_init')){ 
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $segment );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $content = curl_exec($ch);
            curl_close($ch);
            
             
          }else if( ini_get('allow_url_fopen') ){
            
            $content = @file_get_contents( $segment );
                          
          }else{

            $content = 'Please enable curl or allow_url_fopen in order to get cricket data';

          }
          return $content;
      }
      public function lcw_change_document_title( $title  ){

              $series_id      =  isset( $_GET['series-id'] ) ? $_GET['series-id'] : '';
              $match_id       =  isset( $_GET['match-id'] ) ? $_GET['match-id'] : '';
              $player_id      =  isset( $_GET['player-id'] ) ? $_GET['player-id'] : '';
              $newtitle       = '';
              if( get_query_var( 'series' ) ){

                  $series_id = get_query_var( 'series' );

              }else{

                $series_id = $series_id;
              }
              if( get_query_var( 'match' ) ){

                $match_id = get_query_var( 'match' );

              }else{

                $match_id = $match_id;
            }
            if( get_query_var( 'player' ) ){


            }
            if( ! empty( $title ) &&  is_page('match-detail' ) ){

              $match_detail_content    =  $this->lcw_get_content('matches/'.$series_id.'/'.$match_id);
              $match_detail_contents   =  utf8_encode( $match_detail_content ); 
              $match_detail_list       =  json_decode($match_detail_contents);
                if($match_detail_list->match->status == 'INPROGRESS' || $match_detail_list->match->status == 'LIVE' ){

                  if( !empty( $match_detail_list->match->scores->homeScore ) ){

                        $homeScore  = $match_detail_list->match->scores->homeScore.' ( '. 

                        $match_detail_list->match->scores->homeOvers.' ) ovs';

                      }else{

                        $homeScore = '';
                      }
                      if( !empty( $match_detail_list->match->scores->awayScore ) ){

                        $awayScore = $match_detail_list->match->scores->awayScore.' ( '. 

                       $match_detail_list->match->scores->awayOvers.' ) ovs';

                      }else{

                        $awayScore = '';
                      }


                        $newtitle = $match_detail_list->match->homeTeam->shortName.' '.$homeScore.' | '.$match_detail_list->match->awayTeam->shortName.' '.$awayScore.' | '.$match_detail_list->match->series->name.' '.$title;
                       
                    }else{

                      $newtitle  = $match_detail_list->match->name.' , '.$match_detail_list->match->series->name.' '.$title;
                    }
                  return $newtitle;
                }elseif( is_page( 'player-stats' ) ){
                  
                  $player_id = get_query_var( 'player' );
                  $player_detail_content    =  $this->lcw_get_content('players/'.$player_id.'/stats/');
                  $player_detail_contents   =  utf8_encode( $player_detail_content ); 
                  $player_detail_list       =  json_decode($player_detail_contents);
                 
                  $newtitle = $player_detail_list->meta->firstName.' '.$player_detail_list->meta->lastName.' '.$title;
                  return $newtitle;
              }else{

                return $title;
              }
              
        }
        public function lcw_change_document_title_parts( $title_parts ){
            
            $series_id      =  isset( $_GET['series-id'] ) ? $_GET['series-id'] : $series_id;
            $match_id       =  isset( $_GET['match-id'] ) ? $_GET['match-id'] : $match_id;
              if( get_query_var( 'series' ) ){

                  $series_id = get_query_var( 'series' );

              }else{

                $series_id = $series_id;
              }
              if( get_query_var( 'match' ) ){

                $match_id = get_query_var( 'match' );

              }else{

                $match_id = $match_id;
              }
              $match_detail_content    =  $this->lcw_get_content('matches/'.$series_id.'/'.$match_id );
              $match_detail_contents   =  utf8_encode( $match_detail_content ); 
              $match_detail_list       =  json_decode($match_detail_contents);

              if( is_page('match-detail' ) ){

                if($match_detail_list->match->status == 'INPROGRESS' || $match_detail_list->match->status == 'LIVE' ){

                  if( !empty( $match_detail_list->match->scores->homeScore ) ){

                      
                        $homeScore  = $match_detail_list->match->scores->homeScore.' ( '. 

                        $match_detail_list->match->scores->homeOvers.' ) ovs';

                      }else{

                        $homeScore = '';
                      }
                      if( !empty( $match_detail_list->match->scores->awayScore ) ){

                        $awayScore = $match_detail_list->match->scores->awayScore.' ( '. 

                       $match_detail_list->match->scores->awayOvers.' ) ovs';

                      }else{

                        $awayScore = '';
                      }


                        $title_parts['title'] = $match_detail_list->match->homeTeam->shortName.' '.$homeScore.' | '.$match_detail_list->match->awayTeam->shortName.' '.$awayScore.' | '.$match_detail_list->match->series->name;
                       
                    }else{

                      $title_parts['title'] = $match_detail_list->match->name.' , '.$match_detail_list->match->series->name;
                    }
                }elseif(is_page('player-stats')){

                  $title_parts['title'] = $player_name.' | '.get_bloginfo( 'name');;

                }
                
                return $title_parts;
          }
        public function lcw_feed_content( )
        {
            require_once LCW_LIVE_SCORE_ROOT_PATH . '/templates/rss/lcw-feed.php';
            $content         = $this->lcw_get_content('matches?completedLimit=999&inProgressLimit=999&upcomingLimit=999' );
            $contents        = utf8_encode( $content ); 
            $matches_list    = json_decode( $contents);
            $matches         = $matches_list->matchList->matches;
            lcw_load_feed( $matches );
        }
        /**
         * Get Content from api and return
         *
         * @access public
         * @param void
         * @return $content
         * @since 1.0.0
        */
        public function lcw_get_content( $segment )
        {
          if ( function_exists('curl_init')){ 
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, self::BASE_API_URL.'/'.$segment );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $content = curl_exec($ch);
            curl_close($ch);
            
             
          }else if( ini_get('allow_url_fopen') ){
            
            $content = @file_get_contents( self::BASE_API_URL.'/'.$segment );
                          
          }else{

            $content = 'Please enable curl or allow_url_fopen in order to get cricket data';

          }
          return $content;
        }
        /**
         * Update Score Cards
         *
         * @access public
         * @param void
         * @return $content
         * @since 1.0.0
        */
        public function lcw_update_score_cards( )
        {
          $series_id    =  $_POST['series_id'];
          $match_id     =  $_POST['match_id'];
          $content      = $this->lcw_get_content('matches/'.$series_id.'/'.$match_id.'/live');
          echo $content;
          die();
        }
        public function lcw_update_score(){

          $series_id    =  $_POST['series_id'];
          $content      = $this->lcw_get_content('matches/'.$series_id.'?completedLimit=0&upcomingLimit=0');
          echo $content;
          die();
        }
        public function lcw_update_live_score(){

          $series_id               =  $_POST['series_id'];
          $match_id                =  $_POST['match_id'];
          $match_detail_content    =  $this->lcw_get_content('matches/'.$series_id.'/'.$match_id.'/live?OverLimit=5');
          echo $match_detail_content;
          die();
        }
        public function lcw_display_match_tabs( $arr ) {
            $active = 0;
            $match_tabs = $arr;
            $tab_list = '<ul class="nav nav-tabs" role="tablist">';

            if( has_filter( 'lcw_add_match_tabs' )) {

              $match_tabs = apply_filters('lcw_add_match_tabs', $match_tabs);
            
            }

            foreach( $match_tabs as $match_tab => $match_tab_id ) :

              if ( $active == 0 ) {

                $active_class = 'active';

              }else{

                $active_class = '';
              }
              $tab_list .= '<li class="'.$active_class.'"><a class="nav-link" data-toggle="tab" href="#'.$match_tab_id.'" data-target="#'.$match_tab_id.'" role="tab">' . $match_tab . '</a></li>';
              $active++;
            endforeach;
            $tab_list .= '</ul>';

            return $tab_list;
          }
        public function lcw_add_series_text( $text ){

            return $text;
        }
        public function lcw_add_completed_widget_text( $text )
        {
          return $text;
        }
        public function lcw_add_upcomming_widget_text( $text )
        {
          return $text;
        }
        public function lcw_add_live_widget_text( $text )
        {
          return $text;
        }
        public function lcw_add_upcomming_tab_text( $text ){

            return $text;
        }
        public function lcw_add_completed_tab_text( $text ){

            return $text;
        }
        public function lcw_add_stats_tab_text( $text ){

            return $text;
        }
        public function lcw_add_fixture_text( $text ){

            return $text;
        }
        public function lcw_score_mapping( ){

            if ( !defined( 'WPB_VC_VERSION' ) ) {
              return;
            }
            vc_map( 
              array(
                'name' => __('Series', 'wss-live-score'),
                'base' => 'series',
                'description' => __('Shortcode to show series List', 'wss-live-score'), 
                'category' => __('Live cricket score', 'wss-live-score'),   
                'icon' => plugins_url( 'images/live-score.png', dirname(__FILE__) ),            
              )
            );
        }
        public function lcw_score_matches_mapping( ){

            if ( !defined( 'WPB_VC_VERSION' ) ) {
              return;
            }

            $content = '';
            $content        = $this->lcw_get_content('series');
            $contents       = utf8_encode($content); 
            $series         = json_decode($contents);
            $arr            = array();

            if( !empty( $series ) ){

              $series_list    = $series->seriesList->series;
              foreach ($series_list as $single_series) {

                $arr[ $single_series->name ] = $single_series->id;

              }
              // Map the block with vc_map()
              vc_map( 

                array(

                  'name' => __('Series Matches List', 'wss-live-score'),
                  'base' => 'series-matches',
                  'description' => __('Show specific series matches ', 'wss-live-score'), 
                  'category' => __('Live Cricket Score', 'wss-live-score'),   
                  'icon' => plugins_url( 'images/cricket-icon.png', dirname(__FILE__) ), 
                  'params'   => array(
                    array(

                      'type'        => 'dropdown',
                      'heading'     => __('Series id'),
                      'param_name'  => 'series_id',
                      'value'       => $arr,
                      'description' => __('Series id')
                    )
                  )           

                )
              );
            }
        }
        public function lcw_add_css_code(){

            if( get_option('base_color') ){
              echo "<style>
              .lcw-commentry-box-header{ 
                background-color: ".get_option('base_color')." !important;
              }
              .lcw-tabs a.active,.lcw-tabs a.hover, .lcw-livescore-outer .nav-tabs .nav-link.active,.lcw-livescore-outer .nav-tabs>li.active>a{
                color: ".get_option('base_color')." !important;
                border-bottom: 1px solid ".get_option('base_color').";
              }
              
              .lcw-series .lcw-series-tiles{
                background: ".get_option('base_color')." !important;
              }
              .book-now-c a,.overview{
                background-color: ".get_option('base_color')." !important;
              }
             
              #accordion .panel-title a:before, #accordion .panel-title a.collapsed:before{
                  color: ".get_option('base_color')." !important;
              }
              
              .lcw-over-ball.type-4,.lcw-over-ball.type-6,.lcw-batsmen .lcw-thead .lcw-tr{
                background-color: ".get_option('base_color')." !important;
              }
              .series-inner {
                  border: 1px solid ".get_option('base_color')." !important;
              }
              @media screen and (max-width: 767px){
              .lcw-livescore-outer .nav-tabs .nav-link.active:after {
                  background: ".get_option('base_color')." !important;
                }
              }
              </style>";
            }
        }
        public function lcw_add_js_code(  ){
            if( get_query_var( 'series' ) ){

              $series_id = get_query_var( 'series' );

            }else{

              $series_id = $_GET['series-id'];
            }
            
            if( get_query_var( 'status' ) ){

              $series_status = get_query_var( 'status' );

            }else{

              $series_status = $_GET['status'];
            } 
            if( ( get_query_var('series') && ( get_query_var('status') == 'inprogress' || get_query_var('status') == 'live' ) && !get_query_var('match') ) || isset( $_GET['series-id']) && ( $_GET['status'] == 'live' || $_GET['status'] == 'inprogress' ) && !isset( $_GET['match-id'] ) ){
              ob_start()
              ?>
              <script type="text/javascript">
                $ = jQuery;
                function lcw_update_score(series_id,status){

                  $.ajax({

                    type: "POST",
                    url: "<?php echo admin_url('admin-ajax.php')?>",
                    dataType: "json",
                    data: "action=lcw_update_score&series_id="+<?php echo $series_id   ?>,
                    success: function(scores) {
                      for (var i = 0 ; i < scores.matchList.matches.length; i++) {

                       if(scores.matchList.matches[i].status == "LIVE"){
                        console.log(scores.matchList.matches[i].scores.homeScore);
                        $(".home_score_"+i).html(scores.matchList.matches[i].scores.homeScore+ "(" + scores.matchList.matches[i].scores.homeOvers + ")" );
                        $(".away_score_"+i).html(scores.matchList.matches[i].scores.awayScore + "(" + scores.matchList.matches[i].scores.awayOvers + ")" );

                      }
                    }    

                  },
                  error: function( jqXHR, textStatus, errorThrown ) {
                    console.log( "Could not get posts, server response: " + textStatus + ": " + errorThrown );

                  }
                });
                  setTimeout(function(){ 
                    lcw_update_score(); 
                  }, 30000);                   
                }

                lcw_update_score();
              </script>

               
              <?php 
              $js = ob_get_clean();
              echo $js; 
            }
            ob_start();
            ?>
            <script type="text/javascript">
                $ = jQuery;
                function lcw_update_home_score(series_id,match_id){

                  $.ajax({

                    type: "POST",
                    url: "<?php echo admin_url('admin-ajax.php')?>",
                    dataType: "json",
                    data: "action=lcw_update_live_score&series_id="+series_id+'&match_id='+match_id,
                    success: function(scores) {
                      //for (var i = 0 ; i < scores.matchList.matches.length; i++) {

                       //if(scores.matchDetail.matches[i].currentMatchState == "LIVE"){
                        //console.log(scores.matchDetail.awayTeam.scores.homeScore);
                        if (typeof scores.liveMatch.matchDetail.scores.homeScore === 'undefined') {

                            homeScore = '0-0(0)';
                            
                          }else{


                            homeScore = scores.liveMatch.matchDetail.scores.homeScore+'('+scores.liveMatch.matchDetail.scores.homeOvers+')';
                          }
                          if (typeof scores.liveMatch.matchDetail.scores.awayScore === 'undefined') {

                            awayScore = '0-0(0)';
                            
                          }else{

                            awayScore = scores.liveMatch.matchDetail.scores.awayScore+'('+scores.liveMatch.matchDetail.scores.awayOvers+')';

                          }
                        $(".home_score_"+series_id+match_id).html( homeScore );
                        $(".away_score_"+series_id+match_id).html( awayScore );

                      //}
                    //}    

                  },
                  error: function( jqXHR, textStatus, errorThrown ) {
                    console.log( "Could not get posts, server response: " + textStatus + ": " + errorThrown );

                  }
                });
                  setTimeout(function(){ 
                    lcw_update_home_score(series_id,match_id); 
                  }, 30000);                   
                }

                //lcw_update_home_score();
            </script>
            <script type="text/javascript">
                $ = jQuery;
                function lcw_update_psl_score_shortcode(match_id){
                  //console.log(match_id);
                  $.ajax({

                    type: "POST",
                    url: "<?php echo admin_url('admin-ajax.php')?>",
                    data: "action=lcw_update_psl_score_shortcode&match_id="+match_id,
                    success: function(scores) {
                      
                      jQuery('.psl-scores-update').html(scores)
                  },
                  error: function( jqXHR, textStatus, errorThrown ) {
                    //console.log( "Could not get posts, server response: " + textStatus + ": " + errorThrown );

                  }
                });
                  setTimeout(function(){ 
                    lcw_update_psl_score_shortcode(match_id); 
                  }, 60000);                   
                }

                //lcw_update_score();
            </script>
            <script type="text/javascript">
              $ = jQuery;
              function lcw_update_score_custom(series_id,status){

                $.ajax({

                  type: "POST",
                  url: "<?php echo admin_url('admin-ajax.php')?>",
                  dataType: "json",
                  data: "action=lcw_update_score&series_id="+series_id,
                  success: function(scores) {
                    console.log(scores);
                    for ( var i = 0 ; i < scores.matchList.matches.length; i++ ) {

                     if(scores.matchList.matches[i].status == "LIVE"){
                      console.log(scores.matchList.matches[i].scores.homeScore);
                      $(".home_score_"+i).html(scores.matchList.matches[i].scores.homeScore+ "(" + scores.matchList.matches[i].scores.homeOvers + ")" );
                      $(".away_score_"+i).html(scores.matchList.matches[i].scores.awayScore + "(" + scores.matchList.matches[i].scores.awayOvers + ")" );

                    }
                  }    

                },
                error: function( jqXHR, textStatus, errorThrown ) {
                  console.log( "Could not get posts, server response: " + textStatus + ": " + errorThrown );

                }
              });
                setTimeout(function(){ 
                  lcw_update_score_custom(series_id,status); 
                }, 30000);                   
              }
          </script>
          <?php 
          $js = ob_get_clean();
          echo $js;  
          if( get_query_var( 'series' ) ){

              $series_id = get_query_var( 'series' );

            }else{

              $series_id = $_GET['series-id'];
            }
            if( get_query_var( 'series' ) ){

              $match_id = get_query_var( 'match' );

            }else{

              $match_id = $_GET['match-id'];
            }
            if( get_query_var( 'status' ) ){

              $series_status = get_query_var( 'status' );

            }else{

              $series_status = $_GET['status'];
            } 
        
            if( ( get_query_var('series') && ( get_query_var('status') == 'inprogress' || get_query_var('status') == 'live' ) && get_query_var('match') ) || isset( $_GET['series-id']) && ( $_GET['status'] == 'live' || $_GET['status'] == 'inprogress' ) &&isset( $_GET['match-id'] ) ){
            ob_start();
          ?>
          <script type="text/javascript">
              jQuery(document).ready(function($){
                $('[data-toggle="tab"]').on('click', function(){
                  var $this = $(this);
                  loadurl = $this.attr('href');
                  targ = $this.attr('data-target');

                  html = tab_html = '';
                  if( targ == '#score-card'){
                   $.ajax({
                      type: "POST",
                      url: "<?php echo admin_url('admin-ajax.php')?>",
                      dataType: "json",
                      data: "action=lcw_update_score_cards&series_id=<?php echo $series_id  ?>&match_id=<?php echo $match_id ?>",
                      success: function( match_detail_list ) {
                   
                    team_active = 0;
                  for (var i = 0 ; i < match_detail_list.liveMatch.scoreCard.length; i++) {

                    if( team_active == 0 ){

                      active_class = "active";

                    }else{

                      active_class = '';
                    }
                    if ( match_detail_list.liveMatch.matchDetail.isMultiDay ) {


                      html += '<li class="nav-item '+active_class+'">\
                      <a class="nav-link" data-toggle="tab" href="#'+match_detail_list.liveMatch.scoreCard[i].shortName+'-'+team_active+'" role="tab">'+match_detail_list.liveMatch.scoreCard[i].name+'</a>\
                      </li>';
                    }
                    else{

                      
                      html += '<li class="nav-item '+active_class+'">\
                      <a class="nav-link" data-toggle="tab" href="#'+match_detail_list.liveMatch.scoreCard[i].shortName+'-'+team_active+'" role="tab">'+match_detail_list.liveMatch.scoreCard[i].name+'</a>\
                      </li>';
                    }   
                    team_active++; 
                  }
                  html += '</ul>';
                  team_active = 0;
                  console.log(match_detail_list.liveMatch.scoreCard.length);
                  for (var k = 0 ; k < match_detail_list.liveMatch.scoreCard.length; k++) {
                    if( team_active == 0 ){

                      active_class = "active";

                    }else{

                      active_class = '';
                    }
                    tab_html += '<div class="tab-pane '+active_class+'" id="'+match_detail_list.liveMatch.scoreCard[k].shortName+'-'+team_active+'" role="tabpanel">\
                    <div class="lcw-table lcw-batsmen" >\
                    <div class="lcw-thead">\
                    <div class="lcw-tr">\
                    <div class="lcw-td">Batsmen</div>\
                    <div class="lcw-td">R</div>\
                    <div class="lcw-td">B</div>\
                    <div class="lcw-td">4S</div>\
                    <div class="lcw-td">6S</div>\
                    <div class="lcw-td">SR</div>\
                    </div>\
                    </div>\
                    <div class="lcw-tbody">';
                    console.log( match_detail_list.liveMatch.scoreCard[k].batsMen.length);
                    for (var j = 0 ; j < match_detail_list.liveMatch.scoreCard[k].batsMen.length; j++) { 

                      tab_html += '<div class="lcw-tr">\
                      <div class="lcw-td">\
                      <a href="<?php echo home_url() ?>/player-stats/player/'+match_detail_list.liveMatch.scoreCard[k].batsMen[j].id+'">'+match_detail_list.liveMatch.scoreCard[k].batsMen[j].name+'</a>\
                      <p class="out-status">'+ match_detail_list.liveMatch.scoreCard[k].batsMen[j].howOut+'</p>\
                      </div>\
                      <div class="lcw-td"> '+ match_detail_list.liveMatch.scoreCard[k].batsMen[j].runs+'</div>\
                      <div class="lcw-td"> '+ match_detail_list.liveMatch.scoreCard[k].batsMen[j].ballsFaced+'</div>\
                      <div class="lcw-td">'+ match_detail_list.liveMatch.scoreCard[k].batsMen[j].fours+'</div>\
                      <div class="lcw-td">'+ match_detail_list.liveMatch.scoreCard[k].batsMen[j].sixers+'</div>\
                      <div class="lcw-td">'+ match_detail_list.liveMatch.scoreCard[k].batsMen[j].strikeRate +'</div>\
                      </div>';
                    }
                    team_active++;

                    tab_html += '</div></div>';
                    tab_html += '<div class="lcw-table lcw-batsmen lcw-bowlers" >\
                    <div class="lcw-thead">\
                    <div class="lcw-tr">\
                    <div class="lcw-td">Bowlers</div>\
                    <div class="lcw-td">O</div>\
                    <div class="lcw-td hidden-xs hidden-sm">M</div>\
                    <div class="lcw-td">R</div>\
                    <div class="lcw-td">W</div>\
                    <div class="lcw-td">WD</div>\
                    <div class="lcw-td hidden-xs hidden-sm">NB</div>\
                    <div class="lcw-td">Econ</div>\
                    </div>\
                    </div>\
                    <div class="lcw-tbody">';

                    for (var b = 0 ; b < match_detail_list.liveMatch.scoreCard[k].bowlers.length; b++) { 
                      tab_html += '<div class="lcw-tr">\
                      <div class="lcw-td"><a href="<?php echo home_url() ?>/player-stats/player/'+match_detail_list.liveMatch.scoreCard[k].bowlers[b].id+'">'+match_detail_list.liveMatch.scoreCard[k].bowlers[b].name+'</a></div>\
                      <div class="lcw-td">'+match_detail_list.liveMatch.scoreCard[k].bowlers[b].bowlerOver+'</div>\
                      <div class="lcw-td hidden-xs hidden-sm">'+match_detail_list.liveMatch.scoreCard[k].bowlers[b].maiden+'</div>\
                      <div class="lcw-td">'+match_detail_list.liveMatch.scoreCard[k].bowlers[b].runsAgainst+'</div>\
                      <div class="lcw-td">'+match_detail_list.liveMatch.scoreCard[k].bowlers[b].wickets+'</div>\
                      <div class="lcw-td">'+match_detail_list.liveMatch.scoreCard[k].bowlers[b].wide+'</div>\
                      <div class="lcw-td hidden-xs hidden-sm">'+match_detail_list.liveMatch.scoreCard[k].bowlers[b].noBall+'</div>\
                      <div class="lcw-td">'+match_detail_list.liveMatch.scoreCard[k].bowlers[b].economy+'</div>\
                      </div>';
                    } 
                    tab_html += '</div>\
                    </div>\
                    </div>\
                    </div>\
                    </div>';
                  }
                  $('#teams_section').html(html);
                  $('#score-card-html').html(tab_html);
                  $this.tab('show');
                  return false;

                     //}
                   },
                   error: function( jqXHR, textStatus, errorThrown ) {
                    console.log( "Could not get posts, server response: " + textStatus + ": " + errorThrown );

                  }
                });  
              }

          });
      });
    function lcw_update_live_score(){
        <?php  
          if( get_query_var( 'series' ) ){

              $series_id = get_query_var( 'series' );

            }else{

              $series_id = $_GET['series-id'];
            }
            if( get_query_var( 'series' ) ){

              $match_id = get_query_var( 'match' );

            }else{

              $match_id = $_GET['match-id'];
            }
            if( get_query_var( 'status' ) ){

              $series_status = get_query_var( 'status' );

            }else{

              $series_status = $_GET['status'];
            } 
        ?>
              $ = jQuery;
              jQuery.ajax({

                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php')?>",
                dataType: "json",
                data: "action=lcw_update_live_score&series_id=<?php echo $series_id  ?>&match_id=<?php echo $match_id ?>",
                success: function( match_detail_list ) {
                          
                          html= '';
                          comment_type = '';
                          if (typeof match_detail_list.liveMatch.matchDetail.scores.homeScore === 'undefined') {

                            homeScore = '';
                            
                          }else{


                            homeScore = match_detail_list.liveMatch.matchDetail.scores.homeScore+' ( '+match_detail_list.liveMatch.matchDetail.scores.homeOvers+' overs )';
                          }
                          if (typeof match_detail_list.liveMatch.matchDetail.scores.awayScore === 'undefined') {

                            awayScore = '';
                            
                          }else{

                            awayScore = match_detail_list.liveMatch.matchDetail.scores.awayScore+' ( '+match_detail_list.liveMatch.matchDetail.scores.awayOvers+' overs )';

                          }
                            
                            html += '<div class="lcw-match-info">\
                            <div class="row">\
                            <div class="col-md-8">\
                            <div class="lcw-home-team" style="color: '+match_detail_list.liveMatch.matchDetail.homeTeam.teamColour+'">\
                            '+match_detail_list.liveMatch.matchDetail.homeTeam.name+'\
                            <span>'+homeScore+'</span>\
                            </div>\
                            <div class="lcw-away-team" style="color: '+match_detail_list.liveMatch.matchDetail.awayTeam.teamColour+'">'+match_detail_list.liveMatch.matchDetail.awayTeam.name+'\
                            <span>'+awayScore+'</span>\
                            </div>\
                             <div class="lcw-match-msg">'+match_detail_list.liveMatch.matchDetail.matchSummaryText+'</div>\
                            </div>\
                            <div class="col-md-4">\
                            <div class="lcw-short-state">\
                            Required Run Rate : '+match_detail_list.liveMatch.meta.requiredRunRate+'\
                            </div>\
                            <div class="lcw-short-state">\
                            Current Run Rate : '+match_detail_list.liveMatch.meta.currentRunRate +'\
                            </div>\
                            <div class="lcw-match-msg">Toss - '+match_detail_list.liveMatch.matchDetail.tossMessage+'</div>\
                            </div>\
                            </div>\
                            </div>';
                            html += '<div class="lcw-table lcw-batsmen" >\
                            <div class="lcw-thead">\
                            <div class="lcw-tr">\
                            <div class="lcw-td">Batsmens</div>\
                            <div class="lcw-td">R</div>\
                            <div class="lcw-td">B</div>\
                            <div class="lcw-td">4S</div>\
                            <div class="lcw-td">6S</div>\
                            <div class="lcw-td">SR</div>\
                            </div>\
                            </div>\
                            <div class="lcw-tbody">';
                            for ( var i = 0; i < match_detail_list.liveMatch.currentBatters.length; i++ ) {

                              if( match_detail_list.liveMatch.currentBatters[i].isFacing == true ){ 

                                highlight = '#00ab4e';
                                star = '*';
                              }else{

                                highlight = '';
                                star ='';
                              }

                              html += '<div class="lcw-tr">\
                              <div class="lcw-td">\
                              <a href="<?php echo home_url() ?>/player-stats/player/'+match_detail_list.liveMatch.currentBatters[i].id+'" target="_blank" style="color: '+highlight+'">\
                              '+match_detail_list.liveMatch.currentBatters[i].name+''+star+'\
                              </a>\
                              </div>\
                              <div class="lcw-td">'+match_detail_list.liveMatch.currentBatters[i].runs+' </div>\
                              <div class="lcw-td">'+match_detail_list.liveMatch.currentBatters[i].ballsFaced+' </div>\
                              <div class="lcw-td">'+match_detail_list.liveMatch.currentBatters[i].fours+' </div>\
                              <div class="lcw-td">'+match_detail_list.liveMatch.currentBatters[i].sixers+' </div>\
                              <div class="lcw-td">'+match_detail_list.liveMatch.currentBatters[i].strikeRate+' </div>\
                              </div>';
                            }
                            html+= '</div>\
                            </div>';

                            html += '<div class="lcw-table lcw-batsmen lcw-bowlers" >\
                            <div class="lcw-thead">\
                            <div class="lcw-tr">\
                            <div class="lcw-td">Bowler</div>\
                            <div class="lcw-td">O</div>\
                            <div class="lcw-td hidden-xs hidden-sm">M</div>\
                            <div class="lcw-td">R</div>\
                            <div class="lcw-td">W</div>\
                            <div class="lcw-td">WD</div>\
                            <div class="lcw-td hidden-xs hidden-sm">NB</div>\
                            <div class="lcw-td">Econ</div>\
                            </div>\
                            </div>\
                            <div class="lcw-tbody">\
                            <div class="lcw-tr">\
                            <div class="lcw-td"><a href="<?php echo home_url() ?>/player-stats/player/'+match_detail_list.liveMatch.currentbowler.id+'">'+match_detail_list.liveMatch.currentbowler.name +'</a></div>\
                            <div class="lcw-td">'+match_detail_list.liveMatch.currentbowler.bowlerOver +'</div>\
                            <div class="lcw-td hidden-xs hidden-sm">'+match_detail_list.liveMatch.currentbowler.maiden +'</div>\
                            <div class="lcw-td">'+match_detail_list.liveMatch.currentbowler.runsAgainst +'</div>\
                            <div class="lcw-td">'+match_detail_list.liveMatch.currentbowler.wickets +'</div>\
                            <div class="lcw-td">'+match_detail_list.liveMatch.currentbowler.wide +'</div>\
                            <div class="lcw-td hidden-xs hidden-sm">'+match_detail_list.liveMatch.currentbowler.noBall +'</div>\
                            <div class="lcw-td">'+match_detail_list.liveMatch.currentbowler.economy +'</div>\
                            </div>\
                            </div>\
                            </div>';
                            html += '<div class="lcw-commentry-box">\
                            <div class="lcw-commentry-filter">\
                            <div class="lcw-commentry-title">Commentry</div>\
                            </div>';
                            for ( var i = 0; i < match_detail_list.liveMatch.commentary.overs.length; i++ ) {
                            html += '<div class="lcw-over-box">\
                             <div class="lcw-endof-over">In over#'+match_detail_list.liveMatch.commentary.overs[i].number +'</div>\
                             <div class="lcw-endof-over-msg"> Runs : '+match_detail_list.liveMatch.commentary.overs[i].overSummary.runsConcededinOver +'  Wickets :  '+match_detail_list.liveMatch.commentary.overs[i].overSummary.wicketsTakeninOver +'  Bowler : '+match_detail_list.liveMatch.commentary.overs[i].overSummary.bowlersName +'</div>\
                             </div>';
                             for ( var j = 0; j < match_detail_list.liveMatch.commentary.overs[i].balls.length; j++ ) {

                                        for ( var k = 0; k < match_detail_list.liveMatch.commentary.overs[i].balls[j].comments.length; k++ ) {

                                          if( match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].id == 1 ){

                                            html += '<div class="lcw-over-info">\
                                            <div class="lcw-each-over">'+match_detail_list.liveMatch.commentary.overs[i].id +'.'+match_detail_list.liveMatch.commentary.overs[i].balls[j].ballNumber +'</div>';
                                                   
                                                 }else{

                                                  html += '<div class="lcw-over-info">\
                                                  <div class="lcw-each-over"><i class="fa fa-commenting-o fa-2x" aria-hidden="true"></i></div>';
                                                }
                                                if( match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].isFallOfWicket ){

                                                  comment_type = 'W';

                                                  color = '#da2625';

                                                }
                                                else if( match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs == 0  ){

                                                  comment_type = '.';

                                                  color = '#3e3e3e';
                                                }
                                                else if( match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs == 4 || match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs == 6 ){

                                                  comment_type = match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs;
                                                  color = '#00ab4e';

                                                }else{
                                                  comment_type = match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs;
                                                  color = '#3e3e3e';
                                                }
                                                if( match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].ballType != 'NonBallComment'){

                                                    html += '<div class="lcw-over-ball type-'+match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs+'" style="background: '+color +'">'+comment_type +'</div>';

                                                  }

                                                    html+= '<div class="lcw-over-info-right">'+match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].text +'</div>\
                                                    </div>';
                                                  
                                                }
                                              }
                                            } 
                                            html += '</div>\
                                            </div>';
                                            $('.live-score-update').html(html);
                                          },
                                          error: function( jqXHR, textStatus, errorThrown ) {
                                            console.log( "Could not get posts, server response: " + textStatus + ": " + errorThrown );

                                          }
                                        });
            setTimeout(function(){ 
              lcw_update_live_score(); 
            }, 20000);                   
          }

          lcw_update_live_score();

    function lcw_update_live_score_scores(){
        <?php  
          if( get_query_var( 'series' ) ){

              $series_id = get_query_var( 'series' );

            }else{

              $series_id = $_GET['series-id'];
            }
            if( get_query_var( 'series' ) ){

              $match_id = get_query_var( 'match' );

            }else{

              $match_id = $_GET['match-id'];
            }
            if( get_query_var( 'status' ) ){

              $series_status = get_query_var( 'status' );

            }else{

              $series_status = $_GET['status'];
            } 
        ?>
        jQuery.ajax({

        type: "POST",
        url: "<?php echo admin_url('admin-ajax.php')?>",
        dataType: "json",
        data: "action=lcw_update_live_score&series_id=<?php echo $series_id  ?>&match_id=<?php echo $match_id ?>",
        success: function( match_detail_list ) {
                    html= '';
                    comment_type = '';
                    if (typeof match_detail_list.liveMatch.matchDetail.scores.homeScore === 'undefined') {

                      homeScore = '';
                      
                    }else{


                      homeScore = match_detail_list.liveMatch.matchDetail.scores.homeScore+' ( '+match_detail_list.liveMatch.matchDetail.scores.homeOvers+' overs )';
                    }
                    if (typeof match_detail_list.liveMatch.matchDetail.scores.awayScore === 'undefined') {

                      awayScore = '';
                      
                    }else{

                      awayScore = match_detail_list.liveMatch.matchDetail.scores.awayScore+' ( '+match_detail_list.liveMatch.matchDetail.scores.awayOvers+' overs )';

                    }

                    html += '<div class="lcw-match-info">\
                    <div class="row">\
                    <div class="col-md-8">\
                    <div class="lcw-home-team" style="color: '+match_detail_list.liveMatch.matchDetail.homeTeam.teamColour+'">\
                    '+match_detail_list.liveMatch.matchDetail.homeTeam.name+'\
                    <span>'+homeScore+'</span>\
                    </div>\
                    <div class="lcw-away-team" style="color: '+match_detail_list.liveMatch.matchDetail.awayTeam.teamColour+'">'+match_detail_list.liveMatch.matchDetail.awayTeam.name+'\
                    <span>'+awayScore+'</span>\
                    </div>\
                    <div class="lcw-match-msg">Toss - '+match_detail_list.liveMatch.matchDetail.tossMessage+'</div>\
                    <div class="lcw-match-msg">'+match_detail_list.liveMatch.matchDetail.matchSummaryText+'</div>\
                    </div>\
                    <div class="col-md-4">\
                    <div class="lcw-short-state">\
                    Required Run Rate : '+match_detail_list.liveMatch.meta.requiredRunRate+'\
                    </div>\
                    <div class="lcw-short-state">\
                    Current Run Rate : '+match_detail_list.liveMatch.meta.currentRunRate +'\
                    </div>\
                    </div>\
                    </div>\
                    </div>';

                    html += '</div>\
                    </div>';
                    $('.live-score-update-score').html(html);
                  },
                  error: function( jqXHR, textStatus, errorThrown ) {
                    console.log( "Could not get posts, server response: " + textStatus + ": " + errorThrown );

                  }
                });
                setTimeout(function(){ 
                  lcw_update_live_score_scores(); 
                }, 20000);                   
              }

              lcw_update_live_score_scores();

              </script>
              <?php
              $js = ob_get_clean();
              echo $js;
            }
            ?>
            <script type="text/javascript">
              function lcw_update_live_score_shortcode(series_id,match_id,e){
              $ = jQuery;
               e.preventDefault();
              jQuery.ajax({

                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php')?>",
                dataType: "json",
                
            data: "action=lcw_update_live_score&series_id="+series_id+"&match_id="+match_id,
                success: function( match_detail_list ) {
                          
                          html= '';
                          comment_type = '';
                          if (typeof match_detail_list.liveMatch.matchDetail.scores.homeScore === 'undefined') {

                            homeScore = '';
                            
                          }else{


                            homeScore = match_detail_list.liveMatch.matchDetail.scores.homeScore+' ( '+match_detail_list.liveMatch.matchDetail.scores.homeOvers+' overs )';
                          }
                          if (typeof match_detail_list.liveMatch.matchDetail.scores.awayScore === 'undefined') {

                            awayScore = '';
                            
                          }else{

                            awayScore = match_detail_list.liveMatch.matchDetail.scores.awayScore+' ( '+match_detail_list.liveMatch.matchDetail.scores.awayOvers+' overs )';

                          }
                            
                            html += '<div class="lcw-match-info">\
                            <div class="row">\
                            <div class="col-md-8">\
                            <div class="lcw-home-team" style="color: '+match_detail_list.liveMatch.matchDetail.homeTeam.teamColour+'">\
                            '+match_detail_list.liveMatch.matchDetail.homeTeam.name+'\
                            <span>'+homeScore+'</span>\
                            </div>\
                            <div class="lcw-away-team" style="color: '+match_detail_list.liveMatch.matchDetail.awayTeam.teamColour+'">'+match_detail_list.liveMatch.matchDetail.awayTeam.name+'\
                            <span>'+awayScore+'</span>\
                            </div>\
                            <div class="lcw-match-msg">Toss - '+match_detail_list.liveMatch.matchDetail.tossMessage+'</div>\
                            <div class="lcw-match-msg">'+match_detail_list.liveMatch.matchDetail.matchSummaryText+'</div>\
                            </div>\
                            <div class="col-md-4">\
                            <div class="lcw-short-state">\
                            RR : '+match_detail_list.liveMatch.meta.requiredRunRate+'\
                            </div>\
                            <div class="lcw-short-state">\
                            CRR : '+match_detail_list.liveMatch.meta.currentRunRate +'\
                            </div>\
                            </div>\
                            </div>\
                            </div>';
                            html += '<div class="lcw-table lcw-batsmen" >\
                            <div class="lcw-thead">\
                            <div class="lcw-tr">\
                            <div class="lcw-td">Batsmens</div>\
                            <div class="lcw-td">R</div>\
                            <div class="lcw-td">B</div>\
                            <div class="lcw-td">4S</div>\
                            <div class="lcw-td">6S</div>\
                            <div class="lcw-td">SR</div>\
                            </div>\
                            </div>\
                            <div class="lcw-tbody">';
                            for ( var i = 0; i < match_detail_list.liveMatch.currentBatters.length; i++ ) {

                              if( match_detail_list.liveMatch.currentBatters[i].isFacing == true ){ 

                                highlight = '#00ab4e';
                                star = '*';
                              }else{

                                highlight = '';
                                star ='';
                              }

                              html += '<div class="lcw-tr">\
                              <div class="lcw-td">\
                              <a href="<?php echo home_url() ?>/player-stats/player/'+match_detail_list.liveMatch.currentBatters[i].id+'" target="_blank" style="color: '+highlight+'">\
                              '+match_detail_list.liveMatch.currentBatters[i].name+''+star+'\
                              </a>\
                              </div>\
                              <div class="lcw-td">'+match_detail_list.liveMatch.currentBatters[i].runs+' </div>\
                              <div class="lcw-td">'+match_detail_list.liveMatch.currentBatters[i].ballsFaced+' </div>\
                              <div class="lcw-td">'+match_detail_list.liveMatch.currentBatters[i].fours+' </div>\
                              <div class="lcw-td">'+match_detail_list.liveMatch.currentBatters[i].sixers+' </div>\
                              <div class="lcw-td">'+match_detail_list.liveMatch.currentBatters[i].strikeRate+' </div>\
                              </div>';
                            }
                            html+= '</div>\
                            </div>';

                            html += '<div class="lcw-table lcw-batsmen lcw-bowlers" >\
                            <div class="lcw-thead">\
                            <div class="lcw-tr">\
                            <div class="lcw-td">Bowler</div>\
                            <div class="lcw-td">O</div>\
                            <div class="lcw-td hidden-xs hidden-sm">M</div>\
                            <div class="lcw-td">R</div>\
                            <div class="lcw-td">W</div>\
                            <div class="lcw-td">WD</div>\
                            <div class="lcw-td hidden-xs hidden-sm">NB</div>\
                            <div class="lcw-td">Econ</div>\
                            </div>\
                            </div>\
                            <div class="lcw-tbody">\
                            <div class="lcw-tr">\
                            <div class="lcw-td"><a href="<?php echo home_url() ?>/player-stats/player/'+match_detail_list.liveMatch.currentbowler.id+'">'+match_detail_list.liveMatch.currentbowler.name +'</a></div>\
                            <div class="lcw-td">'+match_detail_list.liveMatch.currentbowler.bowlerOver +'</div>\
                            <div class="lcw-td hidden-xs hidden-sm">'+match_detail_list.liveMatch.currentbowler.maiden +'</div>\
                            <div class="lcw-td">'+match_detail_list.liveMatch.currentbowler.runsAgainst +'</div>\
                            <div class="lcw-td">'+match_detail_list.liveMatch.currentbowler.wickets +'</div>\
                            <div class="lcw-td">'+match_detail_list.liveMatch.currentbowler.wide +'</div>\
                            <div class="lcw-td hidden-xs hidden-sm">'+match_detail_list.liveMatch.currentbowler.noBall +'</div>\
                            <div class="lcw-td">'+match_detail_list.liveMatch.currentbowler.economy +'</div>\
                            </div>\
                            </div>\
                            </div>';
                            html += '<div class="lcw-commentry-box">\
                            <div class="lcw-commentry-filter">\
                            <div class="lcw-commentry-title">Commentry</div>\
                            </div>';
                            for ( var i = 0; i < match_detail_list.liveMatch.commentary.overs.length; i++ ) {
                             html += '<div class="lcw-over-box">\
                             <div class="lcw-endof-over">In this over</div>\
                             <div class="lcw-endof-over-msg">Over '+match_detail_list.liveMatch.commentary.overs[i].number +' '+match_detail_list.liveMatch.commentary.overs[i].overSummary.runsConcededinOver +' . Wicket '+match_detail_list.liveMatch.commentary.overs[i].overSummary.runsConcededinOver +' runs   '+match_detail_list.liveMatch.commentary.overs[i].overSummary.wicketsTakeninOver +' WICKETS Bowler: '+match_detail_list.liveMatch.commentary.overs[i].overSummary.bowlersName +'</div>\
                             </div>';
                             for ( var j = 0; j < match_detail_list.liveMatch.commentary.overs[i].balls.length; j++ ) {

                                        for ( var k = 0; k < match_detail_list.liveMatch.commentary.overs[i].balls[j].comments.length; k++ ) {

                                          if( match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].id == 1 ){

                                            html += '<div class="lcw-over-info">\
                                            <div class="lcw-each-over">'+match_detail_list.liveMatch.commentary.overs[i].id +'.'+match_detail_list.liveMatch.commentary.overs[i].balls[j].ballNumber +'</div>';
                                                   
                                                 }else{

                                                  html += '<div class="lcw-over-info">\
                                                  <div class="lcw-each-over"><i class="fa fa-commenting-o fa-2x" aria-hidden="true"></i></div>';
                                                }
                                                if( match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].isFallOfWicket ){

                                                  comment_type = 'W';

                                                  color = '#da2625';

                                                }
                                                else if( match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs == 0  ){

                                                  comment_type = '.';

                                                  color = '#3e3e3e';
                                                }
                                                else if( match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs == 4 || match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs == 6 ){

                                                  comment_type = match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs;
                                                  color = '#00ab4e';

                                                }else{
                                                  comment_type = match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs;
                                                  color = '#3e3e3e';
                                                }
                                                if( match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].ballType != 'NonBallComment'){

                                                    html += '<div class="lcw-over-ball type-'+match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].runs+'" style="background: '+color +'">'+comment_type +'</div>';

                                                  }

                                                    html+= '<div class="lcw-over-info-right">'+match_detail_list.liveMatch.commentary.overs[i].balls[j].comments[k].text +'</div>\
                                                    </div>';
                                                  
                                                }
                                              }
                                            } 
                                            html += '</div>\
                                            </div>';
                                            $('.live-score-update').html(html);
                                            $('.refresh-score').hide('slow');
                                          },
                                          error: function( jqXHR, textStatus, errorThrown ) {
                                            console.log( "Could not get posts, server response: " + textStatus + ": " + errorThrown );

                                          }
                                        });
            setTimeout(function(){ 
              lcw_update_live_score_shortcode(series_id,match_id,e); 
            }, 20000);                   
          }
            </script>
            <?php
          }
          public function lcw_score_series_shortcode(  $atts  ){
            extract(shortcode_atts(
              array(

                'col' => ''

              ), $atts));
            $col_per_row = get_option( 'col_per_row' );
            if( empty( $col ) ){

              $col = $col_per_row;

            }
            $series_content     = '';
            $series_content     = $this->lcw_get_content('series');
            $contents           = utf8_encode($series_content); 
            $series             = json_decode($contents);
            $series_list = '';
            if(!empty($series)){

                $series_list    = $series->seriesList->series;
                $style = get_option( 'series-layout' );
                if(empty( $style )){

                  $series_style = 1;

                } else{

                  $series_style = $style;
                }
                  require_once LCW_LIVE_SCORE_ROOT_PATH . '/templates/series-list-'.$series_style.'.php';
                
                if( empty( $style ) || $style == 1 ){

                     $series_list = lcw_load_series_list_1( $series_list, $col );

                }elseif( $style == 2 ){

                     $series_list = lcw_load_series_list_2( $series_list, $col );
                  }
                 
            }else{

                $series_list = '';
            }

            return $series_list;
          }
          public function lcw_score_series_matches_shortcode( $atts ){
            ob_start();
            extract(shortcode_atts(
              array(

                'series_id'       => -1,
                'series_status'   => 'current'

              ), $atts));

                $series_id      =  isset( $_GET['series-id'] ) ? $_GET['series-id'] : $series_id;
                $status         =  isset( $_GET['status'] ) ? $_GET['status'] : $series_status;
              if( $series_id == '2514'){

                  $content = "This series does belong to lite version";
                
              }else{

                if( get_query_var( 'series' ) ){

                  $series_id = get_query_var( 'series' );

                }else{

                  $series_id = $series_id;
                }
                if( get_query_var( 'status' ) ){

                  $series_status = get_query_var( 'status' );

                }else{

                  $series_status = $series_status;
                }

                $active_tab_completed = '';

                $active_tab_current   = '';


                if( $series_status == 'completed' ){

                  $active_tab_completed = 'active';

                }elseif( $series_status == 'current' ){

                  $active_tab_current = 'active';

                }elseif( $series_status == 'standings' ){

                  $active_tab_standings = 'active';

                }elseif( $series_status == 'stats' ){

                  $active_tab_stats = 'active';

                }else{

                  $active_tab_current = 'active';
                }

                $match_text           = apply_filters('lcw_fixture_text', 'Fixtures'); 
                $completed_tab_text   = apply_filters('lcw_completed_tab_text', 'Completed'); 
                $upcomming_tab_text   = apply_filters('lcw_upcomming_tab_text', 'Live & Upcomming');
                $standing_tab_text    = apply_filters('lcw_standing_tab_text', 'Standings');
                $stats_tab_text       = apply_filters('lcw_stats_tab_text', 'STATS'); 
                $standings_content    = $this->lcw_get_content('series/standings/'.$series_id );
                $standings_contents   = utf8_encode( $standings_content ); 
                $standings_list       = json_decode( $standings_contents);
                 $stats_content    = $this->lcw_get_content('players/byseries/'.$series_id );
                $stats_contents   = utf8_encode( $stats_content ); 
                $stats_list       = json_decode( $stats_contents);
                ?>
                <h3><?php echo $match_text; ?></h3>
                <div class="lcw-fixtures-outer">
                  <div class="lcw-tabs"> 
                    <a class="<?php echo $active_tab_current ?>" href="<?php echo home_url('matches/series/'.$series_id.'/status/current') ?>">

                      <?php echo $upcomming_tab_text; ?>
                    </a> 
                    <a class="<?php echo $active_tab_completed ?>" href="<?php echo home_url('matches/series/'.$series_id.'/status/completed') ?>">

                      <?php echo $completed_tab_text; ?>
                    </a> 
                    <a class="<?php echo $active_tab_stats ?>" href="<?php echo home_url('matches/series/'.$series_id.'/status/stats') ?>">

                      <?php echo $stats_tab_text; ?>
                    </a> 
                    <?php
                    if( $standings_list->metaData->hasPoint == 1 ){ ?>
                    <a class="<?php echo $active_tab_standings ?>" href="<?php echo home_url('matches/series/'.$series_id.'/status/standings') ?>">

                      <?php echo $standing_tab_text; ?>
                    </a> 
                    <?php } ?>
                  </div>
            <?php

              if( $series_status == 'completed' ) {

                $complete_content        = $this->lcw_get_content('matches/'.$series_id.'?inProgressLimit=0&upcomingLimit=0');
                $complete_contents       = utf8_encode( $complete_content ); 
                $complete_matches_list   = json_decode( $complete_contents);
                                  
                if( empty( $complete_content ) ){

                  return false;
                }
                $matches        = $complete_matches_list->matchList->matches;
                require_once LCW_LIVE_SCORE_ROOT_PATH . '/templates/completed-matches.php';
                $content        = lcw_load_complete_matches( $matches );

              }elseif( $series_status == 'standings' ){
                if( empty( $standings_content ) ){

                  return false;
                }
                require_once LCW_LIVE_SCORE_ROOT_PATH . '/templates/standings.php';
                $content = lcw_load_standings( $standings_list );
              }elseif( $series_status == 'stats' ){
                
                require_once LCW_LIVE_SCORE_ROOT_PATH . '/templates/series-stats.php';
                $content = lcw_load_stats( $stats_list );
              }else{

                $content        = $this->lcw_get_content('matches/'.$series_id.'?completedLimit=0');
                $contents       = utf8_encode($content); 
                $matches_list   = json_decode($contents);
                if( empty( $content ) ){

                  return false;
                }
                $matches        = $matches_list->matchList->matches;
                require_once LCW_LIVE_SCORE_ROOT_PATH . '/templates/upcomming-matches.php';
                $content = lcw_load_upcomming_matches( $matches );
              }
            }
              return $content;
          }
          public function lcw_match_detail_shortcode( $atts ){
            extract(shortcode_atts(
              array(

                'series_id'       => -1,
                'match_id'        => -1,
                'status'          => '',
                'show_sections'   => 'show_score_card,show_teams,show_commentary',
              ), $atts));
            $section_array  =  explode(',', $show_sections );
            $series_id      =  isset( $_GET['series-id'] ) ? $_GET['series-id'] : $series_id;
            $match_id       =  isset( $_GET['match-id'] )  ? $_GET['match-id'] : $match_id;
            $status         =  isset( $_GET['status'] )    ? $_GET['status'] : $status;
            
            if( get_query_var( 'series' ) ){

              $series_id = get_query_var( 'series' );

            }else{

              $series_id = $series_id;
            }
            if( get_query_var( 'match' ) ){

              $match_id = get_query_var( 'match' );

            }else{

              $match_id = $match_id;
            }
            if( get_query_var( 'status' ) ){

              $status = get_query_var( 'status' );

            }else{

              $status = $status;
            }
            
            if ( $status == 'live') {

              $match_detail_content    =  $this->lcw_get_content('matches/'.$series_id.'/'.$match_id.'/live?OverLimit=5');
              $match_detail_contents   =  utf8_encode( $match_detail_content ); 
              $match_detail_list       =  json_decode($match_detail_contents);
              $match_player_content    =  $this->lcw_get_content('players/bymatch/'.$series_id.'/'.$match_id);
              $match_player_contents   =  utf8_encode( $match_player_content  ); 
              $match_player_list       =  json_decode( $match_player_contents );
              $graph_content           =  $this->lcw_get_content('graph/'.$series_id.'/'.$match_id);
              $graph_contents          =  utf8_encode( $graph_content  ); 
              $graph_list              =  json_decode( $graph_contents );
              
              if( empty($match_detail_list ) ){

                return false;
              }
              require_once LCW_LIVE_SCORE_ROOT_PATH . '/templates/match-detail-live.php';

              $content = lcw_load_match_detail_live( $this,$match_detail_list,$match_player_list,$graph_list,$series_id,$match_id,$section_array   );

            }
            elseif ( $status == 'upcoming' ) {

              $match_detail_content    =  $this->lcw_get_content('matches/'.$series_id.'/'.$match_id);
              $match_detail_contents   =  utf8_encode( $match_detail_content ); 
              $match_detail_list       =  json_decode($match_detail_contents);

              if( empty($match_detail_list ) ){

                return false;
              }
              require_once LCW_LIVE_SCORE_ROOT_PATH . '/templates/match-detail-upcoming.php';

              $content = lcw_load_match_detail_upcoming( $this,$match_detail_list );

            }else{

              $match_detail_content    =  $this->lcw_get_content('matches/'.$series_id.'/'.$match_id.'/live?OverLimit=5');
              $match_detail_contents   =  utf8_encode( $match_detail_content ); 
              $match_detail_list       =  json_decode($match_detail_contents);
              $match_player_content    =  $this->lcw_get_content('players/bymatch/'.$series_id.'/'.$match_id);
              $match_player_contents   =  utf8_encode( $match_player_content  ); 
              $match_player_list       =  json_decode( $match_player_contents );
              $graph_content           =  $this->lcw_get_content('graph/'.$series_id.'/'.$match_id);
              $graph_contents          =  utf8_encode( $graph_content  ); 
              $graph_list              =  json_decode( $graph_contents );
              if( empty($match_detail_list ) ){

                return false;
              }
              require_once LCW_LIVE_SCORE_ROOT_PATH . '/templates/match-detail.php';
              $content = lcw_load_match_detail( $this,$match_detail_list,$match_player_list,$graph_list,$series_id,$match_id,$section_array  );
            }

            return $content;
          }
          public function lcw_player_stats_shortcode( $atts ){
           
            extract(shortcode_atts(
              array(

                'player_id'       => -1,

              ), $atts));
            $player_id               =  isset( $_GET['player-id'] ) ? $_GET['player-id'] : $player_id;
            if( get_query_var( 'player' ) ){

              $player_id = get_query_var( 'player' );

            }else{

              $player_id = $player_id;
            }
            $player_stats_content    =  $this->lcw_get_content('players/'.$player_id.'/stats');
            $player_stats_contents   =  utf8_encode( $player_stats_content ); 
            $player_stats_list       =  json_decode( $player_stats_contents);

            if( empty($player_stats_list ) ){

              return false;
            }
            require_once LCW_LIVE_SCORE_ROOT_PATH . '/templates/player-stats.php';

            $content = lcw_load_player_stats( $this,$player_stats_list);

            return $content;
          }
          public function lcw_score_front_styles( ){

            $this->enqueue_style( 'wss-live-score-bootstrap-style',plugins_url( 'lib/bootstrap3/css/bootstrap.css', dirname(__FILE__) ),false, LCW_LIVE_SCORE_VERSION);
            $this->enqueue_style( 'wss-live-score-datatable-style','//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css',false, LCW_LIVE_SCORE_VERSION);
            $this->enqueue_style( 'wss-live-score-main-style', plugins_url( 'css/style.css', dirname(__FILE__) ),false, LCW_LIVE_SCORE_VERSION);
            $this->enqueue_style( 'wss-live-score-media-query-style', plugins_url( 'css/media-query.css', dirname(__FILE__) ),false, LCW_LIVE_SCORE_VERSION);
            
          }
        /**
         * Localize script to register ajax url
         *
         * @access public
         * @param void
         * @return void
         * @since 1.0.0
        */
        public function lcw_score_front_reg_scripts( ){
          $ajax_array = array(

            'ajaxurl' => admin_url('admin-ajax.php')
          );
          $this->register_script ( 'lcw-score-front-js', plugins_url('js/script.js', dirname(__FILE__)), false, LCW_LIVE_SCORE_VERSION );

          wp_localize_script ( 'lcw-score-front-js','ajax_params', $ajax_array);
        }
        public function lcw_score_front_scripts( ){

          wp_enqueue_script('jquery');
          
          $this->enqueue_script ( 'lcw-score-dataTables-js', '//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js', false, LCW_LIVE_SCORE_VERSION);
          $this->enqueue_script ( 'lcw-score-charts-js', 'https://www.gstatic.com/charts/loader.js', array(), LCW_LIVE_SCORE_VERSION,false);
          $this->enqueue_script('lcw-bootstrap-js',plugins_url( 'lib/bootstrap3/js/bootstrap.min.js', dirname(__FILE__)), false, LCW_LIVE_SCORE_VERSION);
          $this->add_existed_script ( 'lcw-score-front-js' );
        }
        public function lcw_settings_tabs( $current = 'series' ) {

          $tabs = array(  

            'series'        =>  'General Settings', 

          );

          echo '<div id="icon-themes" class="icon32"></div>';
          echo '<div class="left-area">';
          echo '<h2 class="nav-tab-wrapper">';

          foreach( $tabs as $tab => $name ){

            $class = ( $tab == $current ) ? ' nav-tab-active' : '';
            echo "<a class='nav-tab$class' href='?page=lcw-score-settings&tab=$tab'>$name</a>";
          }

          echo '</h2>';
        }
        /**
         * Add Scripts in admin Side
         *
         * @access public
         * @param void
         * @return void
         * @since 1.0.0
        */
        public function lcw_score_admin_scripts(  ) {

          wp_enqueue_script ( 'lcw-score-admin-js', plugins_url('js/lcw-score-admin.js', dirname(__FILE__)), array( 'wp-color-picker' ), LCW_LIVE_SCORE_VERSION);
        }
        /**
         * Add stylesheets in admin Side
         *
         * @access public
         * @param void
         * @return void
         * @since 1.0.0
        */
        public function lcw_score_admin_styles( ) {
          wp_enqueue_style( 'wp-color-picker' );
        }
        /**
         * Add rewrite tags and rules
         */
        public function lcw_rewrite_rules() {
          add_rewrite_rule(
              'matches/series/([0-9]{1,})/status/([^/]*)',
              'index.php?pagename=matches&series=$matches[1]&status=$matches[2]',
              'top'
          );
          add_rewrite_rule(
              'match-detail/series/([0-9]{1,})/match/([0-9]{1,})/status/([^/]*)',
              'index.php?pagename=match-detail&series=$matches[1]&match=$matches[2]&status=$matches[3]',
              'top'
          );
          add_rewrite_rule(
              'player-stats/player/([0-9]{1,})',
              'index.php?pagename=player-stats&player=$matches[1]',
              'top'
          );

          add_rewrite_tag( '%player%', '([^&]+)' );  
          add_rewrite_tag( '%series%', '([^&]+)' );
          add_rewrite_tag( '%match%', '([^&]+)' );
          add_rewrite_tag( '%status%', '([^&]+)' );
        }
        /**
         * Save options after activate plugin
         *
         * @access public
         * @param void
         * @return void
         * @since 1.0.0
        */
        public static function lcw_score_install_plugin() {
          $series = array(
            'post_title'    => wp_strip_all_tags( 'Series' ),
            'post_content'  => '[series]',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
          );
          $series_match = array(
            'post_title'    => wp_strip_all_tags( 'Matches' ),
            'post_content'  => '[series-matches]',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
          );
          $match_detail = array(
            'post_title'    => wp_strip_all_tags( 'Match Detail ' ),
            'post_content'  => '[match-detail]',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
          );
          $player_stats = array(
            'post_title'    => wp_strip_all_tags( 'Player Stats ' ),
            'post_content'  => '[player-stats]',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page',
          );
          if( !post_exists ( wp_strip_all_tags( 'Series' ) ) ){
            wp_insert_post($series);
          }
          if( !post_exists ( wp_strip_all_tags( 'Matches' ) ) ){
            wp_insert_post($series_match);
          }
          if( !post_exists ( wp_strip_all_tags( 'Match Detail' ) ) ){

            wp_insert_post($match_detail);
          }
          if( !post_exists ( wp_strip_all_tags( 'Player Stats' ) ) ){

            wp_insert_post( $player_stats );
          }
          update_option( 'col_per_row',  4 );
          update_option('base_color','#ed3636');
          //flush_rewrite_rules();
        }
        /**
         * Delete options after activate plugin
         *
         * @access public
         * @param void
         * @return void
         * @since 1.0.0
        */ 
        public static function lcw_score_uninstall_plugin() {


        }
      }
}