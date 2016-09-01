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
<<<<<<< HEAD
    s.cert_file   = File.join(Dir.pwd, '/app/keys', 'certificado.crt')
    s.pk_file     = File.join(Dir.pwd, '/app/keys', 'CERTIFICADO.key') 
=======
    s.cert_file   = File.join(Dir.pwd, './app/keys', 'certificado.crt')
    s.pk_file     = File.join(Dir.pwd, './app/keys', 'CERTIFICADO.key') 
>>>>>>> 31270d787f9a3c47fc4406635485a17d24fbbdbf
  end

  config.supplier do |s|
    s.legal_name = "CODISCOM S.A."
    s.name       = "HUGO DENIS MADRID REFULIO"
    s.ruc        = "20545339006"
    s.address_id = "150117"
    s.street     = "CL.IGNACIO TOROTE NRO.515 DPTO 202 URB. EL TREBOL"
<<<<<<< HEAD
    s.district   = "1RA. ETAPA LOS OLIVOS"
=======
    s.district   = " 1RA. ETAPA LOS OLIVOS"
>>>>>>> 31270d787f9a3c47fc4406635485a17d24fbbdbf
    s.city       = "LIMA"
    s.country    = "PE"
    s.logo_path  = "#{Dir.pwd}/app/assets/images/logo.PNG"
  end
end