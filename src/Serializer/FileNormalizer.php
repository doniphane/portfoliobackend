<?php

namespace App\Serializer;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FileNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function normalize(mixed $object, string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        if ($object instanceof File) {
            return $object->getPathname();
        }

        return null;
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof File;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): mixed
    {
        if ($data instanceof File) {
            return $data;
        }

        return null;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return $data instanceof File && $type === File::class;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            File::class => true,
        ];
    }
}