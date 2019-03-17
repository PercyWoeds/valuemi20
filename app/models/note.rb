class Note < ActiveRecord::Base
    
    
    
    
          def self.import(file)
            CSV.foreach(file.path, headers: true, encoding:'iso-8859-1:utf-8') do |row|
                    a= Note.find_by(td: row['td'],serie: row['serie'],numero: row['numero'])
                    
                    
                    Note.create! row.to_hash 
                    end 
                  Note.where(precio_sigv:nil).update_all('precio_sigv = precio /1.18')
                  Note.where(vventa:nil).update_all('vventa = importe /1.18')
                  Note.where(tax:nil).update_all('tax = importe - vventa')
                  
          end 

 def get_value(value,a,b)
          
          invoices = Invoice.where(["serie = ? and numero = ?",a,b])
          ret = 0
          
          for invoice in invoices
            if(value == "vventa")
              ret += invoice.vventa
            elsif(value == "tax")
              ret += invoice.tax
            else
              ret += invoice.importe 
            end
          end
          
          return ret
     end
          
    def get_facturas_day(fecha1,fecha2,dato) 
      
        
        @boletas = Note.select("serie,MIN(numero) as minimo, MAX(numero) as maximo,sum(importe) as total").where(["fecha >= ? and fecha<= ?  and SUBSTRING (serie, 2, 1)=? ", "#{fecha1} 00:00:00","#{fecha2} 23:59:59",dato ]).group(:serie).order(:serie)
        return @boletas
        
    end 
    
    def get_facturas_eess(fecha1,fecha2,dato ) 
      
        
        @boletas = Note.select("sum(importe) as total").where(["fecha >= ? and fecha<= ?  and SUBSTRING (serie, 2, 1)=? ", "#{fecha1} 00:00:00","#{fecha2} 23:59:59",dato ])
        ret=0  
          if @boletas 
          
            for factura in   @boletas 
            
                ret += factura.total.round(2)
        
            end
           
          end 
          
          return ret    
        
    end 
    
    def self.to_csv(options = {})
      CSV.generate(options) do |csv|
        csv << column_names
        all.each do |client|
          csv << client.attributes.values_at(*column_names)
        end
      end
    end
 def get_processed_short
    if(self.procesado== "1")
      return "Si"
    elsif (self.procesado  == "3")
       return "Si"
    else
      return "No"
    end
  end
  
  
end
