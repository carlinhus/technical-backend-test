<?php
declare(strict_types=1);

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

final class AuthChecker implements AuthCheckerInterface
{
    /**
     * @param Request $request
     * @return bool
     */
    public function __invoke(Request $request): bool
    {
        //Returns false if Authorization header is not present.
        if (!$request->headers->get('Authorization')) {
            return false;
        }
        return $this->checkHeader($request->headers->get("Authorization"));
    }

    private function checkHeader(string $header): bool
    {

        $headerStrings = explode(" ", $header);
        if (count($headerStrings) < 2 && $headerStrings[0] !== "Bearer") {
            return false;
        } else if (count($headerStrings) < 2 && $headerStrings[0] === "Bearer") {
            return true;
        }
        $header = end($headerStrings);
        $openSymbols = ["(", "[", "{"];
        $closeSymbols = [
            ")" => "(",
            "}" => "{",
            "]" => "["
        ];
        $openedSymbols = []; //Will have opened content
        for ($i = 0; $i < strlen($header); $i++) {
            $character = substr($header, $i, 1); //Iteration of each character;
            $isOpenSymbol = in_array($character, $openSymbols); //Check if is open symbol
            $closeChar = $closeSymbols[$character] ?? false; //if is open symbol, it will have the close symbol pair
            if ($isOpenSymbol) {
                $openedSymbols[] = $character; //Adds current opened symbol to opened symbol array
            } else if ($closeChar) { //If is a closing char, its open pair will be removed from openedSymbols array
                $deleteSymbol = end($openedSymbols);
                //Deletes last character of opened symbols
                array_pop($openedSymbols);
                //If last opened symbol is not the same type of the closing symbol, it is invalid
                if ($closeChar !== $deleteSymbol) {
                    return false;
                }
            } else { //If is another type of symbol, it will not be accepted
                return false;
            }
        }
        if (count($openedSymbols) === 0) {
            return true;
        }

        return false;
    }
}