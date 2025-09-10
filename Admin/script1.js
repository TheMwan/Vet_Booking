// Check if user is logged in by checking local storage
let isLoggedIn = localStorage.getItem("isLoggedIn") === "true";

// Function to check login before allowing booking or status check
function checkLoginAndRedirect(targetPage) {
    if (isLoggedIn) {
        window.location.href = targetPage;
    } else {
        alert("Please log in first to access this feature.");
        window.location.href = "login.html"; // Redirect to login page
    }
}

// Function to simulate a login action
function logInUser() {
    isLoggedIn = true;
    localStorage.setItem("isLoggedIn", "true"); // Store login state in local storage
    alert("Logged in successfully!");
    window.location.href = "index.html"; // Redirect to the homepage or dashboard
}

// Function to simulate a logout action
function logOutUser() {
    isLoggedIn = false;
    localStorage.setItem("isLoggedIn", "false"); // Clear login state from local storage
    alert("Logged out successfully!");
    window.location.href = "index.html"; // Redirect to the homepage
}