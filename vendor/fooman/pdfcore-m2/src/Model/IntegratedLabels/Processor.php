<?php
/**
 * @copyright Copyright (c) 2015 Fooman Limited (http://www.fooman.co.nz)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Fooman\PdfCore\Model\IntegratedLabels;

class Processor
{
    /**
     * @var \Fooman\PdfCore\Model\Tcpdf\Pdf
     */
    private $pdf;

    /**
     * @var \Fooman\PdfCore\Block\Pdf\DocumentRendererInterface
     */
    private $document;

    private $content;

    public function __construct(
        \Fooman\PdfCore\Block\Pdf\DocumentRendererInterface $document,
        \Fooman\PdfCore\Model\Tcpdf\Pdf $pdf
    ) {
        $this->pdf = $pdf;
        $this->document = $document;
    }

    public function process()
    {
        $this->content = $this->document->getIntegratedLabelsContent();
        $this->writeContent();
    }

    protected function writeContent()
    {
        if ($this->content->getLeft() && $this->content->getRight()) {
            $this->writeDouble($this->pdf, $this->content);
        } elseif ($this->content->getLeft()) {
            $this->writeSingle($this->pdf, $this->content);
        }
    }

    public function writeDouble($pdf, $content)
    {
        $pdf->SetAutoPageBreak(false);
        $pdf->SetXY(-180, -60);
        $pdf->writeHTMLCell(75, 0, null, null, $content->getLeft(), null, 0);
        $pdf->SetXY(-95, -60);
        $pdf->writeHTMLCell(75, $pdf->getLastH(), null, null, $content->getRight(), null, 1);
    }

    public function writeSingle($pdf, $content)
    {
        $pdf->SetAutoPageBreak(false);
        $pdf->SetXY(-180, -60);
        $pdf->writeHTMLCell(75, 0, null, null, $content->getLeft(), null, 0);
    }
}
