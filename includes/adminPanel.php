<?php

class adminPanel
{
    /**
     * @var float
     * Contains Latitude Coordinate
     **/
    public static float $lat = 0;
    /**
     * @var float
     * Contains Longitude Coordinate
     **/
    public static float $lon = 0;
    /**
     * @var string
     * Contains Api key to Open Weather
     **/
    public static string $key = "";
    /**
     * @var string
     * Contains Hexadecimal color value
     **/
    public static string $color = "6666ff";
    /**
     * @var string
     * Contains short-form character to indicate Measurement System
     **/
    public static string $unit = "k";
    /**
     * @var string
     * Contains Language charachter code
     **/
    public static string $lang = "en";
    /**
     * @var Array
     * Contains languages that are available
     **/
    public static Array $langArr = ['en'=>'English', 'de'=>'Deutsch'];

    /**
     * @return void
     * Saves current settings to the config file
     */
    function saveToFile(): void{
        $config = include 'config.php';
        $config['col'] = self::$color;
        $config['unit'] = self::$unit;
        $config['APIKey'] = self::$key;
        $config['lang'] = self::$lang;
        $config['lon'] = self::$lon;
        $config['lat'] = self::$lat;
        file_put_contents('config.php', '<?php return ' . "\n".'$Settings='.var_export($config, true) . '; ?>');
    }

    /**
     * @return void
     * Gets data from config file
     */
    function pullFromFile():void{
        $config = include 'config.php';
        self::$color = $config['col'];
        self::$unit = $config['unit'];
        self::$key = $config['APIKey'];
        self::$lang = $config['lang'];
        self::$lon = $config['lon'];
        self::$lat = $config['lat'];
    }
    public function __construct(){
        $this->pullFromFile();
    }


    /**
     * @return void
     * Sets static values to Inputted values from the Admin Panel
     */
    function setValues(): void{
        if(isset($_POST["key"])){
            self::$key = $_POST["key"];
        }
        if(isset($_POST["lon"])){
            self::$lon = $_POST["lon"];
        }
        if(isset($_POST["lat"])){
            self::$lat = $_POST["lat"];
        }
        if(isset($_POST["lang"])){
            self::$lang = $_POST["lang"];
        }
        if(isset($_POST["col"])){
            self::$color = $_POST["col"];
        }
        if(isset($_POST["unit"])){
            self::$unit = $_POST["unit"];
        }
        $this->saveToFile();
    }

    /**
     * @param string $key
     * @param string $unit
     * @param string $language
     * @param string $color
     * @param number $longitude
     * @param number $latitude
     * @return void
     */

