class ActionCorreo < ApplicationMailer
<<<<<<< HEAD
  	  default from: 'factura-electronica@hidrotransp.com'
=======
  	  default from: 'factura-electronica@codiscom.com'
>>>>>>> 78a73d75881b3f03cd1267ae4e5b28d4653ee4f8


	  def bienvenido_email(invoice)
		  @invoices=invoice 			
		   @url  = 'http://www.apple.com'
		  #attachments["Factura"] = File.read("#{$lcFileName1}")
		  #attachments['Factura'] = File.read($lcFileName1)
<<<<<<< HEAD

		  email_with_name = "Factura Enviada <factura-electronica@hidrotransp.com>"	
		  email_with_copy = "Administracion <zportal@hidrotransp.com>"	
=======
		  email_with_name = "Factura Enviada <factura-electronica@codiscom.com>"	
		  email_with_copy = "Factura Enviada <administracion@codiscom.com>"	

>>>>>>> 78a73d75881b3f03cd1267ae4e5b28d4653ee4f8
		  attachments[$lcFileName] =  open($lcFileName1).read

		  attachments[$lcFilezip] =  open($lcFile2).read

<<<<<<< HEAD
		  mail(to: [$lcMail,$lcMail2,$lcMail3], cc: email_with_copy,   bcc:email_with_name, subject: 'Factura Electrónica : '+$lcFileNameIni )
=======
		  mail(to: [$lcMail,$lcMail2,$lcMail3],cc:email_with_copy ,  bcc:email_with_name, subject: 'Factura Electrónica : '+$lcFileNameIni )
>>>>>>> 78a73d75881b3f03cd1267ae4e5b28d4653ee4f8


	  end
end
