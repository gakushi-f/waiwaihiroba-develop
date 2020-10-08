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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception as HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Eccube\Controller\AbstractController;

class ForgotController extends AbstractController
{
    /**
     * パスワード再発行(再設定完了ページ).
     *
     * @Route("/forgot/resetcomplete", name="forgot_reset_complete")
     * @Template("Forgot/reset_complete.twig")
     */
    public function reset_complete(Request $request)
    {
        if ($this->isGranted('ROLE_USER')) {
            throw new HttpException\NotFoundHttpException();
        }

        return [];
    }
}
