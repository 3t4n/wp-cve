<?php
if (!defined('ABSPATH'))
{
	exit;
}


function showTooltipsInContactForm7($content)
{
    global $table_prefix,$wpdb,$post;
    
    do_action('action_before_showtooltips', $content);
    remove_filter('the_title', 'wptexturize');
    $content = apply_filters( 'filter_before_showtooltips',  $content);
    
    
    //7.5.7
    $tooltips_pro_disable_tooltip_in_mobile_free = '';
    $tooltips_pro_disable_tooltip_in_mobile_free = tooltips_pro_disable_tooltip_in_mobile_free();
    
    //6.9.3
    //if (tooltips_pro_disable_tooltip_in_mobile_free())
    if ($tooltips_pro_disable_tooltip_in_mobile_free)
    {
        return $content;
    }
    //end 6.9.3
    
    $curent_post = get_post($post);
    
    if (empty($curent_post->post_content))
    {
        $curent_content = '';
    }
    else
    {
        $curent_content = $curent_post->post_content;
    }
    
    //8.1.3 try to support shortcode in post content
    $curent_content = $content;
    //end 8.1.3
    
    $m_result = tooltips_get_option('tooltipsarray','post_title', 'DESC', 'LENGTH');
    
    $m_keyword_result = '';
    if (!(empty($m_result)))
    {
        $m_keyword_id = 0;
        foreach ($m_result as $m_single)
        {
            $tooltip_post_id = $m_single['post_id'];
            $tooltip_unique_id = $m_single['unique_id'];
            
            $get_post_meta_value_for_this_page = get_post_meta($tooltip_post_id, 'toolstipssynonyms', true);
            $get_post_meta_value_for_this_page = trim($get_post_meta_value_for_this_page);
            
            $tooltsip_synonyms = false;
            if (!(empty($get_post_meta_value_for_this_page)))
            {
                $tooltsip_synonyms = explode('|', $get_post_meta_value_for_this_page);
            }
            
            
            if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
            {
                
            }
            else
            {
                $tooltsip_synonyms = array();
                $tooltsip_synonyms[] = $m_single['keyword'];
                
            }
            
            if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
            {
                $tooltsip_synonyms[] = $m_single['keyword'];
                $tooltsip_synonyms = array_unique($tooltsip_synonyms);
                
                foreach ($tooltsip_synonyms as $tooltsip_synonyms_single)
                {
                    $m_keyword = $tooltsip_synonyms_single;
                    
                    
                    if (stripos($curent_content,$m_keyword) === false)
                    {
                        
                    }
                    else
                    {
                        $m_keyword_result .= '<script type="text/javascript">';
                        $m_content = $m_single['content'];
                        
                        $m_content = do_shortcode($m_content);
                        
                        $m_content = preg_quote($m_content,'/');
                        $m_content = str_ireplace('\\','',$m_content);
                        $m_content = str_ireplace("'","\'",$m_content);
                        $m_content = preg_replace('|\r\n|', '<br/>', $m_content);
                        $m_content = preg_replace('|\r|', '', $m_content);
                        $m_content = preg_replace('|\n|', '<br/>', $m_content);
                        
                        if (!(empty($m_content)))
                        {
                            
                            //!!!start
                            $tooltipsPopupCreditLink =	'';
                            $enabletooltipsPopupCreditLinkInPopupWindow = get_option("enabletooltipsPopupCreditLinkInPopupWindow");
                            
                            
                            if ($enabletooltipsPopupCreditLinkInPopupWindow == 'YES')
                            {
                                $tooltipsPopupCreditLink =	'<div class="tooltipsPopupCreditLink" style="float:left;margin-top:4px;margin-left:2px;"><a href="https://tooltips.org/contact-us" target="_blank">'."Tooltip Support"."</a></div>";
                            }
                            else
                            {
                                $tooltipsPopupCreditLink =	'';
                            }
                            
                            $tooltiplinkintooltipboxstart = '<div class="tooltiplinkintooltipbox">';
                            $tooltiplinkintooltipboxclearfloat = '<div style="clear:both"></div>';
                            $tooltiplinkintooltipboxend = "</div>";
                            
                            
                            if ($enabletooltipsPopupCreditLinkInPopupWindow == 'YES')
                            {
                                $m_content = $m_content.$tooltiplinkintooltipboxstart.$tooltipsPopupCreditLink.$tooltiplinkintooltipboxclearfloat.$tooltiplinkintooltipboxend;
                            }
                            //!!!end
                            
                            
                            $m_title_in_tooltip = $m_keyword;
                            $m_keyword_result .= '//##'. " toolTips('.classtoolTips$m_keyword_id','$m_content'); ".'##]]';
                        }
                        $m_keyword_result .= '</script>';
                        //8.0.9
                        $m_bulletscreen_result = show_bullet_screen_for_one_tooltips_free($tooltip_post_id,$m_keyword_id,'content');
                        $m_keyword_result .= $m_bulletscreen_result;
                        
                        //end 8.0.9
                    }
                }
            }
            $m_keyword_id++;
        }
    }
    
    $content = $content.$m_keyword_result;
    do_action('action_after_showtooltips', $content);
    $content = apply_filters( 'filter_after_showtooltips',  $content);
    add_filter('the_title', 'wptexturize');
    return $content;
}

