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

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Eccube\Repository\CategoryRepository;
use Eccube\Repository\ProductRepository;
use Eccube\Repository\Master\ProductListMaxRepository;
use Eccube\Controller\AbstractController;
use Eccube\Form\Type\SearchProductType;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Paginator;

class TopController extends AbstractController
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
     * @Route("/", name="homepage")
     * @Template("index.twig")
     */
    public function index(Request $request, Paginator $productPaginator, Paginator $producerPaginator)
    {
        // handleRequestは空のqueryの場合は無視するため
        if ($request->getMethod() === 'GET') {
            $tmp_pageno = $request->query->get('pageno', '');
            $tmp_pageType = $request->query->get('pageType', '');
            if ($tmp_pageType == 'producer') {
                $request->query->set('pagenoProducer', $tmp_pageno);
            } else {
                $request->query->set('pageno', $tmp_pageno);
            }
        }

        $builder = $this->formFactory
            ->createBuilder(SearchProductType::class);
        $searchForm = $builder->getForm();
        $searchForm->handleRequest($request);

        // ツリー表示のため、ルートからのカテゴリを取得
        $TopCategories = $this->categoryRepository->getList(null);
        $ChoicedCategoryIds = [];

        $searchData = $searchForm->getData();
        // 商品一覧
        $qb = $this->productRepository->getQueryBuilderBySearchData($searchData);
        $query = $qb->getQuery()
            ->useResultCache(true, $this->eccubeConfig['eccube_result_cache_lifetime_short']);
        /** @var SlidingPagination $pagination */
        $pagination = $productPaginator->paginate(
            $query,
            !empty($searchData['pageno']) ? $searchData['pageno'] : 1,
            8
        );

        // 生産者一覧
        $producerQb = $this->productRepository->getQueryBuilderBySearchData($searchData);
        $producerQuery = $producerQb->getQuery()
            ->useResultCache(true, $this->eccubeConfig['eccube_result_cache_lifetime_short']);
        /** @var SlidingPagination $pagination */
        $producerPagination = $producerPaginator->paginate(
            $producerQuery,
            !empty($searchData['pagenoProducer']) ? $searchData['pagenoProducer'] : 1,
            8
        );

        return [
            'searchForm' => $searchForm->createView(),
            'TopCategories' => $TopCategories,
            'ChoicedCategoryIds' => $ChoicedCategoryIds,
            'pagination' => $pagination,
            'producerPagination' => $producerPagination,
        ];
    }
}
