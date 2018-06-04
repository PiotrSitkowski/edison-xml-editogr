{extends file='layout.tpl'}

{block 'css' append}
  <link rel="stylesheet" href="/css/invoicesEdit.css">
{/block}

{block 'content'}
 
  <div class="wrapper">
    <div class="card card-body bg-light">
      <h4>Dokument: {$filename}</h4>
      <dl>
        <dd>Data ostatniej modyfikacji: {$fileInfo.date} {$fileInfo.time}</dd>
        <dd>Rozmiar: {$fileInfo.size}</dd>
      </dl>
    </div>
    <br>
    {if (!$isFound)}
    <div class="col-md-6 offset-md-3" id="not-found-doc">
      <div class="alert alert-info text-center">
        <h4>Nie odnaleziono dokumentu</h4>
      </div>
    </div>
    {/if}

    {if ($isFound)}
    <section class="invoiceEditForm">

      <form method="POST" action="/invoices/save">

        <div class="text-right" id="btn-save">
          <input type="submit" class="btn btn-success" value="Zapisz">
        </div>

        <input type="hidden" name="xmlfilename" value="{$filename}">

        <ul class="nav nav-tabs" id="invoicesTabs" role="tablist">
          <li class="nav-item active">
            <a class="nav-link active" id="header-tab" data-toggle="tab" href="#header" role="tab" aria-controls="header" aria-selected="true">
              Nagłówek
              <span>Invoice-Header</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="parties-tab" data-toggle="tab" href="#parties" role="tab" aria-controls="parties" aria-selected="false">
              Oddziały
              <span>Invoice-Parties</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="lines-tab" data-toggle="tab" href="#lines" role="tab" aria-controls="lines" aria-selected="false">
              Pozycje
              <span>Invoice-Lines</span>
            </a>
          </li>
          {*
          <li class="nav-item">
            <a class="nav-link" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary" aria-selected="false">
              Podsumowanie
              <span>Invoice-Summary</span>
            </a>
          </li>
           *}
          <li class="nav-item">
            <a class="nav-link" id="data-tab" data-toggle="tab" href="#data" role="tab" aria-controls="data" aria-selected="false">
              Źródło
              <span>Dane XML</span>
            </a>
          </li>
        </ul>


        <div class="tab-content" id="invoicesTabContent">

          {* --- INVOICE HEADER --- *}
          <div class="tab-pane fade show active" id="header" role="tabpanel" aria-labelledby="header-tab">
            {include file="invoices/EditTabs/_tabHeader.tpl"}
          </div>

          {* --- INVOICE PARTIES --- *}
          <div class="tab-pane fade " id="parties" role="tabpanel" aria-labelledby="parties-tab">
            {include file="invoices/EditTabs/_tabParties.tpl"}
          </div>

          {* --- INVOICE LINES --- *}
          <div class="tab-pane fade" id="lines" role="tabpanel" aria-labelledby="lines-tab">
            {include file="invoices/EditTabs/_tabLines.tpl"}
          </div>

          {* --- INVOICE SUMMARY --- *}
          {*
          <div class="tab-pane fade" id="summary" role="tabpanel" aria-labelledby="summary-tab">
            {include file="invoices/EditTabs/_tabSummary.tpl"}
          </div>
          *}
          {* --- SOURCE DATA --- *}
          <div class="tab-pane fade" id="data" role="tabpanel" aria-labelledby="data-tab">
            {include file="invoices/EditTabs/_tabSource.tpl"}
          </div>

        </div>

      </form>

    </section>
    {/if}


  </div>

{/block}