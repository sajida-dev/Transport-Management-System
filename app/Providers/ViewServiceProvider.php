<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('admin.layouts.app', function ($view) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $uid = $user->id;
            $rawSidebar = config('sidebar');

            $sidebar = [];

            foreach ($rawSidebar as $category => $items) {
                $filteredItems = [];

                foreach ($items as $item) {
                    if (isset($item['permission']) && !$user->can($item['permission'])) {
                        continue;
                    }

                    // Replace placeholder
                    if (isset($item['params'])) {
                        foreach ($item['params'] as $key => $value) {
                            if ($value === '__AUTH_UID__') {
                                $item['params'][$key] = $uid;
                            }
                        }
                    }

                    $filteredItems[] = $item;
                }

                if (!empty($filteredItems)) {
                    $sidebar[$category] = $filteredItems;
                }
            }

            $view->with([
                'sidebarItems' => $sidebar,
                'user' => $user,
            ]);
        });
    }
}
