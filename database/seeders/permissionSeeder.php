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

       $user_create = Permission::create(['name' => 'create.user']);

        //auth
        $login_auth = Permission::create(['name'=>'auth.login']);
        $logout_auth = Permission::create(['name'=>'auth.logout']);
        $me_auth = Permission::create(['name'=>'auth.me']);
        $selfedit_auth = Permission::create(['name'=>'auth.selfedit']);
        $dashboard_auth = Permission::create(['name'=>'auth.dashboard']);

        //admin

        $dashboard_admin = Permission::create(['name'=>'admin.dashboard']);
        $login_admin = Permission::create(['name'=>'admin.login']);
        $assign_admin = Permission::create(['name'=>'admin.assign']);
        $userindex_admin = Permission::create(['name'=>'admin.userindex']);
        $orderindex_admin = Permission::create(['name'=>'admin.orderindex']);


        // user

        $reports_admin = Permission::create(['name'=>'admin.reports']);
        $answercomment_admin = Permission::create(['name'=>'admin.answercomment']);

        //user

        $index_user = Permission::create(['name'=>'user.index']);
        $edit_user = Permission::create(['name'=>'user.edit']);
        $delete_user = Permission::create(['name'=>'user.delete']);
        $editpassword_user = Permission::create(['name'=>'user.editpassword']);

        // setting
        $index_setting = Permission::create(['name'=>'setting.index']);
        $edit_setting= Permission::create(['name'=>'setting.edit']);


        //products

        //product

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

        //comment
        $create_comments= Permission::create(['name'=>'comments.create']);
        $index_comments= Permission::create(['name'=>'comments.index']);
        $delete_comments= Permission::create(['name'=>'comments.delete']);
        $like_comments= Permission::create(['name'=>'comments.like']);
        $dislike_comments= Permission::create(['name'=>'comments.dislike']);

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

        $userticket_tickets= Permission::create(['name'=>'tickets.usertickets']);

        //massages
        $create_messages= Permission::create(['name'=>'messages.create']);
        $index_messages= Permission::create(['name'=>'messages.index']);
        $edit_messages= Permission::create(['name'=>'messages.edit']);
        $delete_messages= Permission::create(['name'=>'messages.delete']);


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

        $add_rating= Permission::create(['name'=>'rating.add']);


        //discount
        $create_discount= Permission::create(['name'=>'discount.create']);
        $index_discount= Permission::create(['name'=>'discount.index']);
        $edit_discount= Permission::create(['name'=>'discount.edit']);
        $delete_discount= Permission::create(['name'=>'discount.delete']);



        $admin->givePermissionTo([
            $create_discount,$index_discount,$edit_discount,$delete_discount,
            $create_label,$addFave_label,$unFave_label,$index_label,$edit_label,$delete_label,     $add_rating,
            $create_messages,$delete_messages,$edit_messages,$index_messages,
            $create_tickets,$delete_tickets,$edit_tickets,$index_tickets,$userticket_tickets,
            $add_categories,$create_categories,$edit_categories,$delete_categories,$index_categories,
            $edit_teachers,$create_teachers,$index_teachers,$delete_teachers,
            $create_comments,$delete_comments,$dislike_comments,$index_comments,$like_comments,$answercomment_admin,
            $create_orders,$edit_orders,$delete_orders,$index_orders,
            $create_posts,$delete_posts,$index_posts,$edit_posts,
            $create_products,$addmedia_products,$edit_products,$delete_products,$index_products,
            $edit_setting,$index_setting,
            $index_user,$delete_user,$edit_user,$editpassword_user,
            $assign_admin,$login_admin,$dashboard_admin,$orderindex_admin,$reports_admin,$userindex_admin,
            $dashboard_auth,$me_auth,$login_auth,$logout_auth,$selfedit_auth
        ]);

        $user->givePermissionTo([
            $add_rating,$addFave_label,$unFave_label,$create_messages,$index_messages,$create_tickets,
            $userticket_tickets,$index_categories,$index_teachers,$create_comments,$index_comments,
            $like_comments,$dislike_comments,$create_orders,$index_posts,$index_products,$index_setting,
            $editpassword_user,$logout_auth,$me_auth,$selfedit_auth,$dashboard_auth
        ]);


    }
}
