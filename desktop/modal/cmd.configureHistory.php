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
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$count = array('history' => config::getHistorizedCmdNum(), 'timeline' => config::getTimelinedCmdNum());
$cmds = cmd::all();
?>

<div style="display: none;" id="md_cmdConfigureHistory"></div>
<a class="btn btn-success btn-xs pull-right" id="bt_cmdConfigureCmdHistoryApply"><i class="fas fa-check"></i> {{Valider}}</a>
<div class="center">
  <span class="label label-info">{{Commande(s) historisée(s) : }}<?php echo $count['history'] ?> - {{Commande(s) timeline : }}<?php echo $count['timeline'] ?></span>
</div>
<br/>
<table id="table_cmdConfigureHistory" class="table table-bordered table-condensed tablesorter stickyHead">
  <thead>
    <tr>
      <th>{{Nom}}</th>
      <th>{{Plugin}}</th>
      <th>{{Type}}</th>
      <th data-filter="false" data-sorter="checkbox">{{Historisé}}</th>
      <th data-filter="false" data-sorter="checkbox">{{Timeline}}
        <a class="btn btn-success btn-xs" id="bt_applytimeline" style="width:22px;"><i class="fas fa-check"></i></a>
        <a class="btn btn-danger btn-xs" id="bt_canceltimeline" style="width:22px;"><i class="fas fa-times"></i></a>
      </th>
      <th data-filter="false" data-sorter="checkbox">{{Inversée}}</th>
      <th data-sorter="select-text">{{Mode de lissage}}</th>
      <th class="extractor-select sorter-purges">{{Purge si plus vieux}}</th>
      <th data-sorter="false" data-filter="false">{{Action}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    $tr = '';
    foreach ($cmds as $cmd) {
      $tr .= '<tr data-change="0" data-cmd_id="'.$cmd->getId(). '">';

      //humanName:
      $tr .= '<td>';
      $tr .= '<span class="cmdAttr" data-l1key="humanName">'.str_replace('<br/>', '', $cmd->getHumanName(true, true)).'</span>';
      $tr .= '<span class="cmdAttr" data-l1key="id" style="display:none;">'.$cmd->getId().'</span>';
      $tr .= '</td>';

      //plugin:
      $tr .= '<td>';
      if (is_object($cmd->getEqLogic())) {
        $tr .= '<span class="cmdAttr" data-l1key="plugins">'.$cmd->getEqLogic()->getEqType_name().'</span>';
      }
      $tr .= '</td>';

      //type / subType:
      $tr .= '<td>';
      $tr .= '<span class="cmdAttr">'.$cmd->getType().' / '.$cmd->getSubType().'</span>';
      $tr .= '</td>';

      //historized:
      $tr .= '<td class="center">';
      if ($cmd->getType() == 'info') {
        $tr .= '<input type="checkbox" class="cmdAttr" data-l1key="isHistorized" '.(($cmd->getIsHistorized()) ? 'checked' : '').' />';
      }
      $tr .= '</td>';

      //timeline:
      $tr .= '<td>';
      $tr .= '<input type="checkbox" class="cmdAttr" data-l1key="configuration" data-l2key="timeline::enable" '.(($cmd->getConfiguration('timeline::enable')) ? 'checked' : '').' />';
      $tr .= ' <input class="cmdAttr input-xs form-control" data-l1key="configuration" data-l2key="timeline::folder" value="'.$cmd->getConfiguration('timeline::folder').'" style="width:80%;display:inline-block" placeholer="{{Dossier}}"/>';
      $tr .= '</td>';

      //Invert:
      $tr .= '<td class="center">';
      if ($cmd->getType() == 'info' && $cmd->getSubType() == 'binary') {
        $tr .= '<input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="invertBinary"'.(($cmd->getDisplay('invertBinary') == 1) ? 'checked' : '').' />';
      }
      $tr .= '</td>';

      //historizeMode
      $tr .= '<td>';
      if ($cmd->getType() == 'info' && $cmd->getSubType() == 'numeric') {
        $confHistorized = $cmd->getConfiguration('historizeMode');
        $tr .= '<div class="form-group">';
        $tr .= '<select class="form-control cmdAttr input-xs" data-l1key="configuration" data-l2key="historizeMode">';
        $tr .= '<option value="avg" '.(($confHistorized == 'avg') ? 'selected' : '').'>{{Moyenne}}</option>';
        $tr .= '<option value="min" '.(($confHistorized == 'min') ? 'selected' : '').'>{{Minimum}}</option>';
        $tr .= '<option value="max" '.(($confHistorized == 'max') ? 'selected' : '').'>{{Maximum}}</option>';
        $tr .= '<option value="none" '.(($confHistorized == 'none') ? 'selected' : '').'>{{Aucun}}</option>';
        $tr .= '</select>';
      }
      $tr .= '</td>';

      //historyPurge
      $tr .= '<td>';
      if ($cmd->getType() == 'info') {
        $confHistoryPurge = $cmd->getConfiguration('historyPurge');
        $tr .= '<select class="form-control cmdAttr input-xs" data-l1key="configuration" data-l2key="historyPurge">';
        $tr .= '<option value="-1 day" '.(($confHistoryPurge == '-1 day') ? 'selected' : '').'>{{1 jour}}</option>';
        $tr .= '<option value="-7 days" '.(($confHistoryPurge == '-7 days') ? 'selected' : '').'>{{7 jours}}</option>';
        $tr .= '<option value="-1 month" '.(($confHistoryPurge == '-1 month') ? 'selected' : '').'>{{1 mois}}</option>';
        $tr .= '<option value="-3 month" '.(($confHistoryPurge == '-3 month') ? 'selected' : '').'>{{3 mois}}</option>';
        $tr .= '<option value="-6 month" '.(($confHistoryPurge == '-6 month') ? 'selected' : '').'>{{6 mois}}</option>';
        $tr .= '<option value="-1 year" '.(($confHistoryPurge == '-1 year') ? 'selected' : '').'>{{1 an}}</option>';
        $tr .= '<option value="-2 years" '.(($confHistoryPurge == '-2 years') ? 'selected' : '').'>{{2 ans}}</option>';
        $tr .= '<option value="-3 years" '.(($confHistoryPurge == '-3 years') ? 'selected' : '').'>{{3 ans}}</option>';
        $tr .= '<option value="" '.(($confHistoryPurge == '') ? 'selected' : '').'>{{Jamais}}</option>';
        $tr .= '</select>';
      }
      $tr .= '</td>';

      //Actions:
      $tr .= '<td>';
      $tr .= '<a class="btn btn-default btn-xs pull-right cursor bt_configureHistoryAdvanceCmdConfiguration" data-id="'  .$cmd->getId(). '" title="{{Configuration de la commande}}"><i class="fas fa-cogs"></i></a>';
      if ($cmd->getType() == 'info') {
        $tr .= '<a class="btn btn-default btn-xs pull-right cursor bt_configureHistoryExportData" data-id="' .$cmd->getId(). '" title="{{Exporter la commande}}"><i class="fas fa-share export"></i></a>';
      }
      $tr .= '</td>';
      $tr .= '</tr>';
    }
    print $tr;
    ?>
  </tbody>
</table>

<script>
initTableSorter()

var $tableCmdConfigureHistory = $("#table_cmdConfigureHistory")
$tableCmdConfigureHistory[0].config.widgetOptions.resizable_widths = ['', '120px', '115px', '95px', '160px', '90px', '120px', '130px', '95px']
$tableCmdConfigureHistory.trigger('resizableReset')
$tableCmdConfigureHistory.width('100%')

$('.bt_configureHistoryAdvanceCmdConfiguration').off('click').on('click', function() {
  $('#md_modal2').dialog({title: "{{Configuration de la commande}}"}).load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-id')).dialog('open')
})

$(".bt_configureHistoryExportData").on('click', function() {
  window.open('core/php/export.php?type=cmdHistory&id=' + $(this).attr('data-id'), "_blank", null)
})

$('.cmdAttr').on('change click', function() {
  $(this).closest('tr').attr('data-change', '1')
})

$('#bt_cmdConfigureCmdHistoryApply').on('click',function() {
  var cmds = []
  $tableCmdConfigureHistory.find('tbody tr').each(function() {
    if ($(this).attr('data-change') == '1') {
      cmds.push($(this).getValues('.cmdAttr')[0])
    }
  })

  jeedom.cmd.multiSave({
    cmds : cmds,
    error: function(error) {
      $('#md_cmdConfigureHistory').showAlert({message: error.message, level: 'danger'})
    },
    success: function(data) {
      $tableCmdConfigureHistory.trigger("update")
      $('#md_cmdConfigureHistory').showAlert({message: '{{Modifications sauvegardées avec succès}}', level: 'success'})
    }
  })
})

$('#bt_canceltimeline').on('click', function() {
  $('.cmdAttr[data-l1key=configuration][data-l2key="timeline::enable"]:visible').each(function() {
    $(this).prop('checked', false)
    $(this).closest('tr').attr('data-change','1')
  })
})

$('#bt_applytimeline').on('click', function() {
  $('.cmdAttr[data-l1key=configuration][data-l2key="timeline::enable"]:visible').each(function() {
    $(this).prop('checked', true)
    $(this).closest('tr').attr('data-change','1')
  })
})

$('select[data-l2key="historyPurge"]').on('change', function(){
  $tableCmdConfigureHistory.trigger('updateCell', [$(this).parent()])
})

function setTableParser() {
  $.tablesorter.addParser({
    id: 'purges',
    is: function() {
      return false
    },
    format: function(s) {
      if (s == '') return '100000'
      return s.replace(/-3 years/, 1095)
        .replace(/-2 years/, 730)
        .replace(/-1 year/, 365)
        .replace(/-6 month/, 180)
        .replace(/-3 month/, 90)
        .replace(/-1 month/, 30)
        .replace(/-7 days/, 7)
        .replace(/-1 day/, 1)
    },
    type: 'numeric'
  })
}

$(function() {
  setTableParser()
  //initTooltips($tableCmdConfigureHistory)
  jeedom.timeline.autocompleteFolder()
  setTimeout(function() {
    $tableCmdConfigureHistory.closest('.ui-dialog').resize()
  }, 500)
})
</script>