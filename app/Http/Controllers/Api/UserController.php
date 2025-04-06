<?php

namespace App\Http\Controllers\Api;

use App\Filters\User\SearchByCity;
use App\Filters\User\SearchByCountry;
use App\Filters\User\SearchByGender;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $select_fields = $request->get('select_fields') ?? []; 

        // apply filters
        $filters = [
            new SearchByGender(request('gender')),
            new SearchByCity(request('city')),
            new SearchByCountry(request('country'))
        ];

        $filters = array_filter($filters);

        // get the custom columns based on given
        $selected_columns = $this->getAllFilterColumns($select_fields);


        $users = (new Pipeline(app()))
                ->send(User::query()
                    ->select($selected_columns['default_feilds'])
                    ->with([
                        'details' => function ($query) use($selected_columns) {
                            $query->select($selected_columns['detail_feilds']);
                        },
                        'location' => function ($query)  use($selected_columns) {
                            $query->select($selected_columns['location_feilds']);
                        }
                    ])
                )
                ->through($filters)
                ->thenReturn()
                ->limit(request('limit') ?? 5)
                ->get();
        

        return response()->json($users);
    }

        
    /**
     * getAllFilterColumns
     *
     * @param  mixed $select_fields
     * @return void
     */
    protected function getAllFilterColumns(array $select_fields)
    {
        $default_fields = ['id'];
        $user_details_fields = ['user_id'];
        $user_location_fields = ['user_id'];



        foreach($select_fields as $field) {

            match($field) {
                'gender' => $user_details_fields[] = $field,
                'city' => $user_location_fields[] = $field,
                'country' => $user_location_fields[] = $field,
                default => $default_fields[] = $field
            };

        }

        //dd($default_fields, $user_location_fields);
        
        return [
            'default_feilds' => count($default_fields) ? $default_fields : ['id','name','email'],
            'detail_feilds' => count($user_details_fields) ? $user_details_fields : ['user_id','gender'],
            'location_feilds' => count($user_location_fields) ? $user_location_fields : ['user_id','city','country'],
        ];
        
    }
}
