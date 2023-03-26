# Weather and Air Quality Plugin

This plugin adds 4 shortcodes that display weather and air quality information on your WordPress site. The plugin uses the OpenWeather API (The key can be aquired here: https://openweathermap.org/api) and the PollutionAPI to access current weather and air quality data for a given location.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/weather-and-air-quality-plugin` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to the plugin settings page and enter your API key and the coordinates for the location you want to display data for.

## Shortcodes

The plugin adds the following shortcodes:

[weather_Temperatur]
Use the `weather_Temperatur` shortcode to display the current temperature in Celsius for the location you set in the plugin settings.

[weather_General]
Use the `weather_General` shortcode to display general information about the current weather.

[weather_Wind]
Use the `weather_Wind` shortcode to display the current wind speed and direction for the location you set in the plugin settings.
<br>
![image](https://user-images.githubusercontent.com/72823328/227488208-deae6305-0818-4fca-9108-84896a4a15f0.png)

[weather_Polution]
Use the `weather_Polution` shortcode to display the current air quality index (AQI) for the location you set in the plugin settings.

[weather_Time]
Use the `weather_Time` shortcode to display the current time for the location you set in the plugin settings.
<br>
![image](https://user-images.githubusercontent.com/72823328/227489837-c9b7746b-41fd-405e-8e1f-d260ecf77020.png)




## Usage

To use the shortcodes, simply add them to any page or post on your WordPress site. You can also customize the shortcode output by changing the parameters in the options menu:

### Location

To display weather or air quality data for a different location, you can specify the latitude and longitude in the options. For example:
lat="37.7749" lon="-122.4194"

### Units

To display temperature in a different unit of measurement, you can specify the units parameter in the shortcode. Available options are `metric` (default) and `imperial`. 


## Credits

This plugin uses the OpenWeather API and the PollutionAPI to access weather and air quality data.



