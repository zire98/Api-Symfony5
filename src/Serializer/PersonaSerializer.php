<?php

namespace App\Serializer;

use App\Entity\Persona;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class PersonaSerializer implements ContextAwareNormalizerInterface
{

    private $normalizer;
    private $urlHelper;

    public function __construct(ObjectNormalizer $normalizer, UrlHelper $urlHelper)
    {
        $this->normalizer = $normalizer;
        $this->urlHelper = $urlHelper;
    }

    public function normalize($persona, $format = null, array $context = [])
    {
        $data = $this->normalizer->normalize($persona, $format, $context);

        if (!empty($persona->getImage())) {
            $data['image'] = $this->urlHelper->getAbsoluteUrl('/storage/default/' . $persona->getImage());
        }

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = [])
    {
        return $data instanceof Persona;
    }
}
