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
    wp_enqueue_script('financial-chart-script', plugin_dir_file(__FILE__) . '/assets/js/financial-chart.js', ['chart-js'], '1.0', true);

    // Prepare data based on chart type
    if ($chart_type === 'line') {

        $data_points = get_field('data_points'); // Assuming 'data_points' is the repeater field
        $data_pairs = [];
        
        if ($data_points) {
            foreach ($data_points as $point) {
                $data_pairs[] = [
                    'x' => $point['x_value'] ?? '', // Ensure subfields are correctly named
                    'y' => $point['y_value'] ?? 0,
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

/*
function append_chart_canvas($content) {
    $chart_type = get_field('chart_type');
    $canvas_html = '';

    if ($chart_type === 'line') {
        $canvas_html = '<canvas id="lineChartCanvas"></canvas>';
    } elseif ($chart_type === 'pie') {
        $canvas_html = '<canvas id="pieChartCanvas"></canvas>';
    }

    return $content . $canvas_html;
}

add_filter('the_content', 'append_chart_canvas');
*/
?>
