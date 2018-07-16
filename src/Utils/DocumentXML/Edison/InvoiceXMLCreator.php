<?php
namespace DocumentXML\Edison;

use DOMDocument;
use DOMElement;
use Helper\Helper;
use DocumentXML\ParserXML as XML;

/**
 * Edison Invoice XML Creator
 * Author: Piotr Sitkowski
 */
class InvoiceXMLCreator
{
    private $xml = null; // DOMDocument
    private $invoiceData = [];

    protected $DeliveriesGloss = [
            'Auchan Wola Bykowska' => [
                    'DeliveryLocationNumber' => '5900014210400',
                    'ParitesBuyerName' => 'Auchan Polska Spółka z ograniczoną odpowiedzialnością'
                ],
        ];

    public function __construct(DOMDocument $xml, array $XMLData)
    {
        $this->xml = $xml;
        $this->invoiceData = $XMLData;
    }


    /**
     * setInvoiceHeader putting new value into the header section of invoice
     *
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function setInvoiceHeader(string $key, string $value) : void
    {
        $this->invoiceData['Invoice-Header'][$key] = $value;
    }

    /**
     * setInvoiceOneLineItem putting new value into the item of positions section
     *
     * @param int $lineNumber
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function setInvoiceOneLineItem(int $lineNumber, string $key, string $value) : void
    {
        $this->invoiceData['Invoice-Lines']['Line'][$lineNumber]['Line-Item'][$key] = $value;
    }

    /**
     * setInvoiceOneLineOrder putting new value into the line order of positions section
     *
     * @param int $lineNumber
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function setInvoiceOneLineOrder(int $lineNumber, string $key, string $value) : void
    {
        $this->invoiceData['Invoice-Lines']['Line'][$lineNumber]['Line-Order'][$key] = $value;
    }

    /**
     * setInvoiceOneLineDelivery putting new value into the line delivery of positions section
     *
     * @param int $lineNumber
     * @param string $key
     * @param string $value
     *
     * @return array
     */
    public function setInvoiceOneLineDelivery(int $lineNumber, string $key, string $value) : void
    {
        $this->invoiceData['Invoice-Lines']['Line'][$lineNumber]['Line-Delivery'][$key] = $value;
    }



    /**
     * createInvoiceHeader Creating node elements into the header section of invoice and putting data into nodes elements
     *
     * @param  DOMElement $XMLNode
     * @param  array      $dataHeader
     *
     * @return DOMElement
     */
    private function createInvoiceHeader(DOMElement $XMLNode, array $dataHeader) : DOMElement
    {

        $xml_header = $this->addNodeElem($XMLNode, "Invoice-Header");

        $this->addNodeElem($xml_header, "InvoiceNumber", $dataHeader['InvoiceNumber'], true);
        $this->addNodeElem($xml_header, "InvoiceDate", $dataHeader['InvoiceDate']);
        $this->addNodeElem($xml_header, "SalesDate", $dataHeader['SalesDate']);
        $this->addNodeElem($xml_header, "InvoiceCurrency", $dataHeader['InvoiceCurrency']);
        $this->addNodeElem($xml_header, "InvoicePaymentDueDate", $dataHeader['InvoicePaymentDueDate']);
        $this->addNodeElem($xml_header, "InvoicePaymentTerms", $dataHeader['InvoicePaymentTerms']);
        $this->addNodeElem($xml_header, "DocumentFunctionCode", $dataHeader['DocumentFunctionCode']);

        $node = "Order";
        $xml_h_order = $this->addNodeElem($xml_header, $node);
        $this->addNodeElem($xml_h_order, "BuyerOrderNumber", $dataHeader[$node]['BuyerOrderNumber']);
        $this->addNodeElem($xml_h_order, "BuyerOrderDate", $dataHeader[$node]['BuyerOrderDate']);

        $node = "Delivery";
        $xml_h_delivery = $this->addNodeElem($xml_header, $node);
        $this->addNodeElem($xml_h_delivery, "DeliveryLocationNumber", $dataHeader[$node]['DeliveryLocationNumber']);
        $this->addNodeElem($xml_h_delivery, "DeliveryDate", $dataHeader[$node]['DeliveryDate']);
        $this->addNodeElem($xml_h_delivery, "DespatchNumber", $dataHeader[$node]['DespatchNumber']);
        $this->addNodeElem($xml_h_delivery, "DespatchAdviceNumber", $dataHeader[$node]['DespatchAdviceNumber'] );
        $this->addNodeElem($xml_h_delivery, "Name", $dataHeader[$node]['Name'], true);
        $this->addNodeElem($xml_h_delivery, "StreetAndNumber", $dataHeader[$node]['StreetAndNumber'], true);
        $this->addNodeElem($xml_h_delivery, "CityName", $dataHeader[$node]['CityName'], true);
        $this->addNodeElem($xml_h_delivery, "PostalCode", $dataHeader[$node]['PostalCode']);
        $this->addNodeElem($xml_h_delivery, "Country", $dataHeader[$node]['Country']);

        return $xml_header;
    }

