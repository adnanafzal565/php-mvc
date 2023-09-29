# PHP MVC

## Configurations

Set your base URL in "system/config.php" file line #2:

```php
define("URL", "http://localhost:8888");
```

Set your database credentials in "system/config.php" file from line 9 to 12:

```php
define("DB_HOST", "localhost:8889");
define("DB_USER", "root");
define("DB_PASS", "root");
define("DB_NAME", "db_name");
```

## Usage

Set your default controller in "index.php" from line 53 to 55:

```php
require_once("controllers/HomeController.php");
$controller = new HomeController();
$controller->index();
```

To call the model method from controller, do the following from your controller method:

```php
$this->load_model("ModelClassName")->method_name();
```

Model class name and file name must be same. Just the extension of model class file should be ".php". For example, your users model will look like this:

```php
# models/UsersModel.php
class UsersModel extends Model
{
    private $table = "users";
    
    public function register()
    {
      //
    }
}
```

And from your controller, you can call the above method like this:

```php
$this->load_model("UsersModel")->register();
```

## Real-world usage

A movie ticket booking website is created using this framework. Check [this](https://adnan-tech.com/movie-ticket-booking-website-php-and-mysql/) out.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.
