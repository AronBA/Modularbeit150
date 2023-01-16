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
    private function callAPI($method,$url,$data){
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
     * @param    int               $lon         City geo location, longitude
     * @param    int               $lat        City geo location, latitude
     * @return mixed all the data from the request in encoded json
     */
    public function getData(int $lon, int $lat){
        $json=$this->callAPI('GET', "https://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=$this->apikey", false);
        $result = json_decode($json, true);
        $this->result = $result;
        return $result;
    }
    /**
     * gets the results from the main section
     *
     * @return mixed all the temperatur data from the request in encoded json
     */
    private function getDataMain(){
        return $this->result["main"];
    }
    /**
     * gets the results from the weather section
     *
     * @return mixed all the weather data from the request in encoded json
     */
    private function getDataWeather(){
        return $this->result["weather"];
    }
    /**
     * gets the results from the coord section
     *
     * @return mixed all the coord data from the request in encoded json
     */
    private function getDataCoord(){
        return $this->result["coord"];
    }
    /**
     * gets the results from the wind section
     *
     * @return mixed all the wind data from the request in encoded json
     */
    private function getDataWind(){
        return $this->result["wind"];
    }
    /**
     * gets the visibilty, the max is 10'000
     *
     * @return int the visivility in meters.
     */
    public function getVisibility(){
        return $this->result["visibility"];
    }
    /**
     * gets procentage amount of clouds
     *
     * @return int amount of clouds in procent
     */
    public function getClouds(){
        return $this->result["all"];
    }
    /**
     * Gets the Temperatur from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celcius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return double the temperatur converted in a specific unit
     */
    public function getTemperatur(string $unit = "k"){
        $temp = $this->getDataMain()["temp"];
        switch ($unit){
            case "k":
                return $temp;
            case "c":
                return $temp - 273.15;
            case "f":
                return 1.8 * ($temp - 273.15) + 32;
        }
        return null;
    }
    /**
     * Gets the max Temperatur from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celcius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return double the max temperatur converted in a specific unit
     */
    public function getTemperaturMax(string $unit = "k"){
        $temp = $this->getDataMain()["temp_Max"];
        switch ($unit){
            case "k":
                return $temp;
            case "c":
                return $temp - 273.15;
            case "f":
                return 1.8 * ($temp - 273.15) + 32;
        }
        return null;
    }
    /**
     * Gets the min Temperatur from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celcius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return double the min temperatur converted in a specific unit
     */
    public function getTemperaturMin(string $unit = "k"){
        $temp = $this->getDataMain()["temp_Min"];
        switch ($unit){
            case "k":
                return $temp;
            case "c":
                return $temp - 273.15;
            case "f":
                return 1.8 * ($temp - 273.15) + 32;
        }
        return null;
    }
    /**
     * Gets how the Temperatur feels like from the results
     *
     * @param string $unit [optional] <br>
     * "k" = Kelvin <br>
     * "c" = Celcius <br>
     * "f" = Fahrenheit <br>
     * </p>
     * @return double how the temperatur feels like converted in a specific unit
     */
    public function getTemperaturFeelslike(string $unit = "k"){
        $temp = $this->getDataMain()["feels_like"];
        switch ($unit){
            case "k":
                return $temp;
            case "c":
                return $temp - 273.15;
            case "f":
                return 1.8 * ($temp - 273.15) + 32;
        }
        return null;
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
    public function getResult()
    {
        return $this->result;
    }
}











