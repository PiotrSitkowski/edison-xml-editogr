<br>
InvoiceNumber <strong>{$XMLData['Invoice-Header'].InvoiceNumber}</strong>
<br>

<section class="tab-form">

  <div class="row">
    <div class="col-sm-6 col-md-3 col-lg-2">
      <div class="form-group-sm">
        <label>InvoiceDate</label>
        <input type="text" name="Invoice-Header[InvoiceDate]" value="{$XMLData['Invoice-Header'].InvoiceDate}">
      </div>
    </div>

    <div class="col-sm-6 col-md-3 col-lg-2">
      <div class="form-group-sm">
        <label>SalesDate</label>
        <input type="text" name="Invoice-Header[SalesDate]" value="{$XMLData['Invoice-Header'].SalesDate}">
      </div>
    </div>

    <div class="col-sm-6 col-md-3 col-lg-2">
      <div class="form-group-sm">
        <label>InvoiceCurrency</label>
        <input type="text" name="Invoice-Header[InvoiceCurrency]" value="{$XMLData['Invoice-Header'].InvoiceCurrency}">
      </div>
    </div>

    <div class="col-sm-6 col-md-3 col-lg-2">
      <div class="form-group-sm">
        <label>InvoicePaymentDueDate</label>
        <input type="text" name="Invoice-Header[InvoicePaymentDueDate]" value="{$XMLData['Invoice-Header'].InvoicePaymentDueDate}">
      </div>
    </div>

    <div class="col-sm-6 col-md-3 col-lg-2">
      <div class="form-group-sm">
        <label>InvoicePaymentTerms</label>
        <input type="text" name="Invoice-Header[InvoicePaymentTerms]" value="{$XMLData['Invoice-Header'].InvoicePaymentTerms}">
      </div>
    </div>

    <div class="col-sm-6 col-md-3 col-lg-2">
      <div class="form-group-sm">
        <label>DocumentFunctionCode</label>
        <input type="text" name="Invoice-Header[DocumentFunctionCode]" value="{$XMLData['Invoice-Header'].DocumentFunctionCode}">
      </div>
    </div>
  </div>

  <h5>Order</h5>
  <hr>
  <div class="row">
    <div class="col-sm-6 col-md-6 col-lg-2">
      <div class="form-group-sm">
        <label>BuyerOrderNumber</label>
        <input type="text" name="Invoice-Header[Order][BuyerOrderNumber]" value="{$XMLData['Invoice-Header']['Order'].BuyerOrderNumber}">
      </div>
    </div>
    <div class="col-sm-6 col-md-6 col-lg-2">
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
    <div class="col-sm-6 col-md-3 col-lg-3">
      <div class="form-group-sm">
        <label>DeliveryLocationNumber</label>
        <input type="text" name="Invoice-Header[Delivery][DeliveryLocationNumber]" value="{$XMLData['Invoice-Header']['Delivery'].DeliveryLocationNumber}">
      </div>
    </div>
    <div class="col-sm-6 col-md-3 col-lg-2">
      <div class="form-group-sm">
        <label>DeliveryDate</label>
        <input type="text" name="Invoice-Header[Delivery][DeliveryDate]" value="{$XMLData['Invoice-Header']['Delivery'].DeliveryDate}">
      </div>
    </div>
    <div class="col-sm-6 col-md-3 col-lg-2">
      <div class="form-group-sm">
        <label>DespatchNumber</label>
        <input type="text" name="Invoice-Header[Delivery][DespatchNumber]" value="{$XMLData['Invoice-Header']['Delivery'].DespatchNumber}">
      </div>
    </div>
    <div class="col-sm-12 col-md-9 col-lg-4">
      <div class="form-group-sm">
        <label>DespatchAdviceNumber</label>
        <input type="text" name="Invoice-Header[Delivery][DespatchAdviceNumber]" value="{$XMLData['Invoice-Header']['Delivery'].DespatchAdviceNumber}">
      </div>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-sm-12 col-md-9 col-lg-4">
      <div class="form-group-sm">
        <label>Name</label>
        <input type="text" name="Invoice-Header[Delivery][Name]" value="{$XMLData['Invoice-Header']['Delivery'].Name}">
      </div>
      <div class="form-group-sm">
        <label>StreetAndNumber</label>
        <input type="text" name="Invoice-Header[Delivery][StreetAndNumber]" value="{$XMLData['Invoice-Header']['Delivery'].StreetAndNumber}">
      </div>
      <div class="form-group-sm">
        <label>CityName</label>
        <input type="text" name="Invoice-Header[Delivery][CityName]" value="{$XMLData['Invoice-Header']['Delivery'].CityName}">
      </div>

    </div>
  </div>

  <div class="row">
    <div class="col-sm-7 col-md-5 col-lg-2">
      <div class="form-group-sm">
        <label>PostalCode</label>
        <input type="text" name="Invoice-Header[Delivery][PostalCode]" value="{$XMLData['Invoice-Header']['Delivery'].PostalCode}">
      </div>
    </div>
    <div class="col-sm-5 col-md-4 col-lg-2">
      <div class="form-group-sm">
        <label>Country</label>
        <input type="text" name="Invoice-Header[Delivery][Country]" value="{$XMLData['Invoice-Header']['Delivery'].Country}">
      </div>
    </div>
  </div>

</section>
