<?php

use Aivanov\SbisPHP\SbisClient;

require __DIR__.'/vendor/autoload.php';

$sbis = new SbisClient('5294000', 'toPvVJyf5h');

$filter = [
    'jsonrpc' => '2.0',
    'method' => 'СБИС.СписокДокументов',
    'params' => [
        'Фильтр' => [
            'ДатаС' => '29.03.2022',
            'Тип' => 'ДокОтгрВх',
            'Навигация' => [
                'РазмерСтраницы' => 200
            ]
        ]
    ],
    'id' => 0
];

try {
    $docs = $sbis->getDocuments()
        ->withFilter($filter)
        ->getDocumentsList();
} catch (\Aivanov\SbisPhp\Exceptions\SbisExceptions $e) {

}

echo "<pre>";
print_r($docs);