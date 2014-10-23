<?php
$obj = json_decode($_GET['pdf']);

require_once("dompdf/dompdf_config.inc.php");
/*	 
$html =
  '<html><body>'.
  '<h1>Halo, berikut alamat Anda : </h1>'.
  '<p>Alamat lengkap Anda adalah : </p>'.
  '</body></html>';
*/
		 
$dompdf = new DOMPDF();
$dompdf->load_html($obj->file);
$dompdf->render();

$dompdf->stream('laporan_.pdf');
echo "<script>alert('di download!'); </script>";

?>
