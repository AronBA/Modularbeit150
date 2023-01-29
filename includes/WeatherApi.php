<?php

/**
 * A Class which returns data from the API
 */
class WeatherApi
{
    /**
     * @var string $apikey your api key
     * @access protected
     */
    private string $apikey;
    /**
     * @var mixed $response the result of an api call
     * @access protected
     */
    private mixed $response;
    /**
     * Initialize the apikey
     *
     * @param string $apikey your api key
     */
    private function __construct(string $apikey)
    {
        $this->apikey = $apikey;
    }

    /**
     * The Constructor for the Class
     * @param string $apikey
     * @param float $lon
     * @param float $lat
     * @param string $lang
     * @return WeatherApi|false an instance of the WeatherAPI Class or throws a WordPress error
     */
    public static function construct(string $apikey,float $lon, float $lat,string $lang): ?WeatherApi
    {
         $api = new WeatherApi($apikey);
         $api->getData($lon,$lat,$lang);
         if (isset($api->response)){
             return $api;
         }
         else {
             wp_die(__("OOOPS, something went wrong while trying to retrieve the weather data. Please check your API key and location and try again."));
             return false;
         }

    }

    /**
     * Makes a Get Request to the API
     *
     * @param float $lon City geolocation, longitude
     * @param float $lat City geolocation, latitude
     * @param string $lang the language of the result
     * @return mixed all the data from the request in encoded json
     */
    public function getData(float $lon, float $lat,string $lang = "de"): mixed
    {
        $lang = "lang=$lang";
        $response=wp_remote_get("https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&$lang&appid=$this->apikey");
        $json = wp_remote_retrieve_body( $response );
        $result = json_decode($json, true);
        $this->response = $result;
        return $result;
    }
    /**
     * gets the results from a specific section
     *
     * @return mixed all data from the request in encoded json
     */
    private function getDataFrom(string $data): mixed
    {
        return $this->response[$data];
    }
    /**
     * gets the visibility, the max is 10'000
     *
     * @return int the visibility in meters.
     */
    public function getVisibility(): int
    {
        return $this->response["visibility"];
    }
    /**
     * gets percentage amount of clouds
     *
     * @return int amount of clouds in percent
     */
    public function getClouds(): int
    {
        return $this->response["all"];
    }
    /**
     * gets the name of the city
     *
     * @return string name of the city
     */
    public function getCity(): string
    {
        return $this->response["name"];
    }
    /**
     * gets the name of the weather
     *
     * @return string the weather
     */
    public function getWeather(): string
    {
        return $this->getDataFrom("weather")[0]["main"];
    }
    /**
     * gets the description of the weather
     *
     * @return string description the weather
     */
    public function getWeatherDescription(): string
    {
        return $this->getDataFrom("weather")[0]["description"];
    }
    /**
     * gets the icon of the weather
     *
     * @return string the icon of the weather
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
        return $this->getDataFrom("wind")["gust"];
    }
    /**
     * Gets the Temperatur from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celsius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return float|int|null the temperature converted in a specific unit
     */
    public function getTemperatur(string $unit = "k"): float|int|null
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
     * Gets the max Temperatur from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celsius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return float|int|null the max temperature converted in a specific unit
     */
    public function getTemperaturMax(string $unit = "k"): float|int|null
    {
        $temp = $this->getDataFrom("main")["temp_Max"];
        return match ($unit) {
            "k" => round($temp),
            "c" => round($temp - 273.15),
            "f" => round(1.8 * ($temp - 273.15) + 32),
            default => null,
        };
    }

    /**
     * Gets the min Temperatur from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celsius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return float|int|null the min temperature converted in a specific unit
     */
    public function getTemperaturMin(string $unit = "k"): float|int|null
    {
        $temp = $this->getDataFrom("main")["temp_Min"];
        return match ($unit) {
            "k" => round($temp),
            "c" => round($temp - 273.15),
            "f" => round(1.8 * ($temp - 273.15) + 32),
            default => null,
        };
    }

    /**
     * Gets how the Temperatur feels like from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celsius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return float|int|null how the temperature feels like converted in a specific unit
     */
    public function getTemperaturFeelslike(string $unit = "k"): float|int|null
    {
        $temp = $this->getDataFrom("main")["feels_like"];
        return match ($unit) {
            "k" => round($temp),
            "c" => round($temp - 273.15),
            "f" => round(1.8 * ($temp - 273.15) + 32),
            default => null,
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
     * gets the Atmospheric pressure (on the sea level, if there is no sea_level or grnd_level data)
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
    public function getDayTime(): int
    {
        return $this->getDataFrom("dt");
    }
	/**
	 * gets the Sunrise
	 *
	 * @return int returns as UTC Timestamp
	 */
	public function getSunrise(): int
	{
        return $this->getDataFrom("sys")["sunrise"];
	}
	/**
	 * gets the Sunset
	 *
	 * @return int returns as UTC Timestamp
	 */
	public function getSunset(): int
	{
        return $this->getDataFrom("sys")["sunset"];
	}
    /**
     * @return string
     */
    public function getApikey(): string
    {
        return $this->apikey;
    }

    /**
     * @return mixed
     */
    public function getResponse(): mixed
    {
        return $this->response;
    }
    /**
     * @param string $apikey
     */
    public function setApikey(string $apikey): void
    {
        $this->apikey = $apikey;
    }
    /**
     * @param mixed $response
     */
    public function setResponse(mixed $response): void
    {
        $this->response = $response;
    }
}