    /**
     * createInvoiceParties Creating node elements into the Parties section of invoice and putting data into nodes elements
     *
     * @param  DOMElement $XMLNode
     * @param  array      $dataParties
     *
     * @return DOMElement
     */
    private function createInvoiceParties(DOMElement $XMLNode, array $dataParties) : DOMElement
    {
        $xml_invoice_parties = $this->addNodeElem($XMLNode, "Invoice-Parties");

        $node = "Buyer";
        $xml_parties = $this->addNodeElem($xml_invoice_parties, $node);
        $this->addNodeElem($xml_parties, "ILN", $dataParties[$node]['ILN']);
        $this->addNodeElem($xml_parties, "TaxID", $dataParties[$node]['TaxID']);
        $this->addNodeElem($xml_parties, "AccountNumber", $dataParties[$node]['AccountNumber']);
        $this->addNodeElem($xml_parties, "Name", $dataParties[$node]['Name'], true);
        $this->addNodeElem($xml_parties, "StreetAndNumber", $dataParties[$node]['StreetAndNumber'], true);
        $this->addNodeElem($xml_parties, "CityName", $dataParties[$node]['CityName'], true);
        $this->addNodeElem($xml_parties, "PostalCode", $dataParties[$node]['PostalCode']);
        $this->addNodeElem($xml_parties, "Country", $dataParties[$node]['Country']);

        $node = "Payer";
        $xml_parties = $this->addNodeElem($xml_invoice_parties, $node);
        $this->addNodeElem($xml_parties, "ILN", $dataParties[$node]['ILN']);
        $this->addNodeElem($xml_parties, "TaxID", $dataParties[$node]['TaxID']);
        $this->addNodeElem($xml_parties, "AccountNumber", $dataParties[$node]['AccountNumber']);
        $this->addNodeElem($xml_parties, "Name", $dataParties[$node]['Name'], true);
        $this->addNodeElem($xml_parties, "StreetAndNumber", $dataParties[$node]['StreetAndNumber'], true);
        $this->addNodeElem($xml_parties, "CityName", $dataParties[$node]['CityName'], true);
        $this->addNodeElem($xml_parties, "PostalCode", $dataParties[$node]['PostalCode']);
        $this->addNodeElem($xml_parties, "Country", $dataParties[$node]['Country']);

        $node = "Invoicee";
        $xml_parties = $this->addNodeElem($xml_invoice_parties, $node);
        $this->addNodeElem($xml_parties, "ILN", $dataParties[$node]['ILN']);
        $this->addNodeElem($xml_parties, "TaxID", $dataParties[$node]['TaxID']);
        $this->addNodeElem($xml_parties, "AccountNumber", $dataParties[$node]['AccountNumber']);
        $this->addNodeElem($xml_parties, "Name", $dataParties[$node]['Name'], true);
        $this->addNodeElem($xml_parties, "StreetAndNumber", $dataParties[$node]['StreetAndNumber'], true);
        $this->addNodeElem($xml_parties, "CityName", $dataParties[$node]['CityName'], true);
        $this->addNodeElem($xml_parties, "PostalCode", $dataParties[$node]['PostalCode']);
        $this->addNodeElem($xml_parties, "Country", $dataParties[$node]['Country']);

        $node = "Seller";
        $xml_parties = $this->addNodeElem($xml_invoice_parties, $node);
        $this->addNodeElem($xml_parties, "ILN", $dataParties[$node]['ILN']);
        $this->addNodeElem($xml_parties, "TaxID", $dataParties[$node]['TaxID']);
        $this->addNodeElem($xml_parties, "AccountNumber", $dataParties[$node]['AccountNumber']);
        $this->addNodeElem($xml_parties, "CodeByBuyer", $dataParties[$node]['CodeByBuyer']);
        $this->addNodeElem($xml_parties, "Name", $dataParties[$node]['Name'], true);
        $this->addNodeElem($xml_parties, "StreetAndNumber", $dataParties[$node]['StreetAndNumber'], true);
        $this->addNodeElem($xml_parties, "CityName", $dataParties[$node]['CityName'], true);
        $this->addNodeElem($xml_parties, "PostalCode", $dataParties[$node]['PostalCode']);
        $this->addNodeElem($xml_parties, "Country", $dataParties[$node]['Country']);
        $this->addNodeElem($xml_parties, "UtilizationRegisterNumber", $dataParties[$node]['UtilizationRegisterNumber']);
        $this->addNodeElem($xml_parties, "CourtAndCapitalInformation", $dataParties[$node]['CourtAndCapitalInformation']);

        $node = "Payee";
        $xml_parties = $this->addNodeElem($xml_invoice_parties, $node);
        $this->addNodeElem($xml_parties, "ILN", $dataParties[$node]['ILN']);
        $this->addNodeElem($xml_parties, "TaxID", $dataParties[$node]['TaxID']);
        $this->addNodeElem($xml_parties, "AccountNumber", $dataParties[$node]['AccountNumber']);
        $this->addNodeElem($xml_parties, "Name", $dataParties[$node]['Name'], true);
        $this->addNodeElem($xml_parties, "StreetAndNumber", $dataParties[$node]['StreetAndNumber'], true);
        $this->addNodeElem($xml_parties, "CityName", $dataParties[$node]['CityName'], true);
        $this->addNodeElem($xml_parties, "PostalCode", $dataParties[$node]['PostalCode']);
        $this->addNodeElem($xml_parties, "Country", $dataParties[$node]['Country']);

        $node = "SellerHeadquarters";
        $xml_parties = $this->addNodeElem($xml_invoice_parties, $node);
        $this->addNodeElem($xml_parties, "ILN", $dataParties[$node]['ILN']);
        $this->addNodeElem($xml_parties, "Name", $dataParties[$node]['Name'], true);
        $this->addNodeElem($xml_parties, "StreetAndNumber", $dataParties[$node]['StreetAndNumber'], true);
        $this->addNodeElem($xml_parties, "CityName", $dataParties[$node]['CityName'], true);
        $this->addNodeElem($xml_parties, "PostalCode", $dataParties[$node]['PostalCode']);
        $this->addNodeElem($xml_parties, "Country", $dataParties[$node]['Country']);

        return $xml_invoice_parties;
    }

