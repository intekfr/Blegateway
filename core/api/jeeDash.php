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

if (!jeedom::apiAccess(init('apikey'), 'dashbutton')) {
 echo __('Clef API non valide, vous n\'êtes pas autorisé à effectuer cette action (dashbutton)', __FILE__);
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
    $dashbutton = dashbutton::byLogicalId($uid, 'dashbutton');
    if (!is_object($dashbutton)) {
        return true;
    }
    log::add('dashbutton', 'debug', $content);
    $rawpacket = $arrayContent[4];
    $vendor = substr($rawpacket,0,14);
    $type = litEnd(substr($rawpacket,14,4));
    if ($type = 'BC80') {
        $batt = round(hexdec(litEnd(substr($rawpacket, 18, 4))) / 100, 2);
        $dashbutton->checkAndUpdateCmd('batt', $batt);;
        $event = intval(litEnd(substr($rawpacket, 22, 2)));
        if ($dashbutton->getConfiguration('type')=='iSB01T')
        {
            $rawtemp = litEnd(substr($rawpacket, 24, 4));
            $temp = round(reset(unpack("s", pack("s", hexdec($rawtemp)))) / 100, 2);
            $dashbutton->checkAndUpdateCmd('temp', $temp);
            $hum = hexdec(litEnd(substr($rawpacket, 28, 4)));
            $dashbutton->checkAndUpdateCmd('hum', $hum);
        }
        if ($dashbutton->getConfiguration('type')=='iSB01G')
        {
            $dashbutton->checkAndUpdateCmd('move', intval($event & 2) == 2);
        }
        if ($dashbutton->getConfiguration('type')=='iSB01H')
        {
            $dashbutton->checkAndUpdateCmd('hall', intval($event & 4) == 4);
        }
        $dashbutton->checkAndUpdateCmd('button', intval($event & 1) == 1);
    }
    $dashbutton->setConfiguration('lastCommunication', date('Y-m-d H:i:s'));
    $dashbutton->save();

}

return true;
