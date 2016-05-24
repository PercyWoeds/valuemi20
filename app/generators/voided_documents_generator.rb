require_relative 'document_generator'


class VoidedDocumentsGenerator < DocumentGenerator

  def initialize()
    super("voided documents", 0)
  end

  def generate
    issue_date = Date.new(2016,05,21)

    correlative_number = "079"

    voided_documents_data = {reference_date: Date.new(2016,05,21), issue_date: issue_date, id: SUNAT::VoidedDocuments.generate_id(issue_date, correlative_number), correlative_number: correlative_number,
                         lines:[{line_id: "1", document_type_code: "01", document_serial_id: "FF01", document_number_id: "1054", void_reason: "Error en datos consignados" }]}

    voided_document = SUNAT::VoidedDocuments.new(voided_documents_data)

    generate_documents(voided_document)
    voided_document

    File::open("voided_document.xml", "w") { |file| file.write(voided_document.to_xml) }
    voided_document.to_pdf
  end

  def generate_documents(document)
    if document.valid?
      ticket = document.deliver!
      document_status = ticket.get_status
      while document_status.in_process?
        document_status = ticket.get_status
      end
      if document_status.error?
        file_name = "voided_document_error.zip"
        document_status.save_content_to(file_name)
        puts "Voided Document wasn't generated succesfully, check #{file_name} to see why"
      end
    else
      raise "Invalid voided document: #{document.errors}"
    end
  end
end