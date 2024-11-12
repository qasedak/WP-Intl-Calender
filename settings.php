<?php
// All settings-related functions and hooks
// This includes everything from line 157 to the end of the original file

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
        'auto' => 'Auto',
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