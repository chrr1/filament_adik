<?php

namespace App\Services;

use TCPDF;

class PdfService
{
    public function generatePdf($products)
    {
        // Membuat instance TCPDF
        $pdf = new TCPDF();

        // Menentukan informasi dokumen
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Nama Anda');
        $pdf->SetTitle('Daftar Produk');
        $pdf->SetSubject('Subjek PDF');
        $pdf->SetKeywords('TCPDF, PDF, produk, daftar');

        // Mengatur margin
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);

        // Menambahkan halaman
        $pdf->AddPage();

        // Menambahkan konten
        $html = '<h1>Daftar Produk</h1>';
        $html .= '<table border="1" cellpadding="5">';
        $html .= '<tr><th>ID</th><th>Nama Produk</th><th>Harga</th><th>Stok</th></tr>';

        foreach ($products as $product) {
            $html .= '<tr>';
            $html .= '<td>' . $product->id . '</td>';
            $html .= '<td>' . $product->NamaProduk . '</td>';
            $html .= '<td>' . $product->Harga . '</td>';
            $html .= '<td>' . $product->Stok . '</td>';
            $html .= '</tr>';
        }

        $html .= '</table>';

        // Menulis HTML ke PDF
        $pdf->writeHTML($html, true, false, true, false, '');

        // Menyimpan file PDF
        return $pdf->Output('daftar_produk.pdf', 'I'); // 'I' untuk menampilkan di browser
    }
}
