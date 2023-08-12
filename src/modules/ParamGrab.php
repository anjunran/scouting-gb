<?php
class ParamGrab
{
    private $urlParam;

    public function __construct()
    {
        $queryString = filter_input(INPUT_SERVER, 'QUERY_STRING', FILTER_SANITIZE_URL);
        parse_str($queryString, $params);
        $this->urlParam = $params['url'] ?? '';
    }

    public function getUrlParam()
    {
        return $this->urlParam;
    }
}
