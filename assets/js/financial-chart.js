document.addEventListener('DOMContentLoaded', function() {
    // Use specific selectors based on field keys or general selectors if needed
    const chartTypeSelect = document.querySelector('.chart-type-selector select');
    const dataPointsSection = document.querySelector('.data-points-section');
    const pieDataSection = document.querySelector('.pie-data-section');
    

    // Function to toggle form sections based on chart type selection
    function toggleFormSections() {
        if (chartTypeSelect && chartTypeSelect.value === 'line') {
            if (dataPointsSection) dataPointsSection.style.display = 'block'; // Show Data Points
            if (pieDataSection) pieDataSection.style.display = 'none'; // Hide Pie Data
        } else if (chartTypeSelect && chartTypeSelect.value === 'pie') {
            if (dataPointsSection) dataPointsSection.style.display = 'none'; // Hide Data Points
            if (pieDataSection) pieDataSection.style.display = 'block'; // Show Pie Data
        } else {
            // Hide both sections if another chart type (like "bar") is selected
            if (dataPointsSection) dataPointsSection.style.display = 'none';
            if (pieDataSection) pieDataSection.style.display = 'none';
        }
    }


    // Initialize form sections on page load
    toggleFormSections();

    // Event listener for chart type selection
    if (chartTypeSelect) {
        chartTypeSelect.addEventListener('change', toggleFormSections);
    }

    // Determine the canvas and context based on the chart type
    let canvasId = financialChartData.chartType === 'pie' ? 'pieChartCanvas' : 'lineChartCanvas';
    const canvasElement = document.getElementById(canvasId);

    if (!canvasElement) {
        console.error('No canvas element found for the given canvas ID:', canvasId);
        return; // Exit if the canvas element is not found
    }

    const ctx = canvasElement.getContext('2d');

    // Check if chartData exists and has necessary properties
    if (typeof financialChartData === 'undefined' || !financialChartData.chartType) {
        console.error('Chart data or type is undefined.');
        return;
    }

    // Initialize the correct type of chart based on the chartType
    if (financialChartData.chartType === 'pie') {
        // Pie chart specific data structure
        const pieData = {
            labels: financialChartData.labels,
            datasets: [{
                data: financialChartData.data,
                backgroundColor: financialChartData.backgroundColor || ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#F77825', '#9966FF', '#C9CB3A'],
                hoverOffset: 4
            }]
        };

        new Chart(ctx, {
            type: 'pie',
            data: pieData,
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { mode: 'index', intersect: false }
                }
            }
        });
    } else if (financialChartData.chartType === 'line') {
        // Line chart specific data structure
        const lineData = {
            labels: financialChartData.dataPairs.map(pair => pair.x), // x-axis labels
            datasets: [{
                label: financialChartData.chartTitle,
                data: financialChartData.dataPairs.map(pair => pair.y), // y-axis data points
                borderColor: financialChartData.chartColor,
                backgroundColor: financialChartData.chartColor + '33', // Slightly transparent
                fill: true,
                tension: 0.4 // Curved line
            }]
        };

        new Chart(ctx, {
            type: 'line',
            data: lineData,
            options: {
                responsive: true,
                plugins: {
                    title: { display: true, text: financialChartData.chartTitle }
                },
                scales: {
                    x: { title: { display: true, text: 'X-Axis Label' } },
                    y: { title: { display: true, text: 'Y-Axis Value' }, beginAtZero: true }
                }
            }
        });
    } else {
        console.error('Unsupported chart type:', financialChartData.chartType);
    }
});
