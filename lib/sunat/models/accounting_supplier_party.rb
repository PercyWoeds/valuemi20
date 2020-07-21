module SUNAT

  # The AccountingSupplierParty contains the basic details of the supplier of
  # the current invoice being generated.
  #
  # Rather then being forced to use the complex XML structure, helper attributes
  # can be provided to quickly generate a usable object. If necessary, attributes
  # will be forwarded to a party object. The following fields are supported:
  #
  # Basic fields:
  #
  #  * name - name of the company
  #  * ruc  - Peruvian SUNAT ID
  #  * or dni - National ID number, for exports
  #
  # Address fields:
  #
  #  * address_id - Geographical location (UBIGEO) or post code, eg. "070101"
  #  * street - eg. "Calle Los Olivos 234"
  #  * zone - Urbanization o Zone, eg. "Callao"
  #  * city - eg. "Lima"
  #  * province
  #  * district - eg. "Callao"
  #  * country - use 2 digit identification code, like 'PE' or 'ES'
  #

  class AccountingSupplierParty
    include Model

    DOCUMENT_TYPES_DATA = SUNAT::ANNEX::CATALOG_06

    property :account_id,             String
    property :additional_account_id,  String, :default => DOCUMENT_TYPES_DATA[:ruc]
    property :party,                  Party
    property :logo_path,              String

    validates :account_id, existence: true, presence: true
    validates :account_id, ruc_document: true, if: Proc.new { |supplier| supplier.additional_account_id == DOCUMENT_TYPES_DATA[:ruc] }
    validates :additional_account_id, existence: true, document_type_code: true, if: Proc.new { |supplier| supplier.additional_account_id != '-' }

    def initialize(*attrs)
      super(parse_attributes(*attrs))
    end

    def build_xml(xml)
      xml['cac'].AccountingSupplierParty do
        build_xml_payload(xml)
      end
    end

    def type_as_text
      DOCUMENT_TYPES_DATA.key(additional_account_id).to_s.upcase
    end

    protected

    def build_xml_payload(xml)
      # IMPORTANT: We don't know how to handle the case
      # when there is no dni. We are assuming that, because
      # sunat said that the fields are required, the the dni
      # field must be empty string when nil.
      xml['cbc'].CustomerAssignedAccountID  (account_id || '')
      xml['cbc'].AdditionalAccountID        additional_account_id

      party.build_xml xml
    end

    def parse_attributes(attrs = {})
      # Perform basic id and name handling
      kind_of_documents = DOCUMENT_TYPES_DATA.keys
      document_type = kind_of_documents.find { |kind| attrs[kind].present? }

      legal_name  = attrs.delete(:legal_name) || '-'
      name        = attrs.delete(:name) || attrs.delete(:legal_name)

      if (document_type)
        # Special case! Try set the properties accordingly.
        self.additional_account_id = DOCUMENT_TYPES_DATA[document_type]
        self.account_id = attrs.delete(document_type)
      else
        # For facturas, boletas and notas de default values for the
        # account info is '-'
        self.additional_account_id = '-'
        self.account_id = '-'
      end

      self.party = { party_legal_entity: {registration_name: legal_name}, name: name }

      # Grab or build new party
      self.party ||= (attrs.delete(:party) || {})

      # Then try set the details
      addr_keys = [:address_id, :street, :zone, :city, :province, :district, :country]
      if party.postal_addresses.empty? && !(attrs.keys & addr_keys).empty?
        pa = PostalAddress.new({
          :id                    => attrs.delete(:address_id),
          :street_name           => attrs.delete(:street),
          :city_subdivision_name => attrs.delete(:zone),
          :city_name             => attrs.delete(:city),
          :country_subentity     => attrs.delete(:province),
          :district              => attrs.delete(:district),
        })
        if attrs.has_key?(:country)
          pa.country = {
            :identification_code => attrs.delete(:country)
          }
        end
        party.postal_addresses << pa
      end

      attrs
    end
  end
end
