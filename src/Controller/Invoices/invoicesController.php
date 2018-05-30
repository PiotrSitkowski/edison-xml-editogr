<?php
namespace Controller\Invoices;

use Framework;
use Controller\Controller;
use config;
use Helper\Helper;

use Http\Request;
use Http\Response;

use Model\Invoices\Files\Files;

use DOMDocument;
use DocumentXML\Edison;
use DocumentXML\Edison\InvoiceXMLImporter;
use DocumentXML\Edison\InvoiceXMLCreator;
use EditorXML\XMLResolver;


/**
 * invoicesController
 */
class invoicesController extends Controller
{

    private $request;

    function __construct(Request $request)
    {
        $this->request = $request;
        return new Response('Invoices');
    }


    // invoices/list
    public function listAllFilesAction() : Response
    {

        $files = new Files();
        $filesList = $files->getFilesList();

        $contentData = [
                'XMLfiles' => $filesList,
            ];

        return new Response(
                $this->render('invoices/invoicesList.tpl', $contentData)
            );
    }

    // invoices/edit/{xmlFileName}
    public function editInvoiceAction(string $xmlFileName) : Response
    {

        $files = new Files();
        $fileInfo = $files->isFileExists($xmlFileName);
        $isFound = count($fileInfo);


        $edisonImportXML = new InvoiceXMLImporter($xmlFileName);
        $XMLData = $edisonImportXML->getInvoiceData();

        if (!isset($XMLData['Invoice-Lines']['Line'][0])) {
            $tb = $XMLData['Invoice-Lines']['Line'];
            unset($XMLData['Invoice-Lines']['Line']);
            $XMLData['Invoice-Lines']['Line'][0] = $tb;
        }

        $contentData = [
                'filename' => $xmlFileName,
                'isFound' => $isFound,
                'fileInfo' => $fileInfo,
                'XMLData' => $XMLData
            ];

        return new Response(
                $this->render('invoices/invoiceEdit.tpl', $contentData)
            );
    }

    // invoices/save
    public function saveInvoiceAction()
    {
        // source form data
        $data = $this->request->getRequest() ?? [];

        $xmlFileName = $data['xmlfilename'] ?? null;

        if (empty($xmlFileName)) return new Response($this->render('invoices/invoiceEdit.tpl'));

        // get XML data from file
        $edisonImportXML = new InvoiceXMLImporter($xmlFileName);
        $XMLData = $edisonImportXML->getInvoiceData();

        // === Update XML data ===

        // update Invoice-Header
        $this->updateArrayData($XMLData['Invoice-Header'], $data['Invoice-Header']);

        // update Invoice-Parties
        $this->updateArrayData($XMLData['Invoice-Parties'], $data['Invoice-Parties']);

        // update Invoice-Lines
        if (!isset($XMLData['Invoice-Lines']['Line'][0])) {
            $tb = $XMLData['Invoice-Lines']['Line'];
            unset($XMLData['Invoice-Lines']['Line']);
            $XMLData['Invoice-Lines']['Line'][0] = $tb;
        }
        foreach ($data['Invoice-Lines']['Line'] as $srcFormKeyPos => $srcFormPosData) {
            foreach ($data['Invoice-Lines']['Line'][$srcFormKeyPos]['Line-Item'] as $srcFormKeyLineItem => $srcFormItemData) {

                if (isset($XMLData['Invoice-Lines']['Line'][$srcFormKeyPos]['Line-Item'][$srcFormKeyLineItem])) {

                    if (empty($srcFormItemData) && is_array($srcFormItemData)) $srcFormItemData = '';
                    $XMLData['Invoice-Lines']['Line'][$srcFormKeyPos]['Line-Item'][$srcFormKeyLineItem] = $srcFormItemData;

                }
            }
        }



        // generate XML content from XML data
        $XML = new InvoiceXMLCreator(new DOMDocument, $XMLData);
        $contentXML = $XML->generateXML();

        // save XML content
        $files = new Files();

        $newXMLfilename = rtrim($xmlFileName, '.sent');

        if ($files->deleteFile($xmlFileName)) {
            $files->saveToFile($contentXML, $newXMLfilename);
        }

        return $this->editInvoiceAction($newXMLfilename);

    }


    // invoices/search
    public function searchAction(?string $q = null) : Response
    {

        $query = empty($q)
            ? ($this->request->getQuery('q') ?? null)
            : $q;

        $XMLData = [];
        $isFound = false;

        if (!empty($query)) {

            $fileXML = str_replace('/', '_', strtoupper($query)) . '.xml';

            $files = new Files();
            $fileInfo = $files->isFileExists($fileXML);
            $isFound = count($fileInfo);
        }

        $contentData = [
                'query' => htmlspecialchars($query),
                'isFound' => $isFound,
                'XMLfiles' => [$fileInfo]
            ];

        return new Response(
                $this->render('invoices/searchResult.tpl', $contentData)
            );

    }


    private function updateArrayData(array &$sourceArray, array $data) : array
    {
        if (0 === count($sourceArray) || null === $sourceArray || 0 === count($data)) return $sourceArray;

        foreach ($data as $key => &$value) {
            // if (isset($sourceArray[$key]) && !is_array($value)) {
            if (!is_array($value)) {
                $sourceArray[$key] = $value;
            } else {
                // if (is_array($value)) $value = $this->updateArrayData($sourceArray[$key], $value);
                $value = $this->updateArrayData($sourceArray[$key], $value);
            }
        }
        return $sourceArray;
    }


}