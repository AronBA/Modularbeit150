<?php

/*
Plugin Name: Weather
Plugin URI: https://github.com/AronBA/Modularbeit150
Description: This is my first attempt on writing a custom Plugin for this amazing tutorial series.
Version: 1.0.0
Author: Aron
Author URI: https://github.com/AronBA
License: GPLv2 or later
Text Domain: weather-plugin
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Copyright 2005-2015 Automattic, Inc.
*/


if(!defined("ABSPATH")){
    die;
}

if ( !class_exists( 'Weather' ) ) {

    class Weather
    {

        public $plugin;

        function __construct() {
            include_once "includes/Shortcodes.php";
            $this->plugin = plugin_basename( __FILE__ );
            $shortcodes = new Shortcodes();
            add_shortcode('testWeather', array($shortcodes,'testShortcode'));
        }


        function register() {
            add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
        }

        protected function create_post_type() {
            add_action( 'init', array( $this, 'custom_post_type' ) );
        }

        function custom_post_type() {
            register_post_type( 'book', ['public' => true, 'label' => 'Books'] );
        }

        function enqueue() {
            wp_enqueue_style( 'mypluginstyle', plugins_url( '/assets/styles.css', __FILE__ ) );
            wp_enqueue_script( 'mypluginscript', plugins_url( '/assets/scripts.js', __FILE__ ) );
        }

        function activate() {
            require_once plugin_dir_path( __FILE__ ) . 'includes/weather-activate.php';
            WeatherActivate::activate();
        }
        function deactivate(){
            require_once plugin_dir_path( __FILE__ ) . 'includes/weather-deactivate.php';
            WeatherDeactivate::deactivate();
        }
    }

    $weather = new Weather();
    $weather->register();


    register_activation_hook( __FILE__, array( $weather, 'activate' ) );
    register_deactivation_hook( __FILE__, array( $weather, 'deactivate' ) );



}