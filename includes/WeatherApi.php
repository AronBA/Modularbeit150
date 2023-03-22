<?php

/**
 * A Class which returns data from the API
 */
class WeatherApi
{
    /**
     * @var string $apikey the api key
     * @access private
     */

    private string $apikey;
    /**
     * @var mixed $responseWeatherAPI the result of a WeatherAPI call
     * @access private
     */
    private mixed $responseWeatherAPI;

    /**
     * @var mixed $responsePollutionAPI the result of a PollutionAPI call
     * @access private
     */
    private mixed $responsePollutionAPI;

    /**
     * Initialize the APIkey
     *
     * @param string $apikey the APIkey
     */
    private function __construct(string $apikey)
    {
        $this->apikey = $apikey;
    }

    /**
     * The Constructor for the Class, throws an error if the connection fails
     * @param string $apikey the API key
     * @param float $lon the longitude of the location
     * @param float $lat the latitude of the location
     * @param string $lang the language of the result
     * @return WeatherApi|false <br> an instance of the WeatherAPI Class or throws a WordPress error
     */
    public static function construct(string $apikey,float $lon, float $lat,string $lang): ?WeatherApi
    {
         $api = new WeatherApi($apikey);

         if ($api->getData($lon,$lat,$lang)){
             return $api;
         }
         else {
             wp_die(__("Oh no! something went wrong while trying to retrieve the weather data. Please check your API key and location and try again."));
             return false;
         }

    }
    /**
     * Makes a Get Request to the all APIs and stores the response.
     *
     * @param float $lon City geolocation, longitude
     * @param float $lat City geolocation, latitude
     * @param string $lang the language of the result
     *
     * @return bool returns false on error
     */
    public function getData(float $lon, float $lat,string $lang = "de"): bool
    {
        $responseWeatherAPI=wp_remote_get("https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&lang=$lang&appid=$this->apikey");
        $responsePollutionAPI=wp_remote_get("https://api.openweathermap.org/data/2.5/air_pollution?lat=$lat&lon=$lon&appid=$this->apikey");

        $jsonWeatherAPI = wp_remote_retrieve_body($responseWeatherAPI);
        $jsonPollutionAPI = wp_remote_retrieve_body($responsePollutionAPI);
        $resultWeatherAPI = json_decode($jsonWeatherAPI, true);
        $resultPollutionAPI = json_decode($jsonPollutionAPI, true);

	    $this->responseWeatherAPI = $resultWeatherAPI;
	    $this->responsePollutionAPI = $resultPollutionAPI;

		if(!$this->responseWeatherAPI && $this->responsePollutionAPI){
			return false;
		}
		return true;
    }

    /**
     * gets the results from a specific section
     *
     * @return mixed all data from the request in encoded json
     */
    private function getDataFrom(string $data, string $api="WeatherAPI"): mixed
    {
        try {
            if($api == "PollutionAPI"){
                return $this->responsePollutionAPI[$data];
            } else  {
                return $this->responseWeatherAPI[$data];
            }
        }
        catch (Error) {
            return null;
        }
    }

    /**
     * gets the timezone
     *
     * @return int the timezone in ms
     */
    private function getTimeZone(): int
    {
        return $this->getDataFrom("timezone");
    }

    /**
     * gets the visibility, the max is 10'000
     *
     * @return int the visibility in meters.
     */
    public function getVisibility(): int
    {
        return $this->responseWeatherAPI["visibility"];
    }

    /**
     * gets percentage amount of cloud coverage
     *
     * @return int cloud coverage in percent
     */
    public function getClouds(): int
    {
        return $this->getDataFrom("clouds")["all"];
    }

    /**
     * gets the name of the city
     *
     * @return string name of the city
     */
    public function getCity(): string
    {
        return $this->responseWeatherAPI["name"];
    }

    /**
     * gets the designation of the current weather
     *
     * @return string the designation of the current weather
     */
    public function getWeather(): string
    {
        return $this->getDataFrom("weather")[0]["main"];
    }
    
    /**
     * gets the description of the current weather
     *
     * @return string description the current weather
     */
    public function getWeatherDescription(): string
    {
        return $this->getDataFrom("weather")[0]["description"];
    }
    
    /**
     * gets the icon name of the weather. Used for displaying the correct weather icon.
     *
     * @return string the name of the icon
     */
    public function getWeatherIcon(): string
    {
        return $this->getDataFrom("weather")[0]["icon"];
    }
    
    /**
     * gets the speed of the wind
     *
     * @return float the speed of the wind meter/sec
     */
    public function getWindSpeed(): float
    {
        return $this->getDataFrom("wind")["speed"];
    }
    
    /**
     * gets the direction of the wind
     *
     * @return float direction in degrees
     */
    public function getWindDegree(): float
    {
        return $this->getDataFrom("wind")["deg"];
    }

    /**
     * gets the wind gust
     *
     * @return float the wind gust in meter/second
     */
    public function getWindGust(): float
    {
        if (isset($this->getDataFrom("wind")["gust"])) return $this->getDataFrom("wind")["gust"];
        return 0;
    }
    
