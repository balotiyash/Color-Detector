document.getElementById('uploadForm').onsubmit = function(event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(this); // Create FormData object
    const loadingDiv = document.getElementById('loading');
    const resultDiv = document.getElementById('result');

    // Show loading spinner
    loadingDiv.style.display = 'block';
    resultDiv.innerHTML = ''; // Clear the result div before starting

    fetch('process_image.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.text())
    .then(data => {
        // Hide loading spinner
        loadingDiv.style.display = 'none';

        // Update the result div with the server's response
        resultDiv.innerHTML = data;
    })
    .catch(error => {
        // Hide loading spinner
        loadingDiv.style.display = 'none';

        // Log any errors
        console.error('Error:', error);
    });
};

// Reset the form and clear the result div
document.getElementById('uploadForm').onreset = function() {
    document.getElementById('result').innerHTML = ''; // Clear the result div
};
