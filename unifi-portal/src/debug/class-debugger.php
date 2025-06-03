<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}

/**
 * Custom Debugger class used by the plugin.
 *
 * Used to print pretty errors and aid in development.
 *
 * @since 1.0.0
 */
class Debugger
{
    protected static $is_started = false;

    protected const HTML_CLASSNAME = UNI_PLUGIN_SLUG . '-debugger-print-info';

    protected const STYLES = [
        'display' => 'block !important',
        'background-color' => '#000000 !important',
        'color' => '#ffffff !important',
        'font-family' => 'cursive !important',
        'padding' => '1rem',
        'margin' => '1rem',
        'border-radius' => '1rem',
        'width' => 'max-content',
        'height' => 'max-content'
    ];

    /**
     * Checks if debugging is enabled.
     *
     * @return bool True if debugging is enabled, false otherwise.
     */
    public static function is_debugging()
    {
        return UNI_PLUGIN_ENV_MODE === 'DEV' && UNI_PLUGIN_DEBUG === true;
    }

    protected static function is_first_time($class, $style_string)
    {
        if (!self::$is_started):

            $first_time_txt = self::get_robo_svg_icon();
            $poem = self::show_poem();

            echo <<<HTML
      <div class="{$class}" style="{$style_string}">
        {$first_time_txt}
{$poem}
      </div>
HTML;
        endif;
    }

    /**
     * Outputs debug information.
     *
     * @param  mixed  $echo  The data to debug.
     * @param  string $title The title for the debug output.
     * @param  string $type  The type of debug output ('print_r' or 'var_dump').
     * @return void
     */
    public static function debug($echo = '', $title = 'Debug', $type = 'print_r')
    {
        if (!self::is_debugging()) :
            return;
        endif;

        $class = self::HTML_CLASSNAME;

        $styles = self::STYLES;
        $style_string = implode(
            '; ',
            array_map(
                fn($k, $v) => "$k: $v",
                array_keys($styles),
                $styles
            )
        );

        // self::is_first_time($class, $style_string);
        self::$is_started = true;

        echo <<<HTML
    <div class="{$class}" style="{$style_string}">
        <pre style="background-color: {$styles['background-color']}; color: {$styles['color']}; font-family: {$styles['font-family']};">
HTML;

        if ($type === 'print_r') :
            echo $title . ' :: ' . print_r($echo, true);
        else :
            echo var_dump($echo);
        endif;

        echo '</pre></div>';
    }

    /**
     * Outputs constants for debugging.
     *
     * @return void
     */
    public static function constants()
    {
        self::debug(UNI_PLUGIN_FILE, 'Plugin File');
        self::debug(UNI_PLUGIN_PATH, 'Plugin Path');
        self::debug(UNI_PLUGIN_SRC_PATH, 'Plugin Src Path');
        self::debug(UNI_PLUGIN_SLUG, 'Plugin Slug');
        self::debug(UNI_PLUGIN_NAMESPACE, 'Plugin Namespace');
        self::debug(UNI_PLUGIN_ENV_MODE, 'Plugin Env Mode');
        self::debug(UNI_PLUGIN_DEBUG, 'Plugin Debug Mode');

        if (self::is_debugging()) :
            exit;
        endif;
    }

    /**
     * Logs errors if they occur.
     *
     * @return void
     */
    public static function log_errors()
    {
        $error = error_get_last();
        if (
            $error
            && in_array($error['type'], [E_ERROR, E_PARSE, E_COMPILE_ERROR, E_USER_ERROR, E_RECOVERABLE_ERROR], true)
        ) :
            self::show_error(
                __FILE__ . ' - Register Shutdown...',
                $error['file'],
                $error['line'],
                $error['message']
            );
        endif;
    }

    /**
     * Shows error details.
     *
     * @param  string $from    Source of the error.
     * @param  string $file    File where the error occurred.
     * @param  string $line    Line number of the error.
     * @param  string $message Error message.
     * @return void
     */
    public static function show_error($from = 'Default', $file = '', $line = '', $message = '')
    {
        self::debug($from, 'Triggered By');
        self::debug($file, 'File');
        self::debug($line, 'Line No');
        self::debug($message, 'Message');

        if (self::is_debugging()) :
            exit;
        endif;
    }

