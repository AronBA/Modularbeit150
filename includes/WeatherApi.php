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
    private $apikey;
    /**
     * @var mixed $result the result of an api call
     * @access protected
     */
    private $result;
    /**
     * Initalize the apikey
     *
     * @param string $apikey your api key
     */
    public function __construct(string $apikey)
    {
        $this->apikey = $apikey;
    }
    /**
     *
     * @param string $method the method of the api call
     * @param string $url the url of the api
     * @param mixed $data data
     * @return bool|string|void the encoded result of an api call
     */
    private function callAPI(string $method, string $url, mixed $data){
        $curl = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "APIKEY: $this->apikey",
            'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
    }

    /**
     * Makes a Get Request to the API
     *
     * @param int $lon City geo location, longitude
     * @param int $lat City geo location, latitude
     * @param string $lang the language of the result
     * @return mixed all the data from the request in encoded json
     */
    public function getData(float $lon, float $lat,string $lang = "de"): mixed
    {
        $lang = "lang=$lang";
        $json=$this->callAPI('GET', "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&$lang&appid=$this->apikey", false);
        $result = json_decode($json, true);
        $this->result = $result;
        return $result;
    }
    /**
     * gets the results from the main section
     *
     * @return mixed all the temperatur data from the request in encoded json
     */
    private function getDataMain(): mixed
    {
        return $this->result["main"];
    }
    /**
     * gets the results from the weather section
     *
     * @return mixed all the weather data from the request in encoded json
     */
    private function getDataWeather(): mixed
    {
        return $this->result["weather"]["0"];
    }
    /**
     * gets the results from the coord section
     *
     * @return mixed all the coord data from the request in encoded json
     */
    private function getDataCoord(): mixed
    {
        return $this->result["coord"];
    }
    /**
     * gets the results from the wind section
     *
     * @return mixed all the wind data from the request in encoded json
     */
    private function getDataWind(): mixed
    {
        return $this->result["wind"];
    }
    /**
     * gets the result from the System section
     *
     * @return mixed all the system data from the request in encoded json
     */
    private function getDataSystem(): mixed
    {
        return $this->result["sys"];
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
        return $this->getDataWeather()["main"];
    }
    /**
     * gets the description of the weather
     *
     * @return string description the weather
     */
    public function getWeatherDescription(): string
    {
        return $this->getDataWeather()["description"];
    }
    /**
     * gets the icon of the weather
     *
     * @return string the icon of the weather
     */
    public function getWeatherIcon(): string
    {
        return $this->getDataWeather()["0"]["icon"];
    }
    /**
     * gets the speed of the wind
     *
     * @return float the speed of the wind meter/sec
     */
    public function getWindSpeed(): float
    {
        return $this->getDataWind()["speed"];
    }
    /**
     * gets the direction of the wind
     *
     * @return float direction in degrees
     */
    public function getWindDegree(): float
    {
        return $this->getDataWind()["deg"];
    }
    /**
     * gets the wind gust
     *
     * @return float the wind gust in meter/second
     */
    public function getWindGust(): float
    {
        return $this->getDataWind()["gust"];
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
        $temp = $this->getDataMain()["temp"];
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
        $temp = $this->getDataMain()["temp_Max"];
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
        $temp = $this->getDataMain()["temp_Min"];
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
        $temp = $this->getDataMain()["feels_like"];
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
        return $this->getDataMain()["humidity"];
    }
    /**
     * gets the Atmospheric pressure (on the sea level, if there is no sea_level or grnd_level data)
     *
     * @return int pressure in hPa
     */
    public function getPressure(): int
    {
        return $this->getDataMain()["pressure"];
    }
    public function getCountry(): string
    {
        return $this->getDataSystem()["country"];
    }
	/**
	 * gets the Sunrise
	 *
	 * @return int returns as UTC Timestamp
	 */
	public function getSunrise(): int
	{
		return $this->getDataSystem()["sunrise"];
	}
	/**
	 * gets the Sunset
	 *
	 * @return int returns as UTC Timestamp
	 */
	public function getSunset(): int
	{
		return $this->getDataSystem()["sunset"];
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










