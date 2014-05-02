<?php

class PapayaModuleRedirectsRuleMatch extends PapayaObject {

  public function __construct(array $configuration, array $placeholders) {
    $this->_configuration = $configuration;
    $this->_placeholders = $placeholders;
  }

  public function getReference() {
    $configuration = $this->_configuration;
    $placeholders = $this->_placeholders;
    $data = array();
    $mode = PapayaUtilArray::get($configuration, 'mode', 'url');
    $data['scheme'] = $this->prepareValue(
      $placeholders,
      PapayaUtilArray::get(
        array(
          PapayaUtilServerProtocol::BOTH => '',
          PapayaUtilServerProtocol::HTTP => 'http',
          PapayaUtilServerProtocol::HTTPS => 'https'
        ),
        PapayaUtilArray::get($configuration, 'scheme', 0),
        ''
      ),
      PapayaUtilArray::get($placeholders, '_scheme', ''),
      ''
    );
    $data['host'] = $this->prepareValue(
      $placeholders,
      PapayaUtilArray::get($configuration, 'host', ''),
      PapayaUtilArray::get($placeholders, '_host', ''),
      ''
    );
    $data['path'] = $this->prepareValue(
      $placeholders,
      PapayaUtilArray::get($configuration, 'path', ''),
      PapayaUtilArray::get($placeholders, '_path', ''),
      ''
    );
    $data['page_id'] = $this->prepareValue(
      $placeholders,
      PapayaUtilArray::get($configuration, 'page_id', 0),
      PapayaUtilArray::get($placeholders, '_page_id', 0),
      0
    );
    $data['category_id'] = $this->prepareValue(
      $placeholders,
      PapayaUtilArray::get($configuration, 'category_id', ''),
      PapayaUtilArray::get($placeholders, '_category_id', ''),
      0
    );
    $data['language'] = $this->prepareValue(
      $placeholders,
      PapayaUtilArray::get($configuration, 'language', ''),
      PapayaUtilArray::get($placeholders, '_language', ''),
      ''
    );
    $data['extension'] = $this->prepareValue(
      $placeholders,
      PapayaUtilArray::get($configuration, 'extension', ''),
      PapayaUtilArray::get($placeholders, '_extension', ''),
      ''
    );
    $data['querystring'] = $this->prepareValue(
      $placeholders,
      PapayaUtilArray::get($configuration, 'querystring', ''),
      '',
      ''
    );
    $data['fragment'] = $this->prepareValue(
      $placeholders,
      PapayaUtilArray::get($configuration, 'fragment', ''),
      '',
      ''
    );
    switch ($mode) {
    case 'page' :
      $reference = new PapayaUiReferencePage();
      $reference->papaya($this->papaya());
      $reference->setPageId($data['page_id']);
      $reference->setCategoryId($data['category_id']);
      $reference->setPageLanguage($data['language']);
      $reference->setOutputMode($data['extension']);
      $reference->setPreview(FALSE);
      break;
    case 'url' :
    default :
      $reference = new PapayaUiReference();
      $reference->papaya($this->papaya());
      $reference->url()->setPath($data['path']);
      break;
    }
    $reference->url()->setScheme($data['scheme']);
    $reference->url()->setHost($data['host']);
    if (PapayaUtilArray::get($configuration, 'qsa', 0)) {
      $query = new PapayaRequestParametersQuery();
      $query->setString(PapayaUtilArray::get($placeholders, '_querystring'));
      $parameters = $query->values();
    } else {
      $parameters = new PapayaRequestParameters();
    }
    if (!empty($data['querystring'])) {
      $query = new PapayaRequestParametersQuery();
      $query->setString($data['querystring']);
      $parameters->merge($query->values());
    }
    $reference->setParameters($parameters);
    $reference->url()->setFragment($data['fragment']);
    return $reference;
  }

  private function prepareValue($values, $fromConfiguration, $fromMatch, $default) {
    if ($fromConfiguration === '0' || (!empty($fromConfiguration) && $fromConfiguration != '*')) {
      $option = $fromConfiguration;
    } elseif (!empty($fromMatch) && $fromMatch != '*') {
      $option = $fromMatch;
    } else {
      $option = $default;
    }
    if (empty($option)) {
      return '';
    } elseif (is_array($values)) {
      $result = new PapayaUiStringPlaceholders((string)$option, $values);
      return (string)$result;
    } else {
      return (string)$option;
    }
  }

}