<?php
/*
Plugin Name: WP Intl Calendar
Description: this plugin converts wordpress dates and times to all other calendars available in JS Intl method
Version: 1.05 Beta
Author: Mohammad Anbarestany
Text Domain: wp-intl-calendar
Domain Path: /languages
*/

// Load plugin text domain
function intlCalen_load_textdomain() {
    load_plugin_textdomain(
        'wp-intl-calendar',
        false,
        dirname(plugin_basename(__FILE__)) . '/languages'
    );
}
add_action('plugins_loaded', 'intlCalen_load_textdomain');

// Include settings file
require_once plugin_dir_path(__FILE__) . 'settings.php';

function get_calendar_options($locale) {
    // Base options from settings
    $options = [
        'year' => get_option('intlCalen_year_format', '2-digit'),
        'month' => get_option('intlCalen_month_format', 'numeric'),
        'day' => get_option('intlCalen_day_format', 'numeric'),
        'weekday' => get_option('intlCalen_weekday_format', 'short'),
        'hour' => get_option('intlCalen_hour_format', 'numeric'),
        'minute' => get_option('intlCalen_minute_format', 'numeric'),
        'timeZoneName' => get_option('intlCalen_timeZoneName_format', 'short'),
        'timeZone' => get_option('intlCalen_timeZone_format', get_option('timezone_string')),
        'hour12' => filter_var(get_option('intlCalen_hour12_format', 'false'), FILTER_VALIDATE_BOOLEAN),
    ];

    // Add calendar system based on locale
    $calendar_mapping = [
        'fa' => 'persian',
        'ar' => 'islamic',
        'th' => 'buddhist',
        'ja' => 'japanese',
        'zh' => 'chinese',
        'en' => 'gregory'
    ];

    // Get the language code from locale
    $lang = substr($locale, 0, 2);
    if (isset($calendar_mapping[$lang])) {
        $options['calendar'] = $calendar_mapping[$lang];
    }

    return $options;
}

function intlCalen()
{
    // Get locale setting
    $locale_setting = get_option('intlCalen_locale', 'auto');
    
    // Initialize locale variable
    $locale = 'en-US'; // fallback default
    $browser_default = false;
    if ($locale_setting === 'browser') {
        $browser_default = true;
    } elseif ($locale_setting === 'auto') {
        // getting wordpress locale (Auto (WordPress Default))
        $locale = str_replace('_', '-', get_locale());
        $browser_default = false;
    } else {
        $locale = $locale_setting;
        $browser_default = false;
    }

    // Get calendar options
    $calendar_options = get_calendar_options($locale);
    
    // Generate JavaScript options object
    $js_options = [];
    foreach ($calendar_options as $key => $value) {
        if ($value !== '') {
            if ($key === 'hour12') {
                $js_options[] = "'hour12': " . ($value ? 'true' : 'false');
            } else {
                $js_options[] = "'$key': '$value'";
            }
        }
    }

    // Get the custom date selector from options
    $date_selector = get_option('intlCalen_date_selector', '.date, time');
    
    ?>
    <script type="text/javascript">
    const options = {
        <?php echo implode(",\n        ", $js_options); ?>
    };
    
    try {
        // Handle browser locale if selected
        let localeToUse;
        if (<?php echo $browser_default ? 'true' : 'false'; ?>) {
            const browserFormatter = new Intl.DateTimeFormat(navigator.language);
            const resolvedOptions = browserFormatter.resolvedOptions();
            delete options.calendar;
            localeToUse = `<?php echo esc_js($locale); ?>-u-ca-${resolvedOptions.calendar}`;
        } else {
            localeToUse = "<?php echo esc_js($locale); ?>";
        }
        const formatter = new Intl.DateTimeFormat(localeToUse, options);
        
        document.querySelectorAll("<?php echo esc_js($date_selector); ?>").forEach(element => {
            try {
                const gregorianDate = new Date(element.dateTime || element.textContent);
                const convertedDate = formatter.format(gregorianDate);
                element.textContent = convertedDate;
            } catch (error) {
                console.warn('Date conversion failed for:', element, error);
            }
        });
    } catch (error) {
        console.error('Calendar initialization failed:', error);
    }
    </script>
    <?php
}

