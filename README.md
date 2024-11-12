# WP Intl Calendar

WP Intl Calendar is a WordPress plugin that allows you to display dates and times in different calendar systems based on the user's locale. It leverages the JavaScript Intl API to provide accurate and localized date formatting.

## Features

- Convert dates to various calendar systems such as Persian, Islamic, Buddhist, Japanese, and Chinese.
- Automatically detect and use the user's locale or set a custom locale.
- Customize date and time formats including year, month, day, weekday, hour, minute, and timezone.
- Support for both 12-hour and 24-hour time formats.
- Specify custom CSS selectors to target elements containing dates for conversion.

## Installation

1. Upload the plugin files to the `/wp-content/plugins/wp-intl-calendar` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings -> Intl Calendar screen to configure the plugin.

## Usage

1. **Locale Settings**: Choose whether to automatically detect the user's locale or set a specific locale for date conversion.
2. **Date and Time Formats**: Customize the format for year, month, day, weekday, hour, minute, and timezone.
3. **Custom Date Selector**: Enter CSS selectors for elements containing dates, separated by commas. Default is `.date, time`.

## Changelog

### 1.05
- Added support for custom CSS selectors to target elements containing dates.
- Improved handling of timezone settings to use WordPress default timezone.
- Enhanced boolean handling for 12-hour time format option.
- More organized code.
- Translation ability introduced.

### 1.04
- In locale setting, you can now select "Auto" and it will get WordPress locale automatically (better option for multi-language websites).

### 1.03
- Dates on the WordPress dashboard post section (Partialy).

### 1.0
- Initial release with support for converting dates on the front-end.

## Support

For support, please contact me at [m_anbarestany@hotmail.com](mailto:m_anbarestany@hotmail.com).
