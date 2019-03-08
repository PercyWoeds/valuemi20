require_relative 'document_generator'

class InvoiceGenerator < DocumentGenerator
  attr_reader :items 
   

  #$lcAutorizacion1=$lcAutorizacion <<' Datos Adicionales GUIA DE REMISION : '<<$lcGuiaRemision

  def initialize(group, group_case, items, serie,numero)
    super(group, group_case)
    @items = items
    @serie = serie
    @numero = numero 
  end 

  def with_igv(pdf=false)
    invoice = document_class.new(data(@items))
    generate_documents(invoice, pdf)
    invoice
  end

  def with_igv2(pdf=false)
    invoice = document_class.new(data(@items))
    generate_documents2(invoice, pdf)
    invoice
  end
  
  def with_igv3(pdf=false)
    invoice = document_class.new(data(@items))
    generate_documents3(invoice, pdf)
    invoice
  end

  def exempt(pdf=false)
    invoice_data = data
    invoice_data[:lines] = (1..@items).map do |item|
      {id: item.to_s, quantity: 1.0, line_extension_amount: 10000, pricing_reference: 10000, price: 10000,
       item: {id: item.to_s, description: "Item #{item}"}, tax_totals: [{amount: 0, type: :igv, code: "20"}]}
    end
    invoice_data[:additional_monetary_totals] << {id: "1003", payable_amount: @items*10000}
    invoice = document_class.new(invoice_data)
    invoice.legal_monetary_total.value = invoice.legal_monetary_total.value + @items*10000
    generate_documents(invoice, pdf)
    invoice
  end
  
  def free(pdf=false)
    invoice_data = data
    invoice_data[:lines] = (1..@items).map do |item|
      {id: @items.to_s, quantity: 1.0, line_extension_amount: 0, pricing_reference: {amount: 10000, free: true}, price: 0,
       item: {id: @items.to_s, description: "Item #{@items}"}, tax_totals: [{amount: 0, type: :igv, code: "31"}]}
    end
    invoice_data[:additional_monetary_totals] << {id: "1004", payable_amount: @items*10000}
    invoice_data[:additional_properties] = [{id: "1002", value: "TRANSFERENCIA GRATUITA"}]
    invoice = document_class.new(invoice_data)
    generate_documents(invoice, pdf)
    invoice
  end

  def with_discount(pdf=false)
    invoice = document_class.new(data(@items))
    
    taxable_total = invoice.get_monetary_total_by_id("1001")
    discount = (taxable_total.payable_amount.value * 0.05).round
    taxable_total.payable_amount = taxable_total.payable_amount.value - discount
    new_tax_totals = {amount: (taxable_total.payable_amount.to_f * 18).round, type: :igv}

    invoice.allowance_total_amount = discount
    invoice.modify_monetary_total(taxable_total)
    invoice.add_additional_monetary_total({id: "2005", payable_amount: discount})
    invoice.tax_totals = [new_tax_totals]
    invoice.legal_monetary_total = invoice.total_tax_totals + taxable_total.payable_amount

    generate_documents(invoice, pdf)
    invoice
  end

  def with_isc(pdf=false)
    invoice_data = data(@items - 1)
    invoice_data[:lines] << {id: @items.to_s, quantity: 1.0, line_extension_amount: 10000, pricing_reference: 13500, price: 10000, 
                             item: {id: @items.to_s, description: "Item #{@items}"}, tax_totals: [{amount: 1800, type: :igv}, {amount: 1700, type: :isc}]}
    invoice = document_class.new(invoice_data)
    
    taxable_total = invoice.get_monetary_total_by_id("1001")
    taxable_total.payable_amount = taxable_total.payable_amount.value + 10000
    invoice.modify_monetary_total(taxable_total)

    invoice.legal_monetary_total.value = invoice.legal_monetary_total.value + 13500
    
    new_tax_totals = [{amount: invoice.total_tax_totals + SUNAT::PaymentAmount.new(1800), type: :igv}, {amount: 1700, type: :isc}]
    invoice.tax_totals = new_tax_totals

    generate_documents(invoice, pdf)
    invoice
  end

  def with_reception(pdf=false)
    invoice = document_class.new(data(@items))
    payable_amount = (invoice.legal_monetary_total.value * 0.02).round
    invoice.add_additional_monetary_total({id: "2001", reference_amount: invoice.legal_monetary_total, payable_amount: payable_amount, total_amount: invoice.legal_monetary_total.value + payable_amount})
    invoice.add_additional_property({id: "2000", value: "COMPROBANTE PERCEPCION"})
    generate_documents(invoice, pdf)
    invoice
  end

  def with_different_currency(pdf=false)
    invoice = document_class.new(data(@items, 'USD'))
    generate_documents(invoice, pdf)
    invoice
  end

  def with_different_currency2(pdf=false)
    invoice = document_class.new(data(@items, 'USD'))
    generate_documents2(invoice, pdf)
    invoice
  end

  def with_different_currency3(pdf=false)
    invoice = document_class.new(data(@items, 'USD'))
    generate_documents3(invoice, pdf)
    invoice
  end
  
  protected

  def document_class
    SUNAT::Invoice
  end

  
   
  #$lcid= "#{@serie}-#{"%06d" % @@document_serial_id }"
        
  protected
      def customer
       {legal_name:$lcLegalName , ruc: $lcRuc}
      end

  private
   

