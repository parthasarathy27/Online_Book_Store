# Online Book Store - Laravel Interview Project

This is a Laravel-based Online Book Store application. The system allows users to view, search, and filter a catalog of books, view individual book details, and search/import book metadata using the Google Books API. It also includes an administrative dashboard to manage the inventory catalog.

---

## Features

### 1. Public Storefront Pages
* **Home Page:** Displays a search bar, categories list with book counts, and featured books.
* **Book Listing Page:** Displays the catalog grid with keyword search (title or author), category filters, and sorting controls (price low-to-high, price high-to-low, and alphabetical title).
* **Book Details Page:** Shows detailed book information including cover art, category, price, availability status, description, and related books in the same category.
* **Google Books API Explorer:** A page allowing users to search the live Google Books database in real-time, view summaries, and import metadata directly.

### 2. Admin Portal (Protected)
* **Authentication:** Secure login and logout actions using standard Laravel guard authentication.
* **Admin Dashboard:** Tabular overview of the book catalog showing titles, authors, categories, prices, and stock availability.
* **CRUD Management:**
  * **Add Book:** Manually create a book or auto-fill values with one click from a Google Books API result. Supports local image upload or external cover URLs.
  * **Edit Book:** Update book fields, pricing, cover previews, and availability status.
  * **Delete Book:** Safely deletes books and cleans up associated cover images.

---

## Tech Stack
* **PHP:** `8.0.x`
* **Framework:** `Laravel 9.x`
* **Database:** MySQL
* **Styling:** Tailwind CSS (configured and compiled via Vite)
* **Frontend Logic:** Alpine.js

---

## Installation & Local Setup

Follow these steps to run the application locally:

### 1. Clone & Place inside Webroot
Copy the project files to your local server path (e.g. `d:\xampp\htdocs\Online_Book_Store`).

### 2. Configure Environment
1. Copy the `.env.example` file to create a `.env` file:
   ```bash
   cp .env.example .env
   ```
2. Configure your database connection inside `.env`. For a standard local XAMPP environment, use:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=online_book_store
   DB_USERNAME=root
   DB_PASSWORD=
   ```

### 3. Create the Database
Create a database named `online_book_store` in your local MySQL instance.

### 4. Run Migrations & Seeders
Run migrations to build the tables and execute seeders to populate initial categories, default books, and the admin account:
```bash
php artisan migrate:fresh --seed
```

**Admin Credentials:**
* **Email:** `admin@bookstore.com`
* **Password:** `admin123`

---

## Running the Application

### 1. Start Laravel Server
```bash
php artisan serve
```
The store is accessible at: **[http://127.0.0.1:8000](http://127.0.0.1:8000)**.

### 2. Frontend Assets Compilation
This project uses Vite to compile Tailwind CSS.
* **For Development (Hot Reloading):**
  ```bash
  npm run dev
  ```
* **For Production Build:**
  ```bash
  npm run build
  ```
*(Note: Production assets are already compiled and located in `public/build` for immediate run).*

---

## Running Automated Tests
A comprehensive Feature Test suite is included in `tests/Feature/BookStoreTest.php` to verify routing, searching, filtering, API integrations, and the admin CRUD flow.

To run the test suite (uses an in-memory SQLite database configuration to avoid altering your local database):
```bash
vendor/bin/phpunit
```
All 11 tests should pass successfully.
