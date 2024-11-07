<?php
// Fetch chart type from ACF
$chart_type = get_field('chart_type'); // Should be 'line' or 'pie'
$chart_title = get_field('chart_title');
$data_points = get_field('data_points'); // For line chart
$chart_data = get_field('chart_data'); // For pie chart
$y_axis_label = get_field('y_axis_label');
$x_axis_label = get_field('x_axis_label');
$background_color = get_field('background_color') ?: 'rgba(75, 192, 192, 0.2)'; // Default color
$border_color = get_field('border_color') ?: 'rgb(75, 192, 192)'; // Default color

// Initialize data arrays
$chart_labels = [];
$chart_values = [];
$chart_colors = [];

if ($chart_type === 'line') {
    // Line Chart Data Processing
    if (is_array($data_points) && !empty($data_points)) {
        $chart_labels = array_map(function($item) { return $item['label']; }, $data_points);
        $chart_values = array_map(function($item) { return $item['value']; }, $data_points);
    } else {
        echo '<p class="chart-error">No data points available for the line chart.</p>';
        return; // Stop execution if there's no valid data
    }
    ?>
    <div class="line-chart-container">
        <canvas id="lineChartCanvas"
                data-chart-type="line"
                data-chart-title="<?php echo esc_attr($chart_title); ?>"
                data-chart-labels="<?php echo htmlspecialchars(json_encode($chart_labels), ENT_QUOTES, 'UTF-8'); ?>"
                data-chart-values="<?php echo htmlspecialchars(json_encode($chart_values), ENT_QUOTES, 'UTF-8'); ?>"
                data-background-color="<?php echo esc_attr($background_color); ?>"
                data-border-color="<?php echo esc_attr($border_color); ?>"
                data-y-axis-label="<?php echo esc_attr($y_axis_label); ?>"
                data-x-axis-label="<?php echo esc_attr($x_axis_label); ?>">
        </canvas>
    </div>
    <?php
} elseif ($chart_type === 'pie') {
    // Pie Chart Data Processing
    $total_value = 0;
    if (is_array($chart_data) && !empty($chart_data)) {
        foreach ($chart_data as $item) {
            $total_value += $item['value'];
        }
        if ($total_value === 100) {
            foreach ($chart_data as $item) {
                $chart_labels[] = $item['label'];
                $chart_values[] = $item['value'];
                $chart_colors[] = $item['color'];
            }
        } else {
            echo '<p class="chart-error">The sum of the values must equal 100 for the pie chart to display.</p>';
            return;
        }
    } else {
        echo '<p class="chart-error">No data points available for the pie chart.</p>';
        return;
    }
    ?>
    <div class="acf-pie-chart-block">
        <canvas id="pieChartCanvas"
                data-chart-type="pie"
                data-chart-title="<?php echo esc_attr($chart_title); ?>"
                data-chart-labels="<?php echo htmlspecialchars(json_encode($chart_labels), ENT_QUOTES, 'UTF-8'); ?>"
                data-chart-values="<?php echo htmlspecialchars(json_encode($chart_values), ENT_QUOTES, 'UTF-8'); ?>"
                data-chart-colors="<?php echo htmlspecialchars(json_encode($chart_colors), ENT_QUOTES, 'UTF-8'); ?>">
        </canvas>
    </div>
    <?php
} else {
    echo '<p class="chart-error">Invalid chart type specified.</p>';
}
