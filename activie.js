// Fetch data from the PHP endpoint
async function fetchData() {
    const response = await fetch('active.php'); // Assuming `data.php` returns the data
    const data = await response.json();

    const labels = data.map(item => item.week);
    const dataset = data.map(item => item.count);

    // Render the chart
    const ctx = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'line', // Line chart type
        data: {
            labels: labels,
            datasets: [{
                label: 'Active Count',
                data: dataset,
                fill: false,
                borderColor: '#ffd21f',
                tension: 0.3, // Smooth curve
                pointBackgroundColor: '#ffd21f'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
               
            },
            scales: {
                x: { title: { display: true, text: 'Week' } },
                y: { title: { display: true, text: 'Count' } }
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', fetchData);
