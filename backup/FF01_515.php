<?php


// RUTA para enviar documentos
$ruta = "https://api.nubefact.com/api/v1/6bc7a205-1093-423c-90c5-b5eb9b2ed27a";

//TOKEN para enviar documentos
$token ="400eccc816634b9293f3fe4b3d05efe408c64642440344e3aefa8cefb86edd6d";
/*
#########################################################
#### PASO 2: GENERAR EL ARCHIVO PARA ENVIAR A NUBEFACT ####
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
# - MANUAL para archivo JSON en el link: https://goo.gl/WHMmSb
# - MANUAL para archivo TXT en el link: https://goo.gl/Lz7hAq
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
 */

$data_array = [];

  // Loop through query and push results into $someArray;
    

   array_push($data_array, [
                        "unidad_de_medida"          => "GLL",
                        "codigo"                    => "04",
                        "descripcion"               => "GAS 98",
                        "cantidad"                  => "21.60",
                        "valor_unitario"            => "11.9",
                        "precio_unitario"           => "14.0",
                        "descuento"                 => "",
                        "subtotal"                  => "255.60",
                        "tipo_de_igv"               => "1",
                        "igv"                       => "46.0",
                        "total"                     => "301.60",
                        "anticipo_regularizacion"   => "false",
                        "anticipo_documento_serie"  => "",
                        "anticipo_documento_numero" => ""

    ]);
    


   array_push($data_array, [
                        "unidad_de_medida"          => "GLL",
                        "codigo"                    => "05",
                        "descripcion"               => "DIESEL B5 S50",
                        "cantidad"                  => "564.50",
                        "valor_unitario"            => "10.6",
                        "precio_unitario"           => "12.50",
                        "descuento"                 => "",
                        "subtotal"                  => "5993.7",
                        "tipo_de_igv"               => "1",
                        "igv"                       => "1078.90",
                        "total"                     => "7072.60",
                        "anticipo_regularizacion"   => "false",
                        "anticipo_documento_serie"  => "",
                        "anticipo_documento_numero" => ""

    ]);
    

$data = array(
    "operacion"				            => "generar_comprobante",
    "tipo_de_comprobante"               => "1",
    "serie"                             => "FF01",
    "numero"				            => "515",
    "sunat_transaction"			        => "1",
    "cliente_tipo_de_documento"		    => "6",
    "cliente_numero_de_documento"	    => "20501889068",
    "cliente_denominacion"              => "EMPRESA DE TRANSPORTES YUVIEL S.A.",
    "cliente_direccion"                 => "JR. EL ANIS NRO. 3940 URB. NARANJAL (ALT KM 6 AUTOPISTA TUPAC AMARU) LIMA - LIMA - SAN MARTIN DE PORRES",
    "cliente_email"                     => "",
    "cliente_email_1"                   => "",
    "cliente_email_2"                   => "",
    "fecha_de_emision"                  => "2020-03-05",
    "fecha_de_vencimiento"              => "",
    "moneda"                            => "1",
    "tipo_de_cambio"                    => "",
    "porcentaje_de_igv"                 => "18.00",
    "descuento_global"                  => "",
    "descuento_global"                  => "",
    "total_descuento"                   => "",
    "total_anticipo"                    => "",
    "total_gravada"                     => "6249.3",
    "total_inafecta"                    => "",
    "total_exonerada"                   => "",
    "total_igv"                         => "1124.9",
    "total_gratuita"                    => "",
    "total_otros_cargos"                => "",
    "total"                             => "7374.2",
    "percepcion_tipo"                   => "",
    "percepcion_base_imponible"         => "",
    "total_percepcion"                  => "",
    "total_incluido_percepcion"         => "",
    "detraccion"                        => "false",
    "observaciones"                     => "",
    "documento_que_se_modifica_tipo"    => "",
    "documento_que_se_modifica_serie"   => "",
    "documento_que_se_modifica_numero"  => "",
    "tipo_de_nota_de_credito"           => "",
    "tipo_de_nota_de_debito"            => "",
    "enviar_automaticamente_a_la_sunat" => "true",
    "enviar_automaticamente_al_cliente" => "false",
    "codigo_unico"                      => "",
    "condiciones_de_pago"               => "",
    "medio_de_pago"                     => "",
    "placa_vehiculo"                    => "D8S-903,AHJ-708,C9Y-730
F6L-743,B6E-828,C6S-718
A6K-935,A4V-839,D9R-827
F7R-310,AAG-818,B6F-945
AAK-942,D9A-854,D8T-878
AHZ-378
",
    "orden_compra_servicio"             => "",
    "tabla_personalizada_codigo"        => "",
    "formato_de_pdf"                    => "",
    "items" => $data_array

);
	

