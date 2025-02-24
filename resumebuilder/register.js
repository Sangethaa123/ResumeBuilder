document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registerForm");
    const errorMessage = document.getElementById("errorMessage");

    form.addEventListener("submit", function (event) {
        const email = form.email.value.trim();
        const phone = form.phone.value.trim();

        // Email validation
        if (!validateEmail(email)) {
            showError("Invalid email format!");
            event.preventDefault();
            return;
        }

        // Phone number validation (10-digit numbers)
        if (!/^\d{10}$/.test(phone)) {
            showError("Phone number must be 10 digits!");
            event.preventDefault();
            return;
        }

        // Hide error message if validation passes
        errorMessage.style.opacity = "0";
    });

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function showError(message) {
        errorMessage.textContent = message;
        errorMessage.style.opacity = "1";
    }

    // Button click effect
    const button = document.querySelector(".btn");
    button.addEventListener("mousedown", () => {
        button.style.transform = "scale(0.98)";
    });

    button.addEventListener("mouseup", () => {
        button.style.transform = "scale(1)";
    });
});
