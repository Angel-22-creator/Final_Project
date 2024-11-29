document.addEventListener("DOMContentLoaded", () => {
    fetch('fetch_feelings.php')
        .then(response => response.json())
        .then(data => {
            // Prepare the data for the doughnut chart
            const positiveCount = data.positive || 0;
            const negativeCount = data.negative || 0;

            const ctx = document.getElementById('emotionDoughnutChart').getContext('2d');
            const emotionDoughnutChart = new Chart(ctx, {
                type: 'doughnut', // Changed to doughnut
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
                    },
                    cutout: '50%' // Adjust this value for the inner radius (50% creates a hollow center)
                }
            });
        })
        .catch(err => {
            console.error("Error fetching data:", err);
        });
});
