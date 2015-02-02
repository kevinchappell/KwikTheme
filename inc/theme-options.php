<?php
/**
 * KwikTheme Theme Options
 * @subpackage KwikTheme
 * @since KwikTheme 1.0
 */

class KwikThemeOptions
{
    private $options;

    public function __construct()
    {
        add_action('admin_menu', array($this, 'kt_add_options_page'));
        add_action('admin_init', array($this, 'kt_settings_init'));
    }

    public function kt_add_options_page()
    {

        $theme_page = add_menu_page(__('KwikTheme Settings', 'kwik'), // Name of page
            __('Theme Options', 'kwik'), // Label in menu
            'edit_theme_options', // Capability required
            'theme_options', // Menu slug, used to uniquely identify the page
            array($this, 'kwik_theme_settings'), // Function that renders the options page
            'dashicons-admin-generic',
            99
            , 0);

        if (!$theme_page) {
            return;
        }

        add_action("load-$theme_page", array($this, 'kt_help_screen'));
    }

    public function kt_help_screen()
    {

        $general_help = '<p>' . __('Some themes provide customization options that are grouped together on a Theme Options screen. If you change themes, options may change or disappear, as they are theme-specific. Your current theme, KwikTheme, provides the following Theme Options:', 'kwik') . '</p>' .
        '<ol>' .
        '<li>' . __('<strong>Color Scheme</strong>: You can choose a color palette of "Light" (light background with dark text) or "Dark" (dark background with light text) for your site.', 'kwik') . '</li>' .
        '<li>' . __('<strong>Link Color</strong>: You can choose the color used for text links on your site. You can enter the HTML color or hex code, or you can choose visually by clicking the "Select a Color" button to pick from a color wheel.', 'kwik') . '</li>' .
        '<li>' . __('<strong>Default Layout</strong>: You can choose if you want your site&#8217;s default layout to have a sidebar on the left, the right, or not at all.', 'kwik') . '</li>' .
        '</ol>' .
        '<p>' . __('Remember to click "Save Changes" to save any changes you have made to the theme options.', 'kwik') . '</p>';

        $headers_help = '<p>' . __('Specific Header Images can be applied to the various sections of the website. For example, on the Portfolio page you may want to a specific work or a map on the Contact Page.', 'kwik') . '</p>' .

        '<p>' . __('Remember to click "Save Changes" to save any changes you have made to the theme options.', 'kwik') . '</p>';

        $sidebar = '<p><strong>' . __('For more information:', 'kwik') . '</strong></p>' .
        '<p>' . __('<a href="http://codex.wordpress.org/Appearance_Theme_Options_Screen" target="_blank">Documentation on Theme Options</a>', 'kwik') . '</p>' .
        '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>', 'kwik') . '</p>';

        $screen = get_current_screen();

