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
     * @param array $filter
     * @return string
     * @throws \Aivanov\SbisPhp\Exceptions\SbisExceptions
     */
    public function getDocumentsList() : object {

        return SbisRequests::request('POST', $this->filter, $this->sbis_url, $this->token);

    }

}