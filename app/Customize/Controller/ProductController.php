<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Customize\Controller;

use DateTime;
use Eccube\Entity\BaseInfo;
use Eccube\Entity\Master\ProductStatus;
use Eccube\Entity\Product;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\AddCartType;
use Eccube\Form\Type\Master\ProductListMaxType;
use Eccube\Form\Type\Master\ProductListOrderByType;
use Eccube\Form\Type\SearchProductType;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\CustomerFavoriteProductRepository;
use Eccube\Repository\Master\ProductListMaxRepository;
use Eccube\Repository\ProductRepository;
use Eccube\Service\CartService;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Eccube\Controller\AbstractController;
use Customize\Form\Type\Front\ProductDetailSearchType;

class ProductController extends AbstractController
{
    /**
     * @var PurchaseFlow
     */
    protected $purchaseFlow;

    /**
     * @var CustomerFavoriteProductRepository
     */
    protected $customerFavoriteProductRepository;

    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var BaseInfo
     */
    protected $BaseInfo;

    /**
     * @var AuthenticationUtils
     */
    protected $helper;

    /**
     * @var ProductListMaxRepository
     */
    protected $productListMaxRepository;

    private $title = '';

    /**
     * ProductController constructor.
     *
     * @param PurchaseFlow $cartPurchaseFlow
     * @param CustomerFavoriteProductRepository $customerFavoriteProductRepository
     * @param CartService $cartService
     * @param ProductRepository $productRepository
     * @param BaseInfoRepository $baseInfoRepository
     * @param AuthenticationUtils $helper
     * @param ProductListMaxRepository $productListMaxRepository
     */
    public function __construct(
        PurchaseFlow $cartPurchaseFlow,
        CustomerFavoriteProductRepository $customerFavoriteProductRepository,
        CartService $cartService,
        ProductRepository $productRepository,
        BaseInfoRepository $baseInfoRepository,
        AuthenticationUtils $helper,
        ProductListMaxRepository $productListMaxRepository
    ) {
        $this->purchaseFlow = $cartPurchaseFlow;
        $this->customerFavoriteProductRepository = $customerFavoriteProductRepository;
        $this->cartService = $cartService;
        $this->productRepository = $productRepository;
        $this->BaseInfo = $baseInfoRepository->get();
        $this->helper = $helper;
        $this->productListMaxRepository = $productListMaxRepository;
    }

