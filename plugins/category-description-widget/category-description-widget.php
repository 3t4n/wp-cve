<?php
/*
Plugin Name: Category Description Widget
Plugin URI: http://lostfocus.de
Description: Enables a widget with the category description
Version: 2.1
Author: Dominik Schwind
Author URI: http://lostfocus.de/
License: GPL2
*/

/*  Copyright 2014  Dominik Schwind  (email : dschwind@lostfocus.de)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class Category_Description_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            'category_description_widget', // Base ID
            'Category Description Widget', // Name
            array( 'description' => 'A widget with the category description', ) // Args
        );
    }
    public function widget( $args, $instance ) {
        if(!is_tax() && !is_category() && !is_tag()){
            return false;
        }
        extract( $args );
        echo $before_widget;
        echo term_description();
        echo $after_widget;
    }
}

function category_description_widget_init(){
    register_widget('Category_Description_Widget');
}


add_action( 'widgets_init', 'category_description_widget_init');