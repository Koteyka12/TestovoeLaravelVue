# Yandex Reviews - Интеграция с Яндекс Картами

Веб-приложение для сбора и отображения отзывов с Яндекс Карт.

## Функционал

- Авторизация пользователей (регистрация/вход)
- Подключение организации из Яндекс Карт по ссылке
- Автоматический сбор отзывов с карточки организации
- Отображение рейтинга и общего количества отзывов
- Синхронизация отзывов по запросу

## Технологии

- **Backend:** Laravel 11
- **Frontend:** Vue 3 + Inertia.js
- **Стили:** Tailwind CSS
- **База данных:** SQLite (можно заменить на MySQL/PostgreSQL)

## Требования

- PHP >= 8.2
- Composer
- Node.js >= 18
- NPM

## Установка

### 1. Клонирование репозитория

```bash
git clone https://github.com/Koteyka12/TestovoeLaravelVue.git
cd TestovoeLaravelVue
```

### 2. Установка зависимостей

```bash
composer install
npm install
```

### 3. Настройка окружения

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Настройка базы данных

Для SQLite (по умолчанию):
```bash
touch database/database.sqlite
```

Или настройте MySQL/PostgreSQL в файле `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=yandex_reviews
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Выполнение миграций

```bash
php artisan migrate
```

### 6. Сборка фронтенда

Для разработки:
```bash
npm run dev
```

Для продакшена:
```bash
npm run build
```

### 7. Запуск сервера

```bash
php artisan serve
```

Приложение будет доступно по адресу: http://localhost:8000

## Использование

### 1. Регистрация

Перейдите на страницу регистрации и создайте аккаунт.

### 2. Подключение организации

1. Перейдите в раздел "Настройка"
2. Вставьте ссылку на организацию в Яндекс Картах
   - Пример: `https://yandex.ru/maps/org/samoye_populyarnoye_kafe/1010501395/reviews/`
3. Нажмите "Сохранить"

### 3. Просмотр отзывов

После подключения организации перейдите в раздел "Отзывы" для просмотра:
- Списка всех отзывов
- Рейтинга организации
- Общего количества отзывов

### 4. Синхронизация

Нажмите кнопку обновления в правом верхнем углу страницы отзывов для получения новых данных.

## Структура проекта

```
app/
├── Http/Controllers/
│   ├── ReviewController.php         # Контроллер отзывов
│   └── YandexOrganizationController.php  # Контроллер настроек
├── Models/
│   ├── Review.php                   # Модель отзыва
│   ├── User.php                     # Модель пользователя
│   └── YandexOrganization.php       # Модель организации
└── Services/
    └── YandexMapsService.php        # Сервис парсинга Яндекс Карт

resources/js/Pages/Yandex/
├── Reviews.vue                      # Страница отзывов
└── Settings.vue                     # Страница настроек
```

## API Endpoints

| Метод | URL | Описание |
|-------|-----|----------|
| GET | /yandex/reviews | Страница отзывов |
| POST | /yandex/reviews/sync | Синхронизация отзывов |
| GET | /yandex/settings | Страница настроек |
| POST | /yandex/settings | Сохранение ссылки на организацию |
| DELETE | /yandex/organization/{id} | Удаление организации |

## Деплой на VDS

### 1. Настройка сервера

```bash
# Установка необходимых пакетов
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-mbstring php8.2-xml php8.2-curl php8.2-sqlite3 nginx composer nodejs npm

# Клонирование проекта
cd /var/www
git clone https://github.com/Koteyka12/TestovoeLaravelVue.git
cd TestovoeLaravelVue

# Установка зависимостей
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Настройка прав
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### 2. Настройка Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/TestovoeLaravelVue/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 3. Настройка окружения для продакшена

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
```

## Лицензия

MIT License

## Автор

Koteyka12
