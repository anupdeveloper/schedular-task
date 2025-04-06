<?php
namespace App\Filters\User;

use Closure;

class SearchByGender
{
    protected $gender;

    public function __construct($gender)
    {
        $this->gender = $gender;
    }

    public function __invoke($query, $next)
    {
        if(! $this->gender) {
            return $next($query);
        }

        $query->whereHas('details', function ($q) {
            $q->where('gender', $this->gender);
        });

        return $next($query);
    }
}
