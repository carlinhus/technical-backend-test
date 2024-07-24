<?php

namespace App\Tests\Service;

use App\Services\AuthChecker;
use App\Services\AuthCheckerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class UtilTest extends TestCase
{
    /**
     * @return void
     *
     * Checks some authorizacion bearers scenerios required for the proof.
     */
    public function testAuth(): void
    {

        $authChecker = new AuthChecker();
        $request = new Request();
        $request->headers->add(["Authorization" => "Bearer (){}[]"]);
        $this->assertTrue($authChecker($request));

        $request->headers->add(["Authorization" => "Bearer (((({}[]))))"]);
        $this->assertTrue($authChecker($request));

        $request->headers->add(["Authorization" => "Bearer "]);
        $this->assertTrue($authChecker($request));

        $request->headers->add(["Authorization" => "Bearer (((([[}}}"]);
        $this->assertFalse($authChecker($request));

        $request->headers->add(["Authorization" => "Bearer ("]);
        $this->assertFalse($authChecker($request));
        
    }
}
