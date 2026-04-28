# test-wts: API + Админпанель (Laravel + Orchid)  
  
Бэкенд для мобильного приложения «Блог».   
REST API (регистрация, авторизация, посты).  
  
## Требования  
  
- PHP ≥ 8.2  
- MySQL 8.0+ (можно через Docker)  
- Composer  
  
## Установка  
  
```bash
composer install  
cp .env.example .env
```  
настройте подключение к БД в .env  
```bash
php artisan key:generate  
php artisan migrate  
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"  
php artisan migrate  
composer require orchid/platform  
php artisan orchid:install
```  
  
## Создание администратора  
  
php artisan tinker  
  
```php
$admin = User::where('email', 'admin@example.com')->first();  
if ($admin) {  
    $admin->permissions = [  
        'platform.index' => true,  
        'platform.posts' => true,  
        'platform.users' => true,  
        'platform.systems.users' => true,  
    ];  
    $admin->is_admin = true;  
    $admin->save();  
} else {  
    $admin = User::create([  
        'name' => 'Admin',  
        'email' => 'admin@example.com',  
        'password' => bcrypt('password'),  
        'is_admin' => true,  
        'permissions' => [  
            'platform.index' => true,  
            'platform.posts' => true,  
            'platform.systems.users' => true,  
        ],  
    ]);  
}
```  
    
## Запуск  
  
php artisan serve  
  
## API эндпоинты  
  
Метод	URL	Заголовки	Тело (JSON)	Описание  
POST	/api/register	Content-Type: application/json	name, email, password, password_confirmation	Регистрация → access_token  
POST	/api/login	Content-Type: application/json	email, password	Логин → access_token  
POST	/api/posts	Authorization: Bearer <token>
Content-Type: application/json	title, text	Создать пост  
GET	/api/posts	–	Query: limit, offset, sort_by (title/created_at), sort_order (asc/desc), date_from, date_to	Лента с пагинацией, сортировкой, фильтром по дате  
GET	/api/my-posts	Authorization: Bearer <token>	Те же query	Посты текущего пользователя  
  
## Примеры  
  
Создать пост  
  
```bash
curl -X POST http://127.0.0.1:8000/api/posts \  
  -H "Authorization: Bearer 1|xxxx" \  
  -H "Content-Type: application/json" \  
  -d '{"title":"Первый пост","text":"Привет!"}'
```  
  
Получить посты с сортировкой  
  
```bash
curl -X GET "http://127.0.0.1:8000/api/posts?limit=10&sort_by=title&sort_order=asc&date_from=2025-01-01"
```  
  
## Админпанель Orchid  
  
URL: http://127.0.0.1:8000/admin → перенаправляет на страницу входа  
  
Логин: admin@example.com  
Пароль: password  
  
Управление пользователями (CRUD) — стандартные экраны Orchid  
Управление публикациями (CRUD) — экраны PostListScreen, PostEditScreen  
  
Доступ только для пользователей с is_admin = true  