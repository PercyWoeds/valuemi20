# Sample SUNAT configuration

SUNAT.configure do |config|
  config.credentials do |c|


    c.ruc       = "20545339006"
    c.username  = "FACTURA2"
    c.password  = "20545339006"
   end

  config.signature do |s|
    s.party_id    = "20545339006"
    s.party_name  = "CODISCOM S.A.C."
    s.cert_file   = File.join(Dir.pwd, '/app/keys', 'certificado.crt')
    s.pk_file     = File.join(Dir.pwd, '/app/keys', 'CERTIFICADO.key') 
    
  end

  config.supplier do |s|
    s.legal_name = "CODISCOM S.A."
    s.name       = "HUGO DENIS MADRID REFULIO"
    s.ruc        = "20545339006"
    s.address_id = "150142"
    s.street     = "PACHACAMA MZA. C2 LOTE. 12 BAR.2 SEC. 1 IV ETA. 
    (POR SEPARADORA INDUSTRIAL Y PACHACUTEC)"
    s.district   = "LIMA - LIMA - VILLA EL SALVADOR"
    s.city       = "LIMA"
    s.country    = "PE"
    s.logo_path  = "#{Dir.pwd}/public/images/logo.PNG"
  end
end