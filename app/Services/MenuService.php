<?php

namespace App\Services;

use App\Models\Menu;
use Spatie\Menu\Laravel\Menu as SpatieMenu;
use Spatie\Menu\Laravel\Link;

class MenuService
{
    public function buildMenu()
    {
        $menuItems = Menu::whereNull('parent_id')->orderBy('order')->get();

        $menu = SpatieMenu::new()
            ->addClass('lg:flex lg:items-center space-x-4 ')
            ->addItemClass('block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700');
           
            
        $this->createMenuItems($menuItems)->each(function ($item) use ($menu) {
            $menu->add($item);
        });

        return $menu;
    }

    private function createMenuItems($items)
    {
        return $items->map(function ($item) {
            if ($item->children->count() > 0) {
                $submenu = SpatieMenu::new()
                    ->addClass('absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1')
                    ->addItemClass('block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100');
    
                $this->createMenuItems($item->children)->each(function ($subItem) use ($submenu) {
                    $submenu->add($subItem);
                });
    
                return SpatieMenu::new()
                    ->add(Link::to($item->url, $item->name)->addClass('relative group'))
                    ->add($submenu->addClass('hidden group-hover:block'));
            }
    
            return Link::to($item->url, $item->name);
        });
    }
}
