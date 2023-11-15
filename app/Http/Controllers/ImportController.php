<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ImportController extends Controller
{
    public function index()
    {
//        DB::table('users')->where('id', '>', 1)->delete();
//        die();
        $response = Http::get('https://randomuser.me/api/?results=5000');

        $users = $response->collect('results')->chunk(500);
        $startedAt = Carbon::now();

        foreach ($users as $chunk) {
            $data = [];
            foreach ($chunk as $user) {
                $data[] = [
                    'first_name' => data_get($user, 'name.first'),
                    'last_name'  => data_get($user, 'name.last'),
                    'email'      => data_get($user, 'email'),
                    'age'        => data_get($user, 'dob.age'),
                ];
            }
            User::upsert($data, ['first_name', 'last_name'], ['email', 'age']);
        }

        $added_count = DB::table('users')->whereNull('password')->whereColumn('created_at', '=', 'updated_at')->where('created_at', '>=', $startedAt)->count();
        $updated_count = DB::table('users')->whereNull('password')->whereColumn('created_at', '!=', 'updated_at')->where('updated_at', '>=', $startedAt)->count();

        return [
            'total'   => DB::table('users')->whereNull('password')->count(),
            'added'   => $added_count,
            'updated' => $updated_count,
            'entries' => $response->collect('results'),
        ];
    }
}
