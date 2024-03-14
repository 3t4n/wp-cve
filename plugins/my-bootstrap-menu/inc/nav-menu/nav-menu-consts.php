<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 16/06/2015
 * Time: 21:15
 * http://getbootstrap.com/components/#nav
 */
namespace My_Bootstrap_Menu_Plugin_Namespace {

    abstract class My_Bootstrap_Menu_Nav_Menu_Consts
    {


        public static function Menu_Type()
        {
            //ul list class, prefix with 'nav'
            return array('Navbar' => 'navbar-nav',
                'Tabs' => 'navbar-btn nav-tabs',
                'Pills' => 'navbar-btn nav-pills',
                'Buttons' => 'navbar-btn',
                'Button Group' => 'navbar-btn btn-group');
        }

        public static function Navbar_Format()
        {
            // Main div class prefix with 'navbar'
            return array('Default' => 'navbar-default',
                'Inverse' => 'navbar-inverse');
        }


        public static function Button_Group_Size()
        {
            return array('Default' => '',
                'Extra Small' => 'btn-group-xs',
                'Small' => 'btn-group-sm',
                'Large' => 'btn-group-lg');
        }

        public static function Button_Type()
        {
            return array('Default' => 'btn-default',
                'Primary' => 'btn-primary',
                'Success' => 'btn-success',
                'Info' => 'btn-info',
                'Warning' => 'btn-warning',
                'Danger' => 'btn-danger',
                'Link' => 'btn-link',
                'None' => '');
        }

        public static function Alignment()
        {
            return array('Left' => 'navbar-left',
                'Right' => 'navbar-right');
        }


        public static function Menu_Alignment()
        {
            //TODO: implement vertical (side) menu - removed temporarily...
            return array('Left' => 'nav navbar-left',
                'Right' => 'nav navbar-right',
                'Justified' => 'nav nav-justified',
                'Button Justified' => 'btn-group-justified');
        }


        public static function Fixed_Type()
        {
            return array('Default' => '',
                'Fixed Top' => 'navbar-fixed-top',
                'Fixed Bottom' => 'navbar-fixed-bottom',
                'Static Top' => 'navbar-static-top');
        }

        public static function Class_Container()
        {
            return array('Container Fluid' => 'container-fluid',
                'Container' => 'container',
                'None' => '');
        }

        public static function Submenu_Dropdown_Direction()
        {
            return array('Drop down' => '',
                        'Drop up' => 'dropup');
        }

        public static function Submenu_Dropdown_Alignment()
        {
            return array('Default Align' => '',
                'Align Right' => 'dropdown-menu-right');
        }

        public static function Text_Transform()
        {
            return array('Default' => '',
                'Uppercase' => 'uppercase',
                'Lowercase' => 'lowercase',
                'Capitalize' => 'capitalize');
        }
    }
}