    /**
     * Gets the temperature from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celsius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return float|int|null the temperature converted in a specific unit
     */
    public function getTemperature(string $unit = "k"): float|int|null
    {
        $temp = $this->getDataFrom("main")["temp"];
        return match ($unit) {
            "k" => round($temp),
            "c" => round($temp - 273.15),
            "f" => round(1.8 * ($temp - 273.15) + 32),
            default => null,
        };
    }

    /**
     * Gets the max temperature from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celsius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return float|int|null the max temperature converted in a specific unit
     */
    public function getTemperatureMax(string $unit = "k"): float|int|null
    {
        $temp = $this->getDataFrom("main")["temp_max"];
        return match ($unit) {
            "k" => round($temp),
            "c" => round($temp - 273.15),
            "f" => round(1.8 * ($temp - 273.15) + 32),
            default => null,
        };
    }

    /**
     * Gets the min temperature from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celsius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return float|int|null the min temperature converted in a specific unit
     */
    public function getTemperatureMin(string $unit = "k"): float|int|null
    {
        $temp = $this->getDataFrom("main")["temp_min"];
        return match ($unit) {
            "k" => round($temp),
            "c" => round($temp - 273.15),
            "f" => round(1.8 * ($temp - 273.15) + 32),
            default => null,
        };
    }

    /**
     * Gets how the temperature feels like from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celsius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return float|int|null how the temperature feels like converted in a specific unit
     */
    public function getTemperatureFeelsLike(string $unit = "k"): float|int|null
    {
        $temp = $this->getDataFrom("main")["feels_like"];
        return match ($unit) {
            "k" => round($temp),
            "c" => round($temp - 273.15),
            "f" => round(1.8 * ($temp - 273.15) + 32),
            default => null
        };
    }

    /**
     * gets the humidity in %
     *
     * @return int humidity in %
     */
    public function getHumidity(): int
    {
        return $this->getDataFrom("main")["humidity"];
    }

    /**
     * gets the Atmospheric pressure (on the sea level, if there is no sea_level or ground_level data)
     *
     * @return int pressure in hPa
     */
    public function getPressure(): int
    {
        return $this->getDataFrom("main")["pressure"];
    }

    /**
     * gets the Country name
     *
     * @return string Country name as string
     */
    public function getCountry(): string
    {
        return $this->getDataFrom("sys")["country"];
    }

    /**
     * gets the current time
     *
     * @return int the current time in ms
     */
    public function getDayTime(): int {
        return time() + $this->getTimeZone();
    }

	/**
	 * gets the Sunrise
	 *
	 * @return int returns as UTC Timestamp
	 */
	public function getSunrise(): int
	{
        return $this->getDataFrom("sys")["sunrise"] + $this->getTimeZone();
	}

	/**
	 * gets the Sunset
	 *
	 * @return int returns as UTC Timestamp
	 */
	public function getSunset(): int
	{
        return $this->getDataFrom("sys")["sunset"] + $this->getTimeZone();
	}

    /**
     * gets the AirQualityIndex
     * 1 = Good
     * 2 = Fair
     * 3 = Moderate
     * 4 = Poor
     * 5 = Very Poor
     *
     * @return int returns the AQI as int
     */
    public function getAirQualityIndex(): int {
        return $this->getDataFrom("list","PollutionAPI")[0]["main"]["aqi"];
    }

    /**
     * gets the components of the air in an array. All units in μg/m3
     *
     * [co] => 201.94053649902344       Carbon monoxide <br>
     * [no] => 0.01877197064459324      Nitrogen monoxide <br>
     * [no2] => 0.7711350917816162      Nitrogen dioxide <br>
     * [o3] => 68.66455078125           Ozone <br>
     * [so2] => 0.6407499313354492      Sulphur dioxide <br>
     * [pm2_5] => 0.5                   Fine particles matter <br>
     * [pm10] => 0.540438711643219      Coarse particulate matter <br>
     * [nh3] => 0.12369127571582794     Ammonia <br>
     *
     * @return array returns an array of the components and their concentration
     */

    public function getAirComponents(): array
    {
        return $this->getDataFrom("list","PollutionAPI")[0]["components"];
    }

    /**
     * returns the APIkey
     * @return string
     */
    public function getApikey(): string
    {
        return $this->apikey;
    }

    /**
     * returns the last saved WeatherAPI call
     * @return mixed
     */
    public function getResponseWeatherAPI(): mixed
    {
        return $this->responseWeatherAPI;
    }
    /**
     * sets a new APIkey
     * @param string $apikey
     */
    public function setApikey(string $apikey): void
    {
        $this->apikey = $apikey;
    }
    /**
     * @param mixed $responseWeatherAPI
     */
    public function setResponseWeatherAPI(mixed $responseWeatherAPI): void
    {
        $responseWeatherAPI->response = $responseWeatherAPI;
    }

    /**
     * returns the last saved PollutionAPI call
     * @return mixed
     */
    public function getResponsePollutionAPI(): mixed
    {
        return $this->responsePollutionAPI;
    }

    /**
     * @param mixed $responsePollutionAPI
     */
    public function setResponsePollutionAPI(mixed $responsePollutionAPI): void
    {
        $this->responsePollutionAPI = $responsePollutionAPI;
    }
}