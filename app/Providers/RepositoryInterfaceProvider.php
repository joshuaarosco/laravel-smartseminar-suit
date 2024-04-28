<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryInterfaceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */

    public function register()
    {
        $directory = new \RecursiveDirectoryIterator(app_path() . '/Repositories/');
        $iterator = new \RecursiveIteratorIterator($directory);
        foreach ($iterator as $info) {
            if (strpos($info->getFilename(), "Repository.php") !== false) {
                $repoName = $info->getPath() . '/' . $info->getBasename('.php');
                $repoInterfaceName = $info->getPath() . '/I' . $info->getBasename('.php');

                $repository = str_replace(app_path(), "App", $repoName);
                $interface = str_replace(app_path() . '/', "App/Domain/Interfaces/", $repoInterfaceName);

                $this->app->bind(
                    str_replace('/', '\\', $interface),  // App\Domain\Interfaces\Repositories\I<*>Repository.php
                    str_replace('/', '\\', $repository)  // App\Repositories\<*>Repository.php
                );
            }
        }
    }
}
