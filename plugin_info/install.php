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

  require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';


function Eco_legrand_install() {
  $cron = cron::byClassAndFunction('Eco_legrand', 'getConsoall_heure');
  if (!is_object($cron)) {
    $cron = new cron();
    $cron->setClass('Eco_legrand');
    $cron->setFunction('getConsoall_heure');
    $cron->setEnable(1);
    $cron->setDeamon(0);
    $cron->setSchedule('4 * * * *');
    $cron->save();
  }
  $cron = cron::byClassAndFunction('Eco_legrand', 'getinfos');
  if (!is_object($cron)) {
    $cron = new cron();
    $cron->setClass('Eco_legrand');
    $cron->setFunction('getinfos');
    $cron->setEnable(1);
    $cron->setDeamon(0);
    $cron->setSchedule('* * * * *');
    $cron->save();
  }
  
  $cron = cron::byClassAndFunction('Eco_legrand', 'getConsoAll_jour');
  if (!is_object($cron)) {
    $cron = new cron();
    $cron->setClass('Eco_legrand');
    $cron->setFunction('getConsoAll_jour');
    $cron->setEnable(1);
    $cron->setDeamon(0);
    $cron->setSchedule('2 0 * * *');
    $cron->save();
  }
}

function legrandeco_update() {
  $cron = cron::byClassAndFunction('Eco_legrand', 'getConsoall_heure');
  if (!is_object($cron)) {
    $cron = new cron();
    $cron->setClass('Eco_legrand');
    $cron->setFunction('getConsoall_heure');
    $cron->setEnable(1);
    $cron->setDeamon(0);
    $cron->setSchedule('4 * * * *');
    $cron->save();
  }
  $cron = cron::byClassAndFunction('Eco_legrand', 'getConsoAll_jour');
  if (!is_object($cron)) {
    $cron = new cron();
    $cron->setClass('Eco_legrand');
    $cron->setFunction('getConsoAll_jour');
    $cron->setEnable(1);
    $cron->setDeamon(0);
    $cron->setSchedule('2 0 * * *');
    $cron->save();
  }
}

function Eco_legrand_remove() {
  $cron = cron::byClassAndFunction('Eco_legrand', 'getConsoall_heure');
  if (is_object($cron)) {
    $cron->remove();
  }
  $cron = cron::byClassAndFunction('Eco_legrand', 'getConsoAll_jour');
  if (is_object($cron)) {
    $cron->remove();
  }
}
?>