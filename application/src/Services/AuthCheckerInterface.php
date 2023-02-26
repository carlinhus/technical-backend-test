<?php
declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

interface AuthCheckerInterface
{
    /**
     * @param Request $request
     * @return bool
     *
     * Returns true if request is well-formed
     */
    public function __invoke(Request $request): bool;
}