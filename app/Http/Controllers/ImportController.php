<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportController extends Controller
{
    public function index()
    {
        $response = Http::get('https://randomuser.me/api/?results=5000');

        $users = $response->collect('results')->chunk(500);
        $added_count = 0;
        $updated_count = 0;
        foreach ($users as $chunk) {
            foreach ($chunk as $user) {
                $db_user = User::updateOrCreate([
                    'first_name' => data_get($user, 'name.first'),
                    'last_name'  => data_get($user, 'name.last'),
                ], [
                    'email' => data_get($user, 'email'),
                    'age'   => data_get($user, 'dob.age')
                ]);
                // It will return true if the record have been modified
                $wasChanged = $db_user->wasChanged();
                if ($wasChanged)
                    $updated_count++;
                else
                    $added_count++;
            }
        }

        return [
            'total'   => DB::table('users')->count(),
            'added'   => $added_count,
            'updated' => $updated_count,
        ];
    }
}
