<?php

class PapayaModuleRedirectsFilterQuerystring extends PapayaFilterPcre {

  public function __construct() {
    parent::__construct(
      '(^
         (?:
           (?:^|&)
           (?:[^=?#&]+(?:=([^=?#{}&]*|\\{[^=?#&{}]+\\}))?)
         )+
      $)Dix'
    );
  }
}