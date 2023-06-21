<?php

namespace Dwikipeddos\PeddosLaravelTools\Queries;

use Exception;
use Spatie\QueryBuilder\QueryBuilder;

abstract class PaginatedQuery extends QueryBuilder
{
    // abstract public function sort(): self;
    // abstract public function filter(): self;

    //used to get all params
    public function getAllParams()
    {
        return [
            "append" => $this->append,
            "sort" => $this->getAllowedSorts(),
            "filter" => $this->getAllowedFilters(),
            "include" => $this->getAllowedIncludes(),
        ];
    }

    //allowed filter parameters, members should be Spatie\QueryBuilder\AllowedFilter
    protected function getAllowedFilters(): array
    {
        return [];
    }

    //allowed paramter to be used on sort, members should be Spatie\QueryBuilder\AllowedSort
    protected function getAllowedSorts(): array
    {
        return [];
    }

    //allowed includes, members should be Spatie\QueryBuilder\AllowedInclude
    protected function getAllowedIncludes(): array
    {
        return [];
    }

    //owner and super admin only, members should be Spatie\QueryBuilder\AllowedInclude
    protected function getAllowedIncludeAdmin(): array
    {
        return [];
    }

    //allowed appends
    protected array $append = [];

    //admin and super admin only
    protected array $appendAdmin = [];

    //permission name required for super admin/ owner access
    protected string $adminPermission = '';

    public function filter(): self
    {
        $this->allowedFilters($this->getAllowedFilters());
        return $this;
    }

    public function sort(): self
    {
        $this->allowedSorts($this->getAllowedSorts());
        return $this;
    }

    public function appends(): array
    {
        $allowed_appends = [];
        if ($this->isAllowedPermission()) $allowed_appends = array_merge($allowed_appends, $this->appendAdmin);
        return array_merge($allowed_appends, $this->append);
    }

    public function includes(): self
    {
        // if ($this->isAllowedPermission()) $this->allowedFilters($this->allowedIncludes($this->getAllowedIncludeAdmin()));
        $this->allowedIncludes($this->getAllowedIncludes());
        return $this;
    }

    //check if user is allowed, (is either admin or owner)
    public function isAllowedPermission(): bool
    {
        return $this->request->user()?->can($this->adminPermission) == true;
    }

    public function paginateLimitByRequest()
    {
        $limit = $this->request->limit ?? 20;
        return $this->paginate($limit);
    }

    public function filterSortPaginate()
    {
        return $this->filter()
            ->sort()
            ->paginateLimitByRequest();
    }

    public function filterSortPaginateWithAppend()
    {
        return $this->filter()->sort()->paginateLimitByRequest()->through(fn ($p) => $p->append($this->getAppends()));
    }

    public function paginateAndAppend(?int $limit = 20)
    {
        return $this->paginate($limit)->map(fn ($p) => $p->append($this->getAppends()));
    }

    public function firstAndAppend()
    {
        return $this->firstOrFail()->append($this->getAppends());
    }

    public function findAndAppend($id)
    {
        return $this->findOrFail($id)?->append($this->getAppends());
    }

    public function getAndAppend()
    {
        return $this->get()->append($this->getAppends());
    }

    protected function hasAppend(): bool
    {
        return method_exists($this, 'appends');
    }

    protected function getAppends()
    {
        //get allowed appends
        $allowed_appends = $this->append; //$this->hasAppend() ? $this->appends() : [];
        $append = $this->request->has('append') && is_array($this->request->append) ? $this->request->append : [];
        $diff = array_diff($append, $allowed_appends);

        throw_if(
            sizeof($diff) > 0 && config('query-builder.disable_invalid_filter_query_exception'),
            new Exception("not allowed to append : " . implode(',', $diff))
        );

        return array_intersect($append, $allowed_appends);
    }
}
