<?php

declare(strict_types=1);

namespace App\Presentation\Controller\Security;

use LogicException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/logout", name="security_logout")
 */
final class LogoutController
{
    public function __invoke(): void
    {
        throw new LogicException();
    }
}
