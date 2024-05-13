<?php

namespace Cortez\SymfonyHybridViews\Utils;

use Closure;
use Illuminate\Container\Container;
use Symfony\Component\DependencyInjection\Argument\ServiceLocator;
use Symfony\Component\Security\Core\User\UserInterface;

class BladeContainer extends Container
{
    protected array $terminatingCallbacks = [];
    private ServiceLocator $serviceLocator;

    public function terminating(Closure $callback)
    {
        $this->terminatingCallbacks[] = $callback;

        return $this;
    }

    public function setServiceLocator(ServiceLocator $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }


    /**
     * Get a user from the Security Token Storage.
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     */
    protected function getUser(): ?UserInterface
    {
        if($this->serviceLocator == null){
            throw new \LogicException('Symfony Container undefined.');
        }

        if (!$this->serviceLocator->has('security.token_storage')) {
            throw new \LogicException('The SecurityBundle is not registered in your application. Try running "composer require symfony/security-bundle".');
        }

        if (null === $token = $this->serviceLocator->get('security.token_storage')->getToken()) {
            return null;
        }

        return $token->getUser();
    }


    public function terminate()
    {
        foreach ($this->terminatingCallbacks as $terminatingCallback) {
            $terminatingCallback();
        }
    }
}