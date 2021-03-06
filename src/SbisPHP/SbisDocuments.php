<?php

namespace Aivanov\SbisPHP;

use Aivanov\SbisPHP\SbisRequests;

class SbisDocuments
{

    private $filter = [];

    private $sbis_url = '';
    private $token = '';

    /**
     * @param string $sbis_url
     * @param string $token
     */
    public function __construct(string $sbis_url, string $token)
    {
        $this->sbis_url = $sbis_url;
        $this->token = $token;
    }

    /**
     * <code>
     *
     * </code>
     * @param array $filter
     * @return $this
     */
    public function withFilter(array $filter): SbisDocuments
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Return documents list
     * * <code>
     * $filter = [
     *   jsonrpc' => '2.0',
     *   'method' => 'СБИС.СписокДокументов',
     *   'params' => [
     *      'Фильтр' => [
     *          'ДатаС' => '29.03.2022',
     *          'Тип' => 'ДокОтгрВх',
     *          'Навигация' => [
     *              'РазмерСтраницы' => 200
     *          ]
     *      ]
     *    ],
     *   'id' => 0
     * ];
     * </code>
     * @param array $filter
     * @return array
     * @throws \Aivanov\SbisPHP\Exceptions\SbisExceptions
     */
    public function getDocumentsList(): array
    {

        $documents = SbisRequests::request('POST', $this->filter, $this->sbis_url, $this->token);

        $aDocuments = [];

        foreach ($documents->result->Документ as $document) {
            try {
                $aDocuments[] = [
                    'id' => $document->Идентификатор,
                    'date' => $document->Дата,
                    'createdAt' => $document->ДатаВремяСоздания,
                    'documentName' => $document->Название,
                    'documentDirection' => $document->Направление,
                    'documentNumber' => $document->Номер,
                    'documentType' => $document->Тип,
                    'documentSum' => $document->Сумма,
                    'documentDeleted' => $document->Удален,
                    'documentStatus' => $document->Состояние->Код,
                    'supplierInn' => !empty($document->Контрагент->СвЮЛ) ? $document->Контрагент->СвЮЛ->ИНН : $document->Контрагент->СвФЛ->ИНН,
                    'supplierKpp' => !empty($document->Контрагент->СвЮЛ) ? $document->Контрагент->СвЮЛ->КПП : '',
                    'supplierAddress' => !empty($document->Контрагент->СвЮЛ) ? $document->Контрагент->СвЮЛ->АдресЮридический : '',
                    'supplierName' => !empty($document->Контрагент->СвЮЛ) ? $document->Контрагент->СвЮЛ->Название :  $document->Контрагент->СвФЛ->Фамилия.' '.$document->Контрагент->СвФЛ->Имя,
                    'supplierType' => !empty($document->Контрагент->СвЮЛ) ? 'СвЮЛ' : 'СвФЛ',
                    'ourOrganiztionName' => !empty($document->НашаОрганизация->СвЮЛ) ? $document->НашаОрганизация->СвЮЛ->Название : $document->НашаОрганизация->СвФЛ->Название,
                    'ourOrganiztionInn' => !empty($document->НашаОрганизация->СвЮЛ) ? $document->НашаОрганизация->СвЮЛ->ИНН : $document->НашаОрганизация->СвФЛ->ИНН,
                    'ourOrganiztionKpp' => !empty($document->НашаОрганизация->СвЮЛ) ? $document->НашаОрганизация->СвЮЛ->КПП : $document->НашаОрганизация->СвФЛ->КПП,
                    'ourOrganiztionAddress' => !empty($document->НашаОрганизация->СвЮЛ) ? $document->НашаОрганизация->СвЮЛ->АдресЮридический : $document->НашаОрганизация->СвФЛ->АдресЮридический,
                ];
            } catch (\Exception $e){
                echo $e->getMessage();
                echo "<pre>";
                print_r($document);
            }
        }

        $object = json_decode(json_encode($aDocuments), FALSE);

        return $object;

    }


