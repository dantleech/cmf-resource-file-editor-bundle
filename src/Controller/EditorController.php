<?php

namespace Symfony\Cmf\Bundle\FileEditorBundle\Controller;

use Puli\Repository\Api\ResourceRepository;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Cmf\Bundle\ResourceBundle\Registry\ContainerRepositoryRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\ColumnBrowserBundle\Column\ColumnBuilder;
use Symfony\Cmf\Component\Resource\RepositoryRegistryInterface;
use Puli\Repository\Api\EditableRepository;
use Puli\Repository\Api\Resource\BodyResource;
use Symfony\Component\HttpFoundation\RedirectResponse;

class EditorController
{
    private $templating;
    private $registry;

    public function __construct(
        RepositoryRegistryInterface $registry,
        EngineInterface $templating
    )
    {
        $this->templating = $templating;
        $this->registry = $registry;
    }

    public function editorAction(Request $request)
    {
        $repositoryName = $request->get('repository', null);
        $repository = $this->registry->get($repositoryName);
        $path = $request->query->get('path', null);
        $template = $request->get('template', 'CmfFileEditorBundle::index.html.twig');

        $resource = null;
        if ($repository->contains($path)) {
            $resource = $repository->get($path);
        }

        if (!$resource) {
            throw new \InvalidArgumentException(sprintf(
                'Resource at "%s" does not exist',
                $path
            ));
        }

        if (!$resource instanceof BodyResource) {
            throw new \InvalidArgumentException(sprintf(
                'Resource "%s" is not an instance of BodyResource',
                get_class($resource)
            ));
        }

        $editable = $repository instanceof EditableRepository;

        if ($editable && $request->getMethod() === 'POST') {
            file_put_contents($resource->getFilesystemPath(), $request->request->get('body'));
            return new RedirectResponse('#');
        }


        return $this->templating->renderResponse(
            $template,
            [
                'editable' => $editable,
                'resource' => $resource,
            ],
            new Response()
        );
    }
}
