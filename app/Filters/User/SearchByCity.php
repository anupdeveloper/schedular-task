<?php
namespace App\Filters\User;

use Closure;

class SearchByCity
{
    public function __construct(protected $city) {}

    public function __invoke($query, $next)
    {
        if(! $this->city) {
            return $next($query);
        }

        $query->whereHas('location', function ($q) {
            $q->where('city', $this->city);
        });

        return $next($query);
    }
}