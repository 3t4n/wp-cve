<?php

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();
/**
 * undocumented class
 */
class WjsslSliderCodeWritter
{
    public function __construct($textWriter, $sliderCodeDocument)
    {
        $this->TextWriter = $textWriter;
        $this->SliderCodeDocument = $sliderCodeDocument;
    }

    public function WriteSliderDocument()
        {
            $this->_MultipleLines = $this->_SliderCodeDocument->MultipleLines;
            $this->_IndentLevel = $this->_SliderCodeDocument->IndentLevel;
            $this->_IndentSize = $this->_SliderCodeDocument->IndentSize;
            $this->_IndentChar = $this->_SliderCodeDocument->IndentChar;
            $this->_IndentChars = str_split($this->_IndentChar);

            if ($this->_SliderCodeDocument->OutputFullPage)
            {
                
                //write top part of page
                $this->_TextWriter->WriteLine("<!DOCTYPE html>");
                $this->_TextWriter->WriteLine("<html>");
                $this->_TextWriter->WriteLine("<head>");
                $this->_TextWriter->WriteLine('    <meta charset="utf-8">');
                $this->_TextWriter->WriteLine('    <meta name="viewport" content="width=device-width, initial-scale=1.0">');
                $this->WriteMeta();
                $this->_TextWriter->Write("    <title>");
                $this->_TextWriter->WriteLine("</title>");
                $this->_TextWriter->WriteLine("</head>");
                $this->_TextWriter->WriteLine('<body style="padding:0px; margin:0px; background-color:#fff;font-family:arial,helvetica,sans-serif,verdana,\'Open Sans\'">');

                $this->_IndentLevel++;
            }

            //$this->WriteSliderElements();
            $this->WriteNodes($this->_SliderCodeDocument->SliderNodes);

            if ($this->_SliderCodeDocument->OutputFullPage)
            {
                $this->_IndentLevel--;

                //write bottom part of page
                $this->WriteIndent();
                $this->_TextWriter->WriteLine("</body>");
                $this->_TextWriter->WriteLine("</html>");
            }
        }
}
