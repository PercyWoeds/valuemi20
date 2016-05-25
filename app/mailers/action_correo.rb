class ActionCorreo < ApplicationMailer
  	  default from: 'factura-electronica@hidrotransp.com'


	  def bienvenido_email(invoice)
		  @invoices=invoice 			
		   @url  = 'http://www.apple.com'
		  #attachments["Factura"] = File.read("#{$lcFileName1}")
		  #attachments['Factura'] = File.read($lcFileName1)
		  
		  attachments['Factura.pdf'] =  open($lcFileName1).read

		  mail(to: $lcMail, subject: 'Factura Electrónica : '+$lcFileNameIni )
	  end
end
