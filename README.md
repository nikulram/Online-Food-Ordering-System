# Online Food Ordering System by Nikul Ram

## Overview
The Online Food Ordering System is designed to smooth the process of ordering food online for both customers and food vendors. It provides a user-friendly platform where customers can browse menus, add items to their cart, and place orders, while vendors can manage their menu and track orders efficiently.

## Features
### User Features
1. **Browse Menu**
   - Users can browse various menu items categorized neatly.
   - Menu items are dynamically loaded from the database.

2. **Add to Cart**
   - Users can add items to their cart for review before placing an order.
   - JavaScript handles cart operations and stores items in local storage.

3. **Checkout**
   - Users can review their cart and proceed to checkout.
   - Securely collects payment information and validates input.

4. **Order History**
   - Users can view their past orders.
   - Order history is dynamically loaded from the database.

### Admin Features
1. **Manage Menu Items**
   - Admins can add, edit, and delete menu items.
   - Provides forms for CRUD operations on menu items.

2. **View Orders**
   - Admins can view all orders placed by users.
   - Orders are displayed with details like order ID, user, and status.

3. **Manage Users**
   - Admins can see user information and permissions.(Can Manage users from databse)
   - Allows viewing user information.

### Security Features
1. **Password Hashing**
   - Ensures user passwords are stored securely using PHP’s password_hash() function.

2. **Prepared Statements**
   - Prevents SQL injection attacks by using prepared statements in database queries.

3. **Session Management**
   - Securely manages user sessions using PHP sessions.

## Technologies Used
- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Server Environment**: XAMPP
- **Version Control**: Git and GitHub

## Installation
To get a local copy up and running, follow these simple steps:

### Prerequisites
- XAMPP installed on your machine
- Composer installed for PHP dependencies
- Node.js and npm installed for any frontend dependencies

### Installation Steps
1. **Clone the repository**
    
    git clone https://github.com/nikulram/Online-Food-Ordering-System.git
    cd Online-Food-Ordering-System
    
2. **Install PHP dependencies**
    
    composer install

3. **Install Node.js dependencies**
    
    npm install

## Important Note : 
- Ensure the `myproject` folder is placed inside the `htdocs` directory of your XAMPP installation.(xampp > htdocs > myproject).

4. **Start the server**
    - Open XAMPP (Run as administrator) and start Apache and MySQL services.

5. **Setup Database**
    - Open your web browser and go to `http://localhost/phpmyadmin`.
    - Click on the "Databases" tab and create a new database named `myprojectdb` or any name you want.
    - Click on the newly created database `myprojectdb`.
    - Go to the "Import" tab.
    - Click on "Choose File" and select the `schema.sql` file from the cloned repository.
    - Click on "Go" to import the database schema.

## Usage
- **Visit** `http://localhost/myproject` to access the application.
- **Admin Panel Login details**: Accessible by logging in as an admin user.
- **Database Login Information**: Default username - admin, password - adminpass (change the hashed password in `schema.sql` if necessary).

## Documentation
For a detailed explanation of the project, please refer to the [documentation](docs/Online_Food_Ordering_System_Documentation_v1.0.pdf) located in the `docs` folder.

## Contributing
Interested in contributing? Great! Please follow the next steps:

Fork the repository. Create your feature branch (git checkout -b feature/AmazingFeature). Commit your changes (git commit -m 'Add some AmazingFeature'). Push to the branch (git push origin feature/AmazingFeature). Open a Pull Request.

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgements
- The contributors and maintainers of all used open-source software. 
- Special thanks to Hamna Khalid for giving valuable tips and guidance on enhancing the documentation (Online_Food_Ordering_System_Documentation_v1.0) part of this project.

## References
- Hickson, Anthony. HTML, The living standard, 2014.
- CSS, Cascading Style Sheets. "Cascading Style Sheets."(hämtad 2022-04-27).
- Flanagan, David. JavaScript: The definitive guide: Activate your web pages. "O'Reilly Media, Inc.", 2011.
- McLaughlin, Brett. PHP & MySQL: The Missing Manual. "O'Reilly Media, Inc.", 2012.
- Stobart, Simon, and Mike Vassileiou. PHP and MySQL Manual: simple, yet powerful Web programming. Springer Science & Business Media, 2004.
- Steinhoff, Sascha. "Evaluation of MySQL 8.0 Spatial Data Features."
- Kumari, Punam, and Rainu Nandal. "A Research Paper On Website Development Optimization Using Xampp/PHP." International Journal of Advanced Research in Computer Science 8, no. 5 (2017).
- Bhanderi, Dixita Dinesh. "Enhanced two factor authentication with MD5." PhD diss., California State University, Sacramento, 2021.
- Peterson, Clarissa. Learning responsive web design: a beginner's guide. "O'Reilly Media, Inc.", 2014.
- Zandstra, Matt, and Matt Zandstra. "Testing with PHPUnit." PHP Objects, Patterns, and Practice (2016): 435-464.
- Chavan, Varsha, Priya Jadhav, Snehal Korade, and Priyanka Teli. "Implementing customizable online food ordering system using web based application." International Journal of Innovative Science, Engineering & Technology 2, no. 4 (2015): 722-727.
