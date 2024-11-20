<?php
/**
 * Settings Management for WP Intl Calendar
 *
 * Handles all admin settings, options pages, and calendar system configurations.
 *
 * @package WP_Intl_Calendar
 * @since 1.04
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
        __('Intl Calendar Settings', 'wp-intl-calendar'),
        __('Intl Calendar', 'wp-intl-calendar'),
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
        <h1><?php _e('Intl Calendar Settings', 'wp-intl-calendar'); ?></h1>
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
        __('Date Format', 'wp-intl-calendar'),
        'intlCalen_date_format_section_callback',
        'intlCalen_settings'
    );

    add_settings_field(
        'intlCalen_year_format',
        __('Year Format', 'wp-intl-calendar'),
        'intlCalen_year_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_month_format',
        __('Month Format', 'wp-intl-calendar'),
        'intlCalen_month_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_day_format',
        __('Day Format', 'wp-intl-calendar'),
        'intlCalen_day_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_Weekday_format',
        __('Weekday Format', 'wp-intl-calendar'),
        'intlCalen_Weekday_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_hour_format',
        __('Hour Format', 'wp-intl-calendar'),
        'intlCalen_hour_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_minute_format',
        __('Minute Format', 'wp-intl-calendar'),
        'intlCalen_minute_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_timeZoneName_format',
        __('TimeZoneName Format', 'wp-intl-calendar'),
        'intlCalen_timeZoneName_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_timeZone_format',
        __('TimeZone Format', 'wp-intl-calendar'),
        'intlCalen_timeZone_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_hour12_format',
        __('Hour12 Format', 'wp-intl-calendar'),
        'intlCalen_hour12_format_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_field(
        'intlCalen_locale',
        __('Locale', 'wp-intl-calendar'),
        'intlCalen_locale_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    register_setting(
        'intlCalen_settings',
        'intlCalen_date_selector',
        [
            'default' => '.date, time',
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        ]
    );

    add_settings_field(
        'intlCalen_date_selector',
        __('Date Selector', 'wp-intl-calendar'),
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

    register_setting('intlCalen_settings', 'intlCalen_display_language');

    add_settings_field(
        'intlCalen_display_language',
        __('Date Display Language', 'wp-intl-calendar'),
        'intlCalen_display_language_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    register_setting('intlCalen_settings', 'intlCalen_auto_detect', [
        'default' => 0,
        'type' => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);

    add_settings_field(
        'intlCalen_auto_detect',
        __('Automatic Date Detection', 'wp-intl-calendar'),
        'intlCalen_auto_detect_callback',
        'intlCalen_settings',
        'intlCalen_date_format_section'
    );

    add_settings_section(
        'intlCalen_performance_section',
        __('Performance Options', 'wp-intl-calendar'),
        'intlCalen_performance_section_callback',
        'intlCalen_settings'
    );

    add_settings_field(
        'intlCalen_lazy_loading',
        __('Lazy Loading', 'wp-intl-calendar'),
        'intlCalen_lazy_loading_callback',
        'intlCalen_settings',
        'intlCalen_performance_section'
    );

    add_settings_field(
        'intlCalen_enable_caching',
        __('Date Caching', 'wp-intl-calendar'),
        'intlCalen_caching_callback',
        'intlCalen_settings',
        'intlCalen_performance_section'
    );

    add_settings_field(
        'intlCalen_admin_enabled',
        __('Admin Area Conversion', 'wp-intl-calendar'),
        'intlCalen_admin_enabled_callback',
        'intlCalen_settings',
        'intlCalen_performance_section'
    );

    register_setting('intlCalen_settings', 'intlCalen_lazy_loading', [
        'default' => 1,
        'type' => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);

    register_setting('intlCalen_settings', 'intlCalen_enable_caching', [
        'default' => 1,
        'type' => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);

    register_setting('intlCalen_settings', 'intlCalen_admin_enabled', [
        'default' => 0,
        'type' => 'boolean',
        'sanitize_callback' => 'rest_sanitize_boolean',
    ]);
}

function intlCalen_date_format_section_callback()
{
    echo '<p>' . __('Select the format for each date component.', 'wp-intl-calendar') . '</p>';
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
        <option value="" <?php selected($options, ''); ?>><?php _e('Disable', 'wp-intl-calendar'); ?></option>
        <option value="numeric" <?php selected($options, 'numeric'); ?>><?php _e('Numeric', 'wp-intl-calendar'); ?></option>
        <option value="long" <?php selected($options, 'long'); ?>><?php _e('Long', 'wp-intl-calendar'); ?></option>
        <option value="2-digit" <?php selected($options, '2-digit'); ?>><?php _e('2-digit', 'wp-intl-calendar'); ?></option>
        <option value="short" <?php selected($options, 'short'); ?>><?php _e('Short', 'wp-intl-calendar'); ?></option>
        <option value="narrow" <?php selected($options, 'narrow'); ?>><?php _e('Narrow', 'wp-intl-calendar'); ?></option>
    </select>
<?php
}

function intlCalen_day_format_callback()
{
    $options = get_option('intlCalen_day_format');
?>
    <select name="intlCalen_day_format">
        <option value="" <?php selected($options, ''); ?>><?php _e('Disable', 'wp-intl-calendar'); ?></option>
        <option value="numeric" <?php selected($options, 'numeric'); ?>><?php _e('Numeric', 'wp-intl-calendar'); ?></option>
        <option value="2-digit" <?php selected($options, '2-digit'); ?>><?php _e('2-digit', 'wp-intl-calendar'); ?></option>
    </select>
<?php
}

function intlCalen_weekday_format_callback()
{
    $options = get_option('intlCalen_weekday_format');
?>
    <select name="intlCalen_weekday_format">
        <option value="" <?php selected($options, ''); ?>><?php _e('Disable', 'wp-intl-calendar'); ?></option>
        <option value="long" <?php selected($options, 'long'); ?>><?php _e('Long', 'wp-intl-calendar'); ?></option>
        <option value="short" <?php selected($options, 'short'); ?>><?php _e('Short', 'wp-intl-calendar'); ?></option>
        <option value="narrow" <?php selected($options, 'narrow'); ?>><?php _e('Narrow', 'wp-intl-calendar'); ?></option>
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
    $selector = get_option('intlCalen_date_selector', '.date, time');
    ?>
    <input type="text" name="intlCalen_date_selector" value="<?php echo esc_attr($selector); ?>" class="regular-text">
    <p class="description">
        <?php _e('Enter CSS selectors for elements containing dates, separated by commas. Default is ".date, time".', 'wp-intl-calendar'); ?>
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
        <?php _e('When enabled, the plugin will automatically detect and convert dates from posts, comments, and archives. (May impact performance)', 'wp-intl-calendar'); ?>
    </p>
    <?php
}

/**
 * Renders the performance section description.
 *
 * @since 1.07
 * @return void
 */
