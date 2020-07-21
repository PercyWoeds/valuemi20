module SUNAT
  
  class PostalAddress
    include Model
    
    property :id,                     String # ubigeo
    property :street_name,            String # full address
    property :city_subdivision_name,  String # urbanization or zone
    property :city_name,              String # department
    property :country_subentity,      String # province
    property :district,               String # district
    property :country,                Country
    
    def build_xml(xml)
      xml['cac'].PostalAddress do
        xml['cbc'].ID                   id
        xml['cbc'].StreetName           street_name
        xml['cbc'].CitySubdivisionName  city_subdivision_name
        xml['cbc'].CityName             city_name
        xml['cbc'].CountrySubentity     country_subentity
        xml['cbc'].District             district
        
        country.build_xml xml if country.present?
      end
    end

    def to_s
      address = [street_name, city_subdivision_name, city_name, country_subentity, district].select(&:present?).join(', ')
      address += ", #{country.identification_code}" if country.present?
      address 
    end
  end
end