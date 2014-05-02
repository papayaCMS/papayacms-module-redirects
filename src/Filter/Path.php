<?php

class PapayaModuleRedirectsFilterPath extends PapayaFilterPcre {

  public function __construct() {
    parent::__construct(
      '(^
        (?:/(?:[^{}/?#]*|\\{[^{}/?#]*\\})?)+
      $)Dix'
    );
  }
}