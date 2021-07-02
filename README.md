# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ahmedjoda/joda-resources.svg?style=flat-square)](https://packagist.org/packages/ahmedjoda/joda-resources)
[![Total Downloads](https://img.shields.io/packagist/dt/ahmedjoda/joda-resources.svg?style=flat-square)](https://packagist.org/packages/ahmedjoda/joda-resources)
![GitHub Actions](https://github.com/ahmedjoda/joda-resources/actions/workflows/main.yml/badge.svg)

a trait that generates resources methods for controller.

## Installation

You can install the package via composer:

```bash
composer require ahmedjoda/joda-resources
```

## Usage

```php
use Ahmedjoda\JodaResources\JodaResources;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{

     use JodaResources;

     // required
     protected $model = User::class;
     // model that will be used for crud operations


     // optional
     // will be the name of the model in lower case if not set in this example 'user'
     protected $name = 'user';
     // name of the model that will be used in views ans routes in case there are not set


     // optional
     // will be the name of the model if not set in this example 'user'
     protected $view = 'user';
     // name of the model that will be used in returned views


     /// optional
     // will be plural of the name attribute if not set in this example 'users'
     protected $route = 'users';
     // name of the model that will be used in returned routes after finishing the operation


     // optional
     protected $files = ['photo'];
     // items will be uploaded from the request in case there is file with the same name
     // files will be saved in /uploads/{pluralNameOfTheModel} with name {user_id}-{time}.{ext}
     // ex uploads/users/1-1624479228.jpg

     // file will be deleted automatically upon deleting the object

}

//methods will be provided

//index => will return view($view) with three variables, 'route' route of the resource to be used in actions, 'index' (all users) and plural name of the model in this example 'users' you can use either of them

//create => will return view($view.create)

//store => will save all cols from request then return to $route.index

//show => will return view($view.show ) with two variables, 'show' and name of the model in this example 'user' you can use either of them

//edit => will return view($view.edit) with tow variables, 'edit' and name of the model in this example 'user' you can use either of them

//update => will update all cols from request then return to $route.index

//destroy => will save all cols from request then return to $route.index
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email ahmedjoda02@gmail.com instead of using the issue tracker.

## Credits

- [Ahmed Joda](https://github.com/ahmedjoda)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.