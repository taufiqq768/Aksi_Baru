@echo off
echo Clearing Laravel cache...

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

echo Cache cleared successfully!
pause