module SUNAT 
 
  # Generate a new line for an invoice.
  #
  # Helpers are included to make the process a little easier.
  #
  # To build a new item quickly, provide the following non-standard attributes
  # when instantiating the object. They will be used to automatically generate
  # the complex structure used in the XML.
  #
  #  * :code - the item id (optional)
  #  * :description - name of product
  #  * :quantity - converted to invoiced_quantity
  #  * :unit - currently either :product, :service, or string from UN/ECE (optional, default is :product)
  #  * :price - the price charged of a single item without taxes, either as intenger for PEN, or PaymentAmount model.
  #  * :total - effectively price x quantity, minus any discounts!
  #  * :list_price - special unit price of item including tax (see below)
  #
  # The list price requires a special "PriceTypeCode" as per Catalog 16. (See annex.) By default
  # this will automatically be set to type '01', for regular prices. If however this item is included
  # for free, provide the list price as a hash as follows:
  #
  #    :list_price => {:amount => 12345, :free => true}
  #
  # or directly:
  #
  #    line.pricing_reference = {:amount => 12345, :free => true} 
  #
  # See the PricingReference model for more details.
  #
  # Handling taxes is also complicated, see the TaxTotal model
  # for helper method details.
  #
  class InvoiceLine
    include Model
    include HasTaxTotals
    
    property :id,                     String
    property :quantity,               Quantity
    property :line_extension_amount,  PaymentAmount    # total
    property :price,                  PaymentAmount    # price
    property :pricing_reference,      PricingReference # list price with tax
    property :item,                   Item
    property :tax_totals,             [TaxTotal]
    
    validates :id, :quantity, :line_extension_amount, :price, :pricing_reference, :tax_totals, :item, presence: true
    
    KNOWN_UNIT_CODES = {
      :product => "GLL",
      :service => "ZZ"
    }

    TABLE_HEADERS = ["ITEM",
                     "CANTIDAD",
                     "UM",
                     "DESCRIPCION",
                     "PRECIO  UNITARIO",
                     "VALOR TOTAL"]

    def initialize(*args)
      self.tax_totals ||=[]
      super(parse_attributes(*args))
    end

    def xml_line_id
      :InvoiceLine
    end

    def xml_quantity
      :InvoicedQuantity
    end

    def add_tax_total(tax_name, amount, code=nil)      
      
      opts = {
        :amount => amount,
        :type => tax_name,
      }

      opts[:code] = code if code

      tax_total = TaxTotal.new(opts)

      tax_totals << tax_total
    end

    def calculate_tax_total
      result = 0
      tax_totals.each do |total|
        result += total.tax_amount.value
      end
      result
    end

    def calculate_tax_sub_total
      result = 0

      tax_totals.each do |total|
        result += total.sub_total
      end

      result
    end

    def build_pdf_table_row(pdf)
      row = []
      row << self.id
      row << self.quantity.quantity
      row << self.quantity.unit_code
      row << "#{self.item.description} "
      row << "#{self.pricing_reference.alternative_condition_price.price_amount.to_s}"
      row << "#{self.line_extension_amount.to_s}"
    end

    def build_xml(xml)
      xml['cac'].send(xml_line_id) do
        xml['cbc'].ID id
        quantity.build_xml(xml, xml_quantity) if quantity.present?
        line_extension_amount.build_xml(xml, :LineExtensionAmount) if line_extension_amount.present?
        pricing_reference.build_xml(xml) if pricing_reference.present?
        unless tax_totals.nil?
          tax_totals.each do |tax_total|
            tax_total.build_xml(xml)
          end
        end
        item.build_xml(xml) unless item.nil?
        xml['cac'].Price do
          price.build_xml xml, :PriceAmount
        end
      end
    end

    protected

    def parse_attributes(attrs = {})
      handle_item(attrs)
      handle_quantity(attrs)
      handle_total(attrs)
      handle_list_price(attrs)
      attrs
    end
    
    private

    def handle_item(attrs)
      desc = attrs.delete(:description)
      if desc
        self.item = {
          description: desc,
          id: attrs.delete(:code)
        }
      end
    end

    def handle_quantity(attrs)
      qty  = attrs.delete(:quantity)
      unit = attrs.delete(:unit)
      if qty
        code = if unit.is_a?(Symbol) and real_code = KNOWN_UNIT_CODES[unit]
          real_code
        else
          unit
        end
        self.quantity = {quantity:qty, unit_code:code}
      end
    end

    def handle_total(attrs)
      price = attrs.delete(:total)
      if price
        self.line_extension_amount = price
      end
    end

    # Essentially all this does is forward list_price to the 
    # pricing_reference property.
    def handle_list_price(attrs)
      price = attrs.delete(:list_price)
      if price
        self.pricing_reference = price
      end
    end
   
  end
end
