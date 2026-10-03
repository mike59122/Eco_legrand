console.clear();
hideall();
var timer = window.setInterval(Timer_menu, 10000);
var ecqValue = document.getElementById('Eco_legrand_ecq').value;
// Affiche l'élément avec l'ID 'Eco_legrand_elec'
document.getElementById('Eco_legrand_elec').style.display = 'block';
// Ajoute la classe 'active' à tous les éléments correspondants
document.querySelector('.mainnav li.bt_elec').classList.add('active');
document.querySelector('.mainnav li.bt_elec').classList.remove('cursor');

console.log(ecqValue)
 /*
  navActions.forEach(({ selector, callback }) => {
    document.querySelectorAll(selector).forEach(btn => {
      console.log(btn)
      btn.addEventListener('click', () => {
        if (!btn.classList.contains('active')) {
          hideall();
          btn.classList.remove('cursor');
          btn.classList.add('active');
          callback();
        }
      });
    });
  });

  // Boutons de rafraîchissement synthèse
  const syntheseButtons = {
    'bt_refresh_synthese_mois': 'mois',
    'bt_refresh_synthese_jour': 'jours',
    'bt_refresh_synthese_semaine': 'semaine',
    'bt_refresh_synthese_annee': 'annee'
  };

  Object.entries(syntheseButtons).forEach(([id, period]) => {
    const btn = document.getElementById(id);
    if (btn) {
      btn.addEventListener('click', () => refreshSynthese(period));
    }
  });
const tooltipElements = document.querySelectorAll('[data-tooltip]');
  console.log('DOMContentLoaded')
  tooltipElements.forEach(el => {
    el.addEventListener('mouseenter', () => {
      const tooltipText = el.getAttribute('data-tooltip');
      const tooltip = document.createElement('div');
      tooltip.className = 'custom-tooltip';
      tooltip.textContent = tooltipText;
      document.body.appendChild(tooltip);

      const rect = el.getBoundingClientRect();
      tooltip.style.left = `${rect.left + window.scrollX}px`;
      tooltip.style.top = `${rect.top + window.scrollY - tooltip.offsetHeight - 8}px`;

      el._tooltip = tooltip;
    });

    el.addEventListener('mouseleave', () => {
      if (el._tooltip) {
        el._tooltip.remove();
        el._tooltip = null;
      }
    });
  });*/
document.getElementById('Eco_legrand_elec').addEventListener('click', function(event) {
  const target = event.target;
  if (target.closest('.icon_flip')) {
    const card = target.closest('.card');
    const front = card.querySelector('.front');
    const back = card.querySelector('.back');
    const back1 = card.querySelector('.back1');
    const back2 = card.querySelector('.back2');
    const isVisible = el => el && el.offsetParent !== null;

    const fadeToggle = el => {
      if (!el) return;
      
      if (isVisible(el)) {       
         el.style.display = 'none'
      } else {
        if (el.classList.contains("face")){
          el.style.display = 'block';
        }else{
          el.style.display = 'inline-block';
        }
       
      }
    };
   
    if (target == front){
     if (back1){
      card.querySelectorAll('.front').forEach(section => {
        fadeToggle(section)
      });
      card.querySelectorAll('.back1').forEach(section => {
        fadeToggle(section)
      });
     }else{
      card.querySelectorAll('.front').forEach(section => {
        fadeToggle(section)
      });
      card.querySelectorAll('.back').forEach(section => {
        fadeToggle(section)
      });
     }
      
      
    }else if(target == back){
      if (back1){
        card.querySelectorAll('.back').forEach(section => {
          fadeToggle(section)
        });
         card.querySelectorAll('.back1').forEach(section => {
          fadeToggle(section)
        });
      } else {
        card.querySelectorAll('.front').forEach(section => {
        fadeToggle(section)
        });
        card.querySelectorAll('.back').forEach(section => {
          fadeToggle(section)
          
        });
      }
   
    }else if(target == back1){
      if (back2){
        card.querySelectorAll('.back1').forEach(section => {
          fadeToggle(section)
        });
         card.querySelectorAll('.back2').forEach(section => {
          fadeToggle(section)
        });
      } else {
        card.querySelectorAll('.front').forEach(section => {
        fadeToggle(section)
        });
        card.querySelectorAll('.back1').forEach(section => {
          fadeToggle(section)
          
        });
      }
      
    }else if(target == back2){
      card.querySelectorAll('.back2').forEach(section => {
        fadeToggle(section)
        });
        card.querySelectorAll('.front').forEach(section => {
          fadeToggle(section)
          
        });
    }
 
    /*this('.chart').forEach(chartEl => {
      const highcharts = Highcharts.charts.find(chart => chart?.renderTo === chartEl);
      console.log(chartEl)
      console.log(highcharts)
      if (highcharts) {
        highcharts.reflow();
      }
    });*/
  }
  
    
  
})


document.getElementById('div_pageContainer').addEventListener('change', function (event) {
  // Simule un clic sur tous les éléments actifs du menu principalEco_legrand_ecq
  const target = event.target;
  console.log(event)
  if (target.closest('#Eco_legrand_ecq')){
    document.querySelectorAll('.mainnav li.active').forEach(el => {
      el.click();
    });

    // Récupère la valeur du champ et charge le dashboard
    const value = this.value;
    console.log(value)
    loadingDash(value, true); // chargement du dashboard
  }
})

document.getElementById('mainnav').addEventListener('click', function (event) {
  const target = event.target;
  console.log(event)
  
      
  if(target.closest('.bt_elec') && !target.closest('.bt_elec').classList.contains('active')){ 
    hideall();
    document.getElementById('Eco_legrand_elec')?.style.setProperty('display', 'block');    
    target.closest('.bt_elec').classList.remove('cursor');
    target.closest('.bt_elec').classList.add('active');
    loadingDash(document.getElementById('Eco_legrand_ecq')?.value, true);    
  }
  if(target.closest('.bt_eau') && !target.closest('.bt_eau').classList.contains('active')){
    hideall();
    document.getElementById('Eco_legrand_eau')?.style.setProperty('display', 'block');
    target.closest('.bt_eau').classList.remove('cursor');
    target.closest('.bt_eau').classList.add('active');
  }
  if(target.closest('.bt_gaz') && !target.closest('.bt_gaz').classList.contains('active')){
    hideall();
    document.getElementById('Eco_legrand_gaz')?.style.setProperty('display', 'block');
    target.closest('.bt_gaz').classList.remove('cursor');
    target.closest('.bt_gaz').classList.add('active');
  }
  if(target.closest('.bt_synthese') && !target.closest('.bt_synthese').classList.contains('active')){
    hideall();
    document.getElementById('Eco_legrand_synthese')?.style.setProperty('display', 'block');
    target.closest('.bt_synthese').classList.remove('cursor');
    target.closest('.bt_synthese').classList.add('active');
    refreshSynthese('all');
   

  }
  if(target.closest('.bt_tarifs') && !target.closest('.bt_tarifs').classList.contains('active')){
    hideall();
    document.getElementById('Eco_legrand_tarifs')?.style.setProperty('display', 'block');
    target.closest('.bt_tarifs').classList.remove('cursor');
    target.closest('.bt_tarifs').classList.add('active');
    refreshPrix();
  }
  if(target.closest('.bt_configuration') && !target.closest('.bt_configuration').classList.contains('active')){
    hideall();
    document.getElementById('Eco_legrand_configuration')?.style.setProperty('display', 'block');
    target.closest('.bt_configuration').classList.remove('cursor');
    target.closest('.bt_configuration').classList.add('active');
    refreshConfiguration();
  }
  if(target.closest('.bt_database') && !target.closest('.bt_database').classList.contains('active')){
    hideall();
    document.getElementById('Eco_legrand_database')?.style.setProperty('display', 'block');
    target.closest('.bt_database').classList.remove('cursor');
    target.closest('.bt_database').classList.add('active');
    showTeleinfo();
  }
})
document.getElementById('Eco_legrand_tarifs').addEventListener('click', function(event) {
  const target = event.target;
  // Suppression d’un tarif
  if (target.closest('.supp_prix')) {
  
    const liPrix = target.closest('.li_prix');
    if (!liPrix) return;

    const prixAttr = liPrix.querySelectorAll('.prixAttr');
    if (!prixAttr.length) return;

    const Prix = getValues(prixAttr)[0]; // getValues doit être définie ailleurs
    domUtils.ajax({
      type: "POST",
      url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
      data: {
         action: 'Supp_Prix',
        id: Prix.id
      },
      global: false,
      async: false,
      error: function(request, status, error) {
        handleAjaxError(request, status, error);
      },
      success: function(data) {
        if (data.state !== 'ok') {
          jeedomUtils.showAlert({
            message: data.result,
            level: 'danger'
          });
          return;
        }
        liPrix.remove();
        jeedomUtils.showAlert({
          message: 'Suppression réussie.',
          level: 'success'
        });
  

      }
    });
  }
  // Mise à jour d’un tarif
  if (target.closest('.updprix')) {
    const liPrix = target.closest('.li_prix');
    if (!liPrix) return;

    const prixAttr = liPrix.querySelectorAll('.prixAttr');
    if (!prixAttr.length) return;

    const Prix = getValues(prixAttr)[0]; // getValues doit être définie ailleurs
    PopupPrix(Prix.id, target.className);
  }
  // Ajout d’un tarif
  if (target.closest('.updprix')) {
   
    PopupPrix('', target.className);
  
  }
})

