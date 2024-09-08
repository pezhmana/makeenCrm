<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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
            ['key'=>'makeen_today','value'=>'lorem ipsum'],
            ['key'=>'makeen_feature','value' => json_encode([
                'تضمین کاملترین محتوا' => 'lorem ipsum',
                'پشتیبانی آموزش' => 'lorem ipsum',
                'اساتید حرفه ای' => 'lorem ipsum',
                'قابلیت دانلود ویدیو ها'=>'lorem ipsum'
            ])],
            ['key'=>'FAQ','value'=>json_encode([
                'آیادوره های آموزشی افلاین قابلیت دسترسی افلاین دارند ؟'=>'lorem ipsum',
                'چگونه ویدیو های آموزشی افلاین را میتونم دانلود کنم ؟'=>'lorem ipsum',
                'َشرایط استفاده از کد تخفیف چگونه است ؟'=>'lorem ipsum',
                'چگونه میتونم از دوره های آموزشی افلاین استفاده کنم ؟'=>'lorem ipsum',
            ])],
            ['key'=>'about_us','value'=>'ما مکین هستیم
امروزه با رشد چشم گیر فضای فناوری اطلاعات در کشور ، نیاز مبرم این صنعت  به نیروهای متخصص و کاربلد ، روز به روز در حال افزایشه. از طرف دیگه اما  به دلیل نقص سیستم آموزشی در اکثر دانشگاه ها در یاد دادن مهارت های  کاربردی ، نیروی مورد نیاز شرکت های حوزه فناوری اطلاعات ، تامین نمیشه .  به عبارت ساده تر ، بازار کار به شدت دنبال نیروی متخصص میگرده اما نیروی  مطلبوش رو پیدا نمیکنه . از طرفی فارغ التحصیلان رشته های .مهندسی کامپیوتر  و ... به شدت دنبال کار میگردن و در اکثر مواقع کاری پیدا نمیکنن'],
            ['key'=>'makeen_team','value'=>'lorem ipsum'],
            ['key'=>'contact_us','value'=>json_encode([
                'شما میتوانید از طریق راه های ارتباطی زیر با ما ارتباط داشته باشید :'=>[
                    'instagram'=>'makeenacademy',
                    'telegram'=>'@makeen_academy',
                    'whatsapp'=>'+989938882434',
                    'number'=>'021-77188185-6',
                    'address'=>'مترو علم و صنعت بزرگراه شهید سلیمانی بین مداین و23ام پلاک 520 همکف'
                ]
            ])]

        ]);

        DB::table('labels')->insert([
            'name'=>'favorite'
        ]);
        DB::table('categories')->insert([
            ['name'=>'پیشنهادی']
        ]);

        DB::table('labels')->insert([
            'name'=>'favorite'
        ]);



       $admin =  User::create([
            'name'=>'admin',
            'last_name'=>'admin',
            'phone'=>'09120413170',
            'password'=>Hash::make('1234'),
        ]);
       $admin->assignRole('admin');


    }
}
