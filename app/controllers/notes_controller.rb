
class NotesController < ApplicationController
  
  before_action :set_note, only: [:show, :edit, :update, :destroy]
  
   $: << Dir.pwd + '/lib'
    require 'pry'
    require 'peru_sunat_ruc'
  
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
        

  
  def procesar
        
        SUNAT.environment = :test  
        
        files_to_clean = Dir.glob("*.xml") + Dir.glob("./app/pdf_output/*.pdf") + Dir.glob("*.zip")
        files_to_clean.each do |file|
          File.delete(file)
        end 
        
        # bb04-24 ultima boleta procesad ok en sunat dia 11-04-18
        
        
        fecha1 = "2018-08-20"
        fecha2 = "2018-08-20"
        
#        @boleta = Note.select("fecha,serie,numero,cod_cli,td,ruc,precio,precio_sigv,cod_prod,sum(cantidad) as cantidad, sum(importe) as importe").where(["fecha >= ? and fecha <= ? and importe >= 0 and procesado = ? and serie = ?", "#{fecha1} 00:00:00","#{fecha2} 23:59:59","0","BB02"] ).group(:fecha,:serie,:numero)
        @boleta = Note.select("id,fecha,serie,numero,cod_cli,td,ruc,precio,precio_sigv,cod_prod,SUM(cantidad) as cantidad, SUM(importe) as importe").where(["fecha >= ? and fecha <= ? and importe >= 0 and procesado = ? and serie = ?", "#{fecha1} 00:00:00","#{fecha2} 23:59:59","0","FA02"] ).group(:fecha,:serie,:numero)
        
        for @invoice in @boleta
        
                # $lg_fecha       = @invoice.fecha.strftime("%Y-%m-%d") 
                
                # $lg_serie_factura = "BB02"
                # $lg_serial_id   = @invoice.numero.to_i
                # $lg_serial_id2  = @invoice.numero
                
            
                # $lcCantidad     = @invoice.cantidad   
                # $lcClienteInv   = @invoice.cod_cli    
                
                # $lcTd           = @invoice.td 
                
                # if $lcTd == "N"
                #   $lcRuc = ""
                # else 
                  
                # $lcRuc          = @invoice.ruc   
                # $lcRuc0         = @invoice.ruc   
                # end 
                # puts $lcTd 
                
                # if $lcTd == "F"
                
                # ruc_number = @invoice.ruc  
                # puts "ruc"
                # puts ruc_number
                # puts $lcTd
                # puts $lg_serial_id
                
                # result  = PeruSunatRuc.name_from ruc_number
                # result2 = PeruSunatRuc.address_from ruc_number

                
                # legal_name_spaces =  result.lstrip
                
                # if legal_name_spaces == nil
                #     $lcLegalName    = result
                # else
                #     $lcLegalName = result.lstrip 
                # end
                # $lcDirCli       = result2.gsub(/\s+/," ").strip 
                
                #   else
                #     $lcLegalName = ""
                #     $lcDirCli =  ""
                #   end 

                

                # $lcDisCli       = ""
                # $lcProv         = ""
                # $lcDep          = ""

                # $lcPrecioCigv1  =  @invoice.precio * 100
                # $lcPrecioCigv2   = $lcPrecioCigv1.round(0).to_f
                # $lcPrecioCigv   =  $lcPrecioCigv2.to_i 

                # $lcPrecioSigv1  =  @invoice.precio_sigv * 100
                # $lcPrecioSigv2   = $lcPrecioSigv1.round(0).to_f
                # $lcPrecioSIgv   =  $lcPrecioSigv2.to_i 
                
                # lcAmount = @invoice.importe / 1.18 
                # lcAmount0 = lcAmount.round(2)
                # lcTax    = @invoice.importe - lcAmount0
                # lcTax0 =lcTax.round(2)
                
                # $lcVVenta1      =  lcAmount0 * 100        
                # $lcVVenta       =  $lcVVenta1.round(0)
                    
                # $lcIgv1         =  lcTax0 * 100
                # $lcIgv          =  $lcIgv1.round(0)
                
                # $lcTotal1       =  @invoice.importe * 100
                # $lcTotal        =  $lcTotal1.round(0)

                # #@clienteName1   = Client.where("vcodigo = ?",params[ :$lcClienteInv ])        
                # $lcClienteName = ""
                
                # if @invoice.cod_prod == "2"
                #   $lcDes1   = "GAS-90"
                # elsif @invoice.cod_prod == "3"
                #   $lcDes1   = "GAS-95"
                # elsif @invoice.cod_prod == "4"
                #   $lcDes1   = "GAS-95"
                # elsif @invoice.cod_prod == "5"
                #   $lcDes1   = "DIESEL 2 "
                # end 
                # if @invoice.cod_prod == "02"
                #   $lcDes1   = "GAS-90"
                # elsif @invoice.cod_prod == "03"
                #   $lcDes1   = "GAS-95"
                # elsif @invoice.cod_prod == "04"
                #   $lcDes1   = "GAS-95"
                # elsif @invoice.cod_prod == "05"
                #   $lcDes1   = "DIESEL 2 "
                # end 
                
                
                # $lcMoneda = "S"
                
            
                # #$lcGuiaRemision ="NRO.CUENTA BBVA BANCO CONTINENTAL : 0244-0100023293"
                # $lcGuiaRemision =""
                # $lcPlaca = ""
                # $lcDocument_serial_id =@invoice.numero 
                # #$lcAutorizacion =""
                # #$lcAutorizacion1=""
           
                # $lcSerie= @invoice.serie
                # $lcruc = "20555691263" 
                
                # if $lcTd == 'F'
                #     $lctidodocumento = '03'
                #     $lcNroDocCli = $lcRuc
                # end
                # if $lcTd =='B'
                #     $lctidodocumento = '03'
                #     $lcNroDocCli = "00000000"
                # end 
                # if $lcTd =='N'
                #     $lctidodocumento = '03'
                #     $lcNroDocCli = "00000000"
                #     $lcDes1 = "ANULADO"
                #     $lcCantidad = 0.00
                #     $lcPrecioCigv = 0
                #     $lcPrecioSIgv = 0
                #     $lcVVenta = 000
                #     $lcIgv =  000
                #     $lcTotal = 000
                # end 
                
                # if $lcTd == 'NC'
                #     $lctidodocumento = '07'
                # end 
                # if $lcTd == 'ND'
                #     $lctidodocumento = '06'
                # end
                # if @invoice.td == "FT"
                #   $lcTipoDocCli =  "1"
                  
                # else
                #   $lcTipoDocCli =  "6"
                # end 
                 
                 
                # $lcFecha1codigo    = $lg_fecha.to_s

                #   parts = $lcFecha1codigo.split("-")
                #   $aa = parts[0]
                #   $mm = parts[1]        
                #   $dd = parts[2]       
                  
                # $lcFechaCodigoBarras = $aa << "-" << $mm << "-" << $dd
                # $lcIGVcode = lcTax0
                # $lcTotalcode = @invoice.importe 
                
                #   $lcCodigoBarra = $lcruc << "|" << $lcTd << "|" << $lcSerie << "|" << $lcDocument_serial_id.to_s << "|" <<$lcIGVcode.to_s<< "|" << $lcTotalcode.to_s << "|" << $lcFechaCodigoBarras << "|" << $lcTipoDocCli << "|" << $lcNroDocCli
              
                #   $lcPercentIgv  =18000   
                  
                  $lcAutorizacion = ""
                  $lcCuentas=" El pago del documento sera necesariamente efectuado mediante deposito en cualquiera de las siguientes cuentas bancarias:  
          BBVA Continental Cuenta Corriente en Moneda Nacional Numero: 0011-0172-01-00041266
          BBVA Continental Cuenta Corriente en Moneda Extranjera Numero: 0011-0176-01-00063164 
        BCP Cuenta Corriente Moneda Nacional : 191-2167239-0-01
        BCP Cuenta Recaudadora Moneda Nacional : 191-2264838-0-49"  


                  $lcAutorizacion1=$lcAutorizacion +$lcCuentas
                  
              #  case_54 = ReceiptGenerator.new(8, 54, 1, "BB02").with_igv(true)      
              
              #  case_54 = ReceiptGenerator.new(8, 54, 1,@invoice.serie,@invoice.numero).with_igv(true)      
                
                  case_3 = InvoiceGenerator.new(1, 3, 1, @invoice.serie ,@invoice.numero).with_igv(true)
                  
                  @invoice[:procesado] = "1"
                  @invoice.save 
                
         end   

  end 
  
  
  # GET /notes
  # GET /notes.json
  def index
      @notes = Note.all.paginate(:page => params[:page]).where(serie:"FA02").order("fecha DESC","serie ","NUMERO DESC")
  end

  # GET /notes/1
  # GET /notes/1.json
  def show
  end

  # GET /notes/new
  def new
    @note = Note.new
  end

  # GET /notes/1/edit
  def edit
  end

  # POST /notes
  # POST /notes.json
  def create
    @note = Note.new(note_params)

    respond_to do |format|
      if @note.save
        format.html { redirect_to @note, notice: 'Note was successfully created.' }
        format.json { render :show, status: :created, location: @note }
      else
        format.html { render :new }
        format.json { render json: @note.errors, status: :unprocessable_entity }
      end
    end
  end

  # PATCH/PUT /notes/1
  # PATCH/PUT /notes/1.json
  def update
    respond_to do |format|
      if @note.update(note_params)
        format.html { redirect_to @note, notice: 'Note was successfully updated.' }
        format.json { render :show, status: :ok, location: @note }
      else
        format.html { render :edit }
        format.json { render json: @note.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /notes/1
  # DELETE /notes/1.json
  def destroy
    @note.destroy
    respond_to do |format|
      format.html { redirect_to notes_url, notice: 'Note was successfully destroyed.' }
      format.json { head :no_content }
    end
  end
  
	def import
      
	     Note.import(params[:file])
       redirect_to root_url, notice: "Boletas importadas."
  end 
    
  
  

  private
    # Use callbacks to share common setup or constraints between actions.
    def set_note
      @note = Note.find(params[:id])
    end

    # Never trust parameters from the scary internet, only allow the white list through.
    def note_params
      params.require(:note).permit(:td, :fecha, :turno, :cod_emp, :caja, :serie, :numero, :cod_cli, :ruc, :placa, :odometro, :cod_prod, :cantidad, :precio, :importe, :igv, :fpago)
    end
end