function intlCalenDashboard()
{
    // Use get_user_locale() for the admin area
    if (get_option('intlCalen_locale') == 'auto') {
        $locale = str_replace('_', '-', get_user_locale());
    } else {
        $locale = get_option('intlCalen_locale', 'en-US');
    }

    $year_format = get_option('intlCalen_year_format', '2-digit');
    $month_format = get_option('intlCalen_month_format', 'numeric');
    $day_format = get_option('intlCalen_day_format', 'numeric');
    $weekday_format = get_option('intlCalen_weekday_format', 'short');
    $hour_format = get_option('intlCalen_hour_format', 'numeric');
    $minute_format = get_option('intlCalen_minute_format', 'numeric');
    $timeZoneName_format = get_option('intlCalen_timeZoneName_format', 'short');
    $timeZone_format = get_option('intlCalen_timeZone_format', 'Asia/Tehran');
    $hour12_format = get_option('intlCalen_hour12_format', 'false');

    echo '<script type="text/javascript">
	let options = {';
    if ($weekday_format != '') {
        echo "weekday: '$weekday_format',";
    }
    if ($year_format != '') {
        echo "year: '$year_format',";
    }
    if ($month_format != '') {
        echo "month: '$month_format',";
    }
    if ($day_format != '') {
        echo "day: '$day_format',";
    }
    if ($hour_format != '') {
        echo "hour: '$hour_format',";
    }
    if ($minute_format != '') {
        echo "minute: '$minute_format',";
    }
    if ($timeZoneName_format != '') {
        echo "timeZoneName: '$timeZoneName_format',";
    }
    if ($timeZone_format != '') {
        echo "timeZone: '$timeZone_format',";
    }
    if ($hour12_format != '') {
        echo "hour12: '$hour12_format',";
    }
    echo '};
    // Get all the elements with the class name "date"
    const dates = document.querySelectorAll(".date");
    
    // Loop through the elements
    for (let i = 0; i < dates.length; i++) {
        // Get the text content of the element, which contains the Gregorian date
        const gregorianDate = dates[i].innerHTML;
        const gregorianDateSplited = gregorianDate.split("<br>");
        const postStatus = gregorianDateSplited[0];
        const gregorianDateWithoutPrefix = gregorianDateSplited[1];
    
        // Split the string by spaces and dashes and get an array of substrings
        const gregorianDateComponents = gregorianDateWithoutPrefix.split(/[- ]/);
    
        // Convert the substrings to integers and subtract 1 from the month
        const gregorianDateComponentsDate = gregorianDateComponents[0].split("/");
        const year = gregorianDateComponentsDate[0];
        const month = gregorianDateComponentsDate[1] - 1;
        const day = gregorianDateComponentsDate[2];
        const gregorianDateComponentsTime = gregorianDateComponents[2].split(":");
        let hour = gregorianDateComponentsTime[0].padStart(2,"0");
        if (gregorianDateComponents[3] === "pm") {
            let hour = gregorianDateComponentsTime[0].padStart(2,"0") + 12;
        }
        const minute = gregorianDateComponentsTime[1];
    
        // Create a Date object with the Gregorian date
        const date = new Date(Date.UTC(...[year, month, day, hour, minute]));
    
        // Create an Intl.DateTimeFormat object with the Persian locale and calendar
        const formatter = new Intl.DateTimeFormat("' . $locale . '", options);
    
        // Format the date according to the locale and options
        const convertedDate = formatter.format(date);
    
        // Replace the text content of the element with the jalali date
        dates[i].innerHTML = postStatus + "<br>" + convertedDate;
    }
</script>';
}

function is_calendar_supported($locale) {
    static $supported_calendars = null;
    
    if ($supported_calendars === null) {
        try {
            // Test browser support using JavaScript
            ?>
            <script type="text/javascript">
            window.wpIntlCalendarSupport = {};
            try {
                new Intl.DateTimeFormat('<?php echo esc_js($locale); ?>', {
                    calendar: '<?php echo esc_js($calendar); ?>'
                });
                window.wpIntlCalendarSupport['<?php echo esc_js($locale); ?>'] = true;
            } catch (e) {
                window.wpIntlCalendarSupport['<?php echo esc_js($locale); ?>'] = false;
            }
            </script>
            <?php
        } catch (Exception $e) {
            return false;
        }
    }
    
    return true;
}

// Add hooks for the main functionality
add_action('wp_footer', 'intlCalen');
add_action('admin_footer_text', 'intlCalenDashboard');
