<?php

namespace Customize\Controller\Mypage;

use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Util\StringUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Customize\Form\Type\Front\CouponType;

class PointController extends AbstractController
{
    /**
     * PointController constructor.
     *
     */
    public function __construct(
    ) {
    }

    /**
     * ポイント情報
     *
     * @Route("/mypage/point", name="mypage_point")
     * @Template("Mypage/point.twig")
     */
    public function index(Request $request)
    {
        $builder = $this->formFactory
            ->createBuilder(CouponType::class);

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            log_info('クーポン登録開始');
            $this->addSuccess('mypage.point.add.complete');
            log_info('クーポン登録完了');
            return $this->redirect($this->generateUrl('mypage_point_complete'));
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * 完了画面.
     *
     * @Route("/mypage/point/complete", name="mypage_point_complete")
     * @Template("Mypage/point_complete.twig")
     */
    public function complete(Request $request)
    {
        return [
            'point' => 1000,
            'coupon' => "XXXXXXXXXXXXXX"
        ];
    }
}