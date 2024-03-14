<?php

class SPR_Top_Widget extends WP_Widget
{

    function __construct()
    {
        parent::__construct(
                'spr_top_widget', // 
                spr_localize('widgets_top_rated_admin_title', false), array('description'=>spr_localize('widgets_top_rated_description', false))
        );
    }

    public function widget($args, $instance)
    {
        global $wpdb;
        $list=spr_list_cpt_slugs();
        foreach ($list as $list_)
        {
            if ($instance[$list_])
            {
                $intypes[]="'".$list_."'";
            }
        }
        if (count($intypes)>0)
        {
            $intypes=implode(',', $intypes);
            $count=$instance['count'];
            $query="select `id`, `post_title` from `".$wpdb->prefix."posts` inner join `".$wpdb->prefix."spr_rating` on (`".$wpdb->prefix."posts`.`ID` =  `".$wpdb->prefix."spr_rating`.`post_id`) where `post_type` in ($intypes) order by `".$wpdb->prefix."spr_rating`.`points` DESC, `".$wpdb->prefix."posts`.`post_title` ASC limit $count;";
            $popularity=$wpdb->get_results($query, ARRAY_A);
            if (count($popularity)<1)
            {
                $widget_body=spr_localize('widgets_top_rated_no_results', false);
            }
            else
            {
                $widget_body='<ul style="list-style-position:inside;list-style-type:'.$instance['list_style'].';">';
                foreach ($popularity as $popularity_)
                {
                    $widget_body.='<li><a href="'.get_permalink($popularity_['id']).'" title="'.$popularity_['post_title'].'">'.$popularity_['post_title'].'</a></li>';
                }
                $widget_body.="<ul>";
            }
        }
        else
        {
            $widget_body=spr_localize('widgets_top_rated_no_results', false);
        }
        $title=$args['before_title'].$instance['title'].$args['after_title'];
        echo $args['before_widget'].$title.$widget_body.$args['after_widget'];
    }

    public function form($instance)
    {
        $count=(!empty($instance['count'])) ? $instance['count'] : "5";
        echo '<p><label style="font-weight: 700;">'.spr_localize('widgets_top_rated_settings_title', false).':</label><input type="text" style="float:right;" maxlength="200" name="'.$this->get_field_name('title').'" id="'.$this->get_field_id('title').'" value="'.$instance['title'].'"></p>
        <p><label style="font-weight: 700;">'.spr_localize('widgets_top_rated_settings_what_to_include', false).':</label>'.$this->spr_cpt_widgets($instance).'</p><p><label style="font-weight: 700;">'.spr_localize('widgets_top_rated_settings_list_style', false).':</label>
        <select style="float:right;" name="'.$this->get_field_name('list_style').'" id="'.$this->get_field_id('list_style').'">
        <option value="none" '.selected($instance['list_style'], 'none', false).'>'.spr_localize('widgets_top_rated_settings_list_style_none', false).'</option>                        
        <option value="circle" '.selected($instance['list_style'], 'circle', false).'>'.spr_localize('widgets_top_rated_settings_list_style_circle', false).'</option>
        <option value="disc" '.selected($instance['list_style'], 'disc', false).'>'.spr_localize('widgets_top_rated_settings_list_style_disc', false).'</option>
        <option value="square" '.selected($instance['list_style'], 'square', false).'>'.spr_localize('widgets_top_rated_settings_list_style_square', false).'</option>
        <option value="decimal" '.selected($instance['list_style'], 'decimal', false).'>'.spr_localize('widgets_top_rated_settings_list_style_decimal', false).'</option>
        <option value="decimal-leading-zero" '.selected($instance['list_style'], 'decimal-leading-zero', false).'>'.spr_localize('widgets_top_rated_settings_list_style_decimal_leading_zero', false).'</option>
        <option value="lower-alpha" '.selected($instance['list_style'], 'lower-alpha', false).'>'.spr_localize('widgets_top_rated_settings_list_style_lower_alpha', false).'</option>
        <option value="upper-alpha" '.selected($instance['list_style'], 'upper-alpha', false).'>'.spr_localize('widgets_top_rated_settings_list_style_upper_alpha', false).'</option>
        <option value="lower-roman" '.selected($instance['list_style'], 'lower-roman', false).'>'.spr_localize('widgets_top_rated_settings_list_style_lower_roman', false).'</option>
        <option value="upper-roman" '.selected($instance['list_style'], 'upper-roman', false).'>'.spr_localize('widgets_top_rated_settings_list_style_upper_roman', false).'</option>                                                   
        </select></p>
        <p><label style="font-weight: 700;">'.spr_localize('widgets_top_rated_settings_items_count', false).':</label><input type="text" size="10" style="float:right;" maxlength="200" name="'.$this->get_field_name('count').'" id="'.$this->get_field_id('count').'" value="'.$count.'"></p>';
    }

    public function update($new_instance, $old_instance)
    {
        $instance=array();
        $list=spr_list_cpt_slugs();
        foreach ($list as $list_)
        {
            if (isset($new_instance[$list_]))
            {
                $instance[$list_]='1';
            }
            else
            {
                $instance[$list_]='0';
            }
        }
        if (isset($new_instance['list_style']))
        {
            $instance['list_style']=$new_instance['list_style'];
        }
        else
        {
            $instance['list_style']='none';
        }
        if (isset($new_instance['count'])&&is_numeric($new_instance['count']))
        {
            $instance['count']=$new_instance['count'];
        }
        else
        {
            $instance['count']='5';
        }
        $instance['title']=(!empty($new_instance['title']) ) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }

    public function spr_cpt_widgets($options)
    {
        $post_types=get_post_types(array('public'=>true, '_builtin'=>false), 'objects', 'and');
        $list=spr_list_cpt_slugs();
        foreach ($list as $list_)
        {
            $def_types[$list_]=0;
        }
        $diff=array_diff_key($def_types, $options);
        if (count($diff)>0)
        {
            $options=array_merge($options, $diff);
        }
        $result='<table style="width:100%;"><tr><td class="spr_cb_labels">Posts</td><td><input type="checkbox" style="float:right;" name="'.$this->get_field_name('post').'" id="'.$this->get_field_id('post').'" value="'.$options['post'].'" '.checked($options['post'], 1, false).'></td></tr><tr><td class="spr_cb_labels">Pages</td><td><input type="checkbox" style="float:right;" name="'.$this->get_field_name('page').'" id="'.$this->get_field_id('page').'" value="'.$options['page'].'" '.checked($options['page'], 1, false).'></td></tr>';
        foreach ($post_types as $post_type)
        {
            $result.= '<tr><td class="spr_cb_labels">'.$post_type->labels->name.'</td><td><input type="checkbox" style="float:right;" name="'.$this->get_field_name($post_type->rewrite['slug']).'" id="'.$this->get_field_id($post_type->rewrite['slug']).'" value="'.$options[$post_type->rewrite['slug']].'" '.checked($options[$post_type->rewrite['slug']], 1, false).'></td></tr>';
        }
        $result.="</table>";
        return $result;
    }

}

function register_spr_widgets()
{
    register_widget('SPR_Top_Widget');
}

add_action('widgets_init', 'register_spr_widgets');
?>