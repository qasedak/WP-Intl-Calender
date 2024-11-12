<?php
/*
Plugin Name: WP Intl Calendar
Description: this plugin converts wordpress dates and times to all other calendars available in JS Intl method
Version: 1.04 Beta
Author: Mohammad Anbarestany
*/

// Include settings file
require_once plugin_dir_path(__FILE__) . 'settings.php';

function intlCalen()
{
    $year_format = get_option('intlCalen_year_format', '2-digit');
    $month_format = get_option('intlCalen_month_format', 'numeric');
    $day_format = get_option('intlCalen_day_format', 'numeric');
    $weekday_format = get_option('intlCalen_weekday_format', 'short');
    $hour_format = get_option('intlCalen_hour_format', 'numeric');
    $minute_format = get_option('intlCalen_minute_format', 'numeric');
    $timeZoneName_format = get_option('intlCalen_timeZoneName_format', 'short');
    $timeZone_format = get_option('intlCalen_timeZone_format', 'Asia/Tehran');
    $hour12_format = get_option('intlCalen_hour12_format', 'false');
    if (get_option('intlCalen_locale') == 'auto'){
        $locale = str_replace('_','-',get_locale());
    } else {
        $locale = get_option('intlCalen_locale', 'fa-IR');
    }
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
    const formatter = new Intl.DateTimeFormat("' . $locale . '", options);

    document.querySelectorAll("time").forEach(time => {
        const gregorianDate = new Date(time.dateTime);
        const convertedDate = formatter.format(gregorianDate);
        time.textContent = convertedDate;
    });
</script>';
}

function intlCalenDashboard()
{
    $year_format = get_option('intlCalen_year_format', '2-digit');
    $month_format = get_option('intlCalen_month_format', 'numeric');
    $day_format = get_option('intlCalen_day_format', 'numeric');
    $weekday_format = get_option('intlCalen_weekday_format', 'short');
    $hour_format = get_option('intlCalen_hour_format', 'numeric');
    $minute_format = get_option('intlCalen_minute_format', 'numeric');
    $timeZoneName_format = get_option('intlCalen_timeZoneName_format', 'short');
    $timeZone_format = get_option('intlCalen_timeZone_format', 'Asia/Tehran');
    $hour12_format = get_option('intlCalen_hour12_format', 'false');
    if (get_option('intlCalen_locale') == 'auto'){
        $locale = str_replace('_','-',get_locale());
    } else {
        $locale = get_option('intlCalen_locale', 'fa-IR');
    }

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
