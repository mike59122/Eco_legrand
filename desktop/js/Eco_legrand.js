// Initialisation du datepicker (nécessite une lib compatible JS pur si pas jQuery UI)
flatpickr.localize(flatpickr.l10ns.fr)
document.querySelectorAll('.dtimepickerMonth').forEach(function(el) {
    // Remplacer par une lib JS pure comme flatpickr si nécessaire
    // Exemple avec flatpickr :
    flatpickr(el, {
        locale: 'fr',
        dateFormat: 'd/m',
        monthSelectorType: 'dropdown',
        yearSelectorType: 'none',
        showButtonPanel: false
    });
});
document.getElementById('div_pageContainer').addEventListener('click', function(event) {
    // Test d'expression
    
    if (event.target.closest('.cmdAction[data-action="testinfo"]')) {
        target=event.target.closest('.cmdAction[data-action="testinfo"]')
        const input = target.closest('.form-group').querySelector('input');
        const expression = input?.value || '';

        if (!expression.trim()) {
                jeedomUtils.showAlert({
                message: "Expression vide.",
                level: 'warning',
                timeout: 2000,
                emptyBefore: true,
                attachTo:"div_alertExpressionTest"
                });
            //document.getElementById('div_alertExpressionTest').showAlert({ message: 'Expression vide.', level: 'warning' });
            return;
        }

        jeedom.scenario.testExpression({
            expression: expression,
            error: function(error) {
                jeedomUtils.showAlert({
                    message: error.message,
                    level: 'danger',
                    timeout: 2000,
                    emptyBefore: true,
                    attachTo:"div_alertExpressionTest"
                });
                //document.getElementById('div_alertExpressionTest').showAlert({ message: error.message, level: 'danger' });
            },
            success: function(data) {
                jeedomUtils.showAlert({
                    message: 'Commande : ' + expression + '. Résultat : ' + data.result,
                    level: 'success',
                    timeout: 2000,
                    emptyBefore: true
                });
                //document.getElementById('div_alert').showAlert({ message: 'Commande : ' + expression + '. Résultat : ' + data.result, level: 'success' });
            }
        });
    }
    // Sélection de commande info
    if (event.target.closest('.listCmdInfo')) {
       target=event.target.closest('.listCmdInfo')

        const input = target.closest('.form-group').querySelector('input');
        jeedom.cmd.getSelectModal({ cmd: { type: 'info' } }, function(result) {
            input.value = result.human;
        });
    }
    if(event.target.closest('#bt_cmdConfigureShowHistory')){
         target=event.target.closest('#bt_cmdConfigureShowHistory')
         const cmdId = target.closest('tr').getAttribute("data-cmd_id");
         console.log(cmdId)
        jeeDialog.dialog({
            id: 'md_cmdHistory',
            title: '{{Historique}}',                    
            contentUrl: `index.php?v=d&modal=cmd.history&id=${cmdId}`
        })
    }
})


