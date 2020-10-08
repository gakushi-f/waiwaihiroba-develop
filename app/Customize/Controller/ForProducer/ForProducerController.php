<?php

namespace Customize\Controller\ForProducer;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Customize\Form\Type\Front\ForProducerRequestType;

class ForProducerController extends AbstractController
{
    /**
     * ForProducerController constructor.
     */
    public function __construct()
    {
    }

    /**
     * 出店者募集
     *
     * @Route("/for_producer", name="for_producer")
     * @Template("ForProducer/index.twig")
     */
    public function index(Request $request)
    {
        return [];
    }

    /**
     * 出店申込（入力ページ）
     *
     * @Route("/for_producer/form", name="for_producer_form")
     * @Template("ForProducer/for_producer_form.twig")
     */
    public function form(Request $request)
    {
        $builder = $this->formFactory->createBuilder(ForProducerRequestType::class);
        $form = $builder->getForm();
        $form->handleRequest($request);
        return [
            'form' => $form->createView(),
        ];
    }

    /**
     * 出店申込（受付完了）
     *
     * @Route("/for_producer/complete", name="for_producer_form_complete")
     * @Template("ForProducer/for_producer_complete.twig")
     */
    public function complete(Request $request)
    {
        return [];
    }
}
