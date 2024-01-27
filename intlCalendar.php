<?php
/*
Plugin Name: WP Intl Calendar
Description: this plugin converts wordpress dates and times to all other calendars available in JS Intl method (just Jalali for now)
Version: 1.02 Beta
Author: Mohammad Anbarestany
*/

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
    $locale = get_option('intlCalen_locale', 'fa-IR');

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
        const jalaliDate = formatter.format(gregorianDate);
        time.textContent = jalaliDate;
    });
</script>';
}

add_action('wp_footer', 'intlCalen');


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
    $locale = get_option('intlCalen_locale', 'fa-IR');

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
  const gregorianDate = dates[i].textContent;

  // Remove the Published part from the string
  const gregorianDateWithoutPrefix = gregorianDate.replace("Published", "");

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
  const jalaliDate = formatter.format(date);

  // Replace the text content of the element with the jalali date
  dates[i].textContent = jalaliDate;
}
</script>';
}
add_action('admin_footer_text', 'intlCalenDashboard');

function intlCalen_settings()
{
    add_options_page(
        'Intl Calendar Settings',
        'Intl Calendar',
        'manage_options',
        'intlCalen',
        'intlCalen_options_page'
    );
}

function intlCalen_options_page()
{
?>
    <div class="wrap">
        <h1>Intl Calendar Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('intlCalen_settings');
            do_settings_sections('intlCalen_settings');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

function intlCalen_settings_init()
{
    add_settings_section(
        'intlCalen_date_format_section',
        'Date Format',
        'intlCalen_date_format_section_callback',
        'intlCalen_settings'
    );

    add_settings_field(
        'intlCalen_year_format',
        'Year Format',
        'intlCalen_year_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_month_format',
        'Month Format',
        'intlCalen_month_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_day_format',
        'Day Format',
        'intlCalen_day_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_Weekday_format',
        'Weekday Format',
        'intlCalen_Weekday_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_hour_format',
        'Hour Format',
        'intlCalen_hour_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_minute_format',
        'Minute Format',
        'intlCalen_minute_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_timeZoneName_format',
        'TimeZoneName Format',
        'intlCalen_timeZoneName_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_timeZone_format',
        'TimeZone Format',
        'intlCalen_timeZone_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_hour12_format',
        'Hour12 Format',
        'intlCalen_hour12_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_locale',
        'Locale',
        'intlCalen_locale_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    register_setting(
        'intlCalen_settings',
        'intlCalen_year_format'
    );

    register_setting(
        'intlCalen_settings',
        'intlCalen_month_format'
    );

    register_setting(
        'intlCalen_settings',
        'intlCalen_day_format'
    );

    register_setting(
        'intlCalen_settings',
        'intlCalen_weekday_format'
    );

    register_setting(
        'intlCalen_settings',
        'intlCalen_hour_format'
    );

    register_setting(
        'intlCalen_settings',
        'intlCalen_minute_format'
    );

    register_setting(
        'intlCalen_settings',
        'intlCalen_timeZoneName_format'
    );

    register_setting(
        'intlCalen_settings',
        'intlCalen_timeZone_format'
    );

    register_setting(
        'intlCalen_settings',
        'intlCalen_hour12_format'
    );

    register_setting(
        'intlCalen_settings',
        'intlCalen_locale'
    );
}

function intlCalen_date_format_section_callback()
{
    echo '<p>Select the format for each date component.</p>';
}

function intlCalen_locale_callback()
{
    $options = get_option('intlCalen_locale');
    $localeCodes = array(
        'fa-IR' => 'Persian (Iran)',
        'fa-AF' => 'Persian (Afghanistan)',
        // Add more locale codes here
    );
?>
    <select name="intlCalen_locale">
        <?php
        foreach ($localeCodes as $code => $label) {
        ?>
            <option value="<?php echo $code; ?>" <?php selected($options, $code); ?>><?php echo $label; ?></option>
        <?php
        }
        ?>
    </select>
<?php
}

function intlCalen_year_format_callback()
{
    $options = get_option('intlCalen_year_format');
?>
    <select name="intlCalen_year_format">
        <option value="" <?php selected($options, ''); ?>>Disable</option>
        <option value="numeric" <?php selected($options, 'numeric'); ?>>Numeric</option>
        <option value="2-digit" <?php selected($options, '2-digit'); ?>>2-digit</option>
    </select>
<?php
}

function intlCalen_month_format_callback()
{
    $options = get_option('intlCalen_month_format');
?>
    <select name="intlCalen_month_format">
        <option value="" <?php selected($options, ''); ?>>Disable</option>
        <option value="numeric" <?php selected($options, 'numeric'); ?>>Numeric</option>
        <option value="long" <?php selected($options, 'long'); ?>>Long</option>
        <option value="2-digit" <?php selected($options, '2-digit'); ?>>2-digit</option>
        <option value="short" <?php selected($options, 'short'); ?>>short</option>
        <option value="narrow" <?php selected($options, 'narrow'); ?>>narrow</option>
    </select>
<?php
}

function intlCalen_day_format_callback()
{
    $options = get_option('intlCalen_day_format');
?>
    <select name="intlCalen_day_format">
        <option value="" <?php selected($options, ''); ?>>Disable</option>
        <option value="numeric" <?php selected($options, 'numeric'); ?>>Numeric</option>
        <option value="2-digit" <?php selected($options, '2-digit'); ?>>2-digit</option>
    </select>
<?php
}

function intlCalen_weekday_format_callback()
{
    $options = get_option('intlCalen_weekday_format');
?>
    <select name="intlCalen_weekday_format">
        <option value="" <?php selected($options, ''); ?>>Disable</option>
        <option value="long" <?php selected($options, 'long'); ?>>Long</option>
        <option value="short" <?php selected($options, 'short'); ?>>short</option>
        <option value="narrow" <?php selected($options, 'narrow'); ?>>narrow</option>
    </select>
<?php
}

function intlCalen_hour_format_callback()
{
    $options = get_option('intlCalen_hour_format');
?>
    <select name="intlCalen_hour_format">
        <option value="" <?php selected($options, ''); ?>>Disable</option>
        <option value="numeric" <?php selected($options, 'numeric'); ?>>Numeric</option>
        <option value="2-digit" <?php selected($options, '2-digit'); ?>>2-digit</option>
    </select>
<?php
}

function intlCalen_minute_format_callback()
{
    $options = get_option('intlCalen_minute_format');
?>
    <select name="intlCalen_minute_format">
        <option value="" <?php selected($options, ''); ?>>Disable</option>
        <option value="numeric" <?php selected($options, 'numeric'); ?>>Numeric</option>
        <option value="2-digit" <?php selected($options, '2-digit'); ?>>2-digit</option>
    </select>
<?php
}

function intlCalen_timeZoneName_format_callback()
{
    $options = get_option('intlCalen_timeZoneName_format');
?>
    <select name="intlCalen_timeZoneName_format">
        <option value="" <?php selected($options, ''); ?>>Disable</option>
        <option value="long" <?php selected($options, 'long'); ?>>Long</option>
        <option value="short" <?php selected($options, 'short'); ?>>short</option>
    </select>
<?php
}

function intlCalen_timeZone_format_callback()
{
    $options = get_option('intlCalen_timeZone_format');
?>
    <select name="intlCalen_timeZone_format">
        <option value="" <?php selected($options, ''); ?>>Disable</option>
        <option value="Asia/Tehran" <?php selected($options, 'Asia/Tehran'); ?>>Asia/Tehran</option>
        <option value="Asia/Kabul" <?php selected($options, 'Asia/Kabul'); ?>>Asia/Kabul</option>
    </select>
<?php
}

function intlCalen_hour12_format_callback()
{
    $options = get_option('intlCalen_hour12_format');
?>
    <select name="intlCalen_hour12_format">
        <option value="" <?php selected($options, ''); ?>>Disable</option>
        <option value="true" <?php selected($options, 'true'); ?>>true</option>
        <option value="false" <?php selected($options, 'false'); ?>>false</option>
    </select>
<?php
}

add_action('admin_menu', 'intlCalen_settings');
add_action('admin_init', 'intlCalen_settings_init');
