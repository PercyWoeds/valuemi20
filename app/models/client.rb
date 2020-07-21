class Client < ActiveRecord::Base

	has_many :invoices, :class_name=> 'Invoice',	:foreign_key => 'vcodigo' 
  has_many :notacredits 
	
    self.per_page = 20

    
	attr_accessible :vcodigo, :vdep, :vdireccion, :vdistrito, :vprov, :vrazon2, :vruc, :mailclient, :mailclient2, :mailclient3

      def self.import(file)
          CSV.foreach(file.path, headers: true, encoding:'iso-8859-1:utf-8') do |row|
          Client.create! row.to_hash 
        end
      end       

  def self.search(search)
      where("vruc  LIKE ?", "%#{search}%") 
        
  end

 def self.to_csv(options = {})
      CSV.generate(options) do |csv|
        csv << column_names
        all.each do |client|
          csv << client.attributes.values_at(*column_names)
        end
      end
    end

# Return value for customer in specific date
  def get_invoices_value_customer_date(customer, date, value)
    invoices = Invoice.where([" ruc = ? and  fecha >= ? AND fecha <= ?",customer , "#{date} 00:00:00", "#{date} 23:59:59"])
    ret = 0
    
    for invoice in invoices
      if(value == "subtotal")
        ret += invoice.subtotal
      elsif(value == "tax")
        ret += invoice.tax
      else
        ret += invoice.total
      end
    end
    
    return ret
  end

  def get_facturas_day(customer,fecha1,fecha2,moneda)

    @facturas = Invoice.where([" ruc = ? AND fecha >= ? and fecha<= ? and moneda = ?", customer, "#{fecha2}-#{fecha1}-01 00:00:00","#{fecha2}-#{fecha1}-31 23:59:59",moneda ]).order(:id )
    return @facturas
  
  end 
  def get_monedas()
        monedas = Moneda.order(:description)
        return monedas
  end
  
 def get_facturas_day_value(fecha1,fecha2,value = "total",moneda)
      

    facturas = Invoice.where([" fecha >= ? and fecha<= ? and moneda = ?", "#{fecha2}-#{fecha1}-01 00:00:00","#{fecha2}-#{fecha1}-31 23:59:59",moneda])
    if facturas
    ret=0  
    for factura in facturas
      
      if(value == "subtotal")
        ret += factura.subtotal
      elsif(value == "tax")
        ret += factura.tax
      else         
        ret += factura.total
      end
    end
    end 

    return ret
  
 end 
  
end