#   def data(items = 0, currency = 'PEN')
    
#     @boleta_detalle = Note.where(serie: @serie, numero: $lg_serial_id )
    
#     invoice_data = {id: "#{@serie}-#{"%06d" %  $lg_serial_id}", customer: customer, 
#     tax_totals: [{amount: {value: items*$lcIgv, currency: currency}, type: :igv}], legal_monetary_total: {value: $lcTotal * items, currency: currency}, 
#     additional_monetary_totals: [{id: "1001", payable_amount: {value: $lcVVenta * items, currency: currency}}]}

#       invoice_data[:lines] = []
      
#       nro_item = 1 
      
#       if @boleta_detalle.count > 0
        
#       @boleta_detalle.each do |item|
         
#                 lcPrecioCigv1  =  item.precio.round(2) * 100
#                 lcPrecioCigv2   = lcPrecioCigv1.round(0).to_f
#                 lcPrecioCigv   =  lcPrecioCigv2.to_i 
                
#                 lcPrecioSigv1  =  item.precio_sigv.round(2) * 100
#                 lcPrecioSigv2   = lcPrecioSigv1.round(0).to_f
#                 lcPrecioSIgv   =  lcPrecioSigv2.to_i 
                
#                 lcAmount      = item.importe.round(2) / 1.18 
#                 lcAmount0     = lcAmount.round(2)
#                 lcTax         = item.importe - lcAmount0
#                 lcTax0        = lcTax.round(2)
                
#                 lcVVenta1      =  lcAmount0 * 100        
#                 lcVVenta       =  lcVVenta1.round(0)
                    
#                 lcIgv1         =  lcTax0 * 100
#                 lcIgv          =  lcIgv1.round(0)
                
#                 lcCantidad     = item.cantidad
                
#                 puts "*********cantidad.."
#                 puts lcCantidad 
#                 puts lcPrecioCigv
#                 puts lcPrecioSIgv
#                 puts lcIgv 
#                 puts lcVVenta
#           # {:id=>"1", :quantity=>18.798, :line_extension_amount=>{:value=>18559, :currency=>"PEN"},
#           #:pricing_reference=>{:alternative_condition_price=>{:price_amount=>{:value=>116
#           # 5, :currency=>"PEN"}}}, :price=>{:value=>987, :currency=>"PEN"},
#           #:tax_totals=>[{:amount=>{:value=>21900, :currency=>"PEN"}, :type=>:igv}], :item=>{:id=>"1", :descriptio
#           # n=>"DIESEL B2 S50"}}      
                
#           invoice_data[:lines] =  {id: nro_item.to_s, quantity: lcCantidad , line_extension_amount: {value: lcVVenta, currency: currency}, 
#             pricing_reference: {alternative_condition_price: {price_amount: {value: lcPrecioCigv, currency: currency}}}, 
#           price: {value: lcPrecioSIgv, currency: currency}, 
#           tax_totals: [{amount: {value: lcIgv, currency: currency}, type: :igv}], 
#           item: {id: nro_item.to_s, description: item.descrip }}
           
