class Client < ActiveRecord::Base

	has_many :invoices, :class_name=> 'Invoice',	:foreign_key => 'vcodigo' 
	
	attr_accessible :vcodigo, :vdep, :vdireccion, :vdistrito, :vprov, :vrazon2, :vruc, :mailclient, :mailclient2, :mailclient3

      def self.import(file)
          CSV.foreach(file.path, headers: true, encoding:'iso-8859-1:utf-8') do |row|
          Client.create! row.to_hash 
        end
      end       

	   def self.search(search,page=1)  
        paginate :per_page=>5 , :page=> page,  
        :conditions=> ["vrazon2 LIKE ?","%#{:search}%","%#{:search}%"],order=>'name'

     end



end
