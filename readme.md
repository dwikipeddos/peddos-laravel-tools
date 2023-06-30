# Peddos Laravel Tools

A package for laravel that a compilation of tools for easy laravel development, this package contains external packages from [Spatie](https://spatie.be/) (the best laravel package developer!)

- [spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v5/introduction)
- [spatie/laravel-medialibrary](https://spatie.be/docs/laravel-medialibrary/v10/introduction)
- [spatie/laravel-activitylog](https://spatie.be/docs/laravel-activitylog/v4/introduction)
- [spatie/laravel-query-builder](https://spatie.be/docs/laravel-query-builder/v5/introduction)
- [spatie/laravel-data](https://spatie.be/docs/laravel-data/v3/introduction)

This package provide helpers and classes to simplify the usage of the packages above, or simply the way i like to use the package. Not only that, i also compiled classes that I myself often use in most of my projects.

This package can help you with

- Filtering queries using spatie's laravel-query-builder
- Simple use of spatie's media-library to attach a single media (which is usually the case, yes i am talking about profile picture)
- Generating and validating OTP
- Syncing all roles with their own permissions
- Generating CRUD files complete with its function!

## Installation

Use [composer](https://getcomposer.org/) dependency manager to install `peddos-laravel-tools`

```
composer require dwikipeddos/peddos-laravel-tools
```

don't forget to publish the config file by running

```
php artisan vendor:publish --tag=peddos-laravel-tools-config
```

also publish spatie permissions config and migration

```
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

## Usage Example

Below are example usage of the packages

**⚠️Warning!⚠️**

the command might be changed in the future, because this package is compiled from many project hence the inconsistentcy of the command names, and stuff. so... please do read the Usage example, because I wil try to update the documentation here ASAP

### Filtering Queries

Usually when using laravel-query-builder from spatie you use it like

```php
use Spatie\QueryBuilder\QueryBuilder;

QueryBuilder::for(Model::class)
    ->allowedFilters('name')
    ->get();
```

This is fine and good... until you need a lot more filtering possibilities, then adding sorting and includes to the equation, this can quickly clutter your controller, so we can move the querying part to seperate class on their own, you can do this by creating a new class that extends `Dwikipeddos\PeddosLaravelTools\Queries\PaginatedQuery` , I usually create a folder called `Queries` and put all my query class there.

Now all you need to do is fill the filtering and which Model will it query from like

```php
use Dwikipeddos\PeddosLaravelTools\Queries\PaginatedQuery;

class UserQuery extends PaginatedQuery
{
    //you have to override the __construct method
    public function __construct()
    {
        parent::__construct(User::query()); //which model to be queried for
    }

    //set filters that are allowed like you usually do when using spatie query builder
    protected function getAllowedFilters(): array
    {
        return [
            AllowedFilter::partial('name'),
            AllowedFilter::partial('email'),
        ];
    }

    //set sorts that are allowed like you usually do when using spatie query builder
    protected function getAllowedSorts(): array
    {
        return [
            AllowedSort::field('created_at'),
            AllowedSort::field('name'),
        ];
    }

    //set includes that are allowed like you usually do when using spatie query builder
    protected function getAllowedIncludes(): array
    {
        return [
            AllowedInclude::relationship('socialAccounts'),
            AllowedInclude::relationship('fcm_token'),
        ];
    }
}
```

we also added append so the API consumer can append some attributes that are hidden.
all you need to do just add

```php

//specify the attributes that can be appended
protected array $append = [
    'phone',
    'employer',
];
```

and now in your controller you can do

```php
function index(UserQuery $query){
    return $query->includes() //to apply includes
    ->sort() //to apply sorts
    ->filter() //to apply filter
}

//or you could simplify it even more with
function index(UserQuery $query){
    return $query->filterSortPaginateWithAppend();
}
```

paginate? did the function say paginate? yes, it did, we also has added page limit into our query class which you can use by adding `limit` query.
finally now you can do the filtering like what you usually do in laravel-query-builder

```
/user?filter[name]=peddos&sort=name&append[]=phone&limit=10
```

this will filter the user where the name is `peddos` and sorted by `name` and appending `phone` and the a single page will only contains `10` users

It Works!

### Generate and Verify OTP

To generate an OTP the package using `Action` class which basically just a class that handles a single function, to generate OTP is as simple as

```php
use Dwikipeddos\PeddosLaravelTools\Actions\GenerateOTPAction;

(new GenerateOTPAction)->execute(
    4, //number of seed
    1, //user_id
);
```

Or you can inject the action into the controller

```php
function login(LoginRequest $request, GenerateOTPAction $action){
    //login stuff
    $action->execute(4,1);
}
```

**⚠️Limitation⚠️**

This action only works with laravel's default User model, while you can publish the migration as of now, but you can only add some column without changing the existsing one, because it could broke the action.

### SingleMedia

TBA

### Sync Role and Permission

This package can help you sync role and permission and its quite easy, first of all you need to publish the config file if you haven't by running the command :

```
php artisan vendor:publish --tag=peddos-laravel-tools-config
```

Oh right, don't forget to run the migration

```
php artisan migrate
```

Now there's should be a file called `peddoslaraveltools.php` in your config directory. all you have to do now is write the permission and the role that are allowed to access it inside the `available_permissions` array, the config file should looks like this :

```php
'available_permissions' => [
    [
        'name' => 'post.create', //permission name
        'roles' => ['super-admin', 'user'] // role that are allowed to access said permission
    ],
    [
        'name' => 'post.view',
        'roles' => ['user','guest','super-admin']
    ]
],
```

Once you've done that now you can sync the permission and the role with the command

```
php artisan peddos-permission-role:sync
```

This command will create the permission and the role if its not yet in the database so you don't need to worry about it!

### Generating CRUD Files

You know that boring feelings of creating that basic CRUD files? from model, migration, factories, controller, routes, requests and policies? almost everything is the same except for the model it process right, so you do you actually need to type them out every time you creating a simple CRUD? well now you can skip that altogether with this package's CRUD generator! Simply run the command

```
php artisan generate:all {Name}
```

Replace {Name} with the model name you want to create and boom! Everything is generated! the basic CRUD is already working even filtering is already applied!

**⚠️Limitation⚠️**

Currently this command only able to generate basic CRUD and cannot generate more complex function, also you still need to fill the migration, factory, query, and rule for the validation in the FormRequest.

## License

[MIT](https://choosealicense.com/licenses/mit/)