#           puts invoice_data[:lines]
           
           
#         end
#         nro_item+=1 
#       end
#       invoice_data
#     end
# end 

 def data(items = 0, currency = 'PEN')
   
    @invoice1 = Note.select(:fecha,:ruc,:placa,:td,"SUM(vventa) as vventa","SUM(tax) as tax","SUM(importe) as importe").where(serie: @serie , numero: @numero).group(:serie,:numero)
    
    costs_sum_vventa = @invoice1.first.vventa
    costs_sum_tax = @invoice1.first.tax
    costs_sum_importe = @invoice1.first.importe 
    
    puts "*------------"
    puts @costs_sum_vventa
    puts @costs_sum_tax
    puts @costs_sum_importe
    
    
    @invoiceitems = Note.select(:cod_prod,:descrip,:ruc,"precio as price_discount","SUM(cantidad) as cantidad","SUM(importe) as total").where(serie: @serie , numero: @numero).group(:cod_prod,:precio)
    
        $lg_fecha   = @invoice1.first.fecha.to_date
         
        a = @serie
        b = @numero.rjust(6, '0')  
         
        lcVVenta1      =  costs_sum_vventa * 100        
        lcVVenta_a       =  lcVVenta1.round(0)
            
        lcIgv1         =  costs_sum_tax * 100
        lcIgv_a          =  lcIgv1.round(0)
        
        lcTotal1       =  costs_sum_importe * 100
        lcTotal_a        =  lcTotal1.round(0)
         
        puts "******"
        puts a 
        puts b 
        
        @lg_serie_factura = a  
        @lg_serial_id   = b.to_i
        
        $lcRuc          = @invoice1.first.ruc
        
        $lcTd           = @invoice1.first.td 
        
        $lcMail         = ""
        $lcMail2        = ""
        $lcMail3        = ""
        
        puts $lcTd
        puts $lcRuc 
        
        if $lcRuc == nil ||  $lcRuc == ""
          result = "CLIENTE GENERICO"
          result2 = "-"
         
        else 
        
         result  = PeruSunatRuc.name_from $lcRuc
           
         result2 = PeruSunatRuc.address_from   $lcRuc
         
         $lcRuc0 = $lcRuc 
         $lcLegalName= result 
         #result = "-"
         #result2 = "-"
      
          
       end 
       
       $lcNroDocCli = "00000000"   
        
                
         legal_name_spaces = result.lstrip    
        
        if legal_name_spaces == nil
            $lcLegalName    = legal_name_spaces
        else
            $lcLegalName = result.lstrip    
        end
        $lcDirCli       = result2.gsub(/\s+/," ").strip 
        $lcDisCli       = ""
        $lcProv         = ""
        $lcDep          = ""
        $lcPlaca        = @invoice1.first.placa
        
        puts @lg_serie_factura
        puts @lg_serial_id
        
        puts "***********total ****"
        puts lcIgv_a 
        puts lcTotal_a 
        puts lcVVenta_a
        
        
    invoice_data = {id: "#{@lg_serie_factura}-#{"%06d" %  @lg_serial_id}", customer: customer, 
    tax_totals: [{amount: {value: items* lcIgv_a, currency: currency}, type: :igv}], legal_monetary_total: {value: lcTotal_a * items, currency: currency}, 
    additional_monetary_totals: [{id: "1001", payable_amount: {value: lcVVenta_a * items, currency: currency}}]}

      invoice_data[:lines] = []
      nro_item = 1 
      
        for detalle_item in @invoiceitems
        
        lcDes1   = detalle_item.descrip.lstrip 
        lcCantidad  = detalle_item.cantidad 
        lcTotal0 = detalle_item.cantidad * detalle_item.price_discount
        lcTotal1 = lcTotal0 * 100
        lcTotal = lcTotal1.round(0)
        
        lcPrecio =  detalle_item.total   / detalle_item.cantidad   
        lcPrecioSIGV = lcPrecio /1.18
        lcValorVenta = detalle_item.total / 1.18
        lcTax = detalle_item.total - lcValorVenta
        
        lcPrecioCigv1  =  lcPrecio * 100
        if lcPrecioCigv1 > 0 
          lcPrecioCigv2   = lcPrecioCigv1.round(0).to_f
          lcPrecioCigv   =  lcPrecioCigv2.to_i 
        else
          lcPrecioCigv2 = 0
          lcPrecioCigv  = 0
        end
        if lcPrecioSIGV > 0
          lcPrecioSigv1  =  lcPrecioSIGV * 100
          lcPrecioSigv2   = lcPrecioSigv1.round(0).to_f
          lcPrecioSIgv   =  lcPrecioSigv2.to_i 
        else
          lcPrecioSigv1 = 0
          lcPrecioSigv2 = 0
          lcPrecioSIgv  = 0
        end 
        
              a   =  {id: nro_item.to_s, quantity: lcCantidad, line_extension_amount: {value: lcTotal, currency: currency}, 
           pricing_reference: {alternative_condition_price: {price_amount: {value: lcPrecioCigv, currency: currency}}}, 
           price: {value: lcPrecioSIgv, currency: currency}, tax_totals: [{amount: {value: lcTotal, currency: currency}, type: :igv}], 
           item: {id: nro_item.to_s, description: lcDes1}}
         
          invoice_data[:lines] << a 
          
          nro_item += 1 
         
      
      end 
      
      
      invoice_data
    
    end
end 

