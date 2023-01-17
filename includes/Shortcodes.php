<?php
include "WeatherApi.php";
class Shortcodes
{
    private WeatherApi $api;
    public function __construct()
    {
        $this->api = new WeatherApi("50e3d65a1f02700fcb69068f25d7521c");

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

    function  weatherShortcode(): string {
        $this->api->getData(12, 12);
        $weather = $this->api->getWeather();
        $weatherDescription = $this->api->getWeatherDescription();
        $weatherIcon = $this->api->getWeatherIcon();
        $iconLink = "http://openweathermap.org/img/wn/$weatherIcon@2x.png";
        return "<div><h2>$weather</h2><h3>$weatherDescription</h3><img src='$iconLink' alt='weather icon'/></div>";
    }
}