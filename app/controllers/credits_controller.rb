
include ApplicationHelper

class CreditsController < ApplicationController

  before_filter :authenticate_user!


    # Export invoice to PDF
  def pdf
    @invoice = Credit.find(params[:id])
    respond_to do |format|
      format.html { redirect_to("/credits/pdf/#{@invoice.id}.pdf") }
      format.pdf { render :layout => false }
    end
  end
  
  # Process an invoice
  def do_process
    @invoice =Credit.find(params[:id])
    @invoice[:processed] = "1"
    @invoice.process
    
    flash[:notice] = "The invoice order has been processed."
    redirect_to @invoice
  end
  
  # Do send invoice via email
  def do_email
    @invoice = Credit.find(params[:id])
    @email = params[:email]
    
    Notifier.invoice(@email, @invoice).deliver
    
    flash[:notice] = "The invoice has been sent successfully."
    redirect_to "/facturas/#{@invoice.id}"
  end
  
  # Send invoice via email
  def email
    @invoice = Credit.find(params[:id])
    @company = @invoice.company
  end

  def do_anular
    @invoice = Credit.find(params[:id])
    @invoice[:processed] = "2"
    
    @invoice.anular 
    @invoice.delete_guias()
  
    flash[:notice] = "Documento a sido anulado."
    redirect_to @invoice 
  end
  
  
  # List items
  def list_items
      
    items = params[:items]
    items = items.split(",")
    items_arr = []
    @products = []
    i = 0

    for item in items
      if item != ""
        parts = item.split("|BRK|")
        
        id = parts[0]
        quantity = parts[1]
        price = parts[2]
        discount = parts[3]
        
        product = Product.find(id.to_i)
        product[:i] = i
        product[:quantity] = quantity.to_f
        product[:price] = price.to_f
        product[:discount] = discount.to_f
        
        total = product[:price] * product[:quantity]
        total -= total * (product[:discount] / 100)
        
        product[:currtotal] = total
        
        @products.push(product)
      end
      
      i += 1
   end
    
    render :layout => false
  end
  
  # Autocomplete for products
  # Autocomplete for products
  def ac_products
    @products = Product.where(["(code LIKE ? OR name LIKE ?)",  "%" + params[:q] + "%", "%" + params[:q] + "%"])
   
    render :layout => false
  end
  
  # Autocomplete for users
  def ac_user
    company_users = CompanyUser.where(company_id: params[:company_id])
    user_ids = []
    
    for cu in company_users
      user_ids.push(cu.user_id)
    end
    
    @users = User.where(["id IN (#{user_ids.join(",")}) AND (email LIKE ? OR username LIKE ?)", "%" + params[:q] + "%", "%" + params[:q] + "%"])
    alr_ids = []
    
    for user in @users
      alr_ids.push(user.id)
    end
    
    if(not alr_ids.include?(getUserId()))
      @users.push(current_user)
    end
   
    render :layout => false
  end
  
  # Autocomplete for customers
  def ac_customers
    @customers = Client.where(["(ruc LIKE ? )",  "%" + params[:q] + "%"])
   
    render :layout => false
  end
  
  # Show invoices for a company
  def list_invoices

        @pagetitle = "Notas "
        
         @invoices = Credit.all.order('id DESC').paginate(:page => params[:page])



        if params[:search]
          @invoices = Credit.search(params[:search]).order('id DESC').paginate(:page => params[:page])
        else
          @invoices = Credit.all.order('id DESC').paginate(:page => params[:page]) 
        end

    
  end
  
  # GET /invoices
  # GET /invoices.xml
  def index

    
    @path = 'factura'
    @pagetitle = "Facturas"


  end


  # GET /invoices/1
  # GET /invoices/1.xml
  def show
    @invoice = Credit.find(params[:id])
    @customer = @invoice.customer
    
  end

  # GET /invoices/new
  # GET /invoices/new.xml
  
  def new
    @pagetitle = "Nueva factura"
    @action_txt = "Create"

    @notas = Notum.all 
    @products = Product.all 

    @invoice = Credit.new
    @invoice[:code] = "#{generate_guid()}"
    @invoice[:processed] = false
    
  end

  # GET /invoices/1/edit
  def edit
    @pagetitle = "Edit invoice"
    @action_txt = "Update"
    
    @invoice = Credit.find(params[:id])    

    @ac_customer = @invoice.customer.name
    @ac_user = @invoice.user.username

    @payments = @company.get_payments()    
    @services = @company.get_services()
    @deliveryships = @invoice.my_deliverys 

    @products_lines = @invoice.products_lines
    
    
  end

  # POST /invoices
  # POST /invoices.xml
  def create
    @pagetitle = "Nueva factura "
    @action_txt = "Create"
    
    items = params[:items].split(",")


    @invoice = Credit.new(factura_params)
        

    @payments = @company.get_payments()    
    @services = @company.get_services()

    @invoice[:subtotal] = @invoice.get_subtotal(items)
    
    begin
      @invoice[:tax] = @invoice.get_tax(items, @invoice[:customer_id])
    rescue
      @invoice[:tax] = 0
    end
    
    @invoice[:total] = @invoice[:subtotal] + @invoice[:tax]
    
    
    
    respond_to do |format|
      if @invoice.save
        # Create products for kit
        @invoice.add_products(items)
        @invoice.add_guias(items2)
        @invoice.correlativo
        # Check if we gotta process the invoice
        @invoice.process()

        
        format.html { redirect_to(@invoice, :notice => 'Invoice was successfully created.') }
        format.xml  { render :xml => @invoice, :status => :created, :location => @invoice }
      else
        format.html { render :action => "new" }
        format.xml  { render :xml => @invoice.errors, :status => :unprocessable_entity }
      end
    end
  end
  

  # PUT /invoices/1
  # PUT /invoices/1.xml
  def update
    @pagetitle = "Edit invoice"
    @action_txt = "Update"
    
    items = params[:items].split(",")
    
    @invoice = Credit.find(params[:id])
    @company = @invoice.company
    @payments = @company.get_payments()    
    if(params[:ac_customer] and params[:ac_customer] != "")
      @ac_customer = params[:ac_customer]
    else
      @ac_customer = @invoice.customer.name
    end
    
    @products_lines = @invoice.products_lines
        
    @invoice[:subtotal] = @invoice.get_subtotal(items)
    @invoice[:tax] = @invoice.get_tax(items, @invoice[:customer_id])
    @invoice[:total] = @invoice[:subtotal] + @invoice[:tax]

    respond_to do |format|
      if @invoice.update_attributes(factura_params)
        # Create products for kit
        @invoice.delete_products()
        @invoice.add_products(items)
        @invoice.correlativo
        # Check if we gotta process the invoice
        @invoice.process()
        
        format.html { redirect_to(@invoice, :notice => 'Invoice was successfully updated.') }
        format.xml  { head :ok }
      else
        format.html { render :action => "edit" }
        format.xml  { render :xml => @invoice.errors, :status => :unprocessable_entity }
      end
    end
  end

  # DELETE /invoices/1
  # DELETE /invoices/1.xml
  def destroy
    @invoice = Credit.find(params[:id])
    company_id = @invoice[:company_id]
    if @invoice.destroy
      @invoice.delete_guias()
    end   


    respond_to do |format|
      format.html { redirect_to("/companies/facturas/" + company_id.to_s) }
    end

  end


