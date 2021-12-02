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
            $menuOption->title = 'Create new student';
            $menuOption->url = '/student/create';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();

            $menuOption = new MenuOptions();
            $menuOption->title = 'List all students';
            $menuOption->url = '/student';
            $menuOption->icon = 'fa-circle';
            $menuOption->parent_id = $parentMenuOption->id;
            $menuOption->save();


        }
    }
}
