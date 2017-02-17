json.extract! credit, :id, :fecha, :code, :nota_id, :motivo, :subtotal, :tax, :total, :moneda_id, :mod_factura, :mod_tipo, :created_at, :updated_at
json.url credit_url(credit, format: :json)