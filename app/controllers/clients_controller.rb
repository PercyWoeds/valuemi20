class ClientsController < ApplicationController
  before_action :authenticate_user!
  before_action :set_client, only: [:show, :edit, :update, :destroy], :except=> [:index,:search]


  def import
 
      Client.import(params[:file])
       redirect_to root_url, notice: "Clientes importadas."
  end 
  
  # GET /clients
  # GET /clients.json

  def index
      

  @clients = Client.all.order(:vrazon2).paginate(:page => params[:page]) 
  respond_to do |format|
    format.html
    format.csv { send_data @clients.to_csv }    
    end


  if params[:search]

      @clients = Client.search(params[:search]).order(:vrazon2).order('vrazon2').paginate(:page => params[:page]) 
  else
    @clients = Client.all.order(:vrazon2).order('vrazon2').paginate(:page => params[:page]) 
  end

    
  end     
    
  def search
        if params[:search].blank?
            @likes= Client.order("vcodigo ASC").page(params[:page]).per_page(15)        
            @clients=@likes.all
        else
            @likes= Client.order("vcodigo ASC").page(params[:page]).per_page(15)        
            #@clients=@likes.where("vrazon2 like  ?",params[:seach])                
            @clients=@likes.where(params[:seach])
        end        
  end


  def get_facturas_day(customer,fecha1,fecha2,moneda)

    @facturas = Invoice.where([" ruc = ? and fecha >= ? and fecha<= ? and moneda = ?", customer, "#{fecha1} 00:00:00","#{fecha2} 23:59:59",moneda ]).order(:id )
    return @facturas
    
  end 
  # GET /clients/1
  # GET /clients/1.json
  def show
  end

  # GET /clients/new
  def new
    @client = Client.new
  end

  # GET /clients/1/edit
  def edit
  end

  # POST /clients
  # POST /clients.json
  def create
    @client = Client.new(client_params)

    respond_to do |format|
      if @client.save
        format.html { redirect_to @client, notice: 'Client was successfully created.' }
        format.json { render :show, status: :created, location: @client }
      else
        format.html { render :new }
        format.json { render json: @client.errors, status: :unprocessable_entity }
      end
    end
  end

  # PATCH/PUT /clients/1
  # PATCH/PUT /clients/1.json
  def update
    respond_to do |format|
      if @client.update(client_params)
        format.html { redirect_to @client, notice: 'Client was successfully updated.' }
        format.json { render :show, status: :ok, location: @client }
      else
        format.html { render :edit }
        format.json { render json: @client.errors, status: :unprocessable_entity }
      end
    end
  end

  # DELETE /clients/1
  # DELETE /clients/1.json
  def destroy
    @client.destroy
    respond_to do |format|
      format.html { redirect_to clients_url, notice: 'Client was successfully destroyed.' }
      format.json { head :no_content }
    end
  end


   private  
    # Use callbacks to share common setup or constraints between actions.
    def set_client
          @client = Client.find(params[:id])
      end
  
    # Never trust parameters from the scary internet, only allow the white list through.
    def client_params
      params.require(:client).permit(:vcodigo, :vruc, :vrazon2, :vdireccion, :vdistrito, :vprov, :vdep, :mailclient, :mailclient2, :mailclient3)
    end


  
end
