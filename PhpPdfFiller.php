<?php
final Class PhpPdfFiller {
  private $pdftk_path;
  private $base_directory;
  private $pdf_base;
  private $pdf_names = array();

  public function __construct($pdf_base, $pdftk_path = '/usr/local/bin/pdftk', $base_directory = '/tmp/'){
    $this->pdftk_path     = $pdftk_path;
    $this->base_directory = $base_directory;
    $this->pdf_base       = $pdf_base;
  }

  # Create fdf file
  private function createFDF($data){
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

    $code .= "] \n/F (" . $this->pdf_base . ") /ID [ <" . md5(time()) . ">\n] >>";
    $code .= " \n>> \nendobj\ntrailer\n";
    $code .= "<<\n/Root 1 0 R \n\n>>\n%%EOF\n";

    return $code;
  }

  # Get pdf base, create fdf file and merge the content
  public function create($data) {
    $new_pdf_name = mt_rand();

    # create fdf file
    $fdf_file = $this->createFDF($data);
    $tmp_file = fopen($this->base_directory . $new_pdf_name . '.fdf', 'w');

    if($tmp_file && fwrite($tmp_file, $fdf_file, strlen($fdf_file))) {
      $command  = "{$this->pdftk_path} A='{$this->pdf_base}' ";
      $command .= "fill_form '" . $this->base_directory . $new_pdf_name . ".fdf' ";
      $command .= "output '" . $this->base_directory . $new_pdf_name . ".pdf' drop_xfa need_appearances";

      # Execute the command to merge pdf base with new fdf file
      passthru($command);
    }

    # Attached to array the name of pdf files
    $this->pdf_names = array_merge($this->pdf_names, array($new_pdf_name));

    fclose($tmp_file);
  }

  # Show the pdf
  public function display() {
    $final_pdf_name     = mt_rand() . ".pdf";
    $has_multiple_files = count($this->pdf_names) > 1;

    # Merge all files
    if($has_multiple_files) {
      $command = "{$this->pdftk_path} ";

      foreach($this->pdf_names as $pdf_name) {
        $command .= $this->base_directory . $pdf_name . ".pdf ";
      }

      $command .= " cat output " . $this->base_directory . $final_pdf_name;

      # Execute the commend for merge files
      passthru($command);
    } else {
      $final_pdf_name = $this->pdf_names[0] . ".pdf";
    }

    header('Content-type: application/pdf');
    echo file_get_contents($this->base_directory . $final_pdf_name);

    # Remove all files
    unlink($this->base_directory . $final_pdf_name);

    foreach($this->pdf_names as $pdf_name) {
      unlink($this->base_directory . $pdf_name . ".pdf");
      unlink($this->base_directory . $pdf_name . ".fdf");
    }
  }
}