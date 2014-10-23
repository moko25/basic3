<?php
/*
		require_once("dompdf/dompdf_config.inc.php");
		 
		$html =
		  '<html><body>'.
		  '<h1>Halo, berikut alamat Anda : </h1>'.
		  '<p>Alamat lengkap Anda adalah : </p>'.
		  '</body></html>';
		 
		$dompdf = new DOMPDF();
		$dompdf->load_html($html);
		$dompdf->render();
		$dompdf->stream('laporan_.pdf');
		
*/		

require('fpdf17/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');
$pdf->Output();
		
		?>
