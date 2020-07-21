module SUNAT

  class Quantity
    include Model

    property :quantity,   Float
    property :unit_code,  String, default: $unidad_medida #it codes as defined in UN/ECE rec 20

    def build_xml(xml, tag_name)
      xml['cbc'].send(tag_name, { unitCode: unit_code }, quantity)
    end
  end
end
