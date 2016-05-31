<?php

namespace Symfony\Cmf\Bundle\FileEditorBundle\Resource;

use Symfony\Cmf\Component\Resource\Description\DescriptionEnhancerInterface;
use Puli\Repository\Resource\FileResource;
use Symfony\Cmf\Component\Resource\Description\Description;
use Puli\Repository\Api\Resource\PuliResource;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Cmf\Component\Resource\Description\Descriptor;

class FileEditorEnhancer implements DescriptionEnhancerInterface
{
    private $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    public function enhance(Description $description)
    {
        $resource = $description->getResource();
        $description->set(Descriptor::LINK_EDIT_HTML, $this->generator->generate('cmf_file_editor', [ 
            'path' => $resource->getPath()
        ]));
    }

    public function supports(PuliResource $resource)
    {
        return $resource instanceof FileResource;
    }
}
