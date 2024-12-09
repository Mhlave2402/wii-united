<?php

namespace Modules\AdminModule\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Modules\AdminModule\Repository\ActivityLogRepositoryInterface;
use Modules\AdminModule\Repository\AdminNotificationRepositoryInterface;
use Modules\AdminModule\Repository\Eloquent\ActivityLogRepository;
use Modules\AdminModule\Repository\Eloquent\AdminNotificationRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $repositoriesPath = base_path('Modules/AdminModule/Repository/Eloquent');
        $repositoryInterfacePath = base_path('Modules/AdminModule/Repository');
        $repositoryFiles = File::files($repositoriesPath);
        foreach ($repositoryFiles as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $interfaceName = $filename . 'Interface';
            $interfacePath = $repositoryInterfacePath . DIRECTORY_SEPARATOR . $interfaceName . '.php';
            if (File::exists($interfacePath)) {
                $interface = 'Modules\AdminModule\Repository\\' . $interfaceName;
                $repository = 'Modules\AdminModule\Repository\Eloquent\\' . $filename;
                $this->app->bind($interface, $repository);
            }
        }

        //service
        $servicesPath = base_path('Modules/AdminModule/Service');
        $serviceInterfacePath = base_path('Modules/AdminModule/Service/Interface');
        $serviceFiles = File::files($servicesPath);
        foreach ($serviceFiles as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $interfaceName = $filename . 'Interface';
            $interfacePath = $serviceInterfacePath . DIRECTORY_SEPARATOR . $interfaceName . '.php';
            if (File::exists($interfacePath)) {
                $serviceInterface = 'Modules\AdminModule\Service\Interface\\' . $interfaceName;
                $service = 'Modules\AdminModule\Service\\' . $filename;
                $this->app->bind($serviceInterface, $service);
            }
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
