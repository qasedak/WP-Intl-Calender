<?php
/**
 * Settings Management for WP Intl Calendar
 *
 * Handles all admin settings, options pages, and calendar system configurations.
 *
 * @package WP_Intl_Calendar
 * @since 1.0
 */

/**
 * Adds the plugin settings page to WordPress admin menu.
 *
 * @since 1.0
 * @return void
 */
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

/**
 * Renders the settings page HTML.
 *
 * @since 1.0
 * @return void
 */
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

/**
 * Initializes all plugin settings, sections, and fields.
 *
 * @since 1.0
 * @return void
 */
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

    // Register the custom date selector setting
    register_setting(
        'intlCalen_settings',
        'intlCalen_date_selector',
        [
            'default' => '.date, time, .wp-intl-date',
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    // Add a settings field for the custom date selector
    add_settings_field(
        'intlCalen_date_selector',
        'Date Selector',
        'intlCalen_date_selector_callback',
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

    // Add this to your settings registration section
    register_setting('intlCalen_settings', 'intlCalen_display_language');

    // Add this to your settings section
    add_settings_field(
        'intlCalen_display_language',
        __('Date Display Language', 'wp-intl-calendar'),
        'intlCalen_display_language_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    // Add this to your register_settings section
    register_setting('intlCalen_options', 'intlCalen_auto_detect', [
        'default' => 0,
        'type' => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);

    // Add this to your add_settings_field calls
    add_settings_field(
        'intlCalen_auto_detect',
        __('Automatic Date Detection', 'wp-intl-calendar'),
        'intlCalen_auto_detect_callback',
        'intlCalen',
        'intlCalen_section'
    );
}

function intlCalen_date_format_section_callback()
{
    echo '<p>Select the format for each date component.</p>';
}

/**
 * Renders the locale selection field with calendar system grouping.
 * Includes support for auto-detection and various calendar systems.
 *
 * @since 1.04
 * @return void
 */
function intlCalen_locale_callback()
{
    $options = get_option('intlCalen_locale');
    
    // Load WordPress translations
    require_once(ABSPATH . 'wp-admin/includes/translation-install.php');
    $translations = wp_get_available_translations();
    
    // Define supported calendar systems and their locales
    $calendar_systems = [
        'auto' => [
            'name' => esc_html__('Auto (WordPress Default)', 'wp-intl-calendar'),
            'native_name' => esc_html__('Automatic', 'wp-intl-calendar')
        ],
        'browser' => [
            'name' => esc_html__('Auto (Browser Default)', 'wp-intl-calendar'),
            'native_name' => esc_html__('Browser Default', 'wp-intl-calendar')
        ],
        'gregory' => [
            'locales' => ['en-US', 'en-GB', 'en-CA', 'en-AU', 'en-NZ'],
            'name' => esc_html__('Gregorian Calendar', 'wp-intl-calendar'),
            'native_name' => 'Gregorian Calendar'
        ],
        'persian' => [
            'locales' => ['fa-IR', 'fa-AF'],
            'name' => esc_html__('Persian Calendar', 'wp-intl-calendar'),
            'native_name' => 'تقویم فارسی'
        ],
        'islamic' => [
            'locales' => ['ar-SA', 'ar-AE', 'ar-QA', 'ar-BH', 'ar-KW'],
            'name' => esc_html__('Islamic Calendar', 'wp-intl-calendar'),
            'native_name' => 'التقويم الهجري'
        ],
        'buddhist' => [
            'locales' => ['th-TH'],
            'name' => __('Buddhist Calendar', 'wp-intl-calendar'),
            'native_name' => 'ปฏิทินพุทธ'
        ],
        'japanese' => [
            'locales' => ['ja-JP-u-ca-japanese'],
            'name' => __('Japanese Calendar', 'wp-intl-calendar'),
            'native_name' => '和暦'
        ],
        'chinese' => [
            'locales' => ['zh-CN-u-ca-chinese', 'zh-TW-u-ca-chinese'],
            'name' => __('Chinese Calendar', 'wp-intl-calendar'),
            'native_name' => '农历'
        ]
    ];
    
    ?>
    <select name="intlCalen_locale" id="intlCalen_locale">
        <?php
        // Add automatic options first
        printf(
            '<option value="auto" %s>%s</option>',
            selected($options, 'auto', false),
            esc_html__('Auto (WordPress Default)', 'wp-intl-calendar')
        );
        
        // Add browser default option
        printf(
            '<option value="browser" %s>%s</option>',
            selected($options, 'browser', false),
            esc_html__('Auto (Browser Default)', 'wp-intl-calendar')
        );
        
        // Add calendar system groups
        foreach ($calendar_systems as $system => $data) {
            if ($system === 'auto' || $system === 'browser') continue;
            
            printf(
                '<optgroup label="%s - %s">',
                esc_attr($data['name']),
                esc_attr($data['native_name'])
            );
            
            // Add locales for each calendar system
            foreach ($data['locales'] as $locale) {
                $locale_data = isset($translations[$locale]) ? $translations[$locale] : null;
                $display_name = $locale_data ? 
                    sprintf('%s (%s)', $locale_data['native_name'], $locale) : 
                    $locale;
                
                printf(
                    '<option value="%s" %s>%s</option>',
                    esc_attr($locale),
                    selected($options, $locale, false),
                    esc_html($display_name)
                );
            }
            
            echo '</optgroup>';
        }
        ?>
    </select>
    <p class="description">
        <?php _e('Select your preferred calendar system and locale. The "Auto" option will use your WordPress site\'s locale setting.', 'wp-intl-calendar'); ?>
    </p>
    <?php
}

/**
 * Renders the year format selection field.
 *
 * @since 1.0
 * @return void
 */
function intlCalen_year_format_callback()
{
    $options = get_option('intlCalen_year_format');
?>
    <select name="intlCalen_year_format">
        <option value="" <?php selected($options, ''); ?>><?php _e('Disable', 'wp-intl-calendar'); ?></option>
        <option value="numeric" <?php selected($options, 'numeric'); ?>><?php _e('Numeric', 'wp-intl-calendar'); ?></option>
        <option value="2-digit" <?php selected($options, '2-digit'); ?>><?php _e('2-digit', 'wp-intl-calendar'); ?></option>
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

function intlCalen_date_selector_callback()
{
    $selector = get_option('intlCalen_date_selector', '.date, time, .wp-intl-date');
    ?>
    <input type="text" name="intlCalen_date_selector" value="<?php echo esc_attr($selector); ?>" class="regular-text">
    <p class="description">
        <?php _e('Enter CSS selectors for elements containing dates, separated by commas. Default is ".date, time, .wp-intl-date".', 'wp-intl-calendar'); ?>
    </p>
    <?php
}

function intlCalen_display_language_callback() {
    $option = get_option('intlCalen_display_language', 'wordpress');
    ?>
    <select name="intlCalen_display_language">
        <option value="wordpress" <?php selected($option, 'wordpress'); ?>><?php _e('Match WordPress Language', 'wp-intl-calendar'); ?></option>
        <option value="locale" <?php selected($option, 'locale'); ?>><?php _e('Match Selected Locale', 'wp-intl-calendar'); ?></option>
    </select>
    <?php
}

/**
 * Renders the automatic date detection setting field.
 * Controls whether the plugin automatically converts WordPress date elements.
 *
 * @since 1.06
 * @return void
 */
function intlCalen_auto_detect_callback()
{
    $auto_detect = get_option('intlCalen_auto_detect', 0);
    ?>
    <label>
        <input type="checkbox" name="intlCalen_auto_detect" value="1" <?php checked(1, $auto_detect); ?>>
        <?php _e('Automatically detect and convert WordPress date elements', 'wp-intl-calendar'); ?>
    </label>
    <p class="description">
        <?php _e('When enabled, the plugin will automatically detect and convert dates from posts, comments, and archives.', 'wp-intl-calendar'); ?>
    </p>
    <?php
}

add_action('admin_menu', 'intlCalen_settings');
add_action('admin_init', 'intlCalen_settings_init'); 