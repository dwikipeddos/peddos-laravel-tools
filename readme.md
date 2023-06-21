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

## Installation

Use [composer](https://getcomposer.org/) dependency manager to install `peddos-laravel-tools`

`composer require dwikipeddos/peddos-laravel-tools`

## Usage Example

Below are example usage of the packages

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

### OTP Helpers

TBA

### SingleMedia

TBA

### Sync Role and Permission

TBA

## License

[MIT](https://choosealicense.com/licenses/mit/)
