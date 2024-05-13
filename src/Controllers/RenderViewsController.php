<?php

namespace Cortez\SymfonyHybridViews\Controllers;

use Cortez\SymfonyHybridViews\Services\SymfonyHybridViewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

abstract class RenderViewsController extends AbstractController
{

    public function view(string $view, $params = []):Response
    {
        $extension = pathinfo($view, PATHINFO_EXTENSION);
        if($extension === "twig" && $this->container->has("twig"))
        {
            return $this->render($view, $params);
        }
        
        if(!$this->container->has("symfony_hybrid_views"))
        {
            throw new \LogicException('You cannot use the "stream" method if the cortez97/symfony_hybrid_views is not available.');
        }

        $this->container->get("symfony_hybrid_views")->setServiceLocator($this->container);
        $result = $this->container->get("symfony_hybrid_views")->view($view, $params, $this->getAllContainerServices());
        $response = new Response();
        $response->setContent($result);
        return $response;
    }

    public static function getSubscribedServices(): array
    {
        $current_services = parent::getSubscribedServices();
        $symfonyHybridViewsServices = [
            "symfony_hybrid_views"=>'?'.SymfonyHybridViewsService::class
        ];
        $services = array_merge($current_services, $symfonyHybridViewsServices);
        return $services;
    }

    private function getAllContainerServices():array
    {
        $services = [];
        if($this->container->has("symfony_hybrid_views")){
            $services["symfony_hybrid_views"] = $this->container->get("symfony_hybrid_views");
        }
        
        if($this->container->has("router"))
        {
            $services["router"] = $this->container->get("router");
        }

        if($this->container->has("request_stack"))
        {
            $services["request_stack"] = $this->container->get("request_stack");
        }

        if($this->container->has("http_kernel"))
        {
            $services["http_kernel"] = $this->container->get("http_kernel");
        }

        if($this->container->has("serializer"))
        {
            $services["serializer"] = $this->container->get("serializer");
        }


        if($this->container->has("security.authorization_checker"))
        {
            $services["security.authorization_checker"] = $this->container->get("security.authorization_checker");
        }

        if($this->container->has("twig"))
        {
            $services["twig"] = $this->container->get("twig");
        }

        if($this->container->has("form.factory"))
        {
            $services["form.factory"] = $this->container->get("form.factory");
        }

        if($this->container->has("security.token_storage"))
        {
            $services["security.token_storage"] = $this->container->get("security.token_storage");
        }

        if($this->container->has("security.token_storage"))
        {
            $services["security.token_storage"] = $this->container->get("security.token_storage");
        }

        if($this->container->has("security.csrf.token_manager"))
        {
            $services["security.csrf.token_manager"] = $this->container->get("security.csrf.token_manager");
        }

        if($this->container->has("parameter_bag"))
        {
            $services["parameter_bag"] = $this->container->get("parameter_bag");
        }

        if($this->container->has('web_link.http_header_serializer'))
        {
            $services["web_link.http_header_serializer"] = $this->container->get("web_link.http_header_serializer");
        }


        
        return $services;
    }
}