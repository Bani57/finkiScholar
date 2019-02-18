title FinkiScholar installation
echo on
call composer install
pause
call npm install
pause
cd semantic/
call gulp build
pause
cd ..
mkdir "./public/images/profile_pictures"
mkdir "./public/papers_files"
call php artisan key:generate
pause
call php artisan migrate:refresh --seed
pause
call npm run prod
pause
cmd /k
