<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\ShortedUrl;
use App\Exceptions\ThirdPartyUrlShortenerException;
use App\Exceptions\UrlNotValidException;
use App\Services\AuthCheckerInterface;
use App\Services\SaveUrlHandler;
use Doctrine\Persistence\ManagerRegistry;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ShortenerController extends AbstractFOSRestController
{
    /**
     * @param ManagerRegistry $manager
     * @return JsonResponse
     */
    #[Route('/api/v1/short-urls', name: 'app_shortener_list', methods: ['GET'])]
    public function list(ManagerRegistry $manager): JsonResponse
    {
        return $this->json($manager->getRepository(ShortedUrl::class)->findAll());
    }

    /**
     * @param Request $request
     * @param AuthCheckerInterface $authChecker
     * @param SaveUrlHandler $saveUrlHandler
     * @return JsonResponse
     */
    #[Route('/api/v1/short-urls', name: 'app_shortener_create', methods: ['POST'])]
    public function post(Request $request, AuthCheckerInterface $authChecker, SaveUrlHandler $saveUrlHandler): JsonResponse
    {
        if (!$authChecker($request)) {
            return $this->json([
                "status" => "error",
                "message" => "An error was found on Authorization header"
            ], 403);
        }

        try {
            return $saveUrlHandler($request);
        } catch (ThirdPartyUrlShortenerException|UrlNotValidException $e) {
            return $this->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }

    /**
     * @param string $id
     * @param ManagerRegistry $manager
     * @param Request $request
     * @param AuthCheckerInterface $authChecker
     * @param SaveUrlHandler $saveUrlHandler
     * @return Response
     */
    #[Route('/{id}', name: 'shorted_main', methods: ['GET'])]
    public function get(string $id, ManagerRegistry $manager, Request $request, AuthCheckerInterface $authChecker, SaveUrlHandler $saveUrlHandler): Response
    {
        $exixtentUrl = $manager->getRepository(ShortedUrl::class)->findOneBy(["id" => (int)$id]);
        if (!$exixtentUrl) {
            return $this->json(["status" => "error", "message" => "Url is not saved in our database."]);
        }
        return $this->redirect($exixtentUrl->getDestiny());
    }
}