        if (method_exists($screen, 'add_help_tab')) {
            // WordPress 3.3+
            $screen->add_help_tab(array(
                'title' => __('General', 'kwik'),
                'id' => 'general-options-help',
                'content' => $general_help,
            )
            );
            $screen->add_help_tab(array(
                'title' => __('Header', 'kwik'),
                'id' => 'header-options-help',
                'content' => $headers_help,
            )
            );

            $screen->set_help_sidebar($sidebar);
        } else {
            // WordPress 3.2
            add_contextual_help($screen, $help . $sidebar);
        }
    }

    public function kt_settings_init()
    {
        $utils = new KwikUtils();
        $defaultSettings = $this->ktDefaultOptions();
        $utils->settings_init(KT_BASENAME, KT_SETTINGS, $defaultSettings);
    }

    public function kwik_theme_settings()
    {
        $settings = $this->kt_get_options();
        echo '<div class="wrap">';
        echo KwikInputs::markup('h2', __('Theme Options', 'kwik'));
        echo '<form action="options.php" method="post">';
        settings_fields(KT_SETTINGS);
        echo KwikUtils::settings_sections(KT_SETTINGS, $settings);
        echo '</form>';
        echo '</div>';
        echo KwikInputs::markup('div', $output, array('class' => 'wrap'));
    }

    public static function kt_get_options()
    {
        return get_option(KT_SETTINGS, array(&$this, 'kt_default_options'));
    }

    public function ktDefaultOptions()
    {

        $kt_default_options = array(
            'general' => array(
                'section_title' => __('General', 'kwik'),
                'section_desc' => __('Set options for the favicon, default excerpt lengths and more.', 'kwik'),
                'settings' => array(
                    'favicon' => array(
                        'type' => 'img',
                        'title' => __('Favicon', 'kwik'),
                        'value' => '',
                        'desc' => __('Recommended Size & Format: 32x32 / .ico', 'kwik'),
                    ),
                    'analytics' => array(
                        'type' => 'text',
                        'title' => __('Google Analytics ID', 'kwik'),
                        'value' => '',
                        'desc' => __('ex. UA-1234567-89', 'kwik'),
                    ),
                ),
            ),
            'header' => array(
                'section_title' => __('Header', 'kwik'),
                'section_desc' => __('Set theme options such as header, and main menu styling options', 'kwik'),
                'settings' => array(
                    'logo' => array(
                        'type' => 'img',
                        'title' => __('Company/Personal Logo', 'kwik'),
                        'value' => '',
                    ),
                    'show_site_name' => array(
                        'type' => 'cb',
                        'title' => __('Show Site Name', 'kwik'),
                        'value' => true
                    ),
                    'show_site_desc' => array(
                        'type' => 'cb',
                        'title' => __('Show Site Description', 'kwik'),
                        'value' => true
                    ),
                    'menu_link' => array(
                        'type' => 'font',
                        'title' => __('Menu Link', 'kwik'),
                        'value' => array(
                            'color' => '#002C90',
                            'font-weight' => 'normal',
                            'font-size' => 20,
                            'line-height' => 20,
                            'font-family' => 'Open+Sans',
                        ),
                    ),
                    'menu_link_hover' => array(
                        'title' => 'Menu Link Hover',
                        'type' => 'multi',
                        'fields' => array(
                            'color' => array(
                                'type' => 'color',
                                'title' => __('Color', 'kwik'),
                                'value' => '#002C90',
                            ),
                            'style' => array(
                                'type' => 'cb_group',
                                'title' => __('Style', 'kwik'),
                                'value' => array('Underlined' => 'underline'),
                                'options' => array(
                                    'Underlined' => 'underline',
                                    'Bold' => 'bold',
                                    'Italic' => 'italic',
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'typography' => array(
                'section_title' => __('Typography', 'kwik'),
                'section_desc' => __('Style body, header and link text.', 'kwik'),
                'settings' => array(
                    'body_font' => array(
                        'type' => 'font',
                        'title' => __('Body Text', 'kwik'),
                        'value' => array(
                            'color' => '#222222',
                            'font-weight' => 'normal',
                            'font-size' => 14,
                            'line-height' => 20,
                            'font-family' => 'Open+Sans',
                        ),
                    ),
                    'link' => array(
                        'title' => 'Link',
                        'desc' => 'default style for hyperlinks',
                        'type' => 'multi',
                        'fields' => array(
                            'color' => array(
                                'type' => 'color',
                                'title' => __('Color: ', 'kwik'),
                                'value' => '#002C90',
                            ),
                            'style' => array(
                                'type' => 'cb_group',
                                'title' => __('Style: ', 'kwik'),
                                'value' => array('Underlined' => 'underline'),
                                'options' => array(
                                    'Underlined' => 'underline',
                                    'Bold' => 'bold',
                                    'Italic' => 'italic',
                                ),
                            ),
                        ),
                    ),
                    'link_hover' => array(
                        'title' => 'Link Hover',
                        'desc' => 'a:hover style for hyperlinks',
                        'type' => 'multi',
                        'fields' => array(
                            'color' => array(
                                'type' => 'color',
                                'title' => __('Color: ', 'kwik'),
                                'value' => '#002C90',
                            ),
                            'style' => array(
                                'type' => 'cb_group',
                                'title' => __('Style: ', 'kwik'),
                                'value' => array('Underlined' => 'underline'),
                                'options' => array(
                                    'Underlined' => 'underline',
                                    'Bold' => 'bold',
                                    'Italic' => 'italic',
                                ),
                            ),
                        ),
                    ),
                    'h1' => array(
                        'type' => 'font',
                        'title' => __('H1', 'kwik'),
                        'desc' => 'Header H1 Tags',
                        'value' => array(
                            'color' => '#222222',
                            'font-weight' => 'normal',
                            'font-size' => 24,
                            'line-height' => 30,
                            'font-family' => 'Open+Sans',
                        ),
                    ),
                    'h2' => array(
                        'type' => 'font',
                        'title' => __('H2', 'kwik'),
                        'desc' => 'Header H2 Tags',
                        'value' => array(
                            'color' => '#222222',
                            'font-weight' => 'normal',
                            'font-size' => 20,
                            'line-height' => 26,
                            'font-family' => 'Open+Sans',
                        ),
                    ),
                    'h3' => array(
                        'type' => 'font',
                        'title' => __('H3', 'kwik'),
                        'desc' => 'Header H3 Tags',
                        'value' => array(
                            'color' => '#222222',
                            'font-weight' => 'normal',
                            'font-size' => 16,
                            'line-height' => 20,
                            'font-family' => 'Open+Sans',
                        ),
                    ),
                ),
            ),
        );

        return apply_filters('kt_default_options', $kt_default_options);
    }

}// END KwikThemeOptions

if (is_admin()) {
    $kwik_theme_options = new KwikThemeOptions();
}
