# What is CodeIgniter4-Single-SignOn
CodeIgniter 4 is used as a framework with
- Basic User Authentication
- Single Sign On
  - Facebook
    - Follow this [link](https://www.cloudways.com/blog/add-facebook-login-in-php/) to create Facebook console app.
      ```
      namespace Config;
      use CodeIgniter\Config\BaseConfig;
      class Facebook extends BaseConfig{
        public $default_graph_version='v2.5';
        public $app_id    = 'your app id';
        public $app_secret= 'your app secret';
        // Replace your website URL same as added in the developers.Facebook.com/apps e.g. 
        // if you used http instead of https and you used
        public $app_url     = 'http://dummy.com/';
      }
  - Gmail (Create class with below mentioned public variable and assign them values.)
    - Follow this [link](https://www.webslesson.info/2019/09/how-to-make-login-with-google-account-using-php.html) to create Google console app.
      ```
      namespace Config;
      use CodeIgniter\Config\BaseConfig;
      class Gmail extends BaseConfig{
        public $Scope=['email','profile'];
        public $RedirectUri = BASESEURL.'auth/login';
        public $ClientId = '';
        public $ClientSecret = ''; 
      }
      ```
      
## Run a project
  - Database
    - codeigniter4 
  - Migration
    - php spark migrate
  - Install vendors/packages
    - php composer.phar install (for a local composer) 
    - php composer install (for global composer) 
  - Run the project using command
    - php spark serve
  
## Server Requirements
PHP version 7.2 or higher is required, with the following extensions installed: 

- [intl](http://php.net/manual/en/intl.requirements.php)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php)
- xml (enabled by default - don't turn it off)

## Form validation help
- https://codeigniter.com/user_guide/libraries/validation.html#creating-custom-rules

## Add and Alter migration
- [URI](https://codeigniter4.github.io/userguide/dbmgmt/forge.html)
