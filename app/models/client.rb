class Client < ActiveRecord::Base

	has_many :invoices, :class_name=> 'Invoice',	:foreign_key => 'vcodigo' 
<<<<<<< HEAD
  has_many :notacredits 
	
    self.per_page = 20

    
=======
	
>>>>>>> 78a73d75881b3f03cd1267ae4e5b28d4653ee4f8
	attr_accessible :vcodigo, :vdep, :vdireccion, :vdistrito, :vprov, :vrazon2, :vruc, :mailclient, :mailclient2, :mailclient3

      def self.import(file)
          CSV.foreach(file.path, headers: true, encoding:'iso-8859-1:utf-8') do |row|
          Client.create! row.to_hash 
        end
      end       

<<<<<<< HEAD
  def self.search(search)
      where("vruc  LIKE ?", "%#{search}%") 
        
  end
=======
	   def self.search(search,page=1)  
        paginate :per_page=>5 , :page=> page,  
        :conditions=> ["vrazon2 LIKE ?","%#{:search}%","%#{:search}%"],order=>'name'
        #clients = Client.where("vrazon2   LIKE ?","%#{params[:search]}%") if params[:search].present?
        #clients

     end
    def self.to_csv(options = {})
      CSV.generate(options) do |csv|
        csv << column_names
        all.each do |client|
          csv << client.attributes.values_at(*column_names)
        end
      end
    end
>>>>>>> 78a73d75881b3f03cd1267ae4e5b28d4653ee4f8


end
