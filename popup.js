// Initialize variables
let selectedEmotion = ""; // Stores the selected emotion
let intensity = 5; // Default intensity value

// Show popup when clicking the "Go" button
document.getElementById('start').addEventListener('click', () => {
    document.getElementById('emotion-popup').classList.remove('hidden'); // Show popup
});

// Close popup when clicking the "Exit" button
document.getElementById('exit').addEventListener('click', () => {
    document.getElementById('emotion-popup').classList.add('hidden'); // Hide popup
});

// Step 1: Set Emotion
function setEmotion(emotion) {
    selectedEmotion = emotion;
    document.getElementById("intensity-rating").classList.remove("hidden"); // Show intensity section
    document.querySelector(".emotion-buttons").style.display = "none"; // Hide emotion buttons
}

// Step 2: Update Intensity Value
function updateIntensity() {
    intensity = document.getElementById("intensity").value;
    document.getElementById("intensity-value").textContent = intensity;
}

// Step 3: Move to Optional Text Section
function nextStep() {
    document.getElementById("intensity-rating").classList.add("hidden"); // Hide intensity section
    document.getElementById("optional-text").classList.remove("hidden"); // Show optional text section
}

// Step 4: Submit Emotion Data
function submitEmotion() {
    const notes = document.getElementById("notes").value; // Get optional text
    const formData = new FormData();
    formData.append("emotion", selectedEmotion);
    formData.append("intensity", intensity);
    formData.append("notes", notes);
    formData.append("user_id", userId); // Append user ID if available

    fetch("submit_feeling.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then((data) => {
            if (data.success) {
                alert("Your emotion has been recorded!");
                document.getElementById("emotion-popup").classList.add("hidden"); // Hide popup
            } else {
                alert(`Error: ${data.message}`);
            }
        })
        .catch((error) => {
            console.error("Fetch error:", error);
        });
}
