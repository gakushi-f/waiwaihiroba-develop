<?php

namespace Customize\Controller\Regionalcharacteristics;

use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Util\StringUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Customize\Form\Type\Front\ReviewType;

class RegionalcharacteristicsController extends AbstractController
{
    /**
     * RegionalcharacteristicsController constructor.
     */
    public function __construct(
    ) {
    }

    /**
     * 地域別特色
     *
     * @Route("/Regionalcharacteristics", name="Regionalcharacteristics")
     * @Template("Regionalcharacteristics/index.twig")
     */
    public function index(Request $request)
    {
        return [];
    }
}
