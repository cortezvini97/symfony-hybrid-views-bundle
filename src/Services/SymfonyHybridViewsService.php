<?php

namespace Cortez\SymfonyHybridViews\Services;

use Cortez\SymfonyHybridViews\Utils\Blade;
use Cortez\SymfonyHybridViews\Utils\BladeContainer;
use Symfony\Component\DependencyInjection\Argument\ServiceLocator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SymfonyHybridViewsService
{
    private Blade $blade;
    private string $dir_directives;
    private string $functions_dir;
    private array $configs;

    public function __construct(array $configs)
    {
        $dir_view = $configs['dir_views'];
        $dir_cache = $configs['cache_dir'];
        $dir_directives = $configs["directives_dir"];
        $functions_dir = $configs["functions_dir"];
        $this->configs = $configs;
        $this->functions_dir = $functions_dir;
        $this->dir_directives = $dir_directives;
        $this->blade = new Blade($dir_view, $dir_cache, $configs["cache"]);
        $this->loadFunctions();
        $this->loadDirectives();
    }

    public function getBlade():Blade
    {
        return $this->blade;
    }

    public function setBlade(Blade $blade)
    {
        $this->blade = $blade;
    }

    public function getConfigs():array
    {
        return $this->configs;
    }

    public function setServiceLocator(ServiceLocator $serviceLocator)
    {
        $blade_container = $this->blade->getBladeContainer();
        $blade_container->setServiceLocator($serviceLocator);
    }

    public function view(string $view, array $params = [], array $services = []):string
    {
        require_once dirname(__DIR__, 2).DIRECTORY_SEPARATOR."autoload.php";
        autoload($this->functions_dir, $services);
        return $this->blade->make($view, $params)->render();
    }


    private function loadFunctions(){
        require_once dirname(__DIR__, 2).DIRECTORY_SEPARATOR."custom_funtions.php";
    }

    private function getCreatedDirectives()
    {
        $files = scandir($this->dir_directives);
        $files = array_slice($files, 2);
        $objs = [];
        foreach ($files as $file)
        {
            $directive_name = str_replace(".php", "", $file);
            $file_path = $this->dir_directives . DIRECTORY_SEPARATOR . $file;
            
            $callback = require_once $file_path;
            
            $objs[$directive_name] = $callback;
        }

        return $objs;
    }

    private function loadDirectives()
    {
        $directives_project = $this->getCreatedDirectives();
        $directives_lib = require_once dirname(__DIR__, 2).DIRECTORY_SEPARATOR."custom_directive.php";

        $directives = array_merge($directives_project, $directives_lib);

        foreach ($directives as $name => $callback) {
            $this->blade->directive($name, $callback);
        }
    }
}