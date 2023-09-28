<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://google.com
 * @since      1.0.0
 *
 * @package    Sns_Action
 * @subpackage Sns_Action/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sns_Action
 * @subpackage Sns_Action/admin
 * @author     pleaz <oprstfaq@gmail.com>
 */
class Sns_Action_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Sns_Action_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Sns_Action_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        /*wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/sns-action-admin.css', array(), $this->version, 'all');*/
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Sns_Action_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Sns_Action_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        /*wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/sns-action-admin.js', array( 'jquery' ), $this->version, false);*/
    }

    /**
     * Add an options page under the Settings submenu
     *
     * @since  1.0.0
     */
    public function options_page()
    {
        add_menu_page(
            __('SNS Action plugin', 'sns-action'),
            __('SNS Action', 'sns-action'),
            'manage_options',
            $this->plugin_name,
            [$this, 'display_options_page']
        );

        register_setting('snsaction-settings', 'snsaction_aws_key');
        register_setting('snsaction-settings', 'snsaction_aws_secret');
        register_setting('snsaction-settings', 'snsaction_aws_region');
        register_setting('snsaction-settings', 'snsaction_aws_account');
        register_setting('snsaction-settings', 'snsaction_prefix');
    }

    /**
     * Render the options page for plugin
     *
     * @since  1.0.0
     */
    public function display_options_page()
    {
        include_once('partials/sns-action-admin-display.php');
    }

    public function prefix_register_post_routes()
    {
        register_rest_route('sns-action/v1', '/form', [
            'methods'  => WP_REST_Server::CREATABLE,
            'callback' => [$this, 'prefix_get_endpoint_post']
        ]);
    }

    public function prefix_get_endpoint_post()
    {
        $awsKey = get_option('snsaction_aws_key');
        $awsSecret = get_option('snsaction_aws_secret');
        $awsRegion = get_option('snsaction_aws_region');
        $awsAccount = get_option('snsaction_aws_account');
        $prefix = get_option('snsaction_prefix');

        $snsClient = new Aws\SNS\SnsClient([
            'credentials' => [
                'key' => $awsKey,
                'secret' => $awsSecret,
            ],
            'version' => 'latest',
            'region'  => $awsRegion
        ]);

        if (str_contains($_SERVER['SERVER_NAME'], 'easyalquiler')) {
            $country = 'easyalquiler';
        } elseif (str_contains($_SERVER['SERVER_NAME'], 'easynoleggio')) {
            $country = 'easynoleggio';
        } elseif (str_contains($_SERVER['SERVER_NAME'], 'easyaluguer')) {
            $country = 'easyaluguer';
        } else {
            $country = 'easytoolhire';
        }

        $territoryId = [
            'prod' => [
                'easynoleggio' => 31000000639,
                'easyalquiler' => 31000000640,
                'easytoolhire' => 31000000667,
                'easyaluguer' => 31000000865,
            ],
            'stage' => [
                'easynoleggio' => 22000001218,
                'easyalquiler' => 22000001219,
                'easytoolhire' => 22000001220,
                'easyaluguer' => 22000001749,
            ],
        ];

        $postData = $_POST;
        $eventName = $prefix . '_submit_enquiry';
        $topicArn = 'arn:aws:sns:' . $awsRegion . ':' . $awsAccount . ':' . $eventName;

        $message = [
            'contact' => [
                'email' => $postData['email'],
                'name' => $postData['name'],
                'phone' => $postData['phone'] ?? null,
                'type' => isset($postData['company']) ? 'company' : 'individual',
                'company' => $postData['company'] ?? null,
                'lead_source' => $postData['source'],
                'subscription' => isset($postData['subscription']) ?? false,
            ],
            'deal' => [
                'email' => $postData['email'],
                'phone' => $postData['phone'] ?? null,
                'name' => $postData['name'],
                'lead_source' => $postData['source'],
                'page_url' => $postData['page_url'],
                'message' => $postData['message'] ?? null,
            ],
            'territoryId' => $territoryId[$prefix][$country],
        ];

        if (isset($postData['utm'])) {
            $utm = json_decode(stripslashes($postData['utm']), true);
            $message = array_merge($message, ['utm' => $utm]);
            // 'utm_source' 'utm_medium' 'utm_campaign' 'utm_landing_page' 'utm_keyword' 'utm_content'
        }

        $snsClient->publish([
            'Message'           => json_encode($message),
            'TopicArn'          => $topicArn,
            'MessageAttributes' => [
                'MICRO_BUS.JOB_UUID' => [
                    'DataType'    => 'String',
                    'StringValue' => wp_generate_uuid4(),
                ],
            ],
        ]);
    }
}
