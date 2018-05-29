<section class="invoicesList" style="margin-top: 50px;">
  {if (count($XMLfiles))}
  <table class="table table-sm table-hover">
    <thead>
      <tr>
        <th scope="col">Nazwa pliku XML</th>
        <th scope="col">Data utworzenia</th>
        <th scope="col">Rozmiar</th>
        <th scope="col">Akcja</th>
      </tr>
    </thead>
    <tbody>
    {foreach from=$XMLfiles item=item}
      <tr>
        <td><a href="/invoices/edit/{$item.filename}">{$item.filename}</a></td>
        <td>{$item.date} {$item.time}</td>
        <td>{$item.size}</td>
        <td><a href="/invoices/edit/{$item.filename}" class="btn btn-sm btn-success">Edycja</a></td>
      </tr>
    {/foreach}
    </tbody>
  </table>
  {/if}
</section>