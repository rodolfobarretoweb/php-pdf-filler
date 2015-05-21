<?php
final Class PhpPdfFiller {
  private $pdftk_path;
  private $new_file_directory;

  public function __construct($pdftk_path = '/usr/local/bin/pdftk', $new_file_directory = '/tmp/'){
    $this->pdftk_path         = $pdftk_path;
    $this->new_file_directory = $new_file_directory;
  }

  private function createFDF($base_file, $data){
    $code = "%FDF-1.2\n%âãÏÓ\n1 0 obj\n<< \n/FDF << /Fields [ ";

    foreach($data as $field => $value) {
      if(is_array($value)) {
        $code .= '<</T('.$field.')/V[';

        foreach($value as $opt) {
          $code .= '('.trim($opt).')';
        }

        $code .= ']>>';
      } else {
         $code .= '<</T('.$field.')/V('.trim($value).')>>';
      }
    }

    $code .= "] \n/F (" . $base_file . ") /ID [ <" . md5(time()) . ">\n] >>";
    $code .= " \n>> \nendobj\ntrailer\n";
    $code .= "<<\n/Root 1 0 R \n\n>>\n%%EOF\n";

    return $code;
  }

  public function writeFile($new_file_name, $base_file, $data) {
    $new_file = $this->new_file_directory . $new_file_name;

    $fdf_file = $this->createFDF($base_file, $data);
    $tmp_file = fopen($new_file . '.fdf', 'w');

    if($tmp_file && fwrite($tmp_file, $fdf_file, strlen($fdf_file))) {
      $command  = "{$this->pdftk_path} A='{$base_file}' ";
      $command .= "fill_form '{$new_file}.fdf' ";
      $command .= "output '{$new_file}.pdf' drop_xfa need_appearances";

      passthru($command);

      header('Content-type: application/pdf');
      echo file_get_contents("{$new_file}.pdf");
    }

    fclose($file);
  }
}