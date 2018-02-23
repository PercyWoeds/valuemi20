class Notacredit < ActiveRecord::Base

validates_presence_of :fecha, :code, :subtotal, :tax, :total, :mod_factura, :client_id,:price,:quantity,:nombre

belongs_to :client
belongs_to :notum 

end
