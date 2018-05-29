{extends file='layout.tpl'}
{block 'css' append}
<style type="text/css">
  .header-form .form-group-sm {
    margin-top: 15px;
  }
  .header-form .form-group-sm input {
    border: 1px solid #e1e1e1;
    padding: 5px;
  }
  .header-form .form-group-sm label {
    display: block;
    font-size: 12px;
    line-height: 14px;
    margin: 5px 0 2px 0;
    color: #000000;
    font-weight: bold;
  }
  .header-form .row {
    font-size: 14px;
    line-height: 20px;
  }
  .header-form h5 {
    margin-bottom: 5px;
  }
</style>
{/block}
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
          <li class="nav-item active">
            <a class="nav-link" id="header-tab" data-toggle="tab" href="#header" role="tab" aria-controls="header" aria-selected="true" style="line-height: 16px;">Nagłówek<br><span style="font-size:10px">&ltInvoice-Header&gt</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="parties-tab" data-toggle="tab" href="#parties" role="tab" aria-controls="parties" aria-selected="false" style="line-height: 16px;">Oddziały<br><span style="font-size:10px">&ltInvoice-Parties&gt</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="lines-tab" data-toggle="tab" href="#lines" role="tab" aria-controls="lines" aria-selected="false" style="line-height: 16px;">Pozycje<br><span style="font-size:10px">&ltInvoice-Lines&gt</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="lines-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="false" style="line-height: 16px;">Podsumowanie<br><span style="font-size:10px">&ltInvoice-Summary&gt</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data" aria-selected="false" style="line-height: 16px;">Źródło<br><span style="font-size:10px">Dane XML</span></a>
          </li>
        </ul>



        <div class="tab-content" id="myTabContent" style="margin:10px;">
          <div class="tab-pane fade show active" id="header" role="tabpanel" aria-labelledby="header-tab">

            <br>
            InvoiceNumber <strong>{$XMLData['Invoice-Header'].InvoiceNumber}</strong>
            <br>

            <section class="header-form">

              <div class="row">
                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>InvoiceDate</label>
                    <input type="text" name="Invoice-Header[InvoiceDate]" value="{$XMLData['Invoice-Header'].InvoiceDate}">
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>SalesDate</label>
                    <input type="text" name="Invoice-Header[SalesDate]" value="{$XMLData['Invoice-Header'].SalesDate}">
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>InvoiceCurrency</label>
                    <input type="text" name="Invoice-Header[InvoiceCurrency]" value="{$XMLData['Invoice-Header'].InvoiceCurrency}">
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>InvoicePaymentDueDate</label>
                    <input type="text" name="Invoice-Header[InvoicePaymentDueDate]" value="{$XMLData['Invoice-Header'].InvoicePaymentDueDate}">
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>InvoicePaymentTerms</label>
                    <input type="text" name="Invoice-Header[InvoicePaymentTerms]" value="{$XMLData['Invoice-Header'].InvoicePaymentTerms}">
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>DocumentFunctionCode</label>
                    <input type="text" name="Invoice-Header[DocumentFunctionCode]" value="{$XMLData['Invoice-Header'].DocumentFunctionCode}">
                  </div>
                </div>
              </div>

              <br>
              <br>
              <h5>Order</h5>
              <hr>
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>BuyerOrderNumber</label>
                    <input type="text" name="Invoice-Header[Order][BuyerOrderNumber]" value="{$XMLData['Invoice-Header']['Order'].BuyerOrderNumber}">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>BuyerOrderDate</label>
                    <input type="text" name="Invoice-Header[Order][BuyerOrderDate]" value="{$XMLData['Invoice-Header']['Order'].BuyerOrderDate}">
                  </div>
                </div>
              </div>

              <br>
              <br>
              <h5>Delivery</h5>
              <hr>
              <div class="row">
                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>DeliveryLocationNumber</label>
                    <input type="text" name="Invoice-Header[Delivery][DeliveryLocationNumber]" value="{$XMLData['Invoice-Header']['Delivery'].DeliveryLocationNumber}">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>DeliveryDate</label>
                    <input type="text" name="Invoice-Header[Delivery][DeliveryDate]" value="{$XMLData['Invoice-Header']['Delivery'].DeliveryDate}">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>DespatchNumber</label>
                    <input type="text" name="Invoice-Header[Delivery][DespatchNumber]" value="{$XMLData['Invoice-Header']['Delivery'].DespatchNumber}">
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group-sm">
                    <label>DespatchAdviceNumber</label>
                    <input type="text" name="Invoice-Header[Delivery][DespatchAdviceNumber]" style="width: 100%" value="{$XMLData['Invoice-Header']['Delivery'].DespatchAdviceNumber}">
                  </div>
                </div>
              </div>
              <br>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group-sm">
                    <label>Name</label>
                    <input type="text" name="Invoice-Header[Delivery][Name]" style="width: 100%" value="{$XMLData['Invoice-Header']['Delivery'].Name}">
                  </div>
                  <div class="form-group-sm">
                    <label>StreetAndNumber</label>
                    <input type="text" name="Invoice-Header[Delivery][StreetAndNumber]" style="width: 100%" value="{$XMLData['Invoice-Header']['Delivery'].StreetAndNumber}">
                  </div>
                  <div class="form-group-sm">
                    <label>CityName</label>
                    <input type="text" name="Invoice-Header[Delivery][CityName]" style="width: 100%" value="{$XMLData['Invoice-Header']['Delivery'].CityName}">
                  </div>

                </div>
              </div>

              <div class="row">
                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>PostalCode</label>
                    <input type="text" name="Invoice-Header[Delivery][PostalCode]" style="width: 100%" value="{$XMLData['Invoice-Header']['Delivery'].PostalCode}">
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group-sm">
                    <label>Country</label>
                    <input type="text" name="Invoice-Header[Delivery][Country]" style="width: 100%" value="{$XMLData['Invoice-Header']['Delivery'].Country}">
                  </div>
                </div>
              </div>

            </section>



          </div>

          <div class="tab-pane fade" id="parties" role="tabpanel" aria-labelledby="parties-tab">
            <br>
            <h5>Oddziały</h5>
          </div>

          {* --- INVOICE LINES --- *}
          <div class="tab-pane fade" id="lines" role="tabpanel" aria-labelledby="lines-tab">
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