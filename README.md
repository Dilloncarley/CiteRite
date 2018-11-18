# CiteRite

## Local Installation
1. run `git clone https://github.com/Dilloncarley/CiteRite`
2. cd into `CiteRite` and run `mkdir config`
3. create a file named `config.php` and fill in appropriate values:
```
<?php
$config_array = array(
    'debug' => true,
    'templates.path' => 'templates',
    'dbHost' => '',
    'dbUser' => '',
    'dbPass' => '',
    'dbName' => ''
);
?>
```
4. Ensure you have Composer Dependency manager and run `composer install` 
## Run Local Server
 Run `php -S localhost:8888 -t public public/index.php` to serve the site

## Shared Hosting Installation
1. Do steps from Local Installation, if you haven't already.
2. Take all contents of the `CiteRite/public` folder and place them in your `public_html` folder.
3. Delete the `CiteRite/public` folder.
4. Place the `CiteRite` folder with its contents, in the directory above the `public_html` folder.
5. Edit the `.htaccess` file located in the `CiteRite` folder.
```
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^  index.php [L]
    RewriteCond %{HTTP_HOST} !^pluto.cse.msstate.edu$ [NC] <--- put your domain in place of pluto.cse.msstate.edu
    RewriteBase /~dc1829/        <------put any subfolders you have (This example: pluto.cse.msstate.edu/~dc1829/public_html)
</IfModule>
```
6. Move the `.htaccess` file from above to your `public_html` folder
