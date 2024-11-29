document.addEventListener("DOMContentLoaded", () => {
    const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];

    fetch('fetch_emotions.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Fetched data:", data); // Debugging: log the response
            const container = document.getElementById("emotionContainer");
            container.innerHTML = ""; // Clear the container before populating

            // Create a map of fetched days to colors
            const dayColorMap = {};
            data.forEach(day => {
                dayColorMap[day.day] = day.color;
            });

            // Generate 7 boxes for the days of the week
            daysOfWeek.forEach(day => {
                const box = document.createElement("div");
                box.classList.add("day-box");
                box.style.backgroundColor = dayColorMap[day] || "gray"; // Default to gray if no color for the day
                box.textContent = day;
                container.appendChild(box);
            });
        })
        .catch(err => {
            console.error("Error fetching data:", err); // Detailed error logging

            // If an error occurs, still generate 7 default gray boxes
            const container = document.getElementById("emotionContainer");
            container.innerHTML = "";
            daysOfWeek.forEach(day => {
                const box = document.createElement("div");
                box.classList.add("day-box");
                box.style.backgroundColor = "gray"; // Default to gray for errors
                box.textContent = day;
                container.appendChild(box);
            });
        });
});
