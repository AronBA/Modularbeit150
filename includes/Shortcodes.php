<?php
include "WeatherApi.php";
include "config.php";
class Shortcodes
{
    private WeatherApi $api;
    public function __construct()
    {
        $this->api = WeatherApi::construct(APIKEY,LON,LAT,LANG);
    }
    function weather_enqueue_scripts(): void
    {
        wp_register_style( 'weather-stylesheet',  plugin_dir_url( __FILE__ ) . '../assets/styles.css' );
        wp_enqueue_style( 'weather-stylesheet' );
    }
    function testShortcode(): string
    {
        //pull data from the api

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
        $cityName = $this->api->getCity();
        $country = $this->api->getCountry();
        $temperature = $this->api->getTemperatur("c");
        $weatherDescription = $this->api->getWeatherDescription();
        $weatherIcon = $this->api->getWeatherIcon();
        $iconLink = "http://openweathermap.org/img/wn/$weatherIcon@4x.png";
        return "<div class='wrapWeather'>
                    <div class='weatherBar weatherHeader'>
                        <h2>$cityName, $country</h2>
                    </div>
                    <div class='weatherContent'>
                        <img src='$iconLink' alt='weather icon'/>
                        <h1>$temperature °C</h1>
                    </div>
                    <div class='weatherBar weatherFooter'>
                        <h3>$weatherDescription</h3>
                    </div>
                </div>";
    }

	function sunShortcode() :string{

		$city = $this->api->getCity();
        $dateFormat = "H:i:s";
		$sunrise = date($dateFormat, $this->api->getSunrise());
		$sunset = date($dateFormat, $this->api->getSunset());

		return "<div class='wrapWeather'>
                    <div class='weatherBar weatherHeader'>
                        <h2>$city</h2>
                    </div>
                    <div class='weatherContent'>
                        <h3>Sunrise: $sunrise</h3>
                        <h3>Sunset: $sunset</h3>
                    </div>
                </div>";
	}

	function  smallWeatherShortcode(): string {

		$temperature = $this->api->getTemperatur("c");
		$weatherIcon = $this->api->getWeatherIcon();
		$iconLink = "http://openweathermap.org/img/wn/$weatherIcon@4x.png";
		return "<div style='height: 4rem;border: solid black; border-radius: 15px; width: 12rem; aspect-ratio: 1; display: flex; flex-direction: column; justify-content: space-between; align-items: center; background-color: #6ea7ff'>
                    <div style='display: flex; align-items: center; margin-left: -2rem'>
                    	<div style='flex-basis: 100%; display: flex; align-items: center; flex-direction: row; margin-top: -1rem'>
                    		<img src='$iconLink' alt='weather icon' style='flex-basis: 20%; width: 6rem; height: auto'/>
                    		<h2 style='flex-basis: 50%'>$temperature °C</h2>
						</div>
                    </div>
                </div>";
	}
	function  largeWeatherShortcode(): string {
		$cityName = $this->api->getCity();
		$country = $this->api->getCountry();
		$temperature = $this->api->getTemperatur("c");
		$weatherDescription = $this->api->getWeatherDescription();
		$weatherIcon = $this->api->getWeatherIcon();
		$humidity = $this->api->getHumidity();
		$windspeed = $this->api->getWindSpeed();
		$feels_like = $this->api->getTemperaturFeelslike("c");
		$iconLink = "http://openweathermap.org/img/wn/$weatherIcon@4x.png";
		$iconLink2 = "https://cdn-icons-png.flaticon.com/512/1622/1622158.png";
		$iconLink3 = "https://cdn-icons-png.flaticon.com/512/959/959711.png";
		return "<div style='height: 28rem;border: solid black; border-radius: 15px; width: 35rem; display: flex; flex-direction: column; justify-content: center; align-items: center; background-color: whitesmoke'>
					<h2 style='border-radius: 12px 12px 0 0; height: 4rem; background-color: #6ea7ff; width: 100%; display: flex; justify-content: center; align-items: center; margin-bottom: 2rem; margin-top: 0'>$cityName , $country</h2>
                    <div style='display: flex; align-items: center; margin-left: -2rem;	flex-wrap: wrap;'>
                    	<div style='flex-basis: 50%; display: flex; align-items: center; height: 12rem; flex-direction: column; margin-top: -4rem'>
                    		<img src='$iconLink' style='flex-basis: 100%' alt='weather icon'/>
                    		<h3 style='margin-top: -2rem; flex-basis: 100%; text-align: center; width: 100%'>$weatherDescription</h3>
						</div>
						<div style='flex-basis: 50%; display: flex; align-items: center; height: 12rem; flex-direction: column;'>
							<h1 style='flex-basis: 50%; text-align: center; width: 100%'>Actual: $temperature °C</h1>
							<h1 style='flex-basis: 50%; text-align: center; width: 100%'>Feels like: $feels_like °C</h1>
						</div>
						<div style='flex-basis: 50%; display: flex; justify-content: center; align-items: center; flex-direction: row; margin-top: -1rem; height: 12rem'>
							<img src='$iconLink2' style='width: 5rem; height: auto' alt='weather icon'/>
							<h2 style='flex-basis: 25%; text-align: center; width: 100%'>$humidity%</h2>
						</div>
						<div style='flex-basis: 50%; display: flex; justify-content: center; align-items: center; flex-direction: row; margin-top: -1rem; height: 12rem'>
							<img src='$iconLink3' style='width: 5rem; height: auto' alt='weather icon'/>
							<h2 style='flex-basis: 25%; text-align: center; width: 100%'>$windspeed km/h</h2>
						</div>
                    </div>
                </div>";
    }
    function Wind(): string
    {
        $WSP = $this->api->getWindSpeed();
        $WD = $this->api->getWindDegree();
        $WG = $this->api->getWindGust();
        $iconL = $this->api->getWeatherIcon();
        $icon = "https://cdn-icons-png.flaticon.com/512/2011/2011448.png";
        return "<div class='wrapWeather'>
                    Windgeschwindigkeit: $WSP km/h <br/>
                    Windrichtung: $WD ° <br/>
                    Windböhen: $WG m per s <br/>
                </div>";

	}
}