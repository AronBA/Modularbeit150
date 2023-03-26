<?php
include "WeatherApi.php";
include 'adminPanel.php';
class Shortcodes
{
    private WeatherApi $api;
    private string $error;
    public function __construct()
    {
        try {
            $api = WeatherApi::construct(get_option('key'), get_option('lon'), get_option('lat'), get_option('lang'));
            if(is_wp_error($api)){
                $errormsg = $api->get_error_message();
                $this->error = "<div class='wrapWeather wrapCondition'> $errormsg </div>";
            } else {
                $this->api = $api;
            }
        } catch (Error $errormsg){
            $this->error = "<div class='wrapWeather wrapCondition'>If you see this you are probably trying to break things on purpose: <br> $errormsg  </div>";
        }



    }
    function weather_enqueue_scripts(): void
    {
        wp_register_style( 'weather-stylesheet',  plugin_dir_url( __FILE__ ) . '../assets/styles.css' );
        wp_register_script( 'weather-scripts',  plugin_dir_url( __FILE__ ) . '../assets/scripts.js', array(), '1.0.0', false);
        wp_enqueue_style( 'weather-stylesheet' );
        wp_enqueue_script( 'weather-scripts' );
    }
    function handleError($error): string {
        if(isset($this->error)){
            return $this->error . "<br>";
        }
        return "There was an unexpected error" . $error;
    }
    function conditionShortcode(): string {
        try {
            $tempUnit = get_option("unit");
            $tempUnitUpper = strtoupper($tempUnit);
            $cityName = $this->api->getCity();
            $country = $this->api->getCountry();
            $temperature = $this->api->getTemperature($tempUnit);
            $weatherDescription = ucfirst($this->api->getWeatherDescription());
            $clouds = $this->api->getClouds();
        } catch (Error $error){
            return $this->handleError($error);
        }

        return "<div class='wrapWeather wrapCondition'>
                    <div class='conditionBackground conditionSky'>                        
                    </div>
                    <div id='sunIcon' class='sunIcon'></div>
                    <div class='conditionBackground conditionClouds'>
                    </div>
                    <div class='conditionContent'>
                        <div>
                            <h2>$cityName, $country</h2>
                        </div>
                        <div>
                            <h1>$temperature °$tempUnitUpper</h1>
                        </div>
                        <div>
                            <h2>$weatherDescription</h2>
                        </div>
                    </div>
                    <script>setCondition($clouds)</script>
                </div>";
    }
	function sunShortcode(): string {
        try {
            $city = $this->api->getCity();
            $timeInt = $this->api->getDayTime();
            $sunriseInt = $this->api->getSunrise();
            $sunsetInt = $this->api->getSunset();
            $dateFormat = "h:i a";
            $time = date($dateFormat, $timeInt);
            $sunrise = date($dateFormat, $sunriseInt);
            $sunset = date($dateFormat, $sunsetInt);
        } catch (Error $error){
            return $this->handleError($error);
        }

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
						currentTime.innerHTML = date.toLocaleTimeString();
					}, 1000);
				</script>
			</div>";
	}
    function windShortcode(): string
    {
        try {
            $windSpeed = $this->api->getWindSpeed();
            $windDegree = $this->api->getWindDegree();
            $windGust = $this->api->getWindGust();
        } catch (Error $error){
            return $this->handleError($error);
        }

        return "<div class='wrapWeather wrapWind'>
                    <h2>Windspeed - $windSpeed m/s</h2>
                    <div class='wrapDangerLevels darkBlur'>
                        <h4 id='dangerLevelsDescription'></h4>
                        <div id='dangerLevels' class='dangerLevels'></div>
                    </div>
                    <div class='wrapWindSub'>
                        <div class='wrapSub darkBlur' title='Direction of the Wind'>
                            <h3>" . $windDegree . "°</h3>
                            <div class='compas'><div id='arrow' class='arrow'></div></div>
                        </div>
                        <div class='wrapSub darkBlur' title='Speed of Wind Gusts'>
                            <h4>Wind Gusts</h4>
                            <h3>$windGust m/s</h3>
                        </div>
                    </div>
                    <script>setDangerLevels($windSpeed)</script>
                    <script>setArrow($windDegree)</script>
                </div>";
	}
    function temparatureShortcode(): string
    {
        try {
            $city = $this->api->getCity();
            $option = get_option("unit");
            $CurrentTemp = $this->api->getTemperature($option);
            $MaxTemp = $this->api->getTemperatureMax($option);
            $MinTemp = $this->api->getTemperatureMin($option);
            $FeelsTemp = $this->api->getTemperatureFeelslike($option);
        } catch (Error $error){
            return $this->handleError($error);
        }

        return "<div class='wrapWeather wrapTemp'>
                    <h2 class='tempTitel'>Temperature - $city</h2>
                    <div class='Tcontainer lightBlur'>
                    Current 🌡️: $CurrentTemp °$option<br/>
                    Max 🌡️: $MaxTemp °$option <br/>
                    Min 🌡️: $MinTemp °$option <br/>
                    Feels like $FeelsTemp °$option<br/>
                    </div>
                    <div class='Tcontainer'>
                        <div class='TempU' id='Temp'>$CurrentTemp&deg;$option</div>
                    </div>
                    <script>setTemp($CurrentTemp,'$option')</script>
                </div>";
    }

    // aqi = Air Quality Index
    function aqiShortcode(): string {
        try {
            $aqi = $this->api->getAirQualityIndex();
            $components = $this->api->getAirComponents();
            $co = $components["co"];
            $no = $components["no"];
            $no2 = $components["no2"];
            $o3 = $components["o3"];
            $so2 = $components["so2"];
            $nh3 = $components["nh3"];
        } catch (Error $error){
            return $this->handleError($error);
        }
	    return "<div class='wrapWeather wrapAQI'>
                    <h2>Air Quality Index (AQI)</h2>
                    <div class='wrapDangerLevels darkBlur'>
                        <h4 id='indexOfAQIDescription'></h4>
                        <div id='indexOfAQI' class='dangerLevels'></div>
                    </div>
                    <div class='wrapComponents darkBlur'>
                        <select name='components' id='listComponents' onchange='setComponents(event)'>
                            <option value='$co μg/m3'>Carbon Monoxide (CO)</option>
                            <option value='$no μg/m3'>Nitric oxide (NO)</option>
                            <option value='$no2 μg/m3'>Nitrogen dioxide (NO2)</option>
                            <option value='$o3 μg/m3'>Ozone level (O3)</option>
                            <option value='$so2 μg/m3'>Sulfur dioxide (SO2)</option>
                            <option value='$nh3 μg/m3'>Ammonia (NH3)</option>
                        </select>
                        <h3 id='componentsResult'>$co μg/m3</h3>
                    </div>
                    <script>setAQI($aqi)</script>
                </div>";
    }
}