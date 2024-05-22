// 
// Author: Nikul Ram
// This script handles the functionality for the entire user interface of the Online Food Ordering System.
// It includes functions to load menu items, manage the cart, handle user sessions, and place orders.
//

// Event listener to load menu items, cart items, order history, and update user links on page load
document.addEventListener('DOMContentLoaded', function() {
    loadMenuItems();
    loadCartItems();
    loadOrderHistory();
    updateUserLinks();
});

// Initialize the cart and submission state
let cart = [];
let isSubmitting = false;

// Function to load menu items from the server
function loadMenuItems() {
    fetch('php/getMenuItems.php')
        .then(response => response.json())
        .then(data => {
            const menuDiv = document.getElementById('menu');
            menuDiv.innerHTML = ''; // Clear existing items
            const categories = {};

            for (const category in data) {
                if (!categories[category]) {
                    categories[category] = document.createElement('div');
                    categories[category].classList.add('menu-category');
                    const categoryTitle = document.createElement('h2');
                    categoryTitle.textContent = category;
                    categories[category].appendChild(categoryTitle);
                }

                data[category].forEach(item => {
                    const itemDiv = document.createElement('div');
                    itemDiv.classList.add('menu-item');
                    itemDiv.innerHTML = `
                        <h3>${item.name}</h3>
                        <img src="${item.image_url}" alt="${item.name}" onerror="this.onerror=null; this.src='images/placeholder.jpg';">
                        <p>${item.description}</p>
                        <p>Price: $${item.price}</p>
                        <button onclick="addToCart(${item.id}, '${item.name}', ${item.price})">Add to Cart</button>
                    `;
                    categories[category].appendChild(itemDiv);
                });
            }

            for (const category in categories) {
                menuDiv.appendChild(categories[category]);
            }
        })
        .catch(error => {
            console.error('Error loading menu items:', error);
        });
}

// Function to add an item to the cart
function addToCart(id, name, price) {
    const item = cart.find(i => i.id === id);
    if (item) {
        item.quantity++;
    } else {
        cart.push({ id, name, price, quantity: 1 });
    }
    saveCart();
    loadCartItems();
    updateCartCount();
}

// Function to load cart items from the local storage
function loadCartItems() {
    const cartItemsDiv = document.getElementById('cartItems');
    const totalPriceSpan = document.getElementById('totalPrice');
    if (cartItemsDiv && totalPriceSpan) {
        cartItemsDiv.innerHTML = '';
        let totalPrice = 0;

        cart.forEach(item => {
            totalPrice += item.price * item.quantity;
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item';
            cartItem.innerHTML = `
                <h3>${item.name}</h3>
                <p>Price: $${item.price}</p>
                <p>Quantity: <button onclick="updateCartQuantity(${item.id}, -1)">-</button> ${item.quantity} <button onclick="updateCartQuantity(${item.id}, 1)">+</button></p>
                <button onclick="removeFromCart(${item.id})">Remove</button>
            `;
            cartItemsDiv.appendChild(cartItem);
        });

        totalPriceSpan.textContent = totalPrice.toFixed(2);
    }
}

// Function to remove an item from the cart
function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    saveCart();
    loadCartItems();
    updateCartCount();
}

// Function to update the quantity of an item in the cart
function updateCartQuantity(id, change) {
    const item = cart.find(i => i.id === id);
    if (item) {
        item.quantity += change;
        if (item.quantity <= 0) {
            removeFromCart(id);
        } else {
            saveCart();
            loadCartItems();
        }
        updateCartCount();
    }
}

// Function to update the cart item count displayed on the page
function updateCartCount() {
    const cartCountSpan = document.getElementById('cartCount');
    if (cartCountSpan) {
        cartCountSpan.textContent = cart.reduce((acc, item) => acc + item.quantity, 0);
    }
}

// Function to save the cart to local storage
function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Function to load the cart from local storage
function loadCart() {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
        cart = JSON.parse(savedCart);
    }
    updateCartCount();
}

// Function to handle placing an order
function placeOrder() {
    if (cart.length === 0) {
        alert('Cart is empty.');
        return;
    }

    fetch('php/checkSession.php')
        .then(response => response.json())
        .then(data => {
            if (!data.loggedIn) {
                alert('You need to log in to place an order.');
                window.location.href = 'php/login.php';
            } else {
                window.location.href = 'checkout.html';
            }
        });
}

