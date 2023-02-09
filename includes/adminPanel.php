<?php
class adminPanel
{

    public static float $lat = 0;
    public static float $lon = 0;
    public static string $key = "";
    public static string $color = "6666ff";
    public static string $unit = "k";
    public static string $lang = "en";

    function saveToFile(): void{
        $config = include 'config.php';
        $config['col'] = adminPanel::$color;
        $config['unit'] = adminPanel::$unit;
        $config['APIKey'] = adminPanel::$key;
        $config['lang'] = adminPanel::$lang;
        $config['lon'] = adminPanel::$lon;
        $config['lat'] = adminPanel::$lat;
        file_put_contents('config.php', '<?php return ' . var_export($config, true) . '; ?>');
    }

    /*
    function pullFromFile(){
        $config = include 'config.php';
        $this->color = $config['col'];
        $this->unit = $config['unit'];
        $this->key = $config['APIKey'];
        $this->lang = $config['lang'];
        $this->lon = $config['lon'];
        $this->lat = $config['lat'];
    }
    */
    function update(): void{
        $this->defGlobals(adminPanel::$key, adminPanel::$unit, adminPanel::$lang, adminPanel::$color, adminPanel::$lon, adminPanel::$lat);

    }

    function __construct(){
        //$this->pullFromFile();
        $this->setValues();
        $this->update();
    }


    /**
     * @return void
     * Sets Global values to Inputted values from the Admin Panel
     */
    function setValues(): void{
        if(isset($_POST["key"])){
            adminPanel::$key = $_POST["key"];
            $this->saveToFile();
        }
        if(isset($_POST["lon"])){
            adminPanel::$lon = $_POST["lon"];
            $this->saveToFile();
        }
        if(isset($_POST["lat"])){
            adminPanel::$lat = $_POST["lat"];
            $this->saveToFile();
        }
        if(isset($_POST["lang"])){
            adminPanel::$lang = $_POST["lang"];
            $this->saveToFile();
        }
        if(isset($_POST["col"])){
            adminPanel::$color = $_POST["col"];
            $this->saveToFile();
        }
        if(isset($_POST["unit"])){
            adminPanel::$unit = $_POST["unit"];
            $this->saveToFile();
        }
        $this->defGlobals(adminPanel::$key, adminPanel::$unit, adminPanel::$lang, adminPanel::$color, adminPanel::$lon, adminPanel::$lat);
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
    public function defGlobals(string $key, string $unit, string $language, string $color, float $longitude, float $latitude){
        $GLOBALS['color'] = $color;
        $GLOBALS['unit'] = $unit;
        $GLOBALS['key'] = $key;
        $GLOBALS['lang'] = $language;
        $GLOBALS['lon'] = $longitude;
        $GLOBALS['lat'] = $latitude;

    }

    /**
     * @return string
     * Returns Admin Panel for Weather plugin
     */
    public function getAdminPanel(){
        $key = adminPanel::$key;
        $color = adminPanel::$color;
        $lang = adminPanel::$lang;
        $lat = adminPanel::$lat;
        $unit = adminPanel::$unit;
        $lon = adminPanel::$lon;
        return "
        <div class='adminPanel'>
            <form class='input' method='post' action=''>
            <div class='groupIn'>
                <label for='key'>openWeather API Key</label>
                <input id='key' name='key' type='text' value='$key' required />
            </div>
            <div class='groupIn'>
                <label for='lon'>Longitude</label>
                <input type='number' id='lon' name='lon' class='num' value='$lon' required />
                <label for='lat'>Latitude</label>
                <input type='number' id='lat' name='lat' class='num' value='$lat' required />
            </div>
            <div class='groupIn'>
                <label for='unit'>Measurement Unit</label>
                <input type='radio' value='Kelvin (°K)' id='unit' name='unit' class='rad' />
                <input type='radio' value='Celsius (°C)' id='unit' name='unit' class='rad' />
                <input type='radio' value='Fahrenheit (°F)' id='unit' name='unit' class='rad' />
            </div>
            <div class='groupIn'>
                <label for='lang'>Language</label>
                <select id='lang' name='lang' class='sel' required />
                    <option value='en'>English</option>
                    <option value='de'>Deutsch</option>
                </select>
            </div>
            <div class='groupIn'>
            <label for='col'>Color</label>
                <input type='color' id='col' name='col' class='col' value='$color' required />
            </div>
            
            <button type='submit' class='submit' value='Submit' onclick='this.form.submit()'/>
            </form>
        </div>";
    }
}