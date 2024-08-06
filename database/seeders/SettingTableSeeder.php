<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->insert([
            ['key'=>'site_name','value'=>'Makeen Project'],
            ['key'=>'site_description','value'=>'Makeen offline'],
            ['key'=>'whatsapp','value'=>'09938882434'],
            ['key'=>'instagram','value'=>'makeenacademy'],
            ['key'=>'phone','value'=>'021-77188185-6'],
            ['key'=>'telegram','value'=>'makeen_academy'],
            ['key'=>'address','value'=>'مترو علم و صنعت بزرگراه شهید سلیمانی بین مداین و23ام پلاک 520 همکف'],
        ]);

        DB::table('labels')->insert([
            'name'=>'favorite'
        ]);
        DB::table('categories')->insert([
            ['name'=>'suggest']
        ]);

        DB::table('labels')->insert([
            'name'=>'favorite'
        ]);

        DB::table('model_has_roles')->insert([
            'role_id'=>4,
            'model_type'=>'App\Models\User',
            'model_id'=>1
        ]);
    }
}
