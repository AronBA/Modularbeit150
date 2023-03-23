<?php
include "WeatherApi.php";
include 'adminPanel.php';
class Shortcodes
{
    private WeatherApi $api;
    public function __construct()
    {
        $this->api = WeatherApi::construct(get_option('key'), get_option('lon'), get_option('lat'), get_option('lang'));
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

    function conditionShortcode(): string {
        $tempUnit = "c";
        $tempUnitUpper = strtoupper($tempUnit);
        $cityName = $this->api->getCity();
        $country = $this->api->getCountry();
        $temperature = $this->api->getTemperature($tempUnit);
        $weatherDescription = $this->api->getWeatherDescription();
        $clouds = $this->api->getClouds();
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
    function windShortcode(): string
    {
        $windSpeed = $this->api->getWindSpeed();
        $windDegree = $this->api->getWindDegree();
        $windGust = $this->api->getWindGust();
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
    {   $city = $this->api->getCity();
        $option = get_option("unit");
        $CurrentTemp = $this->api->getTemperatur(get_option("unit"));
        $MaxTemp = $this->api->getTemperaturMax(get_option("unit"));
        $MinTemp = $this->api->getTemperaturMin(get_option("unit"));
        $FeelsTemp = $this->api->getTemperaturFeelslike(get_option("unit"));
        return "<div class='wrapWeather wrapTemp'>
                    <h2 class='tempTitel'>Temperature - $city</h2>
                    <div class='Tcontainer lightBlur'>
                    Current 🌡️: $CurrentTemp °C<br/>
                    Max 🌡️: $MaxTemp °C <br/>
                    Min 🌡️: $MinTemp °C <br/>
                    Feels like $FeelsTemp °C<br/>
                    </div>
                    <div class='Tcontainer'>
                        <div class='TempU' id='Temp'>$CurrentTemp&deg;C</div>
                    </div>
                    <script>setTemp($CurrentTemp)</script>
                    <script>const option = '<?php echo $option; ?>';</script>

                </div>
";
    }


    // aqi = Air Quality Index
    function aqiShortcode(): string {
	    $aqi = $this->api->getAirQualityIndex();
        $components = $this->api->getAirComponents();
        $co = $components["co"];
        $no = $components["no"];
        $no2 = $components["no2"];
        $o3 = $components["o3"];
        $so2 = $components["so2"];
        $nh3 = $components["nh3"];
	    return "<div class='wrapWeather wrapAQI'>
                    <h2>Air Quality Index (AQI)</h2>
                    <div class='wrapDangerLevels darkBlur'>
                        <h4 id='indexOfAQIDescription'></h4>
                        <div id='indexOfAQI' class='dangerLevels'></div>
                    </div>
                    <select name='components' id='listComponents' onchange='setComponents(event)'>
                        <option value='$co μg/m3'>Carbon Monoxide (CO)</option>
                        <option value='$no μg/m3'>Nitric oxide (NO)</option>
                        <option value='$no2 μg/m3'>Nitrogen dioxide (NO2)</option>
                        <option value='$o3 μg/m3'>Ozone level (O3)</option>
                        <option value='$so2 μg/m3'>Sulfur dioxide (SO2)</option>
                        <option value='$nh3 μg/m3'>Ammonia (NH3)</option>
                    </select>
                    <h3 id='componentsResult'>$co μg/m3</h3>
                    <script>setAQI($aqi)</script>
                    <p>More advice <a href='https://en.wikipedia.org/wiki/Air_quality_index' target='_blank' class='advice'>here</a>.</p>
                </div>";
    }
}