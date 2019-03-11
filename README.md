# tai-backend

## How to run:

```bash
git clone https://github.com/DeH4er/tai-backend.git
cd tai-backend
composer install
php artisan migrate # if it fails -> check connection to db: postgres should listen port 5432 with user postgres, password postgres and db tai
php artisan serve
```
As the result backend must listen port 8000, if it isn't -> change tai-frontend/src/environments/environment.ts base_url
