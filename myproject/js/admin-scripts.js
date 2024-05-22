// 
// Author: Nikul Ram
// This script handles the functionality for the admin menu page, including loading, adding, and deleting menu items.
// It also updates the user links based on session status.
//

// Event listener to load menu items and update user links on page load
document.addEventListener('DOMContentLoaded', function() {
    loadMenuItems();
    updateUserLinks();
    document.getElementById('addMenuItemForm').addEventListener('submit', function(e) {
        e.preventDefault();
        addMenuItem();
    });
});

// Function to load menu items from the server
function loadMenuItems() {
    fetch('php/getMenuItems.php')
        .then(response => response.json())
        .then(data => {
            const menuItemsTable = document.getElementById('menuItems');
            menuItemsTable.innerHTML = ''; // Clear existing items
            const categories = {};

            for (const category in data) {
                if (!categories[category]) {
                    const categoryRow = document.createElement('tr');
                    categoryRow.className = 'category-row';
                    categoryRow.innerHTML = `<td colspan="6" class="category-title">${category}</td>`;
                    menuItemsTable.appendChild(categoryRow);
                    categories[category] = category;
                }

                data[category].forEach(item => {
                    const menuItemRow = document.createElement('tr');
                    menuItemRow.className = 'menu-item-row';
                    menuItemRow.innerHTML = `
                        <td>${category}</td>
                        <td>${item.name}</td>
                        <td>${item.description}</td>
                        <td>${item.price}</td>
                        <td>
                            <img src="../${item.image_url}" alt="${item.name}" width="50" onerror="this.onerror=null; this.src='../images/placeholder.jpg';">
                        </td>
                        <td><a href="admin-delete-menu-item.php?id=${item.id}">Delete</a></td>
                    `;
                    menuItemsTable.appendChild(menuItemRow);
                });
            }
        })
        .catch(error => {
            console.error('Error loading menu items:', error);
            const menuItemsTable = document.getElementById('menuItems');
            menuItemsTable.innerHTML = '<tr><td colspan="6">Failed to load menu items. Please try again later.</td></tr>';
        });
}

// Function to add a new menu item
function addMenuItem() {
    const name = document.getElementById('name').value;
    const description = document.getElementById('description').value;
    const price = document.getElementById('price').value;
    const category = document.getElementById('category').value;
    let image_url = document.getElementById('image_url').value;

    // Client-side validation for the image URL
    if (!isValidURL(image_url) || !isImageURL(image_url)) {
        image_url = 'images/placeholder.jpg';
    }

    fetch('php/admin-add-menu-item.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'add', name, description, price, category, image_url })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadMenuItems();
            document.getElementById('addMenuItemForm').reset(); // Clear the form
            alert('Menu item added successfully!'); // Notify the admin
        } else {
            alert('Failed to add menu item.');
        }
    })
    .catch(error => {
        console.error('Error adding menu item:', error);
        alert('Failed to add menu item due to a network error.');
    });
}

// Function to delete a menu item
function deleteMenuItem(id) {
    fetch('php/admin-delete-menu-item.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'delete', id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadMenuItems();
        } else {
            alert('Failed to delete menu item.');
        }
    })
    .catch(error => {
        console.error('Error deleting menu item:', error);
        alert('Failed to delete menu item due to a network error.');
    });
}

// Function to update user links based on session status
function updateUserLinks() {
    const userLinksDiv = document.getElementById('userLinks');
    const cartLink = document.getElementById('cartLink');
    const orderHistoryLink = document.getElementById('orderHistoryLink');

    fetch('php/checkSession.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                userLinksDiv.innerHTML = `
                    <a href="php/logout.php">Logout</a>
                    <a href="profile.html">View Profile</a>
                `;
                if (cartLink) cartLink.style.display = 'block';
                if (orderHistoryLink) orderHistoryLink.style.display = 'block';
            } else {
                userLinksDiv.innerHTML = '<a href="php/login.php">Login</a>';
                if (cartLink) cartLink.style.display = 'none';
                if (orderHistoryLink) orderHistoryLink.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error updating user links:', error);
            userLinksDiv.innerHTML = '<p>Failed to load user links. Please try again later.</p>';
        });
}

// Function to validate if a string is a valid URL
function isValidURL(string) {
    try {
        new URL(string);
        return true;
    } catch (_) {
        return false;  
    }
}

// Function to validate if a URL points to an image
function isImageURL(url) {
    return(url.match(/\.(jpeg|jpg|gif|png)$/) != null);
}
