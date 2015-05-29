# PHP PDF FILLER

PHP PDF FILLER is a simple library to import pdf files and write the content inside.

-------------------------------------------------------------------------------

### Credits
 * This library was based on: http://www.pentco.com/test/
 * With the help by: <juliobetta@gmail.com>

-------------------------------------------------------------------------------

### Requirements

##### Install with MAC
  * brew install https://raw.github.com/quantiverge/homebrew-binary/pdftk/pdftk.rb
  
  OR
  * https://github.com/caskroom/homebrew-cask
  * brew cask install pdftk

#### Install with Linux
  sudo apt-get install pdftk
  
-------------------------------------------------------------------------------

### EXAMPLE
    # By default the path of the PDFTK and TMP directory is: /usr/local/bin/pdftk | /tmp/
    $pdf = new PhpPdfFiller('pdf_base.pdf', 'path/to/pdftk', 'path/to/tmp/');
    
    # Single file
    $pdf->create(array('name' => 'value'));
    $pdf->display();
    
    # Multiple files
    $pdf->create(array('name' => 'value1'));
    $pdf->create(array('name' => 'value2'));
    $pdf->display();
