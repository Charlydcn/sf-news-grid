<?php
namespace App\EventSubscriber;

use App\Form\NewsletterType;
use Twig\Environment as TwigEnvironment;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewsletterFormSubscriber implements EventSubscriberInterface
{
    private FormFactoryInterface $formFactory;
    private TwigEnvironment $twig;

    public function __construct(FormFactoryInterface $formFactory, TwigEnvironment $twig)
    {
        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $form = $this->formFactory->create(NewsletterType::class);
        $this->twig->addGlobal('newsletterForm', $form->createView());
    }
}
