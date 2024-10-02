<?php

namespace App\Services;

use TCPDF;

class CustomPDF extends TCPDF
{
    public function Header()
    {
        $this->SetFont('helvetica', '', 8);
        $header = "
            <table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">
                <tr>
                    <td rowspan=\"3\" width=\"76%\"><img src=\"" . asset('logo.png') . "\" width=\"120\"></td>
                    <td width=\"10%\">Halaman</td>
                    <td width=\"2%\">:</td>
                    <td width=\"12%\">" . $this->getAliasNumPage() . " / " . $this->getAliasNbPages() . "</td>
                </tr>
                <tr>
                    <td>Dicetak</td>
                    <td>:</td>
                    <td>" . ucfirst(auth()->user()->name) . "</td>
                </tr>
                <tr>
                    <td>Tgl. Cetak</td>
                    <td>:</td>
                    <td>" . date('d-m-Y H:i') . "</td>
                </tr>
            </table>
            <hr>
        ";
        $this->writeHTML($header, true, false, false, false, '');
    }
}
