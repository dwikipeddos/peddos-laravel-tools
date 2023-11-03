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
- and many more!

## Contents
- [Installation](#Installation)
- [Filtering Queries](#Filtering-Queries)
- [Generate and Verify OTP](#Generate-and-Verify-OTP)
- [SingleMedia](#SingleMedia)
- [Sync Role and Permission](#Sync-Role-and-Permission)
- [Generating CRUD Files](#Generating-CRUD-Files) 
- [Generating Actions](#Generating-Actions) 
- [Generating Enum](#Generating-Enum) 
- [Generating Query](#Generating-Query) 

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

### Usage Example

Below are example usage of the packages

**⚠️Warning!⚠️**

the command might be changed in the future, because this package is compiled from many project hence the inconsistentcy of the command names, and stuff. so... please do read the Usage example, because I wil try to update the documentation here ASAP

## Filtering Queries

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

## Generate and Verify OTP

To generate an OTP the package using `Action` class which basically just a class that handles a single function, to generate OTP is as simple as
to read more about action you might want to read [Generating Actions](#Generating-Actions) 

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

## SingleMedia

Single media is a tool to associate your model with only single media using ``Spatie\laravel-medialibrary`` 
Normally to load single media from model you would need to do : 

```php
$article->getFirstMedia()->getUrl();
```

and to store something to the media collection you would do something like

```php
$article->addMediaFromRequest("image")->toMediaCollection("image");
```

now with this package this is way simpler and easier.
first of you need to prepare your model like so :

```php
use Dwikipeddos\PeddosLaravel\Tools\HasSingleImage;
use Spatie\MediaLibrary\HasMedia;

class Article implements HasMedia{
    use HasSingleImage;
}
```

by default, the package should generate both the image and thumbnail with the collection name of "image" and "thumb" respectively, however you can change this by overrideng ``$defaultSingleImageName``  and the ``$defaultThumbnailName`` like

```php
class Article implements HasMedia{
    use HasSingleImage;

    $defaultThumnailName = "thumbnail";
    $defaultSingleImageName = "Banner";
}
```

also by default the package has image placeholder in case the model has no image at all, you can change the default by doing 

```php
public string $defaultSingleImage = "https://image.com/pepega.png";
public string $defaultThumbnail = "https://image.com/pepega-sm.png";

```

and, how do you insert image to using Single image? simply by

```php
class ArticleController extends Controller{
    function store(ArticleStoreRequest $request){
        $article = Article::create($request->validated());
        $article->addSingleImageFromRequest(); //THIS!
    }
}
```

by default addSingleImageFromRequest() will use the $defaultSingleImageName in the model as the request key that contains the image but you can do 

```php
    $article->addSingleImageFromRequest("my_image_key"); 
    //this will make the package to use "my_image_key" to find the image
```

and to get the image you can simply do

```php
$article->getSingleImage();
//or to get the thumbnail
$article->getSingleImageThumbnail();
```

IT WORKS!

## Sync Role and Permission

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

## Generating CRUD Files

You know that boring feelings of creating that basic CRUD files? from model, migration, factories, controller, routes, requests and policies? almost everything is the same except for the model it process right, so you do you actually need to type them out every time you creating a simple CRUD? well now you can skip that altogether with this package's CRUD generator! Simply run the command

```
php artisan generate:all {Name}
```

Replace {Name} with the model name you want to create and boom! Everything is generated! the basic CRUD is already working even filtering is already applied!

**⚠️Limitation⚠️**

Currently this command only able to generate basic CRUD and cannot generate more complex function, also you still need to fill the migration, factory, query, and rule for the validation in the FormRequest.

## Generating Actions

`Actions`,  Actions are well a simple class that can be injected and holds only a single simple function,  this is useful to offload some simple function off the controller.
to generate this Action all you need to do is 

```
php artisan make:action {name}
```

Replace {name} with the action name, usually it should be verb like "VerifyUserOTP". If you take a look at the generated file at `App\Actions\{Name}`, you should see something like
```php
namespace App\Actions;

class VerifyOtpAction
{
    public function execute(string $otp): bool
    {
       //some code
    }
}
```

As you can see Actions only has 1 function which is `execute`,  and this function may or may not take parameters, because this is just like service class but simpler.
You can use Action class in controller like 

```php
class AuthController{
    function verifyOtp(Request $request, VerifyOtpAction $action){
        //you can use it like this
        $isOtpValid = $action->execute($request->otp);
    }
}
```

or you can manually create it and use it like

```php
$isOtpValid = (new VerifyOtpAction)->execute($otp);
```

so in summary `Actions` are just service class that are so simple it just need 1 function (execute) but you kinda need it in multiple place so you need to make  a new class for it.

## Generating Enum

With php 8 supporting for Enum, I love it very much to the point i am abusing it 🙂, what is enum? basically enums are welp a class that define a custom type that is limited to one of a discrete number of possible values. for example, you want to store sex in DB some most likely you will store it as a single Char and use M and F for Male and Female. but what if you want to do something like that but with different kind of list? for example you want to make list of "how an employee get to work" you dont want to store it as a string, because it will take a lot of storage as your app scales up, what you can do is make the possible answers into Numbers and convert it later to string again for example :

- Walking = 1
- Driving = 2
- PublicTransportation = 3

and then on the DB you can store it an an int column voila, save more space, moreover if you want to add new possible answer all you need to do just assign number to that new answer and boom easy peasy.

a typical php enum should look like this :
```php
namespace App\Enums;

enum EmployeeAccomodation: int
{
    case WALKING = 1;
    case DRIVING = 2;
    case PUBLIC_TRANSPORTATION = 3;
}
```

to create one using this package you can use this command :
```
php artisan make:enum {name}
```

replace the {name} with the name of your enum, this should create a new file in ``App\Enums``. I know this is also a simple class that well probably don't need to make command for it but hey, i am lazy so here you go.
to convert number to its own enum in laravel you can do :

```php
EmployeeAccomodation::from(1); //WALKING
```

and you can also do something like this with the enum

```php
namespace App\Enums;

enum EmployeeAccomodation: int
{
    case WALKING = 1;
    case DRIVING = 2;
    case PUBLIC_TRANSPORTATION = 3;

    function toString(){
        return Str::title(Str::replace('_', ' ', Str::snake($this->name)));
    }

    //
    EmployeeAccomodation::from(3)->toString(); //Public Transportation
}
```

IT WORKS!

## Generating Query

Query classes are one of the most powerful card i have in this package. Usually a query is automatically generated by ``php artisan generate:all {name}`` but if for some reason you don't use it and want to make a query you can use this command to generate query

```
php artisan make:query {name}
```

Replace {name} with the name of your query, and it should be created in ``App\Queries\``
as for how to use the query you might want to read [Filtering Queries](#Filtering-Queries)

IT WORKS!

## License

[MIT](https://choosealicense.com/licenses/mit/)