    /**
     * createInvoiceLines Creating node elements into the Lines of all items section of invoice and putting data into nodes elements
     *
     * @param  DOMElement $XMLNode
     * @param  array      $dataLine
     *
     * @return DOMElement
     */
    private function createInvoiceLines(DOMElement $XMLNode, array $dataLine) : DOMElement
    {

        $xml_invoice_lines = $this->addNodeElem($XMLNode, "Invoice-Lines");

        if (!isset($dataLine[0])) {
            $newDataLine[0] = $dataLine;
            $dataLine = $newDataLine;
        }

        foreach ($dataLine as $item) {

            $xml_line = $this->addNodeElem($xml_invoice_lines, "Line");

            $node = "Line-Item";

            $BuyerItemCode = $item[$node]['BuyerItemCode'] ?? null;
            if (strlen($BuyerItemCode) > 6) $BuyerItemCode = substr($BuyerItemCode,0,6);

            $xml_l_item = $this->addNodeElem($xml_line, $node);
            $this->addNodeElem($xml_l_item, "LineNumber", $item[$node]['LineNumber']);
            $this->addNodeElem($xml_l_item, "EAN", $item[$node]['EAN']);
            $this->addNodeElem($xml_l_item, "BuyerItemCode", $BuyerItemCode);
            $this->addNodeElem($xml_l_item, "SupplierItemCode", $item[$node]['SupplierItemCode']);
            $this->addNodeElem($xml_l_item, "ItemDescription", $item[$node]['ItemDescription'], true);
            $this->addNodeElem($xml_l_item, "ItemType", $item[$node]['ItemType']);
            $this->addNodeElem($xml_l_item, "InvoiceQuantity", $item[$node]['InvoiceQuantity']);
            $this->addNodeElem($xml_l_item, "UnitOfMeasure", $item[$node]['UnitOfMeasure']);
            $this->addNodeElem($xml_l_item, "InvoiceUnitPacksize", $item[$node]['InvoiceUnitPacksize']);
            $this->addNodeElem($xml_l_item, "PackItemUnitOfMeasure", $item[$node]['PackItemUnitOfMeasure']);
            $this->addNodeElem($xml_l_item, "InvoiceUnitNetPrice", $item[$node]['InvoiceUnitNetPrice']);
            $this->addNodeElem($xml_l_item, "TaxRate", $item[$node]['TaxRate']);
            $this->addNodeElem($xml_l_item, "TaxCategoryCode", $item[$node]['TaxCategoryCode']);

            $node_tax_ref = "TaxReference";
            $xml_l_item_tax_ref = $this->addNodeElem($xml_l_item, $node_tax_ref);
            $this->addNodeElem($xml_l_item_tax_ref, "ReferenceType", $item[$node][$node_tax_ref]['ReferenceType']);
            $this->addNodeElem($xml_l_item_tax_ref, "ReferenceNumber", $item[$node][$node_tax_ref]['ReferenceNumber']);

            $this->addNodeElem($xml_l_item, "TaxAmount", $item[$node]['TaxAmount']);
            $this->addNodeElem($xml_l_item, "NetAmount", $item[$node]['NetAmount']);

            $node_order = "Line-Order";
            $xml_l_order = $this->addNodeElem($xml_line, $node_order);
            $this->addNodeElem($xml_l_order, "BuyerOrderNumber", $item[$node_order]['BuyerOrderNumber']);
            $this->addNodeElem($xml_l_order, "BuyerOrderDate", $item[$node_order]['BuyerOrderDate']);

            $node_deli = "Line-Delivery";
            $xml_l_order = $this->addNodeElem($xml_line, $node_deli);
            $this->addNodeElem($xml_l_order, "DeliveryLocationNumber", $item[$node_deli]['DeliveryLocationNumber']);
            $this->addNodeElem($xml_l_order, "DeliveryDate", $item[$node_deli]['DeliveryDate']);
            $this->addNodeElem($xml_l_order, "DespatchNumber", $item[$node_deli]['DespatchNumber']);
            $this->addNodeElem($xml_l_order, "DespatchAdviceNumber", $item[$node_deli]['DespatchAdviceNumber']);
            $this->addNodeElem($xml_l_order, "Name", $item[$node_deli]['Name'], true);
            $this->addNodeElem($xml_l_order, "StreetAndNumber", $item[$node_deli]['StreetAndNumber'], true);
            $this->addNodeElem($xml_l_order, "CityName", $item[$node_deli]['CityName'], true);
            $this->addNodeElem($xml_l_order, "PostalCode", $item[$node_deli]['PostalCode']);
            $this->addNodeElem($xml_l_order, "Country", $item[$node_deli]['Country']);

        }

        return $xml_invoice_lines;
    }

