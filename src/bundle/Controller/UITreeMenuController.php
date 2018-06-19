<?php

namespace Edgar\EzUITreeMenuBundle\Controller;

use Edgar\EzUITreeMenu\Data\Node;
use eZ\Publish\API\Repository\ContentTypeService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use eZ\Publish\Core\MVC\Symfony\Routing\UrlAliasRouter;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

class UITreeMenuController extends Controller
{
    /** @var LocationService  */
    protected $locationService;

    /** @var ContentTypeService  */
    protected $contentTypeService;

    /** @var UrlAliasRouter */
    private $urlRouter;

    /** @var RouterInterface  */
    private $router;

    /**
     * UITreeMenuController constructor.
     *
     * @param LocationService $locationService
     * @param ContentTypeService $contentTypeService
     * @param UrlAliasRouter  $urlRouter
     * @param RouterInterface $router
     */
    public function __construct(
        LocationService $locationService,
        ContentTypeService $contentTypeService,
        UrlAliasRouter $urlRouter,
        RouterInterface $router
    ) {
        $this->locationService = $locationService;
        $this->contentTypeService = $contentTypeService;
        $this->urlRouter = $urlRouter;
        $this->router = $router;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sidebarAction(Request $request): Response
    {
        return $this->render('@EdgarEzUITreeMenu/sidebar.html.twig', [
        ]);
    }

    /**
     * @param Location $location
     * @return Response
     * @throws InvalidArgumentException
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function initAction(Location $location): Response
    {
        $response = new JsonResponse();

        foreach ((array)$location->pathString as $pathString) {
            if (preg_match('/^(\/\w+)+\/$/', $pathString) !== 1) {
                throw new InvalidArgumentException(
                    "value '$location->pathString' must follow the pathString format, eg /1/2/"
                );
            }
        }

        $parentData = null;
        $pathString = array_reverse(explode('/', trim($location->pathString, '/')));
        foreach ($pathString as $key => $locationId) {
            if ($key == count($pathString) - 1) {
                continue;
            }

            $parentLocation = $this->locationService->loadLocation($locationId);
            $nodeData = new Node();
            $nodeData->id = $parentLocation->id;
            $nodeData->text = $parentLocation->contentInfo->name;
            $nodeData->type = $this->contentTypeService->loadContentType($parentLocation->contentInfo->contentTypeId)->identifier;
            $nodeData->icon = 'ct-icon ct-' . $nodeData->type;
            $nodeData->a_attr = [
                'href' => $this->urlRouter->generate(URLAliasRouter::URL_ALIAS_ROUTE_NAME, ['locationId' => $parentLocation->id]),
                'children' => $this->router->generate('edgar.uitreemenu.children', ['locationId' => $parentLocation->id]),
            ];
            $nodeData->state = ['opened' => true];

            if (!$parentData) {
                $nodeData->children = $this->findNodes($parentLocation);
            } else {
                $nodeData->children = $this->findNodes($parentLocation, $parentData);
            }

            $parentData = $nodeData;
        }

        $response->setData(
            [$parentData]
        );

        return $response;
    }

    /**
     * @param Location $location
     * @return Response
     */
    public function childrenAction(Location $location): Response
    {
        $response = new JsonResponse();

        $children = $this->findNodes($location);

        $response->setData($children);

        return $response;
    }

    /**
     * @param Location  $location
     * @param Node|null $node
     * @return array|null
     */
    protected function findNodes(Location $location, ?Node $node = null): ?array
    {
        $children = $this->locationService->loadLocationChildren($location);
        $nodes = [];
        foreach ($children->locations as $child) {
            if ($node && $child->id == $node->id) {
                $nodes[] = $node;
                continue;
            }

            $childNode = new Node();
            $childNode->id = $child->id;
            $childNode->text = $child->contentInfo->name;
            $childNode->type = $this->contentTypeService->loadContentType($child->contentInfo->contentTypeId)->identifier;
            $childNode->icon = 'ct-icon ct-' . $childNode->type;
            $childNode->a_attr = [
                'href' => $this->urlRouter->generate(URLAliasRouter::URL_ALIAS_ROUTE_NAME, ['locationId' => $child->id]),
                'children' => $this->router->generate('edgar.uitreemenu.children', ['locationId' => $child->id]),
            ];

            if ($this->locationService->getLocationChildCount($child)) {
                $nodesTemp = new Node();
                $nodesTemp->text = '...';
                $childNode->children = [$nodesTemp];
            }

            $nodes[] = $childNode;
        }

        if (empty($nodes)) {
            return null;
        }

        return $nodes;
    }
}
