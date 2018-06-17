<?php

namespace Edgar\EzUITreeMenuBundle\Controller;

use Edgar\EzUITreeMenu\Data\Node;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\Location;
use eZ\Publish\Core\Base\Exceptions\InvalidArgumentException;
use eZ\Publish\Core\MVC\Symfony\Routing\UrlAliasRouter;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UITreeMenuController extends Controller
{
    /** @var LocationService  */
    protected $locationService;

    /** @var UrlAliasRouter */
    private $router;

    public function __construct(
        LocationService $locationService,
        UrlAliasRouter $router
    ) {
        $this->locationService = $locationService;
        $this->router = $router;
    }

    public function sidebarAction(Request $request): Response
    {
        return $this->render('@EdgarEzUITreeMenu/sidebar.html.twig', [
        ]);
    }

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
            $ps = trim($parentLocation->pathString, '/');
            $nodeData = new Node();
            $nodeData->locationId = $parentLocation->id;
            $nodeData->text = $parentLocation->contentInfo->name;
            $nodeData->href = $this->router->generate(URLAliasRouter::URL_ALIAS_ROUTE_NAME, ['locationId' => $parentLocation->id]);
            $nodeData->pathString = $ps;
            $nodeData->action = $this->generateUrl('edgar.uitreemenu.init', ['locationId' => $parentLocation->id]);
            if ($count = $this->locationService->getLocationChildCount($parentLocation)) {
                $nodeData->tags = [$count];
            }

            if (!$parentData) {
                $nodeData->nodes = $this->findNodes($parentLocation);
            } else {
                $nodeData->nodes = $this->findNodes($parentLocation, $parentData);
            }

            $parentData = $nodeData;
        }

        $response->setData(
            [$parentData]
        );

        return $response;
    }

    protected function findNodes(Location $location, ?Node $node = null): ?array
    {
        $children = $this->locationService->loadLocationChildren($location);
        $nodes = [];
        foreach ($children->locations as $child) {
            if ($node && $child->id == $node->locationId) {
                $nodes[] = $node;
                continue;
            }

            $ps = trim($child->pathString, '/');

            $childNode = new Node();
            $childNode->locationId = $child->id;
            $childNode->text = $child->contentInfo->name;
            $childNode->href = $this->router->generate(URLAliasRouter::URL_ALIAS_ROUTE_NAME, ['locationId' => $child->id]);
            $childNode->pathString = $ps;
            $childNode->action = $this->generateUrl('edgar.uitreemenu.init', ['locationId' => $child->id]);
            if ($count = $this->locationService->getLocationChildCount($child)) {
                $childNode->tags = [$count];
            }

            if ($this->locationService->getLocationChildCount($child)) {
                $nodesTemp = new Node();
                $nodesTemp->text = '...';
                $nodesTemp->locationId = -1;
                $childNode->nodes = [$nodesTemp];
            }

            $nodes[] = $childNode;
        }

        if (empty($nodes)) {
            return null;
        }

        return $nodes;
    }

}