    /**
     * createInvoiceSummary Creating node elements into the Summary section of invoice and putting data into nodes elements
     *
     * @param  DOMElement $XMLNode
     * @param  array      $dataSummary
     *
     * @return DOMElement
     */
    private function createInvoiceSummary(DOMElement $XMLNode, array $dataSummary, bool $isCorrection = false) : DOMElement
    {
        $xml_summary = $this->addNodeElem($XMLNode, "Invoice-Summary");

        $this->addNodeElem($xml_summary, "TotalLines", $dataSummary['TotalLines']);
        $this->addNodeElem($xml_summary, "TotalNetAmount", $dataSummary['TotalNetAmount']);
        $this->addNodeElem($xml_summary, "TotalTaxableBasis", $dataSummary['TotalTaxableBasis']);
        $this->addNodeElem($xml_summary, "TotalTaxAmount", $dataSummary['TotalTaxAmount']);
        $this->addNodeElem($xml_summary, "TotalGrossAmount", $dataSummary['TotalGrossAmount']);

        if ($isCorrection) {
            $this->addNodeElem($xml_summary, "PreviousTotalNetAmount", $dataSummary['PreviousTotalNetAmount']);
            $this->addNodeElem($xml_summary, "PreviousTotalTaxableBasis", $dataSummary['PreviousTotalTaxableBasis']);
            $this->addNodeElem($xml_summary, "PreviousTotalTaxAmount", $dataSummary['PreviousTotalTaxAmount']);
            $this->addNodeElem($xml_summary, "PreviousTotalGrossAmount", $dataSummary['PreviousTotalGrossAmount']);
            $this->addNodeElem($xml_summary, "CorrectionTotalNetAmount", $dataSummary['CorrectionTotalNetAmount']);
            $this->addNodeElem($xml_summary, "CorrectionTotalTaxableBasis", $dataSummary['CorrectionTotalTaxableBasis']);
            $this->addNodeElem($xml_summary, "CorrectionTotalTaxAmount", $dataSummary['CorrectionTotalTaxAmount']);
            $this->addNodeElem($xml_summary, "CorrectionTotalGrossAmount", $dataSummary['CorrectionTotalGrossAmount']);

        }

        $this->addNodeElem($xml_summary, "GrossAmountInWords", $dataSummary['GrossAmountInWords'], true);

        $node_tax_sum = "Tax-Summary";
        $xml_tax_sum = $this->addNodeElem($xml_summary, $node_tax_sum);

        $node_tax_sum_l = "Tax-Summary-Line";
        $xml_tax_sum_l = $this->addNodeElem($xml_tax_sum, $node_tax_sum_l);

        $this->addNodeElem($xml_tax_sum_l, "TaxRate", $dataSummary[$node_tax_sum][$node_tax_sum_l]['TaxRate']);
        $this->addNodeElem($xml_tax_sum_l, "TaxCategoryCode", $dataSummary[$node_tax_sum][$node_tax_sum_l]['TaxCategoryCode']);
        $this->addNodeElem($xml_tax_sum_l, "TaxAmount", $dataSummary[$node_tax_sum][$node_tax_sum_l]['TaxAmount']);
        $this->addNodeElem($xml_tax_sum_l, "TaxableBasis", $dataSummary[$node_tax_sum][$node_tax_sum_l]['TaxableBasis']);
        $this->addNodeElem($xml_tax_sum_l, "TaxableAmount", $dataSummary[$node_tax_sum][$node_tax_sum_l]['TaxableAmount']);

        if ($isCorrection) {
            $this->addNodeElem($xml_tax_sum_l, "PreviousTaxRate", $dataSummary[$node_tax_sum][$node_tax_sum_l]['PreviousTaxRate']);
            $this->addNodeElem($xml_tax_sum_l, "PreviousTaxCategoryCode", $dataSummary[$node_tax_sum][$node_tax_sum_l]['PreviousTaxCategoryCode']);
            $this->addNodeElem($xml_tax_sum_l, "PreviousTaxAmount", $dataSummary[$node_tax_sum][$node_tax_sum_l]['PreviousTaxAmount']);
            $this->addNodeElem($xml_tax_sum_l, "PreviousTaxableAmount", $dataSummary[$node_tax_sum][$node_tax_sum_l]['PreviousTaxableAmount']);
            $this->addNodeElem($xml_tax_sum_l, "CorrectionTaxAmount", $dataSummary[$node_tax_sum][$node_tax_sum_l]['CorrectionTaxAmount']);
            $this->addNodeElem($xml_tax_sum_l, "CorrectionTaxableAmount", $dataSummary[$node_tax_sum][$node_tax_sum_l]['CorrectionTaxableAmount']);
        }

        $this->addNodeElem($xml_tax_sum_l, "GrossAmount", $dataSummary[$node_tax_sum][$node_tax_sum_l]['GrossAmount']);

        return $xml_summary;
    }

