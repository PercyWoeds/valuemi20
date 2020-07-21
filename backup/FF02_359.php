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



  array_push($data_array, [
                        "unidad_de_medida"          => "GLL",
                        "codigo"                    => "02",
                        "descripcion"               => "GAS 90",
                        "cantidad"                  => "299.49",
                        "valor_unitario"            => "10.16",
                        "precio_unitario"           => "11.99",
                        "descuento"                 => "",
                        "subtotal"                  => "3043.09",
                        "tipo_de_igv"               => "1",
                        "igv"                       => "547.76",
                        "total"                     => "3590.85",
                        "anticipo_regularizacion"   => "false",
                        "anticipo_documento_serie"  => "",
                        "anticipo_documento_numero" => ""

    ]);


  // Loop through query and push results into $someArray;
    array_push($data_array, [
                        "unidad_de_medida"          => "GLL",
                        "codigo"                    => "03",
                        "descripcion"               => "GAS 95",
                        "cantidad"                  => "188.98",
                        "valor_unitario"            => "10.93",
                        "precio_unitario"           => "12.90",
                        "descuento"                 => "",
                        "subtotal"                  => "2065.94",
                        "tipo_de_igv"               => "1",
                        "igv"                       => "371.87",
                        "total"                     => "2437.81",
                        "anticipo_regularizacion"   => "false",
                        "anticipo_documento_serie"  => "",
                        "anticipo_documento_numero" => ""
    ]);

 array_push($data_array, [
                        "unidad_de_medida"          => "GLL",
                        "codigo"                    => "04",
                        "descripcion"               => "GAS 98",
                        "cantidad"                  => "76.39",
                        "valor_unitario"            => "11.85",
                        "precio_unitario"           => "13.98",
                        "descuento"                 => "",
                        "subtotal"                  => "904.87",
                        "tipo_de_igv"               => "1",
                        "igv"                       => "162.88",
                        "total"                     => "1067.75",
                        "anticipo_regularizacion"   => "false",
                        "anticipo_documento_serie"  => "",
                        "anticipo_documento_numero" => ""
    ]);


    array_push($data_array, [
                        "unidad_de_medida"          => "GLL",
                        "codigo"                    => "05",
                        "descripcion"               => "DIESEL B5 S50",
                        "cantidad"                  => "1.62",
                        "valor_unitario"            => "10.44",
                        "precio_unitario"           => "12.32",
                        "descuento"                 => "",
                        "subtotal"                  => "16.95",
                        "tipo_de_igv"               => "1",
                        "igv"                       => "3.05",
                        "total"                     => "20.00",
                        "anticipo_regularizacion"   => "false",
                        "anticipo_documento_serie"  => "",
                        "anticipo_documento_numero" => ""
    ]);




$data = array(
    "operacion"				            => "generar_comprobante",
    "tipo_de_comprobante"               => "1",
    "serie"                             => "FF02",
    "numero"				            => "359",
    "sunat_transaction"			        => "1",
    "cliente_tipo_de_documento"		    => "6",
    "cliente_numero_de_documento"	    => "20600701691",
    "cliente_denominacion"              => "LINEA INTEGRAL DE SEGURIDAD S.A.C.",
    "cliente_direccion"                 => "CAL.8 MZA. C LOTE. 02 ASC. BRISAS DE SANTA ROSA 1ERA ETAPA SAN MARTIN DE PORRES  -LIMA ",
    "cliente_email"                     => "",
    "cliente_email_1"                   => "",
    "cliente_email_2"                   => "",
    "fecha_de_emision"                  => "2020-03-31",
    "fecha_de_vencimiento"              => "",
    "moneda"                            => "1",
    "tipo_de_cambio"                    => "",
    "porcentaje_de_igv"                 => "18.00",
    "descuento_global"                  => "",
    "descuento_global"                  => "",
    "total_descuento"                   => "",
    "total_anticipo"                    => "",
    "total_gravada"                     => "6030.85",
    "total_inafecta"                    => "",
    "total_exonerada"                   => "",
    "total_igv"                         => "1085.55",
    "total_gratuita"                    => "",
    "total_otros_cargos"                => "",
    "total"                             => "7116.40",
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
    "placa_vehiculo"                    => "2228-4F,8889-EA9564-EA,6945-FA",
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