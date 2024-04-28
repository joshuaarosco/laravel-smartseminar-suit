<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ServiceInterfaceProvider extends ServiceProvider
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
        $directory = new \RecursiveDirectoryIterator(app_path() . '/Services/');
        $iterator = new \RecursiveIteratorIterator($directory);
        foreach ($iterator as $info) {
            if (strpos($info->getFilename(), "Service.php") !== false) {
                $serviceName = $info->getPath() . '/' . $info->getBasename('.php');
                $serviceInterfaceName = $info->getPath() . '/I' . $info->getBasename('.php');

                $service = str_replace(app_path(), "App", $serviceName);
                $interface = str_replace(app_path() . '/', "App/Domain/Interfaces/", $serviceInterfaceName);

                $this->app->bind(
                    str_replace('/', '\\', $interface),  // App\Domain\Interfaces\Services\I<*>Service.php
                    str_replace('/', '\\', $service)  // App\Services\<*>Service.php
                );
            }
        }
    }
}
