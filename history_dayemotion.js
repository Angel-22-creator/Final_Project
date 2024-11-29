document.addEventListener("DOMContentLoaded", () => {
    const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    
    // Get the current month and year
    const currentDate = new Date();
    const currentMonth = currentDate.getMonth();  // 0-indexed (0 = January, 11 = December)
    const currentYear = currentDate.getFullYear();

    // Get the number of days in the current month
    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

    fetch('history_fetch_emotion.php')
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

            // Create a map of fetched dates (e.g., '2024-11-01') to colors
            const dateColorMap = {};
            data.forEach(entry => {
                dateColorMap[entry.date] = entry.color;
            });

            // Generate boxes for each day of the month
            for (let i = 1; i <= daysInMonth; i++) {
                const date = new Date(currentYear, currentMonth, i);
                const dayOfWeek = daysOfWeek[date.getDay()]; // Get day of the week (e.g., "Sun")
                const dateString = `${currentYear}-${(currentMonth + 1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`; // Format date as 'YYYY-MM-DD'

                const box = document.createElement("div");
                box.classList.add("day-box-history");
                box.style.backgroundColor = dateColorMap[dateString] || "gray"; // Default to gray if no color for the day
                box.textContent = `${dayOfWeek} ${i}`; // Display the day of the week and the date
                container.appendChild(box);
            }
        })
        .catch(err => {
            console.error("Error fetching data:", err); // Detailed error logging

            // If an error occurs, still generate default gray boxes
            const container = document.getElementById("emotionContainer");
            container.innerHTML = "";
            for (let i = 1; i <= daysInMonth; i++) {
                const date = new Date(currentYear, currentMonth, i);
                const dayOfWeek = daysOfWeek[date.getDay()]; // Get day of the week (e.g., "Sun")

                const box = document.createElement("div");
                box.classList.add("day-box");
                box.style.backgroundColor = "gray"; // Default to gray for errors
                box.textContent = `${dayOfWeek} ${i}`; // Display the day of the week and the date
                container.appendChild(box);
            }
        });
});