// Function to load order history from the server
function loadOrderHistory() {
    const orderHistoryDiv = document.getElementById('orderHistory');
    if (orderHistoryDiv) {
        fetch('php/orderHistory.php')
            .then(response => response.json())
            .then(data => {
                if (Array.isArray(data)) {
                    orderHistoryDiv.innerHTML = '';
                    data.forEach(order => {
                        const orderDiv = document.createElement('div');
                        orderDiv.className = 'order';
                        orderDiv.innerHTML = `
                            <h3>Order ID: ${order.id}</h3>
                            <p>Total Price: $${order.total_price}</p>
                            <p>Status: ${order.status}</p>
                            <p>Order Date: ${order.order_date}</p>
                        `;
                        orderHistoryDiv.appendChild(orderDiv);
                    });
                } else {
                    console.error('Order history data is not an array:', data);
                }
            })
            .catch(error => {
                console.error('Error fetching order history:', error);
            });
    }
}

// Function to update user links based on session status
function updateUserLinks() {
    const userLinksDiv = document.getElementById('userLinks');
    const cartLink = document.getElementById('cartLink');
    const guestReminder = document.getElementById('guestReminder');
    const loginReminder = document.getElementById('loginReminder');

    // Clear the existing links first to prevent duplication
    userLinksDiv.innerHTML = '';

    fetch('php/checkSession.php')
        .then(response => response.json())
        .then(data => {
            if (data.loggedIn) {
                let userLinksHtml = '<a href="php/logout.php">Logout</a><a href="profile.html">View Profile</a>';
                if (!document.querySelector('a[href="order.html"]')) {
                    userLinksHtml += '<a href="order.html">Order History</a>';
                }
                if (data.is_admin) {
                    userLinksHtml += '<a href="php/admin.php">Admin Panel</a>';
                }
                userLinksDiv.innerHTML = userLinksHtml;
                if (cartLink) cartLink.style.display = 'block';
                if (guestReminder) guestReminder.style.display = 'none';
                if (loginReminder) loginReminder.style.display = 'none';
            } else {
                userLinksDiv.innerHTML = '<a href="php/login.php">Login</a><a href="php/signup.php">Signup</a>';
                if (cartLink) cartLink.style.display = 'none';
                if (guestReminder) guestReminder.style.display = 'block';
                if (loginReminder) loginReminder.style.display = 'block';
            }
        });
}

// Function to handle the checkout form submission
document.getElementById('checkoutForm')?.addEventListener('submit', function(e) {
    e.preventDefault();

    if (isSubmitting) return; // Prevent multiple submissions
    isSubmitting = true;

    const submitButton = document.querySelector('button[type="submit"]');
    submitButton.disabled = true;

    const cardNumber = document.getElementById('cardNumber').value;
    const expiryDate = document.getElementById('expiryDate').value;
    const cvv = document.getElementById('cvv').value;
    const redeemCode = document.getElementById('redeemCode').value;
    const giftCard = document.getElementById('giftCard').value;

    // Basic validation
    if (!/^\d{16}$/.test(cardNumber)) {
        alert('Invalid card number. Must be 16 digits.');
        submitButton.disabled = false;
        isSubmitting = false;
        return;
    }
    if (!/^\d{3}$/.test(cvv)) {
        alert('Invalid CVV. Must be 3 digits.');
        submitButton.disabled = false;
        isSubmitting = false;
        return;
    }
    const currentDate = new Date();
    const [year, month] = expiryDate.split('-');
    const expiry = new Date(`${year}-${month}-01`);
    if (expiry <= currentDate) {
        alert('Card has expired.');
        submitButton.disabled = false;
        isSubmitting = false;
        return;
    }

    let discount = 0;
    if (redeemCode === 'USE100') {
        discount = 100;
    } else if (redeemCode === 'DISCOUNT50') {
        discount = 50;
    }

    const totalPrice = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
    const finalPrice = totalPrice - (totalPrice * (discount / 100));

    const orderData = {
        cart,
        cardNumber,
        expiryDate,
        cvv,
        finalPrice,
        redeemCode,
        giftCard
    };

    fetch('php/order.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(orderData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            alert(`Payment processed successfully! Final Price: $${finalPrice.toFixed(2)}`);
            clearFormFields();
            // Redirect to the order-confirmation page
            window.location.href = 'order-confirmation.html';

            // Reset cart
            cart = [];
            saveCart();
            loadCartItems();
            updateCartCount();
        } else {
            alert('Failed to place order: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error placing order:', error);
        alert('Failed to place order due to a network error.');
    })
    .finally(() => {
        submitButton.disabled = false;
        isSubmitting = false;
    });
});

// Function to clear form fields after successful submission
function clearFormFields() {
    document.getElementById('cardNumber').value = '';
    document.getElementById('expiryDate').value = '';
    document.getElementById('cvv').value = '';
    document.getElementById('redeemCode').value = '';
    document.getElementById('giftCard').value = '';
}

// Load the cart from local storage on page load
loadCart();
