<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuOptions;
use Illuminate\Database\Seeder;

class MenuOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Sidebar menu
        $menu = Menu::where(['title' => 'Sidebar menu'])->first();

        if(!empty($menu)) {
            $parentMenuOption = new MenuOptions();
            $parentMenuOption->title = 'General';
            $parentMenuOption->icon = 'fa-tachometer-alt';
            $parentMenuOption->menu_id = $menu->id;
            $parentMenuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Dashboard';
            $menuOption->url = '/dashboard';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $parentMenuOption = new MenuOptions();
            $parentMenuOption->title = 'Students';
            $parentMenuOption->url = '/student';
            $parentMenuOption->icon = 'fa-tachometer-alt';
            $parentMenuOption->menu_id = $menu->id;
            $parentMenuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Add new student';
            $menuOption->url = '/student/create';
            $menuOption->icon = 'fa-plus';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'List all students';
            $menuOption->url = '/student';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $parentMenuOption = new MenuOptions();
            $parentMenuOption->title = 'Bands';
            $parentMenuOption->url = '/band';
            $parentMenuOption->icon = 'fa-tachometer-alt';
            $parentMenuOption->menu_id = $menu->id;
            $parentMenuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Add new band';
            $menuOption->url = '/band/create';
            $menuOption->icon = 'fa-plus';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'List all bands';
            $menuOption->url = '/band';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $parentMenuOption = new MenuOptions();
            $parentMenuOption->title = 'Teachers';
            $parentMenuOption->url = '/teacher';
            $parentMenuOption->icon = 'fa-tachometer-alt';
            $parentMenuOption->menu_id = $menu->id;
            $parentMenuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Add new teacher';
            $menuOption->url = '/teacher/create';
            $menuOption->icon = 'fa-plus';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'List all teachers';
            $menuOption->url = '/teacher';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $parentMenuOption = new MenuOptions();
            $parentMenuOption->title = 'Rooms';
            $parentMenuOption->url = '/room';
            $parentMenuOption->icon = 'fa-tachometer-alt';
            $parentMenuOption->menu_id = $menu->id;
            $parentMenuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Add new room';
            $menuOption->url = '/room/create';
            $menuOption->icon = 'fa-plus';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'List all rooms';
            $menuOption->url = '/room';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $parentMenuOption = new MenuOptions();
            $parentMenuOption->title = 'Instruments';
            $parentMenuOption->url = '/instrument';
            $parentMenuOption->icon = 'fa-tachometer-alt';
            $parentMenuOption->menu_id = $menu->id;
            $parentMenuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Add new instrument';
            $menuOption->url = '/instrument/create';
            $menuOption->icon = 'fa-plus';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'List all instruments';
            $menuOption->url = '/instrument';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $parentMenuOption = new MenuOptions();
            $parentMenuOption->title = 'Calendar';
            $parentMenuOption->url = '/event';
            $parentMenuOption->icon = 'fa-tachometer-alt';
            $parentMenuOption->menu_id = $menu->id;
            $parentMenuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Add new event';
            $menuOption->url = '/event/create';
            $menuOption->icon = 'fa-plus';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'List all events';
            $menuOption->url = '/event';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Calendar';
            $menuOption->url = '/calendar';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $parentMenuOption = new MenuOptions();
            $parentMenuOption->title = 'Subscriptions';
            $parentMenuOption->url = '/subscription';
            $parentMenuOption->icon = 'fa-tachometer-alt';
            $parentMenuOption->menu_id = $menu->id;
            $parentMenuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Add new';
            $menuOption->url = '/subscription/create';
            $menuOption->icon = 'fa-plus';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'List all';
            $menuOption->url = '/subscription';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Subscription types';
            $menuOption->url = '/subscription_type';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $parentMenuOption = new MenuOptions();
            $parentMenuOption->title = 'Payments';
            $parentMenuOption->url = '/payment';
            $parentMenuOption->icon = 'fa-tachometer-alt';
            $parentMenuOption->menu_id = $menu->id;
            $parentMenuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Create new payment';
            $menuOption->url = '/payment/create';
            $menuOption->icon = 'fa-plus';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'List all payments';
            $menuOption->url = '/payment';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $parentMenuOption = new MenuOptions();
            $parentMenuOption->title = 'SMS';
            $parentMenuOption->url = '/sms';
            $parentMenuOption->icon = 'fa-tachometer-alt';
            $parentMenuOption->menu_id = $menu->id;
            $parentMenuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'Send a new SMS';
            $menuOption->url = '/sms/create';
            $menuOption->icon = 'fa-plus';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'List all SMS\'s';
            $menuOption->url = '/sms';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'SMS Templates';
            $menuOption->url = '/sms_template';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();


        }
    }
}
