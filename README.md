# Project Management System
features:
- Tracks your productivity
- Reminds your task for today
- Reminds your past due tasks
- Assign permission to users
- Take note of your thoughts
- Built from Sprnva Framework

![image](https://user-images.githubusercontent.com/37282871/158299946-817ba36c-6aad-4910-9cd2-1247a0d3093d.png)

![image](https://user-images.githubusercontent.com/37282871/158300147-1a563418-10b1-46cc-96ef-6f577f2cf0eb.png)

## REQUIREMENTS
- Php version supported: PHP >= php5.6
- composer
- apache and mysql server

## INSTALLATION
- composer install
- duplicate `config.example` and rename it to `config.php`
- setup `config.php` credentials
- set `'base_url' => 'example-app'` *('example-app' is the directory name of your application)*
- Create a database identical to your config then go to  `/migration`module with this URL:
```
http://localhost/sprnva/migration
```
- click migrate button to migrate tables
- You can start building your application
