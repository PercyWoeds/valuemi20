# create a new Invoice object
require 'NubeFact'


invoice = NubeFact::Invoice.new({
                               serie: 'FF01',
                              numero: 247,
                   sunat_transaction: 1,
           cliente_tipo_de_documento: 6,
         cliente_numero_de_documento: '20514739065',
                cliente_denominacion: 'GRUPO E & E SAC',
                   cliente_direccion: 'AV. TUPAC AMARU KM. 22.5 LOTE. 7 URB. PUNCHAUCA CARABAYLLO',
                       cliente_email: 'grupoeye@hotmail.com',
                              moneda: 1,
                      tipo_de_cambio: 3.35,
                   porcentaje_de_igv: 18,
                       total_gravada: 169.49,
                           total_igv: 30.51,
                      total_inafecta: 0.00,
                               total: 200.00,
   enviar_automaticamente_a_la_sunat: true,
   enviar_automaticamente_al_cliente: false,
                        codigo_unico: 'ABC',
                      formato_de_pdf: 'A4'
})

# Add items
# You don't need to add the fields that are calculated like total or igv
# those got calculated automatically.

invoice.add_item({
  unidad_de_medida: 'NIU',
  descripcion: 'SERVICIO DE TRANSPORTE',
  cantidad: 1,
  valor_unitario: 169.49,
  tipo_de_igv: 1,
})

result = invoice.deliver
