<?php
/**
 * Plugin Name: Financial Chart Plugin
 * Description: A plugin that provides a Financial Chart block using ACF.
 * Version: 1.0
 * Author: Maryam K
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


// Add a custom admin menu for the plugin
add_action('admin_menu', 'financial_chart_plugin_menu');

function financial_chart_plugin_menu() {
    add_menu_page(
        'Financial Chart Settings', // Page title
        'Financial Chart',          // Menu title
        'manage_options',           // Capability
        'financial-chart',          // Menu slug
        'financial_chart_settings_page', // Function to display the settings page
        'dashicons-chart-line',     // Icon (choose any Dashicon)
        6                           // Position in the menu
    );
}

// Function to display the settings page
function financial_chart_settings_page() {
    ?>
    <div class="wrap">
        <h1>Financial Chart Plugin</h1>
        <p>You can now add the <strong>Financial Chart</strong> block to your posts and pages using the WordPress block editor.</p>
        <p>To create a financial chart, simply insert the block and configure the settings as needed.</p>
    </div>
    <?php
}


function register_acf_block_types() {
    // Check function exists.
    if( function_exists('acf_register_block_type') ) {

        // Register a Financial Chart block.
        acf_register_block_type(array(
            'name'              => 'financial-chart',
            'title'             => __('Financial Chart'),
            'description'       => __('A custom block to display financial charts.'),
            'render_template'   => plugin_dir_path(__FILE__) . 'template-parts/blocks/financial-chart.php',
            'category'          => 'formatting',
            'icon'              => 'chart-line',
            'keywords'          => array('financial', 'chart', 'money'),
        ));
    }
}
add_action('acf/init', 'register_acf_block_types');



function financial_chart_assets() {
    // Enqueue JavaScript
    wp_enqueue_script(
        'financial-chart-js',
        plugins_url( '/assets/js/financial-chart.js', __FILE__ ),
        array( 'jquery' ), // Dependencies (if jQuery is needed)
        '1.0.0', // Version number
        true // Load in footer
    );

    // Enqueue CSS
    wp_enqueue_style(
        'financial-chart-css',
        plugins_url( '/assets/css/financial-chart.css', __FILE__ ),
        array(), // Dependencies
        '1.0.0' // Version number
    );
}
add_action( 'enqueue_block_editor_assets', 'financial_chart_assets' ); // Editor only
add_action( 'wp_enqueue_scripts', 'financial_chart_assets' ); // Front end
