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
        $cityName = $this->api->getCity();
        $country = $this->api->getCountry();
        $temperature = $this->api->getTemperatur();
        $weatherDescription = $this->api->getWeatherDescription();
        $weatherIcon = $this->api->getWeatherIcon();
        $iconLink = "http://openweathermap.org/img/wn/$weatherIcon@4x.png";
        return "<div style='border: solid black; border-radius: 15px; width: 500px; aspect-ratio: 1; display: flex; flex-direction: column; justify-content: space-between; align-items: center'>
                    <h2 style='border-radius: 10px 10px 0 0; margin: 0; height: 100px; background-color: #6ea7ff; width: 100%; display: flex; justify-content: center; align-items: center'>$cityName , $country</h2>
                    <div style='display: flex; align-items: center'>
                        <img src='$iconLink' alt='weather icon'/>
                        <h1>$temperature</h1>
                    </div>
                    <h3 style='border-top: solid #6ea7ff; width: 100%; text-align: center; margin: 0; display: grid; place-items: center; height: 80px;'>$weatherDescription</h3>
                </div>";
    }
}