document.getElementById('Eco_legrand_database').addEventListener('click', function(event) {
})

document.getElementById('Eco_legrand_synthese').addEventListener('click', function(event) {
})

document.getElementById('Eco_legrand_configuration').addEventListener('click', function(event) {
})

/*document.addEventListener('DOMContentLoaded', function () {

 

  // Initialisation des datepickers (si tu utilises un plugin JS compatible)
  if (typeof window.datetimepicker === 'function') {
    document.querySelectorAll('.dtimepicker').forEach(el => {
      window.datetimepicker(el, {
        lang: 'fr',
        format: 'd-m-Y',
        timepicker: false,
        step: 15
      });
    });

    document.querySelectorAll('.dtimepickerTime').forEach(el => {
      window.datetimepicker(el, {
        lang: 'fr',
        timepicker: true,
        step: 5
      });
    });
  }

  // Initialisation du datepicker jQuery UI (si utilisé)
  if (typeof window.$ === 'function' && typeof window.$.datepicker === 'function') {
    window.$('.datetimepicker').datepicker({
      dateFormat: 'dd-mm-yy',
      autoclose: true,
      maxDate: "+0d"
    }).datepicker("setDate", "0");
  }
});
document.addEventListener('DOMContentLoaded', function () {
  
});*/





verifParam();
Timer_menu();

// Appelle la fonction loadingDash avec la valeur du champ #Eco_legrand_ecq

loadingDash(ecqValue, true); // chargement du dashboard


function hoverAction(thisPoint, state) {
  const { chart, userOptions, options, index } = thisPoint.series;
  const stackName = userOptions.stack;

  chart.series.forEach(ser => {
    if (ser.options.stack === stackName && ser !== thisPoint.series) {
      const targetPoint = ser.points[index];
      if (targetPoint) {
        targetPoint.setState(state);
      }
    }
  });
}


 (function (H) {
  H.wrap(H.seriesTypes.column.prototype, 'drawPoints', function (proceed, ...args) {
    const seriesIndex = this.index;
    const firstIndex = this.chart.series[0].index;
    const lastIndex = this.chart.series[this.chart.series.length - 1].index;
    const borderRadius = this.options.borderRadius;

    // Ajuste la position et la hauteur de chaque point
    this.points.forEach(point => {
      point.shapeArgs.y += 4;
      point.shapeArgs.height += borderRadius + 4;
    });

    // Appelle la méthode d'origine
    proceed.apply(this, args);

    // Applique le rayon aux coins pour les séries extrêmes
    this.points.forEach(point => {
      if (seriesIndex === firstIndex || seriesIndex === lastIndex) {
        try {
          point.graphic.attr({ r: borderRadius });
        } catch (error) {
          // Ignore les erreurs silencieusement
        }
      }
    });
  });
})(Highcharts);