    /**
     * Echoes the HTML for the poem "A Tale of Two Cities" for testing.
     *
     * This method outputs the opening lines of the novel "A Tale of Two Cities" by Charles Dickens.
     * The text is wrapped inside a div container with the class "poem" for styling purposes.
     *
     * @return void
     */
    public static function show_poem()
    {
        echo '<div class="poem">';
        echo '<h2>A Tale of Two Cities</h2>';
        echo '<p>It was the best of times, it was the worst of times,</p>';
        echo '<p>it was the age of wisdom, it was the age of foolishness,</p>';
        echo '<p>it was the epoch of belief, it was the epoch of incredulity,</p>';
        echo '<p>it was the season of Light, it was the season of Darkness,</p>';
        echo '<p>it was the spring of hope, it was the winter of despair,</p>';
        echo '<p>we had everything before us, we had nothing before us,</p>';
        echo '<p>we were all going direct to Heaven, we were all going direct the other way—in short,</p>';
        echo '<p>the period was so far like the present period, that some of its noisiest authorities insisted on its being received, for good or for evil, in the superlative degree of comparison only.</p>';
        echo '</div>';
    }

    /**
     * Returns the SVG icon for debugging.
     *
     * @return string SVG icon or empty string if not defined.
     */
    public static function get_robo_svg_icon()
    {
        $svg = '

<svg height="70px" width="70px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
  <path style="fill:#144856;" d="M170.515,336.695c0,4.61-3.738,8.348-8.348,8.348H48.08v94.609h11.904
	c5.737,0,11.154,2.51,14.862,6.888c3.709,4.378,5.295,10.133,4.352,15.793l-7.116,42.692c-0.681,4.083-4.218,6.977-8.224,6.977
	c-0.456,0-0.918-0.038-1.382-0.115c-4.548-0.758-7.62-5.059-6.862-9.607l7.116-42.692c0.185-1.106-0.311-1.889-0.622-2.256
	c-0.312-0.368-1.001-0.984-2.124-0.984H19.481c-1.122,0-1.812,0.617-2.124,0.984c-0.312,0.367-0.806,1.15-0.622,2.256l7.116,42.692
	c0.758,4.547-2.314,8.849-6.862,9.607c-4.545,0.757-8.849-2.314-9.607-6.862l-7.116-42.692c-0.943-5.659,0.643-11.415,4.352-15.793
	c3.709-4.378,9.125-6.888,14.862-6.888h11.904V336.695c0-4.61,3.738-8.348,8.348-8.348h122.435
	C166.778,328.347,170.515,332.084,170.515,336.695z" />
  <path style="fill:#84A7B7;" d="M342.261,503.652c0,4.61-3.738,8.348-8.348,8.348H178.087c-4.61,0-8.348-3.738-8.348-8.348
	s3.738-8.348,8.348-8.348h155.826C338.524,495.304,342.261,499.042,342.261,503.652z" />
  <path style="fill:#256277;" d="M382.65,305.921l-29.176,116.704c-4.954,19.819-22.762,33.723-43.192,33.723H201.718
	c-20.429,0-38.238-13.904-43.193-33.723l-29.176-116.704c-3.512-14.05,7.115-27.66,21.597-27.66h210.108
	C375.537,278.261,386.163,291.871,382.65,305.921z M256,0L256,0C169.94,0,100.174,69.766,100.174,155.826v22.261
	c0,24.588,19.933,44.522,44.522,44.522h222.609c24.588,0,44.522-19.933,44.522-44.522v-22.261C411.826,69.766,342.061,0,256,0z" />
  <path style="fill:#84A7B7;" d="M345.044,278.261l-19.893,79.575c-7.933,31.731-36.443,53.99-69.15,53.99l0,0
	c-32.707,0-61.217-22.26-69.15-53.99l-6.064-24.254c-7.024-28.1,14.228-55.321,43.193-55.321H345.044z" />
  <path style="fill:#144856;" d="M496.996,349.17c-1.484,2.61-4.255,4.222-7.257,4.222H278.261c-4.61,0-8.348-3.738-8.348-8.348
	c0-4.61,3.738-8.348,8.348-8.348h196.795L346.748,121.139c-2.359-3.961-1.059-9.085,2.904-11.443
	c3.962-2.36,9.085-1.057,11.443,2.904l135.818,228.174C498.448,343.353,498.48,346.56,496.996,349.17z" />
  <path style="fill:#FC8059;" d="M197.565,388.452v1.113c0,4.61-3.738,8.348-8.348,8.348c-4.61,0-8.348-3.738-8.348-8.348v-1.113
	c0-4.61,3.738-8.348,8.348-8.348C193.828,380.104,197.565,383.842,197.565,388.452z" />
  <path style="fill:#FFD880;" d="M230.957,388.452v1.113c0,4.61-3.738,8.348-8.348,8.348c-4.61,0-8.348-3.738-8.348-8.348v-1.113
	c0-4.61,3.738-8.348,8.348-8.348C227.219,380.104,230.957,383.842,230.957,388.452z" />
  <path style="fill:#B5E5BC;" d="M320,388.452v1.113c0,4.61-3.738,8.348-8.348,8.348c-4.61,0-8.348-3.738-8.348-8.348v-1.113
	c0-4.61,3.738-8.348,8.348-8.348C316.263,380.104,320,383.842,320,388.452z" />
  <path style="fill:#CFDFE2;" d="M211.479,356.174h-33.391c-6.147,0-11.13-4.983-11.13-11.13v-22.261c0-6.147,4.983-11.13,11.13-11.13
	h33.391c6.147,0,11.13,4.983,11.13,11.13v22.261C222.609,351.191,217.626,356.174,211.479,356.174z" />
  <path style="fill:#256277;" d="M512,333.913c0,18.441-14.949,33.391-33.391,33.391s-33.391-14.95-33.391-33.391
	c0-18.441,14.949-33.391,33.391-33.391S512,315.472,512,333.913z M44.522,300.522c-18.442,0-33.391,14.95-33.391,33.391
	c0,18.441,14.949,33.391,33.391,33.391s33.391-14.95,33.391-33.391C77.913,315.472,62.964,300.522,44.522,300.522z" />
  <path style="fill:#84A7B7;" d="M66.783,333.913c0,12.294-9.966,22.261-22.261,22.261s-22.261-9.967-22.261-22.261
	c0-12.295,9.966-22.261,22.261-22.261S66.783,321.618,66.783,333.913z M478.609,311.652c-12.295,0-22.261,9.966-22.261,22.261
	c0,12.294,9.966,22.261,22.261,22.261c12.295,0,22.261-9.967,22.261-22.261C500.87,321.618,490.904,311.652,478.609,311.652z" />
  <path style="fill:#144856;" d="M155.826,116.87c0-30.736,24.917-55.652,55.652-55.652s55.652,24.917,55.652,55.652
	s-24.917,55.652-55.652,55.652S155.826,147.605,155.826,116.87z" />
  <path style="fill:#CFDFE2;" d="M178.087,116.87c0-18.442,14.949-33.391,33.391-33.391s33.391,14.949,33.391,33.391
	s-14.949,33.391-33.391,33.391S178.087,135.312,178.087,116.87z" />
  <path style="fill:#FFFFFF;" d="M211.479,116.87c0-9.22,7.475-16.696,16.696-16.696s16.696,7.475,16.696,16.696
	s-7.475,16.696-16.696,16.696S211.479,126.09,211.479,116.87z" />
  <path style="fill:#84A7B7;" d="M433.248,34.231c45.64,45.64,45.64,119.638,0,165.278s-119.638,45.64-165.278,0
	s-45.64-119.638,0-165.278S387.608-11.41,433.248,34.231z" />
  <path style="fill:#C3E4ED;" d="M428.522,116.87c0,43.03-34.883,77.913-77.913,77.913s-77.913-34.883-77.913-77.913
	s34.883-77.913,77.913-77.913S428.522,73.839,428.522,116.87z" />
  <path style="fill:#DCF3F9;" d="M424.003,90.698l-99.566,99.566c-14.562-5.193-27.13-14.585-36.228-26.734l109.06-109.06
	C409.418,63.567,418.81,76.136,424.003,90.698z M376.781,43.476l-99.566,99.566c1.303,3.656,2.869,7.188,4.681,10.568
	L387.349,48.157C383.968,46.345,380.437,44.779,376.781,43.476z" />
  <path style="fill:#FFFFFF;" d="M350.956,58.609c12.295,0,22.261,9.966,22.261,22.261s-9.966,22.261-22.261,22.261
	c-12.295,0-22.261-9.966-22.261-22.261S338.663,58.609,350.956,58.609z" />
</svg>
         ';

        return $svg;
    }
}
