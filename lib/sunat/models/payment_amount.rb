module SUNAT

  class PaymentAmount
    include Model

    # Money amounts always in lowest common denominator, so integer only!
    property :value,    Integer
    property :currency, String, default:'PEN'
    
    # in the xml, the currency must me in the format: [0-9]+.[0-9]{2}
    validates :currency, currency_code: true
    
    attr_accessor :xml_namespace

    def initialize(*args)
      case args.first
      when String, Integer
        super(value:args.first.to_i)
      else
        super(*args)
      end
    end
    
    def build_xml(xml, tag_name)
      xml[xml_namespace].send(tag_name, { currencyID: currency }, to_s)
    end
    
    def int_part
      value / 100
    end

    def cents_part
      value % 100
    end

    def to_s
      sprintf "%.2f", (value / 100.0)
    end

    def textify
      Helpers::textify(self)
    end

    def to_i
      self.to_s.to_i
    end

    def to_f
      self.to_s.to_f
    end

    def +(payment) 
      if payment.currency != currency
        raise "You can only sum payments with the same currency"
      else
        PaymentAmount.new(currency: currency, value: payment.value.to_i + value.to_i)
      end
    end
    
    def xml_namespace
      @xml_namespace ||= 'cbc'
    end
    
    class << self
      def [](value, currency = "PEN")
        self.new.tap do |payment|
          payment.value = value
          payment.currency = currency
        end
      end
    end
  end
end
