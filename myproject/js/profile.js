// 
// Author: Nikul Ram
// This script handles loading the user's profile information and updating user links based on session status.
//

// Event listener to load user profile and update user links on page load
document.addEventListener('DOMContentLoaded', function() {
    loadUserProfile();
    updateUserLinks();
});

// Function to load user profile information from the server
function loadUserProfile() {
    fetch('php/getUserProfile.php')
        .then(response => response.json())
        .then(data => {
            const userProfileDiv = document.getElementById('userProfile');
            userProfileDiv.innerHTML = `
                <h2>Username: ${data.username}</h2>
                <p>Email: ${data.email}</p>
            `;
        })
        .catch(error => {
            console.error('Error loading user profile:', error);
            const userProfileDiv = document.getElementById('userProfile');
            userProfileDiv.innerHTML = '<p>Error loading profile information. Please try again later.</p>';
        });
}

// Function to update user links based on session status
function updateUserLinks() {
    const userLinksDiv = document.getElementById('userLinks');
    
    fetch('php/checkSession.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                userLinksDiv.innerHTML = `
                    <a href="php/logout.php">Logout</a>
                    <a href="profile.html">View Profile</a>
                `;
            } else {
                userLinksDiv.innerHTML = '<a href="php/login.php">Login</a>';
            }
        })
        .catch(error => {
            console.error('Error updating user links:', error);
            userLinksDiv.innerHTML = '<p>Failed to load user links. Please try again later.</p>';
        });
}