    /**
     * Return attach of document by ID
     * @param string $id
     * @return object
     * @throws \Aivanov\SbisPHP\Exceptions\SbisExceptions
     */
    public function readDocument(string $id): array
    {

        $filterForReadDocument = [
            'jsonrpc' => '2.0',
            'method' => 'СБИС.ПрочитатьДокумент',
            'params' => [
                'Документ' => [
                    'Идентификатор' => $id
                ]
            ],
            'id' => 0
        ];

        $documentAttaches = SbisRequests::request('POST', $filterForReadDocument, $this->sbis_url, $this->token);

        $aAttaches = [];

        foreach ($documentAttaches->result->Вложение as $attach){
            $aAttaches[] = [
                'name' => $attach->Название,
                'date' => $attach->Дата,
                'official' => $attach->Служебный,
                'id' => $attach->Идентификатор,
                'number' => $attach->Номер,
                'html' => $attach->СсылкаНаHTML,
                'pdf' => $attach->СсылкаНаPDF,
                'sum' => $attach->Сумма,
                'sumWithoutNds' => $attach->СуммаБезНДС,
                'type' => $attach->Тип,
                'xml' => $attach->Файл->Ссылка,
            ];
        }

        $docInfo = [];

        $docInfo[] = [
            'status' => $documentAttaches->result->Состояние->Код,
            'pdf' => $documentAttaches->result->СсылкаНаPDF,
            'archive' => $documentAttaches->result->СсылкаНаАрхив,
            'sum' => $documentAttaches->result->Сумма,
            'type' => $documentAttaches->result->Тип,
            'attaches' => $aAttaches
        ];

        $object = json_decode(json_encode($docInfo), FALSE);

        return $object;

    }

    /**
     * @throws Exceptions\SbisExceptions
     */
    public function getHtmlPdf(string $url){

        $documentAttaches = SbisRequests::fileRequest('GET', $url, $this->token);

        return $documentAttaches;

    }


    /**
     * Подготовит действие для документа по его ID
     * @param $docIndentifier
     * @param $actionName
     * @param $actionDocumentName
     * @param $imprint
     * @param $comment
     * @return mixed
     * @throws Exceptions\SbisExceptions
     */
    public function actionPrepare($docIndentifier, $actionName, $actionDocumentName, $imprint, $comment = ''){

        $filterForReadDocument = [
            'jsonrpc' => '2.0',
            'method' => 'СБИС.ПодготовитьДействие',
            'params' => [
                'Документ' => [
                    'Идентификатор' => $docIndentifier,
                    'Этап' => [
                        'Действие' => [
                            'Комментарий' => $comment,
                            'Название' => $actionName,
                            'Сертификат' => [
                                'Отпечаток' => $imprint,
                                'Ключ' => [
                                    'Тип' => 'Отложенный'
                                ],
                            ],
                        ],
                        'Название' => $actionDocumentName
                    ]
                ]
            ],
            'id' => 0
        ];

        $result = SbisRequests::request('POST', $filterForReadDocument, $this->sbis_url, $this->token);

        return $result;

    }


    /**
     * Подготовит действие для документа по его ID
     * @param $docIndentifier
     * @param $actionName
     * @param $actionDocumentName
     * @param $imprint
     * @param $comment
     * @return mixed
     * @throws Exceptions\SbisExceptions
     */
    public function actionRun($docIndentifier, $actionName, $actionDocumentName, $imprint, $comment = ''){

        $filterForReadDocument = [
            'jsonrpc' => '2.0',
            'method' => 'СБИС.ВыполнитьДействие',
            'params' => [
                'Документ' => [
                    'Идентификатор' => $docIndentifier,
                    'Этап' => [
                        'Действие' => [
                            'Комментарий' => $comment,
                            'Название' => $actionName,
                            'Сертификат' => [
                                'Отпечаток' => $imprint,
                                'Ключ' => [
                                    'Тип' => 'Отложенный'
                                ],
                            ],
                        ],
                        'Название' => $actionDocumentName
                    ]
                ]
            ],
            'id' => 0
        ];

        $result = SbisRequests::request('POST', $filterForReadDocument, $this->sbis_url, $this->token);

        return $result;

    }


    public function insertAttach($docIndentifier, $fileName, $fileUrl){

        $data = file_get_contents($fileUrl);

        $filterForReadDocument = [
            'jsonrpc' => '2.0',
            'method' => 'СБИС.ЗаписатьВложение',
            'params' => [
                'Документ' => [
                    'Идентификатор' => $docIndentifier,
                    'Вложение' => [
                        [
                            'Файл' => [
                                'Имя' => $fileName,
                                'ДвоичныеДанные' => base64_encode($data),
                            ]
                        ]
                    ]
                ],
            ],
            'id' => 0
        ];

        $result = SbisRequests::request('POST', $filterForReadDocument, $this->sbis_url, $this->token);

        return $result;
    }
}