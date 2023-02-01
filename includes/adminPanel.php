<?php

namespace adminPanel;

class adminPanel
{
    public float $lat = 0;
    public float $lon = 0;
    public string $key = "";
    public string $color = "6666ff";
    public string $unit = "k";
    public string $lang = "en";

    function __construct(){
        $this->defGlobals($this->key, $this->unit, $this->lang, $this->color, $this->lon, $this->lat);
    }

    /**
     * @return void
     * Sets Global values to Inputted values from the Admin Panel
     */
    function setValues(): void{
        if(isset($_POST["key"])){
            $this->key = $_POST["key"];
        }
        if(isset($_POST["lon"])){
            $this->lon = $_POST["lon"];
        }
        if(isset($_POST["lat"])){
            $this->lat = $_POST["lat"];
        }
        if(isset($_POST["lang"])){
            $this->lang = $_POST["lang"];
        }
        if(isset($_POST["col"])){
            $this->color = $_POST["col"];
        }
        if(isset($_POST["unit"])){
            $this->unit = $_POST["unit"];
        }

        $this->defGlobals($this->key, $this->unit, $this->lang, $this->color, $this->lon, $this->lat);
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

    function getAdminPanel(){
        return "
        <div class='adminPanel'>
            <form class='input' method='post' action=''>
            <div class='groupIn'>
                <label for='key'>openWeather API Key</label>
                <input id='key' name='key' type='text' required />
            </div>
            <div class='groupIn'>
                <label for='lon'>Longitude</label>
                <input type='number' id='lon' name='lon' class='num' required />
                <label for='lat'>Latitude</label>
                <input type='number' id='lat' name='lat' class='num' required />
            </div>
            <div class='groupIn'>
                <label for='unit'>Measurement Unit</label>
                <input type='radio' value='Kelvin (°K)' id='unit' name='unit' class='rad' checked />
                <input type='radio' value='Celsius (°C)' id='unit' name='unit' class='rad' />
                <input type='radio' value='Fahrenheit (°F)' id='unit' name='unit' class='rad' />
            </div>
            <div class='groupIn'>
                <label for='lang'>Language</label>
                <select id='lang' name='lang' class='sel' required />
                    <option value='en' selected>English</option>
                    <option value='de'>Deutsch</option>
                </select>
            </div>
            <div class='groupIn'>
            <label for='col'>Color</label>
                <input type='color' id='col' name='col' class='col' required />
            </div>
            
            <button type='submit' class='submit' value='Submit' onclick='this.form.submit()'/>
            </form>
        </div>";
    }
}