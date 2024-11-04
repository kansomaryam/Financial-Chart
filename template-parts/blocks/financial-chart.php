<?php
/**
 * Financial Chart Block Template
 */

// Enqueue and localize scripts
add_action('wp_enqueue_scripts', 'enqueue_and_localize_chart_scripts');

function enqueue_and_localize_chart_scripts() {
    // Get ACF fields
    $chart_title = get_field('chart_title');
    $chart_type = get_field('chart_type');
    $chart_color = get_field('chart_colors') ?: '#007bff';

    // Enqueue Chart.js and custom script
    wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', [], null, true);
    wp_enqueue_script('financial-chart-script', get_template_directory_uri() . '/assets/js/financial-chart.js', ['chart-js'], '1.0', true);

    // Prepare data based on chart type
    if ($chart_type === 'line') {
        $x_data_values = get_field('data_points');
        $data_pairs = [];

        if (is_array($x_data_values)) {
            foreach ($x_data_values as $data) {
                $data_pairs[] = [
                    'x' => $data['x_value'] ?? '',
                    'y' => $data['y_value'] ?? 0,
                ];
            }
        }

        wp_localize_script('financial-chart-script', 'financialChartData', [
            'chartTitle' => $chart_title,
            'chartType' => $chart_type,
            'chartColor' => $chart_color,
            'dataPairs' => $data_pairs,
        ]);
    } elseif ($chart_type === 'pie') {
        $pie_data = get_field('pie_data');
        $labels = [];
        $data = [];

        if ($pie_data) {
            foreach ($pie_data as $entry) {
                $labels[] = $entry['pie_label'];
                $data[] = $entry['percentage'];
            }
        }

        wp_localize_script('financial-chart-script', 'financialChartData', [
            'chartTitle' => $chart_title,
            'chartType' => $chart_type,
            'labels' => $labels,
            'data' => $data,
            'backgroundColor' => ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#F77825', '#9966FF', '#C9CB3A'],
        ]);
    }
}

// Output canvas element
function output_chart_canvas() {
    $chart_type = get_field('chart_type');

    if ($chart_type === 'line') {
        echo '<canvas id="lineChartCanvas"></canvas>';
    } elseif ($chart_type === 'pie') {
        echo '<canvas id="pieChartCanvas"></canvas>';
    }
}

// Output chart canvas directly in the template
output_chart_canvas();
?>
