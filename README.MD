# Transaction Management System

A comprehensive financial transaction management system built with Laravel and React.

## 🌟 Features

- RESTful API for transaction management
- Interactive Dashboard
- Bulk transaction import via CSV
- Advanced filtering and search
- Pagination system
- Automatic unique identifier generation
- Unit and integration tests

## 🛠 Tech Stack

### Backend
- PHP 8.2
- Laravel 11
- MySQL
- SQLite (for testing)

### Frontend
- React 18
- TailwindCSS
- React Query
- Axios

### Infrastructure
- Docker
- Nginx
- PHP-FPM

## 🚀 Installation

### Prerequisites
- Docker Desktop
- Docker Compose
- Git

### Installation Steps

1. Clone the repository:
```bash
git clone [repository-URL]
cd transaction-management-system
```

2. Copy configuration files:
```bash
cp backend/.env.example backend/.env
cp frontend/.env.example frontend/.env
```

3. Start Docker containers:
```bash
docker-compose up -d
```

4. Install backend dependencies:
```bash
docker-compose exec backend composer install
docker-compose exec backend php artisan key:generate
docker-compose exec backend php artisan migrate
```

5. Install frontend dependencies:
```bash
docker-compose exec frontend npm install
```

## 📚 Project Structure

```
.
├── backend/                 # Laravel Application
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   └── Requests/
│   │   └── Models/
│   ├── database/
│   │   └── migrations/
│   └── tests/
├── frontend/               # React Application
│   ├── src/
│   │   ├── components/
│   │   ├── services/
│   │   └── hooks/
└── docker/                 # Docker Configuration
    ├── nginx/
    └── php/
```

## 🔍 API Endpoints

### Transactions

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/v1/transactions` | List transactions |
| POST | `/api/v1/transactions` | Create transaction |
| DELETE | `/api/v1/transactions/{id}` | Delete transaction |

### Filter Parameters

- `date_from`: Start date
- `date_to`: End date
- `type`: Transaction type (credit/debit)
- `sort_by`: Sort field
- `sort_direction`: Sort direction (asc/desc)

## 🧪 Testing

### Backend Tests
```bash
# Run all tests
docker-compose exec backend php artisan test

# Run specific tests
docker-compose exec backend php artisan test --filter=TransactionControllerTest
```

### Frontend Tests
```bash
docker-compose exec frontend npm test
```

## 📊 Data Import

### Generate Sample CSV File
```bash
docker-compose exec backend php artisan transactions:generate-csv
```

### Import Transactions
```bash
docker-compose exec backend php artisan transactions:import storage/app/transactions.csv
```

## 🔐 Security

- Backend and frontend data validation
- CSRF protection
- Input sanitization
- Nginx security headers
- CORS handling

## ⚙️ Advanced Configuration

### Backend Environment Variables (.env)
```env
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=transactions_db
DB_USERNAME=user
DB_PASSWORD=password
```

### Frontend Environment Variables (.env)
```env
REACT_APP_API_URL=http://localhost:8000/api
```

## 🎯 Development Commands

### Backend
```bash
# Run migrations
docker-compose exec backend php artisan migrate

# Clear cache
docker-compose exec backend php artisan cache:clear

# Generate API documentation
docker-compose exec backend php artisan l5-swagger:generate
```

### Frontend
```bash
# Start development server
docker-compose exec frontend npm start

# Build for production
docker-compose exec frontend npm run build

# Run tests
docker-compose exec frontend npm test
```

## 📝 API Documentation

### Transaction Object

```json
{
    "transaction_id": "TXN-1234567890",
    "account_number_from": "BANK1000001",
    "account_number_type_from": "SAVINGS",
    "account_number_to": "BANK2000001",
    "account_number_type_to": "CHECKING",
    "amount": 1000.00,
    "type": "credit",
    "description": "Monthly transfer",
    "reference": "REF-12345",
    "creation_date": "2024-10-27T10:00:00Z"
}
```

### Create Transaction Request
```json
{
    "account_number_from": "BANK1000001",
    "account_number_type_from": "SAVINGS",
    "account_number_to": "BANK2000001",
    "account_number_type_to": "CHECKING",
    "amount": 1000.00,
    "type": "credit",
    "description": "Monthly transfer"
}
```

## 🔧 Performance Optimizations

1. Database Optimizations
   - Proper indexing on frequently queried fields
   - Batch processing for imports
   - Query optimization

2. Frontend Optimizations
   - React Query for efficient data caching
   - Lazy loading of components
   - Debounced search inputs

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📈 Performance Notes

- CSV import optimized for large data volumes
- Indexed queries for better performance
- Implemented pagination for large datasets
- Full details in PERFORMANCE.md

## 🐞 Known Issues

- Large CSV imports may require increased memory limit
- Frontend pagination limited to 1000 records per page

## 📄 License

This project is licensed under the MIT License - see the LICENSE.md file for details

## 👥 Authors

- Jose Aponte (https://github.com/japontel/tsm)

## 🙏 Acknowledgments

- Laravel Team
- React Team
- Open Source Community
