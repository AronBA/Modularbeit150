<?php

class adminPanel
{
    public SettingFieldCalls $obj;
    public function __construct(){
        $this->obj = new SettingFieldCalls();
    }
    /**
     * @return void
     * Echos Admin Panel for Weather plugin
     */
    public function getAdminPanel(): void{
        echo "<form action='options.php' method='post'>";
        settings_fields('weather_main_options');
        do_settings_sections('weather_plugin_manager_options');
        submit_button('Save', 'primary', 'submit', false);
        echo "</form>";
    }

    /**
     * Adds settings to Wordpress
     * @return void
     */
    function addSettings(): void
    {
        add_settings_section('weather_main_options', 'Weather Plugin Settings', array($this->obj, 'sectionCall'), 'weather_plugin_manager_options');

        register_setting('weather_main_options', 'key', Array('type'=>'string', 'show_in_rest'=>'true', 'default' => 'default'));
        add_settings_field(
            'key',
            'API Key',
            array($this->obj, 'keyCall'),
            'weather_plugin_manager_options',
            'weather_main_options'
        );

        register_setting('weather_main_options', 'lon', Array('type'=>'number', 'show_in_rest'=>'true', 'default' => 7.5885761));
        add_settings_field(
            'lon',
            'Longitude',
            array($this->obj, 'lonCall'),
            'weather_plugin_manager_options',
            'weather_main_options'
        );

        register_setting('weather_main_options', 'lat', Array('type'=>'number', 'show_in_rest'=>'true', 'default' => 47.5595986));
        add_settings_field(
            'lat',
            'Latitude',
            array($this->obj, 'latCall'),
            'weather_plugin_manager_options',
            'weather_main_options'
        );

        register_setting('weather_main_options', 'unit', Array('type'=>'string', 'show_in_rest'=>'true', 'sanitize_callback', 'default' => 'c'));
        add_settings_field(
            'unit',
            'Unit',
            array($this->obj, 'unitCall'),
            'weather_plugin_manager_options',
            'weather_main_options'
        );
    }
}

class SettingFieldCalls{
    function lonCall(){
        $val = get_option('lon');
        echo "<input type='number' id='lon' name='lon' class='num' value='$val' required /><br />";
    }
    function latCall(){
        $val = get_option('lat');
        echo "<input type='number' id='lat' name='lat' class='num' value='$val' required /><br />";
    }
    function keyCall(){
        $val = get_option('key');
        echo "<input id='key' name='key' type='text' value='$val' required /><br />";
    }
    function unitCall(){
        $val = strtolower(get_option('unit'));
        if($val == "k"){
            echo "<input type='radio' value='k' id='unit' name='unit' class='rad' checked /> <label>Kelvin (°K)</label><br />
                  <input type='radio' value='c' id='unit' name='unit' class='rad' /> <label>Celsius (°C)</label><br />
                  <input type='radio' value='f' id='unit' name='unit' class='rad' /> <label>Fahrenheit (°F)</label><br />";
        } elseif ($val == "c") {
            echo "<input type='radio' value='k' id='unit' name='unit' class='rad' /> <label>Kelvin (°K)</label><br />
                  <input type='radio' value='c' id='unit' name='unit' class='rad' checked /> <label>Celsius (°C)</label><br />
                  <input type='radio' value='f' id='unit' name='unit' class='rad' /> <label>Fahrenheit (°F)</label><br />";
        } else {
            echo "<input type='radio' value='k' id='unit' name='unit' class='rad' /> <label>Kelvin (°K)</label><br />
                  <input type='radio' value='c' id='unit' name='unit' class='rad' /> <label>Celsius (°C)</label><br />
                  <input type='radio' value='f' id='unit' name='unit' class='rad' checked /> <label>Fahrenheit (°F)</label><br />";
        }
    }
    function sectionCall():void{
    }
}