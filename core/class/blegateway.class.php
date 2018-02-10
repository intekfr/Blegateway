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

class blegateway extends eqLogic
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
                $list[] = array('plugins/blegateway/doc/images/dashes/' . $imgname, $brand);
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
                $blegatewayCmd = blegatewayCmd::byEqLogicIdAndLogicalId($this->getId(), 'move');
                if (!is_object($blegatewayCmd)) {
                    $blegatewayCmd = new blegatewayCmd();
                    $blegatewayCmd->setName('move');
                    $blegatewayCmd->setEqLogic_id($this->getId());
                    $blegatewayCmd->setLogicalId('move');
                    $blegatewayCmd->setType('info');
                    $blegatewayCmd->setSubType('binary');
                    $blegatewayCmd->setConfiguration('returnStateValue', 0);
                    $blegatewayCmd->setConfiguration('returnStateTime', 1);
                    $blegatewayCmd->save();
                }
                break;
            case 'iSB01H':
                $blegatewayCmd = blegatewayCmd::byEqLogicIdAndLogicalId($this->getId(), 'hall');
                if (!is_object($blegatewayCmd)) {
                    $blegatewayCmd = new blegatewayCmd();
                    $blegatewayCmd->setName('hall');
                    $blegatewayCmd->setEqLogic_id($this->getId());
                    $blegatewayCmd->setLogicalId('hall');
                    $blegatewayCmd->setType('info');
                    $blegatewayCmd->setSubType('binary');
                    $blegatewayCmd->setConfiguration('returnStateValue', 0);
                    $blegatewayCmd->setConfiguration('returnStateTime', 1);
                    $blegatewayCmd->save();
                }
                break;
            case 'iSB01T':
                $blegatewayCmd = blegatewayCmd::byEqLogicIdAndLogicalId($this->getId(), 'temp');
                if (!is_object($blegatewayCmd)) {
                    $blegatewayCmd = new blegatewayCmd();
                    $blegatewayCmd->setName('temp');
                    $blegatewayCmd->setEqLogic_id($this->getId());
                    $blegatewayCmd->setLogicalId('temp');
                    $blegatewayCmd->setType('info');
                    $blegatewayCmd->setSubType('numeric');
                    $blegatewayCmd->setUnite('C');
                    $blegatewayCmd->setConfiguration('returnStateValue', 0);
                    $blegatewayCmd->setConfiguration('returnStateTime', 1);
                    $blegatewayCmd->save();
                }
                $blegatewayCmd = blegatewayCmd::byEqLogicIdAndLogicalId($this->getId(), 'hum');
                if (!is_object($blegatewayCmd)) {
                    $blegatewayCmd = new blegatewayCmd();
                    $blegatewayCmd->setName('hum');
                    $blegatewayCmd->setEqLogic_id($this->getId());
                    $blegatewayCmd->setLogicalId('hum');
                    $blegatewayCmd->setType('info');
                    $blegatewayCmd->setSubType('numeric');
                    $blegatewayCmd->setUnite('%');
                    $blegatewayCmd->setConfiguration('returnStateValue', 0);
                    $blegatewayCmd->setConfiguration('returnStateTime', 1);
                    $blegatewayCmd->save();
                }
                break;
            default:
                break;
        }
        $blegatewayCmd = blegatewayCmd::byEqLogicIdAndLogicalId($this->getId(), 'button');
        if (!is_object($blegatewayCmd)) {
            $blegatewayCmd = new blegatewayCmd();
            $blegatewayCmd->setName('button');
            $blegatewayCmd->setEqLogic_id($this->getId());
            $blegatewayCmd->setLogicalId('button');
            $blegatewayCmd->setType('info');
            $blegatewayCmd->setSubType('binary');
            $blegatewayCmd->setConfiguration('returnStateValue', 0);
            $blegatewayCmd->setConfiguration('returnStateTime', 1);
            $blegatewayCmd->save();
        }
        $blegatewayCmd = blegatewayCmd::byEqLogicIdAndLogicalId($this->getId(), 'batt');
        if (!is_object($blegatewayCmd)) {
            $blegatewayCmd = new blegatewayCmd();
            $blegatewayCmd->setName('batt');
            $blegatewayCmd->setEqLogic_id($this->getId());
            $blegatewayCmd->setLogicalId('batt');
            $blegatewayCmd->setType('info');
            $blegatewayCmd->setSubType('numeric');
            $blegatewayCmd->setUnite('V');
            $blegatewayCmd->setConfiguration('returnStateValue', 0);
            $blegatewayCmd->setConfiguration('returnStateTime', 1);
            $blegatewayCmd->save();
        }
    }
}

class blegatewayCmd extends cmd
{
}
