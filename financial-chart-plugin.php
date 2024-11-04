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



// Check if ACF is installed and active
if ( function_exists('acf_register_block_type') ) {
    add_action('acf/init', 'register_financial_chart_block');
}

function register_financial_chart_block() {
    acf_register_block_type(array(
        'name'              => 'financial-chart',
        'title'             => __('Financial Chart'),
        'description'       => __('A block to display financial data in a chart.'),
        'render_template'   => 'template-parts/blocks/financial-chart.php',
        'category'          => 'widgets',
        'icon'              => 'chart-line',
        'keywords'          => array('chart', 'financial'),
        'enqueue_assets'    => function() {
            wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.0', true);
            wp_enqueue_script('financial-chart-script', plugin_dir_url(__FILE__) . 'assets/js/financial-chart.js', array('chart-js'), '1.0', true);
            wp_enqueue_style('financial-chart-style', plugin_dir_url(__FILE__) . 'assets/css/financial-chart.css'); // If you have CSS for styling
        },
        'supports' => array(
            'align' => true,
            'multiple' => true,
        ),
    ));
}
