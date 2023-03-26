<?php

/*
Plugin Name: Weather
Plugin URI: https://github.com/AronBA/Modularbeit150
Description: This is a Plugin which adds various weather related shortcodes
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
        private WeatherShortcodes $shortcodes;
        private WeatherAdminPanel $adminPanel;
        public string $plugin;

        function __construct() {
            include_once "includes/WeatherShortcodes.php";
            include_once "includes/WeatherAdminPanel.php";
            $this->plugin = plugin_basename( __FILE__ );
            $this->adminPanel = new WeatherAdminPanel();
            $this->shortcodes = new WeatherShortcodes();
        }

        function addAdminMenu(): void
        {
            add_action('admin_init', array($this->adminPanel, 'addSettings'));
            add_options_page('Weather Manager', 'Weather Manager', 'manage_options', 'weather_main_options', array( $this->adminPanel, 'getAdminPanel'));
        }


        function register(): void {
            add_action('admin_menu', array( $this, 'addAdminMenu' ));
            add_action( 'wp_enqueue_scripts', array($this->shortcodes, 'weather_enqueue_scripts'));


            add_shortcode('weather_Wind', array($this->shortcodes,'windShortcode'));
            add_shortcode('weather_General', array($this->shortcodes, 'conditionShortcode'));
	        add_shortcode('weather_Time', array($this->shortcodes, 'sunShortcode'));
            add_shortcode('weather_Temperature', array($this->shortcodes, 'temparatureShortcode'));
            add_shortcode('weather_Pollution', array($this->shortcodes, 'aqiShortcode'));
        }

        function activate(): void {
            require_once plugin_dir_path( __FILE__ ) . 'includes/weather-activate.php';
            WeatherActivate::activate();
        }
        function deactivate(): void{
            require_once plugin_dir_path( __FILE__ ) . 'includes/weather-deactivate.php';
            WeatherDeactivate::deactivate();
        }
    }

    $weather = new Weather();
    $weather->register();

    register_activation_hook( __FILE__, array( $weather, 'activate' ) );
    register_deactivation_hook( __FILE__, array( $weather, 'deactivate' ) );
}