    /**
     * @return void
     * Echos Admin Panel for Weather plugin
     */
    public function getAdminPanel(): void{
        do_settings_fields('weather_plugin_manager', 'weather_main');
        /*
        $this->setValues();
        $key = self::$key;
        $lat = self::$lat;
        $lon = self::$lon;
        $lang = self::$lang;
        $unit = strtolower(self::$unit);
        $color = self::$color;
        $echoVal  = "
        <div class='adminPanel'>
            <form class='input' method='post'>
            <div class='groupIn'>
                <label for='key'>openWeather API Key</label><br />
                <input id='key' name='key' type='text' value='$key' required />
            </div><br />
            <div class='groupIn'>
                <label for='lon'>Longitude</label><br />
                <input type='number' id='lon' name='lon' class='num' value='$lon' required /><br />
                <label for='lat'>Latitude</label><br />
                <input type='number' id='lat' name='lat' class='num' value='$lat' required />
            </div><br />
            <div class='groupIn'>
                <label for='unit'>Measurement Unit</label><br /> ";
                if($unit == "k"){
                    $echoVal = $echoVal."<input type='radio' value='k' id='unit' name='unit' class='rad' checked /> <label>Kelvin (°K)</label><br />
                    <input type='radio' value='c' id='unit' name='unit' class='rad' /> <label>Celsius (°C)</label><br />
                    <input type='radio' value='f' id='unit' name='unit' class='rad' /> <label>Fahrenheit (°F)</label><br />";
                } elseif ($unit == "c") {
                    $echoVal = $echoVal."<input type='radio' value='k' id='unit' name='unit' class='rad' /> <label>Kelvin (°K)</label><br />
                    <input type='radio' value='c' id='unit' name='unit' class='rad' checked /> <label>Celsius (°C)</label><br />
                    <input type='radio' value='f' id='unit' name='unit' class='rad' /> <label>Fahrenheit (°F)</label><br />";
                } else {
                    $echoVal = $echoVal."<input type='radio' value='k' id='unit' name='unit' class='rad' /> <label>Kelvin (°K)</label><br />
                     <input type='radio' value='c' id='unit' name='unit' class='rad' /> <label>Celsius (°C)</label><br />
                    <input type='radio' value='f' id='unit' name='unit' class='rad' checked /> <label>Fahrenheit (°F)</label><br />";
                }
                $echoVal = $echoVal."
            </div><br />
            <div class='groupIn'>
                <label for='lang'>Language</label><br />
                <select id='lang' name='lang' class='sel' required />";
                foreach (self::$langArr as $lan=>$lan_val){
                    if ($lang == $lan) {
                        $echoVal = $echoVal . "<option value='$lan' selected>$lan_val</option>";
                    } else {
                        $echoVal = $echoVal."<option value='$lan'>$lan_val</option>";
                    }
                }
        $echoVal = $echoVal."
                </select>
            </div><br />
            <div class='groupIn'>
                <label for='col'>Color</label><br />
                <input type='color' id='col' name='col' class='col' value='$color' required />
            </div><br />
            <button type='submit' class='submit' value='Submit' onclick='this.form.submit()'>Submit</button>
            </form>
        </div>";
        echo $echoVal;
        */
    }
    function WeatherPSettings(){
        echo 'Edit values to be used in Shortcodes';
    }
    function addSettings(): void
    {
        add_options_page('Weather Plugin', 'Weather Plugin', 'manage_options', 'weather_options_page');

        add_settings_section('weather_main', 'Weather Plugin Settings', 'WeatherPSettings', 'weather_plugin_manager');

        register_setting('options_weather', 'key', Array('type'=>'string', 'show_in_rest'=>'true', 'default' => 'default'));
        register_setting('options_weather', 'lon', Array('type'=>'number', 'show_in_rest'=>'true', 'default' => 7.5885761));
        register_setting('options_weather', 'lat', Array('type'=>'number', 'show_in_rest'=>'true', 'default' => 47.5595986));
        register_setting('options_weather', 'lang', Array('type'=>'string', 'show_in_rest'=>'true', 'default' => 'en'));
        register_setting('options_weather', 'col', Array('type'=>'string', 'show_in_rest'=>'true', 'default' => '#ffffff'));
        register_setting('options_weather', 'unit', Array('type'=>'string', 'show_in_rest'=>'true', 'sanitize_callback', 'default' => 'c'));

        add_settings_field(
            'key',
            'API Key',
            'keyCall',
            'Weather Manager',
            'weather_main',
            array('label-for'=>'Key')
        );
        add_settings_field(
            'lon',
            'Longitude',
            'LonCall',
            'Weather Manager',
            'weather_main',
            array('label-for'=>'Longitude')
            );
        add_settings_field(
            'lat',
            'Latitude',
            'LatCall',
            'Weather Manager',
            'weather_main',
            array('label-for'=>'Latitude')
        );
        add_settings_field(
            'unit',
            'Units',
            'unitCall',
            'Weather Manager',
            'weather_main',
            array('label-for'=>'Unit')
        );
        add_settings_field(
            'lang',
            'Language',
            'LangCall',
            'Weather Manager',
            'weather_main',
            array('label-for'=>'Language')
        );
        add_settings_field(
            'col',
            'Colour',
            'colCall',
            'Weather Manager',
            'weather_main',
            array('label-for'=>'Colour')
        );
    }
    function LonCall(): void{
        $val = get_option('lon');
        echo "<input type='number' id='lon' name='lon' class='num' value='$val' required />";
    }
    function LatCall(): void{
        $val = get_option('lat');
        echo "<input type='number' id='lat' name='lat' class='num' value='$val' required />";
    }
    function keyCall(): void{
        $val = get_option('key');
        echo "<input id='key' name='key' type='text' value='$val' required />";
    }
    function unitCall(): void{
        $val = strtolower(get_option('unit'));
        if($val == "k"){
            echo "<input type='radio' value='k' id='unit' name='unit' class='rad' checked /> <label>Kelvin (°K)</label><br />
                    <input type='radio' value='c' id='unit' name='unit' class='rad' /> <label>Celsius (°C)</label><br />
                    <input type='radio' value='f' id='unit' name='unit' class='rad' /> <label>Fahrenheit (°F)</label>";
        } elseif ($val == "c") {
            echo "<input type='radio' value='k' id='unit' name='unit' class='rad' /> <label>Kelvin (°K)</label><br />
                    <input type='radio' value='c' id='unit' name='unit' class='rad' checked /> <label>Celsius (°C)</label><br />
                    <input type='radio' value='f' id='unit' name='unit' class='rad' /> <label>Fahrenheit (°F)</label>";
        } else {
            echo "<input type='radio' value='k' id='unit' name='unit' class='rad' /> <label>Kelvin (°K)</label><br />
                     <input type='radio' value='c' id='unit' name='unit' class='rad' /> <label>Celsius (°C)</label><br />
                    <input type='radio' value='f' id='unit' name='unit' class='rad' checked /> <label>Fahrenheit (°F)</label>";
        }
    }
    function colCall(): void{
        $val = get_option('col');
        echo "<input type='color' id='col' name='col' class='col' value='$val' required />";
    }
    function langCall(): void{
        $val = get_option('lang');
        $echoVal = "<select id='lang' name='lang' class='sel' required />";
        foreach (self::$langArr as $lan=>$lan_val){
            if ($val == self::$lang) {
                $echoVal = $echoVal . "<option value='$lan' selected>$lan_val</option>";
            } else {
                $echoVal = $echoVal."<option value='$lan'>$lan_val</option>";
            }
        };
        echo $echoVal;
    }
}