    /**
     * addNodeElem Creating new node element and putting data into this element
     *
     * @param DOMElement &$nodeObj
     * @param [type]     $nodeName
     * @param [type]     $value
     * @param boolean    $isCDATA
     *
     * @return DOMElement
     */
    private function addNodeElem(DOMElement &$nodeObj, $nodeName, $value = null, $isCDATA = false) : DOMElement
    {
        if (is_array($value) && empty($value)) $value = null;
        if ($isCDATA) {
            $node = $this->xml->createElement( $nodeName );
            $base = $nodeObj->appendChild( $node );
            $base->appendChild($this->xml->createCDATASection($value));

        } else {
            if (is_null($value)) {
                $node = $this->xml->createElement( $nodeName );
            } else {
                $node = $this->xml->createElement( $nodeName, $value );
            }
            $nodeObj->appendChild( $node );
        }

        return $node;
    }


    /**
     * removeXMLversion Removing header <?xml version="1.0"?> from first line of XML document
     * (Edison XML format has doesn't have this header...)
     *
     * @param  string $xmlData
     *
     * @return string
     */
    private function removeXMLversion(string $xmlData) : string
    {
        return str_replace("<?xml version=\"1.0\"?>\n", '', $xmlData);
    }

    /**
     * fixEmptyFields FIX empty data, try update from glossaries
     * @return void
     */
    private function fixEmptyFields() : void
    {
        $DeliveryName = $this->invoiceData['Invoice-Header']['Delivery']['Name'] ?? null;
        $DeliveryLocationNumber = $this->invoiceData['Invoice-Header']['Delivery']['DeliveryLocationNumber'] ?? null;
        $ParitesBuyerName = $this->invoiceData['Invoice-Parties']['Buyer']['Name'];

        // if $DeliveryLocationNumber is empty and is defined in glossary $this->DeliveriesGloss, get this number from glossary
        if (empty($DeliveryLocationNumber) && !empty($DeliveryName) && isset($this->DeliveriesGloss[$DeliveryName]))
            $this->invoiceData['Invoice-Header']['Delivery']['DeliveryLocationNumber'] = $this->DeliveriesGloss[$DeliveryName]['DeliveryLocationNumber'];

        // if $ParitesBuyerName is empty and is defined in glossary $this->DeliveriesGloss, get this name from glossary
        if (empty($ParitesBuyerName) && !empty($DeliveryName) && isset($this->DeliveriesGloss[$DeliveryName]))
            $this->invoiceData['Invoice-Parties']['Buyer']['Name'] = $this->DeliveriesGloss[$DeliveryName]['ParitesBuyerName'];
    }

    /**
     * generateXML Generating the XML content from created nodes
     *
     * @return string
     */
    public function generateXML() : string
    {


        $xml_root = $this->xml->createElement( "Document-Invoice" );


        $invoiceNum = $this->invoiceData['Invoice-Header']['InvoiceNumber'] ?? null;
        $isCorrection = !empty($invoiceNum) && preg_match('/^FKOR/',$invoiceNum);

        # fix empty data, try update from glossaries
        $this->fixEmptyFields();

        # header
        $xml_header = $this->createInvoiceHeader($xml_root, $this->invoiceData['Invoice-Header'] ?? []);

        # header parties
        $xml_parts = $this->createInvoiceParties($xml_root, $this->invoiceData['Invoice-Parties'] ?? []);

        # lines of invoice
        $xml_lines = $this->createInvoiceLines($xml_root, $this->invoiceData['Invoice-Lines']['Line'] ?? []);

        # summary
        $this->createInvoiceSummary($xml_root, $this->invoiceData['Invoice-Summary'] ?? [], $isCorrection);

        $this->xml->appendChild($xml_root);

        return $this->removeXMLversion(
            $this->xml->saveXML()
        );

    }


}