    /**
     * 商品詳細画面.
     *
     * @Route("/products/detail/{id}", name="product_detail", methods={"GET"}, requirements={"id" = "\d+"})
     * @Template("Product/detail.twig")
     * @ParamConverter("Product", options={"repository_method" = "findWithSortedClassCategories"})
     *
     * @param Request $request
     * @param Product $Product
     *
     * @return array
     */
    public function detail(Request $request, Product $Product, Paginator $paginator)
    {
        if (!$this->checkVisibility($Product)) {
            throw new NotFoundHttpException();
        }

        $builder = $this->formFactory->createNamedBuilder(
            '',
            AddCartType::class,
            null,
            [
                'product' => $Product,
                'id_add_product_id' => false,
            ]
        );

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Product' => $Product,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::FRONT_PRODUCT_DETAIL_INITIALIZE, $event);

        $is_favorite = false;
        if ($this->isGranted('ROLE_USER')) {
            $Customer = $this->getUser();
            $is_favorite = $this->customerFavoriteProductRepository->isFavorite($Customer, $Product);
        }

        $searchFormBuilder = $this->formFactory
            ->createNamedBuilder('', ProductDetailSearchType::class);

        // ダミー
        $searchForm = $searchFormBuilder->getForm();
        $searchForm->handleRequest($request);
        $searchData = [];
        $qb = $this->productRepository->getQueryBuilderBySearchDataForAdmin($searchData);
        $pagination = $paginator->paginate(
            $qb,
            !empty($searchData['pageno']) ? $searchData['pageno'] : 1,
            8
        );
        $qbBuyT = $this->productRepository->getQueryBuilderBySearchData($searchData);
        $buyTogether = $qbBuyT->getQuery()->getResult();

        $link = $this->generateUrl('Regionalcharacteristics', [], UrlGeneratorInterface::ABSOLUTE_URL);
        return [
            'title' => $this->title,
            'subtitle' => $Product->getName(),
            'form' => $builder->getForm()->createView(),
            'Product' => $Product,
            'is_favorite' => $is_favorite,
            // ↓Dummy
            'productInfo' => [
                'content' => '季節のおやさいを7種類をセット
                #きゆうり #トマト #ズッキーニ #にんじん',
                'locale' => '〇〇地域',
                'deliveryDay' => '7日以内に発送します。',
                'deliveryType' => 'クール（冷蔵）',
                'score' => [
                    'point' => 3,
                    'image' =>  [
                        'http://www.material-land.com/material/1305.png',
                        'http://www.material-land.com/material/1305.png',
                        'http://www.material-land.com/material/1305.png',
                        'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                        'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                    ],
                ],
                'messageCount' => 32,
            ],
            'aboutProduct' => '＜商品詳細＞
            商品詳細
            　きゅうり：3〜5本
            　ずっきーに　：　2〜5本
            　トマト　：　4〜8個
            　・・・・・・・

            ＜商品特徴＞
            栽培期間中、農薬・化学肥料を一切使わずに富士山麓のおいしい水に愛情をたっぷり込めて栽培した季節のおやさいを7種類をセットにしてお届けします。※ 期間限定で入るヤングコーン及びとうもろこしのみ今シーズン、化学肥料を使用しての試験的栽培をしています。(農薬不使用)この旨ご了承いただきご購入いただけますようお願い致します。その他のお野菜につきましては栽培期間中、農薬・化学肥料不使用です。
            ＜今畑で収穫できるやさいたち＞ズッキーニ、じゃがいも、キャベツ、なす、ミニにんじん、空芯菜、きゅうり、ヤングコーン(農薬不使用・化学肥料使用)
            ＜おすすめの食べ方＞
            意外かもしれませんが、ズッキーニは、水でさっと洗って、塩をかけて生でかじりつくと、とても美味しいです。
            ＜お客様へのお願い＞※ご注文は、お届け希望日の2日前までにご連絡頂けますと大変ありがたいです。どうぞよろしくお願い致します。※農薬・化学肥料不使用栽培のため、野菜によっては一部虫食いなどがある場合がございます。予めご了承の上、ご注文ください。写真の内容は、野菜の収穫状況により異なる場合がございます。',
            'producer' => [
                'name' => '〇〇さん（生産者名）',
                'image' => 'https://unsplash.it/1051/1051',
                'area' => '〇〇エリア',
                'message' => '私たちは、長野県〇〇市〇〇町で、主にお米を作っています。
                　〇〇の雪解け水を使ってつくるお米は、炊き上がりに程よい粘り気があり、
                　とても甘いのが特徴です。
                　夫婦2人と娘と愛情込めて作っています。
                　よろしくお願いします。
                ',
            ],
            'area' => [
                'name' => '〇〇地域',
                "link" => $link,
                'image' => 'http://www.spacefan.net/freegallery/phdata/sc/sc-003-1.jpg',
                'message' => '積雪量が多いため、11月〜2月は農作は行わないが、そのほかの季節は行う。
                〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇。
                〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇、〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇。
                〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇。
                ',
            ],
            'buyTogether' => $buyTogether,
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'reviewCount' => 100,
            'reviews' => [
                [
                    'isMyPosted' => true,
                    'score' => [
                        'point' => 3,
                        'image' =>  [
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                        ],
                    ],
                    'datetime' => new DateTime(),
                    'userName' => "きんきん",
                    'userMessage' => '無事に届きました
                    味はとても美味しかったのですががこのようなものが封を開けたら混じっていたのが残念でした。。。
                    ',
                    'images' => [
                        'https://unsplash.it/1051/1051',
                        'https://unsplash.it/1051/1052',
                        'https://unsplash.it/1051/1053',
                        'https://unsplash.it/1051/1054'
                    ],
                    'producerName' => '〇〇生産者',
                    'producerImage' => 'https://unsplash.it/1051/1055',
                    'producerMessage' => 'ありがとうございまいます。引っ越し丼、
                    いいですね！パワーが付きそうで(^^♪
                    またどうぞご利用ください。
                    よろしく願いします！!'
                ],
                [
                    'isMyPosted' => false,
                    'score' => [
                        'point' => 1,
                        'image' =>  [
                            'http://www.material-land.com/material/1305.png',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                        ],
                    ],
                    'datetime' => new DateTime(),
                    'userName' => "かんかん",
                    'userMessage' => '無事に届きました
                    味はとても美味しかったのですががこのようなものが封を開けたら混じっていたのが残念でした。。。
                    ',
                    'producerName' => '〇〇生産者',
                    'producerImage' => 'https://unsplash.it/1051/1055',
                    'producerMessage' => 'ありがとうございまいます。引っ越し丼、
                    いいですね！パワーが付きそうで(^^♪
                    またどうぞご利用ください。
                    よろしく願いします！!'
                ],
                [
                    'isMyPosted' => false,
                    'score' => [
                        'point' => 4,
                        'image' =>  [
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                        ],
                    ],
                    'datetime' => new DateTime(),
                    'userName' => "みんみん",
                    'userMessage' => '無事に届きました
                    味はとても美味しかったのですががこのようなものが封を開けたら混じっていたのが残念でした。。。
                    ',
                    'images' => [
                        'https://unsplash.it/1051/1051',
                    ],
                    'producerName' => '〇〇生産者',
                    'producerImage' => 'https://unsplash.it/1051/1055',
                    'producerMessage' => 'ありがとうございまいます。引っ越し丼、
                    いいですね！パワーが付きそうで(^^♪
                    またどうぞご利用ください。
                    よろしく願いします！!'
                ],
            ]
        ];
    }

    /**
     * ページタイトルの設定
     *
     * @param  null|array $searchData
     *
     * @return str
     */
    protected function getPageTitle($searchData)
    {
        if (isset($searchData['name']) && !empty($searchData['name'])) {
            return trans('front.product.search_result');
        } elseif (isset($searchData['category_id']) && $searchData['category_id']) {
            return $searchData['category_id']->getName();
        } else {
            return trans('front.product.all_products');
        }
    }

    /**
     * 閲覧可能な商品かどうかを判定
     *
     * @param Product $Product
     *
     * @return boolean 閲覧可能な場合はtrue
     */
    protected function checkVisibility(Product $Product)
    {
        $is_admin = $this->session->has('_security_admin');

        // 管理ユーザの場合はステータスやオプションにかかわらず閲覧可能.
        if (!$is_admin) {
            // 在庫なし商品の非表示オプションが有効な場合.
            // if ($this->BaseInfo->isOptionNostockHidden()) {
            //     if (!$Product->getStockFind()) {
            //         return false;
            //     }
            // }
            // 公開ステータスでない商品は表示しない.
            if ($Product->getStatus()->getId() !== ProductStatus::DISPLAY_SHOW) {
                return false;
            }
        }

        return true;
    }

    /**
     * 応援メッセージ画面.
     *
     * @Route("/products/review/{id}", name="product_review", methods={"GET"}, requirements={"id" = "\d+"})
     * @Template("Product/review.twig")
     * @ParamConverter("Product", options={"repository_method" = "findWithSortedClassCategories"})
     *
     * @param Request $request
     * @param Product $Product
     *
     * @return array
     */
    public function review(Request $request, Product $Product, Paginator $paginator)
    {
        if (!$this->checkVisibility($Product)) {
            throw new NotFoundHttpException();
        }

        $builder = $this->formFactory->createNamedBuilder(
            '',
            AddCartType::class,
            null,
            [
                'product' => $Product,
                'id_add_product_id' => false,
            ]
        );

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Product' => $Product,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::FRONT_PRODUCT_DETAIL_INITIALIZE, $event);

        $is_favorite = false;
        if ($this->isGranted('ROLE_USER')) {
            $Customer = $this->getUser();
            $is_favorite = $this->customerFavoriteProductRepository->isFavorite($Customer, $Product);
        }

        $link = $this->generateUrl('Regionalcharacteristics', [], UrlGeneratorInterface::ABSOLUTE_URL);
        return [
            'title' => $this->title,
            'subtitle' => $Product->getName(),
            'form' => $builder->getForm()->createView(),
            'Product' => $Product,
            'is_favorite' => $is_favorite,
            // ↓Dummy
            'productInfo' => [
                'content' => '季節のおやさいを7種類をセット
                #きゆうり #トマト #ズッキーニ #にんじん',
                'locale' => '〇〇地域',
                'deliveryDay' => '7日以内に発送します。',
                'deliveryType' => 'クール（冷蔵）',
                'score' => [
                    'point' => 3,
                    'image' =>  [
                        'http://www.material-land.com/material/1305.png',
                        'http://www.material-land.com/material/1305.png',
                        'http://www.material-land.com/material/1305.png',
                        'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                        'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                    ],
                ],
                'messageCount' => 32,
            ],
            'aboutProduct' => '＜商品詳細＞
            商品詳細
            　きゅうり：3〜5本
            　ずっきーに　：　2〜5本
            　トマト　：　4〜8個
            　・・・・・・・

            ＜商品特徴＞
            栽培期間中、農薬・化学肥料を一切使わずに富士山麓のおいしい水に愛情をたっぷり込めて栽培した季節のおやさいを7種類をセットにしてお届けします。※ 期間限定で入るヤングコーン及びとうもろこしのみ今シーズン、化学肥料を使用しての試験的栽培をしています。(農薬不使用)この旨ご了承いただきご購入いただけますようお願い致します。その他のお野菜につきましては栽培期間中、農薬・化学肥料不使用です。
            ＜今畑で収穫できるやさいたち＞ズッキーニ、じゃがいも、キャベツ、なす、ミニにんじん、空芯菜、きゅうり、ヤングコーン(農薬不使用・化学肥料使用)
            ＜おすすめの食べ方＞
            意外かもしれませんが、ズッキーニは、水でさっと洗って、塩をかけて生でかじりつくと、とても美味しいです。
            ＜お客様へのお願い＞※ご注文は、お届け希望日の2日前までにご連絡頂けますと大変ありがたいです。どうぞよろしくお願い致します。※農薬・化学肥料不使用栽培のため、野菜によっては一部虫食いなどがある場合がございます。予めご了承の上、ご注文ください。写真の内容は、野菜の収穫状況により異なる場合がございます。',
            'producer' => [
                'name' => '〇〇さん（生産者名）',
                'image' => 'https://unsplash.it/1051/1051',
                'area' => '〇〇エリア',
                'message' => '私たちは、長野県〇〇市〇〇町で、主にお米を作っています。
                　〇〇の雪解け水を使ってつくるお米は、炊き上がりに程よい粘り気があり、
                　とても甘いのが特徴です。
                　夫婦2人と娘と愛情込めて作っています。
                　よろしくお願いします。
                ',
            ],
            'area' => [
                'name' => '〇〇地域',
                "link" => $link,
                'image' => 'http://www.spacefan.net/freegallery/phdata/sc/sc-003-1.jpg',
                'message' => '積雪量が多いため、11月〜2月は農作は行わないが、そのほかの季節は行う。
                〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇。
                〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇、〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇。
                〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇〇。
                ',
            ],
            'reviewSummary' => [
                'score' => [
                    'point' => 3,
                    'image' =>  [
                        'http://www.material-land.com/material/1305.png',
                        'http://www.material-land.com/material/1305.png',
                        'http://www.material-land.com/material/1305.png',
                        'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                        'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                    ],
                ],
                'Star5' => 65,
                'Star4' => 30,
                'Star3' => 0,
                'Star2' => 10,
                'Star1' => 5,
            ],
            'reviewCount' => 100,
            'reviews' => [
                [
                    'isMyPosted' => true,
                    'score' => [
                        'point' => 3,
                        'image' =>  [
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                        ],
                    ],
                    'datetime' => new DateTime(),
                    'userName' => "きんきん",
                    'userMessage' => '無事に届きました
                    味はとても美味しかったのですががこのようなものが封を開けたら混じっていたのが残念でした。。。
                    ',
                    'images' => [
                        'https://unsplash.it/1051/1051',
                        'https://unsplash.it/1051/1052',
                        'https://unsplash.it/1051/1053',
                        'https://unsplash.it/1051/1054'
                    ],
                    'producerName' => '〇〇生産者',
                    'producerImage' => 'https://unsplash.it/1051/1055',
                    'producerMessage' => 'ありがとうございまいます。引っ越し丼、
                    いいですね！パワーが付きそうで(^^♪
                    またどうぞご利用ください。
                    よろしく願いします！!'
                ],
                [
                    'isMyPosted' => false,
                    'score' => [
                        'point' => 1,
                        'image' =>  [
                            'http://www.material-land.com/material/1305.png',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                        ],
                    ],
                    'datetime' => new DateTime(),
                    'userName' => "かんかん",
                    'userMessage' => '無事に届きました
                    味はとても美味しかったのですががこのようなものが封を開けたら混じっていたのが残念でした。。。
                    ',
                    'producerName' => '〇〇生産者',
                    'producerImage' => 'https://unsplash.it/1051/1055',
                    'producerMessage' => 'ありがとうございまいます。引っ越し丼、
                    いいですね！パワーが付きそうで(^^♪
                    またどうぞご利用ください。
                    よろしく願いします！!'
                ],
                [
                    'isMyPosted' => false,
                    'score' => [
                        'point' => 4,
                        'image' =>  [
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'https://iconbox.fun/wp/wp-content/uploads/697_xm_h.svg',
                        ],
                    ],
                    'datetime' => new DateTime(),
                    'userName' => "みんみん",
                    'userMessage' => '無事に届きました
                    味はとても美味しかったのですががこのようなものが封を開けたら混じっていたのが残念でした。。。
                    ',
                    'images' => [
                        'https://unsplash.it/1051/1051',
                    ],
                    'producerName' => '〇〇生産者',
                    'producerImage' => 'https://unsplash.it/1051/1055',
                    'producerMessage' => 'ありがとうございまいます。引っ越し丼、
                    いいですね！パワーが付きそうで(^^♪
                    またどうぞご利用ください。
                    よろしく願いします！!'
                ],
            ]
        ];
    }
}
