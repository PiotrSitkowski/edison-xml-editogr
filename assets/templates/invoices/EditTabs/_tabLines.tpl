<h5>Pozycje faktury</h5>
<table class="table table-sm table-inv-lines">
  <thead>
    <tr>
      <th>#</th>
      <th>EAN</th>
      <th>BuyerItemCode</th>
      <th>SupplierItemCode</th>
      <th>ItemDescription</th>
    </tr>
  </thead>
  <tbody>
  {foreach from=$XMLData['Invoice-Lines']['Line'] item=item key=key}
    <tr>
      <td>{$item['Line-Item'].LineNumber}</td>
      <td><input type="text" name="Invoice-Lines[Line][{$key}][Line-Item][EAN]" value="{$item['Line-Item'].EAN}"></td>
      <td><input type="text" name="Invoice-Lines[Line][{$key}][Line-Item][BuyerItemCode]" value="{$item['Line-Item'].BuyerItemCode}"></td>
      <td><input type="text" name="Invoice-Lines[Line][{$key}][Line-Item][SupplierItemCode]" value="{$item['Line-Item'].SupplierItemCode}"></td>
      <td><input type="text" name="Invoice-Lines[Line][{$key}][Line-Item][ItemDescription]" value="{$item['Line-Item'].ItemDescription}"></td>
    </tr>
  {/foreach}
  </tbody>
</table>