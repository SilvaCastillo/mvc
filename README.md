![Header Image](public/img/symfonyHeader.png)

# Object-Oriented Web Technologies – MVC Project
[![Code Coverage](https://scrutinizer-ci.com/g/SilvaCastillo/mvc/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/SilvaCastillo/mvc/?branch=main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SilvaCastillo/mvc/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/SilvaCastillo/mvc/?branch=main)
[![Build Status](https://scrutinizer-ci.com/g/SilvaCastillo/mvc/badges/build.png?b=main)](https://scrutinizer-ci.com/g/SilvaCastillo/mvc/build-status/main)


Welcome to my project for the course
This application is built using **PHP** and the **Symfony** framework, following object-oriented principles, routing, and templating with Twig. The frontend is styled using **Tailwind CSS**  for a modern and responsive design.


## Built With 
- PHP 8.3.19
- Symfony 7.0.10
- Twig 3.20.0
- Tailwind CSS 3.4.1
- Composer 2.8.6
- NPM 10.8.2

### Clone the repository

```
git clone git@github.com:SilvaCastillo/mvc.git
```

### Install dependencies

```bash
composer install
npm install
```

### Build frontend assets (Tailwind CSS)

```bash
npm run dev
```

#### For production:

```bash
npm run build
```

### Start the development server

Use PHP's built-in server:

```bash
php -S localhost:8000 -t public
```
