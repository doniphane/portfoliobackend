<?php

namespace App\Serializer;

use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class MultipartDecoder implements DecoderInterface
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function decode(string $data, string $format, array $context = []): array
    {
        $request = $this->requestStack->getCurrentRequest();

        if (!$request) {
            return [];
        }

        $data = [];

        // Récupérer les données du formulaire
        foreach ($request->request->all() as $key => $value) {
            $data[$key] = $value;
        }

        // Gérer les fichiers
        foreach ($request->files->all() as $key => $file) {
            if ($file) {
                $data[$key] = $file;
            }
        }

        // Gérer les tableaux (comme technologie[])
        foreach ($request->request->all() as $key => $value) {
            if (str_ends_with($key, '[]')) {
                $baseKey = str_replace('[]', '', $key);
                if (!isset($data[$baseKey])) {
                    $data[$baseKey] = [];
                }
                $data[$baseKey][] = $value;
                unset($data[$key]);
            }
        }

        return $data;
    }

    public function supportsDecoding(string $format): bool
    {
        return $format === 'multipart';
    }
}