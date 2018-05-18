<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlatformsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	// factory(App\Platforms::class, 2 )->create();


    	DB::table('platforms')->insert([
	        'department' => rand(),
	        'platform' => rand(),
	        'company' => rand(),
	        'user_id' => rand(),
	        'criticality' => rand(),
	        'department_name' => str_random(10),
	        'platform_name' => str_random(10),
	        'company_name' => str_random(10)
	    ]);
    }
}
