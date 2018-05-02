<?php

use Spatie\Menu\Laravel\Menu;
use Spatie\Menu\Laravel\Html;
use Spatie\Menu\Laravel\Link;

//Menu::macro('fullsubmenuexample', function () {
//    return Menu::new()->prepend('<a href="#"><span> Multilevel PROVA </span> <i class="fa fa-angle-left pull-right"></i></a>')
//        ->addParentClass('treeview')
//        ->add(Link::to('/link1prova', 'Link1 prova'))->addClass('treeview-menu')
//        ->add(Link::to('/link2prova', 'Link2 prova'))->addClass('treeview-menu')
//        ->url('http://www.google.com', 'Google');
//});

Menu::macro('adminlteSubmenu', function ($submenuName) {
    return Menu::new()->prepend('<a href="#"><span> ' . $submenuName . '</span> <i class="fa fa-angle-left pull-right"></i></a>')
        ->addParentClass('treeview')->addClass('treeview-menu');
});
Menu::macro('adminlteMenu', function () {
    return Menu::new()
        ->addClass('sidebar-menu')->setAttribute('data-widget','tree');
});
Menu::macro('adminlteSeparator', function ($title) {
    return Html::raw($title)->addParentClass('header');
});

Menu::macro('adminlteDefaultMenu', function ($content) {
    return Html::raw('<i class="fa fa-link"></i><span>' . $content . '</span>')->html();
});

Menu::macro('sidebar', function () {
    return Menu::adminlteMenu()
        ->add(Html::raw('HEADER')->addParentClass('header'))
        ->action('HomeController@index', '<i class="fa fa-home"></i><span>Home</span>')
//        ->url('http://www.google.com', 'Google')
        ->add(Menu::adminlteSeparator('Admin Modules'))
        #adminlte_menu
        ->add(Link::toUrl('/admin/sites', '<i class="fa fa-link"></i><span>SiteList</span>'))
        
//        ->add(
//            Menu::fullsubmenuexample()
//        )
//        ->add(
//            Menu::adminlteSubmenu('Best menu')
//                ->add(Link::to('/acacha', 'acacha'))
//                ->add(Link::to('/profile', 'Profile'))
//                ->url('http://www.google.com', 'Google')
//        )
        ->setActiveFromRequest();
});
