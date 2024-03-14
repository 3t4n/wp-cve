<?php

namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit;

class overrideOldVer
{
    protected static $instance = null;

    public static function overrideThemeMod()
    {
        $is_override_theme_mod =  get_option('njt_is_override_theme_mod', 'false');
        $logicDisplayPage = get_theme_mod('njt_nofi_logic_display_page');
        $listDisplayPage = get_theme_mod('njt_nofi_list_display_page');
        $logicDisplayPost = get_theme_mod('njt_nofi_logic_display_post');
        $listDisplayPost = get_theme_mod('njt_nofi_list_display_post');

        if ($is_override_theme_mod === 'false') {
            $isDisplayHome = get_theme_mod('njt_nofi_homepage', 'no_exit_dis_home');
            $isDisplayPage = get_theme_mod('njt_nofi_pages', 'no_exit_dis_page');
            $isDisplayPosts = get_theme_mod('njt_nofi_posts', 'no_exit_dis_post');
            $isDisplayPageOrPostId = get_theme_mod('njt_nofi_pp_id');
            $arrDisplayPageOrPostId = $isDisplayPageOrPostId ? explode(",", $isDisplayPageOrPostId) : [];
            $excludeDisplayPageOrPostId = get_theme_mod('njt_nofi_exclude_pp_id');
            $arrExcludeDisplayPageOrPostId = $excludeDisplayPageOrPostId ? explode(",", $excludeDisplayPageOrPostId) : [];


            if (
                $isDisplayHome  === 'no_exit_dis_home'
                && $isDisplayPage === 'no_exit_dis_page'
                && $isDisplayPosts === 'no_exit_dis_post'
                && $isDisplayPageOrPostId === 'no_exit_pp_id'
                && $excludeDisplayPageOrPostId === 'exclude_pp_id'
            ) {
                update_option('njt_is_override_theme_mod', 'true');
                return;
            }

            $oldVerListDisplayPage = array();
            $oldVerListDisplayPost = array();

            $oldVerListExcludePage = array();
            $oldVerListExcludePost = array();

            $mergeDisplayPage = '';
            $mergeDisplayPost = '';
            foreach ($arrDisplayPageOrPostId as &$value) {
                if (get_post_type($value) === 'page') {
                    $oldVerListDisplayPage[] = $value;
                } else {
                    $oldVerListDisplayPost[] = $value;
                }
            }

            foreach ($arrExcludeDisplayPageOrPostId as &$value) {
                if (get_post_type($value) === 'page') {
                    $oldVerListExcludePage[] = $value;
                } else {
                    $oldVerListExcludePost[] = $value;
                }
            }
             //Chỉ chọn exclude
             if (( $excludeDisplayPageOrPostId)) {
                $logicDisplayPage = 'hide_all_page';
                $logicDisplayPost = 'hide_all_post';
            }

            //Chỉ chọn include
            if (( $isDisplayPageOrPostId)) {
                if (count($oldVerListDisplayPage) > 0) {
                    $logicDisplayPage = 'dis_selected_page';
                    $mergeDisplayPage = count($oldVerListDisplayPage) > 0 ? $listDisplayPage . ',' . implode(',', $oldVerListDisplayPage) : $listDisplayPage;
                    if ($isDisplayHome) {
                        $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                    }
                } else {
                    $logicDisplayPage = 'hide_all_page';
                }

                if (count($oldVerListDisplayPost) > 0) {
                    $logicDisplayPost = 'dis_selected_post';
                    $mergeDisplayPost = count($oldVerListDisplayPost) > 0 ? $listDisplayPost . ',' . implode(',', $oldVerListDisplayPost) : $listDisplayPost;
                } else {
                    $logicDisplayPost = 'hide_all_post';
                }
            }

            if ($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome) {
                $logicDisplayPage = 'dis_selected_page';
                $mergeDisplayPage = 'home_page';
                $logicDisplayPost = 'hide_all_post';
            }
            if ($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage) {
                $logicDisplayPage = 'dis_all_page';
                $logicDisplayPost = 'hide_all_post';
            }

            if ($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts) {
                $logicDisplayPage = 'hide_all_page';
                $logicDisplayPost = 'dis_all_post';
            }
            if (($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome) && ($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage)) {
                $logicDisplayPage = 'dis_all_page';
                $logicDisplayPost = 'hide_all_post';
            }

            if (($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome) && ($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)) {
                $logicDisplayPage = 'dis_selected_page';
                $mergeDisplayPage = 'home_page';
                $logicDisplayPost = 'dis_all_post';
            }

            if (($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage) && ($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)) {
                $logicDisplayPage = 'dis_all_page';
                $logicDisplayPost = 'dis_all_post';
            }


            if (($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome) && ($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage) && ($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)) {
                $logicDisplayPage = 'dis_all_page';
                $logicDisplayPost = 'dis_all_post';
            }

            if (($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome) && ($isDisplayPageOrPostId)) {
                if (count($oldVerListDisplayPage) > 0) {
                    $logicDisplayPage = 'dis_selected_page';
                    $mergeDisplayPage = count($oldVerListDisplayPage) > 0 ? $listDisplayPage . ',' . implode(',', $oldVerListDisplayPage) : $listDisplayPage;
                    if ($isDisplayHome) {
                        $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                    }
                } else {
                    $logicDisplayPage = 'dis_selected_page';
                    if ($isDisplayHome) {
                        $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                    }
                }

                if (count($oldVerListDisplayPost) > 0) {
                    $logicDisplayPost = 'dis_selected_post';
                    $mergeDisplayPost = count($oldVerListDisplayPost) > 0 ? $listDisplayPost . ',' . implode(',', $oldVerListDisplayPost) : $listDisplayPost;
                } else {
                    $logicDisplayPost = 'hide_all_post';
                }
            }

            if (($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome) && ($excludeDisplayPageOrPostId)) {
                $logicDisplayPage = 'dis_selected_page';
                $mergeDisplayPage = 'home_page';
                $logicDisplayPost = 'hide_all_post';
            }
            if (($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage) && ($isDisplayPageOrPostId)) {
                $logicDisplayPage = 'dis_all_page';
                if (count($oldVerListDisplayPost) > 0) {
                    $logicDisplayPost = 'dis_selected_post';
                    $mergeDisplayPost = count($oldVerListDisplayPost) > 0 ? $listDisplayPost . ',' . implode(',', $oldVerListDisplayPost) : $listDisplayPost;
                } else {
                    $logicDisplayPost = 'hide_all_post';
                }
            }
            if (($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage) && ($excludeDisplayPageOrPostId)) {
                if (count($oldVerListExcludePage) > 0) {
                    if ($logicDisplayPage == 'hide_selected_page') {
                        $mergeDisplayPage = count($oldVerListExcludePage) > 0 ? $listDisplayPage . ',' . implode(',', $oldVerListExcludePage) : $listDisplayPage;
                    } else {
                        $mergeDisplayPage = count($oldVerListExcludePage) > 0 ? implode(',', $oldVerListExcludePage) : $listDisplayPage;
                    }

                    // if (!$isDisplayHome) {
                    //     $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                    // }
                    $logicDisplayPage = 'hide_selected_page';
                } else {
                    $logicDisplayPage = 'dis_all_page';
                }
                $logicDisplayPost = 'hide_all_post';
            }
            if (($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts) && ($isDisplayPageOrPostId)) {
                if (count($oldVerListDisplayPage) > 0) {
                    $logicDisplayPage = 'dis_selected_page';
                    $mergeDisplayPage = count($oldVerListDisplayPage) > 0 ? $listDisplayPage . ',' . implode(',', $oldVerListDisplayPage) : $listDisplayPage;
                    if ($isDisplayHome) {
                        $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                    }
                } else {
                    $logicDisplayPage = 'hide_all_page';
                }
                $logicDisplayPost = 'dis_all_post';
            }
            if (($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)
                && ( $isDisplayPageOrPostId)
            ) {
                if (count($oldVerListDisplayPage) > 0) {
                    $logicDisplayPage = 'dis_selected_page';
                    $mergeDisplayPage = count($oldVerListDisplayPage) > 0 ? $listDisplayPage . ',' . implode(',', $oldVerListDisplayPage) : $listDisplayPage;
                    if ($isDisplayHome) {
                        $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                    }
                } else {
                    $logicDisplayPage = 'hide_all_page';
                }

                $logicDisplayPost = 'dis_all_post';
            }
            if (($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)
                && ( $excludeDisplayPageOrPostId)
            ) {
                $logicDisplayPage = 'hide_all_page';
                if (count($oldVerListExcludePost) > 0) {
                    if ($logicDisplayPost == 'hide_selected_post') {
                        $mergeDisplayPost = count($oldVerListExcludePost) > 0 ? $listDisplayPost . ',' . implode(',', $oldVerListExcludePost) : $listDisplayPost;
                    } else {
                        $mergeDisplayPost = count($oldVerListExcludePost) > 0 ? implode(',', $oldVerListExcludePost) : $listDisplayPost;
                    }
                    $logicDisplayPost = 'hide_selected_post';
                } else {
                    $logicDisplayPost = 'dis_all_post';
                }
            }
            if (($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome)
                && ($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage)
                && ( $isDisplayPageOrPostId)
            ) {
                $logicDisplayPage = 'dis_all_page';
                if (count($oldVerListDisplayPost) > 0) {
                    $logicDisplayPost = 'dis_selected_post';
                    $mergeDisplayPost = count($oldVerListDisplayPost) > 0 ? $listDisplayPost . ',' . implode(',', $oldVerListDisplayPost) : $listDisplayPost;
                } else {
                    $logicDisplayPost = 'hide_all_post';
                }
            }
            if (($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome)
                && ($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)
                && ( $isDisplayPageOrPostId)
            ) {
                if (count($oldVerListDisplayPage) > 0) {
                    $logicDisplayPage = 'dis_selected_page';
                    $mergeDisplayPage = count($oldVerListDisplayPage) > 0 ? $listDisplayPage . ',' . implode(',', $oldVerListDisplayPage) : $listDisplayPage;
                    if ($isDisplayHome) {
                        $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                    }
                } else {
                    $logicDisplayPage = 'dis_selected_page';
                    $mergeDisplayPage = 'home_page';
                }
                $logicDisplayPost = 'dis_all_post';
            }
            if (($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome)
                && ($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)
                && ( $excludeDisplayPageOrPostId)
            ) {
                $logicDisplayPage = 'dis_selected_page';
                $mergeDisplayPage = 'home_page';
                if (count($oldVerListExcludePost) > 0) {
                    if ($logicDisplayPost == 'hide_selected_post') {
                        $mergeDisplayPost = count($oldVerListExcludePost) > 0 ? $listDisplayPost . ',' . implode(',', $oldVerListExcludePost) : $listDisplayPost;
                    } else {
                        $mergeDisplayPost = count($oldVerListExcludePost) > 0 ? implode(',', $oldVerListExcludePost) : $listDisplayPost;
                    }
                    $logicDisplayPost = 'hide_selected_post';
                } else {
                    $logicDisplayPost = 'dis_all_post';
                }
            }
            if (($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage)
                && ($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)
                && ( $isDisplayPageOrPostId)
            ) {
                $logicDisplayPage = 'dis_all_page';
                $logicDisplayPost = 'dis_all_post';
            }

          

            if (($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome)
                && ($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage)
                && ($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)
                && ( $isDisplayPageOrPostId)
            ) {
                $logicDisplayPage = 'dis_all_page';
                $logicDisplayPost = 'dis_all_post';
            }


            if (($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome)
                && ($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage)
                && ($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)
                && ( $excludeDisplayPageOrPostId)
            ) {
                if (count($oldVerListExcludePage) > 0) {
                    if ($logicDisplayPage == 'hide_selected_page') {
                        $mergeDisplayPage = count($oldVerListExcludePage) > 0 ? $listDisplayPage . ',' . implode(',', $oldVerListExcludePage) : $listDisplayPage;
                    } else {
                        $mergeDisplayPage = count($oldVerListExcludePage) > 0 ? implode(',', $oldVerListExcludePage) : $listDisplayPage;
                    }

                    // if (!$isDisplayHome) {
                    //     $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                    // }
                    $logicDisplayPage = 'hide_selected_page';
                } else {
                    $logicDisplayPage = 'dis_all_page';
                }
                if (count($oldVerListExcludePost) > 0) {
                    if ($logicDisplayPost == 'hide_selected_post') {
                        $mergeDisplayPost = count($oldVerListExcludePost) > 0 ? $listDisplayPost . ',' . implode(',', $oldVerListExcludePost) : $listDisplayPost;
                    } else {
                        $mergeDisplayPost = count($oldVerListExcludePost) > 0 ? implode(',', $oldVerListExcludePost) : $listDisplayPost;
                    }
                    $logicDisplayPost = 'hide_selected_post';
                } else {
                    $logicDisplayPost = 'dis_all_post';
                }
            }
             

            if (($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage)
            && ($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)
            && ( $excludeDisplayPageOrPostId)
            ) {
                if (count($oldVerListExcludePage) > 0) {
                    if ($logicDisplayPage == 'hide_selected_page') {
                        $mergeDisplayPage = count($oldVerListExcludePage) > 0 ? $listDisplayPage . ',' . implode(',', $oldVerListExcludePage) : $listDisplayPage;
                    } else {
                        $mergeDisplayPage = count($oldVerListExcludePage) > 0 ? implode(',', $oldVerListExcludePage) : $listDisplayPage;
                    }

                    // if (!$isDisplayHome) {
                    //     $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                    // }
                    $logicDisplayPage = 'hide_selected_page';
                } else {
                    $logicDisplayPage = 'dis_all_page';
                }
                if (count($oldVerListExcludePost) > 0) {
                    if ($logicDisplayPost == 'hide_selected_post') {
                        $mergeDisplayPost = count($oldVerListExcludePost) > 0 ? $listDisplayPost . ',' . implode(',', $oldVerListExcludePost) : $listDisplayPost;
                    } else {
                        $mergeDisplayPost = count($oldVerListExcludePost) > 0 ? implode(',', $oldVerListExcludePost) : $listDisplayPost;
                    }
                    $logicDisplayPost = 'hide_selected_post';
                } else {
                    $logicDisplayPost = 'dis_all_post';
                }
            }
            //Chỉ chọn cả include và exclude
            if (( $isDisplayPageOrPostId)
                && ( $excludeDisplayPageOrPostId)
            ) {
                if (count($oldVerListDisplayPage) > 0) {
                    $logicDisplayPage = 'dis_selected_page';
                    $mergeDisplayPage = count($oldVerListDisplayPage) > 0 ? $listDisplayPage . ',' . implode(',', $oldVerListDisplayPage) : $listDisplayPage;
                    if ($isDisplayHome) {
                        $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                    }
                } else {
                    $logicDisplayPage = 'hide_all_page';
                }

                if (count($oldVerListDisplayPost) > 0) {
                    $logicDisplayPost = 'dis_selected_post';
                    $mergeDisplayPost = count($oldVerListDisplayPost) > 0 ? $listDisplayPost . ',' . implode(',', $oldVerListDisplayPost) : $listDisplayPost;
                } else {
                    $logicDisplayPost = 'hide_all_post';
                }
            }

             //Chọn homepage, page, post, include, exclude
             if (($isDisplayHome === 'no_exit_dis_home' || $isDisplayHome)
             && ($isDisplayPage === 'no_exit_dis_page' || $isDisplayPage)
             && ($isDisplayPosts === 'no_exit_dis_post' || $isDisplayPosts)
             && ( $isDisplayPageOrPostId)
             && ( $excludeDisplayPageOrPostId)
         ) {
             if (count($oldVerListDisplayPage) > 0) {
                 $logicDisplayPage = 'dis_selected_page';
                 $mergeDisplayPage = count($oldVerListDisplayPage) > 0 ? $listDisplayPage . ',' .implode(',',$oldVerListDisplayPage) : $listDisplayPage;
                 if ($isDisplayHome) {
                     $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                 }
             } else {
                 $logicDisplayPage = 'dis_all_page';
             }
     
             if (count($oldVerListDisplayPost) > 0) {
                 $logicDisplayPost = 'dis_selected_post';
                 $mergeDisplayPost = count($oldVerListDisplayPost) > 0 ? $listDisplayPost . ',' .implode(',',$oldVerListDisplayPost) : $listDisplayPost;
             } else {
                 $logicDisplayPost = 'dis_all_post';
             }
     
             if (count($oldVerListExcludePage) > 0) {
                 if ($logicDisplayPage == 'hide_selected_page' ) {
                     $mergeDisplayPage = count($oldVerListExcludePage) > 0 ? $listDisplayPage . ',' . implode(',',$oldVerListExcludePage) : $listDisplayPage;
                 } else {
                     $mergeDisplayPage = count($oldVerListExcludePage) > 0 ? implode(',',$oldVerListExcludePage) : $listDisplayPage;
                 }
                
                 if (!$isDisplayHome) {
                     $mergeDisplayPage = $mergeDisplayPage . ',home_page';
                 }
                 $logicDisplayPage = 'hide_selected_page';
             } else {
                 $logicDisplayPage = 'dis_all_page';
             }
     
             if (count($oldVerListExcludePost) > 0) {
                 if ($logicDisplayPost == 'hide_selected_post') {
                     $mergeDisplayPost = count($oldVerListExcludePost) > 0 ? $listDisplayPost . ',' .implode(',',$oldVerListExcludePost) : $listDisplayPost;
                 } else {
                     $mergeDisplayPost = count($oldVerListExcludePost) > 0 ? implode(',',$oldVerListExcludePost) : $listDisplayPost;
                 }
                 $logicDisplayPost = 'hide_selected_post';
             }else {
                 $logicDisplayPost = 'dis_all_post';
             }
            } 

            if (
                !$isDisplayHome 
                && !$isDisplayPage
                && !$isDisplayPosts
                && !$isDisplayPageOrPostId
                && !$excludeDisplayPageOrPostId
            ) {
                $logicDisplayPage = 'hide_all_page';
                $logicDisplayPost = 'dis_all_post';
            }
            



            $arrUniqueDisplayPage = implode(',', array_unique(explode(',', $mergeDisplayPage)));
            $arrUniqueDisplayPost = implode(',', array_unique(explode(',', $mergeDisplayPost)));

            set_theme_mod('njt_nofi_logic_display_page', $logicDisplayPage);
            set_theme_mod('njt_nofi_list_display_page', $arrUniqueDisplayPage);

            set_theme_mod('njt_nofi_logic_display_post', $logicDisplayPost);
            set_theme_mod('njt_nofi_list_display_post', $arrUniqueDisplayPost);

            update_option('njt_is_override_theme_mod', 'true');
        } else {
            if ($logicDisplayPage === 'hide_all_page') {
                $a = get_option('njt_is_override_theme_mod_ver14');
                if ($a) return;
                set_theme_mod('njt_nofi_logic_display_page', 'dis_all_page');
                if ($logicDisplayPost === 'hide_all_post')
                    set_theme_mod('njt_nofi_logic_display_post', 'dis_all_post');
                update_option('njt_is_override_theme_mod_ver14', 'true');
            }
        }
    }
}
