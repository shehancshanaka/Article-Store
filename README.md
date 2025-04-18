# Article Store

A modern web application for storing and managing articles with images, metadata, and advanced search capabilities.

## Features

- Image upload and storage with automatic thumbnail generation
- Comprehensive metadata management:
  - Title, date, and page number
  - Author and pen name management
  - Subject categorization
  - Related media linking
  - Tag system
- Advanced search functionality
- Modern, responsive UI with animations
- Dynamic form fields
- Real-time validation

## Requirements

- PHP 8.1 or higher
- MySQL/MariaDB
- Composer
- Web server (Apache/Nginx)
- PHP extensions:
  - PDO
  - GD
  - JSON
  - Fileinfo

## Installation

1. Clone the repository:
```bash
git clone https://github.com/yourusername/article-store.git
cd article-store
```

2. Install dependencies:
```bash
composer install
```

3. Create the database:
```bash
mysql -u root -p < database.sql
```

4. Configure the database connection:
Edit `app/config/database.php` with your database credentials.

5. Set up the web server:
- Point your web server's document root to the `public` directory
- Ensure the `public/uploads` directory is writable
- Configure URL rewriting if needed

6. Initialize the application:
```bash
php -S localhost:8000 -t public
```

## Directory Structure

```
article_store/
├── app/
│   ├── config/         # Configuration files
│   ├── controllers/    # Application controllers
│   ├── models/         # Database models
│   ├── views/          # View templates
│   └── helpers/        # Helper functions
├── public/
│   ├── assets/         # CSS, JS, and images
│   └── uploads/        # Uploaded files
├── vendor/             # Composer dependencies
└── composer.json       # PHP dependencies
```

## Usage

1. Access the application through your web browser
2. Use the navigation menu to:
   - Add new articles
   - Search existing articles
   - View article details

## Security Considerations

- All user input is properly sanitized
- File uploads are validated for type and size
- Database queries use prepared statements
- Session management is implemented
- XSS protection is in place

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- Bootstrap 5 for the UI framework
- jQuery for JavaScript functionality
- Select2 for enhanced dropdowns
- Dropzone.js for file uploads
- Intervention Image for image processing 