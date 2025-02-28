<?php

namespace Modules\Jobs\App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Jobs\App\Interfaces\JobApplyRepositoryInterface;
use Modules\Jobs\App\Interfaces\JobCompanyIndustryRepositoryInterface;
use Modules\Jobs\App\Interfaces\JobCompanyTypeRepositoryInterface;
use Modules\Jobs\App\Interfaces\JobEmploymentTypeRepositoryInterface;
use Modules\Jobs\App\Interfaces\JobRepositoryInterface;
use Modules\Jobs\App\Interfaces\JobSeniorityLevelRepositoryInterface;
use Modules\Jobs\App\Interfaces\TagRepositoryInterface;
use Modules\Jobs\App\Repositories\JobApplyRepository;
use Modules\Jobs\App\Repositories\JobCompanyIndustryRepository;
use Modules\Jobs\App\Repositories\JobCompanyTypeRepository;
use Modules\Jobs\App\Repositories\JobEmploymentTypeRepository;
use Modules\Jobs\App\Repositories\JobRepository;
use Modules\Jobs\App\Repositories\JobSeniorityLevelRepository;
use Modules\Jobs\App\Repositories\TagRepository;

class JobsServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Jobs';

    protected string $moduleNameLower = 'jobs';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/migrations'));
    }

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(
            JobRepositoryInterface::class,
            JobRepository::class
        );
        $this->app->bind(
            JobCompanyIndustryRepositoryInterface::class,
            JobCompanyIndustryRepository::class
        );
        $this->app->bind(
            JobCompanyTypeRepositoryInterface::class,
            JobCompanyTypeRepository::class
        );
        $this->app->bind(
            JobEmploymentTypeRepositoryInterface::class,
            JobEmploymentTypeRepository::class
        );
        $this->app->bind(
            JobSeniorityLevelRepositoryInterface::class,
            JobSeniorityLevelRepository::class
        );
        $this->app->bind(
            TagRepositoryInterface::class,
            TagRepository::class
        );
        $this->app->bind(
            JobApplyRepositoryInterface::class,
            JobApplyRepository::class
        );
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/'.$this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes([module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower.'.php')], 'config');
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/'.$this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower.'-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

        $componentNamespace = str_replace('/', '\\', config('modules.namespace').'\\'.$this->moduleName.'\\'.config('modules.paths.generator.component-class.path'));
        Blade::componentNamespace($componentNamespace, $this->moduleNameLower);
    }

    /**
     * Get the services provided by the provider.
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (config('view.paths') as $path) {
            if (is_dir($path.'/modules/'.$this->moduleNameLower)) {
                $paths[] = $path.'/modules/'.$this->moduleNameLower;
            }
        }

        return $paths;
    }
}
