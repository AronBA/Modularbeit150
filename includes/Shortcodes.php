<?php
include "WeatherApi.php";
class Shortcodes
{
    private WeatherApi $api;
    public function __construct()
    {
        $this->api = new WeatherApi("30b3f538f6eec99807fe916c3f9a4b19");

    }
    function testShortcode(): string
    {
        //pull data from the api
        $this->api->getData(12,12);
        //get temperatur from the result
        $temperatur = $this->api->getTemperatur("c");
        //get city name from the result
        $city = $this->api->getCity();
        //shortcode
        $string = "
        <div>
            <h2>Stadt: $city</h2
            <p>Temperatur: $temperatur °</p>
        </div>";
        return $string;

    }
    function Wind(): string
    {
        $this->api->getData(12,12);
        $WSP = $this->api->getWindSpeed();
        $WD = $this->api->getWindDegree();
        $WG = $this->api->getWindGust();
        $iconL = $this->api->getWeatherIcon();
        $icon = "https://cdn-icons-png.flaticon.com/512/2011/2011448.png";
        $string = "
        <style>
        </style>
        <div style='height: 12rem;border: solid black; border-radius: 12px; width: 18rem; aspect-ratio: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; background-color: whitesmoke'>
        <h3 style='border-radius: 12px 12px 0 0; height: 4rem; background-color: #6ea7ff; width: 100%; display: flex; justify-content: center; align-items: center; margin-bottom: 3rem; margin-top: 0'>Wind    <img src='$icon' style='width: 30px'/> </h3>
        Windgeschwindigkeit: $WSP km/h <br/>
        Windrichtung: $WD ° <br/>
        Windböhen: $WG m per s <br/>
        ";
        return $string;

    }
}