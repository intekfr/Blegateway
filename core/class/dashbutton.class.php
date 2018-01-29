<?php

/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class dashbutton extends eqLogic
{


    public static function removeIcon($_icon)
    {
        $path = dirname(__FILE__) . '/../../../../' . $_icon;
        if (file_exists($path)) {
            unlink($path);
        }
        return;
    }

    public static function listIcon()
    {
        $path = dirname(__FILE__) . '/../../doc/images/dashes';
        $files = scandir($path);
        $list = array();
        foreach ($files as $imgname) {
            if (!in_array($imgname, ['.', '..'])) {
                $brand = ucfirst(explode('.', $imgname)[0]);
                $list[] = array('plugins/dashbutton/doc/images/dashes/' . $imgname, $brand);
            }
        }
        return $list;
    }

    public function preUpdate()
    {
        if ($this->getConfiguration('uid') == '') {
            throw new Exception(__('La MAC ne peut etre vide', __FILE__));
        }
        if ($this->getConfiguration('type') == '') {
            throw new Exception(__('Le type ne peut etre vide', __FILE__));
        }
    }

    public function preSave()
    {
        $this->setLogicalId($this->getConfiguration('uid'));
    }

    public function postAjax()
    {

        $type = $this->getConfiguration('type');
        switch ($type) {
            case 'iSB01G':
                $dashbuttonCmd = dashbuttonCmd::byEqLogicIdAndLogicalId($this->getId(), 'move');
                if (!is_object($dashbuttonCmd)) {
                    $dashbuttonCmd = new dashbuttonCmd();
                    $dashbuttonCmd->setName('move');
                    $dashbuttonCmd->setEqLogic_id($this->getId());
                    $dashbuttonCmd->setLogicalId('move');
                    $dashbuttonCmd->setType('info');
                    $dashbuttonCmd->setSubType('binary');
                    $dashbuttonCmd->setConfiguration('returnStateValue', 0);
                    $dashbuttonCmd->setConfiguration('returnStateTime', 1);
                    $dashbuttonCmd->save();
                }
                break;
            case 'iSB01H':
                $dashbuttonCmd = dashbuttonCmd::byEqLogicIdAndLogicalId($this->getId(), 'hall');
                if (!is_object($dashbuttonCmd)) {
                    $dashbuttonCmd = new dashbuttonCmd();
                    $dashbuttonCmd->setName('hall');
                    $dashbuttonCmd->setEqLogic_id($this->getId());
                    $dashbuttonCmd->setLogicalId('hall');
                    $dashbuttonCmd->setType('info');
                    $dashbuttonCmd->setSubType('binary');
                    $dashbuttonCmd->setConfiguration('returnStateValue', 0);
                    $dashbuttonCmd->setConfiguration('returnStateTime', 1);
                    $dashbuttonCmd->save();
                }
                break;
            case 'iSB01T':
                $dashbuttonCmd = dashbuttonCmd::byEqLogicIdAndLogicalId($this->getId(), 'temp');
                if (!is_object($dashbuttonCmd)) {
                    $dashbuttonCmd = new dashbuttonCmd();
                    $dashbuttonCmd->setName('temp');
                    $dashbuttonCmd->setEqLogic_id($this->getId());
                    $dashbuttonCmd->setLogicalId('temp');
                    $dashbuttonCmd->setType('info');
                    $dashbuttonCmd->setSubType('numeric');
                    $dashbuttonCmd->setUnite('C');
                    $dashbuttonCmd->setConfiguration('returnStateValue', 0);
                    $dashbuttonCmd->setConfiguration('returnStateTime', 1);
                    $dashbuttonCmd->save();
                }
                $dashbuttonCmd = dashbuttonCmd::byEqLogicIdAndLogicalId($this->getId(), 'hum');
                if (!is_object($dashbuttonCmd)) {
                    $dashbuttonCmd = new dashbuttonCmd();
                    $dashbuttonCmd->setName('hum');
                    $dashbuttonCmd->setEqLogic_id($this->getId());
                    $dashbuttonCmd->setLogicalId('hum');
                    $dashbuttonCmd->setType('info');
                    $dashbuttonCmd->setSubType('numeric');
                    $dashbuttonCmd->setUnite('%');
                    $dashbuttonCmd->setConfiguration('returnStateValue', 0);
                    $dashbuttonCmd->setConfiguration('returnStateTime', 1);
                    $dashbuttonCmd->save();
                }
                break;
            default:
                break;
        }
        $dashbuttonCmd = dashbuttonCmd::byEqLogicIdAndLogicalId($this->getId(), 'button');
        if (!is_object($dashbuttonCmd)) {
            $dashbuttonCmd = new dashbuttonCmd();
            $dashbuttonCmd->setName('button');
            $dashbuttonCmd->setEqLogic_id($this->getId());
            $dashbuttonCmd->setLogicalId('button');
            $dashbuttonCmd->setType('info');
            $dashbuttonCmd->setSubType('binary');
            $dashbuttonCmd->setConfiguration('returnStateValue', 0);
            $dashbuttonCmd->setConfiguration('returnStateTime', 1);
            $dashbuttonCmd->save();
        }
        $dashbuttonCmd = dashbuttonCmd::byEqLogicIdAndLogicalId($this->getId(), 'batt');
        if (!is_object($dashbuttonCmd)) {
            $dashbuttonCmd = new dashbuttonCmd();
            $dashbuttonCmd->setName('batt');
            $dashbuttonCmd->setEqLogic_id($this->getId());
            $dashbuttonCmd->setLogicalId('batt');
            $dashbuttonCmd->setType('info');
            $dashbuttonCmd->setSubType('numeric');
            $dashbuttonCmd->setUnite('V');
            $dashbuttonCmd->setConfiguration('returnStateValue', 0);
            $dashbuttonCmd->setConfiguration('returnStateTime', 1);
            $dashbuttonCmd->save();
        }
    }
}

class dashbuttonCmd extends cmd
{
}
