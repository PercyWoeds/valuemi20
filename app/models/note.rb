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
          
    
end
