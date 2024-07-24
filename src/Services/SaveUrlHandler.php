<?php
declare(strict_types=1);

namespace App\Services;

use App\Entity\ShortedUrl;
use App\Exceptions\ThirdPartyUrlShortenerException;
use App\Exceptions\UrlNotValidException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class SaveUrlHandler implements SaveUrlHandlerInterface
{
    private const URL_REGEX = "/^https?:\\/\\/(?:www\\.)?[-a-zA-Z0-9@:%._\\+~#=]{1,256}\\.[a-zA-Z0-9()]{1,6}\\b(?:[-a-zA-Z0-9()@:%_\\+.~#?&\\/=]*)$/";

    /**
     * @param ManagerRegistry $registry
     * @param ThirdPartyUrlShortenerInterface $thirdPartyUrlShortener
     */
    public function __construct(private ManagerRegistry $registry, private ThirdPartyUrlShortenerInterface $thirdPartyUrlShortener)
    {
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ThirdPartyUrlShortenerException
     * @throws UrlNotValidException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $bodyContent = json_decode($request->getContent(), true); //Request content parse
        //Check if is url data in content
        if (!$bodyContent || !isset($bodyContent['url'])) {
            return new JsonResponse([
                "status" => "error",
                "message" => "Url index was not found in request body."
            ]);
        }

        //Check if is well-formed url
        if (!preg_match(self::URL_REGEX, $bodyContent['url'])) {
            throw new UrlNotValidException("Url is invalid");
        }

        $em = $this->registry->getManager();
        $existentUrl = $em->getRepository(ShortedUrl::class)->findOneBy(["originUrl" => $bodyContent['url']]);
        //If url is currently saved, it returns the saved link
        if ($existentUrl) {
            return new JsonResponse(json_encode(['url' => $request->getScheme() . "://" . $request->getHost() . "/" . $request->server->get('host') . $existentUrl->getId()], JSON_UNESCAPED_SLASHES), 200, [], true);
        }
        $newUrl = new ShortedUrl();
        //Creating and returnong the new link
        try {
            $newUrl->setOriginUrl($bodyContent['url']);
            $newUrl->setDestiny($this->thirdPartyUrlShortener->__invoke($bodyContent['url']));
            $em->persist($newUrl);
            $em->flush();
            $em->refresh(($newUrl));
            return new JsonResponse(json_encode(['url' => $request->getScheme() . "://" . $request->getHost() . "/" . $request->server->get('host') . $newUrl->getId()], JSON_UNESCAPED_SLASHES), 200, [], true);

        } catch (GuzzleException $e) {
            throw new ThirdPartyUrlShortenerException($e->getMessage());
        }

    }
}