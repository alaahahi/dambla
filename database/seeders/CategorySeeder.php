<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('category')->delete();
        $data = ['name' => 'Featured Parties','order_on' => '1',];
        Category::create($data);
        $data = ['name' => 'T','order_on' => '2',];
        Category::create($data);
        $data = ['name' => 'Maniruzzaman Akash','order_on' => '3',];
        Category::create($data);
        $data = ['name' => 'Maniruzzaman Akash','order_on' => '4',];
        Category::create($data);

    }
}