# reporte completo
  def build_pdf_header_rpt(pdf)
      pdf.font "Helvetica" , :size => 8
     $lcCli  =  @company.name 
     $lcdir1 = @company.address1+@company.address2+@company.city+@company.state

     $lcFecha1= Date.today.strftime("%d/%m/%Y").to_s
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
    
    pdf.text "Facturas Moneda" +" Emitidas : desde "+@fecha1.to_s+ " Hasta: "+@fecha2.to_s , :size => 8 

    if @moneda == 2
      pdf.text "Moneda : SOLES "
    else 
      pdf.text "Moneda : DOLARES "
    end 

    pdf.text ""
    pdf.font "Helvetica" , :size => 6

      headers = []
      table_content = []

      Factura::TABLE_HEADERS2.each do |header|
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
      subtotal = @company.get_facturas_day_value(@fecha1,@fecha2, "subtotal",@moneda)
      subtotals.push(subtotal)
      services_subtotal += subtotal          
      #pdf.text subtotal.to_s
    
    
      tax = @company.get_facturas_day_value(@fecha1,@fecha2, "tax",@moneda)
      taxes.push(tax)
      services_tax += tax
    
      #pdf.text tax.to_s
      
      total = @company.get_facturas_day_value(@fecha1,@fecha2, "total",@moneda)
      totals.push(total)
      services_total += total
      #pdf.text total.to_s

    else
        #total x cliente 
      subtotal = @company.get_facturas_day_value_cliente(@fecha1,@fecha2,@cliente, "subtotal")
      subtotals.push(subtotal)
      services_subtotal += subtotal          
      #pdf.text subtotal.to_s
    
    
      tax = @company.get_facturas_day_value_cliente(@fecha1,@fecha2,@cliente, "tax")
      taxes.push(tax)
      services_tax += tax
    
      #pdf.text tax.to_s
      
      total = @company.get_facturas_day_value_cliente(@fecha1,@fecha2,@cliente, "total")
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
      pdf.draw_text "Company: #{@company.name} - Created with: #{getAppName()} - #{getAppUrl()}", :at => [pdf.bounds.left, pdf.bounds.bottom - 20]

      end

      pdf
      
  end

