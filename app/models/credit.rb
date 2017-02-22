class Credit < ActiveRecord::Base


 validates_uniqueness_of :code
  
  validates_presence_of :nota_id,:mod_factura,:client_id
  validates_numericality_of  :subtotal,:tax,:total,:quantity,:price



end
