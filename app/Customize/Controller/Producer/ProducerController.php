<?php

namespace Customize\Controller\Producer;

use DateTime;
use Knp\Component\Pager\Paginator;
use Eccube\Repository\ProductRepository;
use Eccube\Controller\AbstractController;
use Eccube\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Customize\Form\Type\Front\ProductDetailSearchType;
use Eccube\Repository\Master\ProductListMaxRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProducerController extends AbstractController
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ProductListMaxRepository
     */
    protected $productListMaxRepository;

    /**
     * ProductController constructor.
     *
     * @param ProductRepository $productRepository
     * @param CategoryRepository $categoryRepository
     * @param ProductListMaxRepository $productListMaxRepository
     */
    public function __construct(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository,
        ProductListMaxRepository $productListMaxRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productListMaxRepository = $productListMaxRepository;
    }

    /**
     * 生産者詳細
     *
     * @Route("/producer", name="producer")
     * @Template("Producer/index.twig")
     */
    public function index(Request $request, Paginator $paginator)
    {
        $builder = $this->formFactory
            ->createNamedBuilder('', ProductDetailSearchType::class);

        // ダミー
        $searchForm = $builder->getForm();
        $searchForm->handleRequest($request);
        $searchData = [];
        $qb = $this->productRepository->getQueryBuilderBySearchDataForAdmin($searchData);
        $pagination = $paginator->paginate(
            $qb,
            !empty($searchData['pageno']) ? $searchData['pageno'] : 1,
            8
        );

        $link = $this->generateUrl('Regionalcharacteristics', [], UrlGeneratorInterface::ABSOLUTE_URL);
        return [
            'name' => '〇〇さん（生産者名）',
            'area' => [
                'name' => '北信濃エリア',
                "link" => $link,
                'image' => 'http://www.spacefan.net/freegallery/phdata/sc/sc-003-1.jpg',
            ],
            'message' => '私たちは、長野県〇〇市〇〇町で、主にお米を作っています。
            　〇〇の雪解け水を使ってつくるお米は、炊き上がりに程よい粘り気があり、
            　とても甘いのが特徴です。
            　夫婦2人と娘と愛情込めて作っています。
            　よろしくお願いします。
            ',
            'images' => [
                [
                    'id' => '0',
                    'type' => 'image',
                    'path' => '/html/template/default/assets/img/top/img_hero_pc01.jpg'
                ],
                [
                    'id' => '1',
                    'type' => 'image',
                    'path' => '/html/template/default/assets/img/top/img_hero_pc02.jpg'
                ],
                [
                    'id' => '2',
                    'type' => 'image',
                    'path' => '/html/template/default/assets/img/top/img_hero_pc03.jpg'
                ],
                [
                    'id' => '3',
                    'type' => 'movie',
                    'path' => 'https://img.youtube.com/vi/rnj6cnlIjM4/sddefault.jpg',
                    'moviepath' => 'https://www.youtube.com/embed/rnj6cnlIjM4',
                ],
            ],
            'kodawarilist' => [
                [
                    'image' => '/html/template/default/assets/img/top/img_hero_pc01.jpg',
                    'title' => '作り方・農法へのこだわり',
                    'detail' => '〇〇という農法での生産にこだわっています。〇〇農法とは、、、、、、
                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・',
                ],
                [
                    'image' => '/html/template/default/assets/img/top/img_hero_pc02.jpg',
                    'title' => '信州の〇〇地域だからこそ、出せる味',
                    'detail' => '〇〇地域は、〇〇といった特色があり、、、、、、、
                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・
                    ・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・・',
                ],
            ],
            'schedule' => [
                'header' => ['▼野菜種類', '1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月',],
                'detail' => [
                    ['根菜類', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇'],
                    ['キャベツ類', '×', '×', '×', '×', '×', '〇', '〇', '〇', '〇', '〇', '〇', '×'],
                    ['レタス類', '×', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇'],
                    ['葉茎菜類', '×', '×', '×', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇'],
                    ['果菜類', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇', '〇']
                ],
            ],
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'reviewCount' => 100,
            'reviews' => [
                [
                    'isMyPosted' => false,
                    'score' => [
                        'point' => 5,
                        'image' =>  [
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
                            'http://www.material-land.com/material/1305.png',
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
                    'isMyPosted' => true,
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
