class Invoice < ActiveRecord::Base
 self.per_page = 10
  belongs_to :client, :class_name=> 'Client',    :foreign_key=> 'cliente'
  
  TABLE_HEADERS2 = ["TD",
                      "Documento",
                     "Fecha",
                     "Cliente",
                     "Moneda",
                     "SUBTOTAL",
                     "IGV.",
                     "TOTAL",
                     "ESTADO"]

	attr_accessible :cant1, :cant2, :cantidad, :cliente, :fecha, :guia, :igv, :importe, :moneda, :numero, :preciocigv, :preciosigv, :ruc, :serie, :td, :vventa,:codplaca10,:nombre,:moneda,:flag2


      def self.import(file)
          CSV.foreach(file.path, headers: true, encoding:'iso-8859-1:utf-8') do |row|
                  
          a= Invoice.find_by(td: row['td'],serie: row['serie'],numero: row['numero'])          
      
          if a       
            if a.td == row['td'] and a.serie == row['serie'] and a.numero == row['numero']
                
             a.update(:cliente=>row['cliente'],
                             :fecha=>row['fecha'],
                             :preciocigv=>row['preciocigv'],
                             :preciosigv=>row['preciosigv'],
                             :cantidad=>row['cantidad'],
                             :vventa=>row['vventa'], 
                             :igv=>row['igv'],
                             :importe=> row['importe'], 
                             :ruc=>row['ruc'], 
                             :guia=>row['guia'],
                             :flag1=>row['flag1'],
                             :codplaca10=>row['codplaca10'], 
                             :nombre=>row['nombre'],
                             :moneda=>row['moneda'])
            end 
            
            else
              Invoice.create! row.to_hash 
              
            end
         
         end 
      end 

      def self.search(params)        
        invoices = Invoice.where("numero   LIKE ?","%#{params[:search]}%") if params[:search].present?
        invoices
      end

      def enviarsunat      	
       case_3 = InvoiceGenerator.new(1, 3, 1, "FF01").with_igv(true) 
      end

      def client_name
        read_attribute(client_name) || client.vrazon2
      end
     
     
end


