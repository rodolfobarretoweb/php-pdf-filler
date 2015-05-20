<?php
final Class PhpPdfFiller {
  private $pdftk_path;

  public function __construct($pdftk_path = "/usr/local/bin/pdftk"){
    $this->pdftk_path = $pdftk_path;
  }

  private function createFDF($file, $info){
    $data = "%FDF-1.2\n%âãÏÓ\n1 0 obj\n<< \n/FDF << /Fields [ ";

    foreach($info as $field => $value) {
      if(is_array($value)) {
        $data .= '<</T('.$field.')/V[';

        foreach($value as $opt) {
          $data .= '('.trim($opt).')';
        }

        $data .= ']>>';
      } else {
         $data .= '<</T('.$field.')/V('.trim($value).')>>';
      }
    }

    $data .= "] \n/F (" . $file . ") /ID [ <" . md5(time()) . ">\n] >>";
    $data .= " \n>> \nendobj\ntrailer\n";
    $data .= "<<\n/Root 1 0 R \n\n>>\n%%EOF\n";

    return $data;
  }

  public function writeFile(array $params = array()) {
    $fdf_data = $this->createFDF($params['file'], $params['info']);

    $file = fopen($params['dir_file'] . $params['file_name'] . '.fdf', 'w');

    if($file && fwrite($file, $fdf_data, strlen($fdf_data))) {
      passthru("{$this->pdftk_path} A='{$params['file']}' fill_form '{$params['file_name']}.fdf' output '{$params['file_name']}.pdf' drop_xfa need_appearances");

      header('Content-type: application/pdf');
      echo file_get_contents("{$params['file_name']}.pdf");
    }

    fclose($file);
  }
}