##### reporte de pendientes de pago..

  def build_pdf_header_rpt2(pdf)
      pdf.font "Helvetica" , :size => 8
     $lcCli  =  @company.name 
     $lcdir1 = @company.address1+@company.address2+@company.city+@company.state

     $lcFecha1= Date.today.strftime("%d/%m/%Y").to_s
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

  def build_pdf_body_rpt2(pdf)
    
    pdf.text "Cuentas por cobrar  : desde "+@fecha1.to_s+ " Hasta: "+@fecha2.to_s , :size => 8 
    pdf.text ""
    pdf.font "Helvetica" , :size => 6

      headers = []
      table_content = []

      Factura::TABLE_HEADERS3.each do |header|
        cell = pdf.make_cell(:content => header)
        cell.background_color = "FFFFCC"
        headers << cell
      end

      table_content << headers

      nroitem=1
      lcmonedasoles   = 2
      lcmonedadolares = 1
    

      lcDoc='FT'

      

       lcCliente = @facturas_rpt.first.customer_id 

       for  product in @facturas_rpt
        
          if lcCliente == product.customer_id

             #if product.payment_id == nil 
              fechas2 = product.fecha2 
             #else 
             # days = product.payment.day 
             # fechas2 = product.fechas2 + days.days              
             #end 

            row = []          
            row << lcDoc
            row << product.code
            row << product.fecha.strftime("%d/%m/%Y")
            row << product.fecha2.strftime("%d/%m/%Y")
            row << product.customer.name
            row << product.moneda.symbol  

            if product.moneda_id == 1 
                row << "0.00 "
                row << sprintf("%.2f",product.balance.to_s)
            else
                row << sprintf("%.2f",product.balance.to_s)
                row << "0.00 "
            end 
            row << product.get_vencido 

            
            table_content << row

            nroitem = nroitem + 1

          else
            totals = []            
            total_cliente_soles = 0
            total_cliente_soles = @company.get_pendientes_day_customer(@fecha1,@fecha2, lcCliente, lcmonedadolares)
            total_cliente_dolares = 0
            total_cliente_dolares = @company.get_pendientes_day_customer(@fecha1,@fecha2, lcCliente, lcmonedasoles)
            
            row =[]
            row << ""
            row << ""
            row << ""
            row << ""          
            row << "TOTALES POR CLIENTE=> "            
            row << ""
            row << sprintf("%.2f",total_cliente_dolares.to_s)
            row << sprintf("%.2f",total_cliente_soles.to_s)
            row << " "
            
            table_content << row

            lcCliente = product.customer_id

            row = []          
            row << lcDoc
            row << product.code
            row << product.fecha.strftime("%d/%m/%Y")
            row << product.fecha2.strftime("%d/%m/%Y")
            row << product.customer.name
            row << product.moneda.symbol  

            if product.moneda_id == 1 
                row << "0.00 "
                row << sprintf("%.2f",product.balance.to_s)
            else
                row << sprintf("%.2f",product.balance.to_s)
                row << "0.00 "
            end 
            row << product.observ

            
            table_content << row



          end 
          
       
        end

        lcCliente = @facturas_rpt.last.customer_id 

            totals = []            
            total_cliente = 0

            total_cliente_soles = 0
            total_cliente_soles = @company.get_pendientes_day_customer(@fecha1,@fecha2, lcCliente, lcmonedadolares)
            total_cliente_dolares = 0
            total_cliente_dolares = @company.get_pendientes_day_customer(@fecha1,@fecha2, lcCliente, lcmonedasoles)
    
            
            row =[]
            row << ""
            row << ""
            row << ""
            row << ""          
            row << "TOTALES POR CLIENTE=> "            
            row << ""
            row << sprintf("%.2f",total_cliente_dolares.to_s)
            row << sprintf("%.2f",total_cliente_soles.to_s)                      
            row << " "
            table_content << row
              
          total_soles = @company.get_pendientes_day_value(@fecha1,@fecha2, "total",lcmonedasoles)
          total_dolares = @company.get_pendientes_day_value(@fecha1,@fecha2, "total",lcmonedadolares)
      
           if $lcxCliente == "0" 

          row =[]
          row << ""
          row << ""
          row << ""
          row << ""
          row << "TOTALES => "
          row << ""
          row << sprintf("%.2f",total_soles.to_s)
          row << sprintf("%.2f",total_dolares.to_s)                    
          row << " "
          table_content << row
          end 

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

    def build_pdf_footer_rpt2(pdf)      
                  
      pdf.text "" 
      pdf.bounding_box([0, 20], :width => 535, :height => 40) do
      pdf.draw_text "Company: #{@company.name} - Created with: #{getAppName()} - #{getAppUrl()}", :at => [pdf.bounds.left, pdf.bounds.bottom - 20]

    end

    pdf
      
  end


  # Export serviceorder to PDF
  def rpt_facturas_all_pdf

    $lcFacturasall = '1'

    @company=Company.find(params[:company_id])          
    @fecha1 = params[:fecha1]    
    @fecha2 = params[:fecha2]    
    @moneda = params[:moneda_id]    
    @facturas_rpt = @company.get_facturas_day(@fecha1,@fecha2,@moneda)      

