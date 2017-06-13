<?php

namespace Tests;

trait TestHelper {

    protected static $models = [];

    public static function getModelClasses()
    {
        $files = array();

        // Get all models in the Model directory
        $pathToModels = app_path();

        // Get all entities in the Src directory
        $srcPath = app_path();   // <-- EDIT THIS LINE WITH YOUR OWN PATH
        $srcPSR4Namespace = 'App';  // <-- EDIT THIS LINE WITH YOUR PSR4 namespace to /src dir

        // loop through models dir
        foreach (new \DirectoryIterator($pathToModels) as $fileInfo) {
            if ($fileInfo->isFile() AND ! $fileInfo->isDot() AND $fileInfo->getExtension() === 'php') {
                $name = $fileInfo->getBaseName('.php');

                // Exclude Base models
                if (strpos($name, 'Base') === FALSE) {
                    // Add model to list
                    $files[$name] = $name;
                }
            }
        }


        // entities are models too, loop those also.
        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($srcPath)) as $name => $fileInfo) {

            // Exclude Base models and Interfaces, only include Entity
            if (strpos($name, 'Base') === FALSE AND strpos($name, 'Interface') === FALSE AND strpos($name, 'Entity.php')) {
                $name = $fileInfo->getBaseName('.php');
                $path = str_replace(DIRECTORY_SEPARATOR, '\\', str_replace($srcPath, $srcPSR4Namespace, $fileInfo->getPath())).'\\';

                // Add model to list
                $files[$path.$name] = $path.$name;
            }
        }

        return $files;
    }

    protected function resetEvents()
    {
        // Reset each model event listeners.
        foreach (self::$models as $model) {

            // Flush any existing listeners.
            call_user_func([$model, 'flushEventListeners']);

            // Reregister them.
            call_user_func([$model, 'boot']);
        }
    }
}