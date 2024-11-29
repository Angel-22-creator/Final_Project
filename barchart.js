window.onload = function() {
    // Fetch the emotion data for the bar chart when the page loads
    fetch('fetch_highest_emotion.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderBarChart(data.data);  // Render the bar chart with the fetched data
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
};

// Function to render the bar chart using the fetched data
function renderBarChart(data) {
    const categories = [];
    const frequencies = [];

    // Loop through the data to extract categories (emotions) and their frequencies
    data.forEach(item => {
        categories.push(item.emotion);  // Emotion category (e.g., Happy, Sad)
        frequencies.push(item.frequency);  // Frequency of each emotion
    });

    const ctx = document.getElementById('emotionBarChart').getContext('2d');
    
    // Create the bar chart
    const emotionBarChart = new Chart(ctx, {
        type: 'bar',  // Bar chart type
        data: {
            labels: categories,  // Labels are the emotion categories (x-axis)
            datasets: [{
                label: 'Emotion Frequency',
                data: frequencies,  // Frequency values for each emotion category
                backgroundColor: '#ffcc00', // Bar color
                borderColor: '#ffcc00', // Border color for the bars
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true  // Start the y-axis from 0
                }
            }
        }
    });
}