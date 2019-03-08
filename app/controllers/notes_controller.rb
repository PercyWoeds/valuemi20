
class NotesController < ApplicationController
  
  before_action :set_note, only: [:show, :edit, :update, :destroy]
  
   $: << Dir.pwd + '/lib'
   #require 'pry'
  #  require 'peru_sunat_ruc'
  
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
        
     
       
        # grido solo 
        # bb04 market cargue datos hasta el 10-10
        # cambiar unidad a zz
        # bb02 en chequeo 
        
        $unidad_medida = 'ZZ'
        
        @fecha1 = params[:fecha1]    
        @fecha2 = params[:fecha2]
    
#        @boleta = Note.select("fecha,serie,numero,cod_cli,td,ruc,precio,precio_sigv,cod_prod,sum(cantidad) as cantidad, sum(importe) as importe").where(["fecha >= ? and fecha <= ? and importe >= 0 and procesado = ? and serie = ?", "#{fecha1} 00:00:00","#{fecha2} 23:59:59","0","BB02"] ).group(:fecha,:serie,:numero)
        @boleta = Note.select("id,fecha,serie,numero,cod_cli,td,ruc,precio,precio_sigv,cod_prod,SUM(cantidad) as cantidad, SUM(importe) as importe").where(["fecha >= ? and fecha <= ? and importe >= 0 and procesado = ? and serie = ? ", "#{@fecha1} 00:00:00","#{@fecha2} 23:59:59","0","BX01"] ).group(:fecha,:serie,:numero)
      
        for @invoice in @boleta
        
                  
                  $lcAutorizacion = ""
                  $lcCuentas=" "  


                  $lcAutorizacion1 = $lcAutorizacion +$lcCuentas
                  
              #  case_54 = ReceiptGenerator.new(8, 54, 1, "BB02").with_igv(true)      
              
              #  case_54 = ReceiptGenerator.new(8, 54, 1,@invoice.serie,@invoice.numero).with_igv(true)      
              #  if @invoice.td == "F"
      #         case_3 = InvoiceGenerator.new(1, 3, 1, @invoice.serie ,@invoice.numero).with_igv(true)
              #  else
              case_54 = ReceiptGenerator.new(8, 54, 1,@invoice.serie,@invoice.numero).with_igv(true)      
               # end 
                
                  @invoice[:procesado] = "1"
                  @invoice.save 
                
         end   

  end 
  
  
  # GET /notes
  # GET /notes.json
  def index
      @notes = Note.all.paginate(:page => params[:page]).where("fecha>=? and fecha<= ?","2019-01-01 00:00:00","2019-12-31 23:59:59").order("fecha DESC","serie ","NUMERO DESC")
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