function Timer_menu() {
  if (!document.getElementById('contentebar')) {
    clearInterval(timer);
    return;
  }

  const eqlogicId = document.getElementById('Eco_legrand_ecq')?.value;
  domUtils.ajax({
    type: "POST",
    url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
    data: {
      eqlogic_id: eqlogicId,
      action: 'Trame_actuelle',
      yesterday: 'false',
      limit: '1'
    },
    global: false,
    async: false,
    error: function(request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function(data) {
      if (data.state !== 'ok') {
        jeedomUtils.showAlert({
          message: data.result,
          level: 'danger'
        });
        return;
      }
      const iconeptec = document.getElementsByClassName('iconeptec');
      if (data.result.ptec === 'HC') {
        iconeptec[0]?.classList.replace('redcolor', 'bluecolor');
        iconeptec[1]?.classList.replace('redcolor', 'bluecolor');
      } else {
        iconeptec[0]?.classList.replace('bluecolor', 'redcolor');
        iconeptec[1]?.classList.replace('bluecolor', 'redcolor');
      }

      const ints1 = document.getElementById('Eco_legrand_ints1');
      if (data.result.int_instant > 9) {
        ints1?.classList.replace('ints1simple', 'ints1double');
      } else {
        ints1?.classList.replace('ints1double', 'ints1simple');
      }

      const ptecEl = document.getElementById('Eco_legrand_ptec');
      if (ptecEl) {
        ptecEl.textContent = data.result.ptec;
      }


      const middle = document.querySelector('#tab_detail .middle');
      const last = document.querySelector('#tab_detail .last');

      if (parseInt(data.result.int_instant) <= 0) {
        middle?.style.setProperty('display', 'none');
      } else {
        middle?.style.setProperty('display', 'block');
        const ints1 = document.getElementById('Eco_legrand_ints1');
        if (ints1) {
          ints1.textContent = `${data.result.int_instant}A`;
        }

      }

      if (parseInt(data.result.imax) <= 0) {
        last?.style.setProperty('display', 'none');
      } else {
        last?.style.setProperty('display', 'block');
      }

    }
  });

}

//$('.datetimepicker').datepicker({ 'format': 'yyyy-m-d', 'autoclose': true }).datepicker("setDate", "0");




Highcharts.setOptions({
  lang: {
    months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
    shortMonths: ['Janv', 'Févr', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Déc'],
    weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
    downloadPNG: "Télécharger au format PNG",
    downloadJPEG: "Télécharger au format JPEG",
    downloadPDF: "Télécharger au format PDF",
    downloadSVG: "Télécharger au format SVG",
    rangeSelectorFrom: "De",
    rangeSelectorTo: "à",
    loading: "Chargement...",
    noData: "Aucune donnée à afficher",
    resetZoom: "Réinitialiser le zoom",
    resetZoomTitle: "Réinitialiser le niveau de zoom à 1:1"
  }
});




document.querySelectorAll('.datecurrent.datetimepicker').forEach(el => {
  el.addEventListener('change', function () {
    const dateValue = this.value;
    if (dateValue !== '') {
      const eqlogicId = document.getElementById('Eco_legrand_ecq')?.value;
      domUtils.ajax({
        type: "POST",
        url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
        data: {
          eqlogic_id: eqlogicId,
          action: 'loadingDash',
          date_debut: dateValue
        },
        global: false,
        async: false,
        error: function(request, status, error) {
          handleAjaxError(request, status, error);
        },
        success: function(data) {
          if (data.state !== 'ok') {
            jeedomUtils.showAlert({
              message: data.result,
              level: 'danger'
            });
            return;
          }
          if (data.result.nb_trame > 0) {
            showCurrentTrame(data.result);
          } else {
            console.debug('Aucune valeur du jour trouvée');
          }

        }
      });
    }
  });
});
document.querySelectorAll('.datestat.datetimepicker').forEach(el => {
  el.addEventListener('change', function () {
    const dateValue = this.value;
    const eqlogicId = document.getElementById('Eco_legrand_ecq')?.value;
    domUtils.ajax({
      type: "POST",
      url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
      data: {
        eqlogic_id: eqlogicId,
        action: 'loadingPie',
        date_debut: dateValue
      },
      global: false,
      async: false,
      error: function(request, status, error) {
        handleAjaxError(request, status, error);
      },
      success: function(data) {
        if (data.state !== 'ok') {
          jeedomUtils.showAlert({
            message: data.result,
            level: 'danger'
          });
          return;
        }
        console.log(data);
        // Si tu veux réactiver le bloc conditionnel :
        /*
        if (data.result.nb_trame > 0) {
          console.log(data.result);
        } else {
          console.debug('Aucune valeur du jour trouvée');
        }
        */

      }
    });   
  });
});


function hideall() {
  // Masquer tous les éléments .row-overflow
  document.querySelectorAll('.row-overflow').forEach(el => {
    el.style.display = 'none';
  });

  // Ajouter la classe 'cursor' aux éléments .mainnav li.bt_* qui ne l'ont pas déjà
  document.querySelectorAll('.mainnav li').forEach(li => {
    const classList = li.className.split(' ');
    const hasBtClass = classList.some(cls => cls.startsWith('bt_'));
    const hasCursor = classList.includes('cursor');

    if (hasBtClass && !hasCursor) {
      li.classList.add('cursor');
    }
  });

  // Supprimer la classe 'active' de tous les éléments .mainnav li
  document.querySelectorAll('.mainnav li').forEach(li => {
    li.classList.remove('active');
  });
}



function showGraphTemp(data, containerId) {
 
  const unité = ' °C';
  const timezone = typeof timezonebis !== 'undefined' ? timezonebis : "Europe/Brussels";

  let series_perso = [
    {
      id: 'temp_max',
      name: "Maximum",
      data: data.TEMP_max,
      color: "var(--temp-max)",
      stack: 'current',
      index: 1,
      borderRadius: 12,
      dataLabels: {
        enabled: false,
        color: "var(--txt-color)",
        rotation: -45,
        y: -15,
        formatter: function () {
          return parseFloat(this.y) + '°C';
        },
        style: { font: 'bold 13px Verdana, sans-serif' }
      },
      type: 'spline',
      showInLegend: true
    },
    {
      id: 'temp_min',
      name: "Minimum",
      data: data.TEMP_min,
      color: "var(--temp-min)",
      stack: 'current',
      index: 2,
      borderRadiusTopLeft: 12,
      borderRadiusTopRight: 12,
      dataLabels: {
        enabled: false,
        color: "var(--txt-color)",
        rotation: -45,
        y: -15,
        formatter: function () {
          return parseFloat(this.y) + '°C';
        },
        style: { font: 'bold 13px Verdana, sans-serif' }
      },
      type: 'spline',
      showInLegend: true
    },
    {
      id: 'temp_moy',
      name: "Moyenne",
      data: data.TEMP_moy,
      color:  "var(--temp-moy)",
      stack: 'current',
      index: 2,
      borderRadiusTopLeft: 12,
      borderRadiusTopRight: 12,
      dataLabels: {
        enabled: false,
        color: "var(--txt-color)",
        rotation: -45,
        y: -15,
        formatter: function () {
          return parseFloat(this.y) + '°C';
        },
        style: { font: 'bold 13px Verdana, sans-serif' }
      },
      type: 'spline',
      showInLegend: true
    }
  ];

  

  return {
    chart: {
      renderTo: containerId,
      ignoreHiddenSeries: true,
      time: { timezone },
      type: 'spline'
    },
    legend: { enabled: false },
    credits: { enabled: false },
    title: { text: data.Libellé },
    subtitle: { text: '' },
    xAxis: [{
      type: 'datetime',
      categories: data.Categories,
      labels: {
        style: {
          color: "var(--txt-color)",
          font: '11px Verdana, sans-serif'
        },
        rotation: -45,
        align: 'right'
      }
    }],
    yAxis: {
      title: { text: unité },
      min: -10,
      minorGridLineWidth: 0,
      labels: {
        enabled: true,
        formatter: function () {
          return this.value + unité;
        },
        style: { color: "var(--txt-color)" }
      }
    },
    tooltip: {
      useHTML: true,
      backgroundColor: null,
      borderWidth: 0,
      formatter: function () {

        const index = this.point.index;
        /*const dateFormatter = new Intl.DateTimeFormat('fr-FR', {
          weekday: 'long',
          year: 'numeric',
          month: 'long',
          day: 'numeric'
        });*/
        
     // const formattedDate = dateFormatter.format(this.x);

        let html = `<div class='bubble' style='background-color:rgb(157, 180, 255)'>`;
        html += `<div style='text-align: center;'><b>${this.x}</b></div><hr/>`;
       
        let temp_min = '',   temp_moy = '',  temp_max = ''

        const seriesList = this.series?.chart?.series || [];

        seriesList.forEach(ser => {
          const val = ser.points?.[index]?.y;
          if (typeof val !== 'number') return;

          const color = ser.color || '#000';
          html += `<div style="color:${color}; font-weight:bold;">${ser.name}: ${val.toFixed(2)} °C</div>`;
        });


        html += temp_min + temp_moy + temp_max;
        
        html += "</div>";
        return html;
      }
    },
    plotOptions: {
      series: {
        stickyTracking: false,
        borderWidth: 0
      },
      column: {
        stacking: true,
        states: {
          inactive: { enabled: false }
        },
        point: {
          events: {
            mouseOver: function () {
              hoverAction(this, 'hover');
            },
            mouseOut: function () {
              hoverAction(this, '');
            }
          }
        }
      }
    },
    series: series_perso,
    navigation: {
      menuItemStyle: { fontSize: '10px' }
    }
  };
}

function showGraph(data, containerId) {
  const isEuro = containerId.includes("Euro");
  const unité = isEuro ? ' €' : ' kWh';
  const timezone = "Europe/Brussels";

  const HP_data = isEuro ? data.HP_data_prix_ttc : data.HP_data;
  const HC_data = isEuro ? data.HC_data_prix_ttc : data.HC_data;
  const HP_data_old = isEuro ? data.HP_data_prix_ttc_old : data.HP_data_old;
  const HC_data_old = isEuro ? data.HC_data_prix_ttc_old : data.HC_data_old;

  let series_perso = [
    {
      id: 'serie_hp',
      name: data.HP_name,
      data: HP_data,
      color: 'var(--highcharts-hp-color)',
      stack: 'current',
      index: 1,
      borderRadius: 12,
      dataLabels: {
        enabled: false,
        color: "var(--txt-color)",
        shadow: false,
        formatter: function () {
          return parseFloat(this.y) + unité;
        },
        style: {
          fontSize: '8px',
          font: 'bold 8px Verdana, sans-serif'
        }
      },
      type: 'column',
      showInLegend: true
    },
    {
      id: 'serie_hc',
      name: data.HC_name,
      data: HC_data,
      color: 'var(--highcharts-hc-color)',
      stack: 'current',
      index: 2,
      borderRadiusTopLeft: 12,
      borderRadiusTopRight: 12,
      dataLabels: {
        enabled: false,
        color: "var(--txt-color)",
        formatter: function () {
          return parseFloat(this.y) + unité;
        },
        style: {
          fontSize: '8px',
          font: 'bold 8px Verdana, sans-serif'
        }
      },
      type: 'column',
      showInLegend: true
    }
  ];

  if (data.show_old === "true") {
    series_perso.push(
      {
        id: 'serie_hp_old',
        name: data.HP_name_old,
        data: HP_data_old,
        color: 'var(--highcharts-hp-color-1)',
        stack: 'current_old',
        index: 3,
        borderRadius: 12,
        dataLabels: {
          enabled: false,
          color: "var(--txt-color)",
          shadow: false,
          formatter: function () {
            return parseFloat(this.y) !== 0 ? parseFloat(this.y) + unité : null;
          },
          style: {
            fontSize: '8px',
            font: 'bold 8px Verdana, sans-serif'
          }
        },
        type: 'column',
        showInLegend: true
      },
      {
        id: 'serie_hc_old',
        name: data.HC_name_old,
        data: HC_data_old,
        color: 'var(--highcharts-hc-color-1)',
        stack: 'current_old',
        index: 4,
        borderRadiusTopLeft: 12,
        borderRadiusTopRight: 12,
        dataLabels: {
          enabled: false,
          color: "var(--txt-color)",
          formatter: function () {
            return parseFloat(this.y) !== 0 ? parseFloat(this.y) + unité : null;
          },
          style: {
            fontSize: '8px',
            font: 'bold 8px Verdana, sans-serif'
          }
        },
        type: 'column',
        showInLegend: true
      }
    );
  }

  return {
    chart: {
      renderTo: containerId,
      ignoreHiddenSeries: true,
      time: { timezone },
      type: 'column'
    },
    legend: { enabled: false },
    credits: { enabled: false },
    title: { text: data.Libellé },
    subtitle: { text: '' },
    xAxis: [{
      type: 'datetime',
      categories: data.Categories,
      labels: {
        style: {
          color: "var(--txt-color)",
          font: '11px Verdana, sans-serif'
        },
        rotation: -45,
        align: 'right'
      }
    }],
    yAxis: {
      title: { text: unité },
      min: 0,
      minorGridLineWidth: 0,
      labels: {
        enabled: true,
        formatter: function () {
          return this.value + unité;
        },
        style: { color: "var(--txt-color)" }
      }
    },
    tooltip: {
      useHTML: true,
      backgroundColor: null,
      borderWidth: 0,
      formatter: function () {
        const index = this.point.index;
        let html = `<div class='bubble' style='background-color:rgb(157, 180, 255)'>`;
        html += `<div style='text-align: center;'><b>${this.x.replace("<br>", "-")}</b></div><hr/>`;

        let totalOld = 0;
        let showOldTotal = true;
        let hc = '', hp = '';

        this.series.chart.series.forEach(ser => {
          const val = ser.points[index]?.y || 0;
          if (ser.name.includes("Heures Creuses") && val > 0) hc += `${ser.name}: ${val} ${unité}<br/>`;
          if (ser.name.includes("Heures Pleines") && val > 0) hp += `${ser.name}: ${val} ${unité}<br/>`;
          if (ser.name.includes("année précédente")) totalOld += val;
          if (val === 0) showOldTotal = false;
        });

        html += hc + hp;
        html += `Total: ${this.point.stackTotal} ${unité}<br/>`;
        if (totalOld !== 0 && showOldTotal) {
          html += `Total année précédente: ${totalOld} ${unité}<br/>`;
        }
        html += "</div>";
        return html;
      }
    },
    plotOptions: {
      series: {
        stickyTracking: false,
        borderWidth: 0
      },
      column: {
        stacking: true,
        states: {
          inactive: { enabled: false }
        },
        point: {
          events: {
            mouseOver: function () {
              hoverAction(this, 'hover');
            },
            mouseOut: function () {
              hoverAction(this, '');
            }
          }
        }
      }
    },
    series: series_perso,
    navigation: {
      menuItemStyle: { fontSize: '10px' }
    }
  };
}


function verifParam() {
  domUtils.ajax({
    type: "POST",
    url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
    data: {
       action: 'VerifParam'
    },
    global: false,
    async: false,
    error: function(request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function(data) {
      if (data.state !== 'ok') {
        jeedomUtils.showAlert({
          message: data.result,
          level: 'danger'
        });
        return;
      }
      if (data.result !== '') {
        // Affiche un toast d'avertissement
        if (typeof jeedomUtils !== 'undefined' && typeof jeedomUtils.toastMsg === 'function') {
          jeedomUtils.toastMsg('warning', `<ul>${data.result}</ul>`);
        }

        // Optionnel : affichage dans une div
        // const alertDiv = document.getElementById('div_VerifParam');
        // if (alertDiv && typeof showAlert === 'function') {
        //   showAlert(alertDiv, { message: `<ul>${data.result}</ul>`, level: 'danger', ttl: 2000 });
        // }

        console.log(data.result);
      }

    }
  });
}

function loadingDash(id_equipement, all) {

  domUtils.ajax({
    type: "POST",
    url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
    data: {
      eqlogic_id: id_equipement,
      action: 'loadingDash',

    },
    global: false,
    async: false,
    dataType: 'json',
    error: function(request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function(data) {
      if (data.state !== 'ok') {
        jeedomUtils.showAlert({
          message: data.result,
          level: 'danger'
        });
        return;
      }
      if (data.result.nb_trame > 0) {
        initDashBoard(data.result, all);
      } else {
        console.info('Aucune valeur du jour trouvée, vérifiez les équipements');
      }

    }
  });
  return true;
}

function initDashBoard(datas, all) {
  // Si all est false, on quitte
  if (!all) return;

  // Initialisation des composants
  Tableau_Conso();
  showDashGraph();

  // Vérification de la puissance totale
  const lastTrame = datas.trame_du_jour[datas.trame_du_jour.length - 1];
  if (parseInt(lastTrame.puissance_totale) < 0) {
    ['tab_info', 'gauge', 'Currentbar'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.style.display = 'none';
    });

    const message = 'Pour visualiser la gauge merci de renseigner la Puissance instantanée dans la configuration de votre équipement';

    const appendText = (id, text) => {
      const el = document.getElementById(id);
      if (el) {
        const span = document.createElement('span');
        span.textContent = text;
        el.appendChild(span);
      }
    };

    appendText('tab_list', message);
    appendText('contentegauge', 'Pour visualiser les variations merci de renseigner la Puissance instantanée dans la configuration de votre équipement');
    appendText('contentebar', 'Pour visualiser le graphique merci de renseigner la Puissance instantanée dans la configuration de votre équipement');
  }

  // Affichage des composants dynamiques
  Gauge(datas.trame_du_jour, true, datas.isous);
  showCurrentTrame(datas);
  loadingPie();

  // Forcer le redimensionnement des graphiques
  window.dispatchEvent(new Event('resize'));

  return true;
}

function Tableau_Conso() {
  return new Promise((resolve, reject) => {
    const eqlogicId = document.getElementById('Eco_legrand_ecq')?.value;
    domUtils.ajax({
      type: "POST",
      url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
      data: {
       eqlogic_id: eqlogicId,
      action: 'TabConso'
      },
      global: false,
      async: false,
      error: function(request, status, error) {
        handleAjaxError(request, status, error);
      },
      success: function(data) {
        if (data.state !== 'ok') {
          jeedomUtils.showAlert({
            message: data.result,
            level: 'danger'
          });
          return;
        }    
         
        const unity = ' kWh';
        const Devise = ' €';
        const setText = (id, value) => {
          const el = document.getElementById(id);
          if (el) el.textContent = value;
        };
        const setHTML = (id, value) => {
          const el = document.getElementById(id);
          if (el) el.innerHTML = value;
        };
        const setTitle = (selector, value) => {
          document.querySelectorAll(selector).forEach(el => el.setAttribute('title', value));
        };

        setText('title_tb2', 'Watt');

        const updateConso = (prefix, conso) => {
          if (conso !== false) {
            setHTML(`${prefix}_hp`, conso.total_hp_ttc.toFixed(2) + Devise);
            setHTML(`${prefix}_hc`, conso.total_hc_ttc.toFixed(2) + Devise);
            setHTML(`${prefix}_total`, conso.total_ttc.toFixed(2) + Devise);
            setHTML(`${prefix}_hpw`, conso.hp.toFixed(2) + unity);
            setHTML(`${prefix}_hcw`, conso.hc.toFixed(2) + unity);
            setText(`${prefix}_totalw`, (conso.hp + conso.hc).toFixed(2) + unity);
          } else {
            ['hp', 'hc', 'total', 'hpw', 'hcw', 'totalw'].forEach(suffix => {
              setHTML(`${prefix}_${suffix}`, 'Indispo.');
            });
          }
        };

        updateConso('day', data.result.conso_jour);
        updateConso('yesterday', data.result.conso_hier);
        updateConso('week', data.result.conso_semaine);
        updateConso('month', data.result.conso_mois);
        updateConso('month_prec', data.result.conso_mois_précédent);
        updateConso('year', data.result.conso_année);

        if (data.result.title_mois) setTitle('.datefactmois', data.result.title_mois);
        if (data.result.title_année) setTitle('.datefact', data.result.title_année);

        document.querySelectorAll('.tts, .tts2').forEach(el => el.innerHTML = 'HP');
        document.querySelectorAll('.date_refresh').forEach(el => el.innerHTML = getDateRefresh());

        resolve();

      }
    });
  });
}


function pad(n) {
  return n < 10 ? '0' + n : n
}

function getDateRefresh() {

  var now = new Date();
  var annee = now.getFullYear();
  var mois = (now.getMonth() + 1);
  var jour = now.getDate();
  var heure = now.getHours();
  var minute = now.getMinutes();
  var seconde = now.getSeconds();

  return pad(jour) + "/" + pad(mois) + "/" + annee + " " + pad(heure) + ":" + pad(minute) + ":" + pad(seconde);
}

function Gauge(data, init, isous) {
  const power = isous * 230;
  const timezone = typeof timezonebis !== 'undefined' ? timezonebis : "Europe/Brussels";
  const eqlogicId = data.eqLogicID;
console.log(data)
  if (init) data = data[0];

  const chart = Highcharts.chart('gauge', {
    chart: {
      type: 'gauge',
      height: 200,
      time: { timezone },
      plotBackgroundColor: '',
      plotBackgroundImage: '',
      plotBorderWidth: 0,
      plotShadow: false
    },
    title: { text: '' },
    credits: { enabled: false },
    exporting: { enabled: false },
    pane: {
      startAngle: -150,
      endAngle: 150,
      background: [
        {
          backgroundColor: {
            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
            stops: [[0, '#FFF'], [1, '#333']]
          },
          borderWidth: 0,
          outerRadius: '109%'
        },
        {
          backgroundColor: {
            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
            stops: [[0, '#333'], [1, '#FFF']]
          },
          borderWidth: 0,
          outerRadius: '107%'
        },
        {},
        {
          backgroundColor: '#DDD',
          borderWidth: 0,
          outerRadius: '105%',
          innerRadius: '103%'
        }
      ]
    },
    yAxis: {
      min: 0,
      max: power,
      minorTickInterval: 'auto',
      minorTickWidth: 1,
      minorTickLength: 10,
      minorTickPosition: 'inside',
      minorTickColor: '#000000',
      tickPixelInterval: 50,
      tickWidth: 2,
      tickPosition: 'inside',
      tickLength: 15,
      tickColor: '#000000',
      labels: {
        step: 1,
        rotation: 'auto',
        style: {
          color: '#484343',
          fontWeight: 'bold'
        }
      },
      title: {
        text: 'Watt',
        y: 90
      },
      plotBands: [
        { from: 0, to: ((power - 2000) / 3) * 1, color: '#55BF3B' },
        { from: ((power - 2000) / 3) * 1, to: ((power - 2000) / 3) * 2, color: '#DDDF0D' },
        { from: ((power - 2000) / 3) * 2, to: power - 2000, color: '#FF7F00' },
        { from: power - 2000, to: power, color: '#FF0000' }
      ]
    },
    series: [{
      name: 'Consommation totale',
      data: [parseInt(data.puissance_totale)],
      tooltip: { valueSuffix: ' Watt' }
    }]
  });

  if (!chart.renderer.forExport) {
    const timergauge = setInterval(() => {
      const now = new Date();
      const date_actuelle = now.toLocaleDateString('fr-FR');

      const contentebar = document.getElementById('contentebar');
      const currentDebut = document.getElementById('current_debut');
      
      if (!contentebar) {
        clearInterval(timergauge);
        return;
      }

      if (now.getHours() === 0 && now.getMinutes() === 0) {
        if (currentDebut) currentDebut.value = date_actuelle;
      }

      if (currentDebut && currentDebut.value !== date_actuelle && now.getHours() === 0 && now.getMinutes() === 0) {
        clearInterval(timergauge);
        return;
      }

      if (!chart.series) {
        clearInterval(timergauge);
        return;
      }
     
      const eqlogicVal = document.getElementById('Eco_legrand_ecq').value;
      const point = chart.series[0].points[0];
      domUtils.ajax({
        type: "POST",
        url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
        data: {
          eqlogic_id: eqlogicVal,
          action: 'Trame_actuelle',
          yesterday: false,
          limit: '1'
        },
        global: false,
        async: false,
        error: function(request, status, error) {
          handleAjaxError(request, status, error);
        },
        success: function(data) {
          
          if (data.state !== 'ok') {
            jeedomUtils.showAlert({
              message: data.result,
              level: 'danger'
            });
            return;
          }
          const watt = parseInt(data.result.puissance_totale);
          point.update(watt);

          const timestamp = data.result.timestamp * 1000;
          const seriesTotal = ChartCurrentTrame.get('Total');
          const date = new Date(timestamp);

          if (date.getHours() === 0 && date.getMinutes() === 0 && seriesTotal.points.length > 50) {
            loadingDash(data.result.eqLogicID, true);
            return;
          }

          seriesTotal.addPoint([timestamp, watt]);

          const zones = seriesTotal.zones || [];
          const dataZone = zones.slice(0, -1).map(z => ({ value: z.value, color: z.color }));
          const lastColor = zones[zones.length - 1]?.color;
          const newColor = data.result.ptec === "HP" ? 'var(--highcharts-hp-color)' : 'var(--highcharts-hc-color)';

          if (lastColor === newColor) {
            dataZone[dataZone.length - 1].value = timestamp;
          } else {
            dataZone.push({ value: timestamp, color: newColor });
          }

          seriesTotal.update({ zones: dataZone });

          ['circuit1', 'circuit2', 'circuit3', 'circuit4', 'circuit5', 'circuit_autre'].forEach(circuit => {
            const serie = ChartCurrentTrame.get(circuit);
            const value = parseInt(data.result[`puissance_${circuit}`]);
            serie.addPoint([timestamp, value]);
          });

          const serieTemp = ChartCurrentTrame.get('Température');
          serieTemp.addPoint([timestamp, parseFloat(data.result.temperature)]);

          const trameDate = document.getElementById('trame_date');
          const refreshEls = document.querySelectorAll('.date_isrefresh');
          if (trameDate) trameDate.innerHTML = data.result.date;
          refreshEls.forEach(el => el.innerHTML = data.result.date);

         
          domUtils.ajax({
            type: "POST",
            url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
            data: {
              action: 'loadingPie',
              eqlogic_id: eqlogicVal
            },
            global: false,
            async: false,
            error: function(request, status, error) {
              handleAjaxError(request, status, error);
            },
            success: function(data) {
              if (data.state !== 'ok') {
                jeedomUtils.showAlert({
                  message: data.result,
                  level: 'danger'
                });
                return;
              }
              Tableau_Conso();

            }
          });
          
        }
      });
    }, 60000);
  }
}

function showCurrentTrame(datas) {
  
  const puissance_totale = [];
const puissance_circuit1 = [];
const puissance_circuit2 = [];
const puissance_circuit3 = [];
const puissance_circuit4 = [];
const puissance_circuit5 = [];
const puissance_autre = [];

const dataZone = [];
const dataZone_circuit1 = [];
const dataZone_circuit2 = [];
const dataZone_circuit3 = [];
const dataZone_circuit4 = [];
const dataZone_circuit5 = [];
const dataZone_circuit_autre = [];

const dataTemp = [];
const dataHier = [];

const puissanceMap = {
  circuit1: puissance_circuit1,
  circuit2: puissance_circuit2,
  circuit3: puissance_circuit3,
  circuit4: puissance_circuit4,
  circuit5: puissance_circuit5,
  circuit_autre: puissance_autre
};

const zoneMap = {
  circuit1: dataZone_circuit1,
  circuit2: dataZone_circuit2,
  circuit3: dataZone_circuit3,
  circuit4: dataZone_circuit4,
  circuit5: dataZone_circuit5,
  circuit_autre: dataZone_circuit_autre
};
  
  
 
  


  let point_start = 0;
  let last_color = false;
  let last_timestamp = false;
  let max_temp = 0;
  let old_ptec = false;

  const trames = [...datas.trame_du_jour].reverse();

  trames.forEach((value, key) => {
    var ts = value.timestamp * 1000;
    const date = new Date(ts);
    console.log(date.toLocaleString())
    ts = (value.timestamp - (new Date().getTimezoneOffset() * 60)) * 1000;
    puissance_totale.push([ts, parseInt(value.puissance_totale)]);
    puissance_circuit1.push([ts, parseInt(value.puissance_circuit1)]);
    puissance_circuit2.push([ts, parseInt(value.puissance_circuit2)]);
    puissance_circuit3.push([ts, parseInt(value.puissance_circuit3)]);
    puissance_circuit4.push([ts, parseInt(value.puissance_circuit4)]);
    puissance_circuit5.push([ts, parseInt(value.puissance_circuit5)]);
    puissance_autre.push([ts, Math.max(0, parseFloat(value.puissance_autre))]);
    dataTemp.push([ts, Math.max(0, parseFloat(value.temperature))]);

    max_temp = Math.max(max_temp, parseFloat(value.temperature));

    if (key === 0) point_start = ts;

    const color = value.ptec === "HP" ? 'var(--highcharts-hp-color)' : 'var(--highcharts-hc-color)';
    if (old_ptec !== value.ptec) {
      dataZone.push({ value: ts, color });
      old_ptec = value.ptec;
    }

    last_timestamp = ts;
    last_color = color;
  });

  dataZone.push({ value: last_timestamp, color: last_color });

  if (Array.isArray(datas.trame_hier) && datas.trame_hier.length > 0) {
    [...datas.trame_hier].reverse().forEach(value => {
      const d = new Date(value.timestamp * 1000);
      d.setDate(d.getDate() + 1);
      dataHier.push([d.getTime(), parseInt(value.puissance_totale)]);
    });
  }

  const timezone = "Europe/Brussels";

  ChartCurrentTrame = new Highcharts.StockChart({
    chart: {
      renderTo: 'Currentbar',
      time: { timezone, useUTC: false },
      height: 300,
      ignoreHiddenSeries: true
    },
    legend: {
      enabled: true,
      itemStyle: { font: 'Trebuchet MS, Verdana, sans-serif', color: "#333" },
      itemHoverStyle: { color: "#000" },
      itemHiddenStyle: { color: "#CCC" }
    },
    navigator: { enabled: true, maskInside: 2 },
    rangeSelector: {
      enabled: false,
      buttons: [
        { count: 1, type: 'minute', text: '1M' },
        { count: 5, type: 'minute', text: '5M' },
        { type: 'all', text: 'All' }
      ],
      inputEnabled: true,
      selected: 0
    },
    credits: { enabled: false },
    scrollbar: { enabled: true },
    title: { enabled: false },
    xAxis: { type: 'datetime', className: 'colorlabelxaxis' },
    yAxis: [
      {
        max: max_temp,
        valueDecimals: 0,
        labels: {
          align: 'left',
          format: '{value} °C',
          style: { color: '#006635', font: 'bold 13px Verdana, sans-serif' }
        },
        title: {
          text: '',
          style: { color: '#006635', font: 'normal 13px Verdana, sans-serif' }
        },
        opposite: true
      },
      {
        plotLines: [{ value: 0, width: 1, color: '#808080' }],
        title: {
          text: 'Puissance (Watt)',
          style: { color: '#808080', font: 'normal 13px Verdana, sans-serif' }
        },
        labels: {
          align: 'left',
          style: { color: '#808080', font: 'normal 13px Verdana, sans-serif' }
        }
      }
    ],
    exporting: { enabled: false },
    series: [
      {
        type: 'areaspline',
        name: 'Total',
        data: puissance_totale,
        visible: true,
        id: 'Total',
        pointStart: point_start,
        pointIntervalUnit: point_start,
        tooltip: { valueSuffix: ' W', valueDecimals: 0 },
        zoneAxis: 'x',
        zones: dataZone,
        yAxis: 1
      },
      ...['circuit1', 'circuit2', 'circuit3', 'circuit4', 'circuit5'].map((circuit, i) => ({
        type: 'areaspline',
        name: datas[`nom_${circuit}`],
        data: puissanceMap[circuit],
        visible: false,
        id: circuit,
        color: `var(--highcharts-color-${i})`,
        pointStart: point_start,
        pointIntervalUnit: 'month',
        tooltip: { valueSuffix: ' W', valueDecimals: 0 },
        zoneAxis: 'x',
        zones: eval(`dataZone_${circuit}`),
        yAxis: 1
      })),
      {
        type: 'areaspline',
        name: "Autre",
        data: puissance_autre,
        visible: false,
        id: 'circuit_autre',
        color: 'var(--highcharts-color-5)',
        pointStart: point_start,
        pointIntervalUnit: 'month',
        tooltip: { valueSuffix: ' W', valueDecimals: 0 },
        zoneAxis: 'x',
        zones: dataZone_circuit_autre,
        yAxis: 1
      },
      {
        type: 'spline',
        name: 'Hier',
        id: 'Hier',
        data: dataHier,
        color: 'var(--highcharts-color-hier)',
        tooltip: { valueSuffix: ' W', valueDecimals: 0 },
        visible: false,
        yAxis: 1
      },
      {
        type: 'spline',
        name: 'Température',
        id: 'Température',
        data: dataTemp,
        dashStyle: 'dash',
        color: 'var(--highcharts-color-temp)',
        tooltip: { valueSuffix: ' °C', valueDecimals: 2 },
        visible: true,
        yAxis: 0
      }
    ]
  });
}

function showDashGraph() {
  const eqlogicId = document.getElementById('Eco_legrand_ecq')?.value;
  if (!eqlogicId) return;

  // 🔹 Étape 1 : Récupérer les plages de dates
   domUtils.ajax({
    type: "POST",
    url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
    data: {
      action: 'loadingPie',
      action: 'get_date_actuelle',
      eqlogic_id: eqlogicId
    },
    global: false,
    async: false,
    error: function(request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function(data) {
      if (data.state !== 'ok') {
        jeedomUtils.showAlert({
          message: data.result,
          level: 'danger'
        });
        return;
      }
      
      const ranges = data.result;

      // 🔹 Étape 2 : Appeler les graphiques pour chaque période
      const graphCalls = [
        {
          libelle: 'Consommation des sept derniers jours',
          graph_type: 'jours',
          debut: ranges.sept_derniers_jours.debut,
          fin: ranges.sept_derniers_jours.fin,
          old: false,
          targets: ['Day', 'TempDay', 'DayEuro']
        },
        {
          libelle: 'Consommation des quatre dernières semaines',
          graph_type: 'semaines',
          debut: ranges.quatre_dernières_semaine.debut,
          fin: ranges.quatre_dernières_semaine.fin,
          old: false,
          targets: ['Month', 'TempMonth', 'MonthEuro']
        },
        {
          libelle: 'Consommation des douze derniers mois',
          graph_type: 'mois',
          debut: ranges.douze_derniers_mois.debut,
          fin: ranges.douze_derniers_mois.fin,
          debut_old: ranges.douze_derniers_mois_année_précédente.debut,
          fin_old: ranges.douze_derniers_mois_année_précédente.fin,
          old: true,
          targets: ['Year', 'TempYear', 'YearEuro']
        },
        {
          libelle: 'Consommation des cinq dernières années',
          graph_type: 'year',
          debut: ranges.cinq_dernières_années.debut,
          fin: ranges.cinq_dernières_années.fin,
          old: false,
          targets: ['pluri', 'pluriTemp', 'pluriEuro']
        }
      ];

      graphCalls.forEach(call => {
        const debut_value = call.debut
        const fin_value = call.fin
        const debut_old_value = call.debut_old
        const fin_old_value = call.fin_old
        const libelle_value = call.libelle
        const graph_type_value = call.graph_type
        domUtils.ajax({
          type: "POST",
          url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
          data: {
            action: 'Graphique',
            eqlogic_id: eqlogicId,
            debut: debut_value,
            fin: fin_value,
            debut_old:  debut_old_value,
            fin_old: fin_old_value,
            libelle: libelle_value,
            graph_type: graph_type_value
          },
          global: false,
          async: false,
          error: function(request, status, error) {
            handleAjaxError(request, status, error);
          },
          success: function(data) {
            if (data.state !== 'ok') {
              jeedomUtils.showAlert({
                message: data.result,
                level: 'danger'
              });
              return;
            }
            // 🔹 Générer les graphiques
            console.log( data.result)
            new Highcharts.Chart(showGraph(data.result, call.targets[0]));
            new Highcharts.Chart(showGraphTemp(data.result, call.targets[1]));
            new Highcharts.Chart(showGraph(data.result, call.targets[2]));

          }
        });       
      });
    }
  });
}

function frenchTodayDate(dateDebutStr, dateFinStr) {
  const mois_fr = [
    "janvier", "février", "mars", "avril", "mai", "juin",
    "juillet", "août", "septembre", "octobre", "novembre", "décembre"
  ];

  const dateDebut = new Date(dateDebutStr);
  const dateFin = new Date(dateFinStr);

  const yearDebut = dateDebut.getFullYear();
  const dayDebut = String(dateDebut.getDate()).padStart(2, '0');
  const monthDebut = mois_fr[dateDebut.getMonth()];
  const weekdayDebut = dateDebut.toLocaleDateString("fr-FR", { weekday: "long" });
  const formattedWeekdayDebut = weekdayDebut.charAt(0).toUpperCase() + weekdayDebut.slice(1);

  const yearFin = dateFin.getFullYear();
  const dayFin = String(dateFin.getDate()).padStart(2, '0');
  const monthFin = mois_fr[dateFin.getMonth()];
  const weekdayFin = dateFin.toLocaleDateString("fr-FR", { weekday: "long" });
  const formattedWeekdayFin = weekdayFin.charAt(0).toUpperCase() + weekdayFin.slice(1);

  if (dateDebutStr === dateFinStr) {
    return `${formattedWeekdayDebut} ${dayDebut} ${monthDebut}`;
  }

  if (yearDebut !== yearFin) {
    return `${formattedWeekdayDebut} ${dayDebut} ${monthDebut} ${yearDebut} - ${formattedWeekdayFin} ${dayFin} ${monthFin} ${yearFin}`;
  }

  if (monthDebut === monthFin) {
    return `${formattedWeekdayDebut} ${dayDebut} - ${formattedWeekdayFin} ${dayFin} ${monthFin}`;
  }

  return `${formattedWeekdayDebut} ${dayDebut} ${monthDebut} - ${formattedWeekdayFin} ${dayFin} ${monthFin}`;
}

function loadingPie(date) {
  console.log(date)
  const eqlogicId = document.getElementById('Eco_legrand_ecq')?.value;
  if (!eqlogicId) return;
  domUtils.ajax({
    type: "POST",
    url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
    data: {
      action: 'loadingPie',
      eqlogic_id: eqlogicId
      
    },
    global: false,
    async: false,
    error: function(request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function(data) {
      if (data.state !== 'ok') {
        jeedomUtils.showAlert({
          message: data.result,
          level: 'danger'
        });
        return;
      }
      console.log("loadingPie OK")
      console.log(data)
      if (data.result == null) {
        jeedomUtils.showAlert({
          message: "loadingPie vide",
          level: 'danger'
        });
        return;
      }
      const timezone = typeof timezonebis !== 'undefined' ? timezonebis : "Europe/Brussels";

      const tooltip = {
        useHTML: true,
        backgroundColor: null,
        borderWidth: 0,
        shared: true,
        formatter: function () {
          let html = `<div class='bubble' style='background-color:${this.point.color}'>`;
          html += `<div style='text-align: center;'><b>${this.point.name}</b></div><hr/>`;
          html += `<div style='text-align: center;'><b>${frenchTodayDate(this.point.date_début, this.point.date_fin)}</b></div><hr/>`;
          if (this.point.conso_totale !== 'none') html += `<span><b>Conso totale :</b> ${this.point.conso_totale}</span>`;
          if (this.point.conso_hp !== 'none') html += `<br><span><b>Conso HP :</b> ${this.point.conso_hp} kWh</span>`;
          if (this.point.conso_hc !== 'none') html += `<br><span><b>Conso HC :</b> ${this.point.conso_hc} kWh</span>`;
          if (this.point.prix !== 'none') html += `<br><span><b>Montant :</b> ${this.point.prix} €</span>`;
          html += `</div>`;
          return html;
        }
      };

      const chartConfig = {
        type: 'pie',
        time: { timezone, useUTC: false },
        ignoreHiddenSeries: true
      };

      const renderPieChart = (container, titleText, dataJour, dataTores, chartVarName) => {
        const chart = Object.assign({}, chartConfig, { renderTo: container });
        const series = [
          {
            name: 'Consommation',
            id: container.id,
            data: get_categories(dataJour),
            visible: true,
            size: '80%',
            dataLabels: { enabled: false }
          },
          {
            name: 'Consommation par tores',
            data: get_categories(dataTores),
            visible: true,
            size: '80%',
            innerSize: '70%',
            dataLabels: { enabled: false }
          }
        ];

        window[chartVarName] = new Highcharts.Chart({
          chart,
          title: { text: titleText, floating: true },
          credits: { enabled: false },
          tooltip,
          series,
          navigation: {
            buttonOptions: {
              verticalAlign: 'bottom',
              y: -20
            }
          }
        });
      };

      if (data.result.jour) {
        renderPieChart(StatDAY, "Aujourd'hui", data.result.jour, data.result.jour_tores, 'pieStatDAY');
      }
      if (data.result.hier) {
        renderPieChart(StatYESTERDAY, "Hier", data.result.hier, data.result.hier_tores, 'pieStatYESTERDAY');
      }
      if (data.result.semaine) {
        renderPieChart(StatWEEK, "Semaine", data.result.semaine, data.result.semaine_tores, 'pieStatWEEK');
      }
      if (data.result.mois) {
        renderPieChart(Stat, "Mois", data.result.mois, data.result.mois_tores, 'pieStatMonth');
      }
      if (data.result.annee) {
        renderPieChart(StatYEAR, "Année", data.result.annee, data.result.annee_tores, 'pieStatYEAR');
      }

    }
  });

  

  return true;
}

function get_categories(data) {
  const categorieData = [];

  data.data.forEach((value, index) => {
    const brightness = 0.2 - (index / data.length) / 5;

    categorieData.push({
      name: data.categorie[index],
      y: Math.round(value * 100) / 100,
      conso_totale: data.conso_totale[index],
      conso_hp: data.conso_HP[index],
      conso_hc: data.conso_HC[index],
      date_début: data.dates[0],
      date_fin: data.dates[1],
      prix: data.prix[index],
      color: data.color[index]
    });
  });

  return categorieData;
}

window.EcoLegrand = window.EcoLegrand || {};

/* -------------------------------
   1. TABLE DES MOIS (source unique)
---------------------------------- */
if (!window.EcoLegrand.moisMap) {
  window.EcoLegrand.moisMap = new Map([
    ['janvier', 'Janvier'], ['jan', 'Janvier'], ['january', 'Janvier'],
    ['février', 'Février'], ['fevrier', 'Février'], ['feb', 'Février'], ['february', 'Février'],
    ['mars', 'Mars'], ['mar', 'Mars'], ['march', 'Mars'],
    ['avril', 'Avril'], ['apr', 'Avril'], ['april', 'Avril'],
    ['mai', 'Mai'], ['may', 'Mai'],
    ['juin', 'Juin'], ['june', 'Juin'],
    ['juillet', 'Juillet'], ['jul', 'Juillet'], ['july', 'Juillet'],
    ['août', 'Août'], ['aout', 'Août'], ['aug', 'Août'], ['august', 'Août'],
    ['septembre', 'Septembre'], ['sep', 'Septembre'], ['sept', 'Septembre'], ['september', 'Septembre'],
    ['octobre', 'Octobre'], ['oct', 'Octobre'], ['october', 'Octobre'],
    ['novembre', 'Novembre'], ['nov', 'Novembre'], ['november', 'Novembre'],
    ['décembre', 'Décembre'], ['decembre', 'Décembre'], ['dec', 'Décembre'], ['december', 'Décembre']
  ]);
}

/* -------------------------------
   2. REGEX GÉNÉRÉES AUTOMATIQUEMENT
---------------------------------- */
if (!window.EcoLegrand.moisRegex) {
  window.EcoLegrand.moisRegex = Array.from(window.EcoLegrand.moisMap.keys()).map(mois => ({
    regex: new RegExp(`\\b${mois}\\b`, 'gi'),
    replacement: window.EcoLegrand.moisMap.get(mois)
  }));
}

/* -------------------------------
   3. NORMALISATION AVANCÉE
---------------------------------- */
function normalizeString(str) {
  return str
    .toLowerCase()
    .normalize("NFD").replace(/[\u0300-\u036f]/g, '') // accents
    .replace(/\./g, '') // "sept." → "sept"
    .trim();
}

/* -------------------------------
   4. FORMAT API (2024-01 → Janvier)
---------------------------------- */
function moisDepuisFormatAPI(str) {
  const match = str.match(/^\d{4}[-\/](\d{1,2})$/);
  if (!match) return null;

  const num = parseInt(match[1], 10);
  const moisFrancais = [
    'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin',
    'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'
  ];

  return moisFrancais[num - 1] || null;
}

/* -------------------------------
   5. TRADUCTION GLOBALE
---------------------------------- */
function traduction_mois(item) {
  for (const key in item) {
    let val = item[key];
    if (typeof val !== 'string') continue;

    const normalized = normalizeString(val);

    // 5.1 Format API (2024-01)
    const apiMonth = moisDepuisFormatAPI(normalized);
    if (apiMonth) {
      item[key] = apiMonth;
      continue;
    }

    // 5.2 Abréviations / accents / anglais / français
    for (const { regex, replacement } of window.EcoLegrand.moisRegex) {
      if (regex.test(normalized)) {
        item[key] = val.replace(regex, replacement);
        break;
      }
    }
  }
  return item;
}


function refreshSynthes_old(type) {
  const eqlogicId = document.getElementById('Eco_legrand_ecq')?.value;
  if (!eqlogicId) return;

  
  domUtils.ajax({
      type: "POST",
      url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
      data: {
        action: 'synthese',
        eqlogic_id: eqlogicId,
        type: type
      },
      global: false,
      async: false,
      error: function(request, status, error) {
        handleAjaxError(request, status, error);
      },
      success: function(data) {
        if (data.state !== 'ok') {
          jeedomUtils.showAlert({
            message: data.result,
            level: 'danger'
          });
          return;
        }
        const syntheseContainer = document.getElementById('tableau_synthese');
      if (syntheseContainer) {
        syntheseContainer.querySelectorAll('tr').forEach(tr => tr.remove());
      }

      let all = false;
      if (type === 'all') {
        all = true;
        type = 'jours';
      }

      const types = ['jours', 'mois', 'semaine', 'annee'];
      const processType = currentType => {
        const tableId = `tableau_synthese_${currentType}`;
        const widgetClass = `.synthese_${currentType}`;
        const widget = document.querySelector(widgetClass);
        const numCol = currentType === 'jours' ? 2 : 1;

        const table = document.createElement('table');
        table.id = tableId;
        table.className = 'table-striped table table-bordered table-hover';

        const thead = document.createElement('thead');
        const headerRow = document.createElement('tr');
        headerRow.className = 'widget-header';

        const headers = [
          { label: 'Nom', hidden: true },
          { label: 'Année' },
          ...(currentType === 'jours' ? [{ label: 'Jour' }] :
            currentType === 'semaine' ? [{ label: 'Semaine' }] :
            currentType === 'mois' ? [{ label: 'Mois' }] :
            [{ label: 'Année', hidden: true }]),
          { label: 'Conso HP' }, { label: 'Conso HC' }, { label: 'Prix HP' }, { label: 'Prix HC' },
          { label: 'Total HP (HT/TTC)' }, { label: 'Total HC (HT/TTC)' }, { label: 'Total (HT/TTC)' },
          { label: 'Temp min' }, { label: 'Temp moy' }, { label: 'Temp max' }
        ];

        headers.forEach(h => {
          const th = document.createElement('th');
          th.textContent = h.label;
          if (h.hidden) th.style.display = 'none';
          headerRow.appendChild(th);
        });

        thead.appendChild(headerRow);
        table.appendChild(thead);

        const tbody = document.createElement('tbody');
        tbody.className = 'tableau_synthese';

        data.result.data[currentType].forEach(item => {
          item = traduction_mois(item);
          const tr = document.createElement('tr');

          const values = [
            item.EqlogicID,
            item.annee,
            item.Categorie,
            `${item.hp} Kwh`,
            `${item.hc} Kwh`,
            `${item.prix_hp} €`,
            `${item.prix_hc} €`,
            `${item.total_prix_hp} € / ${item.total_prix_hp_ttc} €`,
            `${item.total_prix_hc} € / ${item.total_prix_hc_ttc} €`,
            `${item.total_prix} € / ${item.total_prix_ttc} €`,
            `${item.temp_min} °C`,
            `${item.temp_moy} °C`,
            `${item.temp_max} °C`
          ];

          values.forEach((val, i) => {
            const td = document.createElement('td');
            td.textContent = val;
            const display = headerRow.children[i]?.style.display;
            if (display === 'none') td.style.display = 'none';
            tr.appendChild(td);
          });

          tbody.appendChild(tr);
        });

        table.appendChild(tbody);

        const oldTable = document.getElementById(tableId);
        if (oldTable) oldTable.remove();
        const wrapper = document.getElementById(`${tableId}_wrapper`);
        if (wrapper) wrapper.remove();
        if (widget) widget.appendChild(table);

        set_datatable(`#${tableId}`, numCol);
      };

      if (all) {
        let typeIndex = types.indexOf(type);
        while (typeIndex < types.length) {
          processType(types[typeIndex]);
          typeIndex++;
        }
      } else {
        processType(type);
      }
    

      }
  });
  
}
function refreshSynthese(type) {
  const eqlogicId = document.getElementById('Eco_legrand_ecq')?.value;
  if (!eqlogicId) return;

  const isAll = type === 'all';
  const types = ['jours', 'mois', 'semaine', 'annee'];
  const requestedTypes = isAll ? types : [type];

  domUtils.showLoading(); // optionnel

  domUtils.ajax({
    type: "POST",
    url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
    data: { action: 'synthese', eqlogic_id: eqlogicId, type },
    global: false,
    async: true,
    error: handleAjaxError,
    success: function (data) {
      

      if (data.state !== 'ok') {
        jeedomUtils.showAlert({ message: data.result, level: 'danger' });
        return;
      }

      const syntheseContainer = document.getElementById('tableau_synthese');
      if (syntheseContainer) {
        syntheseContainer.querySelectorAll('tr').forEach(tr => tr.remove());
      }

      requestedTypes.forEach(currentType => {
        const rows = data.result.data[currentType];
        if (Array.isArray(rows)) {
          renderSyntheseTable(currentType, rows);
        }
      });
      domUtils.hideLoading();// optionnel
    }
  });
}

function renderSyntheseTable(type, dataRows) {
  const tableId = `tableau_synthese_${type}`;
  const widget = document.querySelector(`.synthese_${type}`);
  console.log(type)
  const numCol = type === 'jours' ? 2 : 1;

  const headers = getSyntheseHeaders(type);
  console.log(headers)
  const table = createTable(tableId, headers);
  console.log(table)
  const tbody = document.createElement('tbody');
  tbody.className = 'tableau_synthese';

  const fragment = document.createDocumentFragment();
  dataRows.forEach(raw => {
    const item = traduction_mois(raw);
    const row = createSyntheseRow(item, headers);
    fragment.appendChild(row);
  });
  tbody.appendChild(fragment);
  table.appendChild(tbody);
  console.log(tbody)
  document.getElementById(tableId)?.remove();
  document.getElementById(`${tableId}_wrapper`)?.remove();
  widget?.appendChild(table);
  //var dataTable = new DataTable(document.getElementById(tableId));

  set_datatable(`#${tableId}`, numCol);
}

function getSyntheseHeaders(type) {
  return [
    { label: 'Nom', hidden: true },
    { label: 'Année' },
    type === 'jours' ? { label: 'Jour' } :
    type === 'semaine' ? { label: 'Semaine' } :
    type === 'mois' ? { label: 'Mois' } :
    { label: 'Année', hidden: true },
    { label: 'Conso HP' }, { label: 'Conso HC' },
    { label: 'Prix HP' }, { label: 'Prix HC' },
    { label: 'Total HP (HT/TTC)' }, { label: 'Total HC (HT/TTC)' }, { label: 'Total (HT/TTC)' },
    { label: 'Temp min' }, { label: 'Temp moy' }, { label: 'Temp max' }
  ];
}

function createTable(id, headers) {
  const table = document.createElement('table');
  table.id = id;
  table.className = 'table-striped table table-bordered table-hover';

  const thead = document.createElement('thead');
  const headerRow = document.createElement('tr');
  headerRow.className = 'widget-header';

  headers.forEach(h => {
    const th = document.createElement('th');
    th.textContent = h.label;
    if (h.hidden) th.style.display = 'none';
    headerRow.appendChild(th);
  });

  thead.appendChild(headerRow);
  table.appendChild(thead);
  return table;
}

function createSyntheseRow(item, headers) {
  const tr = document.createElement('tr');
  const values = [
    item.EqlogicID,
    item.annee,
    item.Categorie,
    `${item.hp} Kwh`, `${item.hc} Kwh`,
    `${item.prix_hp} €`, `${item.prix_hc} €`,
    `${item.total_prix_hp} € / ${item.total_prix_hp_ttc} €`,
    `${item.total_prix_hc} € / ${item.total_prix_hc_ttc} €`,
    `${item.total_prix} € / ${item.total_prix_ttc} €`,
    `${item.temp_min} °C`, `${item.temp_moy} °C`, `${item.temp_max} °C`
  ];

  values.forEach((val, i) => {
    const td = document.createElement('td');
    td.textContent = val;
    if (headers[i]?.hidden) td.style.display = 'none';
    tr.appendChild(td);
  });

  return tr;
}



function set_datatable(selector, numCol) {
  const table = document.querySelector(selector);
  if (!table) return;

  // Détruire l'instance précédente si elle existe
  if (table._dataTable) {
    table._dataTable.destroy();
  }

  // Initialiser Vanilla-DataTables
  const dataTable = new DataTable(table, {
    searchable: true,
    sortable: true,
    fixedHeight: true,
    perPage: 10,
    labels: {
      placeholder: "Rechercher...",
      perPage: "{select} lignes par page",
      noRows: "Aucune donnée disponible",
      info: "Affichage de {start} à {end} sur {rows} entrées"
    }
  });

  // Stocker l'instance pour pouvoir la détruire plus tard
  table._dataTable = dataTable;


}

/*
    Tarifs
     */
function PopupPrix(id_prix, type) {
  const eqlogicId = document.getElementById('Eco_legrand_ecq')?.value;
  if (!eqlogicId) return;

  // Mise à jour des boutons
  document.getElementById('bsIsPrwater_type')?.classList.remove('btn-success');
  document.getElementById('bsIsPrgaz_type')?.classList.remove('btn-success');
  document.getElementById('bsIsPrelec_type')?.classList.add('btn-success');

  // Normalisation du type
  if (type.includes('électricité')) {
    type = 'électricité';
  } else if (type.includes('gaz')) {
    type = 'gaz';
  } else if (type.includes('eau')) {
    type = 'eau';
  }

  // Construction de l’URL
  const prixParam = id_prix ? `&id_prix=${id_prix}` : '';
  const url = `index.php?v=d&plugin=Eco_legrand&modal=ajoutprix${prixParam}&eqlogic_id=${eqlogicId}&type=${type}`;

  // Création ou récupération du <dialog>
  let dialog = document.getElementById('md_GestionPrix');
  if (!dialog) {
    dialog = document.createElement('dialog');
    dialog.id = 'md_GestionPrix';
    dialog.style.width = '610px';
    dialog.style.padding = '1em';
    dialog.style.border = '1px solid #ccc';
    dialog.style.borderRadius = '8px';
    dialog.style.maxHeight = '80vh';
    dialog.style.overflowY = 'auto';
    document.body.appendChild(dialog);
  }

  // Titre
  dialog.innerHTML = `<h2 style="margin-top:0;">Administration du tarif ${type.toUpperCase()}</h2><div id="dialogContent">Chargement...</div><button id="closeDialog" style="margin-top:1em;">Fermer</button>`;

  // Chargement du contenu
  domUtils.ajax({
    type: "POST",
    url: url,
    data: {
      
    },
    global: false,
    async: false,
    error: function(request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function(data) {
      if (data.state !== 'ok') {
        jeedomUtils.showAlert({
          message: data.result,
          level: 'danger'
        });
        return;
      }
      const content = dialog.querySelector('#dialogContent');
      if (content) content.innerHTML = data;
        dialog.querySelector('#closeDialog').addEventListener('click', () => {
        dialog.close();
      });

      // Ouverture
      dialog.showModal();
    }
  });
}
  



function refreshConfiguration() {}

function refreshPrix() {
  const eqlogicId = document.getElementById('Eco_legrand_ecq')?.value;
  if (!eqlogicId) return;

 
  domUtils.ajax({
    type: "POST",
    url: "plugins/Eco_legrand/core/ajax/Eco_legrand.ajax.php",
    data: {
      action: 'Récup_Prix',
      id: eqlogicId
    },
    global: false,
    async: false,
    error: function(request, status, error) {
      handleAjaxError(request, status, error);
    },
    success: function(data) {
      if (data.state !== 'ok') {
        jeedomUtils.showAlert({
          message: data.result,
          level: 'danger'
        });
        return;
      }
      const tbodyElec = document.querySelector('#ul_Gestprix_elec tbody');
      const tbodyGaz = document.querySelector('#ul_Gestprix_gaz tbody');
      const tbodyEau = document.querySelector('#ul_Gestprix_eau tbody');

      if (tbodyElec) tbodyElec.innerHTML = '';
      if (tbodyGaz) tbodyGaz.innerHTML = '';
      if (tbodyEau) tbodyEau.innerHTML = '';

      data.result.forEach(prix => {
        const tr = document.createElement('tr');
        tr.className = 'li_prix bt_sortable';
        tr.dataset.prix_id = prix.id;

        const tdHidden = document.createElement('td');
        tdHidden.style.display = 'none';
        tdHidden.innerHTML = `<input type="text" class="prixAttr form-control" data-l1key="id" style="display: none;" value="${prix.id}">`;
        tr.appendChild(tdHidden);

        const tdDebut = document.createElement('td');
        tdDebut.innerHTML = `<div style="float:left">${prix.date_debut}</div>`;
        tr.appendChild(tdDebut);

        const tdFin = document.createElement('td');
        tdFin.innerHTML = `<div style="float:left">${prix.date_fin}</div>`;
        tr.appendChild(tdFin);

        const tdHP = document.createElement('td');
        tdHP.innerHTML = `<div style="float:left">${prix.hp}</div>`;
        tr.appendChild(tdHP);

        const tdHC = document.createElement('td');
        tdHC.innerHTML = `<div style="float:left">${prix.hc}</div>`;
        tr.appendChild(tdHC);

        const tdActions = document.createElement('td');
        tdActions.innerHTML = `
          <center>
            <a class="btn btn-success updprix ${prix.type}" title="Modifier"><i class="fas fa-pencil-alt"></i></a>
            <a class="btn btn-danger supp_prix" title="Supprimer"><i class="fas fa-trash"></i></a>
          </center>
        `;
        tr.appendChild(tdActions);

        if (prix.type === 'électricité' && tbodyElec) {
          tbodyElec.appendChild(tr);
        } else if (prix.type === 'gaz' && tbodyGaz) {
          tbodyGaz.appendChild(tr);
        } else if (prix.type === 'eau' && tbodyEau) {
          tbodyEau.appendChild(tr);
        }
      })
    }
  })
}

function sum(input) {
  if (!Array.isArray(input)) return false;

  const total = input.reduce((acc, val) => {
    return acc + (isNaN(val) ? 0 : Number(val));
  }, 0);

  return total.toFixed(2);
}