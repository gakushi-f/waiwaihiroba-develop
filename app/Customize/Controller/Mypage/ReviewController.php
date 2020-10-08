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
use Customize\Form\Type\Front\ReviewType;

class ReviewController extends AbstractController
{
    /**
     * ReviewController constructor.
     */
    public function __construct(
    ) {
    }

    /**
     * 商品レビュー
     *
     * @Route("/mypage/review/new", name="mypage_review_new")
     * @Route("/mypage/review/{id}/edit", requirements={"id" = "\d+"}, name="mypage_review_new_edit")
     * @Template("Mypage/review_edit.twig")
     */
    public function index(Request $request, $id = null)
    {
        $builder = $this->formFactory
            ->createBuilder(ReviewType::class);

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            log_info('レビュー登録開始');
            $this->addSuccess('mypage.review.add.complete');
            log_info('レビュー登録完了');
            return $this->redirect($this->generateUrl('mypage_review_complete'));
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * 完了画面.
     *
     * @Route("/mypage/review/complete", name="mypage_review_complete")
     * @Template("Mypage/review_complete.twig")
     */
    public function complete(Request $request)
    {
        return [];
    }
}
