<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class permissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);
        $user = Role::create(['name' => 'user']);
        $student = Role::create(['name' => 'student']);
        $user_create = Permission::create(['name' => 'create.user']);
        $admin->givePermissionTo($user_create);

    // auth
        $login_auth = Permission::create(['name'=>'auth.login']);
        $logout_auth = Permission::create(['name'=>'auth.logout']);
        $me_auth = Permission::create(['name'=>'auth.me']);
        $create_auth = Permission::create(['name'=>'auth.create']);
        $selfedit_auth = Permission::create(['name'=>'auth.selfedit']);
        $profile_auth = Permission::create(['name'=>'auth.profile']);

        // admin
        $dashboard_admin = Permission::create(['name'=>'admin.dashboard']);
        $login_admin = Permission::create(['name'=>'admin.login']);
        $assign_admin = Permission::create(['name'=>'admin.assign']);
        $userindex_admin = Permission::create(['name'=>'admin.userindex']);
        $orderindex_admin = Permission::create(['name'=>'admin.orderindex']);

        // user
        $index_user = Permission::create(['name'=>'user.index']);
        $edit_user = Permission::create(['name'=>'user.edit']);
        $delete_user = Permission::create(['name'=>'user.delete']);
        $editpassword_user = Permission::create(['name'=>'user.editpassword']);

        // setting
        $index_setting = Permission::create(['name'=>'setting.index']);
        $edit_setting= Permission::create(['name'=>'setting.edit']);

        //products
        $create_products= Permission::create(['name'=>'products.create']);
        $index_products= Permission::create(['name'=>'products.index']);
        $edit_products= Permission::create(['name'=>'products.edit']);
        $addmedia_products= Permission::create(['name'=>'products.addmedia']);
        $delete_products= Permission::create(['name'=>'products.delete']);

        //post
        $create_posts= Permission::create(['name'=>'posts.create']);
        $edit_posts= Permission::create(['name'=>'posts.edit']);
        $index_posts= Permission::create(['name'=>'posts.index']);
        $delete_posts= Permission::create(['name'=>'posts.delete']);

        //orders
        $create_orders= Permission::create(['name'=>'orders.create']);
        $index_orders= Permission::create(['name'=>'orders.index']);
        $edit_orders= Permission::create(['name'=>'orders.edit']);
        $delete_orders= Permission::create(['name'=>'orders.delete']);

        //comments
        $create_comments= Permission::create(['name'=>'comments.create']);
        $index_comments= Permission::create(['name'=>'comments.index']);
        $delete_comments= Permission::create(['name'=>'comments.delete']);

        //teachers
        $create_teachers= Permission::create(['name'=>'teachers.create']);
        $index_teachers= Permission::create(['name'=>'teachers.index']);
        $edit_teachers= Permission::create(['name'=>'teachers.edit']);
        $delete_teachers= Permission::create(['name'=>'teachers.delete']);

        //categories
        $create_categories= Permission::create(['name'=>'categories.create']);
        $add_categories= Permission::create(['name'=>'categories.add']);
        $index_categories= Permission::create(['name'=>'categories.index']);
        $edit_categories= Permission::create(['name'=>'categories.edit']);
        $delete_categories= Permission::create(['name'=>'categories.delete']);

        //tickets
        $create_tickets= Permission::create(['name'=>'tickets.create']);
        $index_tickets= Permission::create(['name'=>'tickets.index']);
        $edit_tickets= Permission::create(['name'=>'tickets.edit']);
        $delete_tickets= Permission::create(['name'=>'tickets.delete']);

        //massages
        $create_massages= Permission::create(['name'=>'massages.create']);
        $index_massages= Permission::create(['name'=>'massages.index']);
        $edit_massages= Permission::create(['name'=>'massages.edit']);
        $delete_massages= Permission::create(['name'=>'massages.delete']);

        //label
        $create_label= Permission::create(['name'=>'label.create']);
        $addFave_label= Permission::create(['name'=>'label.addFave']);
        $unFave_label= Permission::create(['name'=>'label.unFave']);
        $index_label= Permission::create(['name'=>'label.index']);
        $edit_label= Permission::create(['name'=>'label.edit']);
        $delete_label= Permission::create(['name'=>'label.delete']);

        //rating
        $create_rating= Permission::create(['name'=>'rating.add']);
        $add_rating= Permission::create(['name'=>'rating.index']);

        //discount
        $create_discount= Permission::create(['name'=>'discount.create']);
        $index_discount= Permission::create(['name'=>'discount.index']);
        $edit_discount= Permission::create(['name'=>'discount.edit']);
        $delete_discount= Permission::create(['name'=>'discount.delete']);
    }
}
