<div id="babc_main_div" style="visibility:visible; margin-right:10%;  margin-left: 10px; margin-top: 20px;">
        <div style="color: #6E7274; text-align:left;">
            <h2 style="margin-left: 10px; color: #6E7274; font-size: 35px; font-weight: 600; text-align:<?php echo (get_user_locale() == "he_IL") ? "right" : "left" ?>;"><?php echo esc_html_e( 'Browser Address Bar Color', 'browser-address-bar-color' ) ?>
            </h2>
        </div>
        
        <form style="" target="_self"  method="POST">    
           <input type="hidden" name="color-posted">    
            <br>
            
            <div id="babc_pages_div">
              <div style="background-color: #fff; padding: 4px; direction: ltr; margin-top: 5px; height:40px;">
                <div style="float: left;">
                    <input type="checkbox" id="cbox_all" name="check-color-all" style=" "<?php echo isset(get_option('babc_pages_list')["all"]) ? "checked" : null; ?>>
                    <p style="color: #3e4347; display: inline; font-size:20px; font-weight: 600;"><?php echo esc_html_e( 'All pages and posts ', 'browser-address-bar-color' ) ?> </p>
                </div> 
               <input type="color" id="input_all" name="input-color-all" style="width:50%; padding: 2px 8px; height:35px; float: right; box-shadow: 0px 0px 13px rgba(0, 0, 0, 0.1);"
               <?php
                $babc_curr_pages_arr = get_option('babc_pages_list');
                if(is_array($babc_curr_pages_arr)){
                    if(array_key_exists("all" , $babc_curr_pages_arr)){
                        $curr_checked = "checked";
                        $curr_color = $babc_curr_pages_arr["all"];
                    }else{
                        $curr_checked = " ";
                        $curr_color = "#E3E3E3" ; //default color;
                     }
                }else{
                    $curr_checked = " ";
                    $curr_color = " ";
                }
                echo 'value="'.$curr_color.'"';
                ?>>
               <input type="text" id="txt-cover-inp-all" style="width:50%; padding: 2px 8px; height:35px; float: right; display:none;" value="Not Selected" disabled>
              </div>
    
              <?php 
                if(get_pages()[0]->ID){
                    echo '<hr style="width: 80%; font-weight: 600; color: #000; border-width: 2px;">';
                    echo'<div style=" background-color:#fff; margin-top: 10px; padding: 5px; height: 330px; overflow: scroll;">
                    <div style="height: 35px;"><h3 style="float: left;
                    margin: 0;">Pages:</h3>';
                    echo '<input type="text" id="search_page_inp" style="float: right; margin-right: 10px; width: 100px; min-height:20px; height: 30px;" placeholder="Search page.."> </div>';
                }

                $babcAllPages = get_pages(); 
                $babc_curr_pages_arr = get_option('babc_pages_list');

                //find and attach slug the pages that have the same name:
                $babac_names_array = array();
                foreach ($babcAllPages as $page ) {
                    array_push($babac_names_array, $page->post_title);
                }
                $babc_times = array_count_values($babac_names_array);
                foreach ($babcAllPages as $page ) {
                    if($babc_times[$page->post_title] > 1){
                        $page->need_post_name = $page->post_name;
                    }
                }
                
                foreach ($babcAllPages as $page ) {
                    if(is_array($babc_curr_pages_arr)){
                        if(array_key_exists($page->ID , $babc_curr_pages_arr)){
                            $curr_checked = "checked";
                            $curr_color = $babc_curr_pages_arr[$page->ID];
                        }else{
                            $curr_checked = " ";
                            $curr_color = "#E3E3E3" ; //default color;
                         }
                    }else{
                        $curr_checked = " ";
                        $curr_color = " ";
                    }
 
                    $babc_option = '<div class="item_ge" style="background-color: #F7FCFE; padding: 4px; padding-bottom: 0px; direction: ltr; margin-top: 5px; height:40px;" data-sename="';
                    $babc_option .= $page->post_title;
                    $babc_option .= '">';
                    
                        $babc_option .= '<div style="float: left;">';
                            $babc_option .= ' <input type="checkbox" id="" class="check-inputs" name="check-color-';
                            $babc_option .= $page->ID;
                            $babc_option .= '" style="display:inline-block;" ';
                            $babc_option .= $curr_checked;
                            $babc_option .= '>';
                            $babc_option .= '<input type="checkbox" class="check-cover-input" style="display: none;" checked disabled>';
                            $babc_option .= '<p style="font-size: 20px; color: #0970be; display: inline; font-weight: 600;">';
                            $babc_option .= $page->post_title;
                            $babc_option .= '</p>';
                            if($page->need_post_name){
                                $babc_option .= '<p style="font-style: italic; font-size: 14px; display: inline;"> ';
                                $babc_option .= "&nbsp;";
                                $babc_option .= $page->need_post_name;
                                $babc_option .= '</p>';
                            }
                        $babc_option .= '</div>';
                        $babc_option .= '<input type="color" class="color-inputs" name="input-color-';
                        $babc_option .= $page->ID;
                        $babc_option .= '" style="width:50%; padding: 2px 8px; height:35px; float: right; box-shadow: 0px 0px 13px rgba(0, 0, 0, 0.1);" value="';
                        $babc_option .= $curr_color;
                        $babc_option .= '">';
                        $babc_option .= '<input type="text" class="txt-cover-input" style="display: none; width:50%; padding: 2px 8px; height:35px; float: right;" value="Not Selected" disabled> ';
                    

                    $babc_option .= '</div>';
                    echo $babc_option;
                }
                echo '</div>';
                ?>
               
                <?php
                if(get_posts() && get_posts()[0]->ID){
                    echo '<hr style="width: 80%; font-weight: 600; color: #000; border-width: 2px;">';
                    echo'<div style=" background-color:#fff; margin-top: 10px; padding: 5px; height: 330px; overflow: scroll;">
                    <div style="height: 35px;"><h3 style="float: left;
                    margin: 0;">Posts:</h3>';
                    echo '<input type="text" id="search_post_inp" style="float: right; margin-right: 10px; width: 100px; min-height:20px; height: 30px;" placeholder="Search post.."> </div>';
                }
                
                $babcAllPosts = get_posts(); 
                $babc_curr_pages_arr = get_option('babc_pages_list');

                //find and attach slug the posts that have the same name:
                $babac_names_array = array();
                foreach ($babcAllPosts as $post ) {
                    array_push($babac_names_array, $post->post_title);
                }
                $babc_times = array_count_values($babac_names_array);
                foreach ($babcAllPosts as $post ) {
                    if($babc_times[$post->post_title] > 1){
                        $post->need_post_name = $post->post_name;
                    }
                }
                
                foreach ($babcAllPosts as $post) {
                    if(is_array($babc_curr_pages_arr)){
                        if(array_key_exists($post->ID , $babc_curr_pages_arr)){
                            $curr_checked = "checked";
                            $curr_color = $babc_curr_pages_arr[$post->ID];
                        }else{
                        $curr_checked = "";
                        $curr_color = "#E3E3E3" ; //default color;
                        }
                    }else{
                        $curr_checked = " ";
                        $curr_color = " ";
                    }
 
                    $babc_option = '<div class="item_st" style="background-color: #F7FCFE; padding: 4px; padding-bottom: 0px; direction: ltr; margin-top: 5px; height:40px;" data-sename="';
                    $babc_option .= $post->post_title;
                    $babc_option .= '">';
                    
                        $babc_option .= '<div style="float: left;">';
                            $babc_option .= ' <input type="checkbox" id="" class="check-inputs" name="check-color-';
                            $babc_option .= $post->ID;
                            $babc_option .= '" style="display:inline-block;" ';
                            $babc_option .= $curr_checked;
                            $babc_option .= '>';
                            $babc_option .= '<input type="checkbox" class="check-cover-input" style="display: none;" checked disabled>';
                            $babc_option .= '<p style="font-size: 20px; color: #0970be; display: inline; font-weight: 600;">';
                            $babc_option .= $post->post_title;
                            $babc_option .= '</p>';
                            if($post->need_post_name){
                                $babc_option .= '<p style="font-style: italic; font-size: 14px; display: inline;"> ';
                                $babc_option .= "&nbsp;";
                                $babc_option .= $post->need_post_name;
                                $babc_option .= '</p>';
                            }
                        $babc_option .= '</div>';
                
                        $babc_option .= '<input type="color" class="color-inputs" name="input-color-';
                        $babc_option .= $post->ID;
                        $babc_option .= '" style="width:50%; padding: 2px 8px; height:35px; float: right; box-shadow: 0px 0px 13px rgba(0, 0, 0, 0.1);" value="';
                        $babc_option .= $curr_color;
                        $babc_option .= '">';
                        $babc_option .= '<input type="text" class="txt-cover-input" style="display: none; width:50%; padding: 2px 8px; height:35px; float: right;" value="Not Selected" disabled> ';
                    

                    $babc_option .= '</div>';
                    echo $babc_option;
                }

                ?>
            </div>
            
            <br>
            <?php echo submit_button() ?> 

        </form>

    </div>