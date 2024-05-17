<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig\Environment;

class AccessDeniedHandler implements \Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface
{
    public function __construct(private Environment $twig)
    {
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        return new Response(
            $this->twig->render(
                'bundles/TwigBundle/Exception/error403.html.twig',
                ['title' => 'Access.denied']
            ),
            403
        );
    }
}
