# 💰 Finance API

RESTful API for personal financial management built with Laravel.
This project allows users to manage income and expenses, organize categories, and generate financial summaries.

---

## 🚀 Features

* CRUD for transactions (income and expenses)
* Category management
* Financial summary (income, expenses, balance)
* Filtering by date, type, and category
* Authentication with Laravel Sanctum
* Pagination and sorting

---

## 🛠️ Technologies

* PHP 8+
* Laravel
* MySQL
* Laravel Sanctum

---

## 📦 Installation

Clone the repository:

```bash
git clone https://github.com/your-username/finance-api.git
cd finance-api
```

Install dependencies:

```bash
composer install
```

Copy `.env` file:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Configure your database in `.env`, then run migrations:

```bash
php artisan migrate
```

Run the server:

```bash
php artisan serve
```

---

## 🔐 Authentication

This API uses Laravel Sanctum for authentication.

### Register

```http
POST /api/register
```

### Login

```http
POST /api/login
```

Use the returned token to authenticate requests:

```http
Authorization: Bearer {token}
```

---

## 📊 Endpoints

### Transactions

| Method | Endpoint               | Description           |
| ------ | ---------------------- | --------------------- |
| GET    | /api/transactions      | List all transactions |
| POST   | /api/transactions      | Create a transaction  |
| GET    | /api/transactions/{id} | Show a transaction    |
| PUT    | /api/transactions/{id} | Update a transaction  |
| DELETE | /api/transactions/{id} | Delete a transaction  |

---

### Categories

| Method | Endpoint             | Description     |
| ------ | -------------------- | --------------- |
| GET    | /api/categories      | List categories |
| POST   | /api/categories      | Create category |
| PUT    | /api/categories/{id} | Update category |
| DELETE | /api/categories/{id} | Delete category |

---

### Filters

```http
GET /api/transactions?type=expense
GET /api/transactions?category_id=1
GET /api/transactions?start_date=2026-04-01&end_date=2026-04-30
```

---

### Financial Summary

```http
GET /api/transactions/summary
```

Example response:

```json
{
  "income": 5000,
  "expense": 3200,
  "balance": 1800
}
```

---

## 📄 Example Request

```http
POST /api/transactions
```

```json
{
  "type": "expense",
  "amount": 150.00,
  "category_id": 2,
  "description": "Groceries",
  "date": "2026-04-19"
}
```

---

## 🧪 Testing

You can use tools like Postman or Insomnia to test the API.

---

## 📌 Future Improvements

* Unit and feature tests
* Docker setup
* Frontend integration (React or Vue)
* Recurring transactions
* Dashboard analytics

---

## 🤝 Contributing

Feel free to fork this repository and submit pull requests.

---

## 📄 License

This project is open-source and available under the MIT License.
