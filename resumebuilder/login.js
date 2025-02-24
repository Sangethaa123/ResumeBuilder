document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    const errorMessage = document.getElementById("errorMessage");
    const successPopup = document.getElementById("successPopup");

    form.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        const email = document.getElementById("email").value.trim();
        const password = document.getElementById("password").value.trim();

        // Basic validation
        if (email === "" || password === "") {
            showError("All fields are required!");
            return;
        }

        // Send login request using Fetch API
        fetch("login.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                // Show success popup
                successPopup.style.display = "block";
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
            showError("Something went wrong. Try again!");
        });
    });

    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.style.display = "block";
        errorMessage.classList.add("shake");

        setTimeout(() => {
            errorMessage.classList.remove("shake");
        }, 500);
    }

    // Close success popup and redirect to home page
    window.closePopup = function () {
        successPopup.style.display = "none";
        window.location.href = "index.html"; // Redirect to home page
    };
});