function tooltipsforContactForm7($content)
{
    global $table_prefix,$wpdb,$post;
    
    do_action('action_before_tooltipsincontent', $content);
    $content = apply_filters( 'filter_before_tooltipsincontent',  $content);
    
    //7.5.7
    $tooltips_pro_disable_tooltip_in_mobile_free = '';
    $tooltips_pro_disable_tooltip_in_mobile_free = tooltips_pro_disable_tooltip_in_mobile_free();
    
    //6.9.3
    //if (tooltips_pro_disable_tooltip_in_mobile_free())
    if ($tooltips_pro_disable_tooltip_in_mobile_free)
    {
        //return $content;
    }
    //end 6.9.3
    
    //!!!start
    $post_id = 0;
    if (is_object($post))
    {
        $post_id = $post->ID;
    }
    //!!!end
    
    $disableInHomePage = get_option("disableInHomePage");
    
    if ($disableInHomePage == 'NO')
    {
        if (is_home())
        {
            return $content;
        }
    }
    
    $showOnlyInSingleCategory = get_option("showOnlyInSingleCategory");
    
    if ($showOnlyInSingleCategory != 0)
    {
        
        $post_cats = wp_get_post_categories($post->ID);
        if (in_array($showOnlyInSingleCategory,$post_cats))
        {
            
        }
        else
        {
            return $content;
        }
    }
    
    //!!!start
    if ((isset($post->ID)) && (!(empty($post->ID))))
    {
        $disableTooltipsForGlossaryPage = disableTooltipsFreeForGlossaryPage($post_id);
        if ($disableTooltipsForGlossaryPage == true)
        {
            return $content;
        }
    }
    //!!!end
    
    $onlyFirstKeyword = get_option("onlyFirstKeyword");
    if 	($onlyFirstKeyword == false)
    {
        $onlyFirstKeyword = 'all';
    }
    
    $m_result = tooltips_get_option('tooltipsarray','post_title', 'DESC', 'LENGTH');
    
    if (!(empty($m_result)))
    {
        $m_keyword_id = 0;
        foreach ($m_result as $m_single)
        {
            
            $m_keyword = $m_single['keyword'];
            $m_content = $m_single['content'];
            
            $tooltip_post_id = $m_single['post_id'];
            $tooltip_unique_id = $m_single['unique_id'];
            
            $get_post_meta_value_for_this_page = get_post_meta($tooltip_post_id, 'toolstipssynonyms', true);
            $get_post_meta_value_for_this_page = trim($get_post_meta_value_for_this_page);
            
            $tooltsip_synonyms = false;
            if (!(empty($get_post_meta_value_for_this_page)))
            {
                $tooltsip_synonyms = explode('|', $get_post_meta_value_for_this_page);
            }
            
            if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
            {
                
            }
            else
            {
                $tooltsip_synonyms = array();
                $tooltsip_synonyms[] = $m_keyword;
                
            }
            
            if ((!(empty($tooltsip_synonyms))) && (is_array($tooltsip_synonyms)) && (count($tooltsip_synonyms) > 0))
            {
                $tooltsip_synonyms[] = $m_keyword;
                $tooltsip_synonyms = array_unique($tooltsip_synonyms);
                
                foreach ($tooltsip_synonyms as $tooltsip_synonyms_single)
                {
                    $m_keyword = $tooltsip_synonyms_single;
                    $m_keyword = trim($m_keyword);
                    //!!! $m_replace = "<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">$m_keyword</span>";
                    //6.9.3
                    //$m_replace = "<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">$m_keyword</span>";
                    //7.8.3
                    //7.9.3 $m_replace = "<span class='tooltipsall tooltipsincontent classtoolTips$m_keyword_id'>$m_keyword</span>";
                    $m_replace = "<span class='tooltipsall tooltipsincontent classtoolTips".esc_attr($m_keyword_id).">$m_keyword</span>";
                    
                    if (stripos($content,$m_keyword) === false)
                    {
                        
                    }
                    else
                    {
                        $m_keyword = str_replace('/','\/',$m_keyword); //!!! 6.2.9
                        
                        if ($onlyFirstKeyword == 'all')
                        {
                            $m_keyword = preg_quote($m_keyword,'/');
                            //!!!$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">"."\\2"."</span>"."\\3",$content);
                            //6.9.3
                            //$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content);
                            //7.4.1
                            //$content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".$m_keyword.")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content);
                            //7.8.3
                            //7.9.3
                            //$content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".$m_keyword.")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class='tooltipsall tooltipsincontent classtoolTips$m_keyword_id'>"."\\2"."</span>"."\\3",$content);
                            $content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".esc_attr($m_keyword).")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class='tooltipsall tooltipsincontent classtoolTips".esc_attr($m_keyword_id)."'>"."\\2"."</span>"."\\3",$content);
                        }
                        
                        if ($onlyFirstKeyword == 'first')
                        {
                            $m_keyword = preg_quote($m_keyword,'/');
                            //!!!$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">"."\\2"."</span>"."\\3",$content,1);
                            //6.9.3
                            //$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content,1);
                            // 7.4.1
                            //$content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".$m_keyword.")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content,1);
                            //7.8.3
                            //7.9.3
                            // $content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".$m_keyword.")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class='tooltipsall tooltipsincontent classtoolTips$m_keyword_id'>"."\\2"."</span>"."\\3",$content,1);
                            $content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".esc_attr($m_keyword).")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class='tooltipsall tooltipsincontent classtoolTips".esc_attr($m_keyword_id)."'>"."\\2"."</span>"."\\3",$content,1);
                        }
                        
                        if ($content1 == $content)
                        {
                            $content1 = " x98 ".$content." x98 ";
                            if ($onlyFirstKeyword == 'all')
                            {
                                $m_keyword = preg_quote($m_keyword,'/');
                                //!!! $content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">"."\\2"."</span>"."\\3",$content1);
                                //6.9.3
                                //$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content1);
                                //7.4.1
                                //$content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".$m_keyword.")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content1);
                                //7.8.3
                                //7.9.3
                                //$content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".$m_keyword.")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class='tooltipsall tooltipsincontent classtoolTips$m_keyword_id'>"."\\2"."</span>"."\\3",$content1);
                                //before 9.5.9 $content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".$m_keyword.")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class='tooltipsall tooltipsincontent classtoolTips".esc_attr($m_keyword_id)."'>"."\\2"."</span>"."\\3",$content1);
                                //9.5.9 
                                $content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".esc_attr($m_keyword).")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class='tooltipsall tooltipsincontent classtoolTips".esc_attr($m_keyword_id)."'>"."\\2"."</span>"."\\3",$content1);
                            }
                            
                            if ($onlyFirstKeyword == 'first')
                            {
                                $m_keyword = preg_quote($m_keyword,'/');
                                //!!! $content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">"."\\2"."</span>"."\\3",$content1,1);
                                //6.9.3
                                //$content1 = preg_replace("/(\W)(".$m_keyword.")(?![^<|^\[]*[>|\]])(\W)/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content1,1);
                                //7.4.1
                                //$content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".$m_keyword.")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class=\"tooltipsall tooltipsincontent classtoolTips$m_keyword_id\">"."\\2"."</span>"."\\3",$content1,1);
                                //7.8.3
                                //7.9.3
                                //$content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".$m_keyword.")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class='tooltipsall tooltipsincontent classtoolTips$m_keyword_id'>"."\\2"."</span>"."\\3",$content1,1);
                                $content1 = preg_replace("/([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])(".$m_keyword.")(?![^<|^\[]*[>|\]])([^A-Za-z0-9àâåäąæÀÂĄÄÅÆçćÇĆéèëêęÉÈÊËĘłŁñńÑŃîïÎÏòôöœóøÔŒÓÖØšśŚŠùûüÙÛÜÿŸžźżŹŻŽбвгдёжзийлпфцчщъыьэюяß])/is","\\1"."<span class='tooltipsall tooltipsincontent classtoolTips".esc_attr($m_keyword_id)."'>"."\\2"."</span>"."\\3",$content1,1);
                            }
                            
                            $content1 = trim($content1," x98 ");
                        }
                        //$m_check = "<span class=\"tooltipsall classtoolTips$m_keyword_id\" style=\"border-bottom:2px dotted #888;\">";
                        //7.8.3
                        //7.9.3
                        //$m_check = "<span class='tooltipsall classtoolTips$m_keyword_id' style='border-bottom:2px dotted #888;'>";
                        $m_check = "<span class='tooltipsall classtoolTips".esc_attr($m_keyword_id)."' style='border-bottom:2px dotted #888;'>";
                        if (stripos($content1, $m_check.$m_check) === false)
                        {
                            $content = $content1;
                        }
                        else
                        {
                            $content = $content;
                        }
                        
                    }
                }
                //!!! old $content = str_replace($m_single['keyword'], $tooltip_unique_id, $content);
                //!!! 6.2.9 $content = preg_replace("/"."(".$m_single['keyword'].")(?![^@@@@]*[####])/s",'@@@@'.$tooltip_unique_id.'####'."\\2",$content); //!!!new
                
                    $m_single_keyword = str_replace('/','\/',$m_single['keyword']); //!!! 6.2.9
                    // $content = preg_replace("/"."(".$m_single_keyword.")(?![^@@@@]*[####])/s",'@@@@'.$tooltip_unique_id.'####'."\\2",$content); //!!!new
                    //!!! 7.9.5 this is not same of pro
                        $content = preg_replace("/"."(".$m_single_keyword.")(?![^@@@@]*[%%%%])/s",'@@@@'.$tooltip_unique_id.'%%%%'."\\2",$content); //!!!new
                        
            }
            
            $m_keyword_id++;
        }
        foreach ($m_result as $m_single)
        {
            $m_keyword = $m_single['keyword'];
            $m_content = $m_single['content'];
            $tooltip_post_id = $m_single['post_id'];
            $tooltip_unique_id = $m_single['unique_id'];
            $content = str_ireplace($tooltip_unique_id, $m_keyword , $content);
        }
    }
    
    do_action('action_after_tooltipsincontent', $content);
    $content = apply_filters( 'filter_after_tooltipsincontent',  $content);
    
    //!!!start
    $content = str_replace('@@@@', '', $content);
    $content = str_replace('####', '', $content);
    //!!! 7.9.5 this is not same of pro
    $content = str_replace('%%%%', '', $content);
    //!!!end
    $content = str_ireplace('//##', '', $content);
    $content = str_ireplace('##]]', '', $content);
    
    return $content;
}

add_filter('wpcf7_form_elements','tooltipsforContactForm7',10,1);
add_filter('wpcf7_form_elements','showTooltipsInContactForm7',11,1);

