<?php
/*
Plugin Name: WP Intl Calendar
Description: this plugin converts wordpress dates and times to all other calendars available in JS Intl method (just Jalali for now)
Version: 1.0
Author: Mohammad Anbarestany
*/

function sk_jalali() {
    $year_format = get_option('sk_jalali_year_format', '2-digit');
    $month_format = get_option('sk_jalali_month_format', 'numeric');
    $day_format = get_option('sk_jalali_day_format', 'numeric');
    $weekday_format = get_option('sk_jalali_weekday_format', 'short');
    $hour_format = get_option('sk_jalali_hour_format', 'numeric');
    $minute_format = get_option('sk_jalali_minute_format', 'numeric');
    $timeZoneName_format = get_option('sk_jalali_timeZoneName_format', 'short');
    $timeZone_format = get_option('sk_jalali_timeZone_format', 'Asia/Tehran');
    $hour12_format = get_option('sk_jalali_hour12_format', 'false');

    echo '<script type="text/javascript">
	let options = {';
        if($weekday_format != '') {echo "weekday: '$weekday_format',";}
        if($year_format != '') {echo "year: '$year_format',";}
        if($month_format != '') {echo "month: '$month_format',";}
        if($day_format != '') {echo "day: '$day_format',";}
        if($hour_format != '') {echo "hour: '$hour_format',";}
        if($minute_format != '') {echo "minute: '$minute_format',";}
        if($timeZoneName_format != '') {echo "timeZoneName: '$timeZoneName_format',";}
        if($timeZone_format != '') {echo "timeZone: '$timeZone_format',";}
        if($hour12_format != '') {echo "hour12: '$hour12_format',";}
    echo '};
    const formatter = new Intl.DateTimeFormat("fa-IR", options);

    document.querySelectorAll("time").forEach(time => {
        const gregorianDate = new Date(time.dateTime);
        const jalaliDate = formatter.format(gregorianDate);
        time.textContent = jalaliDate;
    });
</script>';
}

add_action('wp_footer', 'sk_jalali');

function sk_jalali_settings() {
    add_options_page(
        'SK Jalali Settings',
        'SK Jalali',
        'manage_options',
        'sk_jalali',
        'sk_jalali_options_page'
    );
}

function sk_jalali_options_page() {
    ?>
    <div class="wrap">
        <h1>SK Jalali Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('sk_jalali_settings');
            do_settings_sections('sk_jalali_settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function sk_jalali_settings_init() {
    add_settings_section(
        'sk_jalali_date_format_section',
        'Date Format',
        'sk_jalali_date_format_section_callback',
        'sk_jalali_settings'
    );

    add_settings_field(
        'sk_jalali_year_format',
        'Year Format',
        'sk_jalali_year_format_callback',
        'sk_jalali_settings',
        'sk_jalali_date_format_section'
    );

    add_settings_field(
        'sk_jalali_month_format',
        'Month Format',
        'sk_jalali_month_format_callback',
        'sk_jalali_settings',
        'sk_jalali_date_format_section'
    );

    add_settings_field(
        'sk_jalali_day_format',
        'Day Format',
        'sk_jalali_day_format_callback',
        'sk_jalali_settings',
        'sk_jalali_date_format_section'
    );

    add_settings_field(
        'sk_jalali_Weekday_format',
        'Weekday Format',
        'sk_jalali_Weekday_format_callback',
        'sk_jalali_settings',
        'sk_jalali_date_format_section'
    );

    add_settings_field(
        'sk_jalali_hour_format',
        'Hour Format',
        'sk_jalali_hour_format_callback',
        'sk_jalali_settings',
        'sk_jalali_date_format_section'
    );

    add_settings_field(
        'sk_jalali_minute_format',
        'Minute Format',
        'sk_jalali_minute_format_callback',
        'sk_jalali_settings',
        'sk_jalali_date_format_section'
    );

    add_settings_field(
        'sk_jalali_timeZoneName_format',
        'TimeZoneName Format',
        'sk_jalali_timeZoneName_format_callback',
        'sk_jalali_settings',
        'sk_jalali_date_format_section'
    );

    add_settings_field(
        'sk_jalali_timeZone_format',
        'TimeZone Format',
        'sk_jalali_timeZone_format_callback',
        'sk_jalali_settings',
        'sk_jalali_date_format_section'
    );

    add_settings_field(
        'sk_jalali_hour12_format',
        'Hour12 Format',
        'sk_jalali_hour12_format_callback',
        'sk_jalali_settings',
        'sk_jalali_date_format_section'
    );

    register_setting(
        'sk_jalali_settings',
        'sk_jalali_year_format'
    );

    register_setting(
        'sk_jalali_settings',
        'sk_jalali_month_format'
    );

    register_setting(
        'sk_jalali_settings',
        'sk_jalali_day_format'
    );

    register_setting(
        'sk_jalali_settings',
        'sk_jalali_weekday_format'
    );

    register_setting(
        'sk_jalali_settings',
        'sk_jalali_hour_format'
    );

    register_setting(
        'sk_jalali_settings',
        'sk_jalali_minute_format'
    );

    register_setting(
        'sk_jalali_settings',
        'sk_jalali_timeZoneName_format'
    );

    register_setting(
        'sk_jalali_settings',
        'sk_jalali_timeZone_format'
    );

    register_setting(
        'sk_jalali_settings',
        'sk_jalali_hour12_format'
    );
}

function sk_jalali_date_format_section_callback() {
    echo '<p>Select the format for each date component.</p>';
}

function sk_jalali_year_format_callback() {
    $options = get_option('sk_jalali_year_format');
    ?>
    <select name="sk_jalali_year_format">
        <option value=""<?php selected($options, ''); ?>>Disable</option>
        <option value="numeric"<?php selected($options, 'numeric'); ?>>Numeric</option>
        <option value="2-digit"<?php selected($options, '2-digit'); ?>>2-digit</option>
    </select>
    <?php
}

function sk_jalali_month_format_callback() {
    $options = get_option('sk_jalali_month_format');
    ?>
    <select name="sk_jalali_month_format">
        <option value=""<?php selected($options, ''); ?>>Disable</option>
        <option value="numeric"<?php selected($options, 'numeric'); ?>>Numeric</option>
        <option value="long"<?php selected($options, 'long'); ?>>Long</option>
        <option value="2-digit"<?php selected($options, '2-digit'); ?>>2-digit</option>
        <option value="short"<?php selected($options, 'short'); ?>>short</option>
        <option value="narrow"<?php selected($options, 'narrow'); ?>>narrow</option>
    </select>
    <?php
}

function sk_jalali_day_format_callback() {
    $options = get_option('sk_jalali_day_format');
    ?>
    <select name="sk_jalali_day_format">
        <option value=""<?php selected($options, ''); ?>>Disable</option>
        <option value="numeric"<?php selected($options, 'numeric'); ?>>Numeric</option>
        <option value="long"<?php selected($options, 'long'); ?>>Long</option>
    </select>
    <?php
}

function sk_jalali_weekday_format_callback() {
    $options = get_option('sk_jalali_weekday_format');
    ?>
    <select name="sk_jalali_weekday_format">
        <option value=""<?php selected($options, ''); ?>>Disable</option>
        <option value="long"<?php selected($options, 'long'); ?>>Long</option>
        <option value="short"<?php selected($options, 'short'); ?>>short</option>
        <option value="narrow"<?php selected($options, 'narrow'); ?>>narrow</option>
    </select>
    <?php
}

function sk_jalali_hour_format_callback() {
    $options = get_option('sk_jalali_hour_format');
    ?>
    <select name="sk_jalali_hour_format">
        <option value=""<?php selected($options, ''); ?>>Disable</option>
        <option value="numeric"<?php selected($options, 'numeric'); ?>>Numeric</option>
        <option value="2-digit"<?php selected($options, '2-digit'); ?>>2-digit</option>
    </select>
    <?php
}

function sk_jalali_minute_format_callback() {
    $options = get_option('sk_jalali_minute_format');
    ?>
    <select name="sk_jalali_minute_format">
        <option value=""<?php selected($options, ''); ?>>Disable</option>
        <option value="numeric"<?php selected($options, 'numeric'); ?>>Numeric</option>
        <option value="2-digit"<?php selected($options, '2-digit'); ?>>2-digit</option>
    </select>
    <?php
}

function sk_jalali_timeZoneName_format_callback() {
    $options = get_option('sk_jalali_timeZoneName_format');
    ?>
    <select name="sk_jalali_timeZoneName_format">
        <option value=""<?php selected($options, ''); ?>>Disable</option>
        <option value="long"<?php selected($options, 'long'); ?>>Long</option>
        <option value="short"<?php selected($options, 'short'); ?>>short</option>
    </select>
    <?php
}

function sk_jalali_timeZone_format_callback() {
    $options = get_option('sk_jalali_timeZone_format');
    ?>
    <select name="sk_jalali_timeZone_format">
        <option value=""<?php selected($options, ''); ?>>Disable</option>
        <option value="Asia/Tehran"<?php selected($options, 'Asia/Tehran'); ?>>Asia/Tehran</option>
        <option value="Asia/Kabul"<?php selected($options, 'Asia/Kabul'); ?>>Asia/Kabul</option>
    </select>
    <?php
}

function sk_jalali_hour12_format_callback() {
    $options = get_option('sk_jalali_hour12_format');
    ?>
    <select name="sk_jalali_hour12_format">
        <option value=""<?php selected($options, ''); ?>>Disable</option>
        <option value="true"<?php selected($options, 'true'); ?>>true</option>
        <option value="false"<?php selected($options, 'false'); ?>>false</option>
    </select>
    <?php
}

add_action('admin_menu', 'sk_jalali_settings');
add_action('admin_init', 'sk_jalali_settings_init');
