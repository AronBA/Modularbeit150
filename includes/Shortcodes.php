<?php
include "WeatherApi.php";
include 'adminPanel.php';
class Shortcodes
{
    private WeatherApi $api;
    public function __construct()
    {
        $this->api = WeatherApi::construct(adminPanel::$key, adminPanel::$lon, adminPanel::$lat, adminPanel::$lang);
    }
    function weather_enqueue_scripts(): void
    {
        wp_register_style( 'weather-stylesheet',  plugin_dir_url( __FILE__ ) . '../assets/styles.css' );
        wp_register_script( 'weather-scripts',  plugin_dir_url( __FILE__ ) . '../assets/scripts.js', array(), '1.0.0', false);
        wp_enqueue_style( 'weather-stylesheet' );
        wp_enqueue_script( 'weather-scripts' );
    }
    function testShortcode(): string
    {
        //pull data from the api

        //get temperatur from the result
        $temperatur = $this->api->getAirQualityIndex();
        //get city name from the result
        $city = $this->api->getCity();
		$co = $this->api->getAirCompenents()["co"];
        //shortcode
        $string = "
        <div>
            <h2>Stadt: $city</h2>
            <p>Air Quality index: $temperatur °</p>
             <p>amount of bullshit in the air: $co °</p>
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

	function sunShortcode() :string {
		$city = $this->api->getCity();
		$timeInt = $this->api->getDayTime();
		$sunriseInt = $this->api->getSunrise();
		$sunsetInt = $this->api->getSunset();
		$dateFormat = "h:i a";
		$time = date($dateFormat, $timeInt);
		$sunrise = date($dateFormat, $sunriseInt);
		$sunset = date($dateFormat, $sunsetInt);
		return "<div class='wrapWeather wrapSun' id='sunWeather'>
				<h2>$city</h2>
				<div id='progressWeather'></div>
				<div class='sunTime'>
					<div>
						<h3>$sunrise</h3>
						<h4>Sunrise</h4>
					</div>
					<div>
						<h3 id='currentTime'>$time</h3>
						<h4>Current Time</h4>
					</div>                        
					<div>
						<h3>$sunset</h3>
						<h4>Sunset</h4>
					</div>
				</div>
				<script>setSun('$timeInt', '$sunriseInt', '$sunsetInt')</script>
				<script>setProgress('$timeInt')</script>
				<script>
					setInterval(function() {
						const currentTime = document.getElementById('currentTime');
						const date = new Date();
						const time = date.toLocaleTimeString();
						currentTime.innerHTML = time;
					}, 1000);
				</script>
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
		return "<div style='height: 28rem;border: solid black; border-radius: 15px; width: 35rem; aspect-ratio: 1; display: flex; flex-direction: column; justify-content: center; align-items: center; background-color: lightcyan'>
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
    function windShortcode(): string
    {
        $windSpeed = $this->api->getWindSpeed();
        $windDegree = $this->api->getWindDegree();
        $windGust = $this->api->getWindGust();
        $iconL = $this->api->getWeatherIcon();
        $icon = "https://cdn-icons-png.flaticon.com/512/2011/2011448.png";
        return "<div class='wrapWeather wrapWind'>
                    <h2>Windspeed - $windSpeed m/s</h2>
                    <div class='wrapDangerLevels'>
                        <h4 id='dangerLevelsDescription'></h4>
                        <div id='dangerLevels' class='dangerLevels'></div>
                    </div>
                    Windrichtung: $windDegree ° <br/>
                    Windböhen: $windGust m/s <br/>
                    <div class='compas'><div id='arrow' class='arrow'></div></div>
                    <script>setDangerLevels($windSpeed)</script>
                    <script>setArrow($windDegree)</script>";
	}
    function temparatureShortcode(): string
    {
        $CurrentTemp = $this->api->getTemperatur("c");
        $MaxTemp = $this->api->getTemperaturMax("c");
        $MinTemp = $this->api->getTemperaturMin("c");
        $FeelsTemp = $this->api->getTemperaturFeelslike("c");
        return "<div class='tempWeather'>
                    <div class='tempTitel'>Temparatur</div>
                    Temparatur momentan: $CurrentTemp °C<br/>
                    Min Temparatur: $MaxTemp °C <br/>
                    Max Temparatur: $MinTemp ° C <br/>
                    Fühlt sich wie $FeelsTemp ° C an<br/>
                </div>";
    }

    // aqi = Air Quality Index
    function aqiShortcode(): string {
	    $aqi = $this->api->getAirQualityIndex();
	    switch ($aqi) {
		    case 1:
			    $aqi = 'Sehr gut';
			    break;
		    case 2:
			    $aqi = 'Gut';
			    break;
		    case 3:
			    $aqi = 'Mittel';
			    break;
		    case 4:
			    $aqi = 'Schlecht';
			    break;
		    case 5:
			    $aqi = 'Sehr schlecht';
			    break;
	    }
	    $windSpeed = $this->api->getWindSpeed();
	    $windDegree = $this->api->getWindDegree();
	    return "<div class='wrapWeather wrapAQI'>
                    <h2>Luftqualität - $aqi</h2>
                    <div class='wrapDangerLevels'>
                        <h4 id='dangerLevelsDescription'></h4>
                        <div id='dangerLevels' class='dangerLevels'></div>
                    </div>
                    <div class='iconAQI'></div>
                    <script>setDangerLevels($windSpeed)</script>";
    }
}