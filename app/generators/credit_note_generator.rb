require_relative 'document_generator'

class CreditNoteGenerator < DocumentGenerator
  
  def initialize(group, group_case, serie)
    super(group, group_case)
    @serie = serie
  end

  def for_igv_document(associated_document, pdf=false)
    generate_document_for_line(:first, associated_document, pdf)

  end

  def for_exempt_document(associated_document, pdf=false)
    generate_document_for_line(:last, associated_document, pdf)
  end

  def for_discount_invoice(associated_document, pdf=false)
    line = associated_document.lines.first
    credit_note = SUNAT::CreditNote.new(credit_note_data_for_line(line, associated_document))
    
    taxable_total = credit_note.get_monetary_total_by_id("1001")
    discount = (taxable_total.payable_amount.value * 0.05).round
    taxable_total.payable_amount = taxable_total.payable_amount.value - discount
    credit_note.add_additional_monetary_total({id: "2005", payable_amount: discount})
    credit_note.modify_monetary_total(taxable_total)
    
    new_tax_totals = {amount: (taxable_total.payable_amount.to_f * 18).round, type: :igv}
    credit_note.legal_monetary_total = new_tax_totals[:amount] + taxable_total.payable_amount.value
    credit_note.tax_totals = [new_tax_totals]
    
    generate_documents(credit_note, pdf)
    credit_note
  end

  def for_isc_document(associated_document, pdf=false)
    generate_document_for_line(:last, associated_document, pdf)
  end

  def for_reception_document(associated_document, pdf=false)
    generate_document_for_line(:first, associated_document, pdf)
  end

  def for_different_currency_document(associated_document, pdf=false)
    generate_document_for_line(:first, associated_document, pdf)
  end

  private 

  def generate_document_for_line(line_position, associated_document, pdf)
    line = associated_document.lines.send(line_position)
    credit_note = SUNAT::CreditNote.new(credit_note_data_for_line(line, associated_document))
    generate_documents(credit_note, pdf)
    credit_note
  end

  def credit_note_data_for_line(line, associated_document)
    legal_monetary_total = line.line_extension_amount.value + line.tax_totals.inject(0){|sum, tax| sum + tax.tax_amount.value}
    credit_note_data = {id: "#{@serie}-#{"%03d" % @@document_serial_id}", customer: associated_document.customer,
                       billing_reference: {id: associated_document.id, document_type_code: TYPES[associated_document.class.name]},
                        discrepancy_response: {reference_id: associated_document.id, response_code: "09", description: "POR AJUSTE DE PRECIO"},
                        lines: [{id: "1", quantity: line.quantity.quantity, unit: 'GLL', item: line.item,
                          price: line.price, pricing_reference: line.pricing_reference, tax_totals: line.tax_totals, line_extension_amount: line.line_extension_amount}],
                        additional_monetary_totals: [{id: "1001", payable_amount: line.price}], tax_totals: line.tax_totals, 
                        legal_monetary_total: {value: legal_monetary_total, currency: associated_document.document_currency_code}}


    @@document_serial_id += 1
    credit_note_data  
    
if credit_note.valid?
  File::open("credit_note.xml", "w") { |file| file.write(credit_note.to_xml) }
  credit_note.to_pdf
else
  puts "Invalid document, ignoring output: #{credit_note.errors.messages}"
end

  end
end