<?php

namespace App\Naming;

use Vich\UploaderBundle\Util\Transliterator;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\ConfigurableInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Naming\NamerInterface;

/**
 * OrignameNamer.
 *
 * @author Ivan Borzenkov <ivan.borzenkov@gmail.com>
 */
class MyNamer implements NamerInterface, ConfigurableInterface
{
    /**
     * @var bool
     */
    private $transliterate = false;

    /**
     * @var Transliterator
     */
    private $transliterator;

    public function __construct(Transliterator $transliterator)
    {
        $this->transliterator = $transliterator;
    }

    /**
     * @param array $options Options for this namer. The following options are accepted:
     *                       - transliterate: whether the filename should be transliterated or not
     */
    public function configure(array $options): void
    {
        $this->transliterate = isset($options['transliterate']) ? (bool) $options['transliterate'] : $this->transliterate;
    }

    /**
     * {@inheritdoc}
     */
    public function name($object, PropertyMapping $mapping): string
    {
        /* @var $file UploadedFile */
        $file = $mapping->getFile($object);
        $name = $file->getClientOriginalName();

        if ($this->transliterate) {
            $name = $this->transliterator->transliterate($name);
        }

        return $name.'_'.uniqid();
    }
}
