# Sample SUNAT configuration

SUNAT.configure do |config|
  config.credentials do |c|


    c.ruc       = "20522012336"
    c.username  = "FACTURA2"
    c.password  = "20522012336"
   end

  config.signature do |s|
    s.party_id    = "20522012336"
    s.party_name  = "HIDROTRANSP S.A.C."
    s.cert_file   = File.join(Dir.pwd, './app/keys', 'certificate.crt')
    s.pk_file     = File.join(Dir.pwd, './app/keys', 'CERTIFICADO.key') 
  end

  config.supplier do |s|
    s.legal_name = "HIDROTRANSP S.A.C."
    s.name       = "Cesar Jaime Manrique Milla"
    s.ruc        = "20522012336"
    s.address_id = "150132"
    s.street     = "CAM.SECTOR CRUZ DEL NORTE I ZONA BAJA MZA. C LOTE. 5 
    A.H. PROYECTO INTEGRAL ALIANZA INDUSTRIAL DE LAS LOMAS"
    s.district   = "CARABAYLLO"
    s.city       = "LIMA"
    s.country    = "PE"
    s.logo_path  = "#{Dir.pwd}/app/assets/images/logo.PNG"
  end
end