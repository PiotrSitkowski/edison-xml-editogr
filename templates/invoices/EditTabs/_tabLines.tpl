<br>
<h5>Pozycje faktury</h5>
<br>


<table class="table table-sm" style="font-size: 12px">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">EAN</th>
      <th scope="col">BuyerItemCode</th>
      <th scope="col">SupplierItemCode</th>
      <th scope="col">ItemDescription</th>
    </tr>
  </thead>
  <tbody>
  {foreach from=$XMLData['Invoice-Lines']['Line'] item=item key=key}
    <tr>
      <td scope="col" width="2%">{$item['Line-Item'].LineNumber}</td>
      <td scope="col" width="8%"><input type="text" name="Invoice-Lines[Line][{$key}][Line-Item][EAN]" value="{$item['Line-Item'].EAN}"></td>
      <td scope="col" width="30%"><input type="text" name="Invoice-Lines[Line][{$key}][Line-Item][BuyerItemCode]" value="{$item['Line-Item'].BuyerItemCode}" style="width:100% !important;"></td>
      <td scope="col" width="10%"><input type="text" name="Invoice-Lines[Line][{$key}][Line-Item][SupplierItemCode]" value="{$item['Line-Item'].SupplierItemCode}"></td>
      <td scope="col" width="50%"><input type="text" name="Invoice-Lines[Line][{$key}][Line-Item][ItemDescription]" value="{$item['Line-Item'].ItemDescription}" style="width:100% !important;"></td>
    </tr>
  {/foreach}

  </tbody>
</table>