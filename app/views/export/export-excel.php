

<form action="" method="post" id="export-form">
  <table>
    <tbody>
      <tr>
        <td style="padding-right: 20px;">Dari Data Ke- </td>
        <td style="margin: 20px;">
          <input type="number" name="start_range" id="start_range" class="ui-widget-content ui-corner-all" autocomplete="off" value="1" required> 
          <p class="text-xs" style="color: red;" id="start_invalid"></p>
        </td>
      </tr>
      <tr>
        <td style="padding-right: 20px;">Sampai Ke-</td>
        <td>
          <input type="number" name="end_range" id="end_range" class="ui-widget-content ui-corner-all" value="" required>
          <p class="text-xs" style="color: red;" id="end_invalid"></p>
        </td>
      </tr>
    </tbody>
  </table>

</form>


<p id="alert-export" class="mt-5" style="color: white; font-weight: bold; padding: 5px;"></p>

<script>
  let totalRecords = $("#jqGrid").jqGrid('getGridParam', 'records');
  document.getElementById('end_range').value = parseInt(totalRecords);
</script>