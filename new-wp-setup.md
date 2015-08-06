#Create a new WordPress site with Roots base

##Update git repo
1. Go to the local git repo of the Sage theme
  - `$ cd ~/Sites/gits/sage`
2. Go to master branche  
  - `$ git checkout master`
3. Pull files  
  - `$ git pull --rebase upstream master`  
###Pull repo update (if there are updates)  
4. Go to feature branche  
  - `$ git checkout featurebranche`   
5. Rebase master  
  `- $ git rebase master`
###Resolve merge fail (if filemerge fails)
6. Open failed file
7. Search for `>>>>>`
8. Make changes
9. Remove all `>>`, `<<` & `==`
10. Add changed files to git  
  - `$ git add [File name]`
10. Continue script  
  - `$ git rebase --continue`
###Continue
11. Force push updated theme to GitHub   
  - `$ git push --force`

##Install the site

1. Create a new database with name `[sitename]`
  - `$ mysql -u [sql username] -p -e "CREATE DATABASE [sitename]";`
  - `$ [sql password]`

2. Make new folder in Sites directory: 
  - `$ mkdir ~/Sites/[sitename]`

3. Go to the site folder 
  - `$ cd ~/Sites/[sitename]`

4. Install WordPress with Yeoman `$ yo wordpress`
  - WordPress URL: *[sitename].dev*
  - Table prefix: *wp_*
  - Database host: *localhost*
  - Database name: *[sitename]*
  - Database user: *[sql username]*
  - Database password: *[sql password]*
  - Use Git: *n*
  - Would you like to install WordPress as a submodule?: *n*
  - Would you like to install WordPress with the custom directory structure? *n*
  - Install a custom theme? *y*
  - Destination directory: *[sitename]*
  - Theme source type (git/tar): *git*
  - Task runner (grunt/gulp): *gulp*
  - GitHub username: *ericimthorn*
  - GitHub repository name: *sage*
  - Repository branch: *featurebranche*
  - Does this all look correct?: *y*


5. Go to `http://[sitename].dev`in the browser and follow the steps to install WordPress.

6. Install the *Soil* plugin with Composer ( <https://github.com/roots/soil> )
  - `$ composer require roots/soil 3.4.0`
  - `$ wp plugin activate soil`

7. Activate theme with `$ wp theme activate [sitename]`

<<<<<<< HEAD
8. Add menu  
  - `$ wp menu create "primary-menu"`  
  - `$ wp menu location assign primary-menu primary_navigation`
  - `$ wp menu item add-post primary-menu 1 --title="Home"`

9. Go to the theme files `$ cd wp-content/themes/[sitename]`

10. In `assets/manifest.json`, change value `devUrl` to `http://[sitename].dev`

11. Install npm packages
  - `$ npm install`

12. Install Bower packages
  - `$ bower install`

13. Initiate Gulp
  - `$ gulp`

14. Execute Gulp watcher
  - `$ gulp watch`
=======
8. Go to the theme files `$ cd wp-content/themes/[sitename]`

9. In `assets/manifest.json`, change value `devUrl` to `http://[sitename].dev`

10. Install npm packages
  - `$ npm install`

11. Install Bower packages
  - `$ bower install`

12. Initiate Gulp
  - `$ gulp`

13. Execute Gulp watcher
  - `$ gulp watch`
<!--
## Troubleshooting
If **.htaccess** file is not created in *root* create new file  called `.htaccess` and place:
```
# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
```
-->
>>>>>>> minor bugs
