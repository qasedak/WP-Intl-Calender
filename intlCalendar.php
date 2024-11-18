<?php
/**
 * WP Intl Calendar
 *
 * @package     WP_Intl_Calendar
 * @author      Mohammad Anbarestany
 * @copyright   2024 Mohammad Anbarestany
 * @license     MIT
 *
 * @wordpress-plugin
 * Plugin Name: WP Intl Calendar
 * Description: Converts WordPress dates and times to all other calendars available in JS Intl method
 * Version:     1.07 Beta
 * Author:      Mohammad Anbarestany
 * Text Domain: wp-intl-calendar
 * Domain Path: /languages
 * License:     MIT
 */

/**
 * Loads the plugin's text domain for internationalization.
 *
 * @since 1.04
 * @return void
 */
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

/**
 * Gets calendar formatting options based on locale.
 *
 * @since 1.04
 * @param string $locale The locale identifier (e.g., 'en-US', 'fa-IR')
 * @return array Calendar formatting options for Intl.DateTimeFormat
 */
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

    // Map language codes to their corresponding calendar systems
    // This mapping determines which calendar to use based on the locale's language code
    $calendar_mapping = [
        'fa' => 'persian',   // Persian/Farsi
        'ar' => 'islamic',   // Arabic countries
        'th' => 'buddhist',  // Thai
        'ja' => 'japanese',  // Japanese
        'zh' => 'chinese',   // Chinese
        'en' => 'gregory'    // English (Gregorian calendar)
    ];

    // Extract the language code from the full locale (e.g., 'en' from 'en-US')
    $lang = substr($locale, 0, 2);
    
    // Apply the calendar system if a mapping exists for this language
    if (isset($calendar_mapping[$lang])) {
        $options['calendar'] = $calendar_mapping[$lang];
    }

    return $options;
}

/**
 * Main function to initialize calendar conversion on frontend.
 * Handles locale detection and JavaScript initialization.
 *
 * @since 1.0
 * @return void
 */
