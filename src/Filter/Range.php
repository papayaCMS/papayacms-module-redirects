<?php

class PapayaModuleRedirectsFilterRange extends PapayaFilterPcre {

  public function __construct() {
    parent::__construct(
      '(^
        (?:(?:\d+|\d+-\d+),)*
        (?:(?:\d+|\d+-\d+))
      $)Dix'
    );
  }
}