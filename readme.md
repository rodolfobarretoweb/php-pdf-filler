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
    $pdf = new PhpPdfFiller('path/to/pdftk');
    $pdf->writeFile('new_file_name", "base_file", array('name' => 'value'));