function intlCalen()
{
    // Get locale setting
    $locale_setting = get_option('intlCalen_locale', 'auto');
    
    // Initialize locale variable
    $locale = 'en-US'; // fallback default
    $browser_default = false;
    $display_lang = null;
    // Determine locale and display language based on settings
    switch ($locale_setting) {
        case 'browser':
            $browser_default = true;
            $display_lang = get_option('intlCalen_display_language', 'wordpress') === 'wordpress'
                ? str_replace('_', '-', get_locale())
                : '${navigator.language}';
            break;
        case 'auto':
            $locale = str_replace('_', '-', get_locale());
            $display_lang = get_option('intlCalen_display_language', 'wordpress') === 'wordpress'
                ? str_replace('_', '-', get_locale())
                : $locale;
            $browser_default = false;
            break;
        default:
            $locale = $locale_setting;
            // Determine display language based on setting
            $display_lang = get_option('intlCalen_display_language', 'wordpress') === 'wordpress'
                ? str_replace('_', '-', get_locale())
                : $locale_setting;
            $browser_default = false;
    }

    // Get calendar options
    $calendar_options = get_calendar_options($locale);
    
    // Generate JavaScript options object
    $js_options = array_reduce(
        array_filter($calendar_options, 'strlen'),
        function($carry, $value) use ($calendar_options) {
            $key = array_search($value, $calendar_options);
            $formatted_value = $key === 'hour12' 
                ? ($value ? 'true' : 'false')
                : "'$value'";
            $carry[] = "'$key': $formatted_value";
            return $carry;
        },
        []
    );

    // Build the selector string based on settings
    $selectors = [];
    
    // Add auto-detect class if enabled
    if (get_option('intlCalen_auto_detect', 0)) {
        $selectors[] = '.wp-intl-date';
    }
    
    // Add custom selectors if not empty
    $custom_selector = get_option('intlCalen_date_selector', '.date, time');
    if (!empty($custom_selector)) {
        $selectors[] = $custom_selector;
    }
    
    // Combine selectors with comma
    $final_selector = implode(', ', array_filter($selectors));
    
    ?>
    <script type="text/javascript">
    // Initialize date formatter configuration object
    const options = {
        <?php echo implode(",\n        ", $js_options); ?>
    };
    
    try {
        // Handle locale selection and browser detection
        let localeToUse;
        
        if (<?php echo $browser_default ? 'true' : 'false'; ?>) {
            // When browser default is enabled:
            // 1. Detect browser's calendar system
            // 2. Create a locale string with the detected calendar
            const browserFormatter = new Intl.DateTimeFormat(navigator.language);
            const resolvedOptions = browserFormatter.resolvedOptions();
            
            // Remove any predefined calendar to use browser's default
            if (options.calendar) {
                delete options.calendar;
            }
            
            // Construct locale string with detected calendar system
            localeToUse = `<?php echo esc_js($display_lang); ?>-u-ca-${resolvedOptions.calendar}`;
        } else {
            // Use the configured display language
            localeToUse = "<?php echo esc_js($display_lang); ?>";
        }
        
        // Create the date formatter with final locale and options
        const formatter = new Intl.DateTimeFormat(localeToUse, options);
        
        // Process all date elements matching the selector
        document.querySelectorAll("<?php echo esc_js($final_selector); ?>").forEach(element => {
            try {
                // Get date string from element (data-date attribute, dateTime property, or text content)
                const dateStr = element.getAttribute('data-date') || element.dateTime || element.textContent;
                const gregorianDate = new Date(dateStr);
                
                // Only convert valid dates
                if (!isNaN(gregorianDate)) {
                    const convertedDate = formatter.format(gregorianDate);
                    element.textContent = convertedDate;
                }
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

/**
 * Initializes calendar conversion in WordPress dashboard.
 * Uses user's admin locale preferences.
 *
 * @since 1.02
 * @return void
 */
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

// Add hooks for the main functionality
add_action('wp_footer', 'intlCalen');
add_action('admin_footer_text', 'intlCalenDashboard');

// Add class to post dates
function intlCalen_add_date_class($the_date, $format, $post) {
    $timestamp = get_post_timestamp($post);
    return sprintf(
        '<span class="wp-intl-date" data-date="%s">%s</span>',
        date('Y-m-d H:i:s', $timestamp),
        $the_date
    );
}

// Add class to modified dates
function intlCalen_add_modified_date_class($the_modified_date, $format, $post) {
    $timestamp = get_post_modified_timestamp($post);
    return sprintf(
        '<span class="wp-intl-date" data-date="%s">%s</span>',
        date('Y-m-d H:i:s', $timestamp),
        $the_modified_date
    );
}

// Add class to archive dates
function intlCalen_add_archive_date_class($link_html) {
    // Archive links already contain machine-readable dates in their URLs
    return str_replace('<a', '<a class="wp-intl-date"', $link_html);
}

// Add class to comment dates
function intlCalen_add_comment_date_class($date, $format, $comment) {
    $timestamp = strtotime($comment->comment_date);
    return sprintf(
        '<span class="wp-intl-date" data-date="%s">%s</span>',
        date('Y-m-d H:i:s', $timestamp),
        $date
    );
}

// Initialize filters based on auto-detect setting
function intlCalen_initialize_filters() {
    // Only add filters if auto-detect is enabled
    if (get_option('intlCalen_auto_detect', 0)) {
        add_filter('get_the_date', 'intlCalen_add_date_class', 10, 3);
        add_filter('get_the_modified_date', 'intlCalen_add_modified_date_class', 10, 3);
        add_filter('get_archives_link', 'intlCalen_add_archive_date_class');
        add_filter('get_comment_date', 'intlCalen_add_comment_date_class', 10, 3);
    }
}
add_action('init', 'intlCalen_initialize_filters');
