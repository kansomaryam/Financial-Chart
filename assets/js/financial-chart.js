document.addEventListener('DOMContentLoaded', function() {
    initLineChart();
    initPieChart();
});

function initLineChart() {
    var lineCanvas = document.getElementById('lineChartCanvas');
    if (lineCanvas) {
        try {
            var ctx = lineCanvas.getContext('2d');
            var labels = JSON.parse(lineCanvas.getAttribute('data-chart-labels') || '[]');
            var values = JSON.parse(lineCanvas.getAttribute('data-chart-values') || '[]');

            var lineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: lineCanvas.getAttribute('data-chart-title'),
                        data: values,
                        backgroundColor: lineCanvas.getAttribute('data-background-color'),
                        borderColor: lineCanvas.getAttribute('data-border-color'),
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: lineCanvas.getAttribute('data-y-axis-label')
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: lineCanvas.getAttribute('data-x-axis-label')
                            }
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error rendering the line chart:', error);
        }
    } else {
        console.error('Line chart canvas element not found!');
    }
}

function initPieChart() {
    var pieCanvas = document.getElementById('pieChartCanvas');
    if (pieCanvas) {
        try {
            var ctx = pieCanvas.getContext('2d');
            var labels = JSON.parse(pieCanvas.getAttribute('data-chart-labels') || '[]');
            var values = JSON.parse(pieCanvas.getAttribute('data-chart-values') || '[]');
            var colors = JSON.parse(pieCanvas.getAttribute('data-chart-colors') || '[]');

            var pieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: colors,
                        borderColor: 'white',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error rendering the pie chart:', error);
        }
    } else {
        console.error('Pie chart canvas element not found!');
    }
}
