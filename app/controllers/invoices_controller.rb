class InvoicesController < ApplicationController
    
    $: << Dir.pwd  + '/lib'
    before_action :authenticate_user!
    
    require "open-uri"

    def new
         @invoice = Invoice.new
         @customer = Client.all 
         
         @local  =  Local.all 
         
    end
    
    
	def index
	    
	    @ruc = current_user.ruc
	    
	    if current_user.permission_level=='admin'
         @invoices=Invoice.find_by_sql('Select invoices.*,clients.vrazon2,mailings.flag1 from invoices 
            LEFT JOIN mailings ON invoices.numero = mailings.numero
            LEFT  JOIN clients ON invoices.cliente = clients.vcodigo            
            order by numero desc').paginate(:page => params[:page])
        end 
        
        if current_user.permission_level=='cliente'
            
             ruc_cliente= current_user.ruc
             activo = "1"
             
           @invoices=Invoice.find_by_sql(['Select invoices.*,clients.vrazon2,mailings.flag1 from invoices 
            LEFT JOIN mailings ON invoices.numero = mailings.numero
            LEFT JOIN clients  ON invoices.cliente = clients.vcodigo where invoices.flag2 = ?  and clients.vruc = ? order by numero desc ',activo,ruc_cliente ]).paginate(:page => params[:page])
        end 
    end     
    
    def search
        if current_user.permission_level=='admin'
        
            if params[:search].blank?
                #@likes= Invoice.order("numero ASC").page(params[:page]).per_page(15)        
                @invoices=Invoice.find_by_sql('Select invoices.*,clients.vrazon2,mailings.flag1 
                from invoices 
                LEFT JOIN mailings ON invoices.numero = mailings.numero
                LEFT  JOIN clients ON invoices.cliente = clients.vcodigo            
                order by numero desc').paginate(:page => params[:page])
            else            
                #@likes= Invoice.order("numero ASC").page(params[:page]).per_page(15)        
                @invoices=Invoice.find_by_sql(['Select invoices.*,clients.vrazon2,mailings.flag1 from invoices 
                LEFT JOIN mailings ON invoices.numero = mailings.numero
                LEFT JOIN clients ON invoices.cliente = clients.vcodigo            
                 where  invoices.numero like ?  or clients.vrazon2 like ?',params[:search], "%"+ params[:search]+"%"]).paginate(:page => params[:page])
            end      
            
        end 
        if current_user.permission_level=='cliente'
            ruc_cliente= current_user.ruc
            activo = "1"
            if params[:search].blank?
                #@likes= Invoice.order("numero ASC").page(params[:page]).per_page(15)        
                @invoices=Invoice.find_by_sql(['Select invoices.*,clients.vrazon2,mailings.flag1 
                from invoices 
                LEFT JOIN mailings ON invoices.numero = mailings.numero
                LEFT  JOIN clients ON invoices.cliente = clients.vcodigo where flag2 = ? and clients.vruc = ?
                order by numero desc ',activo,ruc_cliente]).paginate(:page => params[:page])
            else            
                #@likes= Invoice.order("numero ASC").page(params[:page]).per_page(15)        
                @invoices=Invoice.find_by_sql(['Select invoices.*,clients.vrazon2,mailings.flag1 from invoices 
                LEFT JOIN mailings ON invoices.numero = mailings.numero
                LEFT JOIN clients ON invoices.cliente = clients.vcodigo            
                 where invoices.flag2 = ? and  invoices.numero like ?  or clients.vrazon2 like ? and clients.vruc = ?',activo,params[:search], "%"+ params[:search]+"%",ruc_cliente]).paginate(:page => params[:page])
            end      
            
        end 
    end

	def import
      
	   Invoice.import(params[:file])
       redirect_to root_url, notice: "Facturas importadas."
    end 
    def show
    	@invoice        = Invoice.find(params[:id])
        @list           = Invoice.find_by_sql([' Select invoices.*,clients.vrazon2,clients.vdireccion,clients.vdistrito,clients.vprov,clients.vdep,clients.mailclient,clients.mailclient2,clients.mailclient3  from invoices INNER JOIN clients ON clients.vcodigo= invoices.cliente where invoices.id = ?',params[:id] ] )
            
        $lg_fecha       = @invoice.fecha.to_s
        $lg_serie_factura = "FF"<< @invoice.serie  
        $lg_serial_id   = @invoice.numero.to_i
        $lg_serial_id2  = @invoice.numero
    
    
        $lcCantidad     = @invoice.cantidad   
        $lcClienteInv   = @invoice.cliente   
        $lcRuc          = @list[0].ruc
        
        $lcTd           = @list[0].td 
        $lcMail         = @list[0].mailclient
        $lcMail2        = @list[0].mailclient2
        $lcMail3        = @list[0].mailclient3
        
         legal_name_spaces = @list[0].vrazon2.lstrip    
        
        if legal_name_spaces == nil
            $lcLegalName    = legal_name_spaces
        else
            $lcLegalName = @list[0].vrazon2
        end
                $lcDirCli       = @list[0].vdireccion   
        $lcDisCli       = @list[0].vdistrito
        $lcProv         = @list[0].vprov
        $lcDep          = @list[0].vdep

        $lcPrecioCigv1  =  @invoice.preciocigv * 100
        $lcPrecioCigv2   = $lcPrecioCigv1.round(0).to_f
        $lcPrecioCigv   =  $lcPrecioCigv2.to_i 

        $lcPrecioSigv1  =  @invoice.preciosigv * 100
        $lcPrecioSigv2   = $lcPrecioSigv1.round(0).to_f
        $lcPrecioSIgv   =  $lcPrecioSigv2.to_i 
        
        $lcVVenta1      =  @invoice.vventa * 100        
        $lcVVenta       =  $lcVVenta1.round(0)
            
        $lcIgv1         =  @invoice.igv * 100
        $lcIgv          =  $lcIgv1.round(0)
        
        $lcTotal1       =  @invoice.importe * 100
        $lcTotal        =  $lcTotal1.round(0)

        #@clienteName1   = Client.where("vcodigo = ?",params[ :$lcClienteInv ])        
        $lcClienteName = ""
        $lcDes1   = @invoice.nombre
        $lcMoneda = @invoice.moneda 
        
    
        #$lcGuiaRemision ="NRO.CUENTA BBVA BANCO CONTINENTAL : 0244-0100023293"
        $lcGuiaRemision =@invoice.guia     
        $lcPlaca =@invoice.codplaca10
        $lcDocument_serial_id =@invoice.numero 
        #$lcAutorizacion =""
        #$lcAutorizacion1=""
   
        $lcSerie= @invoice.serie
        $lcruc = "20555691263" 
        
        if $lcTd == 'FT'
            $lctidodocumento = '01'
        end
        if $lcTd =='BV'
            $lctidodocumento = '03'
        end 
        if $lcTd == 'NC'
            $lctidodocumento = '07'
        end 
        if $lcTd == 'ND'
            $lctidodocumento = '06'
        end
        if @invoice.td == "FT"
          $lcTipoDocCli =  "1"
        else
          $lcTipoDocCli =  "6"
        end 
         $lcNroDocCli =@invoice.get_cliente(@invoice.cliente)
         
         $lcFecha1codigo      = $lg_fecha.to_s

          parts = $lcFecha1codigo.split("-")
          $aa = parts[0]
          $mm = parts[1]        
          $dd = parts[2]       
        $lcFechaCodigoBarras = $aa << "-" << $mm << "-" << $dd
        $lcIGVcode = @invoice.igv
        $lcTotalcode = @invoice.importe 
        
        $lcCodigoBarra = $lcruc << "|" << $lcTd << "|" << $lcSerie << "|" << $lcDocument_serial_id.to_s << "|" <<$lcIGVcode.to_s<< "|" << $lcTotalcode.to_s << "|" << $lcFechaCodigoBarras << "|" << $lcTipoDocCli << "|" << $lcNroDocCli
      
          $lcPercentIgv  =18000   
          $lcAutorizacion="Autorizado mediante Resolucion de Intendencia Nro.034-005-0005592/SUNAT del 22/06/2016 "
          $lcCuentas=" El pago del documento sera necesariamente efectuado mediante deposito en cualquiera de las siguientes cuentas bancarias:  
  BBVA Continental Cuenta Corriente en Moneda Nacional Numero: 0011-0172-01-00041266
  BBVA Continental Cuenta Corriente en Moneda Extranjera Numero: 0011-0176-01-00063164 
BCP Cuenta Corriente Moneda Nacional : 191-2167239-0-01
BCP Cuenta Recaudadora Moneda Nacional : 191-2264838-0-49"  


          $lcScop1       =""   
          $lcScop2       =""
          $lcCantScop1   =""
          $lcCantScop2   =""  
          $lcAutorizacion1=$lcAutorizacion +$lcCuentas
          puts $lg_fecha
          
    end

    def sendsunat
        lib = File.expand_path('../../../lib', __FILE__)
        $LOAD_PATH.unshift(lib) unless $LOAD_PATH.include?(lib)

        require 'sunat'
        require './config/config'
        require './app/generators/invoice_generator'
        require './app/generators/credit_note_generator'
        require './app/generators/debit_note_generator'
        require './app/generators/receipt_generator'
        require './app/generators/daily_receipt_summary_generator'
        require './app/generators/voided_documents_generator'

        SUNAT.environment = :production

        files_to_clean = Dir.glob("*.xml") + Dir.glob("./app/pdf_output/*.pdf") + Dir.glob("*.zip")
        files_to_clean.each do |file|
          File.delete(file)
        end 
        
        if $lcMoneda == "D"
        else
        case_3 = InvoiceGenerator.new(1, 3, 1, $lg_serie_factura).with_igv(true)
        end     
        
        $lcGuiaRemision =""      
        @@document_serial_id =""
        $lg_serial_id=""

    end

    def print

        lib = File.expand_path('../../../lib', __FILE__)
        $LOAD_PATH.unshift(lib) unless $LOAD_PATH.include?(lib)
        puts lib
        

        require 'sunat'
        require './config/config'
        require './app/generators/invoice_generator'
        require './app/generators/credit_note_generator'
        require './app/generators/debit_note_generator'
        require './app/generators/receipt_generator'
        require './app/generators/daily_receipt_summary_generator'
        require './app/generators/voided_documents_generator'

        SUNAT.environment = :production

        files_to_clean = Dir.glob("*.xml") + Dir.glob("./app/pdf_output/*.pdf") + Dir.glob("*.zip")
        files_to_clean.each do |file|
          File.delete(file)
        end         
    
       if $lcMoneda == "D"  
            $lcFileName=""
            case_49 = InvoiceGenerator.new(1,3,1,$lg_serie_factura).with_different_currency2
          #  puts $lcFileName 
       else
            case_3  = InvoiceGenerator.new(1,3,1,$lg_serie_factura).with_igv2(true)
       end 
    
        
        $lcFileName1=File.expand_path('../../../', __FILE__)+ "/"+$lcFileName
                
        send_file("#{$lcFileName1}", :type => 'application/pdf', :disposition => 'inline')

        
        @@document_serial_id =""
        $aviso=""
    end 

        
    def sendmail      

        lib = File.expand_path('../../../lib', __FILE__)
        $LOAD_PATH.unshift(lib) unless $LOAD_PATH.include?(lib)

        require 'sunat'
        require './config/config'
        require './app/generators/invoice_generator'
        require './app/generators/credit_note_generator'
        require './app/generators/debit_note_generator'
        require './app/generators/receipt_generator'
        require './app/generators/daily_receipt_summary_generator'
        require './app/generators/voided_documents_generator'

        SUNAT.environment = :production

        files_to_clean = Dir.glob("*.xml") + Dir.glob("./app/pdf_output/*.pdf") + Dir.glob("*.zip")
        files_to_clean.each do |file|
          File.delete(file)
        end 

        
         if $lcMoneda == "D"
            case_49 = InvoiceGenerator.new(7,49,5,$lg_serie_factura).with_different_currency2(true)
        else
            case_3 = InvoiceGenerator.new(1, 3, 1,$lg_serie_factura).with_igv3(true)
        end 
    
        $lcFileName1=File.expand_path('../../../', __FILE__)+ "/"+$lcFileName        
        $lcFile2    =File.expand_path('../../../', __FILE__)+ "/"+$lcFilezip
        
        ActionCorreo.bienvenido_email(@invoice).deliver
        if $lcMoneda == "D"
            @mailing = Mailing.new(:td =>$lcTd, :serie => 'FF02', :numero => $lcDocument_serial_id, :ruc=>$lcRuc, :flag1 => '1')
        else 
            @mailing = Mailing.new(:td =>$lcTd, :serie => 'FF01', :numero => $lcDocument_serial_id, :ruc=>$lcRuc, :flag1 => '1')
        end
        
        @mailing.save
        $lcGuiaRemision =""
             

    end


    def download
        extension = File.extname(@asset.file_file_name)
        send_data open("#{@asset.file.expiring_url(10000, :original)}").read, filename: "original_#{@asset.id}#{extension}", type: @asset.file_content_type
    end

    def xml
        
        lib = File.expand_path('../../../lib', __FILE__)
        $LOAD_PATH.unshift(lib) unless $LOAD_PATH.include?(lib)

        require 'sunat'
        require './config/config'
        require './app/generators/invoice_generator'
        require './app/generators/credit_note_generator'
        require './app/generators/debit_note_generator'
        require './app/generators/receipt_generator'
        require './app/generators/daily_receipt_summary_generator'
        require './app/generators/voided_documents_generator'

        SUNAT.environment = :production
        files_to_clean = Dir.glob("*.xml") + Dir.glob("./app/pdf_output/*.pdf") + Dir.glob("*.zip")

        files_to_clean.each do |file|
          File.delete(file)
        end         
         if $lcMoneda == "D"
            case_49 = InvoiceGenerator.new(7,49,5,$lg_serie_factura).with_different_currency2
        else
            case_3 = InvoiceGenerator.new(1, 3, 1, $lg_serie_factura).with_igv3(true)
        end 
        $lcFile2 =File.expand_path('../../../', __FILE__)+ "/"+$lcFilezip    
    
        send_file("#{$lcFile2}",:type =>'application/zip', :disposition => 'inline') 
        @@document_serial_id =""
        $aviso=""
    end 
    
    
    
    def editmultiple

    if params[:products_ids] != nil 

        @guiasselect = Invoice.find(params[:products_ids])      
    end     
    end

  def updatemultiple
 
       Invoice.where(id: params[:products_ids]).update_all(params[:invoice])
        
      flash[:notice] = "Facturas modificadas"
       redirect_to "/invoices"
      
  end
    
    
    private
    def validate_user
        redirect_to  new_user_session_path, notice: "Necesitas iniciar sesión ..."
    end

end



