<?php

  if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
  }
sendVarToJS('eqType', 'Eco_legrand');
$eqLogics = eqLogic::byType('Eco_legrand');

?>

  <div class="row row-overflow">
    <div class="col-lg-12 eqLogicThumbnailDisplay" id="listCol">
      <legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
      <div class="eqLogicThumbnailContainer logoPrimary">
        <div class="cursor eqLogicAction logoSecondary" data-action="add">
          <i class="fas fa-plus-circle"></i>
          <br/>
          <span>{{Ajouter}}</span>
        </div>
        <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
          <i class="fas fa-wrench"></i>
          <br/>
          <span>{{Configuration}}</span>
        </div>
      </div>

      <input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />

      <legend><i class="fas fa-home" id="butCol"></i> {{Mes Equipements}}</legend>
      <div class="eqLogicThumbnailContainer">
        <?php
          foreach ($eqLogics as $eqLogic) {
            $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
            echo '<div class="eqLogicDisplayCard cursor" data-eqLogic_id="' . $eqLogic->getId() . '" style="background-color : #ffffff ; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
            echo "<center>";
            echo '<img src="plugins/Eco_legrand/plugin_info/Eco_legrand_icon.png" height="105" width="95" />';
            echo "</center>";
            echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $eqLogic->getHumanName(true, true) . '</center></span>';
            echo '</div>';
          }
        ?>
      </div>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
      <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
      <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
      <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fas fa-cogs"></i> {{Configuration avancée}}</a>
      <ul class="nav nav-tabs" role="tablist">
        <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
        <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Equipement}}</a></li>
        <li role="presentation"><a href="#insttab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Données Instantanées}}</a></li>
        <li role="presentation"><a href="#teleinfotab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Données Téléinfo}}</a></li>
        <li role="presentation"><a href="#csvtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Données Consommation}}</a></li>
      </ul>
      <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">

        <div role="tabpanel" class="tab-pane active" id="eqlogictab">
          <br>
          <form class="form-horizontal">
            <fieldset>

              <div class="form-group">
                <label class="col-sm-3 control-label">{{Nom du compteur}}</label>
                <div class="col-sm-3">
                  <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
                  <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement Eco_legrand}}"/>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label" >{{Objet parent}}</label>
                <div class="col-sm-3">
                  <select class="form-control eqLogicAttr" data-l1key="object_id">
                    <option value="">{{Aucun}}</option>
                    <?php
                      foreach (jeeObject::all() as $object) {
                        echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
                      }
                    ?>
                  </select>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">{{Catégorie}}</label>
                <div class="col-sm-8">
                  <?php
                    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                      echo '<label class="checkbox-inline">';
                      echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                      echo '</label>';
                    }
                  ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label" ></label>
                <div class="col-sm-8">
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label" ></label>
                <div class="col-sm-8">
                  <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr configuration" data-l1key="configuration" data-l2key="afficher_panel"/>{{Afficher dans le panel}}</label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-3 control-label">{{Adresse IP}}</label>
                <div class="col-sm-3">
                  <input type="text" class="eqLogicAttr configuration form-control" data-l1key="configuration" data-l2key="addr" placeholder="192.168.0.17"/>
                </div>
              </div>
              
              
              <div class="form-group">
                <label class="col-sm-3 control-label">{{Date de facturation}}</label>
                <div class="col-sm-3">
                  <input style="width: 70px;" type="text" class="eqLogicAttr form-control dtimepickerMonth" data-l1key="configuration" data-l2key="date_abo" placeholder="01/01">
                </div>  
              </div>

              <div class="form-group">
                <label class="col-sm-3 control-label">{{TVA sur la consommation}}</label>
                <div class="col-sm-3">
                  <input style="width: 70px;" type="numeric" class="eqLogicAttr form-control col-sm-1" data-l1key="configuration" data-l2key="tva" placeholder="20"/>
                  <label class="col-sm-1 control-label">{{%}}</label>
                </div>
                
              </div>
              <div class="form-group ">
                <label class="col-sm-3 control-label">Température extérieure</label>
                <div class="col-sm-3 ">
                  <input class="eqLogicAttr form-control input-sm"  data-l1key="configuration" data-l2key="température extérieure">
                </div>
                <div class="col-sm-6">
                  <a class="btn btn-default btn-sm cmdAction" data-action="testinfo">
                    <i class="fa fa-rss"></i>Tester
                  </a> 
                  <a class="btn btn-default btn-sm listCmdInfo btn-warning" >
                    <i class="fa fa-list-alt"></i>
                  </a> (en °C)
                </div>
            </fieldset>
          </form>
        </div>
        <div role="tabpanel" class="tab-pane" id="insttab">
          <br>
          <table id="inst_cmd" class="table table-bordered table-condensed">
            <thead>
              <tr>
                <th style="">{{ID}}</th>
                <th style="">{{Nom}}</th>
                <th style="">{{Paramètres}}</th>
                <th style="">{{Valeur}}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

        </div>

        <div role="tabpanel" class="tab-pane" id="teleinfotab">
          <br>
          <table id="teleinfo_cmd" class="table table-bordered table-condensed">
            <thead>
              <tr>
                <th style="">{{ID}}</th>
                <th style="">{{Nom}}</th>
                <th style="">{{Paramètres}}</th>
                <th style="">{{Valeur}}</th>
                <th> </th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>

        </div>

        <div role="tabpanel" class="tab-pane" id="csvtab">
          <br>
          <table id="csv_cmd" class="table table-bordered table-condensed">
            <thead>
              <tr>           
              <th style="">{{ID}}</th>
              <th style="">{{Nom}}</th>
              <th style="">{{Paramètres}}</th>
              <th style="">{{Valeur}}</th>
              <th</th>
              <th></th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>

        </div>


      </div>
    </div>
  </div>
  


  <?php include_file('desktop', 'Eco_legrand', 'js', 'Eco_legrand'); ?>
  <?php include_file('core', 'plugin.template', 'js'); ?>
  <?php include_file('desktop', 'style', 'css', 'Eco_legrand');?>