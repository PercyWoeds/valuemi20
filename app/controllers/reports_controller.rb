include ApplicationHelper




class ReportsController < ApplicationController
  before_action :authenticate_user!
# Report customers monthly

  def report_1 
    
    @pagetitle = "Reportes de Ventas"    
    
  end
  
  def rventas 
    
    @pagetitle = "Reportes de Ventas"    
    
  end
  def show
end 

  def report_monthly_customers
    
    @customer =  current_user.ruc
    @supplier = Client.last             
    @monedas = @supplier.get_monedas()
    
    if(params[:year] and params[:year].numeric?)
      @year = params[:year].to_i
    else
      @year = Time.now.year
    end
    
    if(params[:month] and params[:month].numeric?)
      @month = params[:month].to_i
    else
      @month = Time.now.month
    end
    
    curr_year = Time.now.year
    c_year = curr_year
    c_month = 1
    
    @years = []
    @months = monthsArr
    @month_name = @months[@month - 1][0]
    
    @pagetitle = "Monthly customers report - #{@customer} - #{@month_name} #{@year} "
    
    while(c_year > Time.now.year - 5)
      @years.push(c_year)
      c_year -= 1
    end
    
    @dates = []
    
    last_day_of_month = last_day_of_month(@year, @month)
    @date_cats = []
    
    i = 1
    
    while(i <= last_day_of_month)
      if(i < 10)
        i_s = "0#{i}"
      else
        i_s = i.to_s
      end
      
      @dates.push("#{@year}-#{@month}-#{i_s}")
      
      @date_cats.push("'" + doDate(Time.parse("#{@year}-#{@month}-#{i_s}"), 5) + "'")
      
      i += 1
    end
  end
  
  ##REPORTE DE FACTURAS EMITIDAS
  def build_pdf_header_rpt(pdf)
      pdf.font "Helvetica" , :size => 8

     $lcFecha1= Time.zone.now.strftime("%d/%m/%Y").to_s
     $lcHora  = Time.now.to_s

    max_rows = [client_data_headers_rpt.length, invoice_headers_rpt.length, 0].max
      rows = []
      (1..max_rows).each do |row|
        rows_index = row - 1
        rows[rows_index] = []
        rows[rows_index] += (client_data_headers_rpt.length >= row ? client_data_headers_rpt[rows_index] : ['',''])
        rows[rows_index] += (invoice_headers_rpt.length >= row ? invoice_headers_rpt[rows_index] : ['',''])
      end

      if rows.present?

        pdf.table(rows, {
          :position => :center,
          :cell_style => {:border_width => 0},
          :width => pdf.bounds.width
        }) do
          columns([0, 2]).font_style = :bold

        end

        pdf.move_down 10

      end
    
      pdf 
  end   

  
  
  def build_pdf_body_rpt(pdf)
    
    pdf.text "Documentos " +" Emitidas : desde "+@fecha1.to_s+ " Hasta: "+@fecha2.to_s , :size => 8 


    pdf.text ""
    pdf.font "Helvetica" , :size => 6

      headers = []
      table_content = []

      Invoice::TABLE_HEADERS2.each do |header|
        cell = pdf.make_cell(:content => header)
        cell.background_color = "FFFFCC"
        headers << cell
      end

      table_content << headers

      nroitem=1
      lcDoc='FT'
      

       for  product in @facturas_rpt

            row = []          
            row << lcDoc
            row << product.code
            row << product.fecha.strftime("%d/%m/%Y")            
            row << product.customer.name  
            if product.moneda_id == 1
              row << "USD"
            else
              row << "S/."
            end 

            row << product.subtotal.to_s
            row << product.tax.to_s
            row << product.total.to_s
            row << ""
            table_content << row

            nroitem=nroitem + 1
       
        end



      subtotals = []
      taxes = []
      totals = []
      services_subtotal = 0
      services_tax = 0
      services_total = 0

    if $lcFacturasall == '1'    
      subtotal = @supplier.get_facturas_day_value(@fecha1,@fecha2, "subtotal",@moneda)
      subtotals.push(subtotal)
      services_subtotal += subtotal          
      #pdf.text subtotal.to_s
    
    
      tax = @supplier.get_facturas_day_value(@fecha1,@fecha2, "tax",@moneda)
      taxes.push(tax)
      services_tax += tax
    
      #pdf.text tax.to_s
      
      total = @supplier.get_facturas_day_value(@fecha1,@fecha2, "total",@moneda)
      totals.push(total)
      services_total += total
      #pdf.text total.to_s

    else
        #total x cliente 
      subtotal = @supplier.get_facturas_day_value_cliente(@fecha1,@fecha2,@cliente, "subtotal",@moneda)
      subtotals.push(subtotal)
      services_subtotal += subtotal          
      #pdf.text subtotal.to_s
    
    
      tax = @supplier.get_facturas_day_value_cliente(@fecha1,@fecha2,@cliente, "tax",@moneda,)
      taxes.push(tax)
      services_tax += tax
    
      #pdf.text tax.to_s
      
      total = @supplier.get_facturas_day_value_cliente(@fecha1,@fecha2,@cliente,"total",@moneda,)
      totals.push(total)
      services_total += total
    
    end

      row =[]
      row << ""
      row << ""
      row << ""
      row << "TOTALES => "
      row << ""
      row << subtotal.round(2).to_s
      row << tax.round(2).to_s
      row << total.round(2).to_s
      row << ""
      table_content << row
      
      result = pdf.table table_content, {:position => :center,
                                        :header => true,
                                        :width => pdf.bounds.width
                                        } do 
                                          columns([0]).align=:center
                                          columns([1]).align=:left
                                          columns([2]).align=:left
                                          columns([3]).align=:left
                                          columns([4]).align=:left
                                          columns([5]).align=:right  
                                          columns([6]).align=:right
                                          columns([7]).align=:right
                                          columns([8]).align=:right
                                        end                                          
      pdf.move_down 10      

      #totales 

      pdf 

    end

    def build_pdf_footer_rpt(pdf)
      
                  
      pdf.text "" 
      pdf.bounding_box([0, 20], :width => 535, :height => 40) do
      pdf.draw_text "Company: #{@customer} - Created with: #{getAppName()} - #{getAppUrl()}", :at => [pdf.bounds.left, pdf.bounds.bottom - 20]

      end

      pdf
      
  end

  
  def report_view_monthly_customers
    
      # Export serviceorder to PDF
    $lcFacturasall = '1'

    @fecha1 = params[:month]    
    @fecha2 = params[:year]    
    @moneda = params[:moneda_id]
    
    if @moneda == 1
       @moneda = "S"  
    else
      @moneda = "D"  
    end
    
    @customer =  current_user.ruc
    @supplier = Client.last             
    @monedas = @supplier.get_monedas()
    
    
    @facturas_rpt = @supplier.get_facturas_day(@customer,@fecha1,@fecha2,@moneda)

#    respond_to do |format|
#      format.html    
#      format.xls # { send_data @products.to_csv(col_sep: "\t") }
#    end 

    Prawn::Document.generate("app/pdf_output/rpt_factura.pdf") do |pdf|
        pdf.font "Helvetica"
        pdf = build_pdf_header_rpt(pdf)
        pdf = build_pdf_body_rpt(pdf)
        build_pdf_footer_rpt(pdf)
        $lcFileName =  "app/pdf_output/rpt_factura.pdf"              
    end     
    $lcFileName1=File.expand_path('../../../', __FILE__)+ "/"+$lcFileName              
    send_file("app/pdf_output/rpt_factura.pdf", :type => 'application/pdf', :disposition => 'inline')


  end
  
   def client_data_headers_rpt
      client_headers  = [["Empresa  :", "INV.NOBAL S.A.C."]]
      client_headers << ["Direccion :", ""]
      client_headers
  end

  def invoice_headers_rpt            
      invoice_headers  = [["Fecha : ",$lcFecha1]]    
      invoice_headers << ["Hora :", $lcHora ]
      invoice_headers
  end



  
  
end 
