Делаем форм репозитория, клонируем его к себе на ПК
заходим в папку с проектом, нужно выполнить для установки зависимостей:
composer install
npm install

Сгенерировать ключ:
php artisan key:generate

Для storage
php artisan storage:link

Нужна база данных PgSQL
Например с такими данными:
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=book_collection
DB_USERNAME=book_collection
DB_PASSWORD=3434

Прописать в созданом .env файле!

После создания бд, и записи .env файла
Запускаем миграции
php artisan migrate

Далее запускаем сидеры, на всякий случай чистим бд перед этим)
php artisan migrate:fresh --seed

Запуск сервера:
php artisan serve

Далее в браузере: http://localhost:8000

После запущенных сидеров, будут созданы 8 пользователей с разными ролями и данными
для теста:
Админ:
login: admin@example.com
password: 3434

Обычный пользователь:
login: user@example.com
password: 3434

Весь функционал согласно ТЗ реализован, как для админа, так и для обычного юзера
Страницы все книги, о нас доступны и без авторизации:
/
/about

Возможные ошибки:

1. Ошибка с правами доступа
   bash
   sudo chmod -R 775 storage bootstrap/cache

2. Ошибка с Composer
   bash
   composer install --no-scripts

3. Проблемы с Node.js/npm
   bash
   rm -rf node_modules package-lock.json
   npm cache clean --force
   npm install
