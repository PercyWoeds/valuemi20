class Client < ActiveRecord::Base

	has_many :invoices, :class_name=> 'Invoice',	:foreign_key => 'vcodigo' 
	

	attr_accessible :vcodigo, :vdep, :vdireccion, :vdistrito, :vprov, :vrazon2, :vruc

      def self.import(file)
          CSV.foreach(file.path, headers: true) do |row|
          Client.create! row.to_hash 

        end
      end 
end
