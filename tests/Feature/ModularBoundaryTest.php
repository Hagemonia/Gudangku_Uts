<?php

namespace Tests\Feature;

use App\Modules\ProductModule\Contracts\ProductCatalogContract;
use App\Modules\ProductModule\Services\ProductCatalogService;
use App\Modules\SupplierModule\Contracts\SupplierDirectoryContract;
use App\Modules\SupplierModule\Services\SupplierDirectoryService;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class ModularBoundaryTest extends TestCase
{
    public function test_modules_never_import_models_of_other_modules(): void
    {
        $violations = [];

        foreach (File::directories(app_path('Modules')) as $moduleDirectory) {
            $module = basename($moduleDirectory);

            foreach (File::allFiles($moduleDirectory) as $file) {
                if ($file->getExtension() !== 'php') {
                    continue;
                }

                preg_match_all('/use App\\\\Modules\\\\(\w+)\\\\Models\\\\/', File::get($file->getPathname()), $matches);

                foreach ($matches[1] as $other) {
                    if ($other !== $module) {
                        $violations[] = "{$module} mengimpor model {$other} di {$file->getFilename()}";
                    }
                }
            }
        }

        $this->assertSame([], $violations);
    }

    public function test_each_module_has_its_own_service_provider(): void
    {
        $modules = File::directories(app_path('Modules'));

        $this->assertGreaterThanOrEqual(3, count($modules));

        foreach ($modules as $moduleDirectory) {
            $module = basename($moduleDirectory);

            $this->assertFileExists("{$moduleDirectory}/Providers/{$module}ServiceProvider.php");
        }
    }

    public function test_contracts_are_bound_to_their_implementations(): void
    {
        $this->assertInstanceOf(ProductCatalogService::class, app(ProductCatalogContract::class));
        $this->assertInstanceOf(SupplierDirectoryService::class, app(SupplierDirectoryContract::class));
    }
}
