{extends file='layout.tpl'}
{block 'content'}
 
  <div class="wallet">
    <h4>Faktura: {$query}</h4>

    {if (!$isFound)}
    <div class="col-md-6 offset-md-3" style="margin-top: 100px;">
      <div class="alert alert-info text-center">
        <h4>Nie odnaleziono faktury o podanym numerze</h4>
      </div>
    </div>
    {/if}


    {if ($isFound)}
      {include file="invoices/_filesList.tpl"}
    {/if}

  </div>

{/block}