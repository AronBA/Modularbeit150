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
        private Shortcodes $shortcodes;
        public string $plugin;

        function __construct() {
            include_once "includes/Shortcodes.php";
            $this->plugin = plugin_basename( __FILE__ );
            $this->shortcodes = new Shortcodes();
        }

        function register() {
            add_action( 'wp_enqueue_scripts', array($this->shortcodes, 'weather_enqueue_scripts'));
            add_shortcode('testWeather', array($this->shortcodes,'testShortcode'));
            add_shortcode('Wind', array($this->shortcodes,'Wind'));
            add_shortcode('temperatureWeather', array($this->shortcodes, 'weatherShortcode'));
	        add_shortcode('sunWeather', array($this->shortcodes, 'sunShortcode'));
            add_shortcode('largeWeather', array($this->shortcodes, 'largeWeatherShortcode'));
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