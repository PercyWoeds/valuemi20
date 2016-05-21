
$lg_serial_id ='1055'
@serie='FF01'
@@document_serial_id=$lg_serial_id 

$lcid= "#{@serie}-#{"%06d" % $lg_serial_id }"
puts $lcid
