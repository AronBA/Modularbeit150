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
        $feellike = $this->api->getTemperaturFeelslike("c");
        $realtemo = $this->api->getTemperatur("c");
        $string = "<div>
<h2>Thermometer</h2>
Fühlt sich an wie: $feellike °
Ist in wirklichkeit: $realtemo °
</div>";
        return $string;

    }
}