// Navigation entre équipements
document.querySelectorAll('.li_eqLogic').forEach(function(el) {
    el.addEventListener('click', function(event) {
        const eqId = el.getAttribute('data-eqlogic_id');
        const eqType = el.getAttribute('data-eqLogic_type') || document.body.getAttribute('data-page');

        if (event.ctrlKey) {
            const url = `/index.php?v=d&m=${eqType}&p=${eqType}&id=${eqId}`;
            window.open(url).focus();
            return;
        }

        jeedom.eqLogic.cache.getCmd = [];

        const thumbnail = document.querySelector('.eqLogicThumbnailDisplay');
        if (thumbnail) thumbnail.style.display = 'none';

        document.querySelectorAll('.eqLogic').forEach(e => e.style.display = 'none');

        if (typeof prePrintEqLogic === 'function') {
            prePrintEqLogic(eqId);
        }

        const typeClass = document.querySelector('.' + eqType);
        if (typeClass) {
            typeClass.style.display = '';
        } else {
            document.querySelectorAll('.eqLogic').forEach(e => e.style.display = '');
        }

        el.classList.add('active');
        document.querySelector('.nav-tabs a:not(.eqLogicAction)').click();
      
        jeedom.eqLogic.print({
            type: eqType,
            id: eqId,
            status: 1,
            error: function(error) {
              
                document.getElementById('div_alert').showAlert({ message: error.message, level: 'danger' });
            },
            success: function(data) {
                document.querySelectorAll('body .eqLogicAttr').forEach(function(attr) {
                    attr.value = '';
                });

                if (data?.timeout === 0) data.timeout = '';

                document.body.setValues(data, '.eqLogicAttr');

                if (typeof printEqLogic === 'function') printEqLogic(data);
                if (typeof addCmdToTable === 'function') {
                    document.querySelectorAll('.cmd').forEach(e => e.remove());
                    data.cmd.forEach(cmd => addCmdToTable(cmd));
                }

                document.body.querySelectorAll('.cmd .cmdAttr[data-l1key="type"]').forEach(function(el) {
                    el.addEventListener('change', function() {
                        jeedom.cmd.changeType(el.closest('.cmd'));
                    });
                });

                document.body.querySelectorAll('.cmd .cmdAttr[data-l1key="subType"]').forEach(function(el) {
                    el.addEventListener('change', function() {
                        jeedom.cmd.changeSubType(el.closest('.cmd'));
                    });
                });

                addOrUpdateUrl('id', data.id);
                modifyWithoutSave = false;
                setTimeout(() => modifyWithoutSave = false, 1000);
            }
        });

        event.preventDefault();
    });
});


function addCmdToTable(_cmd = { configuration: {} }) {
    const cmd = _cmd;
    if (!cmd.configuration) cmd.configuration = {};

    if (init(cmd.type) !== 'info') return;

    const cmdId = init(cmd.id);
    const subType = init(cmd.subType);
    const tableSelector = {
        inst: '#inst_cmd tbody',
        csv: '#csv_cmd tbody',
        teleinfo: '#teleinfo_cmd tbody'
    }[init(cmd.configuration.type)] || '#inst_cmd tbody';

    const tableBody = document.querySelector(tableSelector);
    if (!tableBody) {
        console.warn('Table cible introuvable pour le type :', cmd.configuration.type);
        return;
    }

    const tr = document.createElement('tr');
    tr.classList.add('cmd');
    tr.setAttribute('data-cmd_id', cmdId);

    tr.innerHTML = `
        <td>
            <input class="cmdAttr form-control input-sm" data-l1key="id">
        </td>
        <td>
            <input class="cmdAttr form-control input-sm" data-l1key="type" style="display:none">
            <input class="cmdAttr form-control input-sm" data-l1key="subType" style="display:none">
            <input class="cmdAttr form-control input-sm" data-l1key="name" disabled placeholder="{{Nom}}">
        </td>
        <td>
            <input class="cmdAttr form-control input-sm" data-l1key="unite" style="width:90px;" placeholder="Unité">
            <span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isHistorized" ${cmd.isHistorized ? 'checked' : ''}>Historiser</label></span>
            <span><label class="checkbox-inline"><input type="checkbox" class="cmdAttr" data-l1key="isVisible" ${cmd.isVisible ? 'checked' : ''}>Afficher</label></span>
        </td>
        <td>
            ${typeof jeeFrontEnd !== 'undefined' && jeeFrontEnd.jeedomVersion !== 'undefined' ? '<span class="cmdAttr" data-l1key="htmlstate"></span>' : ''}
        </td>
        <td>
        `
           if (is_numeric(cmd.id)){
            tr.innerHTML +=`
                <a class="btn btn-default btn-xs cmdAction expertModeVisible" data-action="configure"><i class="fa fa-cogs"></i></a>
                <a class="btn btn-default btn-xs cmdAction" data-action="test"><i class="fa fa-rss"></i> {{Tester}}</a>
            ` 
        } 
               
            
            if (cmd.isHistorized == 1){
                tr.innerHTML +=`
                <a class="btn btn-default btn-xs " id="bt_cmdConfigureShowHistory">
                    <i class="fas fa-history"></i> Afficher historique
                </a>`
             }
         tr.innerHTML +=`</td>`;

    tableBody.appendChild(tr);

    // Appliquer les valeurs et types
    tr.setJeeValues(cmd, '.cmdAttr');
    jeedom.cmd.changeType(tr, subType);
}