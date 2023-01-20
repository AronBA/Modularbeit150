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
     * @var mixed $result the result of an api call
     * @access protected
     */
    private mixed $result;
    /**
     * Initalize the apikey
     *
     * @param string $apikey your api key
     */
    private function __construct(string $apikey)
    {
        $this->apikey = $apikey;
    }

    /**
     *
     * @param string $apikey
     * @param float $lon
     * @param float $lat
     * @param string $lang
     * @return WeatherApi|false an instance of the WeatherAPI Class or false if no result
     */
    public static function construct(string $apikey,float $lon, float $lat,string $lang): ?WeatherApi
    {
         $api = new WeatherApi($apikey);
         $api->getData($lon,$lat,$lang);
         if (isset($api->result)){
             return $api;
         }
         return false;
    }

    /**
     * Makes a Get Request to the API
     *
     * @param float $lon City geo location, longitude
     * @param float $lat City geo location, latitude
     * @param string $lang the language of the result
     * @return mixed all the data from the request in encoded json
     */
    public function getData(float $lon, float $lat,string $lang = "de"): mixed
    {
        $lang = "lang=$lang";
        $response=wp_remote_get("https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&$lang&appid=$this->apikey");
        $json = wp_remote_retrieve_body( $response );
        $result = json_decode($json, true);
        $this->result = $result;
        return $result;
    }
    /**
     * gets the results from the main section
     *
     * @return mixed all the temperatur data from the request in encoded json
     */
    private function getDataFrom(string $data): mixed
    {
        return $this->result[$data];
    }
    /**
     * gets the visibilty, the max is 10'000
     *
     * @return int the visibility in meters.
     */
    public function getVisibility(): int
    {
        return $this->result["visibility"];
    }
    /**
     * gets procentage amount of clouds
     *
     * @return int amount of clouds in procent
     */
    public function getClouds(): int
    {
        return $this->result["all"];
    }
    /**
     * gets the name of the city
     *
     * @return string name of the city
     */
    public function getCity(): string
    {
        return $this->result["name"];
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
     * @return float|int|null the temperatur converted in a specific unit
     */
    public function getTemperatur(string $unit = "k"): float|int|null
    {
        $temp = $this->getDataFrom("main")["temp"];
        return match ($unit) {
            "k" => $temp,
            "c" => $temp - 273.15,
            "f" => 1.8 * ($temp - 273.15) + 32,
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
     * @return float|int|null the max temperatur converted in a specific unit
     */
    public function getTemperaturMax(string $unit = "k"): float|int|null
    {
        $temp = $this->getDataFrom("main")["temp_Max"];
        return match ($unit) {
            "k" => $temp,
            "c" => $temp - 273.15,
            "f" => 1.8 * ($temp - 273.15) + 32,
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
     * @return float|int|null the min temperatur converted in a specific unit
     */
    public function getTemperaturMin(string $unit = "k"): float|int|null
    {
        $temp = $this->getDataFrom("main")["temp_Min"];
        return match ($unit) {
            "k" => $temp,
            "c" => $temp - 273.15,
            "f" => 1.8 * ($temp - 273.15) + 32,
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
     * @return float|int|null how the temperatur feels like converted in a specific unit
     */
    public function getTemperaturFeelslike(string $unit = "k"): float|int|null
    {
        $temp = $this->getDataFrom("main")["feels_like"];
        return match ($unit) {
            "k" => $temp,
            "c" => $temp - 273.15,
            "f" => 1.8 * ($temp - 273.15) + 32,
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
    public function getResult(): mixed
    {
        return $this->result;
    }
    /**
     * @param string $apikey
     */
    public function setApikey(string $apikey): void
    {
        $this->apikey = $apikey;
    }
    /**
     * @param mixed $result
     */
    public function setResult(mixed $result): void
    {
        $this->result = $result;
    }
}