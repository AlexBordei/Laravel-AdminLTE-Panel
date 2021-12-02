<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\MenuOptions;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Response;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\never;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    private $menus;

    public function __construct() {
        $this->menus = Menu::where(['active' => true])->get();

        $index = 0;
        foreach ($this->menus as $menu) {
            $parentOptions = MenuOptions::where(
                [
                'menu_id' => $menu->id,
                'parent_id' => null,
                ]
            )->get();

            $parentIndex = 0;
            foreach ($parentOptions as $option) {
                $childOptions = MenuOptions::where(
                    [
                        'parent_id' => $option->id,
                    ]
                )->get();
                $parentOptions[$parentIndex]['options'] = $childOptions;
                $parentIndex++;
            }


            $this->menus[$index]['options'] = $parentOptions;
            $index++;
        }
    }


    public function buildResponse($view, $data = [])
    {
        $obj = [];
        $obj['menus'] = $this->menus;
        $obj['user'] = Auth::user();
        $obj['activeOptions'] = $this->getActiveMenuOption($obj['menus'], Route::getFacadeRoot()->current()->uri());
        $obj['data'] = $data;

        return new Response(
            view($view, $obj)
        );
    }

    private function getActiveMenuOption($menus, $path) {
        $parent_id = 0;
        $child_id = 0;

        foreach ($menus as $menu) {
            if(! empty($menu->options)) {
                foreach ($menu->options as $parentOption) {
                    if(empty($parentOption->options)) {
                        if(!empty($parentOption->url) && $parentOption->url === '/' . $path) {
                            $parent_id = $parentOption->id;
                        }
                    } else {
                        foreach ($parentOption->options as $childOption) {
                            if(!empty($childOption->url) && $childOption->url === '/' . $path) {
                                $parent_id = $parentOption->id;
                                $child_id = $childOption->id;
                            }
                        }
                    }
                }
            }
        }
        return [
            'parent_id' => $parent_id,
            'child_id' => $child_id
        ];
    }
}