function intlCalen_performance_section_callback() {
    ?>
    <p>
        <?php _e('Configure performance options for date conversion. These settings can help optimize the plugin\'s performance on your site.', 'wp-intl-calendar'); ?>
    </p>
    <?php
}

/**
 * Renders the lazy loading setting field.
 *
 * @since 1.07
 * @return void
 */
function intlCalen_lazy_loading_callback() {
    $lazy_loading = get_option('intlCalen_lazy_loading', 1);
    ?>
    <label>
        <input type="checkbox" name="intlCalen_lazy_loading" value="1" <?php checked(1, $lazy_loading); ?>>
        <?php _e('Enable lazy loading of date conversions', 'wp-intl-calendar'); ?>
    </label>
    <p class="description">
        <?php _e('Only convert dates when they become visible on screen. This can improve initial page load performance, especially on pages with many dates.', 'wp-intl-calendar'); ?>
    </p>
    <?php
}

/**
 * Renders the caching setting field.
 *
 * @since 1.07
 * @return void
 */
function intlCalen_caching_callback() {
    $caching = get_option('intlCalen_enable_caching', 1);
    ?>
    <label>
        <input type="checkbox" name="intlCalen_enable_caching" value="1" <?php checked(1, $caching); ?>>
        <?php _e('Enable date conversion caching', 'wp-intl-calendar'); ?>
    </label>
    <p class="description">
        <?php _e('Cache converted dates to improve performance. This reduces server load by storing converted dates for reuse.', 'wp-intl-calendar'); ?>
    </p>
    <?php
}

/**
 * Renders the admin area conversion setting field.
 *
 * @since 1.07
 * @return void
 */
function intlCalen_admin_enabled_callback() {
    $admin_enabled = get_option('intlCalen_admin_enabled', 0);
    ?>
    <label>
        <input type="checkbox" name="intlCalen_admin_enabled" value="1" <?php checked(1, $admin_enabled); ?>>
        <?php _e('Enable date conversion in admin area', 'wp-intl-calendar'); ?>
    </label>
    <p class="description">
        <?php _e('Convert dates in the WordPress admin area. Note: This may affect admin performance.', 'wp-intl-calendar'); ?>
    </p>
    <?php
}

add_action('admin_menu', 'intlCalen_settings');
add_action('admin_init', 'intlCalen_settings_init'); 