<?php

namespace Modules\Theme\Events\Handlers;

use Maatwebsite\Sidebar\Group;
use Maatwebsite\Sidebar\Item;
use Maatwebsite\Sidebar\Menu;
use Modules\Core\Sidebar\AbstractAdminSidebar;

class RegisterThemeSidebar extends AbstractAdminSidebar
{
    /**
     * @param Menu $menu
     * @return Menu
     */
    public function extendWith(Menu $menu)
    {
        $menu->group('Settings', function (Group $group) {
            $group->weight(1);
            //$group->hideHeading();

            $group->item(trans('theme::theme.name'), function (Item $item) {
                $item->icon('fa fa-cogs');
                $item->route('dashboard.index');
                $item->isActiveWhen(route('dashboard.index', null, false));
                // $item->authorize(
                //     true
                // );
            });
        });

        return $menu;
    }
}
