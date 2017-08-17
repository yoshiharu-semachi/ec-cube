<?php
/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */


namespace Eccube\Controller\Admin\Setting\System;

use Eccube\Annotation\Inject;
use Eccube\Application;
use Eccube\Common\Constant;
use Eccube\Service\SystemService;
use Symfony\Component\HttpFoundation\Request;

class SystemController
{
    /**
     * @Inject(SystemService::class)
     * @var SystemService
     */
    protected $systemService;

    public function index(Application $app, Request $request)
    {
        switch ($request->get('mode')) {
            case 'info':
                ob_start();
                phpinfo();
                $phpinfo = ob_get_contents();
                ob_end_clean();

                return $phpinfo;

               break;
            default:
                break;
        }

        $this->arrSystemInfo = $this->getSystemInfo($app, $request);

        return $app->render('Setting/System/system.twig', array(
            'arrSystemInfo' => $this->arrSystemInfo,
        ));
    }

     public function getSystemInfo(Application $app, Request $request)
     {
        $system = $this->systemService;

        $arrSystemInfo = array(
            array('title' => 'EC-CUBE',     'value' => Constant::VERSION),
            array('title' => 'サーバーOS',    'value' => php_uname()),
            array('title' => 'DBサーバー',    'value' => $system->getDbversion()),
            array('title' => 'WEBサーバー',   'value' => $request->server->get("SERVER_SOFTWARE")),
        );

        $value = phpversion() . ' (' . implode(', ', get_loaded_extensions()) . ')';
        $arrSystemInfo[] = array('title' => 'PHP', 'value' => $value);
        $arrSystemInfo[] = array('title' => 'HTTPユーザーエージェント', 'value' => $request->headers->get('User-Agent'));

        return $arrSystemInfo;
     }
}
