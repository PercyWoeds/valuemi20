class AddFlag1ToInvoice < ActiveRecord::Migration
  def change
    add_column :invoices, :flag1, :string
  end
end
