{extends file='layout.tpl'}
{block 'content'}
 
  <div class="wallet">
    <h4>Dokument: {$filename}</h4>
    <dl>
      <dd>Data ostatniej modyfikacji: {$fileInfo.date} {$fileInfo.time}</dd>
      <dd>Rozmiar: {$fileInfo.size}</dd>
    </dl>

    {if (!$isFound)}
    <div class="col-md-6 offset-md-3" style="margin-top: 100px;">
      <div class="alert alert-info text-center">
        <h4>Nie odnaleziono dokumentu</h4>
      </div>
    </div>
    {/if}

    {if ($isFound)}
    <section class="invoiceEditForm">

      <form method="POST" action="/invoices/save">

        <div class="text-right" style="margin-bottom: 20px;">
          <input type="submit" class="btn btn-success" value="Zapisz">
        </div>

        <input type="hidden" name="xmlfilename" value="{$filename}">

        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item">
            <a class="nav-link" id="header-tab" data-toggle="tab" href="#header" role="tab" aria-controls="header" aria-selected="false" style="line-height: 16px;">Nagłówek<br><span style="font-size:10px">&ltInvoice-Header&gt</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="parties-tab" data-toggle="tab" href="#parties" role="tab" aria-controls="parties" aria-selected="false" style="line-height: 16px;">Oddziały<br><span style="font-size:10px">&ltInvoice-Parties&gt</span></a>
          </li>
          <li class="nav-item active">
            <a class="nav-link" id="lines-tab" data-toggle="tab" href="#lines" role="tab" aria-controls="lines" aria-selected="true" style="line-height: 16px;">Pozycje<br><span style="font-size:10px">&ltInvoice-Lines&gt</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="lines-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="false" style="line-height: 16px;">Podsumowanie<br><span style="font-size:10px">&ltInvoice-Summary&gt</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data" aria-selected="false" style="line-height: 16px;">Źródło<br><span style="font-size:10px">Dane XML</span></a>
          </li>
        </ul>



        <div class="tab-content" id="myTabContent" style="margin:10px;">
          <div class="tab-pane fade" id="header" role="tabpanel" aria-labelledby="header-tab">
            <br>
            <h5>Nagłówek</h5>
            Nr faktury <strong>{$XMLData['Invoice-Header'].InvoiceNumber}</strong>
          </div>
          <div class="tab-pane fade" id="parties" role="tabpanel" aria-labelledby="parties-tab">
            <br>
            <h5>Oddziały</h5>
          </div>

          {* --- INVOICE LINES --- *}
          <div class="tab-pane fade show active" id="lines" role="tabpanel" aria-labelledby="lines-tab">
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

          </div>


          <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab">
            <br>
            <h5>Podsumowanie</h5>
          </div>

          <div class="tab-pane fade" id="data" role="tabpanel" aria-labelledby="data-tab">
            <pre class="code" style="font-size: 10px; color: #888888; width: 100%; height: 300px; overflow: auto; margin-top: 40px; border: 1px solid #ccc; background-color: #FEF9A2; padding: 15px;">
            {print_r($XMLData,true)}
            </pre>
          </div>
        </div>


      </form>

    </section>
    {/if}


  </div>

{/block}