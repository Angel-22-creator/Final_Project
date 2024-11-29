document.addEventListener("DOMContentLoaded", () => {
    fetch('fetch-feeling-week.php')
        .then(response => response.json())
        .then(data => {
            // Prepare the data for the pie chart
            const positiveCount = data.positive || 0;
            const negativeCount = data.negative || 0;

            const ctx = document.getElementById('emotionPieChart').getContext('2d');
            const emotionPieChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Positive', 'Negative'],
                    datasets: [{
                        data: [positiveCount, negativeCount],
                        backgroundColor: ['#ffcc00', '#ff3300'], // Yellow for positive, red for negative
                        borderColor: ['#e6b800', '#e60000'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(tooltipItem) {
                                    return tooltipItem.label + ': ' + tooltipItem.raw;
                                }
                            }
                        }
                    }
                }
            });
        })
        .catch(err => {
            console.error("Error fetching data:", err);
        });
});