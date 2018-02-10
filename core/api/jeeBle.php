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
require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";

if (!jeedom::apiAccess(init('apikey'), 'blegateway')) {
 echo __('Clef API non valide, vous n\'êtes pas autorisé à effectuer cette action (blegateway)', __FILE__);
 die();
}
$content = file_get_contents('php://input');
function litEnd($data)
{
   return join(array_reverse(str_split($data,2)));
}
$arrayContent = explode(',',$content);
if (count($arrayContent) == 5)
{
    $uid = $arrayContent[1];
    $blegateway = blegateway::byLogicalId($uid, 'blegateway');
    if (!is_object($blegateway)) {
        return true;
    }
    log::add('blegateway', 'debug', $content);
    $rawpacket = $arrayContent[4];
    $vendor = substr($rawpacket,0,14);
    $type = litEnd(substr($rawpacket,14,4));
    if ($type = 'BC80') {
        $batt = round(hexdec(litEnd(substr($rawpacket, 18, 4))) / 100, 2);
        $blegateway->checkAndUpdateCmd('batt', $batt);;
        $event = intval(litEnd(substr($rawpacket, 22, 2)));
        if ($blegateway->getConfiguration('type')=='iSB01T')
        {
            $rawtemp = litEnd(substr($rawpacket, 24, 4));
            $temp = round(reset(unpack("s", pack("s", hexdec($rawtemp)))) / 100, 2);
            $blegateway->checkAndUpdateCmd('temp', $temp);
            $hum = hexdec(litEnd(substr($rawpacket, 28, 4)));
            $blegateway->checkAndUpdateCmd('hum', $hum);
        }
        if ($blegateway->getConfiguration('type')=='iSB01G')
        {
            $blegateway->checkAndUpdateCmd('move', intval($event & 2) == 2);
        }
        if ($blegateway->getConfiguration('type')=='iSB01H')
        {
            $blegateway->checkAndUpdateCmd('hall', intval($event & 4) == 4);
        }
        $blegateway->checkAndUpdateCmd('button', intval($event & 1) == 1);
    }
    $blegateway->setConfiguration('lastCommunication', date('Y-m-d H:i:s'));
    $blegateway->save();

}

return true;
