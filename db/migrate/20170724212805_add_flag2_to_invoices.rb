class AddFlag2ToInvoices < ActiveRecord::Migration
  def change
    add_column :invoices, :flag2, :string
    
  end
end
