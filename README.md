# 🚀 Cyber-Deals (Futuristic Multi-Role E-Commerce Platform)

![PHP](https://img.shields.io/badge/PHP-8.x-blue)
![MySQL](https://img.shields.io/badge/MySQL-Database-orange)
![Bootstrap](https://img.shields.io/badge/Bootstrap-Responsive-purple)
![Status](https://img.shields.io/badge/Status-Completed-success)

**Cyber-Deals** is a fully functional, responsive e-commerce web application built using **PHP**, **MySQL**, **JavaScript**, **HTML**, **CSS**, and **Bootstrap**.

The platform supports three user roles **Admin**, **Processing Team**, and **Customer** with complete product, order, stock, and feedback management.

Designed with a futuristic UI theme, the system provides an engaging shopping experience combined with structured backend control.

---

## 👤 User Roles

| Role | Key Capabilities |
|------|-----------------|
| **Customer** | Register, browse & filter products, cart, orders, feedback |
| **Admin** | Full product/user/order management, sales & stock reports |
| **Processing Team** | Product & order management, feedback moderation |

> All roles login from the index page. Unauthorized access redirects to login.

---

## 🛍️ Product Categories

- **Mobile:** Phones, Tablets, Earbuds, Headphones, Chargers, USB Cables
- **Computer:** Desktop, Laptop, Processor, RAM, Storage, Motherboard, Casing

---

## 🎨 UI & Design

- Fully Responsive Design
- Futuristic UI Theme
- Sticky Navigation
- Custom Role Dropdown
- Animated Feedback Slider
- Modern Admin Dashboard
- Graph Video Background in Staff Dashboards
- Structured Layout Consistency

---

## 🔎 Index Page Highlights

- Product Search Bar
- Popular Category Quick Links (Mobile, Laptop, Desktop)
- Latest Products Loop
- Feedback Submission Section
- Role-Based Login Modal

> Only Customers can submit feedback after login.

---

## 🛠️ Tech Stack

| Technology  | Purpose                 |
|-------------|-------------------------|
| PHP         | Backend Logic           |
| MySQL       | Database                |
| JavaScript  | Frontend Interactions   |
| Bootstrap   | Responsive Layout       |
| HTML5       | Structure               |
| CSS3        | Styling                 |
| WampServer  | Local Server            |
| VS Code     | Development Environment |

---

## 🗂️ Project Structure

```
Cyber-Deals/
├── public/
│   ├── index.php, login.php, logout.php, register.php
│   ├── customer/        # shop, cart, order, feedback
│   ├── admin/           # dashboard, products, users, orders, reports
│   ├── processing/      # dashboard, products, orders, feedbacks
│   ├── assets/          # css, images, video
│   └── uploads/         # product images
├── app/
│   ├── config/database.php
│   ├── core/Session.php
│   ├── models/          # User, Product, Cart, Order, Feedback
│   └── bootstrap.php
└── database/cyber_deals.sql
```

---

## 🗄️ Database

**Database file:** `database/cyber_deals.sql`

### Setup Instructions

1. Open **phpMyAdmin**
2. Create database: `cyber_deals`
3. Import `cyber_deals.sql`
4. Update database credentials in:

```
app/config/database.php
```

---

## 🔐 Authentication & Authorization

- Session-based authentication
- Role-based access control
- Protected routes for Admin, Processing Team, and Customer
- Unauthorized users are redirected to login page

---

## 📊 Reports System

### Sales Reports
- Generated from Admin panel
- Downloadable export

### Stock Reports
- Filter by Main Category or Subcategory
- Downloadable export

---

## 📦 Cart & Order Flow

1. Customer logs in
2. Adds product to cart
3. Reviews cart
4. Proceeds to checkout
5. Order stored in database
6. Admin & Processing Team can manage orders

---

## 📁 Upload System

- Product images stored in: `public/uploads/`
- Image validation implemented

---

## ⚙️ Installation Guide

### 1️⃣ Clone Repository
```bash
git clone https://github.com/Kalana-S/Cyber-Deals.git
```

### 2️⃣ Move Project
Place the project folder inside:
```
wamp64/www/
```

### 3️⃣ Import Database
Import 
```
database/cyber_deals.sql via phpMyAdmin.
```

### 4️⃣ Configure Database
Edit
``` 
app/config/database.php with your local credentials.
```

### 5️⃣ Run
Open in browser:
```
http://localhost/Cyber/public/
```

---

## 🧪 Testing Scenarios

> ✔ Customer Registration
>
> ✔ Role-Based Login
>
> ✔ Add to Cart
>
> ✔ Checkout
>
> ✔ Report Generation
> 
> ✔ Product Filtering
>
> ✔ Feedback Submission
>
> ✔ Stock Management

---

## 🎥 Cyber-Deals – Demo Videos

| 🎯 Module                                                  | ▶️ Demo Video                                                                                   |
| :--------------------------------------------------------- | :---------------------------------------------------------------------------------------------- |
| **1️⃣ Admin Actions**                                      | [Watch Demo](https://github.com/your-username/your-repo/assets/admin-actions-video-link)        |
| **2️⃣ Admin – Product Add / Update / Manage**              | [Watch Demo](https://github.com/your-username/your-repo/assets/admin-product-manage-video-link) |
| **3️⃣ Admin – User Add / Update / Manage**                 | [Watch Demo](https://github.com/your-username/your-repo/assets/admin-user-manage-video-link)    |
| **4️⃣ Admin – Reports (Sales & Stock)**                    | [Watch Demo](https://github.com/your-username/your-repo/assets/admin-reports-video-link)        |
| **5️⃣ Product Form Validations**                           | [Watch Demo](https://github.com/your-username/your-repo/assets/product-validation-video-link)   |
| **6️⃣ User Form Validations**                              | [Watch Demo](https://github.com/your-username/your-repo/assets/user-validation-video-link)      |
| **7️⃣ Customer Actions (Register → Login → Cart → Order)** | [Watch Demo](https://github.com/your-username/your-repo/assets/customer-actions-video-link)     |
| **8️⃣ Fully Responsive Design (All Devices)**              | [Watch Demo](https://github.com/your-username/your-repo/assets/responsive-design-video-link)    |

---

## 📜 License

This project is licensed under the MIT License – see the `LICENSE` file for details.

---

## ⭐ Acknowledgements

- [Bootstrap Documentation](https://getbootstrap.com/docs/)
- [PHP Official Documentation](https://www.php.net/docs.php)
- [MySQL Documentation](https://dev.mysql.com/doc/)

---

## 🏁 Project Status

> ✅ Completed &nbsp;|&nbsp; 🛠 Maintained Locally &nbsp;|&nbsp; 📦 Ready for Deployment
