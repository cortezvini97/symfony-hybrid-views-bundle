<?php 

namespace Cortez\SymfonyHybridViews\Utils;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\Contracts\View\View;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Events\Dispatcher;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\ViewServiceProvider;

class Blade implements Factory
{
    protected Container $container;
    private Factory $factory;
    private BladeCompiler $compiler;

    public function __construct(string $viewPaths, string $cachepath, bool $useCache, Container $container = null)
    {
        $this->container = $container ?: new BladeContainer;

        $this->setupContainer((array) $viewPaths, $cachepath, $useCache);
        (new ViewServiceProvider($this->container))->register();

        $this->factory = $this->container->get('view');
        $this->compiler = $this->container->get('blade.compiler');
    }


    public function getBladeContainer():BladeContainer
    {
        return $this->container;
    }

    public function setContainer(BladeContainer $container)
    {
        $this->container = $container;
    }
    

    public function make($view, $data = [], $mergeData = []): View
    {
        return $this->factory->make($view, $data, $mergeData);
    }

    public function compiler(): BladeCompiler
    {
        return $this->compiler;
    }

    public function directive(string $name, callable $handler)
    {
        $this->compiler->directive($name, $handler);
    }

    public function if($name, callable $callback)
    {
        $this->compiler->if($name, $callback);
    }

    public function exists($view): bool
    {
        return $this->factory->exists($view);
    }

    public function file($path, $data = [], $mergeData = []): View
    {
        return $this->factory->file($path, $data, $mergeData);
    }

    public function share($key, $value = null)
    {
        return $this->factory->share($key, $value);
    }

    public function composer($views, $callback): array
    {
        return $this->factory->composer($views, $callback);
    }

    public function creator($views, $callback): array
    {
        return $this->factory->creator($views, $callback);
    }

    public function addNamespace($namespace, $hints): self
    {
        $this->factory->addNamespace($namespace, $hints);

        return $this;
    }

    public function replaceNamespace($namespace, $hints): self
    {
        $this->factory->replaceNamespace($namespace, $hints);

        return $this;
    }

    public function __call(string $method, array $params)
    {
        return call_user_func_array([$this->factory, $method], $params);
    }

    protected function setupContainer(array $viewPaths, string $cachePath, bool $useCache)
    {
        $this->container->bindIf('files', fn () => new Filesystem);
        $this->container->bindIf('events', fn () => new Dispatcher);

        
        $this->container->bindIf('config', fn () => new Repository([
            'view.paths' => $viewPaths,
            'view.compiled' => $cachePath,
            'view.cache'=> $useCache
        ]));

        Facade::setFacadeApplication($this->container);
    }
}