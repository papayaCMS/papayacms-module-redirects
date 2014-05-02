<?php

class PapayaModuleRedirectsFilterHost extends PapayaFilterPcre {

  public function __construct() {
    parent::__construct(
      '(^
        (?:(?:[^./?#{}*]+|\\{[^./?#{}*]+\\}|\\*)\\.)*
        (?:[^./?#{}*]+|\\{[^./?#{}*]+\\}|\\*)
      $)Dix'
    );
  }
}