#    respond_to do |format|
#      format.html    
#      format.xls # { send_data @products.to_csv(col_sep: "\t") }
#    end 

    Prawn::Document.generate("app/pdf_output/rpt_factura.pdf") do |pdf|
        pdf.font "Helvetica"
        pdf = build_pdf_header_rpt(pdf)
        pdf = build_pdf_body_rpt(pdf)
        build_pdf_footer_rpt(pdf)
        $lcFileName =  "app/pdf_output/rpt_factura_all.pdf"              
    end     
    $lcFileName1=File.expand_path('../../../', __FILE__)+ "/"+$lcFileName              
    send_file("app/pdf_output/rpt_factura.pdf", :type => 'application/pdf', :disposition => 'inline')

  end
# Export serviceorder to PDF
  def rpt_facturas_all2_pdf

    $lcFacturasall = '0'
    @company=Company.find(params[:company_id])          
    @fecha1 = params[:fecha1]    
    @fecha2 = params[:fecha2]    
    @cliente = params[:customer_id]     

    @facturas_rpt = @company.get_facturas_day_cliente(@fecha1,@fecha2,@cliente)  


    Prawn::Document.generate("app/pdf_output/rpt_factura.pdf") do |pdf|
        pdf.font "Helvetica"
        pdf = build_pdf_header_rpt(pdf)
        pdf = build_pdf_body_rpt(pdf)
        build_pdf_footer_rpt(pdf)
        $lcFileName =  "app/pdf_output/rpt_factura_all.pdf"              
    end     
    $lcFileName1=File.expand_path('../../../', __FILE__)+ "/"+$lcFileName              
    send_file("app/pdf_output/rpt_factura.pdf", :type => 'application/pdf', :disposition => 'inline')
  end

  ###pendientes de pago 
  def rpt_ccobrar2_pdf
    $lcxCliente ="0"
    @company=Company.find(params[:company_id])      
    
      @fecha1 = params[:fecha1]
    
      @fecha2 = params[:fecha2]
    
    @company.actualizar_fecha2
    @facturas_rpt = @company.get_pendientes_day(@fecha1,@fecha2)  
      
    Prawn::Document.generate("app/pdf_output/rpt_pendientes.pdf") do |pdf|
        pdf.font "Helvetica"
        pdf = build_pdf_header_rpt2(pdf)
        pdf = build_pdf_body_rpt2(pdf)
        build_pdf_footer_rpt2(pdf)

        $lcFileName =  "app/pdf_output/rpt_pendientes.pdf"              
    end     

    $lcFileName1=File.expand_path('../../../', __FILE__)+ "/"+$lcFileName              
    send_file("app/pdf_output/rpt_pendientes.pdf", :type => 'application/pdf', :disposition => 'inline')
  

  end
  
  ###pendientes de pago 
  def rpt_ccobrar3_pdf

    $lcxCliente ="1"
    @company=Company.find(params[:company_id])      
    @fecha1 = params[:fecha1]    
    @fecha2 = params[:fecha2]
    @cliente = params[:customer_id]      

    @facturas_rpt = @company.get_pendientes_day_cliente(@fecha1,@fecha2,@cliente)  


    if @facturas_rpt.size > 0 

    Prawn::Document.generate("app/pdf_output/rpt_pendientes.pdf") do |pdf|
        pdf.font "Helvetica"
        pdf = build_pdf_header_rpt2(pdf)
        pdf = build_pdf_body_rpt2(pdf)
        build_pdf_footer_rpt2(pdf)

        $lcFileName =  "app/pdf_output/rpt_pendientes.pdf"              
    end     


    $lcFileName1=File.expand_path('../../../', __FILE__)+ "/"+$lcFileName              
    send_file("app/pdf_output/rpt_pendientes.pdf", :type => 'application/pdf', :disposition => 'inline')

    end 

  end
  
  ###pendientes de pago detalle

  def rpt_ccobrar4_pdf
      $lcxCliente ="0"
      @company=Company.find(params[:company_id])          
      @fecha1 = params[:fecha1]  
      @fecha2 = params[:fecha2]  
      @facturas_rpt = @company.get_pendientes_day(@fecha1,@fecha2)  
      
      Prawn::Document.generate("app/pdf_output/rpt_pendientes4.pdf") do |pdf|
          pdf.font "Helvetica"
          pdf = build_pdf_header_rpt4(pdf)
          pdf = build_pdf_body_rpt4(pdf)
          build_pdf_footer_rpt4(pdf)
          $lcFileName =  "app/pdf_output/rpt_pendientes4.pdf"              
      end     
      $lcFileName1=File.expand_path('../../../', __FILE__)+ "/"+$lcFileName              
      send_file("app/pdf_output/rpt_pendientes4.pdf", :type => 'application/pdf', :disposition => 'inline')
  
  end
  

  def client_data_headers_rpt
      client_headers  = [["Empresa  :", $lcCli ]]
      client_headers << ["Direccion :", $lcdir1]
      client_headers
  end

  def invoice_headers_rpt            
      invoice_headers  = [["Fecha : ",$lcHora]]    
      invoice_headers
  end



  private
  def credit_params
    params.require(:factura).permit(:company_id,:location_id,:division_id,:customer_id,:description,:comments,:code,:subtotal,:tax,:total,:processed,:return,:date_processed,:user_id,:payment_id,:fecha,:preciocigv,:tipo,:observ,:moneda_id)
  end

end


