class Client < ActiveRecord::Base

	has_many :invoices, :class_name=> 'Invoice',	:foreign_key => 'vcodigo' 
	
	attr_accessible :vcodigo, :vdep, :vdireccion, :vdistrito, :vprov, :vrazon2, :vruc, :mailclient, :mailclient2, :mailclient3

      def self.import(file)
          CSV.foreach(file.path, headers: true) do |row|
          Client.create! row.to_hash 
        end
      end       

	   def self.search(params)    
        clients = Client.where("vrazon2   LIKE ?","%#{params[:search]}%") if params[:search].present?
        clients
      end


end
