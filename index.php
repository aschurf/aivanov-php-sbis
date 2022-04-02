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
            'ДатаПо' => '29.03.2022',
            'Тип' => 'ДокОтгрВх',
            'Навигация' => [
                'РазмерСтраницы' => 200
            ]
        ]
    ],
    'id' => 0
];
try {

    $docs = $sbis->documents()
        ->withFilter($filter)
        ->getDocumentsList();
} catch (\Aivanov\SbisPHP\Exceptions\SbisExceptions $e) {

}

try {
    $doc = $sbis->documents()
        ->readDocument('35cc70cb-90ce-41e4-a8c1-89473c2d6422');
} catch (\Aivanov\SbisPHP\Exceptions\SbisExceptions $e) {

}

try {
    $html = $sbis->documents()->getHtmlPdf('https://online.sbis.ru/fedviewpublic/service/?method=%D0%A4%D0%AD%D0%94.SaveInHTML&params=eyLQmNC00J4iOiIxMjM3NiIsItCY0LzRj9Ce0LHRitC10LrRgtCwIjoi0JTQvtC60YPQvNC1%0A0L3RgiIsItCf0YDQtdC00YHRgtCw0LLQu9C10L3QuNC1Ijoi0J%2FRgNC%2B0YHQvNC%2B0YLRgCzQ%0An9C10YfQsNGC0YwiLCLQmNC80Y%2FQnNC10YLQvtC00LAiOiLQktC90LXRiNC90LjQudCU0L7Q%0AutGD0LzQtdC90YIuUmVhZFN0YW1wSW5mbyIsItCf0LDRgNCw0LzQtdGC0YDRi9Cc0LXRgtC%2B%0A0LTQsCI6eyJkIjpbIjEyMzc2Il0sInMiOlt7Im4iOiLQmNC00J4iLCJ0Ijoi0KHRgtGA0L7Q%0AutCwIn1dLCJfdHlwZSI6InJlY29yZCJ9LCLQn9Cw0YDQsNC80LXRgtGA0YsiOnsiZCI6W3Ry%0AdWUsItCS0L3QtdGI0L3QuNC50JTQvtC60YPQvNC10L3Rgi5SZWFkU3RhbXBJbmZvIix7ImQi%0AOlsib25saW5lIl0sInMiOlt7Im4iOiLQmNC80Y%2FQodC10YDQstC40YHQsCIsInQiOiLQodGC%0A0YDQvtC60LAifV0sIl90eXBlIjoicmVjb3JkIn0sIjM0MjQwODY3Mjk3OTE4NzMyNDEiLHsi%0AZCI6WyIxMjM3NiJdLCJzIjpbeyJuIjoi0JjQtNCeIiwidCI6ItCh0YLRgNC%2B0LrQsCJ9XSwi%0AX3R5cGUiOiJyZWNvcmQifV0sInMiOlt7Im4iOiLQndC%2B0YDQvNCw0LvQuNC30L7QstCw0YLR%0AjCIsInQiOiLQm9C%2B0LPQuNGH0LXRgdC60L7QtSJ9LHsibiI6ItCY0LzRj9Cc0LXRgtC%2B0LTQ%0AsCIsInQiOiLQodGC0YDQvtC60LAifSx7Im4iOiLQo9C00LDQu9C10L3QvdGL0LnQktGL0LfQ%0AvtCyIiwidCI6ItCX0LDQv9C40YHRjCJ9LHsibiI6IkNhY2hlSWQiLCJ0Ijoi0KHRgtGA0L7Q%0AutCwIn0seyJuIjoi0J%2FQsNGA0LDQvNC10YLRgNGL0JzQtdGC0L7QtNCwIiwidCI6ItCX0LDQ%0Av9C40YHRjCJ9XSwiX3R5cGUiOiJyZWNvcmQifX0%3D&protocol=3&id=0');
} catch (\Aivanov\SbisPHP\Exceptions\SbisExceptions $e) {
    echo $e->getMessage();
}


//$insert = $sbis->documents()->insertAttach('2ea32db7-aa46-45b3-ad2f-3f1d968a3767', 'Акт о расхождении', 'https://procob.s3.us-east-2.amazonaws.com/edo/xml.xml');

try {
    $podpisat = $sbis->documents()->actionPrepare('2ea32db7-aa46-45b3-ad2f-3f1d968a3767', 'Утвердить', 'Утверждение', 'FADB94700EC51523E6073B0CA5F1A902D2EA6D7C', '');
} catch (\Aivanov\SbisPHP\Exceptions\SbisExceptions $e) {

}

try {
    $podpisat = $sbis->documents()->actionRun('2ea32db7-aa46-45b3-ad2f-3f1d968a3767', 'Утвердить', 'Утверждение', 'FADB94700EC51523E6073B0CA5F1A902D2EA6D7C', '');
} catch (\Aivanov\SbisPHP\Exceptions\SbisExceptions $e) {

}

echo "<pre>";
print_r($podpisat);