$data_json = json_encode($data);

/*
#########################################################
#### PASO 3: ENVIAR EL ARCHIVO A NUBEFACT ####
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
# SI ESTÁS TRABAJANDO CON ARCHIVO JSON
# - Debes enviar en el HEADER de tu solicitud la siguiente lo siguiente:
# Authorization = Token token="8d19d8c7c1f6402687720eab85cd57a54f5a7a3fa163476bbcf381ee2b5e0c69"
# Content-Type = application/json
# - Adjuntar en el CUERPO o BODY el archivo JSON o TXT
# SI ESTÁS TRABAJANDO CON ARCHIVO TXT
# - Debes enviar en el HEADER de tu solicitud la siguiente lo siguiente:
# Authorization = Token token="8d19d8c7c1f6402687720eab85cd57a54f5a7a3fa163476bbcf381ee2b5e0c69"
# Content-Type = text/plain
# - Adjuntar en el CUERPO o BODY el archivo JSON o TXT
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
*/

//Invocamos el servicio de NUBEFACT
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $ruta);
curl_setopt(
	$ch, CURLOPT_HTTPHEADER, array(
	'Authorization: Token token="'.$token.'"',
	'Content-Type: application/json',
	)
);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS,$data_json);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$respuesta  = curl_exec($ch);
curl_close($ch);

/*
 #########################################################
#### PASO 4: LEER RESPUESTA DE NUBEFACT ####
+++++++++++++++++++++++++++++++++++++++++++++++++++++++
# Recibirás una respuesta de NUBEFACT inmediatamente lo cual se debe leer, verificando que no haya errores.
# Debes guardar en la base de datos la respuesta que te devolveremos.
# Escríbenos a soporte@nubefact.com o llámanos al teléfono: 01 468 3535 (opción 2) o celular (WhatsApp) 955 598762
# Puedes imprimir el PDF que nosotros generamos como también generar tu propia representación impresa previa coordinación con nosotros.
# La impresión del documento seguirá haciéndose desde tu sistema. Enviaremos el documento por email a tu cliente si así lo indicas en el archivo JSON o TXT.
+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
 */

$leer_respuesta = json_decode($respuesta, true);
if (isset($leer_respuesta['errors'])) {
	//Mostramos los errores si los hay
    echo $leer_respuesta['errors'];
} else {
	//Mostramos la respuesta
?>
<h2>RESPUESTA DE SUNAT</h2>
    <table border="1" style="border-collapse: collapse">
        <tbody>
            <tr><th>tipo:</th><td><?php echo $leer_respuesta['tipo_de_comprobante']; ?></td></tr>
            <tr><th>serie:</th><td><?php echo $leer_respuesta['serie']; ?></td></tr>
            <tr><th>numero:</th><td><?php echo $leer_respuesta['numero']; ?></td></tr>
            <tr><th>enlace:</th><td><?php echo $leer_respuesta['enlace']; ?></td></tr>
            <tr><th>aceptada_por_sunat:</th><td><?php echo $leer_respuesta['aceptada_por_sunat']; ?></td></tr>
            <tr><th>sunat_description:</th><td><?php echo $leer_respuesta['sunat_description']; ?></td></tr>
            <tr><th>sunat_note:</th><td><?php echo $leer_respuesta['sunat_note']; ?></td></tr>
            <tr><th>sunat_responsecode:</th><td><?php echo $leer_respuesta['sunat_responsecode']; ?></td></tr>
            <tr><th>sunat_soap_error:</th><td><?php echo $leer_respuesta['sunat_soap_error']; ?></td></tr>
            <tr><th>pdf_zip_base64:</th><td><?php echo $leer_respuesta['pdf_zip_base64']; ?></td></tr>
            <tr><th>xml_zip_base64:</th><td><?php echo $leer_respuesta['xml_zip_base64']; ?></td></tr>
            <tr><th>cdr_zip_base64:</th><td><?php echo $leer_respuesta['cdr_zip_base64']; ?></td></tr>
            <tr><th>codigo_hash:</th><td><?php echo $leer_respuesta['cadena_para_codigo_qr']; ?></td></tr>
            <tr><th>codigo_hash:</th><td><?php echo $leer_respuesta['codigo_hash']; ?></td></tr>
        </tbody>
    </table>
<?php
}