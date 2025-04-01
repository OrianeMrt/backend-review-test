<?php

namespace App\Serializer;

use App\Dto\FullEventIntput;
use Symfony\Component\DependencyInjection\Attribute\AsTaggedItem;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

#[AsTaggedItem(priority: 500)]
#[AutoconfigureTag('serializer.normalizer')]
class GithubEventNormalizer implements DenormalizerInterface
{
    public function __construct(
        private ObjectNormalizer $normalizer,
    ) {
    }

    public function denormalize($data, $type, $format = null, array $context = []): FullEventIntput
    {
        $comment = $data['payload']['comment']['body'] ?? null;

        /** @var FullEventIntput $object */
        $object = $this->normalizer->denormalize($data, $type, $format, $context);

        if (null !== $comment) {
            $object->comment = $comment;
        }

        return $object;
    }

    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return FullEventIntput::class === $type;
    }
}
