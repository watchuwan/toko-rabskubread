<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;

class MakeSingularFilamentResource extends Command
{
    protected $signature = 'make:filament-resource-singular {name} {--G|generate}';
    
    protected $description = 'Create a new Filament resource with singular naming';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $generate = $this->option('generate');
        
        // Jalankan command filament original
        $command = "make:filament-resource {$name}";
        if ($generate) {
            $command .= ' --generate';
        }
        
        $this->call($command);
        
        // Modify file yang baru dibuat
        $this->modifyResourceFile($name);
        
        $this->info("Singular Filament Resource created successfully!");
    }

    protected function modifyResourceFile($name)
    {
        $className = Str::studly($name);
        $path = app_path("Filament/Resources/{$className}Resource.php");
        
        if (!$this->files->exists($path)) {
            $this->error("Resource file not found: {$path}");
            return;
        }
        
        $content = $this->files->get($path);
        
        // Cari posisi setelah "protected static ?string $model"
        $modelProperty = "protected static ?string \$model = {$className}::class;";
        
        // Properties yang akan ditambahkan
        $singularName = $name;
        $slug = Str::slug(Str::snake($name));
        
        $properties = "\n\n    protected static ?string \$modelLabel = '{$singularName}';\n";
        $properties .= "    protected static ?string \$pluralModelLabel = '{$singularName}';\n";
        $properties .= "    protected static ?string \$navigationLabel = '{$singularName}';\n";
        $properties .= "    protected static ?string \$slug = '{$slug}';";
        
        // Insert properties setelah $model
        $content = str_replace(
            $modelProperty,
            $modelProperty . $properties,
            $content
        );
        
        $this->files->put($path, $content);
        
        $this->info("Modified resource file with singular properties.");
    }
}
