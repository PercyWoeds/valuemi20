SUNAT.configure do |config|
  config.credentials do |c|


    c.ruc       = "20517308367"
    c.username  = "FACTURA2"
    c.password  = "NORMA2018"
   end

  config.signature do |s|
    s.party_id    = "20517308367"
    s.party_name  = "INVERSIONES NOBAL S.A.C."
    s.cert_file   = File.join(Dir.pwd, './app/keys', 'certificate4.crt')
    s.pk_file     = File.join(Dir.pwd, './app/keys', 'CERTIFICADO4.key') 
  end

  config.supplier do |s|
    s.legal_name = "INVERSIONES NOBAL  S.A.C."
    s.name       = "NOEL BAUTISTA "
    s.ruc        = "20517308367"
    s.address_id = "150140"
    s.street     = "JR. MONTE FICUS NRO. 151 PROLONGACION BENAVIDES (ALT CDRA 32 DE AV.CAMINOS DEL INCA) "
    s.district   = "LIMA - LIMA - SANTIAGO DE SURCO"
    s.city       = "LIMA"
    s.country    = "PE"
    s.logo_path  = "#{Dir.pwd}/app/assets/images/logo3.jpg"
  
  end
end

