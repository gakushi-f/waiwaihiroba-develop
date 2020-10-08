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
use Customize\Form\Type\Front\ContactFarmerType;

class ContactFarmerController extends AbstractController
{
    /**
     * ContactFarmerController constructor.
     */
    public function __construct(
    ) {
    }

    /**
     * 生産者問合せ
     *
     * @Route("/mypage/contactfarmer/{order_no}/{order_item_id}", name="mypage_contact_farmer")
     * @Template("Mypage/contact_farmer.twig")
     */
    public function index(Request $request, $order_no, $order_item_id)
    {
        // 新規問い合わせフォーム
        $builder = $this->formFactory
            ->createBuilder(ContactFarmerType::class);

        $form = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            log_info('新規生産者問合せ開始');
            $this->addSuccess('mypage.contact.add.complete');
            log_info('新規生産者問合せ完了');
            return $this->redirect($this->generateUrl('mypage_contact_farmer'));
        }

        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * 生産者問合せ新規投稿
     *
     * @Route("/mypage/contactfarmer/contact_new", name="mypage_contact_new")
     * @Template("Mypage/contact_farmer.twig")
     */
    public function new(Request $request, $order_no, $order_item_id)
    {
        return [];
    }
}
