# Weather and Air Quality Plugin

This plugin adds 4 shortcodes that display weather and air quality information on your WordPress site. The plugin uses the OpenWeather API and the PollutionAPI to access current weather and air quality data for a given location.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/weather-and-air-quality-plugin` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to the plugin settings page and enter your API key and the coordinates for the location you want to display data for.

## Shortcodes

The plugin adds the following shortcodes:

[current_temp]
Use the `current_temp` shortcode to display the current temperature in Celsius for the location you set in the plugin settings.


[win_info]
Use the `wind_info` shortcode to display the current wind speed and direction for the location you set in the plugin settings.

[air_quality]
Use the `air_quality` shortcode to display the current air quality index (AQI) for the location you set in the plugin settings.

[current_time]
Use the `current_time` shortcode to display the current time for the location you set in the plugin settings.

## Usage

To use the shortcodes, simply add them to any page or post on your WordPress site. You can also customize the shortcode output by changing the parameters in the options menu:

### Location

To display weather or air quality data for a different location, you can specify the latitude and longitude in the shortcode. For example:
current_temp lat="37.7749" lon="-122.4194"

### Units

To display temperature in a different unit of measurement, you can specify the units parameter in the shortcode. Available options are `metric` (default) and `imperial`. For example:

current_temp units="imperial"

### Language

To display data in a different language, you can specify the language parameter in the shortcode. Available options are `en` (default), `fr`, `es`, `de`, `it`, `pt`, `ru`, `zh`, `ja`, `ko`, `ar`. For example:

wind_info lang="fr"

## Credits

This plugin uses the OpenWeather API and the PollutionAPI to access weather and air quality data.



