<?php
include "WeatherApi.php";
class Shortcodes
{
    private WeatherApi $api;
    public function __construct()
    {
        $this->api = new WeatherApi("50e3d65a1f02700fcb69068f25d7521c");
        add_shortcode('testWeather', 'testShortcode');
    }
    function testShortcode(): string
    {
        $this->api->getData(12,12);
        $temp = $this->api->getTemperaturFeelslike();
        return "<h1>Temperatur Feels Like:$temp</h1>";

    }
}