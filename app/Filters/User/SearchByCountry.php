<?php
namespace App\Filters\User;

use Closure;

class SearchByCountry
{
    public function __construct(protected $country) {}

    public function __invoke($query, $next)
    {
        if(! $this->country) {
            return $next($query);
        }

        $query->whereHas('location', function ($q) {
            $q->where('country', $this->country);
        });

        return